<?php

namespace App\Livewire\Cabangs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cabang;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleAktif(int $id): void
    {
        $c = Cabang::findOrFail($id);
        $c->aktif = (int)$c->aktif === 1 ? 0 : 1;
        $c->save();

        session()->flash('ok', 'Status cabang berhasil diubah.');
    }

    public function render()
    {
        $items = Cabang::query()
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . trim($this->search) . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('kode_cabang', 'like', $s)
                      ->orWhere('nama_cabang', 'like', $s)
                      ->orWhere('alamat', 'like', $s);
                });
            })
            ->orderByRaw('CAST(kode_cabang AS UNSIGNED) ASC')
            ->paginate(10);

        return view('livewire.cabangs.index', compact('items'))
            ->layout('layouts.bootstrap');
    }
}
