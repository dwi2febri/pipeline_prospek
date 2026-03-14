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
        <select class="form-select" wire:model.live="filterCabang">
          <option value="">-- Semua Cabang --</option>
          @foreach($cabangs as $c)
            <option value="{{ $c->id }}">{{ $c->kode_cabang }} - {{ $c->nama_cabang }}</option>
          @endforeach
        </select>
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

        <div id="jatengMap" style="height:420px;border-radius:18px;overflow:hidden;"></div>

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
        <div class="fw-bold mb-2">Top Cabang</div>
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

  @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
      (function () {
        let chartClosingCabang = null;
        let chartProduk = null;
        let chartStatus = null;
        let chartUsaha = null;
        let chartTrend = null;
        let mapInstance = null;

        const closingLabels = @json($closingCabangLabels);
        const closingValues = @json($closingCabangValues);

        const produkLabels = @json($produkLabels);
        const produkValues = @json($produkValues);

        const statusLabels = @json($statusLabels);
        const statusValues = @json($statusValues);

        const usahaLabels = @json($usahaLabels);
        const usahaValues = @json($usahaValues);

        const trendLabels = @json($trendLabels);
        const trendValues = @json($trendValues);

        const mapItems = @json($mapItems);

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

        function renderCharts() {
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
              labels: closingLabels,
              datasets: [{
                label: 'Closing',
                data: closingValues,
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
              labels: produkLabels,
              datasets: [{
                data: produkValues
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
              labels: statusLabels,
              datasets: [{
                data: statusValues
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
              labels: usahaLabels,
              datasets: [{
                label: 'Jumlah',
                data: usahaValues
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
              labels: trendLabels,
              datasets: [{
                label: 'Pengajuan',
                data: trendValues,
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

        function renderMap() {
          const mapEl = document.getElementById('jatengMap');
          if (!mapEl) return;

          if (mapInstance) {
            mapInstance.remove();
            mapInstance = null;
          }

          mapInstance = L.map('jatengMap').setView([-7.150975, 110.140259], 8);

          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
          }).addTo(mapInstance);

          mapItems.forEach(item => {
            if (!item.lat || !item.lng) return;

            const color = usahaColor(item.jenis_usaha);

            L.marker([item.lat, item.lng], {
              icon: makeCircleIcon(color)
            }).addTo(mapInstance).bindPopup(`
              <div style="min-width:220px">
                <div><b>${item.nama ?? '-'}</b></div>
                <div>Jenis Usaha: ${item.jenis_usaha ?? '-'}</div>
                <div>Produk: ${item.jenis_produk ?? '-'}</div>
                <div>Status: ${item.status ?? '-'}</div>
                <div>Wilayah: ${(item.desa ?? '-')}, ${(item.kecamatan ?? '-')}, ${(item.kab_kota ?? '-')}</div>
              </div>
            `);
          });

          setTimeout(() => {
            mapInstance.invalidateSize();
          }, 200);
        }

        function initDashboard() {
          renderCharts();
          renderMap();
        }

        document.addEventListener('DOMContentLoaded', initDashboard);
        document.addEventListener('livewire:navigated', initDashboard);
      })();
    </script>
  @endpush
</div>

