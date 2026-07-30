<?php

namespace App\Livewire\Dashboard;

use App\Models\Cabang;
use App\Models\Prospect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Index extends Component
{
    public ?string $filterCabang = '';
    public ?string $filterMapStatus = '';
    public ?string $filterMapJenisUsaha = '';
    public ?string $filterMapProduk = '';

    public string $filterDateMode = 'all'; // all | monthly | range
    public ?string $filterBulan = '';
    public ?string $filterTahun = '';
    public ?string $filterTanggalAwal = '';
    public ?string $filterTanggalAkhir = '';

    public string $filterGrafikClosingMode = 'closing';
    // closing | pengaju | per_kc_non_closing_rejected | per_kc_follow_up | per_kc_rejected

    public bool $lockCabangFilter = false;
    public string $lockCabangMessage = '';

    protected function currentUserRole(): string
    {
        return strtoupper(trim((string) (Auth::user()->role ?? '')));
    }

    protected function getUserKanwilKode(): string
    {
        $user = Auth::user();

        if (!$user || empty($user->cabang_id)) {
            return '';
        }

        $cabang = Cabang::query()
            ->where('id', (int) $user->cabang_id)
            ->first(['id', 'kode_cabang', 'nama_cabang']);

        return trim((string) ($cabang->kode_cabang ?? ''));
    }

    protected function getKanwilCabangRange(string $kodeKanwil): array
    {
        return match ($kodeKanwil) {
            '100' => [1, 7],
            '200' => [8, 14],
            '300' => [15, 21],
            '400' => [22, 28],
            default => [0, 0],
        };
    }

    public function mount(): void
    {
        $user = Auth::user();
        $role = $this->currentUserRole();

        $this->filterDateMode = 'all';
        $this->filterBulan = '';
        $this->filterTahun = '';
        $this->filterTanggalAwal = '';
        $this->filterTanggalAkhir = '';
        $this->filterGrafikClosingMode = 'closing';

        if ($role === 'SUPERVISOR') {
            $this->filterCabang = $user->cabang_id ? (string) $user->cabang_id : '';
            $this->lockCabangFilter = true;
            $this->lockCabangMessage = 'Filter cabang otomatis mengikuti cabang user supervisor.';
        }

        // MANAJEMEN KANWIL:
        // - default langsung ke kanwilnya
        // - TIDAK di-lock, jadi masih bisa klik filter
        // - opsi filter dibatasi hanya kanwil tersebut + cabang di bawahnya
        if ($role === 'MANAJEMEN KANWIL') {
            $kodeKanwil = $this->getUserKanwilKode();

            if (in_array($kodeKanwil, ['100', '200', '300', '400'], true)) {
                $this->filterCabang = $kodeKanwil;

                [$start, $end] = $this->getKanwilCabangRange($kodeKanwil);

                $labelKanwil = match ($kodeKanwil) {
                    '100' => 'KANWIL SEMARANG',
                    '200' => 'KANWIL SOLO',
                    '300' => 'KANWIL BANYUMAS',
                    '400' => 'KANWIL PEKALONGAN',
                    default => 'KANWIL',
                };

                $this->lockCabangFilter = false;
                $this->lockCabangMessage = 'Default filter mengikuti ' . $labelKanwil . ' (cabang ' . str_pad((string) $start, 3, '0', STR_PAD_LEFT) . ' - ' . str_pad((string) $end, 3, '0', STR_PAD_LEFT) . ').';
            }
        }
    }

    public function updatedFilterCabang(): void
    {
        if ($this->lockCabangFilter) {
            $user = Auth::user();
            $this->filterCabang = $user->cabang_id ? (string) $user->cabang_id : '';
        }

        $this->dispatch('dashboard-refresh');
    }

    public function updatedFilterMapStatus(): void
    {
        $this->dispatch('dashboard-refresh');
    }

    public function updatedFilterMapJenisUsaha(): void
    {
        $this->dispatch('dashboard-refresh');
    }

    public function updatedFilterMapProduk(): void
    {
        $this->dispatch('dashboard-refresh');
    }

    public function updatedFilterDateMode(): void
    {
        if ($this->filterDateMode === 'all') {
            $this->filterBulan = '';
            $this->filterTahun = '';
            $this->filterTanggalAwal = '';
            $this->filterTanggalAkhir = '';
        } elseif ($this->filterDateMode === 'monthly') {
            $this->filterBulan = $this->filterBulan ?: (string) now()->month;
            $this->filterTahun = $this->filterTahun ?: (string) now()->year;
            $this->filterTanggalAwal = '';
            $this->filterTanggalAkhir = '';
        } elseif ($this->filterDateMode === 'range') {
            $this->filterBulan = '';
            $this->filterTahun = '';
        }

        $this->dispatch('dashboard-refresh');
    }

    public function updatedFilterBulan(): void
    {
        $this->dispatch('dashboard-refresh');
    }

    public function updatedFilterTahun(): void
    {
        $this->dispatch('dashboard-refresh');
    }

    public function updatedFilterTanggalAwal(): void
    {
        if ($this->filterDateMode !== 'range') {
            $this->filterDateMode = 'range';
        }

        $this->dispatch('dashboard-refresh');
    }

    public function updatedFilterTanggalAkhir(): void
    {
        if ($this->filterDateMode !== 'range') {
            $this->filterDateMode = 'range';
        }

        $this->dispatch('dashboard-refresh');
    }

    public function updatedFilterGrafikClosingMode(): void
    {
        $this->dispatch('dashboard-refresh');
    }

    protected function normalizeDateRange(): void
    {
        if (
            $this->filterTanggalAwal !== '' &&
            $this->filterTanggalAkhir !== '' &&
            $this->filterTanggalAwal > $this->filterTanggalAkhir
        ) {
            [$this->filterTanggalAwal, $this->filterTanggalAkhir] = [$this->filterTanggalAkhir, $this->filterTanggalAwal];
        }
    }

    protected function getCabangIdsFilter(): array
    {
        $value = trim((string) $this->filterCabang);

        if ($value === '') {
            return [];
        }

        if (!ctype_digit($value)) {
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

    protected function applyCabangFilter($query, string $column = 'prospects.cabang_id')
    {
        $ids = $this->getCabangIdsFilter();

        if (!empty($ids)) {
            $query->whereIn($column, $ids);
        }

        return $query;
    }

    protected function applyDateFilter($query, string $column = 'prospects.tanggal_prospek')
    {
        $this->normalizeDateRange();

        if ($this->filterDateMode === 'monthly') {
            $bulan = (int) ($this->filterBulan !== '' ? $this->filterBulan : now()->month);
            $tahun = (int) ($this->filterTahun !== '' ? $this->filterTahun : now()->year);

            $query->whereMonth($column, $bulan)
                  ->whereYear($column, $tahun);
        } elseif ($this->filterDateMode === 'range') {
            if ($this->filterTanggalAwal !== '') {
                $query->whereDate($column, '>=', $this->filterTanggalAwal);
            }
            if ($this->filterTanggalAkhir !== '') {
                $query->whereDate($column, '<=', $this->filterTanggalAkhir);
            }
        }

        return $query;
    }

    protected function baseQuery()
    {
        $q = Prospect::query()->whereNull('prospects.deleted_at');

        $this->applyCabangFilter($q, 'prospects.cabang_id');
        $this->applyDateFilter($q, 'prospects.tanggal_prospek');

        return $q;
    }

    protected function getUsahaReference()
    {
        $palette = [
            '#22c55e', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#14b8a6',
            '#f97316', '#06b6d4', '#84cc16', '#ec4899', '#64748b', '#a855f7',
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

    protected function getCabangs128()
    {
        return Cabang::query()
            ->whereRaw("CAST(kode_cabang AS UNSIGNED) BETWEEN 1 AND 28")
            ->orderByRaw("CAST(kode_cabang AS UNSIGNED) ASC")
            ->get(['id', 'kode_cabang', 'nama_cabang']);
    }

    protected function getGrafikUtamaData(): array
    {
        $cabangs128 = $this->getCabangs128();

        $labels = [];
        $values = [];
        $title = 'Closing per Cabang (001 - 028)';
        $subtitle = 'Memantau jumlah closing tiap kantor cabang';
        $datasetLabel = 'Closing';

        if ($this->filterGrafikClosingMode === 'pengaju') {
            $title = 'Pengajuan per KC Pengaju';
            $subtitle = 'Jumlah seluruh pengajuan prospek per kantor cabang';
            $datasetLabel = 'Pengajuan';

            $raw = Prospect::query()
                ->select('prospects.cabang_id', DB::raw('COUNT(*) as total'))
                ->whereNull('prospects.deleted_at');

            $this->applyCabangFilter($raw, 'prospects.cabang_id');
            $this->applyDateFilter($raw, 'prospects.tanggal_prospek');

            $raw = $raw->groupBy('prospects.cabang_id')
                ->pluck('total', 'prospects.cabang_id')
                ->toArray();
        } elseif ($this->filterGrafikClosingMode === 'per_kc_non_closing_rejected') {
            $title = 'Per KC (Semua selain Closing dan Rejected)';
            $subtitle = 'Menghitung OPEN + FOLLOW UP per cabang';
            $datasetLabel = 'Open + Follow Up';

            $raw = Prospect::query()
                ->select('prospects.cabang_id', DB::raw('COUNT(*) as total'))
                ->whereNull('prospects.deleted_at')
                ->whereIn('prospects.status', ['OPEN', 'FOLLOW UP']);

            $this->applyCabangFilter($raw, 'prospects.cabang_id');
            $this->applyDateFilter($raw, 'prospects.tanggal_prospek');

            $raw = $raw->groupBy('prospects.cabang_id')
                ->pluck('total', 'prospects.cabang_id')
                ->toArray();
        } elseif ($this->filterGrafikClosingMode === 'per_kc_follow_up') {
            $title = 'Follow Up per Cabang (001 - 028)';
            $subtitle = 'Memantau jumlah follow up tiap kantor cabang';
            $datasetLabel = 'Follow Up';

            $raw = Prospect::query()
                ->select('prospects.cabang_id', DB::raw('COUNT(*) as total'))
                ->whereNull('prospects.deleted_at')
                ->where('prospects.status', 'FOLLOW UP');

            $this->applyCabangFilter($raw, 'prospects.cabang_id');
            $this->applyDateFilter($raw, 'prospects.tanggal_prospek');

            $raw = $raw->groupBy('prospects.cabang_id')
                ->pluck('total', 'prospects.cabang_id')
                ->toArray();
        } elseif ($this->filterGrafikClosingMode === 'per_kc_rejected') {
            $title = 'Rejected per Cabang (001 - 028)';
            $subtitle = 'Memantau jumlah rejected tiap kantor cabang';
            $datasetLabel = 'Rejected';

            $raw = Prospect::query()
                ->select('prospects.cabang_id', DB::raw('COUNT(*) as total'))
                ->whereNull('prospects.deleted_at')
                ->where('prospects.status', 'REJECTED');

            $this->applyCabangFilter($raw, 'prospects.cabang_id');
            $this->applyDateFilter($raw, 'prospects.tanggal_prospek');

            $raw = $raw->groupBy('prospects.cabang_id')
                ->pluck('total', 'prospects.cabang_id')
                ->toArray();
        } else {
            $raw = Prospect::query()
                ->select('prospects.cabang_id', DB::raw('COUNT(*) as total'))
                ->whereNull('prospects.deleted_at')
                ->where('prospects.status', 'CLOSING');

            $this->applyCabangFilter($raw, 'prospects.cabang_id');
            $this->applyDateFilter($raw, 'prospects.tanggal_prospek');

            $raw = $raw->groupBy('prospects.cabang_id')
                ->pluck('total', 'prospects.cabang_id')
                ->toArray();
        }

        foreach ($cabangs128 as $c) {
            $labels[] = $c->kode_cabang;
            $values[] = (int) ($raw[$c->id] ?? 0);
        }

        return compact('labels', 'values', 'title', 'subtitle', 'datasetLabel');
    }

    public function render()
    {
        $role = $this->currentUserRole();
        $userKanwilKode = $this->getUserKanwilKode();

        $cabangsQuery = Cabang::query()
            ->where('aktif', 1);

        if ($role === 'MANAJEMEN KANWIL' && in_array($userKanwilKode, ['100', '200', '300', '400'], true)) {
            [$start, $end] = $this->getKanwilCabangRange($userKanwilKode);

            $cabangsQuery->where(function ($q) use ($userKanwilKode, $start, $end) {
                $q->where('kode_cabang', $userKanwilKode)
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
            ->orderByRaw("CAST(kode_cabang AS UNSIGNED) ASC")
            ->get(['id', 'kode_cabang', 'nama_cabang']);

        $tahunOptions = collect(range((int) now()->year - 3, (int) now()->year + 1));

        $bulanOptions = collect(range(1, 12))->map(function ($b) {
            return [
                'id' => $b,
                'label' => Carbon::create()->month($b)->translatedFormat('F'),
            ];
        });

        $usahaRef = $this->getUsahaReference();
        $legendUsaha = $usahaRef['legendUsaha'];
        $usahaColorMap = $usahaRef['usahaColorMap'];
        $usahaNameMap = $usahaRef['usahaNameMap'];

        $base = $this->baseQuery();

        $summary = [
            'total'     => (clone $base)->count(),
            'open'      => (clone $base)->where('prospects.status', 'OPEN')->count(),
            'follow_up' => (clone $base)->where('prospects.status', 'FOLLOW UP')->count(),
            'rejected'  => (clone $base)->where('prospects.status', 'REJECTED')->count(),
            'closing'   => (clone $base)->where('prospects.status', 'CLOSING')->count(),
        ];

        $grafikUtama = $this->getGrafikUtamaData();
        $closingCabangLabels = $grafikUtama['labels'];
        $closingCabangValues = $grafikUtama['values'];

        $produkRows = $this->baseQuery()
            ->select('prospects.jenis_produk', DB::raw('COUNT(*) as total'))
            ->groupBy('prospects.jenis_produk')
            ->orderBy('prospects.jenis_produk')
            ->get();

        $produkLabels = $produkRows->pluck('jenis_produk')->map(fn ($v) => $v ?: '-')->values();
        $produkValues = $produkRows->pluck('total')->map(fn ($v) => (int) $v)->values();

        $statusOrderMap = [
            'OPEN' => 1,
            'FOLLOW UP' => 2,
            'REJECTED' => 3,
            'CLOSING' => 4,
        ];

        $statusRows = $this->baseQuery()
            ->select('prospects.status', DB::raw('COUNT(*) as total'))
            ->groupBy('prospects.status')
            ->get()
            ->sortBy(function ($row) use ($statusOrderMap) {
                $status = strtoupper(trim((string) $row->status));
                return $statusOrderMap[$status] ?? 99;
            })
            ->values();

        $statusLabels = $statusRows->pluck('status')->map(fn ($v) => $v ?: '-')->values();
        $statusValues = $statusRows->pluck('total')->map(fn ($v) => (int) $v)->values();

        $usahaRows = $this->baseQuery()
            ->select('prospects.jenis_usaha', DB::raw('COUNT(*) as total'))
            ->groupBy('prospects.jenis_usaha')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $usahaLabels = $usahaRows->pluck('jenis_usaha')->map(function ($v) use ($usahaNameMap) {
            $kode = strtoupper(trim((string) ($v ?: 'LAINNYA')));
            return $usahaNameMap[$kode] ?? ucwords(strtolower(str_replace('_', ' ', $kode)));
        })->values();

        $usahaValues = $usahaRows->pluck('total')->map(fn ($v) => (int) $v)->values();

        $trendRows = $this->baseQuery()
            ->selectRaw("DATE_FORMAT(prospects.tanggal_prospek, '%Y-%m') as ym, COUNT(*) as total")
            ->whereNotNull('prospects.tanggal_prospek')
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $trendLabels = $trendRows->pluck('ym')->values();
        $trendValues = $trendRows->pluck('total')->map(fn ($v) => (int) $v)->values();

        $topCabang = Prospect::query()
            ->select('cabangs.kode_cabang', 'cabangs.nama_cabang', DB::raw('COUNT(prospects.id) as total'))
            ->join('cabangs', 'cabangs.id', '=', 'prospects.cabang_id')
            ->whereNull('prospects.deleted_at');

        $this->applyCabangFilter($topCabang, 'prospects.cabang_id');
        $this->applyDateFilter($topCabang, 'prospects.tanggal_prospek');

        $topCabang = $topCabang
            ->groupBy('cabangs.kode_cabang', 'cabangs.nama_cabang')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topClosingCabang = Prospect::query()
            ->select('cabangs.kode_cabang', 'cabangs.nama_cabang', DB::raw('COUNT(prospects.id) as total'))
            ->join('cabangs', 'cabangs.id', '=', 'prospects.cabang_id')
            ->whereNull('prospects.deleted_at')
            ->where('prospects.status', 'CLOSING');

        $this->applyCabangFilter($topClosingCabang, 'prospects.cabang_id');
        $this->applyDateFilter($topClosingCabang, 'prospects.tanggal_prospek');

        $topClosingCabang = $topClosingCabang
            ->groupBy('cabangs.kode_cabang', 'cabangs.nama_cabang')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topPegawai = Prospect::query()
            ->select('users.name', 'users.nama_lengkap', DB::raw('COUNT(prospects.id) as total'))
            ->join('users', 'users.id', '=', 'prospects.input_by')
            ->whereNull('prospects.deleted_at');

        $this->applyCabangFilter($topPegawai, 'prospects.cabang_id');
        $this->applyDateFilter($topPegawai, 'prospects.tanggal_prospek');

        $topPegawai = $topPegawai
            ->groupBy('users.name', 'users.nama_lengkap')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $recent = $this->baseQuery()
            ->with('cabang')
            ->latest('prospects.tanggal_prospek')
            ->latest('prospects.id')
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
                    WHERE LOWER(file_path) LIKE "%.jpg"
                       OR LOWER(file_path) LIKE "%.jpeg"
                       OR LOWER(file_path) LIKE "%.png"
                       OR LOWER(file_path) LIKE "%.webp"
                       OR LOWER(file_path) LIKE "%.gif"
                    GROUP BY prospect_id
                ) d2 ON d1.id = d2.min_id
            ) docs'), 'docs.prospect_id', '=', 'prospects.id')
            ->whereNull('prospects.deleted_at')
            ->whereNotNull('prospects.lokasi_lat')
            ->whereNotNull('prospects.lokasi_lng');

        $this->applyCabangFilter($mapQuery, 'prospects.cabang_id');
        $this->applyDateFilter($mapQuery, 'prospects.tanggal_prospek');

        if ($this->filterMapStatus !== null && $this->filterMapStatus !== '') {
            $mapQuery->where('prospects.status', $this->filterMapStatus);
        }

        if ($this->filterMapJenisUsaha !== null && $this->filterMapJenisUsaha !== '') {
            $mapQuery->where('prospects.jenis_usaha', $this->filterMapJenisUsaha);
        }

        if ($this->filterMapProduk !== null && $this->filterMapProduk !== '') {
            $mapQuery->where('prospects.jenis_produk', $this->filterMapProduk);
        }

        $mapQuery = $mapQuery
            ->select(
                'prospects.nama',
                'prospects.no_hp',
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
                $photoUrl = Storage::disk('public')->url(ltrim((string) $p->file_path, '/'));
            }

            $usahaKode = strtoupper(trim((string) ($p->jenis_usaha ?: 'LAINNYA')));

            return [
                'nama'              => $p->nama,
                'no_hp'             => $p->no_hp,
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

        $mapJenisUsahaOptions = Prospect::query()
            ->whereNull('deleted_at')
            ->whereNotNull('jenis_usaha')
            ->where('jenis_usaha', '!=', '')
            ->distinct()
            ->orderBy('jenis_usaha')
            ->pluck('jenis_usaha');

        $mapProdukOptions = Prospect::query()
            ->whereNull('deleted_at')
            ->whereNotNull('jenis_produk')
            ->where('jenis_produk', '!=', '')
            ->distinct()
            ->orderBy('jenis_produk')
            ->pluck('jenis_produk');

        return view('livewire.dashboard.index', [
            'cabangs'               => $cabangs,
            'summary'               => $summary,
            'closingCabangLabels'   => $closingCabangLabels,
            'closingCabangValues'   => $closingCabangValues,
            'produkLabels'          => $produkLabels,
            'produkValues'          => $produkValues,
            'statusLabels'          => $statusLabels,
            'statusValues'          => $statusValues,
            'usahaLabels'           => $usahaLabels,
            'usahaValues'           => $usahaValues,
            'trendLabels'           => $trendLabels,
            'trendValues'           => $trendValues,
            'topCabang'             => $topCabang,
            'topClosingCabang'      => $topClosingCabang,
            'topPegawai'            => $topPegawai,
            'recent'                => $recent,
            'mapItems'              => $mapItems,
            'legendUsaha'           => $legendUsaha,
            'usahaColorMap'         => $usahaColorMap,
            'lockCabangFilter'      => $this->lockCabangFilter,
            'lockCabangMessage'     => $this->lockCabangMessage,
            'tahunOptions'          => $tahunOptions,
            'bulanOptions'          => $bulanOptions,
            'mapJenisUsahaOptions'  => $mapJenisUsahaOptions,
            'mapProdukOptions'      => $mapProdukOptions,
            'grafikUtamaTitle'      => $grafikUtama['title'],
            'grafikUtamaSubtitle'   => $grafikUtama['subtitle'],
            'grafikUtamaDataset'    => $grafikUtama['datasetLabel'],
        ])->layout('layouts.bootstrap');
    }
}
