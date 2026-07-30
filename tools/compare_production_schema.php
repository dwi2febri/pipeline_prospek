<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = require dirname(__DIR__) . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$dumpPath = $argv[1] ?? '';
$outputDirectory = $argv[2] ?? (dirname(__DIR__) . '/database/production');

if ($dumpPath === '' || !is_file($dumpPath)) {
    fwrite(STDERR, "SQL dump tidak ditemukan.\n");
    exit(1);
}

if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0775, true) && !is_dir($outputDirectory)) {
    fwrite(STDERR, "Direktori output tidak dapat dibuat.\n");
    exit(1);
}

$connection = config('database.default');
$dbConfig = config("database.connections.{$connection}");

if (($dbConfig['driver'] ?? '') !== 'mysql') {
    fwrite(STDERR, "Tool ini hanya mendukung koneksi MySQL/MariaDB.\n");
    exit(1);
}

$localDatabase = (string) ($dbConfig['database'] ?? '');
if ($localDatabase === '' || !preg_match('/^[A-Za-z0-9_]+$/', $localDatabase)) {
    fwrite(STDERR, "Nama database lokal tidak valid.\n");
    exit(1);
}

$dsn = sprintf(
    'mysql:host=%s;port=%s;charset=%s',
    $dbConfig['host'] ?? '127.0.0.1',
    $dbConfig['port'] ?? '3306',
    $dbConfig['charset'] ?? 'utf8mb4'
);

$pdo = new PDO(
    $dsn,
    (string) ($dbConfig['username'] ?? ''),
    (string) ($dbConfig['password'] ?? ''),
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);

$temporaryDatabase = '_codex_schema_compare_' . getmypid();
if (!preg_match('/^_codex_schema_compare_[0-9]+$/', $temporaryDatabase)) {
    throw new RuntimeException('Nama schema sementara tidak valid.');
}

function quoteIdentifier(string $identifier): string
{
    return '`' . str_replace('`', '``', $identifier) . '`';
}

function loadSchema(PDO $pdo, string $database): array
{
    $tableStatement = $pdo->prepare(
        "SELECT TABLE_NAME, ENGINE, TABLE_COLLATION
         FROM information_schema.TABLES
         WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE'
         ORDER BY TABLE_NAME"
    );
    $tableStatement->execute([$database]);

    $tables = [];
    foreach ($tableStatement->fetchAll() as $row) {
        $tables[$row['TABLE_NAME']] = [
            'engine' => $row['ENGINE'],
            'collation' => $row['TABLE_COLLATION'],
        ];
    }

    $columnStatement = $pdo->prepare(
        "SELECT TABLE_NAME, COLUMN_NAME, ORDINAL_POSITION, COLUMN_DEFAULT, IS_NULLABLE,
                COLUMN_TYPE, EXTRA, CHARACTER_SET_NAME, COLLATION_NAME, COLUMN_COMMENT
         FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = ?
         ORDER BY TABLE_NAME, ORDINAL_POSITION"
    );
    $columnStatement->execute([$database]);

    $columns = [];
    foreach ($columnStatement->fetchAll() as $row) {
        $columns[$row['TABLE_NAME']][$row['COLUMN_NAME']] = $row;
    }

    $indexStatement = $pdo->prepare(
        "SELECT TABLE_NAME, INDEX_NAME, NON_UNIQUE, SEQ_IN_INDEX, COLUMN_NAME,
                SUB_PART, COLLATION, INDEX_TYPE
         FROM information_schema.STATISTICS
         WHERE TABLE_SCHEMA = ?
         ORDER BY TABLE_NAME, INDEX_NAME, SEQ_IN_INDEX"
    );
    $indexStatement->execute([$database]);

    $indexes = [];
    foreach ($indexStatement->fetchAll() as $row) {
        $indexes[$row['TABLE_NAME']][$row['INDEX_NAME']]['non_unique'] = (int) $row['NON_UNIQUE'];
        $indexes[$row['TABLE_NAME']][$row['INDEX_NAME']]['type'] = $row['INDEX_TYPE'];
        $indexes[$row['TABLE_NAME']][$row['INDEX_NAME']]['columns'][] = [
            'name' => $row['COLUMN_NAME'],
            'sub_part' => $row['SUB_PART'],
            'collation' => $row['COLLATION'],
        ];
    }

    $foreignStatement = $pdo->prepare(
        "SELECT k.TABLE_NAME, k.CONSTRAINT_NAME, k.COLUMN_NAME, k.ORDINAL_POSITION,
                k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME,
                r.UPDATE_RULE, r.DELETE_RULE
         FROM information_schema.KEY_COLUMN_USAGE k
         JOIN information_schema.REFERENTIAL_CONSTRAINTS r
           ON r.CONSTRAINT_SCHEMA = k.CONSTRAINT_SCHEMA
          AND r.TABLE_NAME = k.TABLE_NAME
          AND r.CONSTRAINT_NAME = k.CONSTRAINT_NAME
         WHERE k.CONSTRAINT_SCHEMA = ? AND k.REFERENCED_TABLE_NAME IS NOT NULL
         ORDER BY k.TABLE_NAME, k.CONSTRAINT_NAME, k.ORDINAL_POSITION"
    );
    $foreignStatement->execute([$database]);

    $foreignKeys = [];
    foreach ($foreignStatement->fetchAll() as $row) {
        $foreignKeys[$row['TABLE_NAME']][$row['CONSTRAINT_NAME']]['referenced_table'] = $row['REFERENCED_TABLE_NAME'];
        $foreignKeys[$row['TABLE_NAME']][$row['CONSTRAINT_NAME']]['update_rule'] = $row['UPDATE_RULE'];
        $foreignKeys[$row['TABLE_NAME']][$row['CONSTRAINT_NAME']]['delete_rule'] = $row['DELETE_RULE'];
        $foreignKeys[$row['TABLE_NAME']][$row['CONSTRAINT_NAME']]['columns'][] = $row['COLUMN_NAME'];
        $foreignKeys[$row['TABLE_NAME']][$row['CONSTRAINT_NAME']]['referenced_columns'][] = $row['REFERENCED_COLUMN_NAME'];
    }

    return compact('tables', 'columns', 'indexes', 'foreignKeys');
}

function canonicalColumn(array $column): array
{
    $extra = strtolower((string) ($column['EXTRA'] ?? ''));
    $extra = trim(str_replace('default_generated', '', $extra));

    return [
        'type' => strtolower((string) $column['COLUMN_TYPE']),
        'nullable' => strtoupper((string) $column['IS_NULLABLE']),
        'default' => $column['COLUMN_DEFAULT'],
        'extra' => preg_replace('/\s+/', ' ', $extra),
        'charset' => strtolower((string) ($column['CHARACTER_SET_NAME'] ?? '')),
        'collation' => strtolower((string) ($column['COLLATION_NAME'] ?? '')),
        'comment' => (string) ($column['COLUMN_COMMENT'] ?? ''),
    ];
}

function canonicalIndex(array $index): array
{
    return [
        'non_unique' => (int) $index['non_unique'],
        'type' => strtoupper((string) $index['type']),
        'columns' => array_map(
            fn (array $column): array => [
                'name' => $column['name'],
                'sub_part' => $column['sub_part'] === null ? null : (int) $column['sub_part'],
                'collation' => $column['collation'],
            ],
            $index['columns']
        ),
    ];
}

function canonicalForeignKey(array $foreignKey): array
{
    return [
        'columns' => array_values($foreignKey['columns']),
        'referenced_table' => $foreignKey['referenced_table'],
        'referenced_columns' => array_values($foreignKey['referenced_columns']),
        'update_rule' => strtoupper((string) $foreignKey['update_rule']),
        'delete_rule' => strtoupper((string) $foreignKey['delete_rule']),
    ];
}

function showCreateTable(PDO $pdo, string $database, string $table): string
{
    $statement = $pdo->query(
        'SHOW CREATE TABLE ' . quoteIdentifier($database) . '.' . quoteIdentifier($table)
    );
    $row = $statement->fetch(PDO::FETCH_NUM);

    if (!$row || !isset($row[1])) {
        throw new RuntimeException("SHOW CREATE TABLE gagal untuk {$table}.");
    }

    return (string) $row[1];
}

function extractColumnDefinition(string $createSql, string $column): string
{
    $pattern = '/^\s*`' . preg_quote($column, '/') . '`\s+(.+?)(?:,)?\s*$/mi';
    if (!preg_match($pattern, $createSql, $matches)) {
        throw new RuntimeException("Definisi kolom {$column} tidak ditemukan.");
    }

    return rtrim(trim($matches[1]), ',');
}

function renderIndexColumns(array $columns): string
{
    return implode(', ', array_map(function (array $column): string {
        $name = quoteIdentifier((string) $column['name']);
        if ($column['sub_part'] !== null) {
            $name .= '(' . (int) $column['sub_part'] . ')';
        }
        if (($column['collation'] ?? null) === 'D') {
            $name .= ' DESC';
        }
        return $name;
    }, $columns));
}

function executeSqlScript(PDO $pdo, string $sql): void
{
    $buffer = '';

    foreach (preg_split('/\R/', $sql) as $line) {
        $buffer .= $line . PHP_EOL;
        if (!preg_match('/;\s*$/', rtrim($line))) {
            continue;
        }

        if (trim(preg_replace('/^\s*--.*$/m', '', $buffer)) !== '') {
            $pdo->exec($buffer);
        }
        $buffer = '';
    }

    if (trim(preg_replace('/^\s*--.*$/m', '', $buffer)) !== '') {
        throw new RuntimeException('Ada statement SQL upgrade yang tidak diakhiri titik koma.');
    }
}

$createdStatements = 0;
$alterStatements = 0;

try {
    $pdo->exec('DROP DATABASE IF EXISTS ' . quoteIdentifier($temporaryDatabase));
    $pdo->exec(
        'CREATE DATABASE ' . quoteIdentifier($temporaryDatabase)
        . ' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
    );
    $pdo->exec('USE ' . quoteIdentifier($temporaryDatabase));
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');

    $handle = fopen($dumpPath, 'rb');
    if ($handle === false) {
        throw new RuntimeException('SQL dump tidak dapat dibuka.');
    }

    $capturing = false;
    $statement = '';
    $statementType = '';

    while (($line = fgets($handle)) !== false) {
        if (!$capturing) {
            $trimmed = ltrim($line);
            if (preg_match('/^(CREATE TABLE|ALTER TABLE)\s+`/i', $trimmed, $matches)) {
                $capturing = true;
                $statement = $line;
                $statementType = strtoupper($matches[1]);
            }
        } else {
            $statement .= $line;
        }

        if ($capturing && preg_match('/;\s*$/', rtrim($line))) {
            $pdo->exec($statement);
            if ($statementType === 'CREATE TABLE') {
                $createdStatements++;
            } else {
                $alterStatements++;
            }
            $capturing = false;
            $statement = '';
            $statementType = '';
        }
    }
    fclose($handle);

    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

    $localSchema = loadSchema($pdo, $localDatabase);
    $productionSchema = loadSchema($pdo, $temporaryDatabase);

    $localTables = array_keys($localSchema['tables']);
    $productionTables = array_keys($productionSchema['tables']);

    $missingTables = array_values(array_diff($localTables, $productionTables));
    $productionOnlyTables = array_values(array_diff($productionTables, $localTables));
    sort($missingTables);
    sort($productionOnlyTables);

    $missingColumns = [];
    $productionOnlyColumns = [];
    $changedColumns = [];
    $missingIndexes = [];
    $changedIndexes = [];
    $missingForeignKeys = [];
    $changedForeignKeys = [];

    foreach (array_values(array_intersect($localTables, $productionTables)) as $table) {
        $localColumns = $localSchema['columns'][$table] ?? [];
        $productionColumns = $productionSchema['columns'][$table] ?? [];

        foreach (array_diff(array_keys($localColumns), array_keys($productionColumns)) as $column) {
            $missingColumns[$table][] = $column;
        }
        foreach (array_diff(array_keys($productionColumns), array_keys($localColumns)) as $column) {
            $productionOnlyColumns[$table][] = $column;
        }
        foreach (array_intersect(array_keys($localColumns), array_keys($productionColumns)) as $column) {
            $localDefinition = canonicalColumn($localColumns[$column]);
            $productionDefinition = canonicalColumn($productionColumns[$column]);
            if ($localDefinition !== $productionDefinition) {
                $changedColumns[$table][$column] = [
                    'local' => $localDefinition,
                    'production' => $productionDefinition,
                ];
            }
        }

        $localIndexes = $localSchema['indexes'][$table] ?? [];
        $productionIndexes = $productionSchema['indexes'][$table] ?? [];
        foreach (array_diff(array_keys($localIndexes), array_keys($productionIndexes)) as $index) {
            $missingIndexes[$table][$index] = $localIndexes[$index];
        }
        foreach (array_intersect(array_keys($localIndexes), array_keys($productionIndexes)) as $index) {
            if (canonicalIndex($localIndexes[$index]) !== canonicalIndex($productionIndexes[$index])) {
                $changedIndexes[$table][$index] = [
                    'local' => canonicalIndex($localIndexes[$index]),
                    'production' => canonicalIndex($productionIndexes[$index]),
                ];
            }
        }

        $localForeignKeys = $localSchema['foreignKeys'][$table] ?? [];
        $productionForeignKeys = $productionSchema['foreignKeys'][$table] ?? [];
        foreach (array_diff(array_keys($localForeignKeys), array_keys($productionForeignKeys)) as $foreignKey) {
            $missingForeignKeys[$table][$foreignKey] = $localForeignKeys[$foreignKey];
        }
        foreach (array_intersect(array_keys($localForeignKeys), array_keys($productionForeignKeys)) as $foreignKey) {
            if (canonicalForeignKey($localForeignKeys[$foreignKey]) !== canonicalForeignKey($productionForeignKeys[$foreignKey])) {
                $changedForeignKeys[$table][$foreignKey] = [
                    'local' => canonicalForeignKey($localForeignKeys[$foreignKey]),
                    'production' => canonicalForeignKey($productionForeignKeys[$foreignKey]),
                ];
            }
        }
    }

    $sql = [];
    $sql[] = '-- Upgrade schema production E-Prospek';
    $sql[] = '-- Sumber production: ' . basename($dumpPath);
    $sql[] = '-- Target struktur: database lokal ' . $localDatabase;
    $sql[] = '-- Dibuat: ' . date('Y-m-d H:i:s');
    $sql[] = '-- WAJIB backup database production sebelum menjalankan script ini.';
    $sql[] = '-- Script ini hanya MENAMBAH tabel, kolom, indeks, dan foreign key yang belum ada.';
    $sql[] = '';
    $sql[] = 'SET FOREIGN_KEY_CHECKS = 0;';
    $sql[] = '';

    foreach ($missingTables as $table) {
        $sql[] = '-- Tabel baru: ' . $table;
        $createTableSql = showCreateTable($pdo, $localDatabase, $table);
        $createTableSql = preg_replace('/\sAUTO_INCREMENT=\d+\b/i', '', $createTableSql);
        $sql[] = $createTableSql . ';';
        $sql[] = '';
    }

    foreach ($missingColumns as $table => $columns) {
        $createSql = showCreateTable($pdo, $localDatabase, $table);
        $localColumnNames = array_keys($localSchema['columns'][$table]);
        $clauses = [];

        foreach ($columns as $column) {
            $position = array_search($column, $localColumnNames, true);
            $positionClause = $position === 0
                ? ' FIRST'
                : ' AFTER ' . quoteIdentifier($localColumnNames[$position - 1]);
            $clauses[] = '  ADD COLUMN ' . quoteIdentifier($column) . ' '
                . extractColumnDefinition($createSql, $column)
                . $positionClause;
        }

        $sql[] = '-- Kolom baru pada ' . $table;
        $sql[] = 'ALTER TABLE ' . quoteIdentifier($table) . "\n" . implode(",\n", $clauses) . ';';
        $sql[] = '';
    }

    foreach ($missingIndexes as $table => $indexes) {
        $clauses = [];
        foreach ($indexes as $name => $index) {
            $columnsSql = renderIndexColumns($index['columns']);
            if ($name === 'PRIMARY') {
                $clauses[] = '  ADD PRIMARY KEY (' . $columnsSql . ')';
            } elseif ((int) $index['non_unique'] === 0) {
                $clauses[] = '  ADD UNIQUE KEY ' . quoteIdentifier($name) . ' (' . $columnsSql . ')';
            } else {
                $prefix = strtoupper((string) $index['type']) === 'FULLTEXT' ? 'FULLTEXT ' : '';
                $clauses[] = '  ADD ' . $prefix . 'KEY ' . quoteIdentifier($name) . ' (' . $columnsSql . ')';
            }
        }
        if ($clauses !== []) {
            $sql[] = '-- Indeks baru pada ' . $table;
            $sql[] = 'ALTER TABLE ' . quoteIdentifier($table) . "\n" . implode(",\n", $clauses) . ';';
            $sql[] = '';
        }
    }

    foreach ($missingForeignKeys as $table => $foreignKeys) {
        $clauses = [];
        foreach ($foreignKeys as $name => $foreignKey) {
            $columnsSql = implode(', ', array_map('quoteIdentifier', $foreignKey['columns']));
            $referencedColumnsSql = implode(', ', array_map('quoteIdentifier', $foreignKey['referenced_columns']));
            $clauses[] = '  ADD CONSTRAINT ' . quoteIdentifier($name)
                . ' FOREIGN KEY (' . $columnsSql . ')'
                . ' REFERENCES ' . quoteIdentifier($foreignKey['referenced_table'])
                . ' (' . $referencedColumnsSql . ')'
                . ' ON DELETE ' . strtoupper((string) $foreignKey['delete_rule'])
                . ' ON UPDATE ' . strtoupper((string) $foreignKey['update_rule']);
        }
        if ($clauses !== []) {
            $sql[] = '-- Foreign key baru pada ' . $table;
            $sql[] = 'ALTER TABLE ' . quoteIdentifier($table) . "\n" . implode(",\n", $clauses) . ';';
            $sql[] = '';
        }
    }

    $migrationNames = [];
    if (in_array('user_simpeg_syncs', $missingTables, true)) {
        $migrationNames[] = '2026_07_23_000001_add_simpeg_fields_and_sync_logs';
    }
    if (in_array('estimasi_nominal_realisasi', $missingColumns['prospects'] ?? [], true)) {
        $migrationNames[] = '2026_07_29_000002_add_estimasi_nominal_realisasi_to_prospects_table';
    }

    if ($migrationNames !== [] && isset($productionSchema['tables']['migrations'])) {
        $sql[] = '-- Tandai migration terkait agar `php artisan migrate` tidak mengulangi DDL di atas.';
        $sql[] = 'SET @eprospek_upgrade_batch = (SELECT COALESCE(MAX(`batch`), 0) + 1 FROM `migrations`);';
        foreach ($migrationNames as $migrationName) {
            $quotedMigration = $pdo->quote($migrationName);
            $sql[] = 'INSERT INTO `migrations` (`migration`, `batch`)'
                . ' SELECT ' . $quotedMigration . ', @eprospek_upgrade_batch'
                . ' WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = ' . $quotedMigration . ');';
        }
        $sql[] = '';
    }

    $sql[] = 'SET FOREIGN_KEY_CHECKS = 1;';
    $sql[] = '';
    $sql[] = '-- Perbedaan definisi kolom/indeks yang sudah ada sengaja TIDAK diubah otomatis.';
    $sql[] = '-- Tinjau laporan Markdown sebelum memutuskan perubahan destruktif/konversi tipe.';

    $report = [];
    $report[] = '# Perbandingan Schema Database E-Prospek';
    $report[] = '';
    $report[] = '- Production dump: `' . basename($dumpPath) . '`';
    $report[] = '- Database lokal: `' . $localDatabase . '`';
    $report[] = '- Tabel production: ' . count($productionTables);
    $report[] = '- Tabel lokal: ' . count($localTables);
    $report[] = '- DDL production diproses: ' . $createdStatements . ' CREATE TABLE, ' . $alterStatements . ' ALTER TABLE';
    $report[] = '';

    $report[] = '## Tabel ada di lokal tetapi belum ada di production';
    $report[] = $missingTables === []
        ? '- Tidak ada.'
        : implode("\n", array_map(fn (string $table): string => '- `' . $table . '`', $missingTables));
    $report[] = '';

    $report[] = '## Tabel hanya ada di production';
    $report[] = $productionOnlyTables === []
        ? '- Tidak ada.'
        : implode("\n", array_map(fn (string $table): string => '- `' . $table . '`', $productionOnlyTables));
    $report[] = '';

    $report[] = '## Detail field pada tabel yang berbeda';
    if ($missingTables === [] && $productionOnlyTables === []) {
        $report[] = '- Tidak ada tabel berbeda.';
    } else {
        foreach ($missingTables as $table) {
            $report[] = '### `' . $table . '` — hanya ada di lokal';
            foreach ($localSchema['columns'][$table] ?? [] as $column => $definition) {
                $canonical = canonicalColumn($definition);
                $report[] = '- `' . $column . '` — `' . $canonical['type']
                    . '`, nullable=' . $canonical['nullable']
                    . ', default=' . var_export($canonical['default'], true)
                    . ', extra=' . ($canonical['extra'] ?: '-') . '`';
            }
            $report[] = '';
        }
        foreach ($productionOnlyTables as $table) {
            $report[] = '### `' . $table . '` — hanya ada di production';
            foreach ($productionSchema['columns'][$table] ?? [] as $column => $definition) {
                $canonical = canonicalColumn($definition);
                $report[] = '- `' . $column . '` — `' . $canonical['type']
                    . '`, nullable=' . $canonical['nullable']
                    . ', default=' . var_export($canonical['default'], true)
                    . ', extra=' . ($canonical['extra'] ?: '-') . '`';
            }
            $report[] = '';
        }
    }

    $report[] = '## Kolom ada di lokal tetapi belum ada di production';
    if ($missingColumns === []) {
        $report[] = '- Tidak ada.';
    } else {
        foreach ($missingColumns as $table => $columns) {
            foreach ($columns as $column) {
                $definition = canonicalColumn($localSchema['columns'][$table][$column]);
                $report[] = '- `' . $table . '.' . $column . '` — `'
                    . $definition['type'] . '`, nullable=' . $definition['nullable']
                    . ', default=' . var_export($definition['default'], true)
                    . ', extra=' . ($definition['extra'] ?: '-')
                    . '`';
            }
        }
    }
    $report[] = '';

    $report[] = '## Kolom hanya ada di production';
    if ($productionOnlyColumns === []) {
        $report[] = '- Tidak ada.';
    } else {
        foreach ($productionOnlyColumns as $table => $columns) {
            foreach ($columns as $column) {
                $definition = canonicalColumn($productionSchema['columns'][$table][$column]);
                $report[] = '- `' . $table . '.' . $column . '` — `'
                    . $definition['type'] . '`, nullable=' . $definition['nullable']
                    . ', default=' . var_export($definition['default'], true)
                    . ', extra=' . ($definition['extra'] ?: '-')
                    . '`';
            }
        }
    }
    $report[] = '';

    $report[] = '## Kolom dengan definisi berbeda';
    if ($changedColumns === []) {
        $report[] = '- Tidak ada.';
    } else {
        foreach ($changedColumns as $table => $columns) {
            foreach ($columns as $column => $definitions) {
                $report[] = '- `' . $table . '.' . $column . '`';
                $report[] = '  - Lokal: `' . json_encode($definitions['local'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '`';
                $report[] = '  - Production: `' . json_encode($definitions['production'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '`';
            }
        }
    }
    $report[] = '';

    $report[] = '## Indeks dan foreign key';
    $report[] = '- Indeks belum ada di production: ' . array_sum(array_map('count', $missingIndexes));
    $report[] = '- Indeks bernama sama tetapi berbeda: ' . array_sum(array_map('count', $changedIndexes));
    $report[] = '- Foreign key belum ada di production: ' . array_sum(array_map('count', $missingForeignKeys));
    $report[] = '- Foreign key bernama sama tetapi berbeda: ' . array_sum(array_map('count', $changedForeignKeys));
    $report[] = '';

    foreach ($missingIndexes as $table => $indexes) {
        foreach ($indexes as $name => $index) {
            $report[] = '- Indeks baru `' . $table . '.' . $name . '` pada kolom `'
                . implode(', ', array_column($index['columns'], 'name')) . '`';
        }
    }
    foreach ($missingForeignKeys as $table => $foreignKeys) {
        foreach ($foreignKeys as $name => $foreignKey) {
            $report[] = '- Foreign key baru `' . $table . '.' . $name . '` → `'
                . $foreignKey['referenced_table'] . '`';
        }
    }

    $report[] = '';
    $report[] = '## Catatan deployment';
    $report[] = '- Backup database production terlebih dahulu.';
    $report[] = '- Jalankan SQL upgrade satu kali pada maintenance window.';
    $report[] = '- Script tidak menghapus tabel/kolom production dan tidak otomatis mengubah definisi kolom yang sudah ada.';
    $report[] = '- Setelah deployment, jalankan kembali pembanding untuk memastikan selisih penambahan sudah nol.';

    $reportPath = rtrim($outputDirectory, '/\\') . '/schema_comparison_production_vs_local.md';
    $sqlPath = rtrim($outputDirectory, '/\\') . '/upgrade_production_to_local_schema.sql';
    $jsonPath = rtrim($outputDirectory, '/\\') . '/schema_comparison_production_vs_local.json';
    $upgradeSql = implode(PHP_EOL, $sql) . PHP_EOL;

    executeSqlScript($pdo, $upgradeSql);
    $validatedProductionSchema = loadSchema($pdo, $temporaryDatabase);
    $remainingTables = array_diff(
        array_keys($localSchema['tables']),
        array_keys($validatedProductionSchema['tables'])
    );
    $remainingColumns = [];
    $remainingIndexes = [];
    $remainingForeignKeys = [];

    foreach (array_intersect(
        array_keys($localSchema['tables']),
        array_keys($validatedProductionSchema['tables'])
    ) as $table) {
        foreach (array_diff(
            array_keys($localSchema['columns'][$table] ?? []),
            array_keys($validatedProductionSchema['columns'][$table] ?? [])
        ) as $column) {
            $remainingColumns[] = $table . '.' . $column;
        }
        foreach (array_diff(
            array_keys($localSchema['indexes'][$table] ?? []),
            array_keys($validatedProductionSchema['indexes'][$table] ?? [])
        ) as $index) {
            $remainingIndexes[] = $table . '.' . $index;
        }
        foreach (array_diff(
            array_keys($localSchema['foreignKeys'][$table] ?? []),
            array_keys($validatedProductionSchema['foreignKeys'][$table] ?? [])
        ) as $foreignKey) {
            $remainingForeignKeys[] = $table . '.' . $foreignKey;
        }
    }

    if (
        $remainingTables !== []
        || $remainingColumns !== []
        || $remainingIndexes !== []
        || $remainingForeignKeys !== []
    ) {
        throw new RuntimeException('Validasi SQL upgrade gagal: masih ada struktur lokal yang belum terbentuk.');
    }

    $report[] = '';
    $report[] = '## Hasil validasi SQL';
    $report[] = '- SQL upgrade berhasil diterapkan pada salinan schema production tanpa data.';
    $report[] = '- Setelah SQL diterapkan: 0 tabel, 0 kolom, 0 indeks, dan 0 foreign key lokal yang masih tertinggal.';

    file_put_contents($reportPath, implode(PHP_EOL, $report) . PHP_EOL);
    file_put_contents($sqlPath, $upgradeSql);
    file_put_contents($jsonPath, json_encode([
        'production_dump' => basename($dumpPath),
        'local_database' => $localDatabase,
        'missing_tables_in_production' => $missingTables,
        'production_only_tables' => $productionOnlyTables,
        'missing_columns_in_production' => $missingColumns,
        'production_only_columns' => $productionOnlyColumns,
        'changed_columns' => $changedColumns,
        'missing_indexes_in_production' => $missingIndexes,
        'changed_indexes' => $changedIndexes,
        'missing_foreign_keys_in_production' => $missingForeignKeys,
        'changed_foreign_keys' => $changedForeignKeys,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

    echo json_encode([
        'ok' => true,
        'production_tables' => count($productionTables),
        'local_tables' => count($localTables),
        'missing_tables' => count($missingTables),
        'production_only_tables' => count($productionOnlyTables),
        'missing_columns' => array_sum(array_map('count', $missingColumns)),
        'production_only_columns' => array_sum(array_map('count', $productionOnlyColumns)),
        'changed_columns' => array_sum(array_map('count', $changedColumns)),
        'missing_indexes' => array_sum(array_map('count', $missingIndexes)),
        'missing_foreign_keys' => array_sum(array_map('count', $missingForeignKeys)),
        'report' => $reportPath,
        'sql' => $sqlPath,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
} finally {
    try {
        $pdo->exec('USE ' . quoteIdentifier($localDatabase));
        $pdo->exec('DROP DATABASE IF EXISTS ' . quoteIdentifier($temporaryDatabase));
    } catch (Throwable $cleanupError) {
        fwrite(STDERR, "Peringatan: schema sementara gagal dibersihkan: {$temporaryDatabase}\n");
    }
}
