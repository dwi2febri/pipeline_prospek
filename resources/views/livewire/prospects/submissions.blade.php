<div class="container-fluid px-0">

  <style>
    .sub-card{
      border:0;
      border-radius:24px;
      background:#ffffff;
      box-shadow:0 14px 40px rgba(15,23,42,.08);
    }

    .soft-filter-card{
      border:1px solid #eef2f7;
      border-radius:24px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfcfe 100%);
      box-shadow:0 10px 28px rgba(15,23,42,.05);
    }

    .modern-table thead th{
      border-bottom:1px solid #e9eef5 !important;
      background:#f8fafc !important;
      color:#334155;
      font-size:.92rem;
      font-weight:800;
      white-space:nowrap;
      vertical-align:middle;
    }

    .modern-table tbody td{
      border-color:#eef2f7 !important;
      vertical-align:middle;
    }

    .row-hover-modern{
      transition:all .18s ease;
    }
    .row-hover-modern:hover{
      background:#fbfdff;
    }

    .badge-modern{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      min-height:38px;
      padding:8px 16px;
      border-radius:999px;
      font-size:.84rem;
      font-weight:800;
      letter-spacing:.01em;
      border:1px solid transparent;
      box-shadow:0 8px 20px rgba(15,23,42,.08);
      white-space:nowrap;
    }

    .badge-status-open{
      background:linear-gradient(180deg,#ffffff 0%,#f1f5f9 100%);
      border-color:#dbe3ee;
      color:#475569;
      box-shadow:0 6px 16px rgba(148,163,184,.18);
    }

    .badge-status-follow{
      background:linear-gradient(135deg,#ffd84d 0%,#f4b400 100%);
      color:#3b2f00;
      box-shadow:0 10px 24px rgba(244,180,0,.24);
    }

    .badge-status-closing{
      background:linear-gradient(135deg,#34d399 0%,#059669 100%);
      color:#fff;
      box-shadow:0 10px 24px rgba(5,150,105,.24);
    }

    .badge-status-rejected{
      background:linear-gradient(135deg,#fb7185 0%,#e11d48 100%);
      color:#fff;
      box-shadow:0 10px 24px rgba(225,29,72,.22);
    }

    .badge-produk-kredit{
      background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);
      color:#fff;
    }

    .badge-produk-tabungan{
      background:linear-gradient(135deg,#22c55e 0%,#15803d 100%);
      color:#fff;
    }

    .badge-produk-deposito{
      background:linear-gradient(135deg,#facc15 0%,#eab308 100%);
      color:#3b2f00;
    }

    .badge-produk-aset{
      background:linear-gradient(135deg,#374151 0%,#111827 100%);
      color:#fff;
    }

    .badge-pengambilan-yes{
      background:linear-gradient(135deg,#1f2937 0%,#111827 100%);
      color:#fff;
    }

    .badge-pengambilan-no{
      background:linear-gradient(180deg,#ffffff 0%,#f3f4f6 100%);
      border:1px solid #e5e7eb;
      color:#6b7280;
      box-shadow:none;
    }

    .prospect-name{
      font-weight:800;
      font-size:1rem;
      color:#0f172a;
      line-height:1.2;
    }

    .prospect-sub{
      color:#64748b;
      font-size:.86rem;
    }

    .modal-modern .modal-content{
      border:0;
      border-radius:28px;
      overflow:hidden;
      box-shadow:0 30px 80px rgba(15,23,42,.20);
      background:
        radial-gradient(circle at top right, rgba(99,102,241,.08), transparent 28%),
        linear-gradient(180deg,#ffffff 0%,#fbfcfe 100%);
    }

    .modal-modern .modal-header{
      border-bottom:1px solid #edf2f7;
      padding:22px 24px 18px 24px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
    }

    .modal-modern .modal-title{
      font-size:1.55rem;
      font-weight:900;
      color:#1e293b;
      letter-spacing:-.02em;
    }

    .modal-modern .modal-body{
      padding:22px 24px;
    }

    .modal-modern .modal-footer{
      border-top:1px solid #edf2f7;
      padding:18px 24px 22px 24px;
      background:#fff;
    }

    .detail-hero{
      border:1px solid #edf2f7;
      border-radius:24px;
      padding:18px;
      background:linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
      box-shadow:0 10px 24px rgba(15,23,42,.05);
    }

    .detail-avatar{
      width:58px;
      height:58px;
      border-radius:18px;
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight:900;
      font-size:1.1rem;
      color:#1d4ed8;
      background:linear-gradient(135deg,#dbeafe 0%,#eff6ff 100%);
      border:1px solid #bfdbfe;
      flex:0 0 58px;
    }

    .detail-hero-name{
      font-size:1.2rem;
      font-weight:900;
      color:#0f172a;
      line-height:1.2;
    }

    .detail-hero-sub{
      font-size:.9rem;
      color:#64748b;
    }

    .detail-grid-card{
      border:1px solid #edf2f7;
      border-radius:22px;
      background:#fff;
      padding:18px;
      height:100%;
      box-shadow:0 10px 24px rgba(15,23,42,.04);
    }

    .detail-section-title{
      font-size:.84rem;
      font-weight:800;
      color:#94a3b8;
      text-transform:uppercase;
      letter-spacing:.08em;
      margin-bottom:12px;
    }

    .detail-item{
      margin-bottom:14px;
    }

    .detail-item:last-child{
      margin-bottom:0;
    }

    .detail-label{
      display:block;
      font-size:.83rem;
      color:#64748b;
      margin-bottom:4px;
    }

    .detail-value{
      font-size:1rem;
      font-weight:800;
      color:#1f2937;
      line-height:1.45;
      word-break:break-word;
    }

    .detail-value-soft{
      font-size:.95rem;
      font-weight:700;
      color:#334155;
      line-height:1.5;
      word-break:break-word;
    }

    .detail-full-card{
      border:1px solid #edf2f7;
      border-radius:22px;
      background:#fff;
      padding:18px;
      box-shadow:0 10px 24px rgba(15,23,42,.04);
    }

    .doc-card-modern{
      border:1px solid #edf2f7;
      border-radius:20px;
      padding:10px;
      background:#fff;
      box-shadow:0 10px 24px rgba(15,23,42,.04);
      height:100%;
    }

    .doc-card-modern img{
      border-radius:16px;
    }

    .btn-wa-modern{
      border-radius:999px;
      padding:8px 16px;
      font-weight:800;
      background:linear-gradient(135deg,#22c55e 0%,#15803d 100%);
      border:0;
      color:#fff;
      box-shadow:0 10px 24px rgba(21,128,61,.22);
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      gap:8px;
    }

    .modal-action-card{
      border:1px solid #edf2f7;
      border-radius:22px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
      padding:18px;
      box-shadow:0 10px 24px rgba(15,23,42,.04);
    }

    .mobile-prospect-card{
      border:0;
      border-radius:22px;
      background:#fff;
      box-shadow:0 12px 30px rgba(15,23,42,.08);
    }

    .divider-soft{
      border-top:1px dashed #e5e7eb;
      margin:2px 0 0 0;
    }
  </style>

  @if(session('ok'))
    <div class="alert alert-success rounded-4 shadow-sm">
      {{ session('ok') }}
    </div>
  @endif

  <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
    <div>
      <div class="fw-bold fs-3">Prospek Diajukan</div>
      <div class="text-muted">Daftar prospek yang diajukan oleh pegawai / AO</div>
    </div>

    <div class="ms-auto">
      <button type="button"
              class="btn btn-success rounded-pill px-4 py-2"
              wire:click="exportExcel"
              wire:loading.attr="disabled"
              wire:target="exportExcel"
              style="min-width:180px;">
        <span wire:loading.remove wire:target="exportExcel">
          <i class="bi bi-file-earmark-excel me-2"></i> Cetak Excel
        </span>
        <span wire:loading wire:target="exportExcel">
          Menyiapkan...
        </span>
      </button>
    </div>
  </div>

  <div class="soft-filter-card p-3 mb-3">
    <div class="row g-2 align-items-end">
      <div class="col-12 col-md-4">
        <label class="form-label small text-muted">Cari</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input class="form-control"
                 style="border-left:0"
                 placeholder="Cari nama / no hp / nik / status..."
                 wire:model.live.debounce.300ms="search">
        </div>
      </div>

      <div class="col-12 col-md-2">
        <label class="form-label small text-muted">Status</label>
        <select class="form-select" wire:model.live="filterStatus">
          <option value="">-- Semua Status --</option>
          <option value="OPEN">OPEN</option>
          <option value="FOLLOW UP">FOLLOW UP</option>
          <option value="CLOSING">CLOSING</option>
          <option value="REJECTED">REJECTED</option>
        </select>
      </div>

      <div class="col-12 col-md-2">
        <label class="form-label small text-muted">Cabang</label>
        <select class="form-select"
                wire:model.live="filterCabang"
                @if($lockCabangFilter) disabled @endif>
          <option value="">-- Semua Cabang --</option>
          @foreach($cabangOptions as $c)
            <option value="{{ $c->id }}">{{ $c->kode_cabang }} - {{ $c->nama_cabang }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-md-2">
        <label class="form-label small text-muted">Pengambilan</label>
        <select class="form-select" wire:model.live="filterPengambilan">
          <option value="">-- Semua --</option>
          <option value="1">Diambil</option>
          <option value="0">Belum</option>
        </select>
      </div>

      @if(in_array(strtoupper(trim((string)(auth()->user()->role ?? ''))), ['MANAJEMEN','SUPERVISOR','AO','AO_KREDIT','AO_DANA','AO_REMEDIAL']))
        <div class="col-6 col-md-1">
          <label class="form-label small text-muted">Bulan</label>
          <select class="form-select" wire:model.live="filterBulan">
            @foreach($bulanOptions as $b)
              <option value="{{ $b['id'] }}">{{ $b['label'] }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-6 col-md-1">
          <label class="form-label small text-muted">Tahun</label>
          <select class="form-select" wire:model.live="filterTahun">
            @foreach($tahunOptions as $t)
              <option value="{{ $t }}">{{ $t }}</option>
            @endforeach
          </select>
        </div>
      @endif

      <div class="col-12 col-md-2">
        <label class="form-label small text-muted d-block">&nbsp;</label>
        <button type="button" class="btn btn-light w-100 rounded-pill" wire:click="resetFilter">
          <i class="bi bi-arrow-clockwise me-1"></i> Reset
        </button>
      </div>

      <div class="col-12 col-md text-md-end text-muted small">
        Total: <span class="fw-bold">{{ $items->total() }}</span> pengajuan
      </div>
    </div>
  </div>

  <div class="sub-card overflow-hidden d-none d-md-block">
    <div class="table-responsive">
      <table class="table modern-table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="min-width:140px;">Tanggal</th>
            <th style="min-width:240px;">Prospek</th>
            <th style="min-width:220px;">Pengaju</th>
            <th style="min-width:220px;">Cabang</th>
            <th style="min-width:170px;">Rekomendasi Produk</th>
            <th style="min-width:140px;">Status</th>
            <th style="min-width:180px;">Pengambilan</th>
            <th style="width:120px;" class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $p)
            @php
              $statusClass = 'badge-status-open';
              if($p->status === 'FOLLOW UP') $statusClass = 'badge-status-follow';
              elseif($p->status === 'CLOSING') $statusClass = 'badge-status-closing';
              elseif($p->status === 'REJECTED') $statusClass = 'badge-status-rejected';

              $produkClass = 'badge-produk-kredit';
              if($p->jenis_produk === 'TABUNGAN') $produkClass = 'badge-produk-tabungan';
              elseif($p->jenis_produk === 'DEPOSITO') $produkClass = 'badge-produk-deposito';
              elseif($p->jenis_produk === 'ASET') $produkClass = 'badge-produk-aset';

              $pengambilanClass = ((int)($p->is_diambil ?? 0) === 1) ? 'badge-pengambilan-yes' : 'badge-pengambilan-no';
              $cabangPengaju = optional($p->creator->cabang)->kode_cabang
                ? optional($p->creator->cabang)->kode_cabang . ' - ' . optional($p->creator->cabang)->nama_cabang
                : '-';
            @endphp
            <tr class="row-hover-modern">
              <td class="small fw-semibold text-slate-700">
                {{ \Illuminate\Support\Carbon::parse($p->tanggal_prospek)->format('d/m/Y') }}
              </td>

              <td>
                <div class="prospect-name">{{ $p->nama }}</div>
                <div class="prospect-sub mt-1">
                  {{ $p->no_hp ?: '-' }}
                  <span class="mx-1">•</span>
                  NIK: {{ $p->nik ?: '-' }}
                </div>
              </td>

              <td class="small">
                <div class="fw-bold text-dark">{{ $p->creator->name ?? '-' }}</div>
                <div class="text-muted">{{ $p->creator->nama_lengkap ?? '-' }}</div>
                <div class="text-muted">{{ $cabangPengaju }}</div>
              </td>

              <td class="small">
                <div class="fw-semibold text-dark">
                  {{ $p->cabang ? ($p->cabang->kode_cabang.' - '.$p->cabang->nama_cabang) : '-' }}
                </div>
              </td>

              <td>
                <span class="badge-modern {{ $produkClass }}">
                  {{ $p->jenis_produk ?: '-' }}
                </span>
              </td>

              <td>
                <span class="badge-modern {{ $statusClass }}">
                  {{ $p->status ?: '-' }}
                </span>
              </td>

              <td class="small">
                @if((int)($p->is_diambil ?? 0) === 1)
                  <span class="badge-modern {{ $pengambilanClass }}">Diambil</span>
                  <div class="text-muted mt-2 fw-semibold">
                    {{ $namaPengambilMap[$p->diambil_oleh] ?? ($p->diambil_oleh ?: '-') }}
                  </div>
                @else
                  <span class="badge-modern {{ $pengambilanClass }}">Belum</span>
                @endif
              </td>

              <td class="text-end">
                <button type="button"
                        class="btn btn-outline-primary btn-sm rounded-pill px-3"
                        wire:click="openDetail({{ $p->id }})">
                  <i class="bi bi-eye me-1"></i> Detail
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted p-5">
                Belum ada pengajuan prospek dari pegawai / AO.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="d-block d-md-none">
    @forelse($items as $p)
      @php
        $statusClass = 'badge-status-open';
        if($p->status === 'FOLLOW UP') $statusClass = 'badge-status-follow';
        elseif($p->status === 'CLOSING') $statusClass = 'badge-status-closing';
        elseif($p->status === 'REJECTED') $statusClass = 'badge-status-rejected';

        $produkClass = 'badge-produk-kredit';
        if($p->jenis_produk === 'TABUNGAN') $produkClass = 'badge-produk-tabungan';
        elseif($p->jenis_produk === 'DEPOSITO') $produkClass = 'badge-produk-deposito';
        elseif($p->jenis_produk === 'ASET') $produkClass = 'badge-produk-aset';

        $pengambilanClass = ((int)($p->is_diambil ?? 0) === 1) ? 'badge-pengambilan-yes' : 'badge-pengambilan-no';
        $cabangPengaju = optional($p->creator->cabang)->kode_cabang
          ? optional($p->creator->cabang)->kode_cabang . ' - ' . optional($p->creator->cabang)->nama_cabang
          : '-';
      @endphp

      <div class="mobile-prospect-card p-3 mb-2">
        <div class="d-flex align-items-start justify-content-between gap-2">
          <div class="fw-bold fs-6">{{ $p->nama }}</div>
          <div class="text-muted small">
            {{ \Illuminate\Support\Carbon::parse($p->tanggal_prospek)->format('d/m/Y') }}
          </div>
        </div>

        <div class="text-muted small mt-1">
          <i class="bi bi-telephone"></i> {{ $p->no_hp ?: '-' }}
          &nbsp;•&nbsp;
          <i class="bi bi-person-vcard"></i> {{ $p->nik ?: '-' }}
        </div>

        <div class="text-muted small mt-1">
          <i class="bi bi-building"></i>
          {{ $p->cabang->kode_cabang ?? '-' }}{{ $p->cabang ? ' - '.$p->cabang->nama_cabang : '' }}
        </div>

        <div class="text-muted small mt-1">
          <i class="bi bi-person"></i>
          {{ $p->creator->name ?? '-' }}
          @if(!empty($p->creator->nama_lengkap))
            • {{ $p->creator->nama_lengkap }}
          @endif
        </div>

        <div class="text-muted small mt-1">
          <i class="bi bi-shop"></i> {{ $cabangPengaju }}
        </div>

        <div class="divider-soft my-3"></div>

        <div class="d-flex flex-wrap gap-2">
          <span class="badge-modern {{ $produkClass }}">
            {{ $p->jenis_produk ?: '-' }}
          </span>

          <span class="badge-modern {{ $statusClass }}">
            {{ $p->status ?: '-' }}
          </span>

          @if((int)($p->is_diambil ?? 0) === 1)
            <span class="badge-modern {{ $pengambilanClass }}">
              Diambil: {{ $namaPengambilMap[$p->diambil_oleh] ?? ($p->diambil_oleh ?: '-') }}
            </span>
          @else
            <span class="badge-modern {{ $pengambilanClass }}">
              Belum Diambil
            </span>
          @endif
        </div>

        <div class="mt-3">
          <button type="button"
                  class="btn btn-outline-primary btn-sm w-100 rounded-pill"
                  wire:click="openDetail({{ $p->id }})">
            <i class="bi bi-eye me-1"></i> Detail
          </button>
        </div>
      </div>
    @empty
      <div class="sub-card p-4 text-center text-muted">
        Belum ada pengajuan prospek dari pegawai / AO.
      </div>
    @endforelse
  </div>

  <div class="mt-3">
    {{ $items->links() }}
  </div>

  <div wire:ignore.self class="modal fade modal-modern" id="prospectDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-fullscreen-sm-down">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <h5 class="modal-title mb-1">Detail Prospek Diajukan</h5>
            <div class="text-muted small">ID Prospek: {{ $detail->id ?? '-' }}</div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          @if($detail)

            @if($showTakenMessage && !$canViewDetail)
              <div class="alert alert-warning rounded-4 mb-0">
                Prospek ini <b>sudah diambil</b> oleh
                <b>{{ $takenByFullName ?: ($takenByUsername ?: '-') }}</b>.
                Anda tidak bisa melihat detailnya.
              </div>
            @elseif(!$canViewDetail)
              <div class="alert alert-warning rounded-4 mb-0">
                Anda tidak berhak melihat detail prospek ini.
              </div>
            @else

              @php
                $detailStatusClass = 'badge-status-open';
                if($detail->status === 'FOLLOW UP') $detailStatusClass = 'badge-status-follow';
                elseif($detail->status === 'CLOSING') $detailStatusClass = 'badge-status-closing';
                elseif($detail->status === 'REJECTED') $detailStatusClass = 'badge-status-rejected';

                $detailProdukClass = 'badge-produk-kredit';
                if($detail->jenis_produk === 'TABUNGAN') $detailProdukClass = 'badge-produk-tabungan';
                elseif($detail->jenis_produk === 'DEPOSITO') $detailProdukClass = 'badge-produk-deposito';
                elseif($detail->jenis_produk === 'ASET') $detailProdukClass = 'badge-produk-aset';

                $detailPengambilanClass = ((int)($detail->is_diambil ?? 0) === 1) ? 'badge-pengambilan-yes' : 'badge-pengambilan-no';
                $detailCabangPengaju = optional($detail->creator->cabang)->kode_cabang
                  ? optional($detail->creator->cabang)->kode_cabang . ' - ' . optional($detail->creator->cabang)->nama_cabang
                  : '-';
              @endphp

              <div class="detail-hero mb-4">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                  <div class="d-flex align-items-center gap-3">
                    <div class="detail-avatar">
                      {{ strtoupper(substr((string)($detail->nama ?? 'P'),0,1)) }}
                    </div>
                    <div>
                      <div class="detail-hero-name">{{ $detail->nama ?: '-' }}</div>
                      <div class="detail-hero-sub mt-1">
                        {{ \Illuminate\Support\Carbon::parse($detail->tanggal_prospek)->format('d/m/Y') }}
                        <span class="mx-1">•</span>
                        {{ $detail->cabang ? ($detail->cabang->kode_cabang.' - '.$detail->cabang->nama_cabang) : '-' }}
                      </div>
                    </div>
                  </div>

                  <div class="d-flex flex-wrap gap-2">
                    <span class="badge-modern {{ $detailProdukClass }}">
                      {{ $detail->jenis_produk ?: '-' }}
                    </span>
                    <span class="badge-modern {{ $detailStatusClass }}">
                      {{ $detail->status ?: '-' }}
                    </span>
                    <span class="badge-modern {{ $detailPengambilanClass }}">
                      @if((int)($detail->is_diambil ?? 0) === 1)
                        Diambil
                      @else
                        Belum
                      @endif
                    </span>
                  </div>
                </div>
              </div>

              <div class="row g-3">
                <div class="col-12 col-lg-6">
                  <div class="detail-grid-card">
                    <div class="detail-section-title">Informasi Prospek</div>

                    <div class="detail-item">
                      <span class="detail-label">Nama Prospek</span>
                      <div class="detail-value">{{ $detail->nama ?: '-' }}</div>
                    </div>

                    <div class="detail-item">
                      <span class="detail-label">No HP</span>

                      @php
                        $waNumber = preg_replace('/[^0-9]/', '', (string) ($detail->no_hp ?? ''));

                        if ($waNumber !== '') {
                          if (substr($waNumber, 0, 1) === '0') {
                            $waNumber = '62' . substr($waNumber, 1);
                          } elseif (substr($waNumber, 0, 2) !== '62') {
                            $waNumber = '62' . $waNumber;
                          }
                        }
                      @endphp

                      <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="detail-value">{{ $detail->no_hp ?: '-' }}</div>

                        @if(!empty($detail->no_hp) && !empty($waNumber))
                          <a href="https://wa.me/{{ $waNumber }}"
                             target="_blank"
                             class="btn-wa-modern">
                            <i class="bi bi-whatsapp"></i> WA
                          </a>
                        @endif
                      </div>
                    </div>

                    <div class="detail-item">
                      <span class="detail-label">NIK</span>
                      <div class="detail-value-soft">{{ $detail->nik ?: '-' }}</div>
                    </div>

                    <div class="detail-item">
                      <span class="detail-label">Tanggal Prospek</span>
                      <div class="detail-value-soft">{{ \Illuminate\Support\Carbon::parse($detail->tanggal_prospek)->format('d/m/Y') }}</div>
                    </div>

                    <div class="detail-item">
                      <span class="detail-label">Jenis Produk</span>
                      <div class="detail-value-soft">{{ $detail->jenis_produk ?: '-' }}</div>
                    </div>

                    <div class="detail-item">
                      <span class="detail-label">Jenis Usaha</span>
                      <div class="detail-value-soft">{{ $detail->jenis_usaha ?: '-' }}</div>
                    </div>

                    <div class="detail-item">
                      <span class="detail-label">Keterangan Usaha</span>
                      <div class="detail-value-soft">{{ $detail->keterangan_usaha ?: '-' }}</div>
                    </div>

                    <div class="detail-item">
                      <span class="detail-label">Catatan</span>
                      <div class="detail-value-soft">{{ $detail->catatan ?: '-' }}</div>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-lg-6">
                  <div class="detail-grid-card">
                    <div class="detail-section-title">Informasi Pengaju & Lokasi</div>

                    <div class="detail-item">
                      <span class="detail-label">Pengaju</span>
                      <div class="detail-value">{{ $detail->creator->name ?? '-' }}</div>
                      <div class="text-muted small mt-1">{{ $detail->creator->nama_lengkap ?? '-' }}</div>
                    </div>

                    <div class="detail-item">
                      <span class="detail-label">Cabang Pengaju</span>
                      <div class="detail-value-soft">{{ $detailCabangPengaju }}</div>
                    </div>

                    <div class="detail-item">
                      <span class="detail-label">Cabang Prospek</span>
                      <div class="detail-value-soft">
                        {{ $detail->cabang ? ($detail->cabang->kode_cabang.' - '.$detail->cabang->nama_cabang) : '-' }}
                      </div>
                    </div>

                    <div class="detail-item">
                      <span class="detail-label">Status Saat Ini</span>
                      <div class="detail-value-soft">{{ $detail->status ?: '-' }}</div>
                    </div>

                    <div class="detail-item">
                      <span class="detail-label">Diambil</span>
                      <div class="detail-value-soft">
                        @if((int)($detail->is_diambil ?? 0) === 1)
                          YA
                          <span class="text-muted">- {{ $takenByFullName ?: ($takenByUsername ?: '-') }}</span>
                        @else
                          TIDAK
                        @endif
                      </div>
                    </div>

                    <div class="detail-item">
                      <span class="detail-label">Alamat</span>
                      <div class="detail-value-soft">{{ $detail->alamat ?: '-' }}</div>
                    </div>

                    <div class="row g-3 mt-1">
                      <div class="col-12 col-md-4">
                        <span class="detail-label">Kab/Kota</span>
                        <div class="detail-value-soft">{{ $detail->kab_kota ?: '-' }}</div>
                      </div>
                      <div class="col-12 col-md-4">
                        <span class="detail-label">Kecamatan</span>
                        <div class="detail-value-soft">{{ $detail->kecamatan ?: '-' }}</div>
                      </div>
                      <div class="col-12 col-md-4">
                        <span class="detail-label">Desa</span>
                        <div class="detail-value-soft">{{ $detail->desa ?: '-' }}</div>
                      </div>
                    </div>

                    <div class="row g-3 mt-1">
                      <div class="col-12 col-md-6">
                        <span class="detail-label">Latitude</span>
                        <div class="detail-value-soft">{{ $detail->lokasi_lat ?: '-' }}</div>
                      </div>
                      <div class="col-12 col-md-6">
                        <span class="detail-label">Longitude</span>
                        <div class="detail-value-soft">{{ $detail->lokasi_lng ?: '-' }}</div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-12">
                  <div class="detail-full-card">
                    <div class="detail-section-title">Titik Lokasi</div>

                    @if(!empty($detail->lokasi_lat) && !empty($detail->lokasi_lng))
                      <div id="detailProspectMap"
                           data-lat="{{ $detail->lokasi_lat }}"
                           data-lng="{{ $detail->lokasi_lng }}"
                           data-title="{{ $detail->nama }}"
                           data-alamat="{{ $detail->alamat }}"
                           style="height:320px;border-radius:18px;overflow:hidden;border:1px solid #e5e7eb;"></div>
                    @else
                      <div class="text-muted">Koordinat lokasi belum tersedia.</div>
                    @endif
                  </div>
                </div>

                <div class="col-12">
                  <div class="detail-full-card">
                    <div class="detail-section-title">Foto / Dokumen</div>

                    @if($detail->documents && $detail->documents->count())
                      <div class="row g-3">
                        @foreach($detail->documents as $doc)
                          <div class="col-6 col-md-4 col-lg-3">
                            <div class="doc-card-modern">
                              @php
                                $path = $doc->file_path ?? '';
                                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                              @endphp

                              @if($isImage)
                                <a href="{{ $doc->url }}" target="_blank">
                                  <img src="{{ $doc->url }}"
                                       class="img-fluid w-100"
                                       style="height:180px;object-fit:cover;">
                                </a>
                              @else
                                <div class="text-muted small mb-2">File non-gambar</div>
                                <a href="{{ $doc->url }}" target="_blank" class="btn btn-light btn-sm rounded-pill">
                                  Buka File
                                </a>
                              @endif

                              <div class="small text-muted mt-2 text-break">
                                {{ basename($doc->file_path ?? '-') }}
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    @else
                      <div class="text-muted">Belum ada foto / dokumen.</div>
                    @endif
                  </div>
                </div>

                @if(!$hideActionForm)
                  <div class="col-12 col-lg-6">
                    <div class="modal-action-card h-100">
                      <div class="detail-section-title">Status Pengambilan</div>

                      <div class="row g-2 align-items-end">
                        <div class="col-12">
                          <label class="form-label small text-muted">Diambil / Tidak Diambil</label>
                          <select class="form-select" wire:model.live="ambilStatus">
                            <option value="0">TIDAK DIAMBIL</option>
                            <option value="1">DIAMBIL</option>
                          </select>
                        </div>

                        <div class="col-12">
                          <button type="button"
                                  class="btn btn-dark w-100 rounded-pill"
                                  wire:click="updateAmbilStatus"
                                  wire:loading.attr="disabled"
                                  wire:target="updateAmbilStatus">
                            <span wire:loading.remove wire:target="updateAmbilStatus">Simpan Pengambilan</span>
                            <span wire:loading wire:target="updateAmbilStatus">Menyimpan...</span>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-lg-6">
                    <div class="modal-action-card h-100">
                      <div class="detail-section-title">Update Status</div>

                      <div class="row g-2 align-items-end">
                        <div class="col-12">
                          <label class="form-label small text-muted">Pilih Status</label>
                          <select class="form-select" wire:model.live="statusUpdate">
                            <option value="">-- Pilih Status --</option>
                            <option value="OPEN">OPEN</option>
                            <option value="FOLLOW UP">FOLLOW UP</option>
                            <option value="CLOSING">CLOSING</option>
                            <option value="REJECTED">REJECTED</option>
                          </select>
                          @error('statusUpdate')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                          @enderror
                        </div>

                        <div class="col-12">
                          <button type="button"
                                  class="btn btn-primary w-100 rounded-pill"
                                  wire:click="updateStatus"
                                  wire:loading.attr="disabled"
                                  wire:target="updateStatus">
                            <span wire:loading.remove wire:target="updateStatus">Simpan Status</span>
                            <span wire:loading wire:target="updateStatus">Menyimpan...</span>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            @endif
          @else
            <div class="text-muted">Data detail tidak ditemukan.</div>
          @endif
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('livewire:init', function () {
    let detailMapInstance = null;

    function renderDetailMap() {
        const mapEl = document.getElementById('detailProspectMap');
        if (!mapEl || typeof L === 'undefined') return;

        const lat = parseFloat(mapEl.dataset.lat || '');
        const lng = parseFloat(mapEl.dataset.lng || '');
        const title = mapEl.dataset.title || 'Lokasi Prospek';
        const alamat = mapEl.dataset.alamat || '-';

        if (isNaN(lat) || isNaN(lng)) return;

        if (detailMapInstance) {
            detailMapInstance.remove();
            detailMapInstance = null;
        }

        detailMapInstance = L.map(mapEl).setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(detailMapInstance);

        L.marker([lat, lng]).addTo(detailMapInstance)
            .bindPopup('<b>' + title + '</b><br>' + alamat)
            .openPopup();

        setTimeout(function () {
            if (detailMapInstance) {
                detailMapInstance.invalidateSize();
            }
        }, 300);
    }

    function setupProspectModal() {
        const modalEl = document.getElementById('prospectDetailModal');
        if (!modalEl || typeof bootstrap === 'undefined') return;

        bootstrap.Modal.getOrCreateInstance(modalEl, {
            backdrop: true,
            keyboard: true
        });

        if (!modalEl.dataset.boundHidden) {
            modalEl.dataset.boundHidden = '1';

            modalEl.addEventListener('hidden.bs.modal', function () {
                if (detailMapInstance) {
                    detailMapInstance.remove();
                    detailMapInstance = null;
                }
                Livewire.dispatch('forceCloseProspectDetailModal');
            });

            modalEl.addEventListener('shown.bs.modal', function () {
                setTimeout(renderDetailMap, 250);
            });
        }

        if (!window.__prospectModalOpenBound) {
            window.__prospectModalOpenBound = true;

            Livewire.on('open-prospect-detail-modal', function () {
                const el = document.getElementById('prospectDetailModal');
                if (!el || typeof bootstrap === 'undefined') return;

                const instance = bootstrap.Modal.getOrCreateInstance(el, {
                    backdrop: true,
                    keyboard: true
                });

                instance.show();

                setTimeout(renderDetailMap, 350);
            });
        }
    }

    setupProspectModal();

    document.addEventListener('livewire:navigated', setupProspectModal);

    Livewire.hook('morphed', function () {
        setTimeout(function () {
            const modalEl = document.getElementById('prospectDetailModal');
            if (modalEl && modalEl.classList.contains('show')) {
                renderDetailMap();
            }
        }, 200);
    });
});
</script>
@endpush
