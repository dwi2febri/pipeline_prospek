<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Cabang;
use App\Services\ProspectReferralUserIdService;
use App\Services\SimpegUserService;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterCabang = '';
    public string $filterRole = '';
    public string $filterAktif = '';
    public string $filterSync = '';
    public string $filterJobPosition = '';
    public string $filterBranchName = '';
    public string $filterUnitKerja = '';
    public string $activeTab = 'local';
    public string $simpegSearch = '';
    public string $simpegFilterKode = '';
    public string $simpegFilterKantor = '';
    public string $simpegFilterUnit = '';
    public string $simpegFilterJabatan = '';
    public string $simpegFilterLevel = '';
    public string $simpegFilterGroup = '';
    public $file;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCabang' => ['except' => ''],
        'filterRole' => ['except' => ''],
        'filterAktif' => ['except' => ''],
        'filterSync' => ['except' => ''],
        'filterJobPosition' => ['except' => ''],
        'filterBranchName' => ['except' => ''],
        'filterUnitKerja' => ['except' => ''],
        'activeTab' => ['except' => 'local', 'as' => 'tab'],
        'simpegSearch' => ['except' => ''],
        'simpegFilterKode' => ['except' => ''],
        'simpegFilterKantor' => ['except' => ''],
        'simpegFilterUnit' => ['except' => ''],
        'simpegFilterJabatan' => ['except' => ''],
        'simpegFilterLevel' => ['except' => ''],
        'simpegFilterGroup' => ['except' => ''],
    ];

    public function mount(): void
    {
        if (!in_array($this->activeTab, ['local', 'simpeg'], true)) {
            $this->activeTab = 'local';
        }
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab === 'simpeg' ? 'simpeg' : 'local';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCabang()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterAktif()
    {
        $this->resetPage();
    }

    public function updatingFilterSync()
    {
        $this->resetPage();
    }

    public function updatingFilterJobPosition()
    {
        $this->resetPage();
    }

    public function updatingFilterBranchName()
    {
        $this->resetPage();
    }

    public function updatingFilterUnitKerja()
    {
        $this->resetPage();
    }

    public function updatedSimpegSearch(): void
    {
        $this->resetPage('simpegPage');
    }

    public function updatedSimpegFilterKode(): void
    {
        $this->resetPage('simpegPage');
    }

    public function updatedSimpegFilterKantor(): void
    {
        $this->resetPage('simpegPage');
    }

    public function updatedSimpegFilterUnit(): void
    {
        $this->resetPage('simpegPage');
    }

    public function updatedSimpegFilterJabatan(): void
    {
        $this->resetPage('simpegPage');
    }

    public function updatedSimpegFilterLevel(): void
    {
        $this->resetPage('simpegPage');
    }

    public function updatedSimpegFilterGroup(): void
    {
        $this->resetPage('simpegPage');
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->filterCabang = '';
        $this->filterRole = '';
        $this->filterAktif = '';
        $this->filterSync = '';
        $this->filterJobPosition = '';
        $this->filterBranchName = '';
        $this->filterUnitKerja = '';
        $this->resetPage();
    }

    public function resetSimpegFilter(): void
    {
        $this->simpegSearch = '';
        $this->simpegFilterKode = '';
        $this->simpegFilterKantor = '';
        $this->simpegFilterUnit = '';
        $this->simpegFilterJabatan = '';
        $this->simpegFilterLevel = '';
        $this->simpegFilterGroup = '';
        $this->resetPage('simpegPage');
    }

    public function startSimpegGenerate(): array
    {
        $this->skipRender();

        try {
            $rows = app(SimpegUserService::class)->allActiveEmployees();
            if ($rows === []) {
                return ['ok' => false, 'message' => 'Tidak ada pegawai aktif SIMPEG untuk diproses.'];
            }

            $frequencies = [];
            foreach ($rows as $row) {
                $key = Str::lower(trim((string) ($row['employee_id'] ?? '')));
                if ($key !== '') {
                    $frequencies[$key] = ($frequencies[$key] ?? 0) + 1;
                }
            }

            $jobId = Str::random(40);
            Cache::put($this->generateCacheKey($jobId), [
                'rows' => $rows,
                'frequencies' => $frequencies,
            ], now()->addMinutes(30));

            return ['ok' => true, 'job_id' => $jobId, 'total' => count($rows)];
        } catch (\Throwable $e) {
            report($e);

            return [
                'ok' => false,
                'message' => 'Database SIMPEG belum dapat dihubungkan atau struktur datanya tidak sesuai.',
            ];
        }
    }

    public function processSimpegGenerate(string $jobId, int $offset): array
    {
        $this->skipRender();

        if (!preg_match('/^[A-Za-z0-9]{40}$/', $jobId)) {
            return ['ok' => false, 'message' => 'ID proses generate tidak valid.'];
        }

        $key = $this->generateCacheKey($jobId);
        $job = Cache::get($key);
        if (!is_array($job) || !isset($job['rows'], $job['frequencies'])) {
            return ['ok' => false, 'message' => 'Sesi generate sudah berakhir. Silakan mulai lagi.'];
        }

        try {
            $result = app(SimpegUserService::class)->processBatch(
                $job['rows'],
                $job['frequencies'],
                max(0, $offset),
                200
            );

            if ($result['done']) {
                Cache::forget($key);
                $this->resetPage();
            } else {
                Cache::put($key, $job, now()->addMinutes(30));
            }

            return ['ok' => true] + $result;
        } catch (\Throwable $e) {
            report($e);

            return ['ok' => false, 'message' => 'Generate gagal. Perubahan pada batch terakhir dibatalkan.'];
        }
    }

    private function generateCacheKey(string $jobId): string
    {
        return 'simpeg-user-generate:' . auth()->id() . ':' . $jobId;
    }

    protected function normalizeText(?string $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    protected function deriveRole(?string $kode, ?string $jobPosition, ?string $level, ?string $branchName = null, ?string $unitKerja = null): string
    {
        $kode = trim((string) $kode);
        $jobPosition = strtoupper(trim((string) $jobPosition));
        $level = strtoupper(trim((string) $level));
        $branchName = strtoupper(trim((string) $branchName));
        $unitKerja = strtoupper(trim((string) $unitKerja));

        if ($level === 'DEWAN KOMISARIS DAN DIREKSI') {
            return 'MANAJEMEN';
        }

        if (
            str_contains($branchName, 'KANTOR WILAYAH') ||
            str_contains($branchName, 'KANWIL') ||
            str_contains($unitKerja, 'KANTOR WILAYAH') ||
            str_contains($unitKerja, 'AREA KANTOR WILAYAH')
        ) {
            return 'MANAJEMEN KANWIL';
        }

        if (in_array($level, ['KEPALA BIDANG', 'KEPALA CABANG'], true) && $kode !== '' && $kode !== '000') {
            return 'SUPERVISOR';
        }

        if (in_array($jobPosition, ['AO KREDIT', 'AO DANA', 'AO REMIDIAL', 'AO REMEDIAL', 'AO'], true)) {
            return 'AO';
        }

        return 'PEGAWAI';
    }

    protected function resolveCabangId(?string $kode, ?string $branchName): ?int
    {
        $kode = trim((string) $kode);
        $branchName = trim((string) $branchName);

        if ($kode !== '') {
            $kodeOnly = preg_replace('/[^0-9]/', '', $kode);

            if ($kodeOnly !== '') {
                $kode3 = str_pad($kodeOnly, 3, '0', STR_PAD_LEFT);

                $cabang = Cabang::query()
                    ->where('kode_cabang', $kode3)
                    ->first(['id']);

                if ($cabang) {
                    return (int) $cabang->id;
                }
            }
        }

        if ($branchName !== '') {
            $branchNameNorm = strtolower($branchName);

            $cabang = Cabang::query()
                ->whereRaw('LOWER(nama_cabang) = ?', [$branchNameNorm])
                ->first(['id']);

            if ($cabang) {
                return (int) $cabang->id;
            }

            $cabang = Cabang::query()
                ->whereRaw('LOWER(nama_cabang) LIKE ?', ['%' . $branchNameNorm . '%'])
                ->first(['id']);

            if ($cabang) {
                return (int) $cabang->id;
            }
        }

        return null;
    }

    public function toggleAktif(int $id): void
    {
        $u = User::findOrFail($id);
        $u->aktif = (int) $u->aktif === 1 ? 0 : 1;
        $u->save();

        session()->flash('ok', 'Status user berhasil diubah.');
    }

    public function importCsv(): void
    {
        $this->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $path = $this->file->getRealPath();
        $fh = fopen($path, 'r');

        if (!$fh) {
            session()->flash('ok', 'Gagal membaca file.');
            return;
        }

        $firstLine = fgets($fh);
        if ($firstLine === false) {
            fclose($fh);
            session()->flash('ok', 'File kosong.');
            return;
        }

        $delim = (substr_count($firstLine, ';') >= substr_count($firstLine, ',')) ? ';' : ',';

        $headerCols = str_getcsv(trim($firstLine), $delim);
        $headerCols = array_map(fn($x) => strtolower(trim((string) $x)), $headerCols);

        $idxKode         = array_search('kode', $headerCols);
        $idxEmployeeId   = array_search('employee_id', $headerCols);
        $idxFullName     = array_search('full_name', $headerCols);
        $idxBranchName   = array_search('branch_name', $headerCols);
        $idxUnitKerja    = array_search('unit_kerja', $headerCols);
        $idxJobPosition  = array_search('job_position', $headerCols);
        $idxLevel        = array_search('level', $headerCols);
        $idxGroupJabatan = array_search('group_jabatan', $headerCols);

        if (
            $idxKode === false ||
            $idxEmployeeId === false ||
            $idxFullName === false ||
            $idxBranchName === false ||
            $idxUnitKerja === false ||
            $idxJobPosition === false ||
            $idxLevel === false ||
            $idxGroupJabatan === false
        ) {
            fclose($fh);
            session()->flash('ok', 'Header wajib: kode, employee_id, full_name, branch_name, unit_kerja, job_position, level, group_jabatan');
            return;
        }

        $inserted = 0;
        $updated  = 0;
        $skipped  = 0;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($fh, 0, $delim)) !== false) {
                if (count($row) < 8) {
                    $skipped++;
                    continue;
                }

                $kode         = $this->normalizeText($row[$idxKode] ?? null);
                $employeeId   = $this->normalizeText($row[$idxEmployeeId] ?? null);
                $fullName     = $this->normalizeText($row[$idxFullName] ?? null);
                $branchName   = $this->normalizeText($row[$idxBranchName] ?? null);
                $unitKerja    = $this->normalizeText($row[$idxUnitKerja] ?? null);
                $jobPosition  = $this->normalizeText($row[$idxJobPosition] ?? null);
                $level        = $this->normalizeText($row[$idxLevel] ?? null);
                $groupJabatan = $this->normalizeText($row[$idxGroupJabatan] ?? null);

                if (!$fullName || !$employeeId) {
                    $skipped++;
                    continue;
                }

                $role = $this->deriveRole($kode, $jobPosition, $level, $branchName, $unitKerja);
                $cabangId = $this->resolveCabangId($kode, $branchName);

                $username = $employeeId;
                $baseEmail = strtolower(str_replace(' ', '', $employeeId)) . '@import.local';

                $u = User::query()
                    ->whereRaw('TRIM(nama_lengkap) = ?', [$fullName])
                    ->first();

                if (!$u) {
                    $u = User::query()
                        ->whereRaw('TRIM(name) = ?', [$username])
                        ->first();
                }

                if ($u) {
                    $oldName = (string) $u->name;
                    $oldEmployeeId = (string) ($u->employee_id ?? '');

                    $u->name = $username;
                    $u->nama_lengkap = $fullName;
                    $u->role = $role;
                    $u->cabang_id = $cabangId;
                    $u->job_position = $jobPosition;
                    $u->kode = $kode;
                    $u->employee_id = $employeeId;
                    $u->branch_name = $branchName;
                    $u->unit_kerja = $unitKerja;
                    $u->level = $level;
                    $u->group_jabatan = $groupJabatan;
                    $u->aktif = 1;

                    if (!$u->email) {
                        $email = $baseEmail;
                        $counter = 1;
                        while (User::where('email', $email)->where('id', '<>', $u->id)->exists()) {
                            $email = strtolower(str_replace(' ', '', $employeeId)) . $counter . '@import.local';
                            $counter++;
                        }
                        $u->email = $email;
                    }

                    $u->save();

                    app(ProspectReferralUserIdService::class)->replace(
                        [$oldName, $oldEmployeeId],
                        (string) ($u->employee_id ?: $u->name)
                    );

                    $updated++;
                } else {
                    $email = $baseEmail;
                    $counter = 1;
                    while (User::where('email', $email)->exists()) {
                        $email = strtolower(str_replace(' ', '', $employeeId)) . $counter . '@import.local';
                        $counter++;
                    }

                    $u = new User();
                    $u->name = $username;
                    $u->email = $email;
                    $u->password = Hash::make('password');
                    $u->nama_lengkap = $fullName;
                    $u->role = $role;
                    $u->cabang_id = $cabangId;
                    $u->job_position = $jobPosition;
                    $u->kode = $kode;
                    $u->employee_id = $employeeId;
                    $u->branch_name = $branchName;
                    $u->unit_kerja = $unitKerja;
                    $u->level = $level;
                    $u->group_jabatan = $groupJabatan;
                    $u->aktif = 1;
                    $u->save();

                    $inserted++;
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($fh);
            session()->flash('ok', 'Import user gagal: ' . $e->getMessage());
            return;
        }

        fclose($fh);

        $this->file = null;
        $this->dispatch('closeImportUsersModal');
        session()->flash('ok', "Import user selesai. Insert: $inserted | Update: $updated | Skip: $skipped");
        $this->resetPage();
    }

    public function render()
    {
        $cabangs = Cabang::query()
            ->orderBy('kode_cabang')
            ->get(['id', 'kode_cabang', 'nama_cabang']);

        $jobPositions = User::query()
            ->whereNotNull('job_position')
            ->where('job_position', '!=', '')
            ->distinct()
            ->orderBy('job_position')
            ->pluck('job_position');

        $branchNames = User::query()
            ->whereNotNull('branch_name')
            ->where('branch_name', '!=', '')
            ->distinct()
            ->orderBy('branch_name')
            ->pluck('branch_name');

        $unitKerjas = User::query()
            ->whereNotNull('unit_kerja')
            ->where('unit_kerja', '!=', '')
            ->distinct()
            ->orderBy('unit_kerja')
            ->pluck('unit_kerja');

        $items = User::query()
            ->select('users.*')
            ->addSelect([
                'simpeg_sync_status' => DB::table('user_simpeg_syncs as sync_status')
                    ->select('sync_status.sync_status')
                    ->whereColumn('sync_status.user_id', 'users.id')
                    ->latest('sync_status.synced_at')
                    ->limit(1),
                'simpeg_sync_message' => DB::table('user_simpeg_syncs as sync_message')
                    ->select('sync_message.sync_message')
                    ->whereColumn('sync_message.user_id', 'users.id')
                    ->latest('sync_message.synced_at')
                    ->limit(1),
                'simpeg_synced_at' => DB::table('user_simpeg_syncs as sync_time')
                    ->select('sync_time.synced_at')
                    ->whereColumn('sync_time.user_id', 'users.id')
                    ->latest('sync_time.synced_at')
                    ->limit(1),
            ])
            ->with('cabang')
            ->when($this->search !== '', function ($q) {
                $s = '%' . $this->search . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('name', 'like', $s)
                        ->orWhere('email', 'like', $s)
                        ->orWhere('role', 'like', $s)
                        ->orWhere('nama_lengkap', 'like', $s)
                        ->orWhere('employee_id', 'like', $s)
                        ->orWhere('branch_name', 'like', $s)
                        ->orWhere('unit_kerja', 'like', $s)
                        ->orWhere('job_position', 'like', $s)
                        ->orWhere('level', 'like', $s)
                        ->orWhere('group_jabatan', 'like', $s);
                });
            })
            ->when($this->filterCabang !== '', function ($q) {
                $q->where('cabang_id', (int) $this->filterCabang);
            })
            ->when($this->filterRole !== '', function ($q) {
                $q->where('role', $this->filterRole);
            })
            ->when($this->filterAktif !== '', function ($q) {
                $q->where('aktif', (int) $this->filterAktif);
            })
            ->when($this->filterSync !== '', function ($q) {
                $q->where(function ($statusQuery) {
                    if ($this->filterSync === 'DEACTIVATED') {
                        $statusQuery->where('users.aktif', 0)
                            ->orWhereExists(function ($sync) {
                                $sync->selectRaw('1')
                                    ->from('user_simpeg_syncs')
                                    ->whereColumn('user_simpeg_syncs.user_id', 'users.id')
                                    ->where('user_simpeg_syncs.sync_status', 'DEACTIVATED');
                            });

                        return;
                    }

                    $statusQuery->whereExists(function ($sync) {
                        $sync->selectRaw('1')
                            ->from('user_simpeg_syncs')
                            ->whereColumn('user_simpeg_syncs.user_id', 'users.id')
                            ->where('user_simpeg_syncs.sync_status', $this->filterSync);
                    });
                });
            })
            ->when($this->filterJobPosition !== '', function ($q) {
                $q->where('job_position', $this->filterJobPosition);
            })
            ->when($this->filterBranchName !== '', function ($q) {
                $q->where('branch_name', $this->filterBranchName);
            })
            ->when($this->filterUnitKerja !== '', function ($q) {
                $q->where('unit_kerja', $this->filterUnitKerja);
            })
            ->latest('id')
            ->paginate(10);

        $simpegItems = new LengthAwarePaginator([], 0, 50, 1, [
            'path' => request()->url(),
            'pageName' => 'simpegPage',
        ]);
        $simpegOptions = [
            'kode' => collect(),
            'kantor' => collect(),
            'unit' => collect(),
            'jabatan' => collect(),
            'level' => collect(),
            'group' => collect(),
        ];
        $simpegError = '';

        if ($this->activeTab === 'simpeg') {
            try {
                $service = app(SimpegUserService::class);
                $filters = [
                    'search' => $this->simpegSearch,
                    'kode' => $this->simpegFilterKode,
                    'kantor' => $this->simpegFilterKantor,
                    'unit' => $this->simpegFilterUnit,
                    'jabatan' => $this->simpegFilterJabatan,
                    'level' => $this->simpegFilterLevel,
                    'group' => $this->simpegFilterGroup,
                ];
                $simpegItems = $service->paginate($filters);
                $simpegOptions = $service->filterOptions();
            } catch (\Throwable $e) {
                report($e);
                $simpegError = 'Database SIMPEG belum dapat dihubungkan atau struktur datanya tidak sesuai.';
            }
        }

        return view('livewire.users.index', compact(
            'items',
            'cabangs',
            'jobPositions',
            'branchNames',
            'unitKerjas',
            'simpegItems',
            'simpegOptions',
            'simpegError'
        ))->layout('layouts.bootstrap');
    }
}
