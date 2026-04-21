<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cabang;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterCabang = '';
    public string $filterRole = '';
    public string $filterAktif = '';
    public $file;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCabang' => ['except' => ''],
        'filterRole' => ['except' => ''],
        'filterAktif' => ['except' => ''],
    ];

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

    public function resetFilter(): void
    {
        $this->search = '';
        $this->filterCabang = '';
        $this->filterRole = '';
        $this->filterAktif = '';
        $this->resetPage();
    }

    protected function normalizeText(?string $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    protected function deriveRole(?string $kode, ?string $jobPosition, ?string $level): string
    {
        $kode = trim((string) $kode);
        $jobPosition = strtoupper(trim((string) $jobPosition));
        $level = strtoupper(trim((string) $level));

        if ($level === 'DEWAN KOMISARIS DAN DIREKSI') {
            return 'MANAJEMEN';
        }

        if (in_array($level, ['KEPALA BIDANG', 'KEPALA CABANG'], true) && $kode !== '' && $kode !== '000') {
            return 'SUPERVISOR';
        }

        if (in_array($jobPosition, ['AO KREDIT', 'AO DANA', 'AO REMIDIAL', 'AO REMEDIAL'], true)) {
            return 'AO';
        }

        return 'PEGAWAI';
    }

    protected function resolveCabangId(?string $kode, ?string $branchName): ?int
    {
        $kode = trim((string) $kode);
        $branchName = trim((string) $branchName);

        // Ambil digit saja, lalu paksa 3 digit:
        // 0 => 000, 10 => 010, 011 => 011, 000 => 000
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

        // Fallback ke nama cabang bila kode tidak ketemu
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

                $role = $this->deriveRole($kode, $jobPosition, $level);
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

                    if ($oldName !== '' && $oldName !== $u->name) {
                        DB::table('prospects')
                            ->where('referral_user_id', $oldName)
                            ->update(['referral_user_id' => $u->name]);
                    }

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

        $items = User::query()
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
            ->latest('id')
            ->paginate(10);

        return view('livewire.users.index', compact('items', 'cabangs'))
            ->layout('layouts.bootstrap');
    }
}
