<?php

namespace App\Livewire\Prospects;

use App\Models\Cabang;
use App\Models\Prospect;
use App\Models\ProspectNotification;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
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
    public ?string $noRekening = null;

    public bool $canViewDetail = false;
    public bool $showTakenMessage = false;
    public ?string $takenByUsername = null;
    public ?string $takenByFullName = null;
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

        if (in_array($role, ['ADMIN', 'MANAJEMEN', 'SUPERVISOR', 'AO'], true)) {
            $this->filterBulan = (string) $now->month;
            $this->filterTahun = (string) $now->year;
        }

        if ($role === 'SUPERVISOR') {
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

    public function updatedNoRekening($value): void
    {
        $this->noRekening = preg_replace('/[^0-9]/', '', (string) $value);
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

        if (in_array($role, ['ADMIN', 'MANAJEMEN', 'SUPERVISOR', 'AO'], true)) {
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

    protected function currentUserJobPosition(): string
    {
        $u = auth()->user();

        $value = '';

        if (isset($u->job_position)) {
            $value = (string) $u->job_position;
        } elseif (isset($u->job_posisition)) {
            $value = (string) $u->job_posisition;
        }

        return strtoupper(trim($value));
    }

    protected function normalizeJobPosition(?string $value): string
    {
        return strtoupper(trim((string) $value));
    }

    protected function getUserJobPositionValue($user): string
    {
        if (isset($user->job_position) && $user->job_position !== null) {
            return (string) $user->job_position;
        }

        if (isset($user->job_posisition) && $user->job_posisition !== null) {
            return (string) $user->job_posisition;
        }

        return '';
    }

    protected function normalizeDigits(?string $value): ?string
    {
        $v = preg_replace('/[^0-9]/', '', (string) $value);
        return $v !== '' ? $v : null;
    }

    protected function hasNoRekeningColumn(): bool
    {
        return Schema::hasColumn('prospects', 'no_rekening');
    }

    protected function isAdminOrManagementRole(?string $role = null): bool
    {
        $role = $role ?: $this->currentUserRole();
        return in_array($role, ['ADMIN', 'MANAJEMEN'], true);
    }

    protected function isSupervisorRole(?string $role = null): bool
    {
        $role = $role ?: $this->currentUserRole();
        return $role === 'SUPERVISOR';
    }

    protected function isAoViewerRole(?string $role = null): bool
    {
        $role = $role ?: $this->currentUserRole();
        return $role === 'AO';
    }

    protected function canManageAssignment(?string $role = null): bool
    {
        $role = $role ?: $this->currentUserRole();
        return in_array($role, ['ADMIN', 'MANAJEMEN', 'SUPERVISOR'], true);
    }

    protected function getNamaLengkapUserByUsername(?string $username): ?string
    {
        $username = trim((string) $username);

        if ($username === '') {
            return null;
        }

        $user = User::query()
            ->where('name', $username)
            ->first(['id', 'name', 'nama_lengkap']);

        if (!$user) {
            return $username;
        }

        return $user->nama_lengkap ?: $user->name;
    }

    protected function getAllowedProdukByUser(): array
    {
        $role = $this->currentUserRole();
        $jobPosition = $this->currentUserJobPosition();

        if ($role !== 'AO') {
            return [];
        }

        if ($jobPosition === 'AO DANA') {
            return ['TABUNGAN', 'DEPOSITO'];
        }

        if ($jobPosition === 'AO KREDIT') {
            return ['KREDIT'];
        }

        if (in_array($jobPosition, ['AO REMIDIAL', 'AO REMEDIAL'], true)) {
            return ['ASET'];
        }

        return [];
    }

    protected function getAssignmentJobPositionsByProduk(?string $jenisProduk): array
    {
        $jenisProduk = strtoupper(trim((string) $jenisProduk));

        if (in_array($jenisProduk, ['TABUNGAN', 'DEPOSITO'], true)) {
            return ['AO DANA'];
        }

        if ($jenisProduk === 'KREDIT') {
            return ['AO KREDIT'];
        }

        if ($jenisProduk === 'ASET') {
            return ['AO REMIDIAL', 'AO REMEDIAL'];
        }

        return [];
    }

    protected function getAssignableAoOptions(?string $jenisProduk, ?int $cabangId): array
    {
        $jobPositions = $this->getAssignmentJobPositionsByProduk($jenisProduk);

        if (empty($jobPositions) || !$cabangId) {
            return [];
        }

        $query = User::query()
            ->where('aktif', 1)
            ->where('cabang_id', (int) $cabangId)
            ->where('role', 'AO')
            ->orderBy('name');

        $selects = ['id', 'name', 'nama_lengkap', 'role'];

        if (Schema::hasColumn('users', 'job_position')) {
            $selects[] = 'job_position';
        }
        if (Schema::hasColumn('users', 'job_posisition')) {
            $selects[] = 'job_posisition';
        }

        $users = $query->get($selects);

        return $users
            ->filter(function ($u) use ($jobPositions) {
                $job = $this->normalizeJobPosition($this->getUserJobPositionValue($u));
                return in_array($job, $jobPositions, true);
            })
            ->map(function ($u) {
                $job = trim($this->getUserJobPositionValue($u));
                $kodePegawai = (string) ($u->name ?? '-');
                $namaPegawai = (string) ($u->nama_lengkap ?: $u->name ?: '-');

                return [
                    'id'           => (int) $u->id,
                    'username'     => (string) $u->name,
                    'nama_lengkap' => $namaPegawai,
                    'job_position' => $job,
                    'label'        => $job . ' - ' . $kodePegawai . ' - ' . $namaPegawai,
                ];
            })
            ->unique('username')
            ->values()
            ->toArray();
    }

    protected function baseQuery()
    {
        $u = auth()->user();
        $role = $this->currentUserRole();
        $allowedProduk = $this->getAllowedProdukByUser();

        if ($this->lockCabangFilter) {
            $this->filterCabang = (int) ($u->cabang_id ?? 0);
        }

        return Prospect::query()
            ->with([
                'cabang',
                'creator',
                'creator.cabang',
                'documents',
            ])
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . trim($this->search) . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('nama', 'like', $s)
                        ->orWhere('no_hp', 'like', $s)
                        ->orWhere('nik', 'like', $s)
                        ->orWhere('status', 'like', $s)
                        ->orWhere('diambil_oleh', 'like', $s);

                    if ($this->hasNoRekeningColumn()) {
                        $w->orWhere('no_rekening', 'like', $s);
                    }
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
            ->when(!empty($allowedProduk), function ($q) use ($allowedProduk) {
                $q->whereIn('jenis_produk', $allowedProduk);
            })
            ->when($this->isAoViewerRole($role), function ($q) use ($u) {
                $q->where('diambil_oleh', (string) $u->name);
            })
            ->when($this->isSupervisorRole($role) && !$this->filterCabang, function ($q) use ($u) {
                $q->where('cabang_id', $u->cabang_id);
            });
    }

    protected function esc($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }

    protected function kirimNotifPenugasanAo(Prospect $prospect, string $usernameAo): void
    {
        $usernameAo = trim($usernameAo);

        if ($usernameAo === '') {
            return;
        }

        $ao = User::query()
            ->where('name', $usernameAo)
            ->first(['id', 'name', 'nama_lengkap']);

        if (!$ao) {
            return;
        }

        ProspectNotification::query()
            ->where('user_id', $ao->id)
            ->where('prospect_id', $prospect->id)
            ->where('status', 'ditugaskan')
            ->whereNull('read_at')
            ->delete();

        ProspectNotification::create([
            'user_id'     => $ao->id,
            'prospect_id' => $prospect->id,
            'title'       => 'Prospek Ditugaskan',
            'message'     => 'Anda ditugaskan untuk menindaklanjuti prospek: ' . ($prospect->nama ?? '-') . '.',
            'status'      => 'ditugaskan',
            'read_at'     => null,
        ]);
    }

    public function assignProspect(int $prospectId, string $username): void
    {
        $role = $this->currentUserRole();

        if (!$this->canManageAssignment($role)) {
            return;
        }

        $username = trim($username);
        if ($username === '') {
            return;
        }

        $prospect = Prospect::findOrFail($prospectId);

        if ($role === 'SUPERVISOR' && (int) $prospect->cabang_id !== (int) (auth()->user()->cabang_id ?? 0)) {
            session()->flash('ok', 'Anda tidak berhak menugaskan prospek di luar cabang Anda.');
            return;
        }

        $allowedOptions = collect($this->getAssignableAoOptions($prospect->jenis_produk, (int) $prospect->cabang_id))
            ->pluck('username')
            ->all();

        if (!in_array($username, $allowedOptions, true)) {
            session()->flash('ok', 'AO yang dipilih tidak sesuai dengan produk atau cabang prospek.');
            return;
        }

        $prospect->is_diambil = 1;
        $prospect->diambil_oleh = $username;

        if ((string) $prospect->status === 'OPEN' || empty($prospect->status)) {
            $prospect->status = 'FOLLOW UP';
        }

        $prospect->save();

        $this->kirimNotifPenugasanAo($prospect, $username);

        $this->takenByUsername = $prospect->diambil_oleh;
        $this->takenByFullName = $this->getNamaLengkapUserByUsername($prospect->diambil_oleh);

        session()->flash('ok', 'Penugasan prospek berhasil diperbarui.');
        $this->resetPage();
    }

    public function exportExcel()
    {
        $rows = $this->baseQuery()
            ->with(['cabang', 'creator', 'creator.cabang'])
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
        $html .= '<th colspan="24" style="font-weight:bold; font-size:16px;">DATA PROSPEK DIAJUKAN</th>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<th>Tanggal Prospek</th>';
        $html .= '<th>Nama Prospek</th>';
        $html .= '<th>No HP</th>';
        $html .= '<th>NIK</th>';
        $html .= '<th>Username Pengaju</th>';
        $html .= '<th>Nama Lengkap Pengaju</th>';
        $html .= '<th>Cabang Pengaju</th>';
        $html .= '<th>Kode Cabang Prospek</th>';
        $html .= '<th>Nama Cabang Prospek</th>';
        $html .= '<th>Jenis Produk</th>';
        $html .= '<th>Jenis Usaha</th>';
        $html .= '<th>Status</th>';
        $html .= '<th>Status Pengambilan</th>';
        $html .= '<th>Username Pengambil</th>';
        $html .= '<th>Nama Lengkap Pengambil</th>';
        $html .= '<th>No Rekening</th>';
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
            $namaPengambil = $this->getNamaLengkapUserByUsername($p->diambil_oleh);
            $cabangPengaju = optional($p->creator->cabang)->kode_cabang
                ? optional($p->creator->cabang)->kode_cabang . ' - ' . optional($p->creator->cabang)->nama_cabang
                : '-';

            $html .= '<tr>';
            $html .= '<td>' . $this->esc(optional($p->tanggal_prospek ? \Illuminate\Support\Carbon::parse($p->tanggal_prospek) : null)->format('d/m/Y')) . '</td>';
            $html .= '<td>' . $this->esc($p->nama) . '</td>';
            $html .= '<td style="mso-number-format:\'@\';">' . $this->esc($p->no_hp) . '</td>';
            $html .= '<td style="mso-number-format:\'@\';">' . $this->esc($p->nik) . '</td>';
            $html .= '<td>' . $this->esc(optional($p->creator)->name) . '</td>';
            $html .= '<td>' . $this->esc(optional($p->creator)->nama_lengkap) . '</td>';
            $html .= '<td>' . $this->esc($cabangPengaju) . '</td>';
            $html .= '<td style="mso-number-format:\'@\';">' . $this->esc(optional($p->cabang)->kode_cabang) . '</td>';
            $html .= '<td>' . $this->esc(optional($p->cabang)->nama_cabang) . '</td>';
            $html .= '<td>' . $this->esc($p->jenis_produk) . '</td>';
            $html .= '<td>' . $this->esc($p->jenis_usaha) . '</td>';
            $html .= '<td>' . $this->esc($p->status) . '</td>';
            $html .= '<td>' . ((int)($p->is_diambil ?? 0) === 1 ? 'DIAMBIL' : 'BELUM') . '</td>';
            $html .= '<td>' . $this->esc($p->diambil_oleh) . '</td>';
            $html .= '<td>' . $this->esc($namaPengambil) . '</td>';
            $html .= '<td style="mso-number-format:\'@\';">' . $this->esc($this->hasNoRekeningColumn() ? ($p->no_rekening ?? '') : '') . '</td>';
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

        $p = Prospect::with(['cabang', 'creator', 'creator.cabang', 'documents'])->findOrFail($id);

        $allowedProduk = $this->getAllowedProdukByUser();
        if (!empty($allowedProduk) && !in_array((string) $p->jenis_produk, $allowedProduk, true)) {
            session()->flash('ok', 'Anda tidak berhak melihat prospek dengan rekomendasi produk tersebut.');
            return;
        }

        if ($this->isAoViewerRole($role) && (string) $p->diambil_oleh !== (string) $u->name) {
            session()->flash('ok', 'Anda hanya bisa melihat prospek yang ditugaskan ke Anda.');
            return;
        }

        $this->detailId = $p->id;
        $this->statusUpdate = in_array((string) $p->status, ['FOLLOW UP', 'CLOSING', 'REJECTED'], true)
            ? (string) $p->status
            : 'FOLLOW UP';
        $this->ambilStatus = (string) ((int) ($p->is_diambil ?? 0));
        $this->noRekening = $this->hasNoRekeningColumn() ? ($p->no_rekening ?? null) : null;
        $this->canViewDetail = false;
        $this->showTakenMessage = false;
        $this->takenByUsername = $p->diambil_oleh;
        $this->takenByFullName = $this->getNamaLengkapUserByUsername($p->diambil_oleh);
        $this->isAdminOrManagement = $this->isAdminOrManagementRole($role);
        $this->hideActionForm = in_array($role, ['MANAJEMEN', 'SUPERVISOR'], true);

        if ($this->isAdminOrManagementRole($role)) {
            $this->canViewDetail = true;
            $this->dispatch('open-prospect-detail-modal');
            return;
        }

        if ($this->isSupervisorRole($role)) {
            if ((int) $p->cabang_id !== (int) ($u->cabang_id ?? 0)) {
                session()->flash('ok', 'Prospek tidak bisa dibuka karena bukan cabang Anda.');
                return;
            }

            $this->canViewDetail = true;
            $this->dispatch('open-prospect-detail-modal');
            return;
        }

        if ($this->isAoViewerRole($role)) {
            if ((string) $p->diambil_oleh !== (string) $u->name) {
                session()->flash('ok', 'Prospek ini bukan penugasan Anda.');
                return;
            }

            $this->canViewDetail = true;
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
        $this->noRekening = null;
        $this->canViewDetail = false;
        $this->showTakenMessage = false;
        $this->takenByUsername = null;
        $this->takenByFullName = null;
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
        $role = $this->currentUserRole();

        $allowedStatuses = $this->isAoViewerRole($role)
            ? ['REJECTED', 'CLOSING']
            : ['FOLLOW UP', 'REJECTED', 'CLOSING'];

        $this->validate([
            'statusUpdate' => ['required', 'in:' . implode(',', $allowedStatuses)],
        ], [
            'statusUpdate.required' => 'Status wajib dipilih.',
            'statusUpdate.in' => 'Status tidak valid.',
        ]);

        if ($this->statusUpdate === 'CLOSING') {
            $this->validate([
                'noRekening' => ['required', 'regex:/^[0-9]+$/', 'max:50'],
            ], [
                'noRekening.required' => 'No. rekening wajib diisi saat status Closing.',
                'noRekening.regex' => 'No. rekening hanya boleh angka.',
            ]);
        }

        if (!$this->detailId || !$this->canViewDetail || $this->hideActionForm) {
            return;
        }

        $u = auth()->user();
        $p = Prospect::findOrFail($this->detailId);
        $oldStatus = (string) $p->status;
        $newStatus = (string) $this->statusUpdate;

        $allowedProduk = $this->getAllowedProdukByUser();
        if (!empty($allowedProduk) && !in_array((string) $p->jenis_produk, $allowedProduk, true)) {
            session()->flash('ok', 'Anda tidak berhak mengubah status prospek dengan rekomendasi produk tersebut.');
            return;
        }

        if ($this->isAoViewerRole($role) && (string) $p->diambil_oleh !== (string) $u->name) {
            session()->flash('ok', 'Anda tidak berhak mengubah status prospek ini.');
            return;
        }

        if ($this->isSupervisorRole($role) && (int) $p->cabang_id !== (int) ($u->cabang_id ?? 0)) {
            session()->flash('ok', 'Anda tidak berhak mengubah status prospek ini.');
            return;
        }

        $p->status = $newStatus;

        if ($this->hasNoRekeningColumn()) {
            if ($newStatus === 'CLOSING') {
                $p->no_rekening = $this->normalizeDigits($this->noRekening);
            } elseif ($this->isAoViewerRole($role)) {
                $p->no_rekening = null;
            }
        }

        $p->save();

        if (
            $oldStatus !== $newStatus &&
            in_array($newStatus, ['CLOSING', 'REJECTED'], true) &&
            !empty($p->input_by)
        ) {
            ProspectNotification::create([
                'user_id'     => $p->input_by,
                'prospect_id' => $p->id,
                'title'       => 'Status prospek diperbarui',
                'message'     => 'Prospek "' . ($p->nama ?: '-') . '" diubah menjadi ' . ($newStatus === 'CLOSING' ? 'Closing' : 'Rejected') . '.',
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

        $allowedProduk = $this->getAllowedProdukByUser();
        if (!empty($allowedProduk) && !in_array((string) $p->jenis_produk, $allowedProduk, true)) {
            session()->flash('ok', 'Anda tidak berhak mengubah pengambilan prospek dengan rekomendasi produk tersebut.');
            return;
        }

        if ($this->isAoViewerRole($role) && (string) $p->diambil_oleh !== (string) $u->name) {
            session()->flash('ok', 'Anda tidak berhak mengubah penugasan prospek ini.');
            return;
        }

        if ($this->isSupervisorRole($role) && (int) $p->cabang_id !== (int) ($u->cabang_id ?? 0)) {
            session()->flash('ok', 'Anda tidak berhak mengubah penugasan prospek ini.');
            return;
        }

        if ($this->ambilStatus === '1') {
            $p->is_diambil = 1;
            $p->diambil_oleh = $u->name;

            if ((string) $p->status === 'OPEN' || empty($p->status)) {
                $p->status = 'FOLLOW UP';
                $this->statusUpdate = 'FOLLOW UP';
            }
        } else {
            if ($this->isAoViewerRole($role)) {
                if (!empty($p->diambil_oleh) && $p->diambil_oleh !== $u->name) {
                    session()->flash('ok', 'Prospek ini tidak bisa dilepas karena bukan Anda yang mengambil.');
                    return;
                }
            }

            $p->is_diambil = 0;
            $p->diambil_oleh = null;
        }

        $p->save();

        $this->takenByUsername = $p->diambil_oleh;
        $this->takenByFullName = $this->getNamaLengkapUserByUsername($p->diambil_oleh);

        session()->flash('ok', 'Status pengambilan prospek berhasil diperbarui.');
        $this->openDetail($p->id);
    }

    public function render()
    {
        $items = $this->baseQuery()
            ->latest('tanggal_prospek')
            ->latest('id')
            ->paginate(10);

        $usernamesPengambil = collect($items->items())
            ->pluck('diambil_oleh')
            ->filter(fn ($v) => trim((string) $v) !== '')
            ->unique()
            ->values();

        $namaPengambilMap = User::query()
            ->whereIn('name', $usernamesPengambil)
            ->get(['name', 'nama_lengkap'])
            ->mapWithKeys(function ($u) {
                return [$u->name => ($u->nama_lengkap ?: $u->name)];
            })
            ->toArray();

        $assignmentMap = [];
        foreach ($items as $p) {
            $assignmentMap[$p->id] = $this->getAssignableAoOptions($p->jenis_produk, (int) $p->cabang_id);
        }

        $detail = null;
        if ($this->detailId) {
            $detail = Prospect::with(['cabang', 'creator', 'creator.cabang', 'documents'])->find($this->detailId);
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
            'tahunOptions',
            'namaPengambilMap',
            'assignmentMap'
        ))->layout('layouts.bootstrap');
    }
}
