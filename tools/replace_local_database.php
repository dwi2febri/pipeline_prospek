<?php

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';
$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$config = config('database.connections.'.config('database.default'));
if (($config['driver'] ?? '') !== 'mysql' || !in_array($config['host'] ?? '', ['127.0.0.1', 'localhost', '::1'], true)) {
    throw new RuntimeException('Penggantian hanya diizinkan pada database MySQL lokal.');
}
$database = $config['database'];
if (!preg_match('/^[a-zA-Z0-9_]+$/', $database)) throw new RuntimeException('Nama database tidak valid.');
$pdo = new PDO('mysql:host='.$config['host'].';port='.$config['port'].';charset=utf8mb4', $config['username'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
function qi(string $name): string { return '`'.str_replace('`', '``', $name).'`'; }
function tables(PDO $pdo, string $database): array {
    $q = $pdo->prepare('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = "BASE TABLE" ORDER BY TABLE_NAME');
    $q->execute([$database]);
    return $q->fetchAll(PDO::FETCH_COLUMN);
}
function counts(PDO $pdo, string $database): array {
    $result = [];
    foreach (tables($pdo, $database) as $table) $result[$table] = (int) $pdo->query('SELECT COUNT(*) FROM '.qi($database).'.'.qi($table))->fetchColumn();
    return $result;
}
$mode = $argv[1] ?? '';
if ($mode === 'prepare') {
    $dump = $argv[2] ?? '';
    if (!is_file($dump)) throw new RuntimeException('Dump tidak ditemukan.');
    $tag = date('Ymd_His');
    $directory = storage_path('app/private/database-replacement-'.$tag);
    if (!mkdir($directory, 0700, true)) throw new RuntimeException('Folder backup gagal dibuat.');
    $stage = '_eprospek_import_'.$tag;
    $state = ['target' => $database, 'stage' => $stage, 'previous' => '_eprospek_previous_'.$tag, 'dump' => realpath($dump), 'directory' => $directory, 'before_counts' => counts($pdo, $database)];
    $options = $directory.'/client.cnf';
    $quote = static fn ($v) => '"'.str_replace(['\\', '"', "\r", "\n"], ['\\\\', '\\"', '', ''], (string) $v).'"';
    file_put_contents($options, "[client]\nhost=".$quote($config['host'])."\nport=".(int) $config['port']."\nuser=".$quote($config['username'])."\npassword=".$quote($config['password'])."\ndefault-character-set=utf8mb4\n");
    $bin = 'C:/laragon/bin/mysql/mysql-8.0.40-winx64/bin/';
    $run = static function (array $command, string $phase, ?string $input = null) use ($directory): void {
        $descriptors = [0 => $input ? ['file', $input, 'r'] : ['pipe', 'r'], 1 => ['file', $directory.'/'.$phase.'.out', 'w'], 2 => ['file', $directory.'/'.$phase.'.error', 'w']];
        $process = proc_open($command, $descriptors, $pipes);
        if (!is_resource($process)) throw new RuntimeException('Proses '.$phase.' gagal dimulai.');
        if (isset($pipes[0])) fclose($pipes[0]);
        if (proc_close($process) !== 0) throw new RuntimeException('Proses '.$phase.' gagal; lihat log di folder backup.');
    };
    try {
        $run([$bin.'mysqldump.exe', '--defaults-extra-file='.$options, '--single-transaction', '--skip-lock-tables', '--no-tablespaces', '--column-statistics=0', '--result-file='.$directory.'/before.sql', $database], 'backup');
        if (filesize($directory.'/before.sql') < 100) throw new RuntimeException('Backup kosong.');
        $pdo->exec('CREATE DATABASE '.qi($stage).' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        $run([$bin.'mysql.exe', '--defaults-extra-file='.$options, '--max-allowed-packet=256M', $stage], 'import', $dump);
    } finally {
        if (is_file($options)) unlink($options);
    }
    $state['import_counts'] = counts($pdo, $stage);
    $q = $pdo->prepare('SELECT TABLE_NAME,COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=?');
    $q->execute([$database]); $old = $q->fetchAll(PDO::FETCH_ASSOC);
    $q->execute([$stage]); $new = $q->fetchAll(PDO::FETCH_ASSOC);
    $columns = static fn ($rows) => array_map(fn ($row) => $row['TABLE_NAME'].'.'.$row['COLUMN_NAME'], $rows);
    $state['local_only_columns'] = array_values(array_diff($columns($old), $columns($new)));
    $state['import_only_columns'] = array_values(array_diff($columns($new), $columns($old)));
    file_put_contents($directory.'/state.json', json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    echo json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL;
} elseif ($mode === 'apply') {
    $stateFile = realpath($argv[2] ?? '');
    $root = realpath(storage_path('app/private'));
    if (!$stateFile || !str_starts_with($stateFile, $root.DIRECTORY_SEPARATOR)) throw new RuntimeException('State harus berada dalam folder backup aplikasi.');
    $state = json_decode(file_get_contents($stateFile), true, flags: JSON_THROW_ON_ERROR);
    if ($state['target'] !== $database || isset($state['applied_at'])) throw new RuntimeException('Target tidak sesuai atau data sudah diterapkan.');
    if (counts($pdo, $database) !== $state['before_counts']) throw new RuntimeException('Data lokal berubah setelah backup; buat backup baru.');
    if (counts($pdo, $state['stage']) !== $state['import_counts']) throw new RuntimeException('Data staging berubah.');
    $pdo->exec('CREATE DATABASE '.qi($state['previous']).' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $moves = [];
    foreach (tables($pdo, $database) as $table) $moves[] = qi($database).'.'.qi($table).' TO '.qi($state['previous']).'.'.qi($table);
    foreach (tables($pdo, $state['stage']) as $table) $moves[] = qi($state['stage']).'.'.qi($table).' TO '.qi($database).'.'.qi($table);
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
    try { $pdo->exec('RENAME TABLE '.implode(', ', $moves)); } finally { $pdo->exec('SET FOREIGN_KEY_CHECKS=1'); }
    if (counts($pdo, $database) !== $state['import_counts']) throw new RuntimeException('Jumlah baris hasil penggantian tidak sesuai.');
    $state['applied_at'] = date(DATE_ATOM);
    file_put_contents($stateFile, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    echo json_encode(['target' => $database, 'counts' => $state['import_counts'], 'backup' => $state['directory'].'/before.sql', 'previous_database' => $state['previous']], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL;
} else {
    throw new RuntimeException('Gunakan prepare <dump.sql> atau apply <state.json>.');
}
