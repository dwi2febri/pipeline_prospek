<div class="container-fluid px-0 nominatif-report">
  <style>
    .nominatif-report{
      padding-bottom:88px;
    }

    .report-title{
      font-size:2.05rem;
      font-weight:900;
      color:#0f172a;
      line-height:1.08;
      letter-spacing:-.03em;
    }

    .report-subtitle{
      color:#64748b;
      margin-top:6px;
      font-size:1rem;
    }

    .report-status{
      display:inline-flex;
      align-items:center;
      gap:8px;
      border-radius:999px;
      padding:10px 14px;
      font-weight:900;
      white-space:nowrap;
      border:1px solid #bfdbfe;
      background:#eff6ff;
      color:#1d4ed8;
      box-shadow:0 10px 22px rgba(37,99,235,.08);
    }

    .report-status.offline{
      border-color:#fecaca;
      background:#fef2f2;
      color:#b91c1c;
    }

    .pro-filter-shell{
      position:relative;
      border:1px solid #dbeafe;
      border-radius:28px;
      background:
        radial-gradient(circle at top left, rgba(37,99,235,.08), transparent 34%),
        linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
      box-shadow:0 18px 40px rgba(15,23,42,.08);
      padding:18px;
      margin-bottom:18px;
      overflow:hidden;
    }

    .pro-filter-title{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
    }

    .pro-filter-title h3{
      margin:0;
      font-size:1.08rem;
      font-weight:950;
      color:#0f172a;
      letter-spacing:-.02em;
    }

    .pro-filter-badge{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 12px;
      border-radius:999px;
      background:#eff6ff;
      border:1px solid #bfdbfe;
      color:#1d4ed8;
      font-weight:900;
      font-size:.86rem;
      white-space:nowrap;
    }

    .filter-label{
      display:flex;
      align-items:center;
      gap:8px;
      margin-bottom:8px;
      color:#475569;
      font-size:.78rem;
      font-weight:950;
      text-transform:uppercase;
      letter-spacing:.14em;
    }

    .filter-control{
      position:relative;
    }

    .filter-input{
      width:100%;
      min-height:54px;
      border:1px solid #dbeafe;
      border-radius:18px;
      background:#ffffff;
      color:#0f172a;
      font-weight:800;
      outline:none;
      box-shadow:none;
      padding:0 44px 0 46px;
      transition:.18s ease;
      appearance:none;
      -webkit-appearance:none;
      -moz-appearance:none;
    }

    .filter-input:hover{
      border-color:#93c5fd;
      background:#f8fbff;
    }

    .filter-input:focus{
      border-color:#2563eb;
      box-shadow:0 0 0 4px rgba(37,99,235,.12);
      background:#ffffff;
    }

    .filter-control-icon{
      position:absolute;
      left:16px;
      top:50%;
      transform:translateY(-50%);
      color:#2563eb;
      font-size:1.05rem;
      z-index:2;
      pointer-events:none;
    }

    .filter-chevron{
      position:absolute;
      right:16px;
      top:50%;
      transform:translateY(-50%);
      color:#64748b;
      font-size:1rem;
      z-index:2;
      pointer-events:none;
    }

    .filter-date{
      padding-right:16px;
    }

    .metric-card{
      position:relative;
      overflow:hidden;
      border:0;
      border-radius:24px;
      min-height:154px;
      padding:20px;
      color:#fff;
      box-shadow:0 18px 34px rgba(15,23,42,.13);
    }

    .metric-card .label{
      font-size:.98rem;
      font-weight:850;
      opacity:.96;
      margin-bottom:12px;
    }

    .metric-card .value{
      font-size:1.72rem;
      font-weight:950;
      letter-spacing:-.03em;
      line-height:1.1;
    }

    .metric-card .noa{
      margin-top:10px;
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:7px 11px;
      border-radius:999px;
      background:rgba(255,255,255,.18);
      border:1px solid rgba(255,255,255,.22);
      font-weight:850;
      font-size:.86rem;
    }

    .metric-card .icon{
      position:absolute;
      right:18px;
      bottom:14px;
      font-size:58px;
      opacity:.18;
    }

    .metric-kredit{ background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%); }
    .metric-tabungan{ background:linear-gradient(135deg,#06b6d4 0%,#0284c7 100%); }
    .metric-deposito{ background:linear-gradient(135deg,#f97316 0%,#c2410c 100%); }
    .metric-total{ background:linear-gradient(135deg,#0f172a 0%,#334155 100%); }

    .report-panel{
      border:1px solid #e8eef6;
      border-radius:24px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
      box-shadow:0 14px 30px rgba(15,23,42,.06);
      overflow:hidden;
    }

    .panel-head{
      padding:18px 20px 0 20px;
    }

    .panel-title{
      font-size:1.12rem;
      font-weight:950;
      color:#0f172a;
      margin-bottom:4px;
      letter-spacing:-.02em;
    }

    .panel-sub{
      color:#64748b;
      font-size:.9rem;
    }

    .panel-body{
      padding:16px 20px 20px 20px;
    }

    .rank-box{
      border-radius:24px;
      padding:20px;
      background:
        radial-gradient(circle at top right, rgba(37,99,235,.12), transparent 34%),
        linear-gradient(135deg,#eff6ff 0%,#ffffff 100%);
      border:1px solid #dbeafe;
      min-height:100%;
      box-shadow:0 14px 30px rgba(15,23,42,.05);
    }

    .rank-number{
      font-size:3rem;
      font-weight:950;
      color:#1d4ed8;
      line-height:1;
      letter-spacing:-.05em;
    }

    .rank-muted{
      color:#64748b;
      font-weight:800;
    }

    .chart-box{
      position:relative;
      height:330px;
      min-height:330px;
    }

    .chart-box.chart-donut{
      height:330px;
      min-height:330px;
    }

    .report-table thead th{
      background:#f8fafc;
      border-bottom:1px solid #e8eef6;
      color:#334155;
      font-size:.82rem;
      font-weight:950;
      white-space:nowrap;
      text-transform:uppercase;
      letter-spacing:.05em;
    }

    .report-table tbody td{
      border-color:#eef2f7;
      vertical-align:middle;
      color:#1f2937;
      white-space:nowrap;
    }

    .product-dot{
      width:11px;
      height:11px;
      border-radius:999px;
      display:inline-block;
      margin-right:8px;
    }

    .product-pill{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      border-radius:999px;
      padding:6px 10px;
      font-size:.76rem;
      font-weight:950;
      letter-spacing:.04em;
      border:1px solid transparent;
    }

    .product-pill-kredit{
      color:#1d4ed8;
      background:#eff6ff;
      border-color:#bfdbfe;
    }

    .product-pill-tabungan{
      color:#0369a1;
      background:#ecfeff;
      border-color:#bae6fd;
    }

    .product-pill-deposito{
      color:#c2410c;
      background:#fff7ed;
      border-color:#fed7aa;
    }

    .empty-state{
      padding:22px;
      color:#64748b;
      text-align:center;
      border:1px dashed #dbe3ef;
      border-radius:18px;
      background:#f8fafc;
      font-weight:800;
    }

    .unmatched-panel{
      border-color:#bfdbfe;
    }

    .unmatched-count{
      display:inline-flex;
      align-items:center;
      gap:8px;
      border-radius:999px;
      padding:8px 12px;
      background:#eff6ff;
      border:1px solid #bfdbfe;
      color:#1d4ed8;
      font-weight:950;
      font-size:.86rem;
    }

    @media (max-width: 767.98px){
      .report-title{
        font-size:1.56rem;
      }

      .metric-card{
        min-height:138px;
      }

      .chart-box,
      .chart-box.chart-donut{
        height:280px;
        min-height:280px;
      }

      .pro-filter-title{
        align-items:flex-start;
        flex-direction:column;
      }
    }
  </style>

  @php
    $money = fn ($value) => 'Rp' . number_format((float) $value, 0, ',', '.');

    $formatJenisUsaha = fn ($value) => ucwords(strtolower(str_replace('_', ' ', $value ?: 'LAINNYA')));

    $tanggalView = function ($value) {
      if (!$value) return '-';

      try {
        return \Carbon\Carbon::parse($value)->translatedFormat('d M Y');
      } catch (\Throwable $e) {
        return '-';
      }
    };

    $summary = $report['summary'];

    $selectedCabangText = $report['selectedCabang']
      ? $report['selectedCabang']->kode_cabang . ' - ' . $report['selectedCabang']->nama_cabang
      : 'Semua Cabang';
  @endphp

  <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
    <div>
      <div class="report-title">Report Prospek Pegawai Cabang</div>
      <div class="report-subtitle">Realisasi closing dari prospek yang cocok dengan nominatif.</div>
    </div>

    <div class="report-status {{ $connectionOk ? '' : 'offline' }}">
      <i class="bi {{ $connectionOk ? 'bi-database-check' : 'bi-database-x' }}"></i>
      {{ $connectionOk ? 'DB Nominatif Terhubung' : 'DB Nominatif Belum Terhubung' }}
    </div>
  </div>

  @if($errorMessage)
    <div class="alert alert-danger rounded-4 shadow-sm">
      <div class="fw-bold mb-1">Koneksi atau pengambilan data nominatif belum bisa digunakan.</div>
      <div class="small">{{ $errorMessage }}</div>
    </div>
  @endif

  <div class="pro-filter-shell"
       data-mobile-filter-panel
       data-mobile-filter-key="nominatif-kredit">
    <div class="pro-filter-title">
      <h3><i class="bi bi-sliders2 me-2 text-primary"></i>Pencarian Cepat</h3>
      <div class="pro-filter-badge">
        <i class="bi bi-funnel"></i>
        Filter Aktif
      </div>
    </div>

    <div class="row g-3 align-items-end">
      <div class="col-12 col-lg-3">
        <label class="filter-label">
          <i class="bi bi-bank"></i>
          Filter Cabang
        </label>
        <div class="filter-control">
          <i class="bi bi-building filter-control-icon"></i>
          <select class="filter-input" wire:model.live="filterCabang">
            <option value="">Semua Cabang</option>
            @foreach($cabangs as $cabang)
              <option value="{{ $cabang->id }}">{{ $cabang->kode_cabang }} - {{ $cabang->nama_cabang }}</option>
            @endforeach
          </select>
          <i class="bi bi-chevron-down filter-chevron"></i>
        </div>
      </div>

      <div class="col-12 col-lg-2">
        <label class="filter-label">
          <i class="bi bi-calendar2-week"></i>
          Mode Tanggal
        </label>
        <div class="filter-control">
          <i class="bi bi-calendar-range filter-control-icon"></i>
          <select class="filter-input" wire:model.live="filterDateMode">
            <option value="all">Semua Data</option>
            <option value="monthly">Bulanan</option>
            <option value="range">Range Tanggal</option>
          </select>
          <i class="bi bi-chevron-down filter-chevron"></i>
        </div>
      </div>

      @if($filterDateMode === 'monthly')
        <div class="col-6 col-lg-2">
          <label class="filter-label">
            <i class="bi bi-calendar-month"></i>
            Bulan
          </label>
          <div class="filter-control">
            <i class="bi bi-calendar3 filter-control-icon"></i>
            <select class="filter-input" wire:model.live="filterBulan">
              @foreach($bulanOptions as $bulan)
                <option value="{{ $bulan['id'] }}">{{ $bulan['label'] }}</option>
              @endforeach
            </select>
            <i class="bi bi-chevron-down filter-chevron"></i>
          </div>
        </div>

        <div class="col-6 col-lg-2">
          <label class="filter-label">
            <i class="bi bi-calendar-check"></i>
            Tahun
          </label>
          <div class="filter-control">
            <i class="bi bi-calendar4 filter-control-icon"></i>
            <select class="filter-input" wire:model.live="filterTahun">
              @foreach($tahunOptions as $tahun)
                <option value="{{ $tahun }}">{{ $tahun }}</option>
              @endforeach
            </select>
            <i class="bi bi-chevron-down filter-chevron"></i>
          </div>
        </div>
      @elseif($filterDateMode === 'range')
        <div class="col-6 col-lg-2">
          <label class="filter-label">
            <i class="bi bi-calendar-plus"></i>
            Tanggal Awal
          </label>
          <div class="filter-control">
            <i class="bi bi-calendar-event filter-control-icon"></i>
            <input type="date" class="filter-input filter-date" wire:model.live="filterTanggalAwal">
          </div>
        </div>

        <div class="col-6 col-lg-2">
          <label class="filter-label">
            <i class="bi bi-calendar-minus"></i>
            Tanggal Akhir
          </label>
          <div class="filter-control">
            <i class="bi bi-calendar-event filter-control-icon"></i>
            <input type="date" class="filter-input filter-date" wire:model.live="filterTanggalAkhir">
          </div>
        </div>
      @endif

      <div class="col-12 col-lg-3">
        <label class="filter-label">
          <i class="bi bi-person-check"></i>
          Referral By
        </label>
        <div class="filter-control">
          <i class="bi bi-people filter-control-icon"></i>
          <select class="filter-input" wire:model.live="filterReferralRole">
            <option value="all">AO + Pegawai</option>
            <option value="ao">AO Saja</option>
            <option value="pegawai">Pegawai Saja</option>
          </select>
          <i class="bi bi-chevron-down filter-chevron"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-12 col-md-6 col-xl-3">
      <div class="metric-card metric-total">
        <div class="label">Total Realisasi</div>
        <div class="value">{{ $money($report['totalRealisasi']) }}</div>
        <div class="noa">
          <i class="bi bi-check2-circle"></i>
          {{ number_format($report['totalNoa']) }} NOA match nominatif
        </div>
        <div class="icon"><i class="bi bi-graph-up-arrow"></i></div>
      </div>
    </div>

    @foreach(['KREDIT' => 'metric-kredit', 'TABUNGAN' => 'metric-tabungan', 'DEPOSITO' => 'metric-deposito'] as $key => $class)
      <div class="col-12 col-md-6 col-xl-3">
        <div class="metric-card {{ $class }}">
          <div class="label">Realisasi {{ $summary[$key]['label'] }}</div>
          <div class="value">{{ $money($summary[$key]['realisasi']) }}</div>
          <div class="noa">
            <i class="bi bi-layers"></i>
            {{ number_format($summary[$key]['noa']) }} NOA
          </div>
          <div class="icon"><i class="bi {{ $summary[$key]['icon'] }}"></i></div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="row g-3 mb-3">
    <div class="col-12 col-xl-4">
      <div class="rank-box">
        <div class="d-flex align-items-start justify-content-between gap-3">
          <div>
            <div class="panel-title mb-1">Peringkat Cabang</div>
            <div class="rank-muted">{{ $selectedCabangText }}</div>
          </div>
          <i class="bi bi-trophy fs-2 text-primary"></i>
        </div>

        <div class="mt-4 d-flex align-items-end gap-2">
          <div class="rank-number">
            {{ $report['rank'] ? '#' . $report['rank'] : '-' }}
          </div>
          <div class="rank-muted mb-2">dari {{ $report['rankTotalCabang'] }} cabang</div>
        </div>

        <div class="mt-3 small text-muted">
          Peringkat dihitung berdasarkan total realisasi closing yang cocok dengan nominatif pada filter aktif.
        </div>

        <div class="mt-3 d-flex flex-wrap gap-2">
          <span class="badge bg-dark rounded-pill px-3 py-2">
            {{ number_format($report['closingProspects']) }} closing prospek
          </span>
          <span class="badge bg-primary rounded-pill px-3 py-2">
            {{ number_format($report['unmatchedNoa']) }} belum match nominatif
          </span>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-8">
      <div class="report-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Top Cabang</div>
          <div class="panel-sub">Peringkat realisasi closing pada filter aktif.</div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table report-table mb-0">
              <thead>
                <tr>
                  <th>Rank</th>
                  <th>Cabang</th>
                  <th class="text-end">Realisasi</th>
                  <th class="text-end">NOA</th>
                </tr>
              </thead>
              <tbody>
                @forelse($report['topCabangRows'] as $row)
                  <tr>
                    <td class="fw-bold">#{{ $row['rank'] }}</td>
                    <td>{{ $row['kode_cabang'] }} - {{ $row['nama_cabang'] }}</td>
                    <td class="text-end fw-bold">{{ $money($row['realisasi']) }}</td>
                    <td class="text-end fw-bold">{{ number_format($row['noa']) }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4">
                      <div class="empty-state">Belum ada data ranking.</div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-12 col-xl-7">
      <div class="report-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Realisasi by Jenis Usaha</div>
          <div class="panel-sub">Grafik batang komposisi produk Kredit, Tabungan, dan Deposito per jenis usaha.</div>
        </div>
        <div class="panel-body">
          <div wire:ignore class="chart-box">
            <canvas id="jenisUsahaBarChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-5">
      <div class="report-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Komposisi Jenis Usaha</div>
          <div class="panel-sub">Donat berdasarkan total realisasi per jenis usaha.</div>
        </div>
        <div class="panel-body">
          <div wire:ignore class="chart-box chart-donut">
            <canvas id="jenisUsahaDonutChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="report-panel mb-3">
    <div class="panel-head">
      <div class="panel-title">Realisasi NOA by Jenis Usaha</div>
      <div class="panel-sub">Komposisi kredit, tabungan, dan deposito berdasarkan jenis usaha prospek.</div>
    </div>

    <div class="panel-body">
      <div class="table-responsive">
        <table class="table report-table align-middle mb-0">
          <thead>
            <tr>
              <th>Jenis Usaha</th>
              <th class="text-end"><span class="product-dot" style="background:{{ $summary['KREDIT']['color'] }}"></span>Kredit</th>
              <th class="text-end"><span class="product-dot" style="background:{{ $summary['TABUNGAN']['color'] }}"></span>Tabungan</th>
              <th class="text-end"><span class="product-dot" style="background:{{ $summary['DEPOSITO']['color'] }}"></span>Deposito</th>
              <th class="text-end">Total</th>
              <th class="text-end">NOA</th>
            </tr>
          </thead>
          <tbody>
            @forelse($report['jenisUsahaRows'] as $row)
              <tr>
                <td class="fw-bold">{{ $formatJenisUsaha($row['jenis_usaha']) }}</td>
                <td class="text-end">{{ $money($row['KREDIT']) }}</td>
                <td class="text-end">{{ $money($row['TABUNGAN']) }}</td>
                <td class="text-end">{{ $money($row['DEPOSITO']) }}</td>
                <td class="text-end fw-bold">{{ $money($row['total']) }}</td>
                <td class="text-end fw-bold">{{ number_format($row['noa']) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="empty-state">
                    Belum ada closing prospek yang cocok dengan nominatif pada filter ini.
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="report-panel unmatched-panel">
    <div class="panel-head">
      <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
        <div>
          <div class="panel-title">Data Belum Match Nominatif</div>
          <div class="panel-sub">Daftar prospek closing yang no rekeningnya belum ditemukan pada tabel nominatif sesuai jenis produk.</div>
        </div>

        <div class="unmatched-count">
          <i class="bi bi-exclamation-circle"></i>
          {{ number_format($report['unmatchedNoa']) }} belum match nominatif
        </div>
      </div>
    </div>

    <div class="panel-body">
      <div class="table-responsive">
        <table class="table report-table align-middle mb-0">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Cabang</th>
              <th>Produk</th>
              <th>No Rekening</th>
              <th>Jenis Usaha</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($report['unmatchedRows'] as $row)
              <tr>
                <td>{{ $tanggalView($row['tanggal_prospek']) }}</td>
                <td class="fw-bold">{{ $row['kode_cabang'] }} - {{ $row['nama_cabang'] }}</td>
                <td>
                  <span class="product-pill product-pill-{{ strtolower($row['jenis_produk']) }}">
                    {{ $row['jenis_produk_label'] }}
                  </span>
                </td>
                <td class="fw-bold">{{ $row['no_rekening'] }}</td>
                <td>{{ $formatJenisUsaha($row['jenis_usaha']) }}</td>
                <td class="text-muted">{{ $row['keterangan'] }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="empty-state">
                    Semua data closing pada filter ini sudah match dengan nominatif.
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script type="application/json" id="nominatif-bar-labels">@json($report['barChartLabels'])</script>
  <script type="application/json" id="nominatif-bar-datasets">@json($report['barChartDatasets'])</script>
  <script type="application/json" id="nominatif-donut-labels">@json($report['jenisUsahaDonutLabels'])</script>
  <script type="application/json" id="nominatif-donut-values">@json($report['jenisUsahaDonutValues'])</script>
  <script type="application/json" id="nominatif-donut-colors">@json($report['jenisUsahaDonutColors'])</script>

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      (function(){
        if (window.__nominatifReportBound) return;
        window.__nominatifReportBound = true;

        var jenisUsahaBarChart = null;
        var jenisUsahaDonutChart = null;

        function parseJson(id, fallback){
          var el = document.getElementById(id);
          if (!el) return fallback;

          try {
            return JSON.parse(el.textContent || 'null') || fallback;
          } catch(e) {
            return fallback;
          }
        }

        function rupiah(value){
          value = Number(value || 0);

          return 'Rp' + value.toLocaleString('id-ID', {
            maximumFractionDigits: 0
          });
        }

        function rupiahShort(value){
          value = Number(value || 0);
          var abs = Math.abs(value);

          if (abs >= 1000000000000) return 'Rp' + (value / 1000000000000).toFixed(1).replace('.0', '') + ' T';
          if (abs >= 1000000000) return 'Rp' + (value / 1000000000).toFixed(1).replace('.0', '') + ' M';
          if (abs >= 1000000) return 'Rp' + (value / 1000000).toFixed(1).replace('.0', '') + ' jt';
          if (abs >= 1000) return 'Rp' + (value / 1000).toFixed(1).replace('.0', '') + ' rb';

          return 'Rp' + value.toLocaleString('id-ID');
        }

        function renderNominatifCharts(){
          if (!window.Chart) return;

          renderBarChart();
          renderDonutChart();
        }

        function renderBarChart(){
          var canvas = document.getElementById('jenisUsahaBarChart');
          if (!canvas || !window.Chart) return;

          var labels = parseJson('nominatif-bar-labels', []);
          var datasets = parseJson('nominatif-bar-datasets', []);

          if (jenisUsahaBarChart && jenisUsahaBarChart.canvas !== canvas) {
            try { jenisUsahaBarChart.destroy(); } catch(e) {}
            jenisUsahaBarChart = null;
          }

          var config = {
            type: 'bar',
            data: {
              labels: labels,
              datasets: datasets
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              animation: false,
              interaction: {
                mode: 'index',
                intersect: false
              },
              plugins: {
                legend: {
                  position: 'top',
                  labels: {
                    boxWidth: 12,
                    usePointStyle: true,
                    font: {
                      weight: 'bold'
                    }
                  }
                },
                tooltip: {
                  callbacks: {
                    label: function(context){
                      return context.dataset.label + ': ' + rupiah(context.raw);
                    },
                    footer: function(items){
                      var total = items.reduce(function(sum, item){
                        return sum + Number(item.raw || 0);
                      }, 0);

                      return 'Total: ' + rupiah(total);
                    }
                  }
                }
              },
              scales: {
                x: {
                  stacked: true,
                  grid: {
                    display: false
                  },
                  ticks: {
                    color: '#334155',
                    font: {
                      weight: 'bold'
                    },
                    maxRotation: 0,
                    autoSkip: false
                  }
                },
                y: {
                  stacked: true,
                  beginAtZero: true,
                  ticks: {
                    color: '#64748b',
                    callback: function(value){
                      return rupiahShort(value);
                    }
                  },
                  grid: {
                    color: 'rgba(148,163,184,.22)'
                  }
                }
              }
            }
          };

          if (jenisUsahaBarChart) {
            jenisUsahaBarChart.data.labels = labels;
            jenisUsahaBarChart.data.datasets = datasets;
            jenisUsahaBarChart.update('none');
            return;
          }

          jenisUsahaBarChart = new Chart(canvas, config);
        }

        function renderDonutChart(){
          var canvas = document.getElementById('jenisUsahaDonutChart');
          if (!canvas || !window.Chart) return;

          var labels = parseJson('nominatif-donut-labels', []);
          var values = parseJson('nominatif-donut-values', []);
          var colors = parseJson('nominatif-donut-colors', []);

          if (jenisUsahaDonutChart && jenisUsahaDonutChart.canvas !== canvas) {
            try { jenisUsahaDonutChart.destroy(); } catch(e) {}
            jenisUsahaDonutChart = null;
          }

          var config = {
            type: 'doughnut',
            data: {
              labels: labels,
              datasets: [{
                data: values,
                backgroundColor: colors,
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 8
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              cutout: '58%',
              animation: false,
              plugins: {
                legend: {
                  position: 'bottom',
                  labels: {
                    boxWidth: 12,
                    usePointStyle: true,
                    font: {
                      weight: 'bold'
                    }
                  }
                },
                tooltip: {
                  callbacks: {
                    label: function(context){
                      var total = context.dataset.data.reduce(function(sum, item){
                        return sum + Number(item || 0);
                      }, 0);

                      var value = Number(context.raw || 0);
                      var percent = total > 0 ? ((value / total) * 100).toFixed(1) : '0.0';

                      return context.label + ': ' + rupiah(value) + ' (' + percent + '%)';
                    }
                  }
                }
              }
            }
          };

          if (jenisUsahaDonutChart) {
            jenisUsahaDonutChart.data.labels = labels;
            jenisUsahaDonutChart.data.datasets[0].data = values;
            jenisUsahaDonutChart.data.datasets[0].backgroundColor = colors;
            jenisUsahaDonutChart.update('none');
            return;
          }

          jenisUsahaDonutChart = new Chart(canvas, config);
        }

        function bindObserver(){
          var targets = document.querySelectorAll('script[id^="nominatif-"]');
          if (!targets.length || !window.MutationObserver) return;

          if (window.__nominatifChartObserver) {
            try { window.__nominatifChartObserver.disconnect(); } catch(e) {}
          }

          var observer = new MutationObserver(function(){
            setTimeout(renderNominatifCharts, 100);
          });

          targets.forEach(function(el){
            observer.observe(el, {
              childList: true,
              characterData: true,
              subtree: true
            });
          });

          window.__nominatifChartObserver = observer;
        }

        function bootCharts(){
          setTimeout(function(){
            bindObserver();
            renderNominatifCharts();
          }, 150);
        }

        document.addEventListener('DOMContentLoaded', bootCharts);
        document.addEventListener('livewire:navigated', bootCharts);
        document.addEventListener('nominatif-report-refresh', bootCharts);

        document.addEventListener('livewire:init', function(){
          try {
            Livewire.hook('commit', function(payload){
              if (payload && typeof payload.succeed === 'function') {
                payload.succeed(function(){
                  setTimeout(function(){
                    bindObserver();
                    renderNominatifCharts();
                  }, 180);
                });
              }
            });
          } catch(e) {}
        });

        window.addEventListener('load', bootCharts);
      })();
    </script>
  @endpush
</div>
