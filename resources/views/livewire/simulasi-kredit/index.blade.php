<div class="container-fluid px-0">
  <style>
    .sim-card{
      border:0;
      border-radius:26px;
      background:#fff;
      box-shadow:0 16px 40px rgba(15,23,42,.08);
    }
    .sim-soft{
      border:1px solid #edf2f7;
      border-radius:22px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
      box-shadow:0 10px 24px rgba(15,23,42,.04);
    }
    .sim-result{
      border-radius:22px;
      padding:18px;
      color:#fff;
      min-height:100%;
      box-shadow:0 16px 32px rgba(15,23,42,.12);
    }
    .sim-result.primary{
      background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%);
    }
    .sim-result.success{
      background:linear-gradient(135deg,#10b981 0%,#059669 100%);
    }
    .sim-result.warning{
      background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%);
    }
    .sim-kv-label{
      font-size:.82rem;
      opacity:.86;
      margin-bottom:6px;
    }
    .sim-kv-value{
      font-size:1.18rem;
      font-weight:900;
      line-height:1.2;
    }
    .sim-title{
      font-size:1.55rem;
      font-weight:900;
      color:#0f172a;
      letter-spacing:-.02em;
    }
    .sim-subtitle{
      color:#64748b;
      font-size:.95rem;
    }
    .sim-note{
      border-radius:18px;
      padding:14px 16px;
      background:#fff7ed;
      color:#9a3412;
      border:1px solid #fed7aa;
      font-size:.92rem;
    }
  </style>

  <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
    <div>
      <div class="sim-title">Simulasi Kredit</div>
      <div class="sim-subtitle">Simulasi plafond, bunga, provisi, administrasi, penerimaan bersih, dan angsuran per bulan.</div>
    </div>
  </div>

  <div class="sim-card p-4">
    <div class="row g-4">
      <div class="col-12 col-lg-5">
        <div class="sim-soft p-3 h-100">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold">Produk Kredit</label>
              <select class="form-select" wire:model.live="produk">
                @foreach($produkOptions as $opt)
                  <option value="{{ $opt['kode'] }}">{{ $opt['nama'] }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Plafon Kredit</label>
              <input type="text"
                     class="form-control"
                     wire:model.live="plafon"
                     inputmode="numeric"
                     placeholder="Masukkan plafon, contoh 50000000">
              <div class="small text-muted mt-1">
                Min {{ 'Rp ' . number_format($plafonMinProduk, 0, ',', '.') }}
                •
                Max {{ 'Rp ' . number_format($plafonMaxProduk, 0, ',', '.') }}
              </div>
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Jangka Waktu</label>
              <select class="form-select" wire:model.live="jangka_waktu">
                <option value="">-- Pilih Jangka Waktu --</option>
                @foreach($tenorOptions as $tenor)
                  <option value="{{ $tenor['id'] }}">{{ $tenor['label'] }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-12 d-grid">
              <button type="button" class="btn btn-primary rounded-pill py-2" wire:click="hitung">
                Hitung Simulasi
              </button>
            </div>

            @if($catatan)
              <div class="col-12">
                <div class="sim-note">
                  {{ $catatan }}
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-7">
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <div class="sim-result primary">
              <div class="sim-kv-label">Suku Bunga</div>
              <div class="sim-kv-value">{{ $bungaLabel ?: '-' }}</div>
              <div class="small mt-2" style="opacity:.9;">{{ $metodeAngsuran ?: 'Menunggu input simulasi' }}</div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="sim-result success">
              <div class="sim-kv-label">Penerimaan Bersih</div>
              <div class="sim-kv-value">{{ 'Rp ' . number_format($penerimaanBersih, 0, ',', '.') }}</div>
              <div class="small mt-2" style="opacity:.9;">
                Plafon - Provisi - Administrasi
              </div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="sim-soft p-3 h-100">
              <div class="sim-kv-label text-muted">Provisi (1,5%)</div>
              <div class="sim-kv-value text-dark">{{ 'Rp ' . number_format($provisiNominal, 0, ',', '.') }}</div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="sim-soft p-3 h-100">
              <div class="sim-kv-label text-muted">Biaya Administrasi</div>
              <div class="sim-kv-value text-dark">{{ 'Rp ' . number_format($biayaAdmin, 0, ',', '.') }}</div>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="sim-soft p-3 h-100">
              <div class="sim-kv-label text-muted">Angsuran Pokok / Bulan</div>
              <div class="sim-kv-value text-dark">{{ 'Rp ' . number_format($angsuranPokok, 0, ',', '.') }}</div>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="sim-soft p-3 h-100">
              <div class="sim-kv-label text-muted">Angsuran Bunga / Bulan</div>
              <div class="sim-kv-value text-dark">{{ 'Rp ' . number_format($angsuranBunga, 0, ',', '.') }}</div>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="sim-result warning">
              <div class="sim-kv-label">Angsuran / Bulan</div>
              <div class="sim-kv-value">{{ 'Rp ' . number_format($angsuranPerBulan, 0, ',', '.') }}</div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="sim-soft p-3 h-100">
              <div class="sim-kv-label text-muted">Total Bunga</div>
              <div class="sim-kv-value text-dark">{{ 'Rp ' . number_format($totalBunga, 0, ',', '.') }}</div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="sim-soft p-3 h-100">
              <div class="sim-kv-label text-muted">Total Pengembalian</div>
              <div class="sim-kv-value text-dark">{{ 'Rp ' . number_format($totalPengembalian, 0, ',', '.') }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
