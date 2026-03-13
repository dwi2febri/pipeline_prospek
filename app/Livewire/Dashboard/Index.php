<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Prospect;
use App\Models\Cabang;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public ?int $filterCabang = null;

    public function updatedFilterCabang(): void
    {
        // auto rerender
    }

    protected function baseQuery()
    {
        $q = Prospect::query()->whereNull('deleted_at');

        if ($this->filterCabang) {
            $q->where('cabang_id', $this->filterCabang);
        }

        return $q;
    }

    public function render()
    {
        $cabangs = Cabang::query()
            ->where('aktif', 1)
            ->orderByRaw("LPAD(kode_cabang, 10, '0') ASC")
            ->get(['id', 'kode_cabang', 'nama_cabang']);

        $base = $this->baseQuery();

        $summary = [
            'total' => (clone $base)->count(),
            'follow_up' => (clone $base)->where('status', 'FOLLOW UP')->count(),
            'rejected' => (clone $base)->where('status', 'REJECTED')->count(),
            'closing' => (clone $base)->where('status', 'CLOSING')->count(),
        ];

        // closing per cabang 1-28
        $closingPerCabangRaw = Prospect::query()
            ->select('cabang_id', DB::raw('COUNT(*) as total'))
            ->where('status', 'CLOSING')
            ->whereBetween('cabang_id', [1, 28])
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
            $closingCabangValues[] = (int)($closingPerCabangRaw[$c->id] ?? 0);
        }

        // rekomendasi produk by filter cabang
        $produkRows = $this->baseQuery()
            ->select('jenis_produk', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_produk')
            ->orderBy('jenis_produk')
            ->get();

        $produkLabels = $produkRows->pluck('jenis_produk')->map(fn($v) => $v ?: '-')->values();
        $produkValues = $produkRows->pluck('total')->map(fn($v) => (int)$v)->values();

        // status distribution
        $statusRows = $this->baseQuery()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $statusLabels = $statusRows->pluck('status')->map(fn($v) => $v ?: '-')->values();
        $statusValues = $statusRows->pluck('total')->map(fn($v) => (int)$v)->values();

        // jenis usaha
        $usahaRows = $this->baseQuery()
            ->select('jenis_usaha', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_usaha')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $usahaLabels = $usahaRows->pluck('jenis_usaha')->map(fn($v) => $v ?: 'LAINNYA')->values();
        $usahaValues = $usahaRows->pluck('total')->map(fn($v) => (int)$v)->values();

        // trend 12 bulan
        $trendRows = $this->baseQuery()
            ->selectRaw("DATE_FORMAT(tanggal_prospek, '%Y-%m') as ym, COUNT(*) as total")
            ->whereNotNull('tanggal_prospek')
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $trendLabels = $trendRows->pluck('ym')->values();
        $trendValues = $trendRows->pluck('total')->map(fn($v) => (int)$v)->values();

        // top cabang
        $topCabang = Prospect::query()
            ->select('cabangs.kode_cabang', 'cabangs.nama_cabang', DB::raw('COUNT(prospects.id) as total'))
            ->join('cabangs', 'cabangs.id', '=', 'prospects.cabang_id')
            ->whereNull('prospects.deleted_at')
            ->when($this->filterCabang, fn($q) => $q->where('prospects.cabang_id', $this->filterCabang))
            ->groupBy('cabangs.kode_cabang', 'cabangs.nama_cabang')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // recent
        $recent = $this->baseQuery()
            ->with('cabang')
            ->latest('id')
            ->limit(10)
            ->get();

        // map markers
        $mapItems = $this->baseQuery()
            ->select('nama', 'jenis_usaha', 'kab_kota', 'kecamatan', 'desa', 'lokasi_lat', 'lokasi_lng', 'jenis_produk', 'status')
            ->whereNotNull('lokasi_lat')
            ->whereNotNull('lokasi_lng')
            ->limit(500)
            ->get()
            ->map(function ($p) {
                return [
                    'nama' => $p->nama,
                    'jenis_usaha' => $p->jenis_usaha ?: 'LAINNYA',
                    'kab_kota' => $p->kab_kota,
                    'kecamatan' => $p->kecamatan,
                    'desa' => $p->desa,
                    'lat' => (float)$p->lokasi_lat,
                    'lng' => (float)$p->lokasi_lng,
                    'jenis_produk' => $p->jenis_produk,
                    'status' => $p->status,
                ];
            })
            ->values();

        return view('livewire.dashboard.index', [
            'cabangs' => $cabangs,
            'summary' => $summary,
            'closingCabangLabels' => $closingCabangLabels,
            'closingCabangValues' => $closingCabangValues,
            'produkLabels' => $produkLabels,
            'produkValues' => $produkValues,
            'statusLabels' => $statusLabels,
            'statusValues' => $statusValues,
            'usahaLabels' => $usahaLabels,
            'usahaValues' => $usahaValues,
            'trendLabels' => $trendLabels,
            'trendValues' => $trendValues,
            'topCabang' => $topCabang,
            'recent' => $recent,
            'mapItems' => $mapItems,
        ])->layout('layouts.bootstrap');
    }
}
