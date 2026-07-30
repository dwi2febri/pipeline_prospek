<?php

namespace App\Livewire\NominatifKredit;

use App\Models\Cabang;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class Index extends Component
{
    public ?string $filterCabang = '';
    public string $filterDateMode = 'monthly';
    public ?string $filterBulan = '';
    public ?string $filterTahun = '';
    public ?string $filterTanggalAwal = '';
    public ?string $filterTanggalAkhir = '';
    public string $filterReferralRole = 'all';

    protected array $productMap = [
        'KREDIT' => [
            'label' => 'Kredit',
            'table' => 'nominatif',
            'amount' => 'jml_pinjaman',
            'fallback_amounts' => [],
            'color' => '#2563eb',
            'icon' => 'bi-bank',
        ],
        'TABUNGAN' => [
            'label' => 'Tabungan',
            'table' => 'nominatif_tabungan',
            'amount' => 'saldo',
            'fallback_amounts' => [],
            'color' => '#06b6d4',
            'icon' => 'bi-wallet2',
        ],
        'DEPOSITO' => [
            'label' => 'Deposito',
            'table' => 'nominatif_deposito',
            'amount' => 'saldo_akhir',
            'fallback_amounts' => [],
            'color' => '#f97316',
            'icon' => 'bi-safe2',
        ],
    ];

    public function mount(): void
    {
        $this->filterDateMode = 'monthly';
        $this->filterBulan = (string) now()->month;
        $this->filterTahun = (string) now()->year;
        $this->filterTanggalAwal = '';
        $this->filterTanggalAkhir = '';
        $this->filterReferralRole = 'all';
    }

    public function updatedFilterCabang(): void
    {
        $this->dispatch('nominatif-report-refresh');
    }

    public function updatedFilterReferralRole(): void
    {
        $this->dispatch('nominatif-report-refresh');
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

        $this->dispatch('nominatif-report-refresh');
    }

    public function updatedFilterBulan(): void
    {
        $this->dispatch('nominatif-report-refresh');
    }

    public function updatedFilterTahun(): void
    {
        $this->dispatch('nominatif-report-refresh');
    }

    public function updatedFilterTanggalAwal(): void
    {
        if ($this->filterDateMode !== 'range') {
            $this->filterDateMode = 'range';
        }

        $this->dispatch('nominatif-report-refresh');
    }

    public function updatedFilterTanggalAkhir(): void
    {
        if ($this->filterDateMode !== 'range') {
            $this->filterDateMode = 'range';
        }

        $this->dispatch('nominatif-report-refresh');
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

    protected function applyDateFilter($query): void
    {
        $this->normalizeDateRange();

        if ($this->filterDateMode === 'monthly') {
            $bulan = (int) ($this->filterBulan !== '' ? $this->filterBulan : now()->month);
            $tahun = (int) ($this->filterTahun !== '' ? $this->filterTahun : now()->year);

            $query->whereMonth('prospects.tanggal_prospek', $bulan)
                ->whereYear('prospects.tanggal_prospek', $tahun);
        } elseif ($this->filterDateMode === 'range') {
            if ($this->filterTanggalAwal !== '') {
                $query->whereDate('prospects.tanggal_prospek', '>=', $this->filterTanggalAwal);
            }

            if ($this->filterTanggalAkhir !== '') {
                $query->whereDate('prospects.tanggal_prospek', '<=', $this->filterTanggalAkhir);
            }
        }
    }

    protected function applyReferralFilter($query): void
    {
        if ($this->filterReferralRole === 'ao') {
            $query->whereIn(DB::raw('UPPER(users.role)'), ['AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL']);
        } elseif ($this->filterReferralRole === 'pegawai') {
            $query->where(DB::raw('UPPER(users.role)'), 'PEGAWAI');
        }
    }

    protected function closingProspectsQuery(bool $applyCabangFilter = true)
    {
        $query = DB::table('prospects')
            ->leftJoin('users', 'users.id', '=', 'prospects.input_by')
            ->leftJoin('cabangs', 'cabangs.id', '=', 'prospects.cabang_id')
            ->whereNull('prospects.deleted_at')
            ->where('prospects.status', 'CLOSING')
            ->whereNotNull('prospects.no_rekening')
            ->where('prospects.no_rekening', '!=', '')
            ->whereIn(DB::raw('UPPER(prospects.jenis_produk)'), array_keys($this->productMap));

        if ($applyCabangFilter && $this->filterCabang !== '' && ctype_digit((string) $this->filterCabang)) {
            $query->where('prospects.cabang_id', (int) $this->filterCabang);
        }

        $this->applyDateFilter($query);
        $this->applyReferralFilter($query);

        return $query;
    }

    protected function normalizeProspectRecords(Collection $prospects): array
    {
        $records = [];
        $rekeningsByProduct = [];

        foreach ($prospects as $row) {
            $product = $this->normalizeProduct($row->jenis_produk);
            $rekening = trim((string) $row->no_rekening);

            if (!$product || $rekening === '') {
                continue;
            }

            $recordKey = $product . '|' . $row->cabang_id . '|' . $rekening;

            if (isset($records[$recordKey])) {
                continue;
            }

            $records[$recordKey] = [
                'prospect_id' => (int) $row->id,
                'tanggal_prospek' => $row->tanggal_prospek ?? null,
                'product' => $product,
                'rekening' => $rekening,
                'jenis_usaha' => strtoupper(trim((string) ($row->jenis_usaha ?: 'LAINNYA'))),
                'cabang_id' => (int) $row->cabang_id,
                'kode_cabang' => (string) ($row->kode_cabang ?? ''),
                'nama_cabang' => (string) ($row->nama_cabang ?? ''),
            ];

            $rekeningsByProduct[$product][] = $rekening;
        }

        return [$records, $rekeningsByProduct];
    }

    protected function normalizeProduct(?string $value): ?string
    {
        $value = strtoupper(trim((string) $value));

        return array_key_exists($value, $this->productMap) ? $value : null;
    }

    protected function defaultProductSummary(): array
    {
        $summary = [];

        foreach ($this->productMap as $key => $meta) {
            $summary[$key] = [
                'key' => $key,
                'label' => $meta['label'],
                'icon' => $meta['icon'],
                'color' => $meta['color'],
                'noa' => 0,
                'realisasi' => 0.0,
            ];
        }

        return $summary;
    }

    protected function fetchDpkAmounts(string $product, array $rekenings): array
    {
        $meta = $this->productMap[$product];

        $amountColumns = array_values(array_unique(array_merge(
            [$meta['amount']],
            $meta['fallback_amounts']
        )));

        $quotedColumns = array_map(function ($column) {
            return '`' . str_replace('`', '', $column) . '`';
        }, $amountColumns);

        if (count($quotedColumns) === 1) {
            $amountExpression = 'COALESCE(' . $quotedColumns[0] . ', 0)';
        } elseif (count($quotedColumns) > 1) {
            $amountExpression = 'COALESCE(' . implode(', ', $quotedColumns) . ', 0)';
        } else {
            $amountExpression = '0';
        }

        $amounts = [];

        foreach (array_chunk(array_values(array_unique($rekenings)), 800) as $chunk) {
            if (empty($chunk)) {
                continue;
            }

            $rows = DB::connection('dpk')
                ->table($meta['table'])
                ->select('no_rekening', DB::raw($amountExpression . ' as nominal'))
                ->whereIn('no_rekening', $chunk)
                ->get();

            foreach ($rows as $row) {
                $rekening = trim((string) $row->no_rekening);
                $amounts[$rekening] = (float) ($row->nominal ?? 0);
            }
        }

        return $amounts;
    }

    protected function formatJenisUsahaLabel(string $value): string
    {
        $value = trim(str_replace('_', ' ', strtolower($value)));

        return $value !== '' ? ucwords($value) : 'Lainnya';
    }

    protected function buildJenisUsahaCharts(Collection $jenisUsahaRows): array
    {
        $barRows = $jenisUsahaRows->take(12)->values();

        $barDatasets = [];

        foreach (['KREDIT', 'TABUNGAN', 'DEPOSITO'] as $product) {
            $barDatasets[] = [
                'label' => $this->productMap[$product]['label'],
                'data' => $barRows->pluck($product)->map(fn ($value) => (float) $value)->values()->all(),
                'backgroundColor' => $this->productMap[$product]['color'],
                'borderRadius' => 8,
                'borderSkipped' => false,
                'stack' => 'produk',
            ];
        }

        $donutRows = $jenisUsahaRows->take(8)->values();
        $otherTotal = (float) $jenisUsahaRows->slice(8)->sum('total');

        $donutLabels = $donutRows
            ->pluck('jenis_usaha')
            ->map(fn ($value) => $this->formatJenisUsahaLabel((string) $value))
            ->values()
            ->all();

        $donutValues = $donutRows
            ->pluck('total')
            ->map(fn ($value) => (float) $value)
            ->values()
            ->all();

        if ($otherTotal > 0) {
            $donutLabels[] = 'Lainnya';
            $donutValues[] = $otherTotal;
        }

        $palette = [
            '#2563eb',
            '#38bdf8',
            '#818cf8',
            '#1d4ed8',
            '#60a5fa',
            '#0f172a',
            '#93c5fd',
            '#0891b2',
            '#64748b',
        ];

        $donutColors = [];

        for ($i = 0; $i < count($donutLabels); $i++) {
            $donutColors[] = $palette[$i % count($palette)];
        }

        return [
            'barChartLabels' => $barRows
                ->pluck('jenis_usaha')
                ->map(fn ($value) => $this->formatJenisUsahaLabel((string) $value))
                ->values()
                ->all(),

            'barChartDatasets' => $barDatasets,

            'jenisUsahaDonutLabels' => $donutLabels,
            'jenisUsahaDonutValues' => $donutValues,
            'jenisUsahaDonutColors' => $donutColors,
        ];
    }

    protected function emptyReport(): array
    {
        return [
            'summary' => $this->defaultProductSummary(),
            'totalRealisasi' => 0.0,
            'totalNoa' => 0,
            'closingProspects' => 0,
            'matchedNoa' => 0,
            'unmatchedNoa' => 0,
            'rank' => null,
            'rankTotalCabang' => 28,
            'selectedCabang' => null,
            'jenisUsahaRows' => collect(),
            'topCabangRows' => collect(),
            'unmatchedRows' => collect(),

            'barChartLabels' => [],
            'barChartDatasets' => [],
            'jenisUsahaDonutLabels' => [],
            'jenisUsahaDonutValues' => [],
            'jenisUsahaDonutColors' => [],
        ];
    }

    protected function buildReport(): array
    {
        $report = $this->emptyReport();

        $selectColumns = [
            'prospects.id',
            'prospects.tanggal_prospek',
            'prospects.no_rekening',
            'prospects.jenis_produk',
            'prospects.jenis_usaha',
            'prospects.cabang_id',
            'cabangs.kode_cabang',
            'cabangs.nama_cabang',
        ];

        $prospects = $this->closingProspectsQuery()
            ->select(...$selectColumns)
            ->orderBy('prospects.cabang_id')
            ->limit(20000)
            ->get();

        $report['closingProspects'] = $prospects->count();

        if ($prospects->isEmpty()) {
            return $report;
        }

        [$uniqueRecords, $rekeningsByProduct] = $this->normalizeProspectRecords($prospects);

        if ($this->filterCabang !== '' && ctype_digit((string) $this->filterCabang)) {
            $rankingProspects = $this->closingProspectsQuery(false)
                ->select(...$selectColumns)
                ->orderBy('prospects.cabang_id')
                ->limit(30000)
                ->get();

            [$rankingRecords, $rankingRekeningsByProduct] = $this->normalizeProspectRecords($rankingProspects);

            foreach ($rankingRekeningsByProduct as $product => $rekenings) {
                $rekeningsByProduct[$product] = array_merge($rekeningsByProduct[$product] ?? [], $rekenings);
            }
        } else {
            $rankingRecords = $uniqueRecords;
        }

        $amountsByProduct = [];

        foreach (array_keys($this->productMap) as $product) {
            $amountsByProduct[$product] = $this->fetchDpkAmounts($product, $rekeningsByProduct[$product] ?? []);
        }

        $jenisUsahaMap = [];
        $branchMap = [];
        $unmatchedRows = [];
        $matched = 0;

        foreach ($uniqueRecords as $record) {
            $product = $record['product'];
            $rekening = $record['rekening'];

            if (!array_key_exists($rekening, $amountsByProduct[$product] ?? [])) {
                $unmatchedRows[] = [
                    'prospect_id' => $record['prospect_id'],
                    'tanggal_prospek' => $record['tanggal_prospek'],
                    'kode_cabang' => $record['kode_cabang'],
                    'nama_cabang' => $record['nama_cabang'],
                    'jenis_produk' => $product,
                    'jenis_produk_label' => $this->productMap[$product]['label'],
                    'no_rekening' => $rekening,
                    'jenis_usaha' => $record['jenis_usaha'] ?: 'LAINNYA',
                    'keterangan' => 'No rekening tidak ditemukan pada tabel ' . $this->productMap[$product]['table'],
                ];

                continue;
            }

            $amount = (float) $amountsByProduct[$product][$rekening];
            $matched++;

            $report['summary'][$product]['noa']++;
            $report['summary'][$product]['realisasi'] += $amount;

            $jenis = $record['jenis_usaha'] ?: 'LAINNYA';

            if (!isset($jenisUsahaMap[$jenis])) {
                $jenisUsahaMap[$jenis] = [
                    'jenis_usaha' => $jenis,
                    'KREDIT' => 0.0,
                    'TABUNGAN' => 0.0,
                    'DEPOSITO' => 0.0,
                    'total' => 0.0,
                    'noa' => 0,
                ];
            }

            $jenisUsahaMap[$jenis][$product] += $amount;
            $jenisUsahaMap[$jenis]['total'] += $amount;
            $jenisUsahaMap[$jenis]['noa']++;
        }

        foreach ($rankingRecords as $record) {
            $product = $record['product'];
            $rekening = $record['rekening'];

            if (!array_key_exists($rekening, $amountsByProduct[$product] ?? [])) {
                continue;
            }

            $amount = (float) $amountsByProduct[$product][$rekening];
            $branchId = $record['cabang_id'];

            if (!isset($branchMap[$branchId])) {
                $branchMap[$branchId] = [
                    'cabang_id' => $branchId,
                    'kode_cabang' => $record['kode_cabang'],
                    'nama_cabang' => $record['nama_cabang'],
                    'realisasi' => 0.0,
                    'noa' => 0,
                ];
            }

            $branchMap[$branchId]['realisasi'] += $amount;
            $branchMap[$branchId]['noa']++;
        }

        $report['matchedNoa'] = $matched;
        $report['unmatchedNoa'] = max(count($uniqueRecords) - $matched, 0);
        $report['totalRealisasi'] = collect($report['summary'])->sum('realisasi');
        $report['totalNoa'] = collect($report['summary'])->sum('noa');

        $report['jenisUsahaRows'] = collect($jenisUsahaMap)
            ->sortByDesc('total')
            ->values();

        $report['unmatchedRows'] = collect($unmatchedRows)
            ->sortBy(fn ($row) => ($row['kode_cabang'] ?? '') . '|' . ($row['jenis_produk'] ?? '') . '|' . ($row['no_rekening'] ?? ''))
            ->values();

        $topCabangRows = collect($branchMap)
            ->sortByDesc('realisasi')
            ->values()
            ->map(function ($row, $index) {
                $row['rank'] = $index + 1;
                return $row;
            });

        $report['topCabangRows'] = $topCabangRows->take(10)->values();

        if ($this->filterCabang !== '' && ctype_digit((string) $this->filterCabang)) {
            $selectedId = (int) $this->filterCabang;
            $selectedCabang = Cabang::query()->find($selectedId);
            $report['selectedCabang'] = $selectedCabang;
            $ranked = $topCabangRows->firstWhere('cabang_id', $selectedId);
            $report['rank'] = $ranked['rank'] ?? null;
        }

        $chartData = $this->buildJenisUsahaCharts($report['jenisUsahaRows']);

        return array_merge($report, $chartData);
    }

    public function render()
    {
        $connectionOk = false;
        $errorMessage = null;
        $report = $this->emptyReport();

        try {
            DB::connection('dpk')->table('nominatif')->limit(1)->exists();
            $connectionOk = true;
            $report = $this->buildReport();
        } catch (Throwable $e) {
            $errorMessage = $e->getMessage();
        }

        $cabangs = Cabang::query()
            ->where('aktif', 1)
            ->whereRaw('CAST(kode_cabang AS UNSIGNED) BETWEEN 1 AND 28')
            ->orderByRaw('CAST(kode_cabang AS UNSIGNED) ASC')
            ->get(['id', 'kode_cabang', 'nama_cabang']);

        $tahunOptions = collect(range((int) now()->year - 3, (int) now()->year + 1));

        $bulanOptions = collect(range(1, 12))->map(fn ($month) => [
            'id' => $month,
            'label' => Carbon::create()->month($month)->translatedFormat('F'),
        ]);

        return view('livewire.nominatif-kredit.index', [
            'cabangs' => $cabangs,
            'tahunOptions' => $tahunOptions,
            'bulanOptions' => $bulanOptions,
            'connectionOk' => $connectionOk,
            'errorMessage' => $errorMessage,
            'report' => $report,
            'productMap' => $this->productMap,
        ])->layout('layouts.bootstrap');
    }
}
