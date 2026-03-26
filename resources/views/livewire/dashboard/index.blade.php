<div class="container-fluid px-0">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-3">Dashboard CRM Prospek</div>
      <div class="text-muted">Ringkasan prospek, closing, produk, jenis usaha, dan peta persebaran Jawa Tengah</div>
    </div>
  </div>

  <div class="card-soft p-3 mb-3">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md-4">
        <label class="form-label fw-semibold mb-1">Filter Cabang</label>
        <select class="form-select"
                wire:model.live="filterCabang"
                @if($lockCabangFilter) disabled @endif>
          <option value="">-- Semua Cabang --</option>
          @foreach($cabangs as $c)
            <option value="{{ $c->id }}">{{ $c->kode_cabang }} - {{ $c->nama_cabang }}</option>
          @endforeach
        </select>

        @if($lockCabangFilter)
          <div class="small text-muted mt-1">
            Filter cabang otomatis mengikuti cabang user supervisor.
          </div>
        @endif
      </div>

      <div class="col-12 col-md-8">
        <div class="small text-muted mt-md-4">
          Dashboard akan menyesuaikan grafik sesuai cabang yang dipilih.
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card-soft p-4 h-100">
        <div class="text-muted small">Total Pengajuan</div>
        <div class="fw-bold" style="font-size:2rem;">{{ number_format($summary['total']) }}</div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="card-soft p-4 h-100">
        <div class="text-muted small">Follow Up</div>
        <div class="fw-bold text-warning" style="font-size:2rem;">{{ number_format($summary['follow_up']) }}</div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="card-soft p-4 h-100">
        <div class="text-muted small">Rejected</div>
        <div class="fw-bold text-danger" style="font-size:2rem;">{{ number_format($summary['rejected']) }}</div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="card-soft p-4 h-100">
        <div class="text-muted small">Closing</div>
        <div class="fw-bold text-success" style="font-size:2rem;">{{ number_format($summary['closing']) }}</div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
      <div class="card-soft p-3">
        <div class="fw-bold mb-2">Closing per Cabang (ID 1 - 28)</div>
        <div style="position:relative;height:320px;">
          <canvas id="chartClosingCabang"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="card-soft p-3 h-100">
        <div class="fw-bold mb-2">Pengajuan per Rekomendasi Produk</div>
        <div style="position:relative;height:320px;">
          <canvas id="chartProduk"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-12 col-xl-4">
      <div class="card-soft p-3 h-100">
        <div class="fw-bold mb-2">Distribusi Status</div>
        <div style="position:relative;height:320px;">
          <canvas id="chartStatus"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="card-soft p-3 h-100">
        <div class="fw-bold mb-2">Top Jenis Usaha</div>
        <div style="position:relative;height:320px;">
          <canvas id="chartUsaha"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="card-soft p-3 h-100">
        <div class="fw-bold mb-2">Tren Pengajuan Bulanan</div>
        <div style="position:relative;height:320px;">
          <canvas id="chartTrend"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
      <div class="card-soft p-3">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
          <div>
            <div class="fw-bold fs-5 mb-1">Peta Persebaran Pengajuan Jawa Tengah</div>
            <div class="small text-muted">
              Warna marker mengikuti master <b>Jenis Usaha</b> dari database.
            </div>
          </div>

          <div style="min-width:240px;">
            <label class="form-label small fw-semibold mb-1">Filter Status Map</label>
            <select class="form-select form-select-sm" wire:model.live="filterMapStatus">
              <option value="">-- Semua Status --</option>
              <option value="FOLLOW UP">FOLLOW UP</option>
              <option value="CLOSING">CLOSING</option>
              <option value="REJECTED">REJECTED</option>
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

    <div class="col-12 col-xl-4">
      <div class="card-soft p-3 h-100">
        <div class="fw-bold mb-2">Top Cabang Pengajuan</div>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th>Cabang</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
              @forelse($topCabang as $r)
                <tr>
                  <td>{{ $r->kode_cabang }} - {{ $r->nama_cabang }}</td>
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

  <div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
      <div class="card-soft p-3 h-100">
        <div class="fw-bold mb-2">Top 5 Cabang Closing Terbanyak</div>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th>Cabang</th>
                <th class="text-end">Closing</th>
              </tr>
            </thead>
            <tbody>
              @forelse($topClosingCabang as $r)
                <tr>
                  <td>{{ $r->kode_cabang }} - {{ $r->nama_cabang }}</td>
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

    <div class="col-12 col-xl-6">
      <div class="card-soft p-3 h-100">
        <div class="fw-bold mb-2">Top 5 Pegawai / AO Berdasarkan Jumlah Pengajuan</div>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th>Username</th>
                <th>Nama</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
              @forelse($topPegawai as $r)
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

  <div class="card-soft p-3">
    <div class="fw-bold mb-2">Prospek Terbaru</div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
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
            <tr>
              <td>{{ $p->tanggal_prospek }}</td>
              <td>{{ $p->nama }}</td>
              <td>{{ $p->no_hp }}</td>
              <td>{{ $p->jenis_produk }}</td>
              <td>{{ $p->status }}</td>
              <td>{{ $p->cabang?->nama_cabang ?? '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <script type="application/json" id="dashboard-data-closing-labels">@json($closingCabangLabels)</script>
  <script type="application/json" id="dashboard-data-closing-values">@json($closingCabangValues)</script>
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

  @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
      .dashboard-map{
        height: 440px;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.35);
      }
      .map-panel{
        background: linear-gradient(180deg,#f8fafc 0%,#f1f5f9 100%);
        border-radius: 22px;
        padding: 10px;
        border: 1px solid #e5e7eb;
      }
      .legend-wrap{
        background:#f8fafc;
        border:1px solid #e5e7eb;
        border-radius:18px;
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
        font-size:.92rem;
        font-weight:500;
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
    </style>

    <script>
    (function () {
      if (window.__crmDashboardBound) return;
      window.__crmDashboardBound = true;

      let chartClosingCabang = null;
      let chartProduk = null;
      let chartStatus = null;
      let chartUsaha = null;
      let chartTrend = null;

      let mapInstance = null;
      let mapLayerGroup = null;

      function parseJsonScript(id, fallback) {
        const el = document.getElementById(id);
        if (!el) return fallback;
        try {
          return JSON.parse(el.textContent || 'null') ?? fallback;
        } catch (e) {
          return fallback;
        }
      }

      function destroyChart(chart) {
        if (chart) chart.destroy();
      }

      function esc(v) {
        return String(v ?? '')
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');
      }

      function getDashboardPayload() {
        return {
          closingLabels: parseJsonScript('dashboard-data-closing-labels', []),
          closingValues: parseJsonScript('dashboard-data-closing-values', []),
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
          html: `
            <div style="
              width:16px;
              height:16px;
              border-radius:999px;
              background:${color};
              border:2px solid #fff;
              box-shadow:0 0 0 2px rgba(15,23,42,.12), 0 4px 10px rgba(15,23,42,.18);
            "></div>
          `,
          iconSize: [16, 16],
          iconAnchor: [8, 8]
        });
      }

      function renderCharts() {
        const data = getDashboardPayload();

        const elClosing = document.getElementById('chartClosingCabang');
        const elProduk  = document.getElementById('chartProduk');
        const elStatus  = document.getElementById('chartStatus');
        const elUsaha   = document.getElementById('chartUsaha');
        const elTrend   = document.getElementById('chartTrend');

        if (!elClosing || !elProduk || !elStatus || !elUsaha || !elTrend) return;

        destroyChart(chartClosingCabang);
        destroyChart(chartProduk);
        destroyChart(chartStatus);
        destroyChart(chartUsaha);
        destroyChart(chartTrend);

        chartClosingCabang = new Chart(elClosing, {
          type: 'bar',
          data: {
            labels: data.closingLabels,
            datasets: [{
              label: 'Closing',
              data: data.closingValues,
              backgroundColor: '#93c5fd',
              borderColor: '#60a5fa',
              borderWidth: 1,
              barThickness: 18,
              maxBarThickness: 24
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
              y: {
                beginAtZero: true,
                ticks: { precision: 0 }
              },
              x: {
                ticks: {
                  autoSkip: false,
                  maxRotation: 90,
                  minRotation: 0
                }
              }
            }
          }
        });

        chartProduk = new Chart(elProduk, {
          type: 'doughnut',
          data: {
            labels: data.produkLabels,
            datasets: [{
              data: data.produkValues
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false
          }
        });

        chartStatus = new Chart(elStatus, {
          type: 'pie',
          data: {
            labels: data.statusLabels,
            datasets: [{
              data: data.statusValues
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false
          }
        });

        chartUsaha = new Chart(elUsaha, {
          type: 'bar',
          data: {
            labels: data.usahaLabels,
            datasets: [{
              label: 'Jumlah',
              data: data.usahaValues,
              backgroundColor: '#60a5fa'
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
              x: {
                beginAtZero: true,
                ticks: { precision: 0 }
              }
            }
          }
        });

        chartTrend = new Chart(elTrend, {
          type: 'line',
          data: {
            labels: data.trendLabels,
            datasets: [{
              label: 'Pengajuan',
              data: data.trendValues,
              fill: false,
              tension: 0.25,
              borderColor: '#3b82f6',
              backgroundColor: '#3b82f6'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
              y: {
                beginAtZero: true,
                ticks: { precision: 0 }
              }
            }
          }
        });
      }

      function destroyMap() {
        if (mapLayerGroup) {
          mapLayerGroup.clearLayers();
          mapLayerGroup = null;
        }

        if (mapInstance) {
          mapInstance.off();
          mapInstance.remove();
          mapInstance = null;
        }
      }

      function renderMap() {
        const data = getDashboardPayload();
        const mapEl = document.getElementById('jatengMap');
        if (!mapEl || typeof L === 'undefined') return;

        destroyMap();

        mapInstance = L.map(mapEl, {
          zoomControl: true,
          scrollWheelZoom: true
        }).setView([-7.150975, 110.140259], 8);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap'
        }).addTo(mapInstance);

        mapLayerGroup = L.layerGroup().addTo(mapInstance);

        const bounds = [];
        const mapItems = Array.isArray(data.mapItems) ? data.mapItems : [];

        mapItems.forEach(function(item) {
          if (!item.lat || !item.lng) return;

          const color = getUsahaColor(item.jenis_usaha_kode, data);

          let fotoHtml = '';
          if (item.photo_url) {
            fotoHtml = `
              <div>
                <a href="${esc(item.photo_url)}" target="_blank">
                  <img src="${esc(item.photo_url)}" class="map-popup-photo">
                </a>
              </div>
            `;
          }

          const popupHtml = `
            <div style="min-width:250px;max-width:290px;">
              <div class="map-popup-title">${esc(item.nama || '-')}</div>

              <div class="map-popup-row">
                <span class="map-popup-badge">${esc(item.status || '-')}</span>
                <span class="map-popup-badge">${esc(item.jenis_produk || '-')}</span>
              </div>

              <div class="map-popup-row"><b>Jenis Usaha:</b> ${esc(item.jenis_usaha_label || '-')}</div>
              <div class="map-popup-row"><b>KC:</b> ${esc(item.cabang || '-')}</div>
              <div class="map-popup-row"><b>Wilayah:</b> ${esc(item.kab_kota || '-')} , ${esc(item.kecamatan || '-')} , ${esc(item.desa || '-')}</div>
              <div class="map-popup-row"><b>Alamat:</b> ${esc(item.alamat || '-')}</div>
              <div class="map-popup-row"><b>Keterangan:</b> ${esc(item.keterangan_usaha || '-')}</div>

              ${fotoHtml}
            </div>
          `;

          const marker = L.marker([item.lat, item.lng], {
            icon: makeCircleIcon(color)
          }).bindPopup(popupHtml);

          mapLayerGroup.addLayer(marker);
          bounds.push([item.lat, item.lng]);
        });

        setTimeout(function () {
          if (!mapInstance) return;

          if (bounds.length > 0) {
            mapInstance.fitBounds(bounds, { padding: [20, 20] });
          } else {
            mapInstance.setView([-7.150975, 110.140259], 8);
          }

          mapInstance.invalidateSize();
        }, 250);
      }

      function renderAllDashboard() {
        renderCharts();
        renderMap();
      }

      document.addEventListener('DOMContentLoaded', function () {
        setTimeout(renderAllDashboard, 300);
      });

      document.addEventListener('livewire:navigated', function () {
        setTimeout(renderAllDashboard, 300);
      });

      document.addEventListener('livewire:init', function () {
        if (!window.Livewire) return;

        Livewire.on('dashboard-refresh', function () {
          setTimeout(function () {
            renderAllDashboard();
          }, 350);
        });
      });

      window.addEventListener('resize', function () {
        if (mapInstance) {
          setTimeout(function () {
            mapInstance.invalidateSize();
          }, 150);
        }
      });
    })();
    </script>
  @endpush
</div>
