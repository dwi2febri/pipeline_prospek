<?php

namespace App\Livewire\Prospects;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Prospect;

class RecycleBin extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function restore(int $id): void
    {
        $p = Prospect::onlyTrashed()->findOrFail($id);

        // hanya admin (route sudah pakai middleware role:ADMIN)
        $p->restore();
        session()->flash('ok', 'Prospek berhasil dipulihkan.');
        $this->resetPage();
    }

    public function forceDelete(int $id): void
    {
        $p = Prospect::onlyTrashed()->findOrFail($id);

        // hapus permanen
        $p->forceDelete();
        session()->flash('ok', 'Prospek dihapus permanen.');
        $this->resetPage();
    }

    public function render()
    {
        $q = Prospect::onlyTrashed()
            ->with(['cabang','creator'])
            ->latest('deleted_at');

        if ($this->search !== '') {
            $s = '%' . $this->search . '%';
            $q->where(function ($w) use ($s) {
                $w->where('nama', 'like', $s)
                  ->orWhere('no_hp', 'like', $s)
                  ->orWhere('nik', 'like', $s);
            });
        }

        $items = $q->paginate(10);

        return view('livewire.prospects.recycle-bin', compact('items'))
            ->layout('layouts.bootstrap');
    }
}
