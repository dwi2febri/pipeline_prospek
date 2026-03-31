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

    public ?int $filterCabang = null;
    public string $filterBulan = '';
    public string $filterTahun = '';
    public string $search = '';

    public string $sortFieldPegawai = 'total_pengajuan';
    public string $sortDirectionPegawai = 'desc';

    public string $sortFieldKc = 'kode_cabang';
    public string $sortDirectionKc = 'asc';

    public bool $lockCabangFilter = false;

    public ?int $detailPegawaiId = null;
    public string $detailFilterBulan = '';
    public string $detailFilterTahun = '';

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
    ];

    public function mount(): void
    {
        $now = now();
        $this->filterBulan = (string) $now->month;
        $this->filterTahun = (string) $now->year;
        $this->detailFilterBulan = (string) $now->month;
        $this->detailFilterTahun = (string) $now->year;

        $role = $this->getRoleUserLogin();

        if ($role === 'SUPERVISOR') {
            $this->filterCabang = (int) (auth()->user()->cabang_id ?? 0);
            $this->lockCabangFilter = true;
        }
    }

    public function setActiveTab(string $tab): void
    {
        if (!in_array($tab, ['kc', 'pegawai'], true)) {
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

    public function updatedDetailFilterBulan(): void
    {
        $this->dispatch('open-detail-pegawai-modal');
    }

    public function updatedDetailFilterTahun(): void
    {
        $this->dispatch('open-detail-pegawai-modal');
    }

    protected function getRoleUserLogin(): string
    {
        return strtoupper(trim((string) (auth()->user()->role ?? '')));
    }

    protected function getLockedCabangId(): ?int
    {
        if ($this->getRoleUserLogin() === 'SUPERVISOR') {
            return (int) (auth()->user()->cabang_id ?? 0);
        }

        return $this->filterCabang ?: null;
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
        $cabangId = $this->getLockedCabangId();

        return User::query()
            ->leftJoin('cabangs', 'cabangs.id', '=', 'users.cabang_id')
            ->leftJoin('prospects', function ($join) use ($bulan, $tahun) {
                $join->on('prospects.input_by', '=', 'users.id')
                    ->whereMonth('prospects.tanggal_prospek', $bulan)
                    ->whereYear('prospects.tanggal_prospek', $tahun)
                    ->whereNull('prospects.deleted_at');
            })
            ->whereIn('users.role', ['PEGAWAI', 'AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL'])
            ->when($cabangId, function ($q) use ($cabangId) {
                $q->where('users.cabang_id', $cabangId);
            })
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
            })
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
        $cabangId = $this->getLockedCabangId();

        return Cabang::query()
            ->leftJoin('prospects', function ($join) use ($bulan, $tahun) {
                $join->on('prospects.cabang_id', '=', 'cabangs.id')
                    ->whereMonth('prospects.tanggal_prospek', $bulan)
                    ->whereYear('prospects.tanggal_prospek', $tahun)
                    ->whereNull('prospects.deleted_at');
            })
            ->where('cabangs.aktif', 1)
            ->whereRaw("CAST(cabangs.kode_cabang AS UNSIGNED) BETWEEN 1 AND 28")
            ->when($cabangId, function ($q) use ($cabangId) {
                $q->where('cabangs.id', $cabangId);
            })
            ->when(trim($this->search) !== '', function ($q) {
                $s = '%' . trim($this->search) . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('cabangs.kode_cabang', 'like', $s)
                        ->orWhere('cabangs.nama_cabang', 'like', $s);
                });
            })
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

    public function exportExcel()
    {
        $bulanNama = Carbon::createFromDate(
            (int) $this->filterTahun,
            (int) $this->filterBulan,
            1
        )->translatedFormat('F');

        if ($this->activeTab === 'kc') {
            $rows = $this->getKcBaseQuery()
                ->orderBy($this->sortFieldKc, $this->sortDirectionKc)
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

        $rows = $this->getPegawaiBaseQuery()
            ->orderBy($this->sortFieldPegawai, $this->sortDirectionPegawai)
            ->orderBy('users.name', 'asc')
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
            echo '<th>Cabang</th>';
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
                echo '<td>' . e($row->nama_lengkap ?: '-') . '</td>';
                echo '<td>' . e($row->role ?: '-') . '</td>';
                echo '<td>' . e($row->job_position ?: '-') . '</td>';
                echo '<td>' . e(($row->kode_cabang ?: '-') . ' - ' . ($row->nama_cabang ?: '-')) . '</td>';
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
        if ($this->getRoleUserLogin() === 'SUPERVISOR') {
            $this->filterCabang = (int) (auth()->user()->cabang_id ?? 0);
            $this->lockCabangFilter = true;
        }

        $cabangs = Cabang::query()
            ->where('aktif', 1)
            ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 1 AND 28")
            ->when($this->getRoleUserLogin() === 'SUPERVISOR', function ($q) {
                $q->where('id', (int) (auth()->user()->cabang_id ?? 0));
            })
            ->orderByRaw("CAST(kode_cabang AS UNSIGNED) ASC")
            ->get(['id', 'kode_cabang', 'nama_cabang']);

        if ($this->activeTab === 'kc') {
            $items = $this->getKcBaseQuery()
                ->orderBy($this->sortFieldKc, $this->sortDirectionKc)
                ->orderByRaw("CAST(cabangs.kode_cabang AS UNSIGNED) ASC")
                ->paginate(15);
        } else {
            $items = $this->getPegawaiBaseQuery()
                ->orderBy($this->sortFieldPegawai, $this->sortDirectionPegawai)
                ->orderBy('users.name', 'asc')
                ->paginate(15);
        }

        $detailPegawaiUser = $this->detailPegawaiId
            ? User::query()->find($this->detailPegawaiId)
            : null;

        $detailItems = $this->detailPegawaiId
            ? $this->getDetailPegawaiQuery()->get()
            : collect();

        $bulanOptions = collect(range(1, 12))->map(function ($b) {
            return [
                'id' => $b,
                'label' => Carbon::createFromDate(now()->year, $b, 1)->translatedFormat('F'),
            ];
        });

        $tahunSekarang = (int) now()->year;
        $tahunOptions = collect(range($tahunSekarang - 3, $tahunSekarang + 1));

        return view('livewire.reports.prospect-recap', [
            'cabangs' => $cabangs,
            'items' => $items,
            'bulanOptions' => $bulanOptions,
            'tahunOptions' => $tahunOptions,
            'activeTab' => $this->activeTab,
            'detailPegawaiUser' => $detailPegawaiUser,
            'detailItems' => $detailItems,
        ])->layout('layouts.bootstrap');
    }
}
