<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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

    public function toggleAktif(int $id): void
    {
        $u = User::findOrFail($id);
        $u->aktif = (int)$u->aktif === 1 ? 0 : 1;
        $u->save();

        session()->flash('ok', 'Status user berhasil diubah.');
    }

    public function importCsv(): void
    {
        $this->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $allowedRoles = ['ADMIN', 'MANAJEMEN', 'SUPERVISOR', 'AO', 'PEGAWAI'];

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
        $headerCols = array_map(fn($x) => strtolower(trim($x)), $headerCols);

        $idxUsername    = array_search('username', $headerCols);
        $idxPassword    = array_search('password', $headerCols);
        $idxNamaLengkap = array_search('nama_lengkap', $headerCols);
        $idxRole        = array_search('role', $headerCols);
        $idxCabang      = array_search('id_cabang', $headerCols);
        $idxJobPosisi   = array_search('job_posisi', $headerCols);

        if ($idxUsername === false || $idxPassword === false || $idxRole === false) {
            fclose($fh);
            session()->flash('ok', 'Header wajib ada: username, password, role.');
            return;
        }

        $inserted = 0;
        $updated  = 0;
        $skipped  = 0;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($fh, 0, $delim)) !== false) {
                if (count($row) < 3) {
                    $skipped++;
                    continue;
                }

                $username     = trim((string)($row[$idxUsername] ?? ''));
                $password     = trim((string)($row[$idxPassword] ?? ''));
                $namaLengkap  = $idxNamaLengkap !== false ? trim((string)($row[$idxNamaLengkap] ?? '')) : null;
                $role         = $idxRole !== false ? strtoupper(trim((string)($row[$idxRole] ?? 'PEGAWAI'))) : 'PEGAWAI';
                $cabangId     = $idxCabang !== false ? trim((string)($row[$idxCabang] ?? '')) : null;
                $jobPosisi    = $idxJobPosisi !== false ? trim((string)($row[$idxJobPosisi] ?? '')) : null;

                if ($username === '') {
                    $skipped++;
                    continue;
                }

                if (!in_array($role, $allowedRoles, true)) {
                    $role = 'PEGAWAI';
                }

                $cabangId = ($cabangId !== '' && is_numeric($cabangId)) ? (int)$cabangId : null;

                if ($cabangId && !Cabang::where('id', $cabangId)->exists()) {
                    $cabangId = null;
                }

                $baseEmail = strtolower(trim($username)) . '@import.local';

                $u = User::whereRaw('TRIM(name) = ?', [$username])->first();

                if (!$u && $namaLengkap) {
                    $u = User::whereRaw('TRIM(nama_lengkap) = ?', [$namaLengkap])->first();
                }

                if ($u) {
                    $u->name = $username;
                    $u->nama_lengkap = $namaLengkap ?: null;
                    $u->role = $role;
                    $u->cabang_id = $cabangId;

                    if (Schema::hasColumn('users', 'job_position')) {
                        $u->job_position = $jobPosisi ?: null;
                    }

                    if ($password !== '') {
                        $u->password = $password;
                    }

                    if (!$u->email) {
                        $email = $baseEmail;
                        $counter = 1;
                        while (User::where('email', $email)->where('id', '<>', $u->id)->exists()) {
                            $email = strtolower(trim($username)) . $counter . '@import.local';
                            $counter++;
                        }
                        $u->email = $email;
                    }

                    if (Schema::hasColumn('users', 'aktif')) {
                        $u->aktif = 1;
                    }

                    $u->save();
                    $updated++;
                } else {
                    $email = $baseEmail;
                    $counter = 1;
                    while (User::where('email', $email)->exists()) {
                        $email = strtolower(trim($username)) . $counter . '@import.local';
                        $counter++;
                    }

                    $u = new User();
                    $u->name = $username;
                    $u->email = $email;
                    $u->password = $password !== '' ? $password : 'password';
                    $u->nama_lengkap = $namaLengkap ?: null;
                    $u->role = $role;
                    $u->cabang_id = $cabangId;

                    if (Schema::hasColumn('users', 'job_position')) {
                        $u->job_position = $jobPosisi ?: null;
                    }

                    if (Schema::hasColumn('users', 'aktif')) {
                        $u->aktif = 1;
                    }

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
                      ->orWhere('nama_lengkap', 'like', $s);
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
