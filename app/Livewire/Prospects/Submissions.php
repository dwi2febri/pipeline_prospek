<?php

namespace App\Livewire\Prospects;

use App\Models\Prospect;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Submissions extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public ?string $filterStatus = '';
    public ?int $detailId = null;
    public ?string $statusUpdate = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function openDetail(int $id): void
    {
        $p = Prospect::with(['cabang', 'creator', 'documents'])->findOrFail($id);

        $this->detailId = $p->id;
        $this->statusUpdate = $p->status ?: 'FOLLOW UP';

        $this->dispatch('open-prospect-detail-modal');
    }

    public function closeDetail(): void
    {
        $this->detailId = null;
        $this->statusUpdate = null;
        $this->resetValidation();
    }

    #[\Livewire\Attributes\On('forceCloseProspectDetailModal')]
    public function forceCloseProspectDetailModal(): void
    {
        $this->closeDetail();
    }

    public function updateStatus(): void
    {
        $this->validate([
            'statusUpdate' => ['required', 'in:FOLLOW UP,REJECTED,CLOSING'],
        ], [
            'statusUpdate.required' => 'Status wajib dipilih.',
            'statusUpdate.in' => 'Status hanya boleh FOLLOW UP, REJECTED, atau CLOSING.',
        ]);

        if (!$this->detailId) {
            return;
        }

        $p = Prospect::findOrFail($this->detailId);
        $p->status = $this->statusUpdate;
        $p->save();

        session()->flash('ok', 'Status prospek berhasil diperbarui.');

        $this->detailId = $p->id;
        $this->statusUpdate = $p->status;

        $this->dispatch('open-prospect-detail-modal');
    }

    public function render()
    {
        $pengajuIds = User::query()
            ->where(function ($q) {
                $q->where('role', 'PEGAWAI')
                  ->orWhere('role', 'AO')
                  ->orWhere('role', 'AO_KREDIT')
                  ->orWhere('role', 'AO_DANA')
                  ->orWhere('role', 'AO_REMEDIAL');
            })
            ->pluck('id');

        $items = Prospect::query()
            ->with(['cabang', 'creator'])
            ->whereIn('input_by', $pengajuIds)
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . trim($this->search) . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('nama', 'like', $s)
                      ->orWhere('no_hp', 'like', $s)
                      ->orWhere('nik', 'like', $s)
                      ->orWhere('status', 'like', $s);
                });
            })
            ->when($this->filterStatus !== null && $this->filterStatus !== '', function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->latest('tanggal_prospek')
            ->latest('id')
            ->paginate(10);

        $detail = null;
        if ($this->detailId) {
            $detail = Prospect::with(['cabang', 'creator', 'documents'])->find($this->detailId);
        }

        return view('livewire.prospects.submissions', compact('items', 'detail'))
            ->layout('layouts.bootstrap');
    }
}
