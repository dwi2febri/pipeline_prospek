<?php

namespace App\Livewire\Prospects;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Prospect;

class RecycleBin extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function restore(int $id): void
    {
        $p = Prospect::onlyTrashed()
            ->where('id', $id)
            ->firstOrFail();

        $p->restore();

        session()->flash('ok', 'Prospek berhasil dipulihkan.');
        $this->resetPage();
    }

    public function forceDelete(int $id): void
    {
        $p = Prospect::onlyTrashed()
            ->where('id', $id)
            ->firstOrFail();

        $p->forceDelete();

        session()->flash('ok', 'Prospek dihapus permanen.');
        $this->resetPage();
    }

    public function render()
    {
        $q = Prospect::onlyTrashed()
            ->with(['cabang', 'creator'])
            ->latest('deleted_at');

        if (trim($this->search) !== '') {
            $s = '%' . trim($this->search) . '%';

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
