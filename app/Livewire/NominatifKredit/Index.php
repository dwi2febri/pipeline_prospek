<?php

namespace App\Livewire\NominatifKredit;

use App\Models\Cabang;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class Index extends Component
{
    private const ALLOWED_ROLES = [
        'ADMIN',
        'MANAJEMEN',
        'MANAJEMEN KANWIL',
        'SUPERVISOR',
    ];

    public ?string $filterCabang = '';

    // Menentukan sumber tanggal untuk filter dashboard:
    // prospek = prospects.tanggal_prospek (internal key tetap: pengajuan)
    // realisasi = tgl_realisasi pada tabel nominatif DPK
    public string $filterDataMode = 'pengajuan';

    public string $filterDateMode = 'monthly';

    public ?string $filterBulan = '';

    public ?string $filterTahun = '';

    public ?string $filterTanggalAwal = '';

    public ?string $filterTanggalAkhir = '';

    public string $filterReferralRole = 'all';

    public bool $lockCabangFilter = false;

    protected array $productMap = [
        'KREDIT' => [
            'label' => 'Kredit',
            'table' => 'nominatif',
            'amount' => 'jml_pinjaman',
            'fallback_amounts' => [],
            'date_column' => 'tgl_realisasi',
            'color' => '#2563eb',
            'icon' => 'bi-bank',
        ],
        'TABUNGAN' => [
            'label' => 'Tabungan',
            'table' => 'nominatif_tabungan',
            'amount' => 'saldo',
            'fallback_amounts' => [],
            'date_column' => 'tgl_register',
            'color' => '#06b6d4',
            'icon' => 'bi-wallet2',
        ],
        'DEPOSITO' => [
            'label' => 'Deposito',
            'table' => 'nominatif_deposito',
            'amount' => 'saldo_akhir',
            'fallback_amounts' => [],
            'date_column' => 'tgl_registrasi',
            'color' => '#f97316',
            'icon' => 'bi-safe2',
        ],
    ];

    public function mount(): void
    {
        $this->authorizeAccess();

        $this->filterDataMode = 'pengajuan';
        $this->filterDateMode = 'monthly';
        $this->filterBulan = (string) now()->month;
        $this->filterTahun = (string) now()->year;
        $this->filterTanggalAwal = '';
        $this->filterTanggalAkhir = '';
        $this->filterReferralRole = 'all';

        if ($this->isSupervisor()) {
            $this->lockCabangFilter = true;
            $this->enforceSupervisorCabangFilter();
        }
    }

    public function updatedFilterCabang(): void
    {
        $this->enforceSupervisorCabangFilter();
        $this->dispatch('nominatif-report-refresh');
    }

    protected function currentUserRole(): string
    {
        return strtoupper(trim((string) (Auth::user()->role ?? '')));
    }

    protected function authorizeAccess(): void
    {
        abort_unless(in_array($this->currentUserRole(), self::ALLOWED_ROLES, true), 403);
    }

    protected function isSupervisor(): bool
    {
        return $this->currentUserRole() === 'SUPERVISOR';
    }

    protected function supervisorCabangId(): ?int
    {
        $cabangId = (int) (Auth::user()->cabang_id ?? 0);

        return $cabangId > 0 ? $cabangId : null;
    }

    protected function enforceSupervisorCabangFilter(): void
    {
        $this->lockCabangFilter = $this->isSupervisor();

        if (! $this->lockCabangFilter) {
            return;
        }

        $cabangId = $this->supervisorCabangId();
        $this->filterCabang = $cabangId ? (string) $cabangId : '';
    }

    public function updatedFilterDataMode(): void
    {
        if (! in_array($this->filterDataMode, ['pengajuan', 'realisasi'], true)) {
            $this->filterDataMode = 'pengajuan';
        }

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
        // Pada mode realisasi, tanggal tidak boleh difilter dari tabel prospects.
        // Filter periode akan diterapkan ke kolom tgl_realisasi pada DB nominatif.
        if ($this->filterDataMode === 'realisasi') {
            return;
        }

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

    protected function applyDpkDateFilter($query, string $dateColumn): void
    {
        if ($this->filterDataMode !== 'realisasi') {
            return;
        }

        $this->normalizeDateRange();

        if ($this->filterDateMode === 'monthly') {
            $bulan = (int) ($this->filterBulan !== '' ? $this->filterBulan : now()->month);
            $tahun = (int) ($this->filterTahun !== '' ? $this->filterTahun : now()->year);

            $query->whereMonth($dateColumn, $bulan)
                ->whereYear($dateColumn, $tahun);
        } elseif ($this->filterDateMode === 'range') {
            if ($this->filterTanggalAwal !== '') {
                $query->whereDate($dateColumn, '>=', $this->filterTanggalAwal);
            }

            if ($this->filterTanggalAkhir !== '') {
                $query->whereDate($dateColumn, '<=', $this->filterTanggalAkhir);
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

        if ($this->isSupervisor()) {
            $cabangId = $this->supervisorCabangId();

            if ($cabangId) {
                $query->where('prospects.cabang_id', $cabangId);
            } else {
                // Supervisor tanpa cabang harus gagal tertutup, bukan melihat seluruh data.
                $query->whereRaw('1 = 0');
            }
        } elseif ($applyCabangFilter && $this->filterCabang !== '' && ctype_digit((string) $this->filterCabang)) {
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

            if (! $product || $rekening === '') {
                continue;
            }

            $recordKey = $product.'|'.$row->cabang_id.'|'.$rekening;

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

    protected function fetchDpkData(string $product, array $rekenings): array
    {
        $meta = $this->productMap[$product];

        $amountColumns = array_values(array_unique(array_merge(
            [$meta['amount']],
            $meta['fallback_amounts']
        )));

        $quotedColumns = array_map(function ($column) {
            return '`'.str_replace('`', '', $column).'`';
        }, $amountColumns);

        if (count($quotedColumns) === 1) {
            $amountExpression = 'COALESCE('.$quotedColumns[0].', 0)';
        } elseif (count($quotedColumns) > 1) {
            $amountExpression = 'COALESCE('.implode(', ', $quotedColumns).', 0)';
        } else {
            $amountExpression = '0';
        }

        // Sumber nama nasabah HARUS dari tabel nominatif DPK, bukan prospects.
        // Nama kolom bisa berbeda antar dump/database, jadi dideteksi dari schema tabel terkait.
        $tableColumns = DB::connection('dpk')
            ->getSchemaBuilder()
            ->getColumnListing($meta['table']);

        $columnMap = [];
        foreach ($tableColumns as $column) {
            $columnMap[strtolower((string) $column)] = (string) $column;
        }

        $nameColumn = null;
        $nameCandidates = [
            'nama_nasabah',
            'nama',
            'nama_rekening',
            'nama_debitur',
            'nm_nasabah',
            'nama_pemilik',
        ];

        foreach ($nameCandidates as $candidate) {
            if (isset($columnMap[strtolower($candidate)])) {
                $nameColumn = $columnMap[strtolower($candidate)];
                break;
            }
        }

        // Fallback aman: pilih kolom yang mengandung kata "nama" pada tabel nominatif itu sendiri.
        if ($nameColumn === null) {
            foreach ($tableColumns as $column) {
                if (str_contains(strtolower((string) $column), 'nama')) {
                    $nameColumn = (string) $column;
                    break;
                }
            }
        }

        $nameExpression = $nameColumn !== null
            ? '`'.str_replace('`', '', $nameColumn).'`'
            : 'NULL';

        // Kolom tanggal realisasi/registrasi berbeda untuk tiap produk.
        // Semua mode tetap membaca tanggal ini agar tabel dapat menampilkan
        // Tanggal Prospek DAN Tanggal Realisasi secara bersamaan.
        $dateColumn = match ($product) {
            'KREDIT' => 'tgl_realisasi',
            'TABUNGAN' => 'tgl_register',
            'DEPOSITO' => 'tgl_registrasi',
            default => $meta['date_column'] ?? 'tgl_realisasi',
        };

        $quotedDateColumn = '`'.str_replace('`', '', $dateColumn).'`';
        $data = [];

        foreach (array_chunk(array_values(array_unique($rekenings)), 800) as $chunk) {
            if (empty($chunk)) {
                continue;
            }

            $query = DB::connection('dpk')
                ->table($meta['table'])
                ->select(
                    'no_rekening',
                    DB::raw($quotedDateColumn.' as tgl_realisasi'),
                    DB::raw($nameExpression.' as nama_nasabah'),
                    DB::raw($amountExpression.' as nominal')
                );

            if ($this->filterDataMode === 'realisasi') {
                // Filter Bulanan/Range memakai tanggal dari tabel nominatif masing-masing produk.
                $this->applyDpkDateFilter($query, $dateColumn);
            }

            $query->whereIn('no_rekening', $chunk);

            $rows = $query->get();

            foreach ($rows as $row) {
                $rekening = trim((string) $row->no_rekening);

                $data[$rekening] = [
                    'nominal' => (float) ($row->nominal ?? 0),
                    'tgl_realisasi' => $row->tgl_realisasi ?? null,
                    'nama_nasabah' => trim((string) ($row->nama_nasabah ?? '')),
                ];
            }
        }

        return $data;
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
            'topCabangRowsByProduct' => collect([
                'ALL' => collect(),
                'KREDIT' => collect(),
                'TABUNGAN' => collect(),
                'DEPOSITO' => collect(),
            ]),
            'matchedRowsByProduct' => collect([
                'KREDIT' => collect(),
                'TABUNGAN' => collect(),
                'DEPOSITO' => collect(),
            ]),
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
        $this->enforceSupervisorCabangFilter();

        $report = $this->emptyReport();

        if ($this->filterCabang !== '' && ctype_digit((string) $this->filterCabang)) {
            $selectedId = (int) $this->filterCabang;
            $report['selectedCabang'] = Cabang::query()->find($selectedId);
        }

        if ($this->isSupervisor()) {
            $report['rankTotalCabang'] = $this->supervisorCabangId() ? 1 : 0;
        }

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

        $dpkDataByProduct = [];

        foreach (array_keys($this->productMap) as $product) {
            $dpkDataByProduct[$product] = $this->fetchDpkData($product, $rekeningsByProduct[$product] ?? []);
        }

        $jenisUsahaMap = [];
        $branchMaps = [
            'ALL' => [],
            'KREDIT' => [],
            'TABUNGAN' => [],
            'DEPOSITO' => [],
        ];
        $matchedRowsByProduct = [
            'KREDIT' => [],
            'TABUNGAN' => [],
            'DEPOSITO' => [],
        ];
        $unmatchedRows = [];
        $matched = 0;

        foreach ($uniqueRecords as $record) {
            $product = $record['product'];
            $rekening = $record['rekening'];

            if (! array_key_exists($rekening, $dpkDataByProduct[$product] ?? [])) {
                // Pada mode tgl realisasi, rekening yang tidak masuk periode tgl_realisasi
                // tidak boleh dianggap sebagai unmatched; cukup dikeluarkan dari dashboard.
                if ($this->filterDataMode === 'realisasi') {
                    continue;
                }

                $unmatchedRows[] = [
                    'prospect_id' => $record['prospect_id'],
                    'tanggal_prospek' => $record['tanggal_prospek'],
                    'kode_cabang' => $record['kode_cabang'],
                    'nama_cabang' => $record['nama_cabang'],
                    'jenis_produk' => $product,
                    'jenis_produk_label' => $this->productMap[$product]['label'],
                    'no_rekening' => $rekening,
                    'jenis_usaha' => $record['jenis_usaha'] ?: 'LAINNYA',
                    'keterangan' => 'No rekening tidak ditemukan pada tabel '.$this->productMap[$product]['table'],
                ];

                continue;
            }

            $dpkRow = $dpkDataByProduct[$product][$rekening];
            $amount = (float) ($dpkRow['nominal'] ?? 0);
            $tanggalRealisasi = $dpkRow['tgl_realisasi'] ?? null;
            $tanggalData = $this->filterDataMode === 'realisasi'
                ? $tanggalRealisasi
                : $record['tanggal_prospek'];

            $matched++;

            $matchedRowsByProduct[$product][] = [
                'prospect_id' => $record['prospect_id'],
                'tanggal_prospek' => $record['tanggal_prospek'],
                'tanggal_realisasi' => $tanggalRealisasi,
                'tanggal_data' => $tanggalData,
                'kode_cabang' => $record['kode_cabang'],
                'nama_cabang' => $record['nama_cabang'],
                'nama_nasabah' => (string) ($dpkRow['nama_nasabah'] ?? ''),
                'jenis_produk' => $product,
                'jenis_produk_label' => $this->productMap[$product]['label'],
                'no_rekening' => $rekening,
                'jenis_usaha' => $record['jenis_usaha'] ?: 'LAINNYA',
                'realisasi' => $amount,
            ];

            $report['summary'][$product]['noa']++;
            $report['summary'][$product]['realisasi'] += $amount;

            $jenis = $record['jenis_usaha'] ?: 'LAINNYA';

            if (! isset($jenisUsahaMap[$jenis])) {
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

            if (! array_key_exists($rekening, $dpkDataByProduct[$product] ?? [])) {
                continue;
            }

            $amount = (float) ($dpkDataByProduct[$product][$rekening]['nominal'] ?? 0);
            $branchId = $record['cabang_id'];

            foreach (['ALL', $product] as $group) {
                if (! isset($branchMaps[$group][$branchId])) {
                    $branchMaps[$group][$branchId] = [
                        'cabang_id' => $branchId,
                        'kode_cabang' => $record['kode_cabang'],
                        'nama_cabang' => $record['nama_cabang'],
                        'realisasi' => 0.0,
                        'noa' => 0,
                    ];
                }

                $branchMaps[$group][$branchId]['realisasi'] += $amount;
                $branchMaps[$group][$branchId]['noa']++;
            }
        }

        $report['matchedNoa'] = $matched;

        if ($this->filterDataMode === 'realisasi') {
            // Seluruh angka dashboard pada mode ini hanya menghitung data nominatif
            // yang tgl_realisasi-nya masuk filter aktif.
            $report['closingProspects'] = $matched;
            $report['unmatchedNoa'] = 0;
        } else {
            $report['unmatchedNoa'] = max(count($uniqueRecords) - $matched, 0);
        }
        $report['totalRealisasi'] = collect($report['summary'])->sum('realisasi');
        $report['totalNoa'] = collect($report['summary'])->sum('noa');

        $report['jenisUsahaRows'] = collect($jenisUsahaMap)
            ->sortByDesc('total')
            ->values();

        $report['unmatchedRows'] = collect($unmatchedRows)
            ->sortBy(fn ($row) => ($row['kode_cabang'] ?? '').'|'.($row['jenis_produk'] ?? '').'|'.($row['no_rekening'] ?? ''))
            ->values();

        $report['matchedRowsByProduct'] = collect($matchedRowsByProduct)
            ->map(fn ($rows) => collect($rows)
                ->sortBy(fn ($row) => ($row['tanggal_data'] ?? '').'|'.($row['kode_cabang'] ?? '').'|'.($row['no_rekening'] ?? ''))
                ->values());

        $rankedCabangRowsByProduct = collect($branchMaps)->map(function ($rows) {
            return collect($rows)
                ->sortByDesc('realisasi')
                ->values()
                ->map(function ($row, $index) {
                    $row['rank'] = $index + 1;

                    return $row;
                });
        });

        $topCabangRowsByProduct = $rankedCabangRowsByProduct
            ->map(fn ($rows) => $rows->take(10)->values());

        $topCabangRows = $topCabangRowsByProduct->get('ALL', collect());

        $report['topCabangRows'] = $topCabangRows;
        $report['topCabangRowsByProduct'] = $topCabangRowsByProduct;

        if ($this->filterCabang !== '' && ctype_digit((string) $this->filterCabang)) {
            $selectedId = (int) $this->filterCabang;
            $ranked = $rankedCabangRowsByProduct->get('ALL', collect())->firstWhere('cabang_id', $selectedId);
            $report['rank'] = $ranked['rank'] ?? null;
        }

        $chartData = $this->buildJenisUsahaCharts($report['jenisUsahaRows']);

        return array_merge($report, $chartData);
    }

    protected function exportPeriodLabel(): string
    {
        if ($this->filterDateMode === 'range') {
            return trim(($this->filterTanggalAwal ?: '-').' s.d. '.($this->filterTanggalAkhir ?: '-'));
        }

        if ($this->filterDateMode === 'monthly') {
            $month = (int) ($this->filterBulan !== '' ? $this->filterBulan : now()->month);
            $year = (int) ($this->filterTahun !== '' ? $this->filterTahun : now()->year);

            return Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');
        }

        return 'Semua Periode';
    }

    public function exportRealisasiNoa(string $product)
    {
        $this->authorizeAccess();
        $product = strtoupper(trim($product));
        abort_unless(array_key_exists($product, $this->productMap), 404);

        $report = $this->buildReport();
        $rows = $report['matchedRowsByProduct']->get($product, collect());
        $productLabel = $this->productMap[$product]['label'];
        $periodLabel = $this->exportPeriodLabel();
        $dataModeLabel = $this->filterDataMode === 'realisasi' ? 'Tanggal Realisasi' : 'Tanggal Prospek';
        $branchLabel = $report['selectedCabang']
            ? $report['selectedCabang']->kode_cabang.' - '.$report['selectedCabang']->nama_cabang
            : 'Semua Cabang';
        $filename = 'noa_realisasi_'.strtolower($product).'_'.now()->format('Ymd_His').'.xls';

        return response()->streamDownload(function () use ($rows, $productLabel, $periodLabel, $branchLabel, $dataModeLabel) {
            echo '<html><head><meta charset="UTF-8"></head><body><table border="1">';
            echo '<tr><th colspan="9" style="font-weight:bold;font-size:16px;">NOA REALISASI '.e(strtoupper($productLabel)).'</th></tr>';
            echo '<tr><td colspan="9">Mode Data: '.e($dataModeLabel).' | Periode: '.e($periodLabel).' | Cabang: '.e($branchLabel).'</td></tr>';
            echo '<tr><th>No</th><th>Tanggal Prospek</th><th>Tanggal Realisasi</th><th>Kode Cabang</th><th>Nama Cabang</th><th>Nama Nasabah</th><th>No Rekening</th><th>Jenis Usaha</th><th>Realisasi</th></tr>';

            foreach ($rows as $index => $row) {
                $tanggalProspek = ! empty($row['tanggal_prospek']) ? Carbon::parse($row['tanggal_prospek'])->format('d/m/Y') : '-';
                $tanggalRealisasi = ! empty($row['tanggal_realisasi']) ? Carbon::parse($row['tanggal_realisasi'])->format('d/m/Y') : '-';
                echo '<tr>';
                echo '<td>'.($index + 1).'</td>';
                echo '<td>'.e($tanggalProspek).'</td>';
                echo '<td>'.e($tanggalRealisasi).'</td>';
                echo '<td style="mso-number-format:\'@\';">'.e($row['kode_cabang']).'</td>';
                echo '<td>'.e($row['nama_cabang']).'</td>';
                echo '<td>'.e($row['nama_nasabah'] ?: '-').'</td>';
                echo '<td style="mso-number-format:\'@\';">'.e($row['no_rekening']).'</td>';
                echo '<td>'.e($this->formatJenisUsahaLabel($row['jenis_usaha'])).'</td>';
                echo '<td style="mso-number-format:\'0\';">'.(float) $row['realisasi'].'</td>';
                echo '</tr>';
            }

            echo '</table></body></html>';
        }, $filename, ['Content-Type' => 'application/vnd.ms-excel; charset=UTF-8']);
    }

    public function render()
    {
        $this->authorizeAccess();
        $this->enforceSupervisorCabangFilter();

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
            ->when($this->isSupervisor(), function ($query) {
                $cabangId = $this->supervisorCabangId();

                return $cabangId
                    ? $query->whereKey($cabangId)
                    : $query->whereRaw('1 = 0');
            })
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
            'lockCabangFilter' => $this->lockCabangFilter,
        ])->layout('layouts.bootstrap');
    }
}
