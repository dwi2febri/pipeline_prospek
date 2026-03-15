<?php

namespace App\Livewire\Prospects;

use App\Models\Prospect;
use App\Models\ProspectNotification;
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
    public string $ambilStatus = '0';

    public bool $canViewDetail = false;
    public bool $showTakenMessage = false;
    public ?string $takenByUsername = null;
    public bool $isAdminOrManagement = false;

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

    protected function currentUserRole(): string
    {
        return strtoupper(trim((string) (auth()->user()->role ?? '')));
    }

    protected function isAdminOrManagementRole(?string $role = null): bool
    {
        $role = $role ?: $this->currentUserRole();
        return in_array($role, ['ADMIN', 'MANAJEMEN'], true);
    }

    protected function isCabangRestrictedRole(?string $role = null): bool
    {
        $role = $role ?: $this->currentUserRole();

        return in_array($role, [
            'SUPERVISOR',
            'AO',
            'AO_KREDIT',
            'AO_DANA',
            'AO_REMEDIAL',
        ], true);
    }

    public function openDetail(int $id): void
    {
        $u = auth()->user();
        $role = $this->currentUserRole();

        $p = Prospect::with(['cabang', 'creator', 'documents'])->findOrFail($id);

        $this->detailId = $p->id;
        $this->statusUpdate = $p->status ?: 'FOLLOW UP';
        $this->ambilStatus = (string) ((int) ($p->is_diambil ?? 0));
        $this->canViewDetail = false;
        $this->showTakenMessage = false;
        $this->takenByUsername = $p->diambil_oleh;
        $this->isAdminOrManagement = $this->isAdminOrManagementRole($role);

        if ($this->isAdminOrManagementRole($role)) {
            $this->canViewDetail = true;
            $this->dispatch('open-prospect-detail-modal');
            return;
        }

        if ($this->isCabangRestrictedRole($role)) {
            if ((int) $p->cabang_id !== (int) $u->cabang_id) {
                session()->flash('ok', 'Prospek tidak bisa dibuka karena bukan cabang Anda.');
                return;
            }
        }

        if ((int) $p->is_diambil === 1 && !empty($p->diambil_oleh) && $p->diambil_oleh !== $u->name) {
            $this->showTakenMessage = true;
            $this->canViewDetail = false;
            $this->dispatch('open-prospect-detail-modal');
            return;
        }

        $this->canViewDetail = true;
        $this->dispatch('open-prospect-detail-modal');
    }

    public function closeDetail(): void
    {
        $this->detailId = null;
        $this->statusUpdate = null;
        $this->ambilStatus = '0';
        $this->canViewDetail = false;
        $this->showTakenMessage = false;
        $this->takenByUsername = null;
        $this->isAdminOrManagement = false;
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

        if (!$this->detailId || !$this->canViewDetail) {
            return;
        }

        $u = auth()->user();
        $role = $this->currentUserRole();

        $p = Prospect::findOrFail($this->detailId);
        $oldStatus = (string) $p->status;
        $newStatus = (string) $this->statusUpdate;

        if (!$this->isAdminOrManagementRole($role)) {
            if ($this->isCabangRestrictedRole($role) && (int) $p->cabang_id !== (int) $u->cabang_id) {
                session()->flash('ok', 'Anda tidak berhak mengubah status prospek ini.');
                return;
            }

            if ((int) $p->is_diambil === 1 && !empty($p->diambil_oleh) && $p->diambil_oleh !== $u->name) {
                session()->flash('ok', 'Prospek ini sudah diambil user lain.');
                return;
            }
        }

        $p->status = $newStatus;
        $p->save();

        if (
            $oldStatus !== $newStatus &&
            in_array($newStatus, ['CLOSING', 'REJECTED'], true) &&
            !empty($p->input_by)
        ) {
            $statusLabel = $newStatus === 'CLOSING' ? 'Closing' : 'Rejected';

            ProspectNotification::create([
                'user_id'     => $p->input_by,
                'prospect_id' => $p->id,
                'title'       => 'Status prospek diperbarui',
                'message'     => 'Prospek "' . ($p->nama ?: '-') . '" diubah menjadi ' . $statusLabel . '.',
                'status'      => $newStatus,
            ]);
        }

        session()->flash('ok', 'Status prospek berhasil diperbarui.');

        $this->openDetail($p->id);
    }

    public function updateAmbilStatus(): void
    {
        $this->validate([
            'ambilStatus' => ['required', 'in:0,1'],
        ], [
            'ambilStatus.required' => 'Status pengambilan wajib dipilih.',
            'ambilStatus.in' => 'Status pengambilan tidak valid.',
        ]);

        if (!$this->detailId) {
            return;
        }

        $u = auth()->user();
        $role = $this->currentUserRole();

        $p = Prospect::findOrFail($this->detailId);

        if (!$this->isAdminOrManagementRole($role)) {
            if ($this->isCabangRestrictedRole($role) && (int) $p->cabang_id !== (int) $u->cabang_id) {
                session()->flash('ok', 'Anda tidak berhak mengubah pengambilan prospek ini.');
                return;
            }

            if ((int) $p->is_diambil === 1 && !empty($p->diambil_oleh) && $p->diambil_oleh !== $u->name) {
                session()->flash('ok', 'Prospek ini sudah diambil oleh ' . $p->diambil_oleh . '.');
                return;
            }
        }

        if ($this->ambilStatus === '1') {
            $p->is_diambil = 1;
            $p->diambil_oleh = $u->name;
        } else {
            if (!$this->isAdminOrManagementRole($role)) {
                if (!empty($p->diambil_oleh) && $p->diambil_oleh !== $u->name) {
                    session()->flash('ok', 'Prospek ini tidak bisa dilepas karena bukan Anda yang mengambil.');
                    return;
                }
            }

            $p->is_diambil = 0;
            $p->diambil_oleh = null;
        }

        $p->save();

        session()->flash('ok', 'Status pengambilan prospek berhasil diperbarui.');

        $this->openDetail($p->id);
    }

    public function render()
    {
        $u = auth()->user();
        $role = $this->currentUserRole();

        $items = Prospect::query()
            ->with(['cabang', 'creator'])
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . trim($this->search) . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('nama', 'like', $s)
                      ->orWhere('no_hp', 'like', $s)
                      ->orWhere('nik', 'like', $s)
                      ->orWhere('status', 'like', $s)
                      ->orWhere('diambil_oleh', 'like', $s);
                });
            })
            ->when($this->filterStatus !== null && $this->filterStatus !== '', function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->when($this->isCabangRestrictedRole($role), function ($q) use ($u) {
                $q->where('cabang_id', $u->cabang_id);
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
