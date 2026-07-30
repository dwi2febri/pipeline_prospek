<div class="container-fluid px-0">
  <style>
    .sim-title{
      font-size:1.65rem;
      font-weight:900;
      color:#0f172a;
      letter-spacing:-.02em;
    }
    .sim-subtitle{
      color:#64748b;
      font-size:.95rem;
    }
    .sim-card{
      border:0;
      border-radius:24px;
      background:#fff;
      box-shadow:0 16px 40px rgba(15,23,42,.08);
      overflow:hidden;
    }
    .sim-panel{
      border:1px solid #edf2f7;
      border-radius:18px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
    }
    .sim-summary{
      border-left:4px solid #0d6efd;
      border-radius:16px;
      background:#fff;
      box-shadow:0 10px 24px rgba(15,23,42,.06);
      min-height:100%;
    }
    .sim-summary .label{
      color:#64748b;
      font-size:.86rem;
      font-weight:800;
      margin-bottom:6px;
    }
    .sim-summary .value{
      color:#0f172a;
      font-size:1.15rem;
      font-weight:900;
      line-height:1.2;
    }
    .sim-note{
      border-radius:16px;
      padding:12px 14px;
      background:#fff7ed;
      color:#9a3412;
      border:1px solid #fed7aa;
      font-size:.9rem;
    }
    .sim-table{
      font-size:13px;
    }
    .sim-table thead th{
      background:#f8fafc !important;
      color:#334155;
      font-weight:800;
      white-space:nowrap;
    }
    .sim-print-head{
      display:none;
    }
    .sim-currency,
    .sim-amount{
      display:inline;
    }
    @media (max-width:767.98px){
      .sim-title{
        font-size:1.24rem;
      }
      .sim-subtitle{
        font-size:.64rem;
        line-height:1.45;
      }
      .sim-card{
        width:100%;
        padding:10px !important;
        border-radius:20px;
      }
      .sim-panel{
        padding:11px !important;
        border-radius:16px;
      }
      .sim-panel .row{
        --bs-gutter-x:10px;
        --bs-gutter-y:10px;
      }
      .sim-panel .form-label{
        margin-bottom:5px;
        font-size:.67rem;
      }
      .sim-panel .form-control,
      .sim-panel .form-select,
      .sim-panel .searchable-filter-trigger{
        min-height:46px !important;
        border-radius:14px !important;
        font-size:.68rem !important;
      }
      .sim-panel .btn{
        min-height:38px;
        padding:8px 13px;
        border-radius:12px;
        font-size:.65rem;
      }
      .sim-summary-grid{
        --bs-gutter-x:6px;
        --bs-gutter-y:6px;
        margin-bottom:10px !important;
      }
      .sim-summary-grid > [class*="col-"]{
        width:33.333333%;
        flex:0 0 33.333333%;
      }
      .sim-summary{
        min-height:70px;
        padding:8px !important;
        border-left-width:3px;
        border-radius:12px;
      }
      .sim-summary .label{
        margin-bottom:4px;
        font-size:.5rem;
        line-height:1.25;
      }
      .sim-summary .value{
        font-size:.58rem;
        line-height:1.35;
        overflow-wrap:anywhere;
      }
      .sim-table-wrap{
        width:100%;
        max-width:100%;
        overflow:hidden;
        border:1px solid #e2e8f0;
        border-radius:12px;
        background:#fff;
      }
      .sim-table{
        width:100% !important;
        min-width:0 !important;
        table-layout:fixed;
        margin:0 !important;
        font-size:7.5px;
        line-height:1.25;
      }
      .sim-table th,
      .sim-table td{
        height:auto;
        padding:7px 2px !important;
        overflow:hidden;
        vertical-align:middle;
        text-align:center !important;
        text-overflow:clip;
        white-space:nowrap;
      }
      .sim-table th{
        font-size:7.2px;
        letter-spacing:-.01em;
      }
      .sim-table th:nth-child(1),
      .sim-table td:nth-child(1){
        width:11%;
      }
      .sim-table th:nth-child(n+2),
      .sim-table td:nth-child(n+2){
        width:22.25%;
      }
      .sim-currency{
        display:none;
      }
      .sim-amount{
        display:inline;
      }
    }
    @media print{
      body{
        background:#fff !important;
        zoom:100% !important;
        width:100% !important;
        height:auto !important;
        overflow:visible !important;
      }
      .sidebar,
      .header,
      .bottom-nav,
      .sim-no-print{
        display:none !important;
      }
      .app-shell,
      .main,
      .main-scroll,
      .page-wrap{
        display:block !important;
        width:100% !important;
        height:auto !important;
        overflow:visible !important;
        padding:0 !important;
        margin:0 !important;
      }
      .sim-card{
        box-shadow:none !important;
        border-radius:0 !important;
      }
      .sim-print-head{
        display:block;
      }
    }
  </style>

  <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3 sim-no-print">
    <div>
      <div class="sim-title">Kalkulator Kredit BKK</div>
      <div class="sim-subtitle">Simulasi angsuran flat atau anuitas beserta jadwal pembayaran bulanan.</div>
    </div>
  </div>

  <div class="sim-card p-4">
    <div class="sim-print-head mb-4">
      <h3 class="mb-1">Kalkulator Kredit BKK</h3>
      <div>Simulasi Kredit BKK</div>
    </div>

    <div class="sim-panel p-3 mb-3 sim-no-print">
      <div class="row g-3">
        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">Produk</label>
          <select class="form-select"
                  wire:model.live="produk"
                  data-searchable-filter
                  data-search-placeholder="Cari produk...">
            @foreach($produkOptions as $value => $label)
              <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
          </select>
        </div>

        @if($produk === 'makaryo')
          <div class="col-12 col-md-4">
            <label class="form-label fw-semibold">Status Pegawai</label>
            <select class="form-select"
                    wire:model.live="pegawai"
                    data-searchable-filter
                    data-search-placeholder="Cari status pegawai...">
              <option value="internal">Pegawai Internal</option>
              <option value="eksternal">Pegawai Eksternal</option>
            </select>
          </div>
        @endif

        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">Metode</label>
          <select class="form-select"
                  wire:model.live="metode"
                  data-searchable-filter
                  data-search-placeholder="Cari metode...">
            <option value="flat">Flat</option>
            <option value="anuitas">Anuitas</option>
          </select>
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">Pinjaman</label>
          <input type="hidden" id="pinjaman_hidden" wire:model.live="pinjaman">
          <input
            type="text"
            class="form-control"
            id="pinjaman_display"
            inputmode="numeric"
            autocomplete="off"
            placeholder="100.000.000"
            value="{{ $pinjaman ? number_format((float) preg_replace('/[^0-9]/', '', $pinjaman), 0, ',', '.') : '' }}"
            wire:ignore
          >
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">Tenor (bulan)</label>
          <input type="number" class="form-control" wire:model.live.debounce.400ms="tenor" min="1">
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">Bunga (%)</label>
          <input type="number" class="form-control" value="{{ $bunga }}" readonly>
        </div>
      </div>

      @if($catatan)
        <div class="sim-note mt-3">{{ $catatan }}</div>
      @endif

      <div class="d-flex flex-wrap gap-2 mt-3">
        <button type="button" class="btn btn-primary" wire:click="hitung">
          <i class="bi bi-calculator me-1"></i> Hitung
        </button>
        <button type="button" class="btn btn-secondary" onclick="window.print()">
          <i class="bi bi-printer me-1"></i> Cetak
        </button>
        <button type="button" class="btn btn-success" onclick="exportSimulasiKreditExcel()">
          <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
        </button>
      </div>
    </div>

    <div id="simulasi-kredit-export-area">
      <table class="d-none" id="simulasi-kredit-meta">
        <tbody>
          <tr><td>Produk</td><td>{{ $produkOptions[$produk] ?? '-' }}</td></tr>
          <tr><td>Plafon</td><td>{{ $pinjaman }}</td></tr>
          <tr><td>Metode</td><td>{{ strtoupper($metode) }}</td></tr>
          <tr><td>Bunga (%)</td><td>{{ $bunga }}</td></tr>
          <tr><td>Tenor (Bulan)</td><td>{{ $tenor }}</td></tr>
        </tbody>
      </table>

      <div class="row g-3 mb-4 sim-summary-grid">
        <div class="col-12 col-md-4">
          <div class="sim-summary p-3">
            <div class="label">Angsuran/Bulan</div>
            <div class="value">{{ 'Rp ' . number_format($angsuranPerBulan, 0, ',', '.') }}</div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="sim-summary p-3">
            <div class="label">Total Bunga</div>
            <div class="value">{{ 'Rp ' . number_format($totalBunga, 0, ',', '.') }}</div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="sim-summary p-3">
            <div class="label">Total Pembayaran</div>
            <div class="value">{{ 'Rp ' . number_format($totalPembayaran, 0, ',', '.') }}</div>
          </div>
        </div>
      </div>

      <div class="table-responsive sim-table-wrap">
        <table class="table table-bordered table-striped sim-table" id="simulasi-kredit-table">
          <thead>
            <tr>
              <th>Bulan</th>
              <th class="text-end">Pokok</th>
              <th class="text-end">Bunga</th>
              <th class="text-end">Angsuran</th>
              <th class="text-end">Sisa Pokok</th>
            </tr>
          </thead>
          <tbody>
            @forelse($jadwalAngsuran as $row)
              <tr>
                <td>{{ $row['bulan'] }}</td>
                <td class="text-end"><span class="sim-currency">Rp </span><span class="sim-amount">{{ number_format($row['pokok'], 0, ',', '.') }}</span></td>
                <td class="text-end"><span class="sim-currency">Rp </span><span class="sim-amount">{{ number_format($row['bunga'], 0, ',', '.') }}</span></td>
                <td class="text-end"><span class="sim-currency">Rp </span><span class="sim-amount">{{ number_format($row['angsuran'], 0, ',', '.') }}</span></td>
                <td class="text-end"><span class="sim-currency">Rp </span><span class="sim-amount">{{ number_format($row['sisa_pokok'], 0, ',', '.') }}</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted">Belum ada hasil simulasi.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script>
      function initSimulasiPinjamanInput() {
        const display = document.getElementById('pinjaman_display');
        const hidden = document.getElementById('pinjaman_hidden');

        if (!display || !hidden || display.dataset.simulasiBound === '1') return;

        const formatRibuan = function(value) {
          const raw = String(value || '').replace(/\D/g, '');
          if (!raw) return '';
          return new Intl.NumberFormat('id-ID').format(Number(raw));
        };

        display.dataset.simulasiBound = '1';
        display.value = formatRibuan(display.value || hidden.value);

        display.addEventListener('input', function() {
          const raw = this.value.replace(/\D/g, '');
          this.value = formatRibuan(raw);
          hidden.value = raw;
          hidden.dispatchEvent(new Event('input', { bubbles: true }));
        });

        display.addEventListener('blur', function() {
          this.value = formatRibuan(this.value);
        });
      }

      function exportSimulasiKreditExcel() {
        if (!window.XLSX) {
          alert('Library export Excel belum siap. Silakan muat ulang halaman.');
          return;
        }

        const table = document.getElementById('simulasi-kredit-table');
        const metaRows = document.querySelectorAll('#simulasi-kredit-meta tr');

        if (!table || !table.querySelector('tbody tr')) {
          alert('Silakan hitung terlebih dahulu.');
          return;
        }

        const data = [
          ['SIMULASI KREDIT BKK'],
          []
        ];

        metaRows.forEach(function(row) {
          const cells = row.querySelectorAll('td');
          data.push([
            cells[0] ? cells[0].innerText : '',
            cells[1] ? cells[1].innerText : ''
          ]);
        });

        data.push([]);

        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(data);
        const wsTable = XLSX.utils.table_to_sheet(table);

        XLSX.utils.sheet_add_json(ws, XLSX.utils.sheet_to_json(wsTable, { header: 1 }), {
          origin: 'A10',
          skipHeader: false
        });

        XLSX.utils.book_append_sheet(wb, ws, 'Simulasi Kredit');
        XLSX.writeFile(wb, 'Simulasi_Kredit_BKK.xlsx');
      }

      document.addEventListener('DOMContentLoaded', initSimulasiPinjamanInput);
      document.addEventListener('livewire:navigated', initSimulasiPinjamanInput);
      document.addEventListener('livewire:init', function() {
        try {
          Livewire.hook('commit', function(payload) {
            if (payload && typeof payload.succeed === 'function') {
              payload.succeed(function() {
                setTimeout(initSimulasiPinjamanInput, 80);
              });
            }
          });
        } catch (e) {}
      });
    </script>
  @endpush
</div>
