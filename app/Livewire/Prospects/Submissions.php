<?php

namespace App\Livewire\Prospects;

use App\Models\Cabang;
use App\Models\Prospect;
use App\Models\ProspectNotification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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

    public ?string $filterKanwil = '';
    public ?string $filterAo = '';
    public ?string $filterInputRole = '';

    public string $filterBulan = '';
    public string $filterTahun = '';
    public string $filterMode = 'all';

    public ?string $filterTanggalAwal = null;
    public ?string $filterTanggalAkhir = null;

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
        'filterKanwil' => ['except' => ''],
        'filterAo' => ['except' => ''],
        'filterInputRole' => ['except' => ''],
        'filterMode' => ['except' => 'all'],
        'filterBulan' => ['except' => ''],
        'filterTahun' => ['except' => ''],
        'filterTanggalAwal' => ['except' => ''],
        'filterTanggalAkhir' => ['except' => ''],
    ];

    public function mount(): void
    {
        $role = $this->currentUserRole();

        $this->filterMode = 'all';
        $this->filterBulan = '';
        $this->filterTahun = '';
        $this->filterTanggalAwal = null;
        $this->filterTanggalAkhir = null;
        $this->filterKanwil = '';
        $this->filterAo = '';
        $this->filterInputRole = '';

        if ($role === 'SUPERVISOR') {
            $this->filterCabang = (int) (auth()->user()->cabang_id ?? 0);
            $this->lockCabangFilter = true;
        }

        if ($role === 'MANAJEMEN KANWIL') {
            $kodeKanwil = $this->getUserKanwilKode();

            if (in_array($kodeKanwil, ['100', '200', '300', '400'], true)) {
                $this->filterKanwil = $kodeKanwil;
                $this->filterCabang = null;
                $this->lockCabangFilter = false;
            }
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

    public function updatingFilterKanwil(): void
    {
        if ($this->lockCabangFilter) {
            $this->filterKanwil = '';
            return;
        }

        if ($this->isManagementKanwilRole()) {
            $kodeKanwil = $this->getUserKanwilKode();

            if (in_array($kodeKanwil, ['100', '200', '300', '400'], true)) {
                $this->filterKanwil = $kodeKanwil;
            }
        }

        $this->filterCabang = null;
        $this->filterAo = '';
        $this->resetPage();
    }

    public function updatingFilterCabang(): void
    {
        if ($this->lockCabangFilter) {
            $this->filterCabang = (int) (auth()->user()->cabang_id ?? 0);
        } else {
            if ($this->isManagementKanwilRole()) {
                $allowedCabangIds = $this->getAllowedCabangIdsForManagementKanwil();

                if (!empty($this->filterCabang) && !in_array((int) $this->filterCabang, $allowedCabangIds, true)) {
                    $this->filterCabang = null;
                }

                $this->filterKanwil = $this->getUserKanwilKode();
            } else {
                $this->filterKanwil = '';
            }
        }

        $this->filterAo = '';
        $this->resetPage();
    }

    public function updatingFilterAo(): void
    {
        $this->resetPage();
    }

    public function updatingFilterInputRole(): void
    {
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

    public function updatingFilterMode(): void
    {
        if ($this->filterMode === 'all') {
            $this->filterBulan = '';
            $this->filterTahun = '';
            $this->filterTanggalAwal = null;
            $this->filterTanggalAkhir = null;
        } elseif ($this->filterMode === 'monthly') {
            $this->filterTanggalAwal = null;
            $this->filterTanggalAkhir = null;
        } elseif ($this->filterMode === 'range') {
            $this->filterBulan = '';
            $this->filterTahun = '';
        }

        $this->resetPage();
    }

    public function updatingFilterTanggalAwal(): void
    {
        if ($this->filterMode !== 'range') {
            $this->filterMode = 'range';
        }
        $this->resetPage();
    }

    public function updatingFilterTanggalAkhir(): void
    {
        if ($this->filterMode !== 'range') {
            $this->filterMode = 'range';
        }
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
        $this->filterAo = '';
        $this->filterInputRole = '';
        $this->filterMode = 'all';
        $this->filterBulan = '';
        $this->filterTahun = '';
        $this->filterTanggalAwal = null;
        $this->filterTanggalAkhir = null;

        if ($this->lockCabangFilter) {
            $this->filterCabang = (int) (auth()->user()->cabang_id ?? 0);
            $this->filterKanwil = '';
        } elseif ($this->isManagementKanwilRole()) {
            $this->filterKanwil = $this->getUserKanwilKode();
            $this->filterCabang = null;
        } else {
            $this->filterKanwil = '';
            $this->filterCabang = null;
        }

        $this->resetPage();
    }

    protected function currentUserRole(): string
    {
        return strtoupper(trim((string) (auth()->user()->role ?? '')));
    }

    protected function isManagementKanwilRole(?string $role = null): bool
    {
        $role = $role ?: $this->currentUserRole();
        return $role === 'MANAJEMEN KANWIL';
    }

    protected function getUserKanwilKode(): string
    {
        $user = auth()->user();

        if (!$user || empty($user->cabang_id)) {
            return '';
        }

        $cabang = Cabang::query()
            ->where('id', (int) $user->cabang_id)
            ->first(['kode_cabang']);

        return trim((string) ($cabang->kode_cabang ?? ''));
    }

    protected function getKanwilRange(string $kodeKanwil): array
    {
        return match ($kodeKanwil) {
            '100' => [1, 7],
            '200' => [8, 14],
            '300' => [15, 21],
            '400' => [22, 28],
            default => [0, 0],
        };
    }

    protected function getAllowedCabangIdsForManagementKanwil(): array
    {
        $kodeKanwil = $this->getUserKanwilKode();

        if (!in_array($kodeKanwil, ['100', '200', '300', '400'], true)) {
            return [];
        }

        [$start, $end] = $this->getKanwilRange($kodeKanwil);

        return Cabang::query()
            ->where(function ($q) use ($kodeKanwil, $start, $end) {
                $q->where('kode_cabang', $kodeKanwil)
                    ->orWhereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN {$start} AND {$end}");
            })
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();
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
        return in_array($role, ['ADMIN', 'MANAJEMEN', 'MANAJEMEN KANWIL'], true);
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
        return in_array($role, ['ADMIN', 'MANAJEMEN', 'MANAJEMEN KANWIL', 'SUPERVISOR'], true);
    }

    protected function normalizeDateRange(): void
    {
        if (
            !empty($this->filterTanggalAwal) &&
            !empty($this->filterTanggalAkhir) &&
            $this->filterTanggalAwal > $this->filterTanggalAkhir
        ) {
            [$this->filterTanggalAwal, $this->filterTanggalAkhir] = [$this->filterTanggalAkhir, $this->filterTanggalAwal];
        }
    }

    protected function getSelectedCabangIds(): array
    {
        if ($this->lockCabangFilter) {
            $forcedCabang = (int) (auth()->user()->cabang_id ?? 0);
            return $forcedCabang > 0 ? [$forcedCabang] : [];
        }

        if ($this->isManagementKanwilRole()) {
            $allowedCabangIds = $this->getAllowedCabangIdsForManagementKanwil();

            if (!empty($this->filterCabang)) {
                return in_array((int) $this->filterCabang, $allowedCabangIds, true)
                    ? [(int) $this->filterCabang]
                    : $allowedCabangIds;
            }

            return $allowedCabangIds;
        }

        if (!empty($this->filterCabang)) {
            return [(int) $this->filterCabang];
        }

        $kanwil = trim((string) $this->filterKanwil);

        if ($kanwil === '100') {
            return Cabang::query()
                ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 1 AND 7")
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();
        }

        if ($kanwil === '200') {
            return Cabang::query()
                ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 8 AND 14")
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();
        }

        if ($kanwil === '300') {
            return Cabang::query()
                ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 15 AND 21")
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();
        }

        if ($kanwil === '400') {
            return Cabang::query()
                ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 22 AND 28")
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();
        }

        return [];
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

    protected function getAoJobOrder(?string $jobPosition): int
    {
        $job = strtoupper(trim((string) $jobPosition));

        return match ($job) {
            'AO KREDIT' => 1,
            'AO DANA' => 2,
            'AO REMIDIAL', 'AO REMEDIAL', 'AO REMIDAL' => 3,
            default => 99,
        };
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

        $selects = ['id', 'name', 'nama_lengkap', 'role', 'fcm_token'];

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

    protected function getAoOptionsByCabangFilter()
    {
        $selectedCabangIds = $this->getSelectedCabangIds();

        $query = User::query()
            ->where('aktif', 1)
            ->where(function ($q) {
                $q->where('role', 'AO')
                  ->orWhere(function ($qq) {
                      $qq->whereIn(DB::raw('UPPER(COALESCE(job_position, ""))'), [
                          'AO KREDIT',
                          'AO DANA',
                          'AO REMIDIAL',
                          'AO REMEDIAL',
                          'AO REMIDAL'
                      ]);
                  });
            });

        if (!empty($selectedCabangIds)) {
            $query->whereIn('cabang_id', $selectedCabangIds);
        }

        $items = $query->get([
            'id',
            'name',
            'nama_lengkap',
            'job_position',
            'cabang_id',
            'role',
        ]);

        return $items
            ->map(function ($u) {
                $job = trim((string) $u->job_position);
                $kodePegawai = trim((string) $u->name);
                $namaPegawai = trim((string) ($u->nama_lengkap ?: $u->name));

                return (object) [
                    'id' => $u->id,
                    'name' => $u->name,
                    'nama_lengkap' => $u->nama_lengkap,
                    'job_position' => $job,
                    'cabang_id' => $u->cabang_id,
                    'kode_pegawai' => $kodePegawai,
                    'label' => $kodePegawai . ' - ' . $namaPegawai . ($job !== '' ? ' - ' . $job : ''),
                    'sort_order' => $this->getAoJobOrder($job),
                ];
            })
            ->sort(function ($a, $b) {
                if ($a->sort_order === $b->sort_order) {
                    return strcmp((string) $a->nama_lengkap, (string) $b->nama_lengkap);
                }
                return $a->sort_order <=> $b->sort_order;
            })
            ->values();
    }

    protected function baseQuery()
    {
        $u = auth()->user();
        $role = $this->currentUserRole();
        $allowedProduk = $this->getAllowedProdukByUser();

        $this->normalizeDateRange();

        if ($this->lockCabangFilter) {
            $this->filterCabang = (int) ($u->cabang_id ?? 0);
            $this->filterKanwil = '';
        }

        if ($this->isManagementKanwilRole()) {
            $this->filterKanwil = $this->getUserKanwilKode();
        }

        $selectedCabangIds = $this->getSelectedCabangIds();

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
            ->when(!empty($selectedCabangIds), function ($q) use ($selectedCabangIds) {
                $q->whereIn('cabang_id', $selectedCabangIds);
            })
            ->when($this->filterAo !== null && trim((string) $this->filterAo) !== '', function ($q) {
                $q->where('diambil_oleh', trim((string) $this->filterAo));
            })
            ->when($this->filterInputRole !== null && trim((string) $this->filterInputRole) !== '', function ($q) {
                $roleFilter = trim((string) $this->filterInputRole);
                $q->whereHas('creator', function ($creatorQ) use ($roleFilter) {
                    $creatorQ->where('role', $roleFilter);
                });
            })
            ->when($this->filterMode === 'monthly' && $this->filterBulan !== '', function ($q) {
                $q->whereMonth('tanggal_prospek', (int) $this->filterBulan);
            })
            ->when($this->filterMode === 'monthly' && $this->filterTahun !== '', function ($q) {
                $q->whereYear('tanggal_prospek', (int) $this->filterTahun);
            })
            ->when($this->filterMode === 'range' && filled($this->filterTanggalAwal), function ($q) {
                $q->whereDate('tanggal_prospek', '>=', $this->filterTanggalAwal);
            })
            ->when($this->filterMode === 'range' && filled($this->filterTanggalAkhir), function ($q) {
                $q->whereDate('tanggal_prospek', '<=', $this->filterTanggalAkhir);
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
            ->first(['id', 'name', 'nama_lengkap', 'fcm_token']);

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

        $this->kirimPushFcmPenugasanAo($ao, $prospect);
    }

    protected function kirimNotifStatusKePengaju(Prospect $prospect, string $newStatus): void
    {
        if (empty($prospect->input_by)) {
            return;
        }

        $statusUpper = strtoupper(trim($newStatus));

        $labelStatus = match ($statusUpper) {
            'FOLLOW UP' => 'Follow Up',
            'CLOSING'   => 'Closing',
            'REJECTED'  => 'Rejected',
            default     => $statusUpper,
        };

        $message = 'Prospek "' . ($prospect->nama ?: '-') . '" diubah menjadi ' . $labelStatus . '.';

        ProspectNotification::query()
            ->where('user_id', (int) $prospect->input_by)
            ->where('prospect_id', $prospect->id)
            ->where('status', $statusUpper)
            ->whereNull('read_at')
            ->delete();

        ProspectNotification::create([
            'user_id'     => (int) $prospect->input_by,
            'prospect_id' => $prospect->id,
            'title'       => 'Status prospek diperbarui',
            'message'     => $message,
            'status'      => $statusUpper,
            'read_at'     => null,
        ]);
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    protected function getFirebaseServiceAccount(): ?array
    {
        $path = storage_path('app/firebase/service-account.json');

        if (!file_exists($path)) {
            return null;
        }

        $json = json_decode(file_get_contents($path), true);

        return is_array($json) ? $json : null;
    }

    protected function getGoogleAccessToken(): ?string
    {
        $serviceAccount = $this->getFirebaseServiceAccount();

        if (!$serviceAccount || empty($serviceAccount['client_email']) || empty($serviceAccount['private_key'])) {
            return null;
        }

        $now = time();

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT',
        ];

        $claimSet = [
            'iss'   => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'exp'   => $now + 3600,
            'iat'   => $now,
        ];

        $base64Header = $this->base64UrlEncode(json_encode($header));
        $base64Claim  = $this->base64UrlEncode(json_encode($claimSet));
        $unsignedJwt  = $base64Header . '.' . $base64Claim;

        $signature = '';
        $ok = openssl_sign($unsignedJwt, $signature, $serviceAccount['private_key'], 'SHA256');

        if (!$ok) {
            return null;
        }

        $jwt = $unsignedJwt . '.' . $this->base64UrlEncode($signature);

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]);

        if (!$response->successful()) {
            return null;
        }

        return $response->json('access_token');
    }

    protected function kirimPushFcmPenugasanAo(User $ao, Prospect $prospect): void
    {
        if (empty($ao->fcm_token)) {
            return;
        }

        $serviceAccount = $this->getFirebaseServiceAccount();
        if (!$serviceAccount || empty($serviceAccount['project_id'])) {
            return;
        }

        $accessToken = $this->getGoogleAccessToken();
        if (!$accessToken) {
            return;
        }

        $url = 'https://fcm.googleapis.com/v1/projects/' . $serviceAccount['project_id'] . '/messages:send';

        $payload = [
            'message' => [
                'token' => $ao->fcm_token,
                'notification' => [
                    'title' => 'Prospek Ditugaskan',
                    'body'  => 'Anda ditugaskan untuk menindaklanjuti prospek: ' . ($prospect->nama ?? '-') . '.',
                ],
                'data' => [
                    'type' => 'prospect_assignment',
                    'prospect_id' => (string) $prospect->id,
                    'title' => 'Prospek Ditugaskan',
                    'message' => 'Anda ditugaskan untuk menindaklanjuti prospek: ' . ($prospect->nama ?? '-') . '.',
                ],
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'channel_id' => 'eprospek_channel',
                        'sound' => 'default',
                    ],
                ],
            ],
        ];

        try {
            Http::withToken($accessToken)
                ->acceptJson()
                ->post($url, $payload);
        } catch (\Throwable $e) {
            report($e);
        }
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

        if ($this->isManagementKanwilRole($role)) {
            $allowedCabangIds = $this->getAllowedCabangIdsForManagementKanwil();

            if (!in_array((int) $prospect->cabang_id, $allowedCabangIds, true)) {
                session()->flash('ok', 'Anda tidak berhak menugaskan prospek di luar wilayah kanwil Anda.');
                return;
            }
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
        $html .= '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<table border="1">';
        $html .= '<tr><th colspan="24" style="font-weight:bold; font-size:16px;">DATA PROSPEK DIAJUKAN</th></tr>';
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

        $html .= '</table></body></html>';

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

        if ($this->isManagementKanwilRole($role)) {
            $allowedCabangIds = $this->getAllowedCabangIdsForManagementKanwil();

            if (!in_array((int) $p->cabang_id, $allowedCabangIds, true)) {
                session()->flash('ok', 'Anda tidak berhak melihat prospek di luar wilayah kanwil Anda.');
                return;
            }
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
        $this->hideActionForm = in_array($role, ['MANAJEMEN', 'MANAJEMEN KANWIL', 'SUPERVISOR'], true);

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


    public function updateAmbilStatus(): void
    {
        $role = $this->currentUserRole();

        if (!$this->detailId || !$this->canViewDetail || $this->hideActionForm) {
            session()->flash('ok', 'Data prospek tidak dapat diperbarui.');
            return;
        }

        $this->validate([
            'ambilStatus' => ['required', 'in:0,1'],
        ], [
            'ambilStatus.required' => 'Status pengambilan wajib dipilih.',
            'ambilStatus.in' => 'Status pengambilan tidak valid.',
        ]);

        $u = auth()->user();
        $prospect = Prospect::findOrFail($this->detailId);

        $allowedProduk = $this->getAllowedProdukByUser();
        if (!empty($allowedProduk) && !in_array((string) $prospect->jenis_produk, $allowedProduk, true)) {
            session()->flash('ok', 'Anda tidak berhak mengubah pengambilan prospek dengan rekomendasi produk tersebut.');
            return;
        }

        if ($this->isAoViewerRole($role) && (string) $prospect->diambil_oleh !== (string) $u->name) {
            session()->flash('ok', 'Anda hanya bisa mengubah prospek yang ditugaskan ke Anda.');
            return;
        }

        if ($this->isManagementKanwilRole($role)) {
            $allowedCabangIds = $this->getAllowedCabangIdsForManagementKanwil();

            if (!in_array((int) $prospect->cabang_id, $allowedCabangIds, true)) {
                session()->flash('ok', 'Anda tidak berhak mengubah prospek di luar wilayah kanwil Anda.');
                return;
            }
        }

        $isDiambil = (int) $this->ambilStatus === 1;

        $prospect->is_diambil = $isDiambil ? 1 : 0;

        if (!$isDiambil) {
            $prospect->diambil_oleh = null;
        } else {
            if (empty($prospect->diambil_oleh) && $this->isAoViewerRole($role)) {
                $prospect->diambil_oleh = (string) $u->name;
            }

            if ((string) $prospect->status === 'OPEN' || empty($prospect->status)) {
                $prospect->status = 'FOLLOW UP';
            }
        }

        $prospect->save();

        session()->flash('ok', 'Status pengambilan berhasil diperbarui.');
        $this->dispatch('close-prospect-detail-modal');
        $this->closeDetail();
        $this->resetPage();
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
            session()->flash('ok', 'Anda hanya bisa mengubah prospek yang ditugaskan ke Anda.');
            return;
        }

        if ($this->isManagementKanwilRole($role)) {
            $allowedCabangIds = $this->getAllowedCabangIdsForManagementKanwil();

            if (!in_array((int) $p->cabang_id, $allowedCabangIds, true)) {
                session()->flash('ok', 'Anda tidak berhak mengubah prospek di luar wilayah kanwil Anda.');
                return;
            }
        }

        $p->status = $newStatus;

        if ($newStatus === 'CLOSING' && $this->hasNoRekeningColumn()) {
            $p->no_rekening = $this->noRekening;
            $p->is_diambil = 1;
            if (empty($p->diambil_oleh)) {
                $p->diambil_oleh = (string) $u->name;
            }
        }

        $p->save();

        if ($oldStatus !== $newStatus) {
            $this->kirimNotifStatusKePengaju($p, $newStatus);
        }

        session()->flash('ok', 'Status prospek berhasil diperbarui.');
        $this->dispatch('close-prospect-detail-modal');
        $this->closeDetail();
    }

    public function getDetailProperty()
    {
        if (!$this->detailId) {
            return null;
        }

        return Prospect::with(['cabang', 'creator', 'creator.cabang', 'documents'])->find($this->detailId);
    }

    public function render()
    {
        $this->normalizeDateRange();

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

        $detail = $this->detail;

        $role = $this->currentUserRole();
        $kodeKanwilUser = $this->getUserKanwilKode();

        $cabangOptionsQuery = Cabang::query()
            ->where('aktif', 1)
            ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 1 AND 28");

        if ($this->isManagementKanwilRole() && in_array($kodeKanwilUser, ['100', '200', '300', '400'], true)) {
            [$start, $end] = $this->getKanwilRange($kodeKanwilUser);

            $cabangOptionsQuery->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN {$start} AND {$end}");
        } elseif ($this->filterKanwil === '100') {
            $cabangOptionsQuery->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 1 AND 7");
        } elseif ($this->filterKanwil === '200') {
            $cabangOptionsQuery->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 8 AND 14");
        } elseif ($this->filterKanwil === '300') {
            $cabangOptionsQuery->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 15 AND 21");
        } elseif ($this->filterKanwil === '400') {
            $cabangOptionsQuery->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 22 AND 28");
        }

        $cabangOptions = $cabangOptionsQuery
            ->orderByRaw("CAST(kode_cabang AS UNSIGNED) ASC")
            ->get(['id', 'kode_cabang', 'nama_cabang']);

        $aoOptions = $this->getAoOptionsByCabangFilter();

        $allKanwilOptions = [
            ['id' => '100', 'label' => '100 - KANWIL SEMARANG'],
            ['id' => '200', 'label' => '200 - KANWIL SOLO'],
            ['id' => '300', 'label' => '300 - KANWIL BANYUMAS'],
            ['id' => '400', 'label' => '400 - KANWIL PEKALONGAN'],
        ];

        $kanwilOptions = $this->isManagementKanwilRole() && in_array($kodeKanwilUser, ['100', '200', '300', '400'], true)
            ? array_values(array_filter($allKanwilOptions, fn ($k) => $k['id'] === $kodeKanwilUser))
            : $allKanwilOptions;

        $filterModeOptions = [
            ['id' => 'all', 'label' => 'Semua Data'],
            ['id' => 'monthly', 'label' => 'Bulanan'],
            ['id' => 'range', 'label' => 'Range Tanggal'],
        ];

        $bulanOptions = collect(range(1, 12))->map(function ($b) {
            return [
                'id' => $b,
                'label' => now()->copy()->month($b)->translatedFormat('F'),
            ];
        });

        $tahunNow = (int) now()->year;
        $tahunOptions = collect(range($tahunNow - 3, $tahunNow + 1));

        $inputRoleOptions = [
            ['id' => 'AO', 'label' => 'AO'],
            ['id' => 'PEGAWAI', 'label' => 'PEGAWAI'],
        ];

        return view('livewire.prospects.submissions', compact(
            'items',
            'detail',
            'cabangOptions',
            'aoOptions',
            'kanwilOptions',
            'bulanOptions',
            'tahunOptions',
            'namaPengambilMap',
            'assignmentMap',
            'filterModeOptions',
            'inputRoleOptions'
        ))->layout('layouts.bootstrap');
    }
}
