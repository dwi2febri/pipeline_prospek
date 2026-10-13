<?php

namespace App\Livewire\Reports;

use App\Models\Cabang;
use App\Models\Prospect;
use App\Models\User;
use App\Services\ProspectValidationReport;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProspectRecap extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $activeTab = 'kc';

    public $filterCabang = '';
    public string $filterMode = 'all'; // all | monthly | range
    public string $filterBulan = '';
    public string $filterTahun = '';
    public string $filterTanggalAwal = '';
    public string $filterTanggalAkhir = '';
    public string $search = '';

    public string $sortFieldPegawai = 'total_pengajuan';
    public string $sortDirectionPegawai = 'desc';

    public string $sortFieldKc = 'kode_cabang';
    public string $sortDirectionKc = 'asc';

    public string $sortFieldPengaju = 'kode_cabang';
    public string $sortDirectionPengaju = 'asc';

    public bool $lockCabangFilter = false;

    public ?int $detailPegawaiId = null;
    public string $detailProductTab = 'all';
    public string $detailStatus = 'ALL';
    public string $detailFilterMode = 'all';
    public string $detailFilterBulan = '';
    public string $detailFilterTahun = '';
    public string $detailFilterTanggalAwal = '';
    public string $detailFilterTanggalAkhir = '';

    public ?int $detailKcCabangId = null;
    public string $detailKcStatus = 'ALL';
    public string $detailKcProductTab = 'all';
    public string $detailKcFilterMode = 'all';
    public string $detailKcFilterBulan = '';
    public string $detailKcFilterTahun = '';
    public string $detailKcFilterTanggalAwal = '';
    public string $detailKcFilterTanggalAkhir = '';

    protected $queryString = [
        'activeTab' => ['except' => 'kc'],
        'filterCabang' => ['except' => ''],
        'filterMode' => ['except' => 'all'],
        'filterBulan' => ['except' => ''],
        'filterTahun' => ['except' => ''],
        'filterTanggalAwal' => ['except' => ''],
        'filterTanggalAkhir' => ['except' => ''],
        'search' => ['except' => ''],
        'sortFieldPegawai' => ['except' => 'total_pengajuan'],
        'sortDirectionPegawai' => ['except' => 'desc'],
        'sortFieldKc' => ['except' => 'kode_cabang'],
        'sortDirectionKc' => ['except' => 'asc'],
        'sortFieldPengaju' => ['except' => 'kode_cabang'],
        'sortDirectionPengaju' => ['except' => 'asc'],
    ];

    public function mount(): void
    {
        $role = $this->getRoleUserLogin();

        if ($role === 'SUPERVISOR') {
            $this->filterCabang = (string) (auth()->user()->cabang_id ?? '');
            $this->lockCabangFilter = true;
        }

        if ($role === 'MANAJEMEN KANWIL') {
            $kodeKanwil = $this->getUserKanwilKode();

            if (in_array($kodeKanwil, ['100', '200', '300', '400'], true)) {
                $this->filterCabang = $kodeKanwil;
                $this->lockCabangFilter = false;
            }
        }
    }

    public function setActiveTab(string $tab): void
    {
        if (!in_array($tab, ['kc', 'pegawai', 'pengaju'], true)) {
            return;
        }

        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCabang(): void
    {
        if ($this->getRoleUserLogin() === 'SUPERVISOR') {
            $this->filterCabang = (string) (auth()->user()->cabang_id ?? '');
        }

        $this->resetPage();
    }

    public function updatingFilterMode(): void
    {
        if ($this->filterMode === 'all') {
            $this->filterBulan = '';
            $this->filterTahun = '';
            $this->filterTanggalAwal = '';
            $this->filterTanggalAkhir = '';
        } elseif ($this->filterMode === 'monthly') {
            $now = now();
            $this->filterBulan = $this->filterBulan !== '' ? $this->filterBulan : (string) $now->month;
            $this->filterTahun = $this->filterTahun !== '' ? $this->filterTahun : (string) $now->year;
            $this->filterTanggalAwal = '';
            $this->filterTanggalAkhir = '';
        } elseif ($this->filterMode === 'range') {
            $this->filterBulan = '';
            $this->filterTahun = '';
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

    public function updatingFilterTanggalAwal(): void
    {
        if ($this->filterMode !== 'range') {
            $this->filterMode = 'range';
            $this->filterBulan = '';
            $this->filterTahun = '';
        }

        $this->resetPage();
    }

    public function updatingFilterTanggalAkhir(): void
    {
        if ($this->filterMode !== 'range') {
            $this->filterMode = 'range';
            $this->filterBulan = '';
            $this->filterTahun = '';
        }

        $this->resetPage();
    }

    public function updatedDetailFilterMode(): void
    {
        if ($this->detailFilterMode === 'all') {
            $this->detailFilterBulan = '';
            $this->detailFilterTahun = '';
            $this->detailFilterTanggalAwal = '';
            $this->detailFilterTanggalAkhir = '';
        } elseif ($this->detailFilterMode === 'monthly') {
            $now = now();
            $this->detailFilterBulan = $this->detailFilterBulan !== '' ? $this->detailFilterBulan : (string) $now->month;
            $this->detailFilterTahun = $this->detailFilterTahun !== '' ? $this->detailFilterTahun : (string) $now->year;
            $this->detailFilterTanggalAwal = '';
            $this->detailFilterTanggalAkhir = '';
        } else {
            $this->detailFilterBulan = '';
            $this->detailFilterTahun = '';
        }

        $this->dispatch('open-detail-pegawai-modal');
    }

    public function updatedDetailFilterBulan(): void
    {
        $this->dispatch('open-detail-pegawai-modal');
    }

    public function updatedDetailFilterTahun(): void
    {
        $this->dispatch('open-detail-pegawai-modal');
    }

    public function updatedDetailFilterTanggalAwal(): void
    {
        if ($this->detailFilterMode !== 'range') {
            $this->detailFilterMode = 'range';
            $this->detailFilterBulan = '';
            $this->detailFilterTahun = '';
        }

        $this->dispatch('open-detail-pegawai-modal');
    }

    public function updatedDetailFilterTanggalAkhir(): void
    {
        if ($this->detailFilterMode !== 'range') {
            $this->detailFilterMode = 'range';
            $this->detailFilterBulan = '';
            $this->detailFilterTahun = '';
        }

        $this->dispatch('open-detail-pegawai-modal');
    }

    public function updatedDetailKcFilterMode(): void
    {
        if ($this->detailKcFilterMode === 'all') {
            $this->detailKcFilterBulan = '';
            $this->detailKcFilterTahun = '';
            $this->detailKcFilterTanggalAwal = '';
            $this->detailKcFilterTanggalAkhir = '';
        } elseif ($this->detailKcFilterMode === 'monthly') {
            $now = now();
            $this->detailKcFilterBulan = $this->detailKcFilterBulan !== '' ? $this->detailKcFilterBulan : (string) $now->month;
            $this->detailKcFilterTahun = $this->detailKcFilterTahun !== '' ? $this->detailKcFilterTahun : (string) $now->year;
            $this->detailKcFilterTanggalAwal = '';
            $this->detailKcFilterTanggalAkhir = '';
        } else {
            $this->detailKcFilterBulan = '';
            $this->detailKcFilterTahun = '';
        }

        $this->dispatch('open-detail-kc-modal');
    }

    public function updatedDetailKcFilterBulan(): void
    {
        $this->dispatch('open-detail-kc-modal');
    }

    public function updatedDetailKcFilterTahun(): void
    {
        $this->dispatch('open-detail-kc-modal');
    }

    public function updatedDetailKcFilterTanggalAwal(): void
    {
        if ($this->detailKcFilterMode !== 'range') {
            $this->detailKcFilterMode = 'range';
            $this->detailKcFilterBulan = '';
            $this->detailKcFilterTahun = '';
        }

        $this->dispatch('open-detail-kc-modal');
    }

    public function updatedDetailKcFilterTanggalAkhir(): void
    {
        if ($this->detailKcFilterMode !== 'range') {
            $this->detailKcFilterMode = 'range';
            $this->detailKcFilterBulan = '';
            $this->detailKcFilterTahun = '';
        }

        $this->dispatch('open-detail-kc-modal');
    }

    protected function getRoleUserLogin(): string
    {
        return strtoupper(trim((string) (auth()->user()->role ?? '')));
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

    protected function normalizeDateRange(): void
    {
        if ($this->filterTanggalAwal !== '' && $this->filterTanggalAkhir !== '' && $this->filterTanggalAwal > $this->filterTanggalAkhir) {
            [$this->filterTanggalAwal, $this->filterTanggalAkhir] = [$this->filterTanggalAkhir, $this->filterTanggalAwal];
        }
    }

    protected function normalizeDetailDateRange(): void
    {
        if ($this->detailFilterTanggalAwal !== '' && $this->detailFilterTanggalAkhir !== '' && $this->detailFilterTanggalAwal > $this->detailFilterTanggalAkhir) {
            [$this->detailFilterTanggalAwal, $this->detailFilterTanggalAkhir] = [$this->detailFilterTanggalAkhir, $this->detailFilterTanggalAwal];
        }

        if ($this->detailKcFilterTanggalAwal !== '' && $this->detailKcFilterTanggalAkhir !== '' && $this->detailKcFilterTanggalAwal > $this->detailKcFilterTanggalAkhir) {
            [$this->detailKcFilterTanggalAwal, $this->detailKcFilterTanggalAkhir] = [$this->detailKcFilterTanggalAkhir, $this->detailKcFilterTanggalAwal];
        }
    }

    protected function getSelectedCabangIds(): array
    {
        $value = trim((string) $this->filterCabang);
        $role = $this->getRoleUserLogin();

        if ($role === 'SUPERVISOR') {
            $value = (string) (auth()->user()->cabang_id ?? '');
        }

        if ($role === 'MANAJEMEN KANWIL') {
            $kodeKanwilUser = $this->getUserKanwilKode();

            if (in_array($kodeKanwilUser, ['100', '200', '300', '400'], true)) {
                [$start, $end] = $this->getKanwilRange($kodeKanwilUser);

                $allowedCabangIds = Cabang::query()
                    ->where(function ($q) use ($kodeKanwilUser, $start, $end) {
                        $q->where('kode_cabang', $kodeKanwilUser)
                          ->orWhereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN {$start} AND {$end}");
                    })
                    ->pluck('id')
                    ->map(fn ($v) => (int) $v)
                    ->toArray();

                if ($value === '') {
                    $value = $kodeKanwilUser;
                }

                if (in_array($value, ['100', '200', '300', '400'], true)) {
                    if ($value !== $kodeKanwilUser) {
                        $value = $kodeKanwilUser;
                    }
                } elseif (is_numeric($value)) {
                    $selectedCabangId = (int) $value;

                    if (!in_array($selectedCabangId, $allowedCabangIds, true)) {
                        return $allowedCabangIds;
                    }
                } else {
                    return $allowedCabangIds;
                }
            }
        }

        if ($value === '' || !is_numeric($value)) {
            return [];
        }

        $num = (int) $value;

        if ($num === 100) {
            return Cabang::query()
                ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 1 AND 7")
                ->pluck('id')
                ->map(fn ($v) => (int) $v)
                ->toArray();
        }

        if ($num === 200) {
            return Cabang::query()
                ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 8 AND 14")
                ->pluck('id')
                ->map(fn ($v) => (int) $v)
                ->toArray();
        }

        if ($num === 300) {
            return Cabang::query()
                ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 15 AND 21")
                ->pluck('id')
                ->map(fn ($v) => (int) $v)
                ->toArray();
        }

        if ($num === 400) {
            return Cabang::query()
                ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 22 AND 28")
                ->pluck('id')
                ->map(fn ($v) => (int) $v)
                ->toArray();
        }

        return [$num];
    }

    protected function applyCabangFilterToCabangQuery($query, string $alias = 'cabangs')
    {
        $ids = $this->getSelectedCabangIds();

        if (empty($ids)) {
            return $query;
        }

        return $query->whereIn("{$alias}.id", $ids);
    }

    protected function applyCabangFilterToUserCabangQuery($query)
    {
        $ids = $this->getSelectedCabangIds();

        if (empty($ids)) {
            return $query;
        }

        return $query->whereIn('users.cabang_id', $ids);
    }

    protected function applyProspectDateJoinFilter($join, string $mode, string $bulan, string $tahun, string $tanggalAwal, string $tanggalAkhir, string $column = 'prospects.tanggal_prospek')
    {
        if ($mode === 'range') {
            if ($tanggalAwal !== '') {
                $join->whereDate($column, '>=', $tanggalAwal);
            }
            if ($tanggalAkhir !== '') {
                $join->whereDate($column, '<=', $tanggalAkhir);
            }
        } elseif ($mode === 'monthly') {
            $bulanValue = (int) ($bulan !== '' ? $bulan : now()->month);
            $tahunValue = (int) ($tahun !== '' ? $tahun : now()->year);

            $join->whereMonth($column, $bulanValue)
                ->whereYear($column, $tahunValue);
        }

        return $join;
    }

    protected function applyProspectDateWhereFilter($query, string $mode, string $bulan, string $tahun, string $tanggalAwal, string $tanggalAkhir, string $column = 'tanggal_prospek')
    {
        if ($mode === 'range') {
            if ($tanggalAwal !== '') {
                $query->whereDate($column, '>=', $tanggalAwal);
            }
            if ($tanggalAkhir !== '') {
                $query->whereDate($column, '<=', $tanggalAkhir);
            }
        } elseif ($mode === 'monthly') {
            $bulanValue = (int) ($bulan !== '' ? $bulan : now()->month);
            $tahunValue = (int) ($tahun !== '' ? $tahun : now()->year);

            $query->whereMonth($column, $bulanValue)
                ->whereYear($column, $tahunValue);
        }

        return $query;
    }

    public function sortBy(string $field): void
    {
        if ($this->activeTab === 'kc') {
            $allowed = [
                'kode_cabang',
                'nama_cabang',
                'total_pengajuan',
                'total_pengajuan_ao',
                'total_pengajuan_non_ao',
                'total_open',
                'total_follow_up',
                'total_closing',
                'total_rejected',
            ];

            if (!in_array($field, $allowed, true)) {
                return;
            }

            if ($this->sortFieldKc === $field) {
                $this->sortDirectionKc = $this->sortDirectionKc === 'asc' ? 'desc' : 'asc';
            } else {
                $this->sortFieldKc = $field;
                $this->sortDirectionKc = in_array($field, ['kode_cabang', 'nama_cabang'], true) ? 'asc' : 'desc';
            }
        } elseif ($this->activeTab === 'pengaju') {
            $allowed = [
                'kode_cabang',
                'nama_cabang',
                'total_pengajuan',
                'total_pengajuan_ao',
                'total_pengajuan_non_ao',
                'total_open',
                'total_follow_up',
                'total_closing',
                'total_rejected',
            ];

            if (!in_array($field, $allowed, true)) {
                return;
            }

            if ($this->sortFieldPengaju === $field) {
                $this->sortDirectionPengaju = $this->sortDirectionPengaju === 'asc' ? 'desc' : 'asc';
            } else {
                $this->sortFieldPengaju = $field;
                $this->sortDirectionPengaju = in_array($field, ['kode_cabang', 'nama_cabang'], true) ? 'asc' : 'desc';
            }
        } else {
            $allowed = [
                'name',
                'nama_lengkap',
                'role',
                'job_position',
                'kode_cabang',
                'total_pengajuan',
                'total_open',
                'total_follow_up',
                'total_closing',
                'total_rejected',
            ];

            if (!in_array($field, $allowed, true)) {
                return;
            }

            if ($this->sortFieldPegawai === $field) {
                $this->sortDirectionPegawai = $this->sortDirectionPegawai === 'asc' ? 'desc' : 'asc';
            } else {
                $this->sortFieldPegawai = $field;
                $this->sortDirectionPegawai = in_array($field, ['name', 'nama_lengkap', 'role', 'job_position', 'kode_cabang'], true) ? 'asc' : 'desc';
            }
        }

        $this->resetPage();
    }

    protected function getPegawaiOrderField(): string
    {
        $allowed = [
            'name',
            'nama_lengkap',
            'role',
            'job_position',
            'kode_cabang',
            'total_pengajuan',
            'total_open',
            'total_follow_up',
            'total_closing',
            'total_rejected',
        ];

        return in_array($this->sortFieldPegawai, $allowed, true) ? $this->sortFieldPegawai : 'total_pengajuan';
    }

    protected function getKcOrderField(): string
    {
        $allowed = [
            'kode_cabang',
            'nama_cabang',
            'total_pengajuan',
            'total_pengajuan_ao',
            'total_pengajuan_non_ao',
            'total_open',
            'total_follow_up',
            'total_closing',
            'total_rejected',
        ];

        return in_array($this->sortFieldKc, $allowed, true) ? $this->sortFieldKc : 'kode_cabang';
    }

    protected function getPengajuOrderField(): string
    {
        $allowed = [
            'kode_cabang',
            'nama_cabang',
            'total_pengajuan',
            'total_pengajuan_ao',
            'total_pengajuan_non_ao',
            'total_open',
            'total_follow_up',
            'total_closing',
            'total_rejected',
        ];

        return in_array($this->sortFieldPengaju, $allowed, true) ? $this->sortFieldPengaju : 'kode_cabang';
    }

    protected function getKcBaseQuery()
    {
        $this->normalizeDateRange();

        $query = Cabang::query()
            ->leftJoin('prospects', function ($join) {
                $join->on('prospects.cabang_id', '=', 'cabangs.id')
                    ->whereNull('prospects.deleted_at');

                $this->applyProspectDateJoinFilter(
                    $join,
                    $this->filterMode,
                    $this->filterBulan,
                    $this->filterTahun,
                    $this->filterTanggalAwal,
                    $this->filterTanggalAkhir
                );
            })
            ->leftJoin('users as input_users', 'input_users.id', '=', 'prospects.input_by')
            ->where('cabangs.aktif', 1)
            ->where(function ($q) {
                $q->where('cabangs.kode_cabang', '000')
                    ->orWhereRaw("CAST(cabangs.kode_cabang AS UNSIGNED) BETWEEN 1 AND 28");
            })
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . trim($this->search) . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('cabangs.kode_cabang', 'like', $s)
                        ->orWhere('cabangs.nama_cabang', 'like', $s);
                });
            });

        $query = $this->applyCabangFilterToCabangQuery($query);

        return $query
            ->groupBy('cabangs.id', 'cabangs.kode_cabang', 'cabangs.nama_cabang')
            ->select([
                'cabangs.id',
                'cabangs.kode_cabang',
                'cabangs.nama_cabang',
                DB::raw('COUNT(prospects.id) as total_pengajuan'),
                DB::raw("SUM(CASE WHEN prospects.id IS NOT NULL AND input_users.role = 'AO' THEN 1 ELSE 0 END) as total_pengajuan_ao"),
                DB::raw("SUM(CASE WHEN prospects.id IS NOT NULL AND (input_users.role <> 'AO' OR input_users.role IS NULL) THEN 1 ELSE 0 END) as total_pengajuan_non_ao"),
                DB::raw("SUM(CASE WHEN prospects.status = 'OPEN' THEN 1 ELSE 0 END) as total_open"),
                DB::raw("SUM(CASE WHEN prospects.status = 'FOLLOW UP' THEN 1 ELSE 0 END) as total_follow_up"),
                DB::raw("SUM(CASE WHEN prospects.status = 'CLOSING' THEN 1 ELSE 0 END) as total_closing"),
                DB::raw("SUM(CASE WHEN prospects.status = 'REJECTED' THEN 1 ELSE 0 END) as total_rejected"),
            ]);
    }

    protected function getPengajuBaseQuery()
    {
        $this->normalizeDateRange();

        $query = Cabang::query()
            ->leftJoin('users', function ($join) {
                $join->on('users.cabang_id', '=', 'cabangs.id')
                    ->whereIn('users.role', ['PEGAWAI', 'AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL']);
            })
            ->leftJoin('prospects', function ($join) {
                $join->on('prospects.input_by', '=', 'users.id')
                    ->whereNull('prospects.deleted_at');

                $this->applyProspectDateJoinFilter(
                    $join,
                    $this->filterMode,
                    $this->filterBulan,
                    $this->filterTahun,
                    $this->filterTanggalAwal,
                    $this->filterTanggalAkhir
                );
            })
            ->where('cabangs.aktif', 1)
            ->where(function ($q) {
                $q->where('cabangs.kode_cabang', '000')
                    ->orWhereRaw("CAST(cabangs.kode_cabang AS UNSIGNED) BETWEEN 1 AND 28");
            })
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . trim($this->search) . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('cabangs.kode_cabang', 'like', $s)
                        ->orWhere('cabangs.nama_cabang', 'like', $s);
                });
            });

        $query = $this->applyCabangFilterToCabangQuery($query);

        return $query
            ->groupBy('cabangs.id', 'cabangs.kode_cabang', 'cabangs.nama_cabang')
            ->select([
                'cabangs.id',
                'cabangs.kode_cabang',
                'cabangs.nama_cabang',
                DB::raw('COUNT(prospects.id) as total_pengajuan'),
                DB::raw("SUM(CASE WHEN prospects.id IS NOT NULL AND users.role = 'AO' THEN 1 ELSE 0 END) as total_pengajuan_ao"),
                DB::raw("SUM(CASE WHEN prospects.id IS NOT NULL AND users.role <> 'AO' THEN 1 ELSE 0 END) as total_pengajuan_non_ao"),
                DB::raw("SUM(CASE WHEN prospects.status = 'OPEN' THEN 1 ELSE 0 END) as total_open"),
                DB::raw("SUM(CASE WHEN prospects.status = 'FOLLOW UP' THEN 1 ELSE 0 END) as total_follow_up"),
                DB::raw("SUM(CASE WHEN prospects.status = 'CLOSING' THEN 1 ELSE 0 END) as total_closing"),
                DB::raw("SUM(CASE WHEN prospects.status = 'REJECTED' THEN 1 ELSE 0 END) as total_rejected"),
            ]);
    }

    protected function getPegawaiBaseQuery()
    {
        $this->normalizeDateRange();

        $query = User::query()
            ->leftJoin('cabangs', 'cabangs.id', '=', 'users.cabang_id')
            ->leftJoin('prospects', function ($join) {
                $join->on('prospects.input_by', '=', 'users.id')
                    ->whereNull('prospects.deleted_at');

                $this->applyProspectDateJoinFilter(
                    $join,
                    $this->filterMode,
                    $this->filterBulan,
                    $this->filterTahun,
                    $this->filterTanggalAwal,
                    $this->filterTanggalAkhir
                );
            })
            ->whereIn('users.role', ['PEGAWAI', 'AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL'])
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . trim($this->search) . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('users.name', 'like', $s)
                        ->orWhere('users.nama_lengkap', 'like', $s)
                        ->orWhere('users.job_position', 'like', $s)
                        ->orWhere('users.role', 'like', $s)
                        ->orWhere('cabangs.nama_cabang', 'like', $s)
                        ->orWhere('cabangs.kode_cabang', 'like', $s);
                });
            });

        $query = $this->applyCabangFilterToUserCabangQuery($query);

        return $query
            ->groupBy(
                'users.id',
                'users.name',
                'users.nama_lengkap',
                'users.job_position',
                'users.role',
                'users.cabang_id',
                'cabangs.kode_cabang',
                'cabangs.nama_cabang'
            )
            ->select([
                'users.id',
                'users.name',
                'users.nama_lengkap',
                'users.job_position',
                'users.role',
                'users.cabang_id',
                'cabangs.kode_cabang',
                'cabangs.nama_cabang',
                DB::raw('COUNT(prospects.id) as total_pengajuan'),
                DB::raw("SUM(CASE WHEN prospects.status = 'OPEN' THEN 1 ELSE 0 END) as total_open"),
                DB::raw("SUM(CASE WHEN prospects.status = 'FOLLOW UP' THEN 1 ELSE 0 END) as total_follow_up"),
                DB::raw("SUM(CASE WHEN prospects.status = 'CLOSING' THEN 1 ELSE 0 END) as total_closing"),
                DB::raw("SUM(CASE WHEN prospects.status = 'REJECTED' THEN 1 ELSE 0 END) as total_rejected"),
            ]);
    }

    public function openDetailPegawai(int $userId): void
    {
        $this->resetErrorBag('validationPdf');
        $this->detailPegawaiId = $userId;
        $this->detailProductTab = 'all';
        $this->detailStatus = 'ALL';
        $this->detailFilterMode = $this->filterMode;
        $this->detailFilterBulan = $this->filterBulan;
        $this->detailFilterTahun = $this->filterTahun;
        $this->detailFilterTanggalAwal = $this->filterTanggalAwal;
        $this->detailFilterTanggalAkhir = $this->filterTanggalAkhir;
        $this->dispatch('open-detail-pegawai-modal');
    }

    public function setDetailProductTab(string $tab): void
    {
        if (!in_array($tab, ['all', 'kredit', 'dana'], true)) {
            return;
        }

        $this->detailProductTab = $tab;
        $this->dispatch('open-detail-pegawai-modal');
    }

    public function setDetailKcProductTab(string $tab): void
    {
        if (in_array($tab, ['all', 'kredit', 'dana'], true)) {
            $this->detailKcProductTab = $tab;
            $this->dispatch('open-detail-kc-modal');
        }
    }

    public function updatedDetailStatus(): void
    {
        $this->detailStatus = $this->validatedDetailStatus($this->detailStatus);
        $this->dispatch('open-detail-pegawai-modal');
    }

    public function updatedDetailKcStatus(): void
    {
        $this->detailKcStatus = $this->validatedDetailStatus($this->detailKcStatus);
        $this->dispatch('open-detail-kc-modal');
    }

    protected function validatedDetailStatus(string $status): string
    {
        return in_array($status, ['ALL', 'OPEN', 'FOLLOW UP', 'CLOSING', 'REJECTED'], true) ? $status : 'ALL';
    }

    #[\Livewire\Attributes\On('closeDetailPegawaiModal')]
    public function closeDetailPegawaiModal(): void
    {
        $this->detailPegawaiId = null;
    }

    public function openDetailKc(int $cabangId, string $status = 'ALL'): void
    {
        $this->resetErrorBag('validationPdf');
        $this->detailKcCabangId = $cabangId;
        $this->detailKcProductTab = 'all';
        $this->detailKcStatus = $this->validatedDetailStatus(strtoupper(trim($status)));
        $this->detailKcFilterMode = $this->filterMode;
        $this->detailKcFilterBulan = $this->filterBulan;
        $this->detailKcFilterTahun = $this->filterTahun;
        $this->detailKcFilterTanggalAwal = $this->filterTanggalAwal;
        $this->detailKcFilterTanggalAkhir = $this->filterTanggalAkhir;
        $this->dispatch('open-detail-kc-modal');
    }

    #[\Livewire\Attributes\On('closeDetailKcModal')]
    public function closeDetailKcModal(): void
    {
        $this->detailKcCabangId = null;
        $this->detailKcStatus = 'ALL';
    }

    protected function getDetailKcQuery(bool $applyDisplayFilters = true)
    {
        if (!$this->detailKcCabangId) {
            return Prospect::query()->whereRaw('1=0');
        }

        $this->normalizeDetailDateRange();

        $query = Prospect::query()
            ->where('cabang_id', $this->detailKcCabangId)
            ->whereNull('deleted_at');

        $this->applyProspectDateWhereFilter(
            $query,
            $this->detailKcFilterMode,
            $this->detailKcFilterBulan,
            $this->detailKcFilterTahun,
            $this->detailKcFilterTanggalAwal,
            $this->detailKcFilterTanggalAkhir
        );

        if ($applyDisplayFilters && $this->detailKcStatus !== 'ALL') {
            $query->where('status', $this->detailKcStatus);
        }
        if ($applyDisplayFilters) {
            $this->applyDetailProductFilter($query, $this->detailKcProductTab);
        }

        return $query
            ->orderByDesc('tanggal_prospek')
            ->orderByDesc('id')
            ->select([
                'id',
                'tanggal_prospek',
                'nama',
                'no_hp',
                'alamat',
                'no_rekening',
                'jenis_produk',
                'jenis_usaha',
                'status',
            ]);
    }

    protected function getDetailKcStatusLabel(): string
    {
        return match ($this->detailKcStatus) {
            'OPEN' => 'Open',
            'FOLLOW UP' => 'Follow Up',
            'CLOSING' => 'Closing',
            'REJECTED' => 'Rejected',
            default => 'Semua Status',
        };
    }

    protected function getDetailPegawaiQuery(bool $applyDisplayFilters = true)
    {
        if (!$this->detailPegawaiId) {
            return Prospect::query()->whereRaw('1=0');
        }

        $this->normalizeDetailDateRange();

        $query = Prospect::query()
            ->where('input_by', $this->detailPegawaiId)
            ->whereNull('deleted_at');

        if ($applyDisplayFilters) {
            $this->applyDetailProductFilter($query, $this->detailProductTab);
            if ($this->detailStatus !== 'ALL') {
                $query->where('status', $this->detailStatus);
            }
        }

        $this->applyProspectDateWhereFilter(
            $query,
            $this->detailFilterMode,
            $this->detailFilterBulan,
            $this->detailFilterTahun,
            $this->detailFilterTanggalAwal,
            $this->detailFilterTanggalAkhir
        );

        return $query
            ->orderByDesc('tanggal_prospek')
            ->orderByDesc('id')
            ->select([
                'id',
                'tanggal_prospek',
                'nama',
                'jenis_produk',
                'jenis_usaha',
                'status',
                'no_rekening',
            ]);
    }

    protected function applyDetailProductFilter($query, string $tab): void
    {
        if ($tab === 'kredit') {
            $query->where('jenis_produk', 'KREDIT');
        } elseif ($tab === 'dana') {
            $query->whereIn('jenis_produk', ['DEPOSITO', 'TABUNGAN']);
        }
    }

    protected function buildDetailDisplay(Collection $items, string $status, string $tab): array
    {
        if ($status !== 'ALL') {
            $items = $items->where('status', $status)->values();
        }
        [$nominatif, $unavailable] = $this->getDetailNominatif($items);
        $summary = app(ProspectValidationReport::class)->summarize($items, $nominatif);
        $items = $items->filter(fn ($row) => match ($tab) {
            'kredit' => $row->jenis_produk === 'KREDIT',
            'dana' => in_array($row->jenis_produk, ['DEPOSITO', 'TABUNGAN'], true),
            default => true,
        })->values();
        $nominatif = array_intersect_key($nominatif, array_flip($items->pluck('id')->all()));

        return compact('items', 'nominatif', 'unavailable', 'summary') + [
            'unmatchedCount' => collect($nominatif)->where('status', 'unmatched')->count(),
        ];
    }

    protected function validationSubject(string $scope): User|Cabang
    {
        abort_unless(in_array($scope, ['pegawai', 'kc'], true), 404);
        $role = $this->getRoleUserLogin();
        abort_unless(in_array($role, ['ADMIN', 'MANAJEMEN', 'MANAJEMEN KANWIL', 'SUPERVISOR'], true), 403);
        $subject = $scope === 'pegawai'
            ? User::with('cabang')->findOrFail($this->detailPegawaiId)
            : Cabang::findOrFail($this->detailKcCabangId);
        $branch = $scope === 'pegawai' ? $subject->cabang : $subject;
        if ($role === 'SUPERVISOR') {
            abort_unless($branch && auth()->user()->cabang_id && (int) $branch->id === (int) auth()->user()->cabang_id, 403);
        } elseif ($role === 'MANAJEMEN KANWIL') {
            $code = $this->getUserKanwilKode();
            abort_unless($branch && in_array($code, ['100', '200', '300', '400'], true), 403);
            [$start, $end] = $this->getKanwilRange($code);
            abort_unless($branch->kode_cabang === $code || ((int) $branch->kode_cabang >= $start && (int) $branch->kode_cabang <= $end), 403);
        }

        return $subject;
    }

    protected function validationData(string $scope): array
    {
        $subject = $this->validationSubject($scope);
        $isKc = $scope === 'kc';
        $query = $isKc ? $this->getDetailKcQuery(false) : $this->getDetailPegawaiQuery(false);
        $product = $isKc ? $this->detailKcProductTab : $this->detailProductTab;
        $this->applyDetailProductFilter($query, $product);
        // A validation letter always contains Closing, regardless of the modal status filter.
        $items = $query->where('status', 'CLOSING')->get();
        [$nominatif, $unavailable] = $this->getDetailNominatif($items);
        $branch = $isKc ? $subject : $subject->cabang;
        $signer = $isKc ? app(ProspectValidationReport::class)->branchHead($subject->id) : $subject;
        $prefix = $isKc ? 'detailKcFilter' : 'detailFilter';
        $mode = $this->{$prefix.'Mode'};
        $period = match ($mode) {
            'monthly' => Carbon::createFromDate((int) ($this->{$prefix.'Tahun'} ?: now()->year), (int) ($this->{$prefix.'Bulan'} ?: now()->month), 1)->locale('id')->translatedFormat('F Y'),
            'range' => ($this->{$prefix.'TanggalAwal'} ?: 'Awal').' s.d. '.($this->{$prefix.'TanggalAkhir'} ?: 'Akhir'),
            default => 'Semua tanggal',
        };

        return compact('items', 'nominatif', 'unavailable', 'subject', 'branch', 'signer', 'period', 'scope') + [
            'summary' => app(ProspectValidationReport::class)->summarize($items, $nominatif),
            'productLabel' => match ($product) { 'kredit' => 'Kredit', 'dana' => 'Dana (Deposito / Tabungan)', default => 'Semua produk' },
            'printedAt' => now('Asia/Jakarta')->locale('id'),
        ];
    }

    public function exportValidationPdf(string $scope)
    {
        $this->resetErrorBag('validationPdf');
        $data = $this->validationData($scope);
        if ($data['items']->isEmpty()) {
            $this->addError('validationPdf', 'Tidak ada pengajuan Closing pada produk dan periode yang dipilih.');
            return;
        }
        if ($data['unavailable']) {
            $this->addError('validationPdf', 'Data nominatif belum dapat dimuat. Coba cetak kembali setelah data tersedia.');
            return;
        }
        $pdf = app(ProspectValidationReport::class)->pdf($data);
        $filename = 'validasi-pengajuan-'.$scope.'-'.$data['subject']->id.'-'.now('Asia/Jakarta')->format('Ymd').'.pdf';

        return response()->streamDownload(static function () use ($pdf) { echo $pdf; }, $filename, ['Content-Type' => 'application/pdf']);
    }

    protected function getDetailNominatif(Collection $items): array
    {
        $details = [];
        $unavailable = false;
        // Use the same nominatif sources as the Realisasi report.
        $sources = [
            'KREDIT' => ['nominatif', 'jml_pinjaman'],
            'DEPOSITO' => ['nominatif_deposito', 'saldo_akhir'],
            'TABUNGAN' => ['nominatif_tabungan', 'saldo'],
        ];

        foreach ($items as $item) {
            $hasAccount = trim((string) $item->no_rekening) !== '';
            $details[$item->id] = [
                'nama' => '',
                'nominal' => null,
                'status' => $hasAccount ? 'unsupported' : 'missing_account',
                'keterangan' => $hasAccount ? 'Produk belum didukung untuk pencocokan nominatif.' : 'Nomor rekening belum diisi di e-Prospek.',
            ];
        }

        foreach ($sources as $product => [$table, $column]) {
            $prospects = $items->filter(fn ($row) =>
                strtoupper(trim((string) $row->jenis_produk)) === $product
                && trim((string) $row->no_rekening) !== ''
            );

            if ($prospects->isEmpty()) {
                continue;
            }

            try {
                // Match the name-column detection used by the Realisasi report.
                $columns = DB::connection('dpk')->getSchemaBuilder()->getColumnListing($table);
                $columnMap = [];
                foreach ($columns as $name) {
                    $columnMap[strtolower((string) $name)] = (string) $name;
                }
                $nameColumn = null;
                foreach (['nama_nasabah', 'nama', 'nama_rekening', 'nama_debitur', 'nm_nasabah', 'nama_pemilik'] as $candidate) {
                    if (isset($columnMap[$candidate])) {
                        $nameColumn = $columnMap[$candidate];
                        break;
                    }
                }
                if ($nameColumn === null) {
                    foreach ($columns as $name) {
                        if (str_contains(strtolower((string) $name), 'nama')) {
                            $nameColumn = (string) $name;
                            break;
                        }
                    }
                }

                $byAccount = [];
                $accounts = $prospects->map(fn ($row) => trim((string) $row->no_rekening))->unique()->values()->all();
                $selectColumns = ['no_rekening', $column];
                if ($nameColumn !== null) {
                    $selectColumns[] = $nameColumn;
                }
                foreach (array_chunk($accounts, 800) as $chunk) {
                    $rows = DB::connection('dpk')->table($table)
                        ->whereIn('no_rekening', $chunk)
                        ->get($selectColumns);
                    foreach ($rows as $row) {
                        $byAccount[trim((string) $row->no_rekening)] = [
                            'nama' => $nameColumn !== null ? trim((string) ($row->{$nameColumn} ?? '')) : '',
                            'nominal' => (float) ($row->{$column} ?? 0),
                        ];
                    }
                }

                foreach ($prospects as $prospect) {
                    $matched = $byAccount[trim((string) $prospect->no_rekening)] ?? null;
                    $isClosing = strtoupper(trim((string) $prospect->status)) === 'CLOSING';
                    $details[$prospect->id] = [
                        'nama' => $matched['nama'] ?? '',
                        'nominal' => $isClosing ? ($matched['nominal'] ?? null) : null,
                        'status' => $matched !== null ? 'matched' : 'unmatched',
                        'keterangan' => $matched === null
                            ? 'Rekening e-Prospek tidak ditemukan di nominatif '.strtolower($product).'.'
                            : ($matched['nama'] === '' ? 'Nama nasabah belum tersedia di nominatif.' : ($isClosing ? '-' : 'Rekening cocok; status prospek belum Closing.')),
                    ];
                }
            } catch (\Throwable $exception) {
                report($exception);
                $unavailable = true;
                foreach ($prospects as $prospect) {
                    $details[$prospect->id]['status'] = 'unavailable';
                    $details[$prospect->id]['keterangan'] = 'Data nominatif belum dapat dimuat; rekening belum dapat dicocokkan.';
                }
            }
        }

        return [$details, $unavailable];
    }

    protected function getPeriodeLabelForExport(): string
    {
        if ($this->filterMode === 'range') {
            return trim(($this->filterTanggalAwal ?: '-') . ' s.d ' . ($this->filterTanggalAkhir ?: '-'));
        }

        if ($this->filterMode === 'monthly') {
            $bulanValue = (int) ($this->filterBulan !== '' ? $this->filterBulan : now()->month);
            $tahunValue = (int) ($this->filterTahun !== '' ? $this->filterTahun : now()->year);
            return Carbon::createFromDate($tahunValue, $bulanValue, 1)->translatedFormat('F Y');
        }

        return 'Semua Periode';
    }

    public function exportExcel()
    {
        $periodeLabel = $this->getPeriodeLabelForExport();

        if ($this->activeTab === 'kc') {
            $rows = $this->getKcBaseQuery();

            if (in_array($this->getKcOrderField(), ['kode_cabang', 'nama_cabang'], true)) {
                $rows = $rows->orderBy($this->getKcOrderField(), $this->sortDirectionKc);
            } else {
                $rows = $rows
                    ->orderBy($this->getKcOrderField(), $this->sortDirectionKc)
                    ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN 0 ELSE 1 END ASC")
                    ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN -1 ELSE CAST(cabangs.kode_cabang AS UNSIGNED) END ASC");
            }

            $rows = $rows->get();

            $filename = 'rekap_prospek_per_kc_' . now()->format('Ymd_His') . '.xls';

            return response()->streamDownload(function () use ($rows, $periodeLabel) {
                echo '<html><head><meta charset="UTF-8"></head><body><table border="1">';
                echo '<tr><th colspan="10" style="font-weight:bold;">Rekap Prospek Per KC - ' . e($periodeLabel) . '</th></tr>';
                echo '<tr>
                        <th>No</th>
                        <th>Kode Cabang</th>
                        <th>Nama Cabang</th>
                        <th>Jumlah Pengajuan</th>
                        <th>AO</th>
                        <th>NON AO</th>
                        <th>Open</th>
                        <th>Follow Up</th>
                        <th>Closing</th>
                        <th>Rejected</th>
                      </tr>';
                foreach ($rows as $i => $row) {
                    echo '<tr>';
                    echo '<td>' . ($i + 1) . '</td>';
                    echo '<td>' . e($row->kode_cabang) . '</td>';
                    echo '<td>' . e($row->nama_cabang) . '</td>';
                    echo '<td>' . (int) $row->total_pengajuan . '</td>';
                    echo '<td>' . (int) ($row->total_pengajuan_ao ?? 0) . '</td>';
                    echo '<td>' . (int) ($row->total_pengajuan_non_ao ?? 0) . '</td>';
                    echo '<td>' . (int) $row->total_open . '</td>';
                    echo '<td>' . (int) $row->total_follow_up . '</td>';
                    echo '<td>' . (int) $row->total_closing . '</td>';
                    echo '<td>' . (int) $row->total_rejected . '</td>';
                    echo '</tr>';
                }
                echo '</table></body></html>';
            }, $filename, ['Content-Type' => 'application/vnd.ms-excel; charset=UTF-8']);
        }

        if ($this->activeTab === 'pengaju') {
            $rows = $this->getPengajuBaseQuery();

            if (in_array($this->getPengajuOrderField(), ['kode_cabang', 'nama_cabang'], true)) {
                $rows = $rows->orderBy($this->getPengajuOrderField(), $this->sortDirectionPengaju);
            } else {
                $rows = $rows
                    ->orderBy($this->getPengajuOrderField(), $this->sortDirectionPengaju)
                    ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN 0 ELSE 1 END ASC")
                    ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN -1 ELSE CAST(cabangs.kode_cabang AS UNSIGNED) END ASC");
            }

            $rows = $rows->get();

            $filename = 'rekap_pengaju_per_cabang_' . now()->format('Ymd_His') . '.xls';

            return response()->streamDownload(function () use ($rows, $periodeLabel) {
                echo '<html><head><meta charset="UTF-8"></head><body><table border="1">';
                echo '<tr><th colspan="10" style="font-weight:bold;">Rekap Pengaju Per Cabang - ' . e($periodeLabel) . '</th></tr>';
                echo '<tr>
                        <th>No</th>
                        <th>Kode Cabang</th>
                        <th>Nama Cabang</th>
                        <th>Jumlah Pengaju</th>
                        <th>AO</th>
                        <th>NON AO</th>
                        <th>Open</th>
                        <th>Follow Up</th>
                        <th>Closing</th>
                        <th>Rejected</th>
                      </tr>';
                foreach ($rows as $i => $row) {
                    echo '<tr>';
                    echo '<td>' . ($i + 1) . '</td>';
                    echo '<td>' . e($row->kode_cabang) . '</td>';
                    echo '<td>' . e($row->nama_cabang) . '</td>';
                    echo '<td>' . (int) $row->total_pengajuan . '</td>';
                    echo '<td>' . (int) ($row->total_pengajuan_ao ?? 0) . '</td>';
                    echo '<td>' . (int) ($row->total_pengajuan_non_ao ?? 0) . '</td>';
                    echo '<td>' . (int) $row->total_open . '</td>';
                    echo '<td>' . (int) $row->total_follow_up . '</td>';
                    echo '<td>' . (int) $row->total_closing . '</td>';
                    echo '<td>' . (int) $row->total_rejected . '</td>';
                    echo '</tr>';
                }
                echo '</table></body></html>';
            }, $filename, ['Content-Type' => 'application/vnd.ms-excel; charset=UTF-8']);
        }

        $rows = $this->getPegawaiBaseQuery()
            ->orderBy($this->getPegawaiOrderField(), $this->sortDirectionPegawai)
            ->orderBy('users.id', 'desc')
            ->get();

        $filename = 'rekap_prospek_per_pegawai_' . now()->format('Ymd_His') . '.xls';

        return response()->streamDownload(function () use ($rows, $periodeLabel) {
            echo '<html><head><meta charset="UTF-8"></head><body><table border="1">';
            echo '<tr><th colspan="12" style="font-weight:bold;">Rekap Prospek Per Pegawai - ' . e($periodeLabel) . '</th></tr>';
            echo '<tr><th>No</th><th>Username</th><th>Nama Lengkap</th><th>Role</th><th>Jabatan</th><th>Kode Cabang</th><th>Nama Cabang</th><th>Jumlah Pengajuan</th><th>Open</th><th>Follow Up</th><th>Closing</th><th>Rejected</th></tr>';
            foreach ($rows as $i => $row) {
                echo '<tr>';
                echo '<td>' . ($i + 1) . '</td>';
                echo '<td>' . e($row->name) . '</td>';
                echo '<td>' . e($row->nama_lengkap) . '</td>';
                echo '<td>' . e($row->role) . '</td>';
                echo '<td>' . e($row->job_position) . '</td>';
                echo '<td>' . e($row->kode_cabang) . '</td>';
                echo '<td>' . e($row->nama_cabang) . '</td>';
                echo '<td>' . (int) $row->total_pengajuan . '</td>';
                echo '<td>' . (int) $row->total_open . '</td>';
                echo '<td>' . (int) $row->total_follow_up . '</td>';
                echo '<td>' . (int) $row->total_closing . '</td>';
                echo '<td>' . (int) $row->total_rejected . '</td>';
                echo '</tr>';
            }
            echo '</table></body></html>';
        }, $filename, ['Content-Type' => 'application/vnd.ms-excel; charset=UTF-8']);
    }

    public function render()
    {
        $this->normalizeDateRange();
        $this->normalizeDetailDateRange();

        $bulanOptions = collect(range(1, 12))->map(function ($b) {
            return [
                'id' => $b,
                'label' => Carbon::create()->month($b)->translatedFormat('F'),
            ];
        });

        $tahunNow = (int) now()->year;
        $tahunOptions = collect(range($tahunNow - 3, $tahunNow + 1));

        $role = $this->getRoleUserLogin();
        $kodeKanwilUser = $this->getUserKanwilKode();

        $cabangsQuery = Cabang::query()
            ->where('aktif', 1)
            ->where(function ($q) {
                $q->where('kode_cabang', '000')
                    ->orWhereIn('kode_cabang', ['100', '200', '300', '400'])
                    ->orWhereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 1 AND 28");
            });

        if ($role === 'MANAJEMEN KANWIL' && in_array($kodeKanwilUser, ['100', '200', '300', '400'], true)) {
            [$start, $end] = $this->getKanwilRange($kodeKanwilUser);

            $cabangsQuery->where(function ($q) use ($kodeKanwilUser, $start, $end) {
                $q->where('kode_cabang', $kodeKanwilUser)
                  ->orWhereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN {$start} AND {$end}");
            });
        }

        $cabangs = $cabangsQuery
            ->orderByRaw("
                CASE
                    WHEN kode_cabang = '000' THEN 0
                    WHEN kode_cabang IN ('100','200','300','400') THEN 1
                    ELSE 2
                END ASC
            ")
            ->orderByRaw("
                CASE
                    WHEN kode_cabang = '000' THEN -1
                    ELSE CAST(kode_cabang AS UNSIGNED)
                END ASC
            ")
            ->get(['id', 'kode_cabang', 'nama_cabang']);

        if ($this->activeTab === 'kc') {
            $items = $this->getKcBaseQuery();

            if (in_array($this->getKcOrderField(), ['kode_cabang', 'nama_cabang'], true)) {
                $items = $items->orderBy($this->getKcOrderField(), $this->sortDirectionKc);
            } else {
                $items = $items
                    ->orderBy($this->getKcOrderField(), $this->sortDirectionKc)
                    ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN 0 ELSE 1 END ASC")
                    ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN -1 ELSE CAST(cabangs.kode_cabang AS UNSIGNED) END ASC");
            }

            $items = $items->get();
        } elseif ($this->activeTab === 'pengaju') {
            $items = $this->getPengajuBaseQuery();

            if (in_array($this->getPengajuOrderField(), ['kode_cabang', 'nama_cabang'], true)) {
                $items = $items->orderBy($this->getPengajuOrderField(), $this->sortDirectionPengaju);
            } else {
                $items = $items
                    ->orderBy($this->getPengajuOrderField(), $this->sortDirectionPengaju)
                    ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN 0 ELSE 1 END ASC")
                    ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN -1 ELSE CAST(cabangs.kode_cabang AS UNSIGNED) END ASC");
            }

            $items = $items->get();
        } else {
            $items = $this->getPegawaiBaseQuery()
                ->orderBy($this->getPegawaiOrderField(), $this->sortDirectionPegawai)
                ->orderBy('users.id', 'desc')
                ->paginate(10);
        }

        $detailPegawai = null;
        $detailItems = collect();
        $detailNominatif = [];
        $detailRealisasiUnavailable = false;
        $detailSummary = app(ProspectValidationReport::class)->summarize(collect(), []);

        if ($this->detailPegawaiId) {
            $detailPegawai = User::query()
                ->leftJoin('cabangs', 'cabangs.id', '=', 'users.cabang_id')
                ->where('users.id', $this->detailPegawaiId)
                ->select([
                    'users.id',
                    'users.name',
                    'users.nama_lengkap',
                    'users.role',
                    'users.job_position',
                    'cabangs.kode_cabang',
                    'cabangs.nama_cabang',
                ])
                ->first();

            $display = $this->buildDetailDisplay($this->getDetailPegawaiQuery(false)->get(), $this->detailStatus, $this->detailProductTab);
            $detailItems = $display['items'];
            $detailNominatif = $display['nominatif'];
            $detailRealisasiUnavailable = $display['unavailable'];
            $detailSummary = $display['summary'];
        }

        $detailKcCabang = null;
        $detailKcItems = collect();
        $detailKcDisplay = $this->buildDetailDisplay(collect(), 'ALL', 'all');

        if ($this->detailKcCabangId) {
            $detailKcCabang = Cabang::query()
                ->where('id', $this->detailKcCabangId)
                ->first(['id', 'kode_cabang', 'nama_cabang']);

            $detailKcDisplay = $this->buildDetailDisplay($this->getDetailKcQuery(false)->get(), $this->detailKcStatus, $this->detailKcProductTab);
            $detailKcItems = $detailKcDisplay['items'];
        }

        return view('livewire.reports.prospect-recap', [
            'items' => $items,
            'cabangs' => $cabangs,
            'bulanOptions' => $bulanOptions,
            'tahunOptions' => $tahunOptions,
            'detailPegawai' => $detailPegawai,
            'detailItems' => $detailItems,
            'detailNominatif' => $detailNominatif,
            'detailUnmatchedCount' => collect($detailNominatif)->where('status', 'unmatched')->count(),
            'detailRealisasiUnavailable' => $detailRealisasiUnavailable,
            'detailSummary' => $detailSummary,
            'detailKcDisplay' => $detailKcDisplay,
            'detailKcCabang' => $detailKcCabang,
            'detailKcItems' => $detailKcItems,
            'detailKcStatusLabel' => $this->getDetailKcStatusLabel(),
        ])->layout('layouts.bootstrap');
    }
}
