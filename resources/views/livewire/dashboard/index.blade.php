<div class="container-fluid px-4 py-3">
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
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
          <div>
            <div class="fw-bold">Peta Persebaran Pengajuan Jawa Tengah</div>
            <div class="small text-muted">
              Marker dibedakan warna berdasarkan jenis usaha.
            </div>
          </div>

          <div style="min-width:220px;">
            <label class="form-label small fw-semibold mb-1">Filter Status Map</label>
            <select class="form-select form-select-sm" wire:model.live="filterMapStatus">
              <option value="">-- Semua Status --</option>
              <option value="FOLLOW UP">FOLLOW UP</option>
              <option value="CLOSING">CLOSING</option>
              <option value="REJECTED">REJECTED</option>
            </select>
          </div>
        </div>

        <div wire:ignore>
            <div id="jatengMap" style="height:420px;border-radius:18px;overflow:hidden;"></div>
        </div>

        <div class="d-flex flex-wrap gap-3 mt-3 small">
          <span><span style="display:inline-block;width:12px;height:12px;border-radius:999px;background:#22c55e;"></span> Pertanian</span>
          <span><span style="display:inline-block;width:12px;height:12px;border-radius:999px;background:#3b82f6;"></span> Perdagangan</span>
          <span><span style="display:inline-block;width:12px;height:12px;border-radius:999px;background:#f59e0b;"></span> Jasa</span>
          <span><span style="display:inline-block;width:12px;height:12px;border-radius:999px;background:#ef4444;"></span> Peternakan</span>
          <span><span style="display:inline-block;width:12px;height:12px;border-radius:999px;background:#8b5cf6;"></span> Lainnya</span>
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

    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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

            function usahaColor(jenis) {
                const j = String(jenis || '').toUpperCase();
                if (j.includes('PERTANIAN')) return '#22c55e';
                if (j.includes('PERDAGANGAN')) return '#3b82f6';
                if (j.includes('JASA')) return '#f59e0b';
                if (j.includes('PETERNAKAN')) return '#ef4444';
                return '#8b5cf6';
            }

            function makeCircleIcon(color) {
                return L.divIcon({
                    className: '',
                    html: `<div style="width:14px;height:14px;border-radius:999px;background:${color};border:2px solid #fff;box-shadow:0 0 0 2px rgba(0,0,0,.15)"></div>`,
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });
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
                    mapItems: parseJsonScript('dashboard-data-map-items', [])
                };
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
                            data: data.usahaValues
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
                    zoomControl: true
                }).setView([-7.150975, 110.140259], 8);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(mapInstance);

                mapLayerGroup = L.layerGroup().addTo(mapInstance);

                const bounds = [];
                const mapItems = Array.isArray(data.mapItems) ? data.mapItems : [];

                mapItems.forEach(function(item) {
                    if (!item.lat || !item.lng) return;

                    const color = usahaColor(item.jenis_usaha);

                    let fotoHtml = '';
                    if (item.photo_url) {
                        fotoHtml = `
                            <div style="margin-top:8px">
                                <a href="${esc(item.photo_url)}" target="_blank">
                                    <img src="${esc(item.photo_url)}"
                                        style="width:100%;max-width:220px;height:140px;object-fit:cover;border-radius:10px;border:1px solid #e5e7eb;">
                                </a>
                            </div>
                        `;
                    }

                    const popupHtml = `
                        <div style="min-width:240px;max-width:270px;">
                            <div style="font-weight:700;font-size:14px;margin-bottom:6px;">${esc(item.nama || '-')}</div>
                            <div><b>Status:</b> ${esc(item.status || '-')}</div>
                            <div><b>Produk:</b> ${esc(item.jenis_produk || '-')}</div>
                            <div><b>Jenis Usaha:</b> ${esc(item.jenis_usaha || '-')}</div>
                            <div><b>KC:</b> ${esc(item.cabang || '-')}</div>
                            <div><b>Wilayah:</b> ${esc(item.kab_kota || '-')} , ${esc(item.kecamatan || '-')} , ${esc(item.desa || '-')}</div>
                            <div><b>Alamat:</b> ${esc(item.alamat || '-')}</div>
                            <div><b>Keterangan Usaha:</b> ${esc(item.keterangan_usaha || '-')}</div>
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
