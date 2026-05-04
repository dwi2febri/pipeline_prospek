<?php

namespace App\Livewire\Reports;

use App\Models\Cabang;
use App\Models\Prospect;
use App\Models\User;
use Carbon\Carbon;
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
    public string $detailFilterMode = 'all';
    public string $detailFilterBulan = '';
    public string $detailFilterTahun = '';
    public string $detailFilterTanggalAwal = '';
    public string $detailFilterTanggalAkhir = '';

    public ?int $detailKcCabangId = null;
    public string $detailKcStatus = 'ALL';
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

    protected function getSelectedCabang(): ?Cabang
    {
        $id = trim((string) $this->filterCabang);

        if ($this->getRoleUserLogin() === 'SUPERVISOR') {
            $id = (string) (auth()->user()->cabang_id ?? '');
        }

        if ($id === '' || !is_numeric($id)) {
            return null;
        }

        return Cabang::query()->find((int) $id);
    }

    protected function applyCabangFilterToCabangQuery($query, string $alias = 'cabangs')
    {
        $selected = $this->getSelectedCabang();

        if (!$selected) {
            return $query;
        }

        return $query->where("{$alias}.id", (int) $selected->id);
    }

    protected function applyCabangFilterToUserCabangQuery($query)
    {
        $selected = $this->getSelectedCabang();

        if (!$selected) {
            return $query;
        }

        return $query->where('users.cabang_id', (int) $selected->id);
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
            $allowed = ['kode_cabang', 'nama_cabang', 'total_pengajuan', 'total_open', 'total_follow_up', 'total_closing', 'total_rejected'];
            if (!in_array($field, $allowed, true)) return;

            if ($this->sortFieldKc === $field) {
                $this->sortDirectionKc = $this->sortDirectionKc === 'asc' ? 'desc' : 'asc';
            } else {
                $this->sortFieldKc = $field;
                $this->sortDirectionKc = in_array($field, ['kode_cabang', 'nama_cabang'], true) ? 'asc' : 'desc';
            }
        } elseif ($this->activeTab === 'pengaju') {
            $allowed = ['kode_cabang', 'nama_cabang', 'total_pengajuan', 'total_open', 'total_follow_up', 'total_closing', 'total_rejected'];
            if (!in_array($field, $allowed, true)) return;

            if ($this->sortFieldPengaju === $field) {
                $this->sortDirectionPengaju = $this->sortDirectionPengaju === 'asc' ? 'desc' : 'asc';
            } else {
                $this->sortFieldPengaju = $field;
                $this->sortDirectionPengaju = in_array($field, ['kode_cabang', 'nama_cabang'], true) ? 'asc' : 'desc';
            }
        } else {
            $allowed = ['name', 'nama_lengkap', 'role', 'job_position', 'kode_cabang', 'total_pengajuan', 'total_open', 'total_follow_up', 'total_closing', 'total_rejected'];
            if (!in_array($field, $allowed, true)) return;

            if ($this->sortFieldPegawai === $field) {
                $this->sortDirectionPegawai = $this->sortDirectionPegawai === 'asc' ? 'desc' : 'asc';
            } else {
                $this->sortFieldPegawai = $field;
                $this->sortDirectionPegawai = in_array($field, ['name', 'nama_lengkap', 'role', 'job_position', 'kode_cabang'], true) ? 'asc' : 'desc';
            }
        }

        $this->resetPage();
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
                DB::raw("SUM(CASE WHEN prospects.status = 'OPEN' THEN 1 ELSE 0 END) as total_open"),
                DB::raw("SUM(CASE WHEN prospects.status = 'FOLLOW UP' THEN 1 ELSE 0 END) as total_follow_up"),
                DB::raw("SUM(CASE WHEN prospects.status = 'CLOSING' THEN 1 ELSE 0 END) as total_closing"),
                DB::raw("SUM(CASE WHEN prospects.status = 'REJECTED' THEN 1 ELSE 0 END) as total_rejected"),
            ]);
    }

    public function openDetailPegawai(int $userId): void
    {
        $this->detailPegawaiId = $userId;
        $this->detailFilterMode = $this->filterMode;
        $this->detailFilterBulan = $this->filterBulan;
        $this->detailFilterTahun = $this->filterTahun;
        $this->detailFilterTanggalAwal = $this->filterTanggalAwal;
        $this->detailFilterTanggalAkhir = $this->filterTanggalAkhir;
        $this->dispatch('open-detail-pegawai-modal');
    }

    #[\Livewire\Attributes\On('closeDetailPegawaiModal')]
    public function closeDetailPegawaiModal(): void
    {
        $this->detailPegawaiId = null;
    }

    public function openDetailKc(int $cabangId, string $status = 'ALL'): void
    {
        $this->detailKcCabangId = $cabangId;
        $this->detailKcStatus = strtoupper(trim($status)) ?: 'ALL';
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

    protected function getDetailKcQuery()
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

        if ($this->detailKcStatus !== 'ALL') {
            $query->where('status', $this->detailKcStatus);
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

    protected function getDetailPegawaiQuery()
    {
        if (!$this->detailPegawaiId) {
            return Prospect::query()->whereRaw('1=0');
        }

        $this->normalizeDetailDateRange();

        $query = Prospect::query()
            ->where('input_by', $this->detailPegawaiId)
            ->whereNull('deleted_at');

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
            ]);
    }

    protected function getPegawaiOrderField(): string
    {
        $allowed = ['name', 'nama_lengkap', 'role', 'job_position', 'kode_cabang', 'total_pengajuan', 'total_open', 'total_follow_up', 'total_closing', 'total_rejected'];
        return in_array($this->sortFieldPegawai, $allowed, true) ? $this->sortFieldPegawai : 'total_pengajuan';
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
            $rows = $this->getKcBaseQuery()
                ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN 0 ELSE 1 END ASC")
                ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN -1 ELSE CAST(cabangs.kode_cabang AS UNSIGNED) END ASC")
                ->get();

            $filename = 'rekap_prospek_per_kc_' . now()->format('Ymd_His') . '.xls';

            return response()->streamDownload(function () use ($rows, $periodeLabel) {
                echo '<html><head><meta charset="UTF-8"></head><body><table border="1">';
                echo '<tr><th colspan="8" style="font-weight:bold;">Rekap Prospek Per KC - ' . e($periodeLabel) . '</th></tr>';
                echo '<tr><th>No</th><th>Kode Cabang</th><th>Nama Cabang</th><th>Jumlah Pengajuan</th><th>Open</th><th>Follow Up</th><th>Closing</th><th>Rejected</th></tr>';
                foreach ($rows as $i => $row) {
                    echo '<tr>';
                    echo '<td>' . ($i + 1) . '</td>';
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

        if ($this->activeTab === 'pengaju') {
            $rows = $this->getPengajuBaseQuery()
                ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN 0 ELSE 1 END ASC")
                ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN -1 ELSE CAST(cabangs.kode_cabang AS UNSIGNED) END ASC")
                ->get();

            $filename = 'rekap_pengaju_per_cabang_' . now()->format('Ymd_His') . '.xls';

            return response()->streamDownload(function () use ($rows, $periodeLabel) {
                echo '<html><head><meta charset="UTF-8"></head><body><table border="1">';
                echo '<tr><th colspan="8" style="font-weight:bold;">Rekap Pengaju Per Cabang - ' . e($periodeLabel) . '</th></tr>';
                echo '<tr><th>No</th><th>Kode Cabang</th><th>Nama Cabang</th><th>Jumlah Pengaju</th><th>Open</th><th>Follow Up</th><th>Closing</th><th>Rejected</th></tr>';
                foreach ($rows as $i => $row) {
                    echo '<tr>';
                    echo '<td>' . ($i + 1) . '</td>';
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

        $cabangs = Cabang::query()
            ->where('aktif', 1)
            ->where(function ($q) {
                $q->where('kode_cabang', '000')
                  ->orWhereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 1 AND 28");
            })
            ->orderByRaw("CASE WHEN kode_cabang = '000' THEN 0 ELSE 1 END ASC")
            ->orderByRaw("CASE WHEN kode_cabang = '000' THEN -1 ELSE CAST(kode_cabang AS UNSIGNED) END ASC")
            ->get(['id', 'kode_cabang', 'nama_cabang']);

        if ($this->activeTab === 'kc') {
            $items = $this->getKcBaseQuery()
                ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN 0 ELSE 1 END ASC")
                ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN -1 ELSE CAST(cabangs.kode_cabang AS UNSIGNED) END ASC")
                ->get();
        } elseif ($this->activeTab === 'pengaju') {
            $items = $this->getPengajuBaseQuery()
                ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN 0 ELSE 1 END ASC")
                ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN -1 ELSE CAST(cabangs.kode_cabang AS UNSIGNED) END ASC")
                ->get();
        } else {
            $items = $this->getPegawaiBaseQuery()
                ->orderBy($this->getPegawaiOrderField(), $this->sortDirectionPegawai)
                ->orderBy('users.id', 'desc')
                ->paginate(10);
        }

        $detailPegawai = null;
        $detailItems = collect();

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

            $detailItems = $this->getDetailPegawaiQuery()->get();
        }

        $detailKcCabang = null;
        $detailKcItems = collect();

        if ($this->detailKcCabangId) {
            $detailKcCabang = Cabang::query()
                ->where('id', $this->detailKcCabangId)
                ->first(['id', 'kode_cabang', 'nama_cabang']);

            $detailKcItems = $this->getDetailKcQuery()->get();
        }

        return view('livewire.reports.prospect-recap', [
            'items' => $items,
            'cabangs' => $cabangs,
            'bulanOptions' => $bulanOptions,
            'tahunOptions' => $tahunOptions,
            'detailPegawai' => $detailPegawai,
            'detailItems' => $detailItems,
            'detailKcCabang' => $detailKcCabang,
            'detailKcItems' => $detailKcItems,
            'detailKcStatusLabel' => $this->getDetailKcStatusLabel(),
        ])->layout('layouts.bootstrap');
    }
}
