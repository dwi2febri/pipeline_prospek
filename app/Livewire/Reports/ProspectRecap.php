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
    public string $filterBulan = '';
    public string $filterTahun = '';
    public string $search = '';

    public string $sortFieldPegawai = 'total_pengajuan';
    public string $sortDirectionPegawai = 'desc';

    public string $sortFieldKc = 'kode_cabang';
    public string $sortDirectionKc = 'asc';

    public string $sortFieldPengaju = 'total_pengajuan';
    public string $sortDirectionPengaju = 'desc';

    public bool $lockCabangFilter = false;

    public ?int $detailPegawaiId = null;
    public string $detailFilterBulan = '';
    public string $detailFilterTahun = '';

    // DETAIL PER KC
    public ?int $detailKcCabangId = null;
    public string $detailKcStatus = 'ALL';
    public string $detailKcFilterBulan = '';
    public string $detailKcFilterTahun = '';

    protected $queryString = [
        'activeTab' => ['except' => 'kc'],
        'filterCabang' => ['except' => ''],
        'filterBulan' => ['except' => ''],
        'filterTahun' => ['except' => ''],
        'search' => ['except' => ''],
        'sortFieldPegawai' => ['except' => 'total_pengajuan'],
        'sortDirectionPegawai' => ['except' => 'desc'],
        'sortFieldKc' => ['except' => 'kode_cabang'],
        'sortDirectionKc' => ['except' => 'asc'],
        'sortFieldPengaju' => ['except' => 'total_pengajuan'],
        'sortDirectionPengaju' => ['except' => 'desc'],
    ];

    protected array $korwilRanges = [
        '100' => [1, 7],
        '200' => [8, 14],
        '300' => [15, 21],
        '400' => [22, 28],
    ];

    public function mount(): void
    {
        $now = now();
        $this->filterBulan = (string) $now->month;
        $this->filterTahun = (string) $now->year;
        $this->detailFilterBulan = (string) $now->month;
        $this->detailFilterTahun = (string) $now->year;

        $this->detailKcFilterBulan = (string) $now->month;
        $this->detailKcFilterTahun = (string) $now->year;

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

    public function updatingFilterBulan(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTahun(): void
    {
        $this->resetPage();
    }

    public function updatedDetailFilterBulan(): void
    {
        $this->dispatch('open-detail-pegawai-modal');
    }

    public function updatedDetailFilterTahun(): void
    {
        $this->dispatch('open-detail-pegawai-modal');
    }

    // DETAIL KC
    public function updatedDetailKcFilterBulan(): void
    {
        $this->dispatch('open-detail-kc-modal');
    }

    public function updatedDetailKcFilterTahun(): void
    {
        $this->dispatch('open-detail-kc-modal');
    }

    protected function getRoleUserLogin(): string
    {
        return strtoupper(trim((string) (auth()->user()->role ?? '')));
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

    protected function getCabangFilterMeta(): array
    {
        $selected = $this->getSelectedCabang();

        if (!$selected) {
            return [
                'type' => 'all',
                'id' => null,
                'kode' => null,
                'range' => null,
            ];
        }

        $kode = trim((string) $selected->kode_cabang);

        if (isset($this->korwilRanges[$kode])) {
            return [
                'type' => 'korwil',
                'id' => (int) $selected->id,
                'kode' => $kode,
                'range' => $this->korwilRanges[$kode],
            ];
        }

        return [
            'type' => 'cabang',
            'id' => (int) $selected->id,
            'kode' => $kode,
            'range' => null,
        ];
    }

    protected function applyCabangFilterToCabangQuery($query, string $alias = 'cabangs')
    {
        $meta = $this->getCabangFilterMeta();

        if ($meta['type'] === 'all') {
            return $query;
        }

        if ($meta['type'] === 'korwil' && is_array($meta['range'])) {
            [$start, $end] = $meta['range'];

            return $query->whereRaw(
                "CAST({$alias}.kode_cabang AS UNSIGNED) BETWEEN ? AND ?",
                [$start, $end]
            );
        }

        return $query->where("{$alias}.id", $meta['id']);
    }

    protected function applyCabangFilterToUserCabangQuery($query, string $alias = 'cabangs')
    {
        $meta = $this->getCabangFilterMeta();

        if ($meta['type'] === 'all') {
            return $query;
        }

        if ($meta['type'] === 'korwil' && is_array($meta['range'])) {
            [$start, $end] = $meta['range'];

            return $query->whereRaw(
                "CAST({$alias}.kode_cabang AS UNSIGNED) BETWEEN ? AND ?",
                [$start, $end]
            );
        }

        return $query->where("users.cabang_id", $meta['id']);
    }

    public function sortBy(string $field): void
    {
        if ($this->activeTab === 'kc') {
            $allowed = [
                'kode_cabang',
                'nama_cabang',
                'total_pengajuan',
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
                $this->sortDirectionKc = in_array($field, ['total_pengajuan', 'total_open', 'total_follow_up', 'total_closing', 'total_rejected'], true)
                    ? 'desc'
                    : 'asc';
            }
        } elseif ($this->activeTab === 'pengaju') {
            $allowed = [
                'kode_cabang',
                'nama_cabang',
                'total_pengajuan',
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
                $this->sortDirectionPengaju = in_array($field, ['total_pengajuan', 'total_open', 'total_follow_up', 'total_closing', 'total_rejected'], true)
                    ? 'desc'
                    : 'asc';
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
                $this->sortDirectionPegawai = in_array($field, ['total_pengajuan', 'total_open', 'total_follow_up', 'total_closing', 'total_rejected'], true)
                    ? 'desc'
                    : 'asc';
            }
        }

        $this->resetPage();
    }

    protected function getPegawaiBaseQuery()
    {
        $bulan = (int) ($this->filterBulan ?: now()->month);
        $tahun = (int) ($this->filterTahun ?: now()->year);

        $query = User::query()
            ->leftJoin('cabangs', 'cabangs.id', '=', 'users.cabang_id')
            ->leftJoin('prospects', function ($join) use ($bulan, $tahun) {
                $join->on('prospects.input_by', '=', 'users.id')
                    ->whereMonth('prospects.tanggal_prospek', $bulan)
                    ->whereYear('prospects.tanggal_prospek', $tahun)
                    ->whereNull('prospects.deleted_at');
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

        $query = $this->applyCabangFilterToUserCabangQuery($query, 'cabangs');

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
        $bulan = (int) ($this->filterBulan ?: now()->month);
        $tahun = (int) ($this->filterTahun ?: now()->year);

        $query = Cabang::query()
            ->leftJoin('prospects', function ($join) use ($bulan, $tahun) {
                $join->on('prospects.cabang_id', '=', 'cabangs.id')
                    ->whereMonth('prospects.tanggal_prospek', $bulan)
                    ->whereYear('prospects.tanggal_prospek', $tahun)
                    ->whereNull('prospects.deleted_at');
            })
            ->where('cabangs.aktif', 1)
            ->whereRaw("CAST(cabangs.kode_cabang AS UNSIGNED) BETWEEN 1 AND 28")
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . trim($this->search) . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('cabangs.kode_cabang', 'like', $s)
                        ->orWhere('cabangs.nama_cabang', 'like', $s);
                });
            });

        $query = $this->applyCabangFilterToCabangQuery($query, 'cabangs');

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
        $bulan = (int) ($this->filterBulan ?: now()->month);
        $tahun = (int) ($this->filterTahun ?: now()->year);

        $query = Cabang::query()
            ->leftJoin('users', function ($join) {
                $join->on('users.cabang_id', '=', 'cabangs.id')
                    ->whereIn('users.role', ['PEGAWAI', 'AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL']);
            })
            ->leftJoin('prospects', function ($join) use ($bulan, $tahun) {
                $join->on('prospects.input_by', '=', 'users.id')
                    ->whereMonth('prospects.tanggal_prospek', $bulan)
                    ->whereYear('prospects.tanggal_prospek', $tahun)
                    ->whereNull('prospects.deleted_at');
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

        $query = $this->applyCabangFilterToCabangQuery($query, 'cabangs');

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
        $this->detailFilterBulan = $this->filterBulan;
        $this->detailFilterTahun = $this->filterTahun;
        $this->dispatch('open-detail-pegawai-modal');
    }

    #[\Livewire\Attributes\On('closeDetailPegawaiModal')]
    public function closeDetailPegawaiModal(): void
    {
        $this->detailPegawaiId = null;
    }

    // DETAIL PER KC
    public function openDetailKc(int $cabangId, string $status = 'ALL'): void
    {
        $this->detailKcCabangId = $cabangId;
        $this->detailKcStatus = strtoupper(trim($status)) ?: 'ALL';
        $this->detailKcFilterBulan = $this->filterBulan;
        $this->detailKcFilterTahun = $this->filterTahun;
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

        $query = Prospect::query()
            ->where('cabang_id', $this->detailKcCabangId)
            ->whereNull('deleted_at')
            ->when($this->detailKcFilterBulan !== '', function ($q) {
                $q->whereMonth('tanggal_prospek', (int) $this->detailKcFilterBulan);
            })
            ->when($this->detailKcFilterTahun !== '', function ($q) {
                $q->whereYear('tanggal_prospek', (int) $this->detailKcFilterTahun);
            });

        if ($this->detailKcStatus !== 'ALL') {
            $query->where('status', $this->detailKcStatus);
        }

        return $query
            ->latest('tanggal_prospek')
            ->latest('id')
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

        return Prospect::query()
            ->where('input_by', $this->detailPegawaiId)
            ->whereNull('deleted_at')
            ->when($this->detailFilterBulan !== '', function ($q) {
                $q->whereMonth('tanggal_prospek', (int) $this->detailFilterBulan);
            })
            ->when($this->detailFilterTahun !== '', function ($q) {
                $q->whereYear('tanggal_prospek', (int) $this->detailFilterTahun);
            })
            ->latest('tanggal_prospek')
            ->latest('id')
            ->select([
                'id',
                'tanggal_prospek',
                'nama',
                'jenis_produk',
                'jenis_usaha',
                'status',
            ]);
    }

    protected function getKcOrderField(): string
    {
        $allowed = [
            'kode_cabang',
            'nama_cabang',
            'total_pengajuan',
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
            'total_open',
            'total_follow_up',
            'total_closing',
            'total_rejected',
        ];

        return in_array($this->sortFieldPengaju, $allowed, true) ? $this->sortFieldPengaju : 'total_pengajuan';
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

    public function exportExcel()
    {
        $bulanNama = Carbon::createFromDate(
            (int) $this->filterTahun,
            (int) $this->filterBulan,
            1
        )->translatedFormat('F');

        if ($this->activeTab === 'kc') {
            $rows = $this->getKcBaseQuery()
                ->orderBy($this->getKcOrderField(), $this->sortDirectionKc)
                ->orderByRaw("CAST(cabangs.kode_cabang AS UNSIGNED) ASC")
                ->get();

            $filename = 'rekap_prospek_per_kc_' . $this->filterTahun . '_' . str_pad($this->filterBulan, 2, '0', STR_PAD_LEFT) . '.xls';

            return response()->streamDownload(function () use ($rows, $bulanNama) {
                echo '<html><head><meta charset="UTF-8"></head><body>';
                echo '<table border="1">';
                echo '<tr><th colspan="8" style="font-weight:bold;">Rekap Prospek Per KC Bulan ' . e($bulanNama) . ' ' . e($this->filterTahun) . '</th></tr>';
                echo '<tr>';
                echo '<th>No</th>';
                echo '<th>Kode Cabang</th>';
                echo '<th>Nama Cabang</th>';
                echo '<th>Jumlah Pengajuan</th>';
                echo '<th>Open</th>';
                echo '<th>Follow Up</th>';
                echo '<th>Closing</th>';
                echo '<th>Rejected</th>';
                echo '</tr>';

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

                echo '</table>';
                echo '</body></html>';
            }, $filename, [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            ]);
        }

        if ($this->activeTab === 'pengaju') {
            $rows = $this->getPengajuBaseQuery()
                ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN 0 ELSE 1 END ASC")
                ->orderBy($this->getPengajuOrderField(), $this->sortDirectionPengaju)
                ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN -1 ELSE CAST(cabangs.kode_cabang AS UNSIGNED) END ASC")
                ->get();

            $filename = 'rekap_pengaju_per_cabang_' . $this->filterTahun . '_' . str_pad($this->filterBulan, 2, '0', STR_PAD_LEFT) . '.xls';

            return response()->streamDownload(function () use ($rows, $bulanNama) {
                echo '<html><head><meta charset="UTF-8"></head><body>';
                echo '<table border="1">';
                echo '<tr><th colspan="8" style="font-weight:bold;">Rekap Pengaju Per Cabang Bulan ' . e($bulanNama) . ' ' . e($this->filterTahun) . '</th></tr>';
                echo '<tr>';
                echo '<th>No</th>';
                echo '<th>Kode Cabang</th>';
                echo '<th>Nama Cabang</th>';
                echo '<th>Jumlah Pengaju</th>';
                echo '<th>Open</th>';
                echo '<th>Follow Up</th>';
                echo '<th>Closing</th>';
                echo '<th>Rejected</th>';
                echo '</tr>';

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

                echo '</table>';
                echo '</body></html>';
            }, $filename, [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            ]);
        }

        $rows = $this->getPegawaiBaseQuery()
            ->orderBy($this->getPegawaiOrderField(), $this->sortDirectionPegawai)
            ->orderBy('users.id', 'desc')
            ->get();

        $filename = 'rekap_prospek_per_pegawai_' . $this->filterTahun . '_' . str_pad($this->filterBulan, 2, '0', STR_PAD_LEFT) . '.xls';

        return response()->streamDownload(function () use ($rows, $bulanNama) {
            echo '<html><head><meta charset="UTF-8"></head><body>';
            echo '<table border="1">';
            echo '<tr><th colspan="11" style="font-weight:bold;">Rekap Prospek Per Pegawai Bulan ' . e($bulanNama) . ' ' . e($this->filterTahun) . '</th></tr>';
            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Username</th>';
            echo '<th>Nama Lengkap</th>';
            echo '<th>Role</th>';
            echo '<th>Jabatan</th>';
            echo '<th>Kode Cabang</th>';
            echo '<th>Nama Cabang</th>';
            echo '<th>Jumlah Pengajuan</th>';
            echo '<th>Open</th>';
            echo '<th>Follow Up</th>';
            echo '<th>Closing</th>';
            echo '<th>Rejected</th>';
            echo '</tr>';

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

            echo '</table>';
            echo '</body></html>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function render()
    {
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
                  ->orWhereIn('kode_cabang', ['100', '200', '300', '400'])
                  ->orWhereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 1 AND 28");
            })
            ->orderByRaw("
                CASE
                    WHEN kode_cabang = '000' THEN 0
                    WHEN CAST(kode_cabang AS UNSIGNED) BETWEEN 1 AND 28 THEN 1
                    WHEN kode_cabang IN ('100','200','300','400') THEN 2
                    ELSE 3
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
            $items = $this->getKcBaseQuery()
                ->orderBy($this->getKcOrderField(), $this->sortDirectionKc)
                ->orderByRaw("CAST(cabangs.kode_cabang AS UNSIGNED) ASC")
                ->paginate(10);
        } elseif ($this->activeTab === 'pengaju') {
            $items = $this->getPengajuBaseQuery()
                ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN 0 ELSE 1 END ASC")
                ->orderBy($this->getPengajuOrderField(), $this->sortDirectionPengaju)
                ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN -1 ELSE CAST(cabangs.kode_cabang AS UNSIGNED) END ASC")
                ->paginate(10);
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
