<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Cabang;
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

    public string $password = '';
    public string $password_confirmation = '';

    public function mount($id = null)
    {
        $this->id = $id ? (int)$id : null;

        if ($this->id) {
            $u = User::findOrFail($this->id);
            $this->name = (string)$u->name;
            $this->nama_lengkap = $u->nama_lengkap;
            $this->email = (string)$u->email;
            $this->role = (string)$u->role;
            $this->cabang_id = $u->cabang_id ? (int)$u->cabang_id : null;
            $this->aktif = (int)$u->aktif;
            $this->job_position = $u->job_position;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required','string','max:150'],
            'nama_lengkap' => ['nullable','string','max:150'],
            'email' => [
                'required','email','max:255',
                Rule::unique('users','email')->ignore($this->id)
            ],
            'role' => ['required', 'in:ADMIN,MANAJEMEN,SUPERVISOR,AO,PEGAWAI'],
            'cabang_id' => ['nullable','integer'],
            'aktif' => ['required','in:0,1'],
            'password' => [$this->id ? 'nullable' : 'required','string','min:6','confirmed'],
            'job_position' => ['nullable','string','max:100'],
        ];
    }

    public function save()
    {
        $this->validate();

        $u = $this->id ? User::findOrFail($this->id) : new User();

        $u->name = $this->name;
        $u->nama_lengkap = $this->nama_lengkap;
        $u->email = $this->email;
        $u->role = $this->role;
        $u->cabang_id = $this->cabang_id ?: null;
        $u->aktif = $this->aktif;
        $u->job_position = $this->job_position ? trim($this->job_position) : null;

        if ($this->password !== '') {
            $u->password = Hash::make($this->password);
        }

        $this->role = strtoupper(trim($this->role));
        if (!in_array($this->role, ['ADMIN','MANAJEMEN','SUPERVISOR','AO','PEGAWAI'], true)) {
            $this->role = 'PEGAWAI';
        }
        $u->save();

        session()->flash('ok', 'User berhasil disimpan.');
        return redirect()->route('users.index');
    }

    public function render()
    {
        $cabangs = Cabang::query()->where('aktif',1)->orderBy('kode_cabang')->get();

        return view('livewire.users.form', compact('cabangs'))
            ->layout('layouts.bootstrap');
    }
}
