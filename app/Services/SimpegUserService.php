<?php

namespace App\Services;

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SimpegUserService
{
    private bool $readOnlyConfigured = false;

    public function __construct(
        private readonly ProspectReferralUserIdService $prospectReferralUserIds
    ) {
    }

    public function activeEmployeesQuery(array $filters = []): Builder
    {
        $this->ensureReadOnlyConnection();

        $query = DB::connection('simpeg')
            ->table('tb_jabatan as j')
            ->join('tb_pegawai as p', 'j.id_peg', '=', 'p.id_peg')
            ->join('tb_master_jabatan as mj', function ($join) {
                $join->whereRaw('CAST(j.kode_jabatan AS CHAR) = CAST(mj.kode_jabatan AS CHAR)');
            })
            ->leftJoin('tb_kantor as k', function ($join) {
                $join->whereRaw('CAST(j.unit_kerja AS CHAR) = CAST(k.kode_kantor_detail AS CHAR)');
            })
            ->where('j.status_jab', 'Aktif')
            ->select([
                'k.kode_cabang as kode_cabang',
                'k.kode_kantor_detail as kode_kantor',
                'j.id_peg as employee_id',
                'p.nama as full_name',
                'k.nama_kantor as branch_name',
                'mj.nama_unit_kerja as unit_kerja',
                'mj.nama_jabatan as job_position',
                'mj.level',
                'mj.group_jabatan',
            ]);

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($where) use ($like) {
                $where->whereRaw('CAST(k.kode_cabang AS CHAR) LIKE ?', [$like])
                    ->orWhereRaw('CAST(k.kode_kantor_detail AS CHAR) LIKE ?', [$like])
                    ->orWhereRaw('CAST(j.id_peg AS CHAR) LIKE ?', [$like])
                    ->orWhere('p.nama', 'like', $like)
                    ->orWhere('k.nama_kantor', 'like', $like)
                    ->orWhere('mj.nama_unit_kerja', 'like', $like)
                    ->orWhere('mj.nama_jabatan', 'like', $like)
                    ->orWhereRaw('CAST(mj.level AS CHAR) LIKE ?', [$like])
                    ->orWhere('mj.group_jabatan', 'like', $like);
            });
        }

        $this->applyExactFilter($query, 'k.kode_cabang', $filters['kode'] ?? null);
        $this->applyExactFilter($query, 'k.kode_kantor_detail', $filters['kantor'] ?? null);
        $this->applyExactFilter($query, 'mj.nama_unit_kerja', $filters['unit'] ?? null);
        $this->applyExactFilter($query, 'mj.nama_jabatan', $filters['jabatan'] ?? null);
        $this->applyExactFilter($query, 'mj.level', $filters['level'] ?? null);
        $this->applyExactFilter($query, 'mj.group_jabatan', $filters['group'] ?? null);

        return $query->orderBy('k.kode_kantor_detail')->orderBy('p.nama');
    }

    public function paginate(array $filters, int $perPage = 50): LengthAwarePaginator
    {
        return $this->activeEmployeesQuery($filters)
            ->paginate($perPage, ['*'], 'simpegPage');
    }

    public function allActiveEmployees(): array
    {
        return $this->activeEmployeesQuery()
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    public function filterOptions(): array
    {
        return Cache::remember('simpeg:user-filter-options:v1', now()->addMinutes(10), function () {
            $rows = $this->activeEmployeesQuery()
                ->get()
                ->map(fn ($row) => (array) $row);

            return [
                'kode' => $this->uniqueOptions($rows, 'kode_cabang'),
                'kantor' => $this->uniqueOptions($rows, 'kode_kantor'),
                'unit' => $this->uniqueOptions($rows, 'unit_kerja'),
                'jabatan' => $this->uniqueOptions($rows, 'job_position'),
                'level' => $this->uniqueOptions($rows, 'level'),
                'group' => $this->uniqueOptions($rows, 'group_jabatan'),
            ];
        });
    }

    public function processBatch(array $rows, array $frequencies, int $offset, int $limit = 20): array
    {
        $batch = array_slice($rows, $offset, $limit);
        $results = [];

        $branchMap = Cabang::query()
            ->get(['id', 'kode_cabang'])
            ->mapWithKeys(fn ($branch) => [
                str_pad((string) ((int) $branch->kode_cabang), 3, '0', STR_PAD_LEFT) => (int) $branch->id,
            ])
            ->all();

        DB::transaction(function () use ($batch, $frequencies, $branchMap, &$results) {
            foreach ($batch as $source) {
                $results[] = $this->syncEmployee($source, $frequencies, $branchMap);
            }
        });

        $processed = min(count($rows), $offset + count($batch));
        $done = $processed >= count($rows);
        $deactivated = 0;

        if ($done) {
            $deactivated = DB::transaction(function () use ($rows, &$results) {
                return $this->deactivateMissingUsers($rows, $results);
            });
        }

        return [
            'rows' => $results,
            'processed' => $processed,
            'total' => count($rows),
            'done' => $done,
            'deactivated' => $deactivated,
        ];
    }

    public function deriveRole(array $row): string
    {
        $kode = trim((string) ($row['kode_cabang'] ?? ''));
        $job = Str::upper(trim((string) ($row['job_position'] ?? '')));
        $level = Str::upper(trim((string) ($row['level'] ?? '')));
        $branch = Str::upper(trim((string) ($row['branch_name'] ?? '')));
        $unit = Str::upper(trim((string) ($row['unit_kerja'] ?? '')));

        if ($level === 'DEWAN KOMISARIS DAN DIREKSI') {
            return 'MANAJEMEN';
        }

        if (
            str_contains($branch, 'KANTOR WILAYAH') ||
            str_contains($branch, 'KANWIL') ||
            str_contains($unit, 'KANTOR WILAYAH') ||
            str_contains($unit, 'AREA KANTOR WILAYAH')
        ) {
            return 'MANAJEMEN KANWIL';
        }

        if (in_array($level, ['KEPALA BIDANG', 'KEPALA CABANG'], true) && $kode !== '' && $kode !== '000') {
            return 'SUPERVISOR';
        }

        if (in_array($job, ['AO KREDIT', 'AO DANA', 'AO REMIDIAL', 'AO REMEDIAL', 'AO'], true)) {
            return 'AO';
        }

        return 'PEGAWAI';
    }

    private function syncEmployee(array $source, array $frequencies, array $branchMap): array
    {
        $employeeId = trim((string) ($source['employee_id'] ?? ''));
        $fullName = trim((string) ($source['full_name'] ?? ''));
        $frequencyKey = $this->normalize($employeeId);
        $failure = '';

        if ($employeeId === '' || $fullName === '') {
            $failure = 'Employee ID atau nama lengkap kosong.';
        } elseif (($frequencies[$frequencyKey] ?? 0) > 1) {
            $failure = 'Employee ID aktif muncul lebih dari satu kali di SIMPEG.';
        }

        [$matched, $matchFailure] = $this->findMatchingUser($employeeId, $fullName);
        if ($failure === '' && $matchFailure !== '') {
            $failure = $matchFailure;
        }

        $kodeRaw = trim((string) ($source['kode_cabang'] ?? ''));
        $kode = $kodeRaw === '' ? '' : str_pad((string) ((int) $kodeRaw), 3, '0', STR_PAD_LEFT);
        $cabangId = $branchMap[$kode] ?? null;
        if ($failure === '' && $kode === '') {
            $failure = 'Kode cabang SIMPEG kosong.';
        } elseif ($failure === '' && $kode !== '000' && !$cabangId) {
            $failure = 'Kode cabang ' . $kode . ' belum terpetakan.';
        }

        $role = $matched && strtoupper((string) $matched->role) === 'ADMIN'
            ? 'ADMIN'
            : $this->deriveRole($source);
        $status = 'FAILED';
        $message = $failure;
        $userId = $matched?->id;

        if ($failure === '') {
            if ($matched) {
                $oldName = (string) $matched->name;
                $oldEmployeeId = (string) ($matched->employee_id ?? '');
                $matched->fill($this->userAttributes($source, $employeeId, $fullName, $role, $cabangId));
                $matched->aktif = 1;
                $matched->save();

                $this->prospectReferralUserIds->replace(
                    [$oldName, $oldEmployeeId],
                    $employeeId
                );

                $status = 'UPDATED';
                $message = 'Data user diperbarui dari SIMPEG.';
            } else {
                $matched = User::create(array_merge(
                    $this->userAttributes($source, $employeeId, $fullName, $role, $cabangId),
                    [
                        'email' => $this->availableEmail($employeeId),
                        'password' => Hash::make('password'),
                        'aktif' => 1,
                    ]
                ));
                $userId = $matched->id;
                $status = 'NEW';
                $message = 'User baru dibuat dengan password awal password.';
            }
        }

        $this->writeSyncLog($userId, $employeeId, $status, $message, $source);

        return [
            'employee_id' => $employeeId,
            'full_name' => $fullName,
            'role' => $role,
            'branch' => (string) ($source['branch_name'] ?? ''),
            'status' => $status,
            'message' => $message,
        ];
    }

    private function findMatchingUser(string $employeeId, string $fullName): array
    {
        $usernameMatch = User::query()
            ->where('employee_id', $employeeId)
            ->orWhere('name', $employeeId)
            ->lockForUpdate()
            ->first();

        $nameMatches = User::query()
            ->whereRaw('LOWER(TRIM(nama_lengkap)) = ?', [$this->normalize($fullName)])
            ->lockForUpdate()
            ->limit(2)
            ->get();
        $nameMatch = $nameMatches->count() === 1 ? $nameMatches->first() : null;

        if ($usernameMatch && $nameMatch && $usernameMatch->id !== $nameMatch->id) {
            return [$usernameMatch, 'Employee ID dan nama mengarah ke dua user berbeda.'];
        }

        return [$usernameMatch ?: $nameMatch, ''];
    }

    private function userAttributes(
        array $source,
        string $employeeId,
        string $fullName,
        string $role,
        ?int $cabangId
    ): array {
        return [
            'name' => $employeeId,
            'nama_lengkap' => $fullName,
            'role' => $role,
            'cabang_id' => $cabangId,
            'job_position' => $this->nullable($source['job_position'] ?? null),
            'kode' => $this->nullable($source['kode_cabang'] ?? null),
            'employee_id' => $employeeId,
            'branch_name' => $this->nullable($source['branch_name'] ?? null),
            'unit_kerja' => $this->nullable($source['unit_kerja'] ?? null),
            'level' => $this->nullable($source['level'] ?? null),
            'group_jabatan' => $this->nullable($source['group_jabatan'] ?? null),
        ];
    }

    private function deactivateMissingUsers(array $activeRows, array &$results): int
    {
        $activePairs = [];
        foreach ($activeRows as $row) {
            $activePairs[$this->normalize($row['employee_id'] ?? '') . '|' . $this->normalize($row['full_name'] ?? '')] = true;
        }

        $managedUsers = User::query()
            ->join('user_simpeg_syncs as sync', 'sync.user_id', '=', 'users.id')
            ->where('users.aktif', 1)
            ->where('users.role', '!=', 'ADMIN')
            ->select('users.*')
            ->distinct()
            ->lockForUpdate()
            ->get();

        $count = 0;
        foreach ($managedUsers as $user) {
            $pair = $this->normalize($user->employee_id ?: $user->name) . '|' . $this->normalize($user->nama_lengkap);
            if (isset($activePairs[$pair])) {
                continue;
            }

            $user->aktif = 0;
            $user->save();
            $message = 'Employee ID atau nama tidak ditemukan pada data SIMPEG aktif.';
            $snapshot = [
                'employee_id' => $user->employee_id ?: $user->name,
                'full_name' => $user->nama_lengkap,
                'branch_name' => $user->branch_name,
                'previous_role' => $user->role,
                'reconciled_at' => now()->toDateTimeString(),
            ];
            $this->writeSyncLog($user->id, (string) ($user->employee_id ?: $user->name), 'DEACTIVATED', $message, $snapshot);
            $results[] = [
                'employee_id' => (string) ($user->employee_id ?: $user->name),
                'full_name' => (string) $user->nama_lengkap,
                'role' => (string) $user->role,
                'branch' => (string) $user->branch_name,
                'status' => 'DEACTIVATED',
                'message' => $message,
            ];
            $count++;
        }

        return $count;
    }

    private function writeSyncLog(
        ?int $userId,
        string $employeeId,
        string $status,
        string $message,
        array $snapshot
    ): void {
        DB::table('user_simpeg_syncs')->updateOrInsert(
            ['employee_id' => $employeeId],
            [
                'user_id' => $userId,
                'sync_status' => $status,
                'sync_message' => Str::limit($message, 500, ''),
                'snapshot_data' => json_encode($snapshot),
                'synced_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function availableEmail(string $employeeId): string
    {
        $localPart = Str::lower(preg_replace('/[^a-zA-Z0-9._-]/', '', $employeeId)) ?: 'pegawai';
        $email = $localPart . '@import.local';
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $email = $localPart . $counter . '@import.local';
            $counter++;
        }

        return $email;
    }

    private function applyExactFilter(Builder $query, string $column, mixed $value): void
    {
        if (trim((string) $value) !== '') {
            $query->whereRaw('CAST(' . $column . ' AS CHAR) = ?', [(string) $value]);
        }
    }

    private function uniqueOptions(Collection $rows, string $key): Collection
    {
        return $rows->pluck($key)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique()
            ->sort(SORT_NATURAL | SORT_FLAG_CASE)
            ->values();
    }

    private function normalize(mixed $value): string
    {
        return Str::lower(trim(preg_replace('/\s+/', ' ', (string) $value)));
    }

    private function nullable(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function ensureReadOnlyConnection(): void
    {
        if ($this->readOnlyConfigured) {
            return;
        }

        DB::connection('simpeg')->statement('SET SESSION TRANSACTION READ ONLY');
        $this->readOnlyConfigured = true;
    }
}
