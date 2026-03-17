<?php

namespace App\Livewire\Reports;

use App\Models\Cabang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProspectRecap extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public ?int $filterCabang = null;
    public string $filterBulan = '';
    public string $filterTahun = '';
    public string $search = '';

    public string $sortField = 'total_pengajuan';
    public string $sortDirection = 'desc';

    public bool $lockCabangFilter = false;

    protected $queryString = [
        'filterCabang' => ['except' => ''],
        'filterBulan' => ['except' => ''],
        'filterTahun' => ['except' => ''],
        'search' => ['except' => ''],
        'sortField' => ['except' => 'total_pengajuan'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(): void
    {
        $now = now();
        $this->filterBulan = (string) $now->month;
        $this->filterTahun = (string) $now->year;

        $role = $this->getRoleUserLogin();

        if ($role === 'SUPERVISOR') {
            $this->filterCabang = (int) (auth()->user()->cabang_id ?? 0);
            $this->lockCabangFilter = true;
        }
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

    public function sortBy(string $field): void
    {
        $allowed = [
            'name',
            'nama_lengkap',
            'role',
            'job_position',
            'kode_cabang',
            'total_pengajuan',
            'total_follow_up',
            'total_closing',
            'total_rejected',
        ];

        if (!in_array($field, $allowed, true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;

            if (in_array($field, ['total_pengajuan', 'total_follow_up', 'total_closing', 'total_rejected'], true)) {
                $this->sortDirection = 'desc';
            } else {
                $this->sortDirection = 'asc';
            }
        }

        $this->resetPage();
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

    protected function getBaseQuery()
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
                DB::raw("SUM(CASE WHEN prospects.status = 'FOLLOW UP' THEN 1 ELSE 0 END) as total_follow_up"),
                DB::raw("SUM(CASE WHEN prospects.status = 'CLOSING' THEN 1 ELSE 0 END) as total_closing"),
                DB::raw("SUM(CASE WHEN prospects.status = 'REJECTED' THEN 1 ELSE 0 END) as total_rejected"),
            ]);
    }

    public function exportExcel()
    {
        $rows = $this->getBaseQuery()
            ->orderBy($this->sortField, $this->sortDirection)
            ->orderBy('users.name', 'asc')
            ->get();

        $bulanNama = Carbon::createFromDate(
            (int) $this->filterTahun,
            (int) $this->filterBulan,
            1
        )->translatedFormat('F');

        $filename = 'rekap_prospek_' . $this->filterTahun . '_' . str_pad($this->filterBulan, 2, '0', STR_PAD_LEFT) . '.xls';

        return response()->streamDownload(function () use ($rows, $bulanNama) {
            echo '<html><head><meta charset="UTF-8"></head><body>';
            echo '<table border="1">';
            echo '<tr>';
            echo '<th colspan="10" style="font-weight:bold;">Rekap Prospek Bulan ' . e($bulanNama) . ' ' . e($this->filterTahun) . '</th>';
            echo '</tr>';
            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Username</th>';
            echo '<th>Nama Lengkap</th>';
            echo '<th>Role</th>';
            echo '<th>Jabatan</th>';
            echo '<th>Cabang</th>';
            echo '<th>Jumlah Pengajuan</th>';
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
            ->when($this->getRoleUserLogin() === 'SUPERVISOR', function ($q) {
                $q->where('id', (int) (auth()->user()->cabang_id ?? 0));
            })
            ->orderByRaw("LPAD(kode_cabang, 10, '0') ASC")
            ->get(['id', 'kode_cabang', 'nama_cabang']);

        $items = $this->getBaseQuery()
            ->orderBy($this->sortField, $this->sortDirection)
            ->orderBy('users.name', 'asc')
            ->paginate(15);

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
        ])->layout('layouts.bootstrap');
    }
}
