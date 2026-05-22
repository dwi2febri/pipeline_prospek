<div class="container-fluid px-0">

  <style>
    .dash-title{
      font-size:2.2rem;
      font-weight:900;
      letter-spacing:-.03em;
      color:#1e293b;
      line-height:1.1;
    }

    .dash-subtitle{
      color:#64748b;
      font-size:1rem;
    }

    .dash-top-actions{
      display:flex;
      align-items:center;
      gap:10px;
      flex-wrap:wrap;
    }

    .desktop-ai-btn{
      display:inline-flex;
      align-items:center;
      gap:8px;
      border-radius:999px;
      padding:12px 18px;
      font-weight:800;
      text-decoration:none;
      color:#fff;
      background:linear-gradient(135deg,#7c3aed 0%,#2563eb 100%);
      box-shadow:0 16px 28px rgba(37,99,235,.24);
      border:0;
      white-space:nowrap;
      transition:all .18s ease;
    }

    .desktop-ai-btn:hover{
      color:#fff;
      transform:translateY(-1px);
    }

    .mobile-inline-actions{
      display:none;
    }

    .mobile-inline-ai{
      display:inline-flex;
      align-items:center;
      gap:8px;
      justify-content:center;
    }

    .mobile-fab-stack{
      position:fixed;
      right:16px;
      bottom:88px;
      z-index:1045;
      display:flex;
      flex-direction:column;
      gap:12px;
    }

    .mobile-fab-stack .mobile-fab-ai,
    .mobile-fab-stack .mobile-fab-dashboard-add{
      width:62px;
      height:62px;
      border-radius:999px;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#fff;
      text-decoration:none;
      box-shadow:0 18px 30px rgba(37,99,235,.28);
    }

    .mobile-fab-stack .mobile-fab-ai{
      background:linear-gradient(135deg,#7c3aed 0%,#2563eb 100%);
    }

    .mobile-fab-stack .mobile-fab-dashboard-add{
      background:linear-gradient(135deg,#14b8a6 0%,#3b82f6 100%);
    }

    .mobile-fab-stack .mobile-fab-ai i,
    .mobile-fab-stack .mobile-fab-dashboard-add i{
      font-size:1.45rem;
    }

    .dash-filter-card{
      border:1px solid #e9eef5;
      border-radius:26px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
      box-shadow:0 14px 34px rgba(15,23,42,.06);
    }

    .dash-stat-card{
      position:relative;
      overflow:hidden;
      border:0;
      border-radius:26px;
      padding:22px 22px 18px 22px;
      color:#fff;
      min-height:140px;
      box-shadow:0 18px 38px rgba(15,23,42,.12);
    }

    .dash-stat-card .label{
      font-size:.98rem;
      font-weight:700;
      opacity:.95;
      margin-bottom:8px;
    }

    .dash-stat-card .value{
      font-size:2.5rem;
      font-weight:900;
      line-height:1;
      letter-spacing:-.03em;
    }

    .dash-stat-card .icon{
      position:absolute;
      right:16px;
      bottom:10px;
      font-size:54px;
      opacity:.18;
    }

    .bg-total{ background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%); }
    .bg-open{ background:linear-gradient(135deg,#ffcfcf 0%,#fc9d9d 100%); color:#7f1d1d; }
    .bg-follow{ background:linear-gradient(135deg,#10b981 0%,#059669 100%); }
    .bg-rejected{ background:linear-gradient(135deg,#fb7185 0%,#e11d48 100%); }
    .bg-closing{ background:linear-gradient(135deg,#60a5fa 0%,#2563eb 100%); }

    .dash-panel{
      border:1px solid #e9eef5;
      border-radius:26px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
      box-shadow:0 14px 34px rgba(15,23,42,.06);
      overflow:hidden;
    }

    .dash-panel .panel-head{
      padding:18px 20px 0 20px;
    }

    .dash-panel .panel-title{
      font-size:1.12rem;
      font-weight:800;
      color:#1f2937;
      margin-bottom:4px;
    }

    .dash-panel .panel-sub{
      font-size:.88rem;
      color:#64748b;
    }

    .dash-panel .panel-body{
      padding:16px 20px 20px 20px;
    }

    .summary-note{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:10px 14px;
      border-radius:999px;
      background:#f8fafc;
      border:1px solid #e5e7eb;
      color:#64748b;
      font-size:.9rem;
      font-weight:600;
    }

    .modern-table thead th{
      border-bottom:1px solid #e9eef5 !important;
      background:#f8fafc !important;
      color:#334155;
      font-size:.9rem;
      font-weight:800;
      white-space:nowrap;
      vertical-align:middle;
    }

    .modern-table tbody td{
      border-color:#eef2f7 !important;
      vertical-align:middle;
    }

    .modern-table tbody tr:hover{
      background:#fbfdff;
    }

    .rank-badge{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      width:28px;
      height:28px;
      border-radius:999px;
      background:#e0ecff;
      color:#1d4ed8;
      font-size:.82rem;
      font-weight:800;
    }

    .status-chip{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:6px 12px;
      border-radius:999px;
      font-size:.75rem;
      font-weight:800;
      letter-spacing:.02em;
      white-space:nowrap;
    }

    .status-open{
      background:linear-gradient(180deg,#f8fafc 0%,#e2e8f0 100%);
      color:#475569;
      border:1px solid #cbd5e1;
    }

    .status-follow{
      background:linear-gradient(135deg,#fde68a 0%,#fbbf24 100%);
      color:#4b3a00;
    }

    .status-rejected{
      background:linear-gradient(135deg,#fda4af 0%,#f43f5e 100%);
      color:#fff;
    }

    .status-closing{
      background:linear-gradient(135deg,#86efac 0%,#22c55e 100%);
      color:#14532d;
    }

    .produk-chip{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:6px 12px;
      border-radius:999px;
      font-size:.75rem;
      font-weight:800;
      letter-spacing:.02em;
      white-space:nowrap;
      color:#fff;
    }

    .produk-kredit{ background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%); }
    .produk-tabungan{ background:linear-gradient(135deg,#22c55e 0%,#15803d 100%); }
    .produk-deposito{ background:linear-gradient(135deg,#facc15 0%,#eab308 100%); color:#4b3a00; }
    .produk-aset{ background:linear-gradient(135deg,#374151 0%,#111827 100%); }

    .dashboard-map{
      height:460px;
      border-radius:22px;
      overflow:hidden;
      border:1px solid #e5e7eb;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.35);
    }

    .map-panel{
      background:linear-gradient(180deg,#f8fafc 0%,#f1f5f9 100%);
      border-radius:24px;
      padding:12px;
      border:1px solid #e5e7eb;
    }

    .legend-wrap{
      background:#f8fafc;
      border:1px solid #e5e7eb;
      border-radius:20px;
      padding:14px 16px;
    }

    .legend-chip{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 12px;
      border-radius:999px;
      background:#fff;
      border:1px solid #e5e7eb;
      font-size:.9rem;
      font-weight:600;
      box-shadow:0 1px 2px rgba(15,23,42,.04);
    }

    .legend-dot{
      width:12px;
      height:12px;
      border-radius:999px;
      display:inline-block;
      flex:0 0 auto;
    }

    .leaflet-popup-content-wrapper{
      border-radius:16px;
    }

    .leaflet-popup-content{
      margin:14px 16px;
    }

    .map-popup-title{
      font-weight:700;
      font-size:14px;
      margin-bottom:8px;
      color:#0f172a;
    }

    .map-popup-row{
      margin-bottom:4px;
      font-size:13px;
      color:#334155;
    }

    .map-popup-badge{
      display:inline-block;
      padding:4px 10px;
      border-radius:999px;
      font-size:11px;
      font-weight:700;
      margin-top:4px;
      margin-right:4px;
      border:1px solid rgba(15,23,42,.08);
      background:#f8fafc;
    }

    .map-popup-photo{
      margin-top:10px;
      border-radius:12px;
      width:100%;
      max-width:220px;
      height:140px;
      object-fit:cover;
      border:1px solid #e5e7eb;
    }



    /* Smooth chart area saat filter Livewire berubah */
    .dashboard-chart-box{
      position:relative;
      width:100%;
      height:320px !important;
      min-height:320px !important;
      max-height:320px !important;
      overflow:hidden;
      transition:none !important;
    }

    .dashboard-chart-box canvas{
      display:block !important;
      width:100% !important;
      height:100% !important;
      max-width:100% !important;
      max-height:100% !important;
    }

    .dashboard-chart-box.is-updating{
      opacity:1 !important;
      filter:none !important;
    }

    @media (max-width: 767.98px){
      .dash-title{ font-size:1.7rem; }

      .desktop-ai-btn{
        display:none !important;
      }

      .mobile-inline-actions{
        display:flex;
        gap:10px;
        margin-top:12px;
        flex-wrap:wrap;
      }

      .mobile-inline-actions .mobile-inline-ai,
      .mobile-inline-actions .mobile-inline-add{
        flex:1 1 calc(50% - 5px);
        justify-content:center;
      }

      .dash-stat-card{
        min-height:124px;
        border-radius:22px;
      }

      .dash-stat-card .value{
        font-size:2.15rem;
      }

      .dashboard-map{
        height:360px;
      }
    }

    @media (min-width: 768px){
      .mobile-fab-stack,
      .mobile-inline-actions{
        display:none !important;
      }
    }
  </style>

  <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
    <div>
      <div class="dash-title">Dashboard CRM Prospek</div>
      <div class="dash-subtitle">Ringkasan prospek, status, produk, jenis usaha, dan peta persebaran Jawa Tengah</div>

      <div class="mobile-inline-actions">
        <a href="{{ route('ai.chat.index') }}" class="desktop-ai-btn mobile-inline-ai">
          <i class="bi bi-stars"></i> Chat AI
        </a>


      </div>
    </div>

    <div class="dash-top-actions d-none d-md-flex">
      <a href="{{ route('ai.chat.index') }}" class="desktop-ai-btn">
        <i class="bi bi-stars"></i> Chat AI
      </a>


    </div>
  </div>

  <div class="dash-filter-card p-3 mb-4">
    <div class="row g-3 align-items-end">
      <div class="col-12 col-md-3">
        <label class="form-label fw-semibold mb-1">Filter Cabang / Kanwil</label>
        <select class="form-select"
                wire:model.live="filterCabang"
                @if($lockCabangFilter) disabled @endif>
          <option value="">-- Semua Cabang --</option>
          @foreach($cabangs as $c)
            <option value="{{ in_array($c->kode_cabang, ['100','200','300','400']) ? $c->kode_cabang : $c->id }}">
              {{ $c->kode_cabang }} - {{ $c->nama_cabang }}
            </option>
          @endforeach
        </select>

        @if($lockCabangFilter)
          <div class="small text-muted mt-1">
            Filter cabang otomatis mengikuti cabang user supervisor.
          </div>
        @endif
      </div>

      <div class="col-12 col-md-2">
        <label class="form-label fw-semibold mb-1">Mode Tanggal</label>
        <select class="form-select" wire:model.live="filterDateMode">
          <option value="all">Semua Data</option>
          <option value="monthly">Bulanan</option>
          <option value="range">Range Tanggal</option>
        </select>
      </div>

      @if($filterDateMode === 'monthly')
        <div class="col-6 col-md-2">
          <label class="form-label fw-semibold mb-1">Bulan</label>
          <select class="form-select" wire:model.live="filterBulan">
            @foreach($bulanOptions as $b)
              <option value="{{ $b['id'] }}">{{ $b['label'] }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-semibold mb-1">Tahun</label>
          <select class="form-select" wire:model.live="filterTahun">
            @foreach($tahunOptions as $t)
              <option value="{{ $t }}">{{ $t }}</option>
            @endforeach
          </select>
        </div>
      @elseif($filterDateMode === 'range')
        <div class="col-6 col-md-2">
          <label class="form-label fw-semibold mb-1">Tanggal Awal</label>
          <input type="date" class="form-control" wire:model.live="filterTanggalAwal">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-semibold mb-1">Tanggal Akhir</label>
          <input type="date" class="form-control" wire:model.live="filterTanggalAkhir">
        </div>
      @endif

      <div class="col-12 col-md">
        <div class="summary-note">
          <i class="bi bi-info-circle"></i>
          Dashboard akan menyesuaikan seluruh rekap sesuai filter yang dipilih.
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl">
      <div class="dash-stat-card bg-total h-100">
        <div class="label">Total Pengajuan</div>
        <div class="value">{{ number_format($summary['total']) }}</div>
        <div class="icon"><i class="bi bi-collection"></i></div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
      <div class="dash-stat-card bg-open h-100">
        <div class="label">Open</div>
        <div class="value">{{ number_format($summary['open']) }}</div>
        <div class="icon"><i class="bi bi-folder2-open"></i></div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
      <div class="dash-stat-card bg-follow h-100">
        <div class="label">Follow Up</div>
        <div class="value">{{ number_format($summary['follow_up']) }}</div>
        <div class="icon"><i class="bi bi-arrow-repeat"></i></div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
      <div class="dash-stat-card bg-rejected h-100">
        <div class="label">Rejected</div>
        <div class="value">{{ number_format($summary['rejected']) }}</div>
        <div class="icon"><i class="bi bi-x-circle"></i></div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
      <div class="dash-stat-card bg-closing h-100">
        <div class="label">Closing</div>
        <div class="value">{{ number_format($summary['closing']) }}</div>
        <div class="icon"><i class="bi bi-check2-circle"></i></div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
      <div class="dash-panel h-100">
        <div class="panel-head d-flex flex-wrap align-items-start justify-content-between gap-3">
          <div>
            <div class="panel-title">{{ $grafikUtamaTitle }}</div>
            <div class="panel-sub">{{ $grafikUtamaSubtitle }}</div>
          </div>

          <div style="min-width:260px;">
            <label class="form-label small fw-semibold mb-1">Mode Grafik</label>
            <select class="form-select form-select-sm" wire:model.live="filterGrafikClosingMode">
              <option value="closing">Per KC (Closing)</option>
              <option value="pengaju">Per KC By Pengajuan</option>
              <option value="per_kc_non_closing_rejected">Per KC (Open + Follow Up)</option>
              <option value="per_kc_follow_up">Per KC (Follow Up)</option>
              <option value="per_kc_rejected">Per KC (Rejected)</option>
            </select>
          </div>
        </div>

        <div class="panel-body">
          <div wire:ignore class="dashboard-chart-box">
            <canvas id="chartClosingCabang"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="dash-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Pengajuan per Rekomendasi Produk</div>
          <div class="panel-sub">Komposisi produk yang paling banyak diajukan</div>
        </div>
        <div class="panel-body">
          <div wire:ignore class="dashboard-chart-box">
            <canvas id="chartProduk"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-xl-4">
      <div class="dash-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Distribusi Status</div>
          <div class="panel-sub">Mencakup OPEN, FOLLOW UP, REJECTED, dan CLOSING</div>
        </div>
        <div class="panel-body">
          <div wire:ignore class="dashboard-chart-box">
            <canvas id="chartStatus"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="dash-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Top Jenis Usaha</div>
          <div class="panel-sub">Jenis usaha yang paling dominan</div>
        </div>
        <div class="panel-body">
          <div wire:ignore class="dashboard-chart-box">
            <canvas id="chartUsaha"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="dash-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Tren Pengajuan Bulanan</div>
          <div class="panel-sub">Pergerakan jumlah input prospek per bulan</div>
        </div>
        <div class="panel-body">
          <div wire:ignore class="dashboard-chart-box">
            <canvas id="chartTrend"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
      <div class="dash-panel">
        <div class="panel-head">
          <div class="panel-title">Peta Persebaran Pengajuan Jawa Tengah</div>
          <div class="panel-sub">Warna marker mengikuti master jenis usaha dari database</div>
        </div>

        <div class="panel-body">
          <div class="row g-2 mb-3">
            <div class="col-12 col-md-4">
              <label class="form-label small fw-semibold mb-1">Filter Status Map</label>
              <select class="form-select form-select-sm" wire:model.live="filterMapStatus">
                <option value="">-- Semua Status --</option>
                <option value="OPEN">OPEN</option>
                <option value="FOLLOW UP">FOLLOW UP</option>
                <option value="CLOSING">CLOSING</option>
                <option value="REJECTED">REJECTED</option>
              </select>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label small fw-semibold mb-1">Filter Jenis Usaha</label>
              <select class="form-select form-select-sm" wire:model.live="filterMapJenisUsaha">
                <option value="">-- Semua Jenis Usaha --</option>
                @foreach($mapJenisUsahaOptions as $opt)
                  <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label small fw-semibold mb-1">Filter Rekomendasi Produk</label>
              <select class="form-select form-select-sm" wire:model.live="filterMapProduk">
                <option value="">-- Semua Produk --</option>
                @foreach($mapProdukOptions as $opt)
                  <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="map-panel mb-3">
            <div wire:ignore>
              <div id="jatengMap" class="dashboard-map"></div>
            </div>
          </div>

          <div class="legend-wrap">
            <div class="small fw-semibold text-secondary mb-2">Legend Jenis Usaha</div>
            <div class="d-flex flex-wrap gap-2">
              @forelse($legendUsaha as $lg)
                <div class="legend-chip">
                  <span class="legend-dot" style="background:{{ $lg['color'] }};"></span>
                  <span>{{ $lg['nama'] }}</span>
                </div>
              @empty
                <div class="text-muted small">Belum ada legend jenis usaha.</div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="dash-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Top Cabang Pengajuan</div>
          <div class="panel-sub">5 cabang dengan pengajuan terbanyak</div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table modern-table align-middle mb-0">
              <thead>
                <tr>
                  <th>Cabang</th>
                  <th class="text-end">Total</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topCabang as $i => $r)
                  <tr>
                    <td>
                      <span class="rank-badge me-2">{{ $i + 1 }}</span>
                      {{ $r->kode_cabang }} - {{ $r->nama_cabang }}
                    </td>
                    <td class="text-end fw-bold">{{ number_format($r->total) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="2" class="text-center text-muted">Belum ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-xl-6">
      <div class="dash-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Top 5 Cabang Closing Terbanyak</div>
          <div class="panel-sub">Cabang dengan jumlah closing tertinggi</div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table modern-table align-middle mb-0">
              <thead>
                <tr>
                  <th>Cabang</th>
                  <th class="text-end">Closing</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topClosingCabang as $i => $r)
                  <tr>
                    <td>
                      <span class="rank-badge me-2">{{ $i + 1 }}</span>
                      {{ $r->kode_cabang }} - {{ $r->nama_cabang }}
                    </td>
                    <td class="text-end fw-bold text-success">{{ number_format($r->total) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="2" class="text-center text-muted">Belum ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-6">
      <div class="dash-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Top 5 Pegawai / AO Berdasarkan Jumlah Pengajuan</div>
          <div class="panel-sub">Pegawai paling aktif menginput prospek</div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table modern-table align-middle mb-0">
              <thead>
                <tr>
                  <th>Username</th>
                  <th>Nama</th>
                  <th class="text-end">Total</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topPegawai as $i => $r)
                  <tr>
                    <td>{{ $r->name }}</td>
                    <td>{{ $r->nama_lengkap ?: '-' }}</td>
                    <td class="text-end fw-bold">{{ number_format($r->total) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="3" class="text-center text-muted">Belum ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="dash-panel">
    <div class="panel-head">
      <div class="panel-title">Prospek Terbaru</div>
      <div class="panel-sub">10 data prospek terbaru</div>
    </div>
    <div class="panel-body">
      <div class="table-responsive">
        <table class="table modern-table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Nama</th>
              <th>No HP</th>
              <th>Produk</th>
              <th>Status</th>
              <th>Cabang</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recent as $p)
              @php
                $statusClass = 'status-open';
                if($p->status === 'FOLLOW UP') $statusClass = 'status-follow';
                elseif($p->status === 'REJECTED') $statusClass = 'status-rejected';
                elseif($p->status === 'CLOSING') $statusClass = 'status-closing';

                $produkClass = 'produk-kredit';
                if($p->jenis_produk === 'TABUNGAN') $produkClass = 'produk-tabungan';
                elseif($p->jenis_produk === 'DEPOSITO') $produkClass = 'produk-deposito';
                elseif($p->jenis_produk === 'ASET') $produkClass = 'produk-aset';
              @endphp
              <tr>
                <td>{{ $p->tanggal_prospek }}</td>
                <td class="fw-semibold">{{ $p->nama }}</td>
                <td>{{ $p->no_hp }}</td>
                <td>
                  <span class="produk-chip {{ $produkClass }}">{{ $p->jenis_produk }}</span>
                </td>
                <td>
                  <span class="status-chip {{ $statusClass }}">{{ $p->status }}</span>
                </td>
                <td>{{ $p->cabang?->nama_cabang ?? '-' }}</td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script type="application/json" id="dashboard-data-closing-labels">@json($closingCabangLabels)</script>
  <script type="application/json" id="dashboard-data-closing-values">@json($closingCabangValues)</script>
  <script type="application/json" id="dashboard-data-closing-dataset">@json($grafikUtamaDataset)</script>
  <script type="application/json" id="dashboard-data-produk-labels">@json($produkLabels)</script>
  <script type="application/json" id="dashboard-data-produk-values">@json($produkValues)</script>
  <script type="application/json" id="dashboard-data-status-labels">@json($statusLabels)</script>
  <script type="application/json" id="dashboard-data-status-values">@json($statusValues)</script>
  <script type="application/json" id="dashboard-data-usaha-labels">@json($usahaLabels)</script>
  <script type="application/json" id="dashboard-data-usaha-values">@json($usahaValues)</script>
  <script type="application/json" id="dashboard-data-trend-labels">@json($trendLabels)</script>
  <script type="application/json" id="dashboard-data-trend-values">@json($trendValues)</script>
  <script type="application/json" id="dashboard-data-map-items">@json($mapItems)</script>
  <script type="application/json" id="dashboard-data-usaha-color-map">@json($usahaColorMap)</script>

  <div class="mobile-fab-stack d-md-none">
    <a href="{{ route('ai.chat.index') }}" class="mobile-fab-ai" aria-label="Chat AI">
      <i class="bi bi-stars"></i>
    </a>


  </div>

  @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
    (function () {
      if (window.__crmDashboardSmoothBound) return;
      window.__crmDashboardSmoothBound = true;

      let chartClosingCabang = null;
      let chartProduk = null;
      let chartStatus = null;
      let chartUsaha = null;
      let chartTrend = null;
      let mapInstance = null;
      let mapLayerGroup = null;
      let renderTimer = null;

      function parseJsonScript(id, fallback) {
        const el = document.getElementById(id);
        if (!el) return fallback;
        try {
          return JSON.parse(el.textContent || 'null') ?? fallback;
        } catch (e) {
          return fallback;
        }
      }

      function esc(v) {
        return String(v ?? '')
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');
      }


      function pick(item, keys, fallback = '-') {
        if (!item) return fallback;

        for (const key of keys) {
          const parts = String(key).split('.');
          let val = item;

          for (const part of parts) {
            if (val && Object.prototype.hasOwnProperty.call(val, part)) {
              val = val[part];
            } else {
              val = undefined;
              break;
            }
          }

          if (val !== undefined && val !== null && String(val).trim() !== '') {
            return val;
          }
        }

        return fallback;
      }

      function getDashboardPayload() {
        return {
          closingLabels: parseJsonScript('dashboard-data-closing-labels', []),
          closingValues: parseJsonScript('dashboard-data-closing-values', []),
          closingDataset: parseJsonScript('dashboard-data-closing-dataset', 'Closing'),
          produkLabels: parseJsonScript('dashboard-data-produk-labels', []),
          produkValues: parseJsonScript('dashboard-data-produk-values', []),
          statusLabels: parseJsonScript('dashboard-data-status-labels', []),
          statusValues: parseJsonScript('dashboard-data-status-values', []),
          usahaLabels: parseJsonScript('dashboard-data-usaha-labels', []),
          usahaValues: parseJsonScript('dashboard-data-usaha-values', []),
          trendLabels: parseJsonScript('dashboard-data-trend-labels', []),
          trendValues: parseJsonScript('dashboard-data-trend-values', []),
          mapItems: parseJsonScript('dashboard-data-map-items', []),
          usahaColorMap: parseJsonScript('dashboard-data-usaha-color-map', {})
        };
      }

      function getUsahaColor(kode, payload) {
        const map = (payload && payload.usahaColorMap) ? payload.usahaColorMap : {};
        return map[String(kode || '').toUpperCase()] || '#94a3b8';
      }

      function makeCircleIcon(color) {
        return L.divIcon({
          className: '',
          html: '<div style="width:16px;height:16px;border-radius:999px;background:' + color + ';border:2px solid #fff;box-shadow:0 0 0 2px rgba(15,23,42,.12), 0 4px 10px rgba(15,23,42,.18);"></div>',
          iconSize: [16, 16],
          iconAnchor: [8, 8]
        });
      }

      function setChartLoading(on) {
        // No opacity/fade supaya filter tidak terlihat glitch/kedip.
      }

      function forceCanvasSize(canvas) {
        if (!canvas) return;
        var box = canvas.closest('.dashboard-chart-box');
        if (!box) return;

        box.style.setProperty('height', '320px', 'important');
        box.style.setProperty('min-height', '320px', 'important');
        box.style.setProperty('max-height', '320px', 'important');

        canvas.style.setProperty('width', '100%', 'important');
        canvas.style.setProperty('height', '100%', 'important');
        canvas.style.setProperty('display', 'block', 'important');
      }

      function refreshChartSize(chart, canvas) {
        forceCanvasSize(canvas);
        if (!chart) return;
        setTimeout(function(){ try { chart.resize(); chart.update('none'); } catch(e){} }, 30);
        setTimeout(function(){ try { chart.resize(); chart.update('none'); } catch(e){} }, 180);
      }

      function upsertChart(current, canvas, config) {
        if (!canvas || !window.Chart) return current;

        forceCanvasSize(canvas);

        if (current) {
          current.data.labels = config.data.labels || [];
          current.data.datasets = config.data.datasets || [];
          current.options = config.options;
          current.update('none');
          refreshChartSize(current, canvas);
          return current;
        }

        var chart = new Chart(canvas, config);
        refreshChartSize(chart, canvas);
        return chart;
      }

      function renderCharts() {
        const data = getDashboardPayload();

        const elClosing = document.getElementById('chartClosingCabang');
        const elProduk  = document.getElementById('chartProduk');
        const elStatus  = document.getElementById('chartStatus');
        const elUsaha   = document.getElementById('chartUsaha');
        const elTrend   = document.getElementById('chartTrend');

        if (!elClosing || !elProduk || !elStatus || !elUsaha || !elTrend || !window.Chart) return;

        chartClosingCabang = upsertChart(chartClosingCabang, elClosing, {
          type: 'bar',
          data: {
            labels: data.closingLabels,
            datasets: [{
              label: data.closingDataset,
              data: data.closingValues,
              backgroundColor: '#93c5fd',
              borderColor: '#60a5fa',
              borderWidth: 1,
              borderRadius: 12,
              barThickness: 18,
              maxBarThickness: 24
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: { legend: { display: true } },
            scales: {
              y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(148,163,184,.18)' } },
              x: { ticks: { autoSkip: false, maxRotation: 90, minRotation: 0 }, grid: { display: false } }
            }
          }
        });

        chartProduk = upsertChart(chartProduk, elProduk, {
          type: 'doughnut',
          data: {
            labels: data.produkLabels,
            datasets: [{
              data: data.produkValues,
              backgroundColor: ['#38bdf8','#fb7185','#fb923c','#facc15','#34d399','#818cf8']
            }]
          },
          options: { responsive: true, maintainAspectRatio: false, animation: false, cutout: '55%' }
        });

        chartStatus = upsertChart(chartStatus, elStatus, {
          type: 'pie',
          data: {
            labels: data.statusLabels,
            datasets: [{
              data: data.statusValues,
              backgroundColor: ['#cbd5e1','#fbbf24','#f43f5e','#22c55e']
            }]
          },
          options: { responsive: true, maintainAspectRatio: false, animation: false }
        });

        chartUsaha = upsertChart(chartUsaha, elUsaha, {
          type: 'bar',
          data: {
            labels: data.usahaLabels,
            datasets: [{ label: 'Jumlah', data: data.usahaValues, backgroundColor: '#60a5fa', borderRadius: 10 }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
              x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(148,163,184,.18)' } },
              y: { grid: { display: false } }
            }
          }
        });

        chartTrend = upsertChart(chartTrend, elTrend, {
          type: 'line',
          data: {
            labels: data.trendLabels,
            datasets: [{
              label: 'Pengajuan',
              data: data.trendValues,
              borderColor: '#2563eb',
              backgroundColor: 'rgba(37,99,235,.15)',
              fill: true,
              tension: .35,
              pointRadius: 3,
              pointBackgroundColor: '#2563eb'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
              y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(148,163,184,.18)' } },
              x: { grid: { display: false } }
            }
          }
        });
      }

      function renderMap() {
        const payload = getDashboardPayload();
        const items = payload.mapItems || [];
        const mapEl = document.getElementById('jatengMap');
        if (!mapEl || !window.L) return;

        if (!mapInstance) {
          mapInstance = L.map('jatengMap').setView([-7.150975, 110.140259], 8);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
          }).addTo(mapInstance);

          mapLayerGroup = L.layerGroup().addTo(mapInstance);
        }

        mapLayerGroup.clearLayers();

        const bounds = [];

        items.forEach(item => {
          const lat = parseFloat(item.latitude ?? item.lat ?? 0);
          const lng = parseFloat(item.longitude ?? item.lng ?? 0);

          if (!lat || !lng) return;

          const color = getUsahaColor(item.kode_jenis_usaha || item.jenis_usaha_kode || item.jenis_usaha, payload);
          const marker = L.marker([lat, lng], { icon: makeCircleIcon(color) });

          const nama = pick(item, ['nama', 'nama_prospek', 'prospek', 'name'], '-');
          const cabang = pick(item, [
            'cabang',
            'nama_cabang',
            'cabang_nama',
            'branch_name',
            'cabang.nama_cabang',
            'cabang.nama',
            'branch.nama_cabang'
          ], '-');
          const noHp = pick(item, ['no_hp', 'hp', 'phone', 'telepon', 'no_telp', 'noTelp', 'noHandphone'], '-');
          const status = pick(item, ['status'], '-');
          const produk = pick(item, ['jenis_produk', 'produk', 'rekomendasi_produk'], '-');
          const usaha = pick(item, [
            'jenis_usaha',
            'usaha',
            'nama_jenis_usaha',
            'keterangan_usaha',
            'jenisUsaha.nama',
            'jenis_usaha_nama'
          ], '-');
          const alamat = pick(item, ['alamat', 'address'], '');
          const wilayah = [
            pick(item, ['desa'], ''),
            pick(item, ['kecamatan'], ''),
            pick(item, ['kab_kota', 'kabupaten', 'kota'], '')
          ].filter(v => String(v || '').trim() !== '').join(', ');
          const fotoUrl = pick(item, ['foto_url', 'photo_url', 'file_url', 'image_url', 'foto', 'photo'], '');

          const foto = fotoUrl
            ? '<img class="map-popup-photo" src="' + esc(fotoUrl) + '" alt="Foto">'
            : '';

          const alamatHtml = alamat
            ? '<div class="map-popup-row"><strong>Alamat:</strong> ' + esc(alamat) + '</div>'
            : '';

          const wilayahHtml = wilayah
            ? '<div class="map-popup-row"><strong>Wilayah:</strong> ' + esc(wilayah) + '</div>'
            : '';

          const popupHtml =
            '<div class="map-popup-title">' + esc(nama) + '</div>' +
            '<div class="map-popup-row"><strong>Cabang:</strong> ' + esc(cabang) + '</div>' +
            '<div class="map-popup-row"><strong>No HP:</strong> ' + esc(noHp) + '</div>' +
            '<div class="map-popup-row"><strong>Status:</strong> ' + esc(status) + '</div>' +
            '<div class="map-popup-row"><strong>Produk:</strong> ' + esc(produk) + '</div>' +
            '<div class="map-popup-row"><strong>Usaha:</strong> ' + esc(usaha) + '</div>' +
            alamatHtml +
            wilayahHtml +
            '<div class="map-popup-badge">' + esc(status) + '</div>' +
            '<div class="map-popup-badge">' + esc(produk) + '</div>' +
            foto;

          marker.bindPopup(popupHtml);
          marker.addTo(mapLayerGroup);
          bounds.push([lat, lng]);
        });

        if (bounds.length > 0) {
          mapInstance.fitBounds(bounds, { padding: [30, 30] });
        } else {
          mapInstance.setView([-7.150975, 110.140259], 8);
        }

        setTimeout(() => {
          if (mapInstance) mapInstance.invalidateSize();
        }, 120);
      }

      function renderAll() {
        clearTimeout(renderTimer);
        renderTimer = setTimeout(function(){
          requestAnimationFrame(function(){
            renderCharts();
            renderMap();
          });
        }, 60);
      }

      function renderAllStable() {
        renderAll();
        setTimeout(renderAll, 180);
        setTimeout(renderAll, 420);
      }

      function watchDashboardJson() {
        var targets = document.querySelectorAll('script[id^="dashboard-data-"]');
        if (!targets.length || !window.MutationObserver) return;

        if (window.__crmDashboardJsonObserver) {
          try { window.__crmDashboardJsonObserver.disconnect(); } catch(e){}
        }

        var observer = new MutationObserver(function(){
          renderAllStable();
        });

        targets.forEach(function(el){
          observer.observe(el, { childList:true, characterData:true, subtree:true });
        });

        window.__crmDashboardJsonObserver = observer;
      }

      document.addEventListener('dashboard:smooth-refresh', function(){
        setTimeout(function(){ watchDashboardJson(); renderAllStable(); }, 120);
      });

      document.addEventListener('livewire:navigated', function () {
        setTimeout(function(){ watchDashboardJson(); renderAllStable(); }, 120);
      });

      document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function(){ watchDashboardJson(); renderAllStable(); }, 120);
      });

      window.addEventListener('resize', function(){ setTimeout(renderAllStable, 120); });
      window.addEventListener('load', function(){ setTimeout(function(){ watchDashboardJson(); renderAllStable(); }, 180); });

      document.addEventListener('livewire:init', function(){
        try{
          Livewire.hook('commit', function(payload){
            if(payload && typeof payload.succeed === 'function'){
              payload.succeed(function(){
                setTimeout(function(){ watchDashboardJson(); renderAllStable(); }, 180);
                setTimeout(renderAllStable, 520);
              });
            }else{
              setTimeout(renderAllStable, 220);
            }
          });
        }catch(e){}

        try{ Livewire.hook('morph.updated', function(){ setTimeout(renderAllStable, 180); }); }catch(e){}
        try{ Livewire.hook('message.processed', function(){ setTimeout(renderAllStable, 180); }); }catch(e){}
      });
    })();
    </script>
  @endpush
</div>
