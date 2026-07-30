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
        $userId = auth()->id();

        $p = Prospect::onlyTrashed()
            ->where('id', $id)
            ->where('input_by', $userId)
            ->firstOrFail();

        $p->restore();

        session()->flash('ok', 'Prospek berhasil dipulihkan.');
        $this->resetPage();
    }

    public function forceDelete(int $id): void
    {
        $userId = auth()->id();

        $p = Prospect::onlyTrashed()
            ->where('id', $id)
            ->where('input_by', $userId)
            ->firstOrFail();

        if (strtoupper(trim((string) $p->status)) === 'CLOSING') {
            session()->flash('error', 'Prospek berstatus Closing tidak dapat dihapus permanen.');
            return;
        }

        $p->forceDelete();

        session()->flash('ok', 'Prospek dihapus permanen.');
        $this->resetPage();
    }

    public function render()
    {
        $userId = auth()->id();

        $q = Prospect::onlyTrashed()
            ->with(['cabang', 'creator'])
            ->where('input_by', $userId)
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
