<?php

namespace App\Livewire\Cabangs;

use Livewire\Component;
use App\Models\Cabang;

class Form extends Component
{
    public ?int $id = null;

    public string $kode_cabang = '';
    public string $nama_cabang = '';
    public ?string $alamat = null;
    public int $aktif = 1;

    public function mount($id = null): void
    {
        $this->id = $id ? (int)$id : null;

        if ($this->id) {
            $c = Cabang::findOrFail($this->id);
            $this->kode_cabang = (string)$c->kode_cabang;
            $this->nama_cabang = (string)$c->nama_cabang;
            $this->alamat      = $c->alamat;
            $this->aktif       = (int)$c->aktif;
        }
    }

    protected function rules(): array
    {
        return [
            'kode_cabang' => ['required','string','max:20'],
            'nama_cabang' => ['required','string','max:150'],
            'alamat'      => ['nullable','string','max:255'],
            'aktif'       => ['required','in:0,1'],
        ];
    }

    public function save()
    {
        $this->validate();

        // unik kode cabang
        $exists = Cabang::query()
            ->where('kode_cabang', $this->kode_cabang)
            ->when($this->id, fn($q) => $q->where('id','!=',$this->id))
            ->exists();

        if ($exists) {
            $this->addError('kode_cabang', 'Kode cabang sudah digunakan.');
            return;
        }

        $c = $this->id ? Cabang::findOrFail($this->id) : new Cabang();

        $c->kode_cabang = $this->kode_cabang;
        $c->nama_cabang = $this->nama_cabang;
        $c->alamat      = $this->alamat;
        $c->aktif       = $this->aktif;

        $c->save();

        session()->flash('ok', 'Cabang berhasil disimpan.');
        return redirect()->route('cabangs.index');
    }

    public function render()
    {
        return view('livewire.cabangs.form')
            ->layout('layouts.bootstrap');
    }
}
