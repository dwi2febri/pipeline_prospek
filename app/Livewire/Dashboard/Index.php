<?php

namespace App\Livewire\Dashboard;

use App\Models\Cabang;
use App\Models\Prospect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Index extends Component
{
    public ?int $filterCabang = null;
    public ?string $filterMapStatus = '';
    public bool $lockCabangFilter = false;

    public function mount(): void
    {
        $user = Auth::user();
        $role = strtoupper(trim((string) ($user->role ?? '')));

        if ($role === 'SUPERVISOR') {
            $this->filterCabang = $user->cabang_id ? (int) $user->cabang_id : null;
            $this->lockCabangFilter = true;
        }
    }

    public function updatedFilterCabang(): void
    {
        if ($this->lockCabangFilter) {
            $user = Auth::user();
            $this->filterCabang = $user->cabang_id ? (int) $user->cabang_id : null;
        }

        $this->dispatch('dashboard-refresh');
    }

    public function updatedFilterMapStatus(): void
    {
        $this->dispatch('dashboard-refresh');
    }

    protected function baseQuery()
    {
        $q = Prospect::query()->whereNull('deleted_at');

        if ($this->filterCabang) {
            $q->where('cabang_id', $this->filterCabang);
        }

        return $q;
    }

    protected function getUsahaReference()
    {
        $palette = [
            '#22c55e', // hijau
            '#3b82f6', // biru
            '#f59e0b', // amber
            '#ef4444', // merah
            '#8b5cf6', // ungu
            '#14b8a6', // teal
            '#f97316', // orange
            '#06b6d4', // cyan
            '#84cc16', // lime
            '#ec4899', // pink
            '#64748b', // slate
            '#a855f7', // violet
        ];

        $refs = DB::table('ref_jenis_usaha')
            ->where('aktif', 1)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get(['kode', 'nama', 'urutan']);

        $legendUsaha = [];
        $usahaColorMap = [];
        $usahaNameMap = [];

        foreach ($refs as $i => $r) {
            $kode = strtoupper(trim((string) $r->kode));
            $nama = trim((string) $r->nama);
            $color = $palette[$i % count($palette)];

            $legendUsaha[] = [
                'kode' => $kode,
                'nama' => $nama,
                'color' => $color,
            ];

            $usahaColorMap[$kode] = $color;
            $usahaNameMap[$kode] = $nama;
        }

        if (!isset($usahaColorMap['LAINNYA'])) {
            $usahaColorMap['LAINNYA'] = '#8b5cf6';
            $usahaNameMap['LAINNYA'] = 'Lainnya';
            $legendUsaha[] = [
                'kode' => 'LAINNYA',
                'nama' => 'Lainnya',
                'color' => '#8b5cf6',
            ];
        }

        return [
            'legendUsaha' => $legendUsaha,
            'usahaColorMap' => $usahaColorMap,
            'usahaNameMap' => $usahaNameMap,
        ];
    }

    public function render()
    {
        $cabangs = Cabang::query()
            ->where('aktif', 1)
            ->orderByRaw("LPAD(kode_cabang, 10, '0') ASC")
            ->get(['id', 'kode_cabang', 'nama_cabang']);

        $usahaRef = $this->getUsahaReference();
        $legendUsaha = $usahaRef['legendUsaha'];
        $usahaColorMap = $usahaRef['usahaColorMap'];
        $usahaNameMap = $usahaRef['usahaNameMap'];

        $base = $this->baseQuery();

        $summary = [
            'total'     => (clone $base)->count(),
            'follow_up' => (clone $base)->where('status', 'FOLLOW UP')->count(),
            'rejected'  => (clone $base)->where('status', 'REJECTED')->count(),
            'closing'   => (clone $base)->where('status', 'CLOSING')->count(),
        ];

        $closingPerCabangRaw = Prospect::query()
            ->select('cabang_id', DB::raw('COUNT(*) as total'))
            ->whereNull('deleted_at')
            ->where('status', 'CLOSING')
            ->whereBetween('cabang_id', [1, 28])
            ->when($this->filterCabang, fn($q) => $q->where('cabang_id', $this->filterCabang))
            ->groupBy('cabang_id')
            ->pluck('total', 'cabang_id')
            ->toArray();

        $closingCabangLabels = [];
        $closingCabangValues = [];

        $cabangs128 = Cabang::query()
            ->whereBetween('id', [1, 28])
            ->orderBy('id')
            ->get(['id', 'kode_cabang', 'nama_cabang']);

        foreach ($cabangs128 as $c) {
            $closingCabangLabels[] = $c->kode_cabang;
            $closingCabangValues[] = (int) ($closingPerCabangRaw[$c->id] ?? 0);
        }

        $produkRows = $this->baseQuery()
            ->select('jenis_produk', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_produk')
            ->orderBy('jenis_produk')
            ->get();

        $produkLabels = $produkRows->pluck('jenis_produk')->map(fn($v) => $v ?: '-')->values();
        $produkValues = $produkRows->pluck('total')->map(fn($v) => (int) $v)->values();

        $statusRows = $this->baseQuery()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $statusLabels = $statusRows->pluck('status')->map(fn($v) => $v ?: '-')->values();
        $statusValues = $statusRows->pluck('total')->map(fn($v) => (int) $v)->values();

        $usahaRows = $this->baseQuery()
            ->select('jenis_usaha', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_usaha')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $usahaLabels = $usahaRows->pluck('jenis_usaha')->map(function ($v) use ($usahaNameMap) {
            $kode = strtoupper(trim((string) ($v ?: 'LAINNYA')));
            return $usahaNameMap[$kode] ?? ucwords(strtolower(str_replace('_', ' ', $kode)));
        })->values();

        $usahaValues = $usahaRows->pluck('total')->map(fn($v) => (int) $v)->values();

        $trendRows = $this->baseQuery()
            ->selectRaw("DATE_FORMAT(tanggal_prospek, '%Y-%m') as ym, COUNT(*) as total")
            ->whereNotNull('tanggal_prospek')
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $trendLabels = $trendRows->pluck('ym')->values();
        $trendValues = $trendRows->pluck('total')->map(fn($v) => (int) $v)->values();

        $topCabang = Prospect::query()
            ->select('cabangs.kode_cabang', 'cabangs.nama_cabang', DB::raw('COUNT(prospects.id) as total'))
            ->join('cabangs', 'cabangs.id', '=', 'prospects.cabang_id')
            ->whereNull('prospects.deleted_at')
            ->when($this->filterCabang, fn($q) => $q->where('prospects.cabang_id', $this->filterCabang))
            ->groupBy('cabangs.kode_cabang', 'cabangs.nama_cabang')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topClosingCabang = Prospect::query()
            ->select('cabangs.kode_cabang', 'cabangs.nama_cabang', DB::raw('COUNT(prospects.id) as total'))
            ->join('cabangs', 'cabangs.id', '=', 'prospects.cabang_id')
            ->whereNull('prospects.deleted_at')
            ->where('prospects.status', 'CLOSING')
            ->when($this->filterCabang, fn($q) => $q->where('prospects.cabang_id', $this->filterCabang))
            ->groupBy('cabangs.kode_cabang', 'cabangs.nama_cabang')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topPegawai = Prospect::query()
            ->select('users.name', 'users.nama_lengkap', DB::raw('COUNT(prospects.id) as total'))
            ->join('users', 'users.id', '=', 'prospects.input_by')
            ->whereNull('prospects.deleted_at')
            ->when($this->filterCabang, fn($q) => $q->where('prospects.cabang_id', $this->filterCabang))
            ->groupBy('users.name', 'users.nama_lengkap')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $recent = $this->baseQuery()
            ->with('cabang')
            ->latest('tanggal_prospek')
            ->latest('id')
            ->limit(10)
            ->get();

        $mapQuery = Prospect::query()
            ->leftJoin('cabangs', 'cabangs.id', '=', 'prospects.cabang_id')
            ->leftJoin(DB::raw('(
                SELECT d1.prospect_id, d1.file_path
                FROM prospect_documents d1
                INNER JOIN (
                    SELECT prospect_id, MIN(id) as min_id
                    FROM prospect_documents
                    GROUP BY prospect_id
                ) d2 ON d1.id = d2.min_id
            ) docs'), 'docs.prospect_id', '=', 'prospects.id')
            ->whereNull('prospects.deleted_at')
            ->whereNotNull('prospects.lokasi_lat')
            ->whereNotNull('prospects.lokasi_lng')
            ->when($this->filterCabang, fn($q) => $q->where('prospects.cabang_id', $this->filterCabang))
            ->when($this->filterMapStatus !== null && $this->filterMapStatus !== '', fn($q) => $q->where('prospects.status', $this->filterMapStatus))
            ->select(
                'prospects.nama',
                'prospects.alamat',
                'prospects.jenis_usaha',
                'prospects.keterangan_usaha',
                'prospects.kab_kota',
                'prospects.kecamatan',
                'prospects.desa',
                'prospects.lokasi_lat',
                'prospects.lokasi_lng',
                'prospects.jenis_produk',
                'prospects.status',
                'cabangs.kode_cabang',
                'cabangs.nama_cabang',
                'docs.file_path'
            )
            ->limit(500)
            ->get();

        $mapItems = $mapQuery->map(function ($p) use ($usahaNameMap) {
            $photoUrl = null;

            if (!empty($p->file_path)) {
                $photoUrl = Storage::url($p->file_path);
            }

            $usahaKode = strtoupper(trim((string) ($p->jenis_usaha ?: 'LAINNYA')));

            return [
                'nama'              => $p->nama,
                'alamat'            => $p->alamat,
                'jenis_usaha_kode'  => $usahaKode,
                'jenis_usaha_label' => $usahaNameMap[$usahaKode] ?? ucwords(strtolower(str_replace('_', ' ', $usahaKode))),
                'keterangan_usaha'  => $p->keterangan_usaha,
                'kab_kota'          => $p->kab_kota,
                'kecamatan'         => $p->kecamatan,
                'desa'              => $p->desa,
                'lat'               => (float) $p->lokasi_lat,
                'lng'               => (float) $p->lokasi_lng,
                'jenis_produk'      => $p->jenis_produk,
                'status'            => $p->status,
                'cabang'            => trim(($p->kode_cabang ?: '-') . ' - ' . ($p->nama_cabang ?: '-')),
                'photo_url'         => $photoUrl,
            ];
        })->values();

        return view('livewire.dashboard.index', [
            'cabangs'             => $cabangs,
            'summary'             => $summary,
            'closingCabangLabels' => $closingCabangLabels,
            'closingCabangValues' => $closingCabangValues,
            'produkLabels'        => $produkLabels,
            'produkValues'        => $produkValues,
            'statusLabels'        => $statusLabels,
            'statusValues'        => $statusValues,
            'usahaLabels'         => $usahaLabels,
            'usahaValues'         => $usahaValues,
            'trendLabels'         => $trendLabels,
            'trendValues'         => $trendValues,
            'topCabang'           => $topCabang,
            'topClosingCabang'    => $topClosingCabang,
            'topPegawai'          => $topPegawai,
            'recent'              => $recent,
            'mapItems'            => $mapItems,
            'legendUsaha'         => $legendUsaha,
            'usahaColorMap'       => $usahaColorMap,
            'lockCabangFilter'    => $this->lockCabangFilter,
        ])->layout('layouts.bootstrap');
    }
}
