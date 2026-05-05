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

    .assignment-wrap{
      min-width:260px;
    }

    .assignment-label{
      display:inline-flex;
      align-items:center;
      gap:6px;
      font-size:.72rem;
      font-weight:800;
      color:#64748b;
      text-transform:uppercase;
      letter-spacing:.06em;
      margin-bottom:8px;
    }

    .assignment-box{
      border:1px solid #dbe7f3;
      border-radius:16px;
      background:linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
      padding:10px;
      box-shadow:0 8px 20px rgba(15,23,42,.05);
    }

    .assignment-select{
      min-width:240px;
      border-radius:12px;
      border:1px solid #cfddee;
      font-weight:700;
      color:#1f2937;
      background-color:#fff;
    }

    .assignment-current{
      margin-top:8px;
      padding:8px 10px;
      border-radius:12px;
      background:#f8fafc;
      border:1px dashed #d7e1ec;
    }

    .assignment-current-code{
      font-size:.88rem;
      font-weight:900;
      color:#0f172a;
      line-height:1.2;
    }

    .assignment-current-name{
      font-size:.82rem;
      color:#64748b;
      margin-top:2px;
      line-height:1.35;
    }

    .assignment-empty{
      font-size:.84rem;
      color:#94a3b8;
      font-weight:700;
      padding:6px 2px 0;
    }

    .filter-mode-card{
      border:1px solid #e7edf5;
      border-radius:16px;
      padding:10px 12px;
      background:#fff;
    }

    .filter-mode-hint{
      font-size:.82rem;
      color:#64748b;
    }
  </style>

  @php
    $loggedRole = strtoupper(trim((string)(auth()->user()->role ?? '')));
    $canManageAssignment = in_array($loggedRole, ['ADMIN','MANAJEMEN','SUPERVISOR']);
    $isAoRole = $loggedRole === 'AO';
  @endphp

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

    <div class="col-12 col-md-3">
      <label class="form-label small text-muted">Cari</label>
      <div class="input-group">
        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
        <input class="form-control"
               style="border-left:0"
               placeholder="Cari nama / no hp / nik / status / no rekening..."
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
      <label class="form-label small text-muted">Kanwil</label>
      <select class="form-select"
              wire:model.live="filterKanwil"
              @if($lockCabangFilter) disabled @endif>
        <option value="">-- Semua Kanwil --</option>
        @foreach($kanwilOptions as $k)
          <option value="{{ $k['id'] }}">{{ $k['label'] }}</option>
        @endforeach
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

    <div class="col-12 col-md-3">
      <label class="form-label small text-muted">AO</label>
      <select class="form-select" wire:model.live="filterAo">
        <option value="">-- Semua AO --</option>
        @foreach($aoOptions as $ao)
          <option value="{{ $ao->name }}">
            {{ $ao->label }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="col-12 col-md-2">
      <label class="form-label small text-muted">Input Oleh</label>
      <select class="form-select" wire:model.live="filterInputRole">
        <option value="">-- Semua --</option>
        @foreach($inputRoleOptions as $opt)
          <option value="{{ $opt['id'] }}">{{ $opt['label'] }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-12 col-md-2">
      <label class="form-label small text-muted">Penugasan</label>
      <select class="form-select" wire:model.live="filterPengambilan">
        <option value="">-- Semua --</option>
        <option value="1">Diambil</option>
        <option value="0">Belum</option>
      </select>
    </div>

    <div class="col-12 col-md-2">
      <label class="form-label small text-muted">Mode Filter Tanggal</label>
      <select class="form-select" wire:model.live="filterMode">
        @foreach($filterModeOptions as $mode)
          <option value="{{ $mode['id'] }}">{{ $mode['label'] }}</option>
        @endforeach
      </select>
    </div>

    @if($filterMode === 'monthly')
      <div class="col-6 col-md-2">
        <label class="form-label small text-muted">Bulan</label>
        <select class="form-select" wire:model.live="filterBulan">
          <option value="">-- Bulan --</option>
          @foreach($bulanOptions as $b)
            <option value="{{ $b['id'] }}">{{ $b['label'] }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <label class="form-label small text-muted">Tahun</label>
        <select class="form-select" wire:model.live="filterTahun">
          <option value="">-- Tahun --</option>
          @foreach($tahunOptions as $t)
            <option value="{{ $t }}">{{ $t }}</option>
          @endforeach
        </select>
      </div>
    @endif

    @if($filterMode === 'range')
      <div class="col-12 col-md-2">
        <label class="form-label small text-muted">Dari Tanggal</label>
        <input type="date"
               class="form-control"
               wire:model.live="filterTanggalAwal">
      </div>

      <div class="col-12 col-md-2">
        <label class="form-label small text-muted">Sampai Tanggal</label>
        <input type="date"
               class="form-control"
               wire:model.live="filterTanggalAkhir">
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

  <div class="mt-3">
    @if($filterMode === 'all')
      <div class="filter-mode-hint">
        Menampilkan <span class="fw-semibold">semua pengajuan</span> tanpa filter periode.
      </div>
    @elseif($filterMode === 'monthly')
      <div class="filter-mode-hint">
        Menampilkan data berdasarkan
        <span class="fw-semibold">bulan {{ $filterBulan ?: '-' }}</span>
        dan
        <span class="fw-semibold">tahun {{ $filterTahun ?: '-' }}</span>.
      </div>
    @elseif($filterMode === 'range')
      <div class="filter-mode-hint">
        Menampilkan data dari
        <span class="fw-semibold">{{ $filterTanggalAwal ?: '-' }}</span>
        s.d.
        <span class="fw-semibold">{{ $filterTanggalAkhir ?: '-' }}</span>.
      </div>
    @endif
  </div>
</div>

  <div class="sub-card overflow-hidden d-none d-md-block">
    <div class="table-responsive">
      <table class="table modern-table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="min-width:140px;">Tanggal</th>
            <th style="min-width:220px;">Prospek</th>
            <th style="min-width:220px;">Pengaju</th>
            <th style="min-width:180px;">Cabang</th>
            <th style="min-width:160px;">Rekomendasi Produk</th>
            <th style="min-width:140px;">Status</th>
            <th style="min-width:180px;">No Rekening</th>
            <th style="min-width:280px;">Penugasan</th>
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

              $cabangPengaju = optional($p->creator->cabang)->kode_cabang
                ? optional($p->creator->cabang)->kode_cabang . ' - ' . optional($p->creator->cabang)->nama_cabang
                : '-';

              $namaPengambilLengkap = $namaPengambilMap[$p->diambil_oleh] ?? null;
              $assignmentOptions = $assignmentMap[$p->id] ?? [];
            @endphp

            <tr class="row-hover-modern"
                wire:key="prospect-row-{{ $p->id }}-{{ md5((string)($p->diambil_oleh ?? '')) }}-{{ md5((string)($p->status ?? '')) }}">
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
                <div class="fw-semibold text-dark">{{ $p->no_rekening ?? '-' }}</div>
              </td>

              <td class="small">
                <div class="assignment-wrap">
                  @if($canManageAssignment)
                    <div class="assignment-label">
                      <i class="bi bi-person-check"></i> Pilih AO Penugasan
                    </div>

                    <div class="assignment-box">
                      <select class="form-select form-select-sm assignment-select"
                              wire:key="assignment-select-{{ $p->id }}-{{ md5((string)($p->diambil_oleh ?? '')) }}"
                              wire:change="assignProspect({{ $p->id }}, $event.target.value)">
                        <option value="">-- Pilih AO --</option>
                        @foreach($assignmentOptions as $ao)
                          <option value="{{ $ao['username'] }}" @selected((string)$p->diambil_oleh === (string)$ao['username'])>
                            {{ $ao['label'] }}
                          </option>
                        @endforeach
                      </select>

                      @if((int)($p->is_diambil ?? 0) === 1)
                        <div class="assignment-current">
                          <div class="assignment-current-code">
                            {{ $p->diambil_oleh ?: '-' }}
                          </div>
                          <div class="assignment-current-name">
                            {{ $namaPengambilLengkap ?: '-' }}
                          </div>
                        </div>
                      @else
                        <div class="assignment-empty">
                          Belum ada AO yang ditugaskan.
                        </div>
                      @endif
                    </div>
                  @else
                    @if((int)($p->is_diambil ?? 0) === 1)
                      <div class="assignment-box">
                        <div class="assignment-label mb-2">
                          <i class="bi bi-person-badge"></i> AO Penugasan
                        </div>
                        <div class="assignment-current-code">
                          {{ $p->diambil_oleh ?: '-' }}
                        </div>
                        <div class="assignment-current-name">
                          {{ $namaPengambilLengkap ?: '-' }}
                        </div>
                      </div>
                    @else
                      <div class="assignment-empty">-</div>
                    @endif
                  @endif
                </div>
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
              <td colspan="9" class="text-center text-muted p-5">
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

        $cabangPengaju = optional($p->creator->cabang)->kode_cabang
          ? optional($p->creator->cabang)->kode_cabang . ' - ' . optional($p->creator->cabang)->nama_cabang
          : '-';

        $namaPengambilLengkap = $namaPengambilMap[$p->diambil_oleh] ?? null;
        $assignmentOptions = $assignmentMap[$p->id] ?? [];
      @endphp

      <div class="mobile-prospect-card p-3 mb-2"
           wire:key="mobile-prospect-{{ $p->id }}-{{ md5((string)($p->diambil_oleh ?? '')) }}-{{ md5((string)($p->status ?? '')) }}">
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
          <i class="bi bi-credit-card-2-front"></i> No Rekening: {{ $p->no_rekening ?? '-' }}
        </div>

        <div class="divider-soft my-3"></div>

        <div class="d-flex flex-wrap gap-2">
          <span class="badge-modern {{ $produkClass }}">
            {{ $p->jenis_produk ?: '-' }}
          </span>

          <span class="badge-modern {{ $statusClass }}">
            {{ $p->status ?: '-' }}
          </span>
        </div>

        <div class="mt-3">
          <div class="text-muted small mb-1">Penugasan</div>

          @if($canManageAssignment)
            <div class="assignment-box">
              <select class="form-select form-select-sm"
                      wire:key="mobile-assignment-select-{{ $p->id }}-{{ md5((string)($p->diambil_oleh ?? '')) }}"
                      wire:change="assignProspect({{ $p->id }}, $event.target.value)">
                <option value="">-- Pilih AO --</option>
                @foreach($assignmentOptions as $ao)
                  <option value="{{ $ao['username'] }}" @selected((string)$p->diambil_oleh === (string)$ao['username'])>
                    {{ $ao['label'] }}
                  </option>
                @endforeach
              </select>

              @if((int)($p->is_diambil ?? 0) === 1)
                <div class="assignment-current mt-2">
                  <div class="assignment-current-code">{{ $p->diambil_oleh ?: '-' }}</div>
                  <div class="assignment-current-name">{{ $namaPengambilLengkap ?: '-' }}</div>
                </div>
              @else
                <div class="assignment-empty mt-2">Belum ada AO yang ditugaskan.</div>
              @endif
            </div>
          @else
            <div class="small">
              @if((int)($p->is_diambil ?? 0) === 1)
                <div class="assignment-current-code">{{ $p->diambil_oleh ?: '-' }}</div>
                <div class="text-muted">{{ $namaPengambilLengkap ?: '-' }}</div>
              @else
                -
              @endif
            </div>
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
                            <a href="https://api.whatsapp.com/send?phone={{ $waNumber }}"
                               target="_blank"
                               rel="noopener noreferrer"
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
                      <span class="detail-label">No Rekening</span>
                      <div class="detail-value-soft">{{ $detail->no_rekening ?: '-' }}</div>
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
                      <div wire:key="detail-map-wrap-{{ $detail->id ?? 'x' }}">
                        <div wire:ignore
                             id="detailProspectMap"
                             data-lat="{{ $detail->lokasi_lat }}"
                             data-lng="{{ $detail->lokasi_lng }}"
                             data-title="{{ $detail->nama }}"
                             data-alamat="{{ $detail->alamat }}"
                             style="height:320px;border-radius:18px;overflow:hidden;border:1px solid #e5e7eb;"></div>
                      </div>
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
                  @if(!$isAoRole)
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
                  @endif

                  <div class="col-12 col-lg-{{ $isAoRole ? '12' : '6' }}">
                    <div class="modal-action-card h-100">
                      <div class="detail-section-title">Update Status</div>

                      <div class="row g-2 align-items-end">
                        <div class="col-12">
                          <label class="form-label small text-muted">Pilih Status</label>
                          <select class="form-select" wire:model.live="statusUpdate">
                            <option value="">-- Pilih Status --</option>

                            @if(!$isAoRole)
                              <option value="FOLLOW UP">FOLLOW UP</option>
                            @endif

                            <option value="CLOSING">CLOSING</option>
                            <option value="REJECTED">REJECTED</option>
                          </select>
                          @error('statusUpdate')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                          @enderror
                        </div>

                        @if($statusUpdate === 'CLOSING')
                          <div class="col-12">
                            <label class="form-label small text-muted">No. Rekening</label>
                            <input type="text"
                                   class="form-control"
                                   wire:model.live="noRekening"
                                   inputmode="numeric"
                                   placeholder="Masukkan nomor rekening"
                                   oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            @error('noRekening')
                              <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                          </div>
                        @endif

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
    let lastMapSignature = null;

    function destroyDetailMap() {
        if (detailMapInstance) {
            detailMapInstance.remove();
            detailMapInstance = null;
        }
        lastMapSignature = null;
    }

    function renderDetailMap(force = false) {
        const mapEl = document.getElementById('detailProspectMap');
        if (!mapEl || typeof L === 'undefined') return;

        const lat = parseFloat(mapEl.dataset.lat || '');
        const lng = parseFloat(mapEl.dataset.lng || '');
        const title = mapEl.dataset.title || 'Lokasi Prospek';
        const alamat = mapEl.dataset.alamat || '-';

        if (isNaN(lat) || isNaN(lng)) return;

        const currentSignature = [lat, lng, title, alamat].join('|');

        if (detailMapInstance && !force && lastMapSignature === currentSignature) {
            setTimeout(function () {
                if (detailMapInstance) {
                    detailMapInstance.invalidateSize();
                }
            }, 200);
            return;
        }

        destroyDetailMap();

        detailMapInstance = L.map(mapEl).setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(detailMapInstance);

        L.marker([lat, lng]).addTo(detailMapInstance)
            .bindPopup('<b>' + title + '</b><br>' + alamat)
            .openPopup();

        lastMapSignature = currentSignature;

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
                destroyDetailMap();
                Livewire.dispatch('forceCloseProspectDetailModal');
            });

            modalEl.addEventListener('shown.bs.modal', function () {
                setTimeout(function () {
                    renderDetailMap(true);
                }, 250);
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

                setTimeout(function () {
                    renderDetailMap(true);
                }, 350);
            });
        }
    }

    setupProspectModal();

    document.addEventListener('livewire:navigated', setupProspectModal);

    Livewire.hook('morphed', function () {
        setTimeout(function () {
            const modalEl = document.getElementById('prospectDetailModal');
            if (modalEl && modalEl.classList.contains('show')) {
                renderDetailMap(false);
            }
        }, 200);
    });
});
</script>
@endpush
