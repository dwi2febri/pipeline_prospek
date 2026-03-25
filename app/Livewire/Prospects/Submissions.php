<?php

namespace App\Livewire\Prospects;

use App\Models\Cabang;
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
    public ?string $filterPengambilan = '';
    public ?int $filterCabang = null;
    public string $filterBulan = '';
    public string $filterTahun = '';

    public ?int $detailId = null;
    public ?string $statusUpdate = null;
    public string $ambilStatus = '0';

    public bool $canViewDetail = false;
    public bool $showTakenMessage = false;
    public ?string $takenByUsername = null;
    public bool $isAdminOrManagement = false;
    public bool $hideActionForm = false;
    public bool $lockCabangFilter = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterPengambilan' => ['except' => ''],
        'filterCabang' => ['except' => ''],
        'filterBulan' => ['except' => ''],
        'filterTahun' => ['except' => ''],
    ];

    public function mount(): void
    {
        $role = $this->currentUserRole();
        $now = now();

        if (in_array($role, ['MANAJEMEN', 'SUPERVISOR', 'AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL'], true)) {
            $this->filterBulan = (string) $now->month;
            $this->filterTahun = (string) $now->year;
        }

        if (in_array($role, ['SUPERVISOR', 'AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL'], true)) {
            $this->filterCabang = (int) (auth()->user()->cabang_id ?? 0);
            $this->lockCabangFilter = true;
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterPengambilan(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCabang(): void
    {
        if ($this->lockCabangFilter) {
            $this->filterCabang = (int) (auth()->user()->cabang_id ?? 0);
        }

        $this->resetPage();
    }

    public function updatingFilterBulan(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTahun(): void
    {
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterPengambilan = '';

        if ($this->lockCabangFilter) {
            $this->filterCabang = (int) (auth()->user()->cabang_id ?? 0);
        } else {
            $this->filterCabang = null;
        }

        $role = $this->currentUserRole();
        $now = now();

        if (in_array($role, ['MANAJEMEN', 'SUPERVISOR', 'AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL'], true)) {
            $this->filterBulan = (string) $now->month;
            $this->filterTahun = (string) $now->year;
        } else {
            $this->filterBulan = '';
            $this->filterTahun = '';
        }

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

    protected function canBypassTakenLock(?string $role = null): bool
    {
        $role = $role ?: $this->currentUserRole();
        return in_array($role, ['ADMIN', 'MANAJEMEN', 'SUPERVISOR'], true);
    }

    protected function baseQuery()
    {
        $u = auth()->user();
        $role = $this->currentUserRole();

        if ($this->lockCabangFilter) {
            $this->filterCabang = (int) ($u->cabang_id ?? 0);
        }

        return Prospect::query()
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
            ->when($this->filterPengambilan !== null && $this->filterPengambilan !== '', function ($q) {
                $q->where('is_diambil', (int) $this->filterPengambilan);
            })
            ->when($this->filterCabang, function ($q) {
                $q->where('cabang_id', $this->filterCabang);
            })
            ->when($this->filterBulan !== '', function ($q) {
                $q->whereMonth('tanggal_prospek', (int) $this->filterBulan);
            })
            ->when($this->filterTahun !== '', function ($q) {
                $q->whereYear('tanggal_prospek', (int) $this->filterTahun);
            })
            ->when(
                $this->isCabangRestrictedRole($role) && !$this->filterCabang,
                function ($q) use ($u) {
                    $q->where('cabang_id', $u->cabang_id);
                }
            );
    }

    protected function esc($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }

    public function exportExcel()
    {
        $rows = $this->baseQuery()
            ->with(['cabang', 'creator'])
            ->latest('tanggal_prospek')
            ->latest('id')
            ->get();

        $namaFile = 'prospek_diajukan_' . now()->format('Ymd_His') . '.xls';

        $html = '';
        $html .= '<html>';
        $html .= '<head><meta charset="UTF-8"></head>';
        $html .= '<body>';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<th colspan="19" style="font-weight:bold; font-size:16px;">DATA PROSPEK DIAJUKAN</th>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<th>Tanggal Prospek</th>';
        $html .= '<th>Nama Prospek</th>';
        $html .= '<th>No HP</th>';
        $html .= '<th>NIK</th>';
        $html .= '<th>Username Pengaju</th>';
        $html .= '<th>Nama Lengkap Pengaju</th>';
        $html .= '<th>Kode Cabang</th>';
        $html .= '<th>Nama Cabang</th>';
        $html .= '<th>Jenis Produk</th>';
        $html .= '<th>Status</th>';
        $html .= '<th>Status Pengambilan</th>';
        $html .= '<th>Diambil Oleh</th>';
        $html .= '<th>Alamat</th>';
        $html .= '<th>Kab/Kota</th>';
        $html .= '<th>Kecamatan</th>';
        $html .= '<th>Desa</th>';
        $html .= '<th>Keterangan Usaha</th>';
        $html .= '<th>Catatan</th>';
        $html .= '<th>Latitude</th>';
        $html .= '<th>Longitude</th>';
        $html .= '</tr>';

        foreach ($rows as $p) {
            $html .= '<tr>';
            $html .= '<td>' . $this->esc(optional($p->tanggal_prospek ? \Illuminate\Support\Carbon::parse($p->tanggal_prospek) : null)->format('d/m/Y')) . '</td>';
            $html .= '<td>' . $this->esc($p->nama) . '</td>';
            $html .= '<td style="mso-number-format:\'@\';">' . $this->esc($p->no_hp) . '</td>';
            $html .= '<td style="mso-number-format:\'@\';">' . $this->esc($p->nik) . '</td>';
            $html .= '<td>' . $this->esc(optional($p->creator)->name) . '</td>';
            $html .= '<td>' . $this->esc(optional($p->creator)->nama_lengkap) . '</td>';
            $html .= '<td style="mso-number-format:\'@\';">' . $this->esc(optional($p->cabang)->kode_cabang) . '</td>';
            $html .= '<td>' . $this->esc(optional($p->cabang)->nama_cabang) . '</td>';
            $html .= '<td>' . $this->esc($p->jenis_produk) . '</td>';
            $html .= '<td>' . $this->esc($p->status) . '</td>';
            $html .= '<td>' . ((int)($p->is_diambil ?? 0) === 1 ? 'DIAMBIL' : 'BELUM') . '</td>';
            $html .= '<td>' . $this->esc($p->diambil_oleh) . '</td>';
            $html .= '<td>' . $this->esc($p->alamat) . '</td>';
            $html .= '<td>' . $this->esc($p->kab_kota) . '</td>';
            $html .= '<td>' . $this->esc($p->kecamatan) . '</td>';
            $html .= '<td>' . $this->esc($p->desa) . '</td>';
            $html .= '<td>' . $this->esc($p->keterangan_usaha) . '</td>';
            $html .= '<td>' . $this->esc($p->catatan) . '</td>';
            $html .= '<td style="mso-number-format:\'@\';">' . $this->esc($p->lokasi_lat) . '</td>';
            $html .= '<td style="mso-number-format:\'@\';">' . $this->esc($p->lokasi_lng) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';

        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $namaFile, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
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

        $this->hideActionForm = in_array($role, ['MANAJEMEN', 'SUPERVISOR'], true);

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

        if (!$this->canBypassTakenLock($role)) {
            if ((int) $p->is_diambil === 1 && !empty($p->diambil_oleh) && $p->diambil_oleh !== $u->name) {
                $this->showTakenMessage = true;
                $this->canViewDetail = false;
                $this->dispatch('open-prospect-detail-modal');
                return;
            }
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
        $this->hideActionForm = false;
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

        if (!$this->detailId || !$this->canViewDetail || $this->hideActionForm) {
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

            if (!$this->canBypassTakenLock($role)) {
                if ((int) $p->is_diambil === 1 && !empty($p->diambil_oleh) && $p->diambil_oleh !== $u->name) {
                    session()->flash('ok', 'Prospek ini sudah diambil user lain.');
                    return;
                }
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

        if (!$this->detailId || $this->hideActionForm) {
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

            if (!$this->canBypassTakenLock($role)) {
                if ((int) $p->is_diambil === 1 && !empty($p->diambil_oleh) && $p->diambil_oleh !== $u->name) {
                    session()->flash('ok', 'Prospek ini sudah diambil oleh ' . $p->diambil_oleh . '.');
                    return;
                }
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
        $items = $this->baseQuery()
            ->latest('tanggal_prospek')
            ->latest('id')
            ->paginate(10);

        $detail = null;
        if ($this->detailId) {
            $detail = Prospect::with(['cabang', 'creator', 'documents'])->find($this->detailId);
        }

        $cabangOptions = Cabang::query()
            ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 1 AND 28")
            ->orderByRaw("CAST(kode_cabang AS UNSIGNED) ASC")
            ->get(['id', 'kode_cabang', 'nama_cabang']);

        $bulanOptions = collect(range(1, 12))->map(function ($b) {
            return [
                'id' => $b,
                'label' => now()->copy()->month($b)->translatedFormat('F'),
            ];
        });

        $tahunNow = (int) now()->year;
        $tahunOptions = collect(range($tahunNow - 3, $tahunNow + 1));

        return view('livewire.prospects.submissions', compact(
            'items',
            'detail',
            'cabangOptions',
            'bulanOptions',
            'tahunOptions'
        ))->layout('layouts.bootstrap');
    }
}
