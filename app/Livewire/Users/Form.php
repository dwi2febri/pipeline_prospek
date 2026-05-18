<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public ?int $id = null;

    public string $name = '';
    public ?string $nama_lengkap = null;
    public string $email = '';
    public string $role = 'PEGAWAI';
    public ?int $cabang_id = null;
    public int $aktif = 1;
    public ?string $job_position = null;

    public ?string $kode = null;
    public ?string $employee_id = null;
    public ?string $branch_name = null;
    public ?string $unit_kerja = null;
    public ?string $level = null;
    public ?string $group_jabatan = null;

    public string $password = '';
    public string $password_confirmation = '';

    public function mount($id = null)
    {
        $this->id = $id ? (int) $id : null;

        if ($this->id) {
            $u = User::findOrFail($this->id);
            $this->name = (string) $u->name;
            $this->nama_lengkap = $u->nama_lengkap;
            $this->email = (string) $u->email;
            $this->role = (string) $u->role;
            $this->cabang_id = $u->cabang_id ? (int) $u->cabang_id : null;
            $this->aktif = (int) $u->aktif;
            $this->job_position = $u->job_position;

            $this->kode = $u->kode ?? null;
            $this->employee_id = $u->employee_id ?? null;
            $this->branch_name = $u->branch_name ?? null;
            $this->unit_kerja = $u->unit_kerja ?? null;
            $this->level = $u->level ?? null;
            $this->group_jabatan = $u->group_jabatan ?? null;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'nama_lengkap' => ['nullable', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->id)
            ],
            'role' => ['required', 'in:ADMIN,MANAJEMEN,MANAJEMEN KANWIL,SUPERVISOR,AO,PEGAWAI'],
            'cabang_id' => ['nullable', 'integer'],
            'aktif' => ['required', 'in:0,1'],
            'password' => [$this->id ? 'nullable' : 'required', 'string', 'min:6', 'confirmed'],
            'job_position' => ['nullable', 'string', 'max:150'],

            'kode' => ['nullable', 'string', 'max:10'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'branch_name' => ['nullable', 'string', 'max:150'],
            'unit_kerja' => ['nullable', 'string', 'max:150'],
            'level' => ['nullable', 'string', 'max:150'],
            'group_jabatan' => ['nullable', 'string', 'max:100'],
        ];
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

    public function save()
    {
        $this->kode = $this->normalizeText($this->kode);
        $this->employee_id = $this->normalizeText($this->employee_id);
        $this->nama_lengkap = $this->normalizeText($this->nama_lengkap);
        $this->branch_name = $this->normalizeText($this->branch_name);
        $this->unit_kerja = $this->normalizeText($this->unit_kerja);
        $this->job_position = $this->normalizeText($this->job_position);
        $this->level = $this->normalizeText($this->level);
        $this->group_jabatan = $this->normalizeText($this->group_jabatan);
        $this->name = trim((string) $this->name);
        $this->email = trim((string) $this->email);
        $this->role = strtoupper(trim((string) $this->role));

        // PAKAI ROLE DARI FORM, JANGAN DITIMPA LAGI OLEH deriveRole()
        $this->validate();

        DB::beginTransaction();

        try {
            $u = $this->id ? User::findOrFail($this->id) : new User();
            $oldName = (string) ($u->name ?? '');

            $u->name = $this->name;
            $u->nama_lengkap = $this->nama_lengkap;
            $u->email = $this->email;
            $u->role = $this->role;
            $u->cabang_id = $this->cabang_id ?: null;
            $u->aktif = $this->aktif;
            $u->job_position = $this->job_position;

            $u->kode = $this->kode;
            $u->employee_id = $this->employee_id;
            $u->branch_name = $this->branch_name;
            $u->unit_kerja = $this->unit_kerja;
            $u->level = $this->level;
            $u->group_jabatan = $this->group_jabatan;

            if ($this->password !== '') {
                $u->password = Hash::make($this->password);
            }

            $u->save();

            if ($oldName !== '' && $oldName !== $u->name) {
                DB::table('prospects')
                    ->where('referral_user_id', $oldName)
                    ->update(['referral_user_id' => $u->name]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('ok', 'User gagal disimpan: ' . $e->getMessage());
            return null;
        }

        session()->flash('ok', 'User berhasil disimpan.');
        return redirect()->route('users.index');
    }

    public function render()
    {
        $cabangs = Cabang::query()
            ->where('aktif', 1)
            ->orderBy('kode_cabang')
            ->get();

        return view('livewire.users.form', compact('cabangs'))
            ->layout('layouts.bootstrap');
    }
}
