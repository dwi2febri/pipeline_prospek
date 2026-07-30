<div class="container-fluid px-0">

  <style>
    .recap-table{
      --rt-border:#d9dee7;
      --rt-border-strong:#cbd3df;
      --rt-head:#eef2f7;
      --rt-subhead:#f6f8fb;
      --rt-text:#1f2937;
    }

    .recap-table table{
      border-collapse:separate;
      border-spacing:0;
      width:100%;
      margin-bottom:0;
    }

    .recap-table thead th{
      background:var(--rt-head) !important;
      color:var(--rt-text);
      font-weight:700;
      border-top:1px solid var(--rt-border-strong) !important;
      border-bottom:1px solid var(--rt-border-strong) !important;
      border-right:1px solid var(--rt-border) !important;
      vertical-align:middle !important;
      white-space:nowrap;
    }

    .recap-table thead tr:first-child th:first-child{
      border-left:1px solid var(--rt-border-strong) !important;
      border-top-left-radius:12px;
    }

    .recap-table thead tr:first-child th:last-child{
      border-top-right-radius:12px;
    }

    .recap-table thead tr:nth-child(2) th{
      background:var(--rt-subhead) !important;
      border-top:0 !important;
      font-weight:700;
    }

    .recap-table thead tr:nth-child(2) th:first-child{
      border-left:1px solid var(--rt-border) !important;
    }

    .recap-table thead a{
      color:var(--rt-text) !important;
    }

    .recap-table tbody td{
      border-right:1px solid #edf1f5 !important;
      border-bottom:1px solid #edf1f5 !important;
      vertical-align:middle !important;
      background:#fff;
    }

    .recap-table tbody tr td:first-child{
      border-left:1px solid #edf1f5 !important;
    }

    .recap-table tbody tr:hover td{
      background:#fafbfd;
    }

    .recap-table tbody tr:last-child td:first-child{
      border-bottom-left-radius:12px;
    }

    .recap-table tbody tr:last-child td:last-child{
      border-bottom-right-radius:12px;
    }

    .recap-table .group-head{
      text-align:center;
      box-shadow:inset 0 -1px 0 var(--rt-border-strong);
    }

    .recap-table .subcol-head{
      text-align:center;
    }
  </style>

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-3">Rekap Prospek</div>
      <div class="text-muted">
        @if($activeTab === 'kc')
          Rekap jumlah pengajuan prospek per kantor cabang, termasuk Kantor Pusat
        @elseif($activeTab === 'pengaju')
          Rekap jumlah pengajuan dari user pada masing-masing cabang, termasuk Kantor Pusat
        @else
          Daftar pegawai / AO beserta jumlah pengajuan prospek
        @endif
      </div>
    </div>

    <button type="button"
            class="btn btn-success rounded-pill px-4"
            wire:click="exportExcel">
      <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
    </button>
  </div>

  <div class="card-soft p-2 mb-3">
    <div class="d-flex flex-wrap gap-2">
      <button type="button"
              class="btn rounded-pill px-4 {{ $activeTab === 'kc' ? 'btn-primary' : 'btn-light' }}"
              wire:click="setActiveTab('kc')">
        Per KC
      </button>

      <button type="button"
              class="btn rounded-pill px-4 {{ $activeTab === 'pegawai' ? 'btn-primary' : 'btn-light' }}"
              wire:click="setActiveTab('pegawai')">
        Per Pegawai
      </button>

      <button type="button"
              class="btn rounded-pill px-4 {{ $activeTab === 'pengaju' ? 'btn-primary' : 'btn-light' }}"
              wire:click="setActiveTab('pengaju')">
        Pengaju
      </button>
    </div>
  </div>

  <div class="card-soft p-3 mb-3"
       data-mobile-filter-panel
       data-mobile-filter-key="prospect-recap">
    <div class="row g-2 align-items-end">
      <div class="col-12 col-md-3">
        <label class="form-label fw-semibold mb-1">Filter Cabang</label>
        <select class="form-select"
                wire:model.live="filterCabang"
                @if($lockCabangFilter) disabled @endif>
          <option value="">-- Semua Cabang --</option>
          @foreach($cabangs as $c)
            <option value="{{ in_array($c->kode_cabang, ['100','200','300','400']) ? $c->kode_cabang : $c->id }}">
              {{ $c->kode_cabang }} - {{ strtoupper($c->nama_cabang) }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-md-2">
        <label class="form-label fw-semibold mb-1">Mode Tanggal</label>
        <select class="form-select" wire:model.live="filterMode">
          <option value="all">Semua</option>
          <option value="monthly">Bulanan</option>
          <option value="range">Range Tanggal</option>
        </select>
      </div>

      @if($filterMode === 'monthly')
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
      @elseif($filterMode === 'range')
        <div class="col-6 col-md-2">
          <label class="form-label fw-semibold mb-1">Tanggal Awal</label>
          <input type="date" class="form-control" wire:model.live="filterTanggalAwal">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-semibold mb-1">Tanggal Akhir</label>
          <input type="date" class="form-control" wire:model.live="filterTanggalAkhir">
        </div>
      @endif

      <div class="col-12 col-md-3" data-mobile-filter-primary>
        <label class="form-label fw-semibold mb-1">Cari</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input class="form-control"
                 style="border-left:0"
                 placeholder="{{ $activeTab === 'pegawai' ? 'Cari username / nama / jabatan / cabang...' : 'Cari kode cabang / nama cabang...' }}"
                 wire:model.live.debounce.300ms="search">
        </div>
      </div>
    </div>
  </div>

  <div class="card-soft overflow-hidden recap-table">
    <div class="table-responsive">

      @if($activeTab === 'kc')
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th rowspan="2" style="width:70px;">No</th>

              <th rowspan="2" style="min-width:150px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('kode_cabang')">
                  Kode Cabang
                  @if($sortFieldKc === 'kode_cabang')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th rowspan="2" style="min-width:260px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('nama_cabang')">
                  Nama Cabang
                  @if($sortFieldKc === 'nama_cabang')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th colspan="3" class="group-head" style="min-width:360px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_pengajuan')">
                  Jumlah Pengajuan
                  @if($sortFieldKc === 'total_pengajuan')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th rowspan="2" class="text-end" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_open')">
                  Open
                  @if($sortFieldKc === 'total_open')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th rowspan="2" class="text-end" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_follow_up')">
                  Follow Up
                  @if($sortFieldKc === 'total_follow_up')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th rowspan="2" class="text-end" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_closing')">
                  Closing
                  @if($sortFieldKc === 'total_closing')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th rowspan="2" class="text-end" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_rejected')">
                  Rejected
                  @if($sortFieldKc === 'total_rejected')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>
            </tr>
            <tr>
              <th class="text-end subcol-head" style="min-width:120px;">Total</th>
              <th class="text-end subcol-head" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_pengajuan_ao')">
                  AO
                  @if($sortFieldKc === 'total_pengajuan_ao')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>
              <th class="text-end subcol-head" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_pengajuan_non_ao')">
                  NON AO
                  @if($sortFieldKc === 'total_pengajuan_non_ao')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>
            </tr>
          </thead>
          <tbody>
            @forelse($items as $i => $row)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td class="fw-semibold">{{ $row->kode_cabang }}</td>
                <td>{{ $row->nama_cabang }}</td>

                <td class="text-end fw-bold">
                  <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold" wire:click="openDetailKc({{ $row->id }}, 'ALL')">
                    {{ number_format($row->total_pengajuan) }}
                  </button>
                </td>
                <td class="text-end text-primary fw-bold">{{ number_format($row->total_pengajuan_ao ?? 0) }}</td>
                <td class="text-end text-dark fw-bold">{{ number_format($row->total_pengajuan_non_ao ?? 0) }}</td>

                <td class="text-end text-secondary fw-bold">
                  <button type="button" class="btn btn-link p-0 text-decoration-none text-secondary fw-bold" wire:click="openDetailKc({{ $row->id }}, 'OPEN')">
                    {{ number_format($row->total_open) }}
                  </button>
                </td>
                <td class="text-end text-warning fw-bold">
                  <button type="button" class="btn btn-link p-0 text-decoration-none text-warning fw-bold" wire:click="openDetailKc({{ $row->id }}, 'FOLLOW UP')">
                    {{ number_format($row->total_follow_up) }}
                  </button>
                </td>
                <td class="text-end text-success fw-bold">
                  <button type="button" class="btn btn-link p-0 text-decoration-none text-success fw-bold" wire:click="openDetailKc({{ $row->id }}, 'CLOSING')">
                    {{ number_format($row->total_closing) }}
                  </button>
                </td>
                <td class="text-end text-danger fw-bold">
                  <button type="button" class="btn btn-link p-0 text-decoration-none text-danger fw-bold" wire:click="openDetailKc({{ $row->id }}, 'REJECTED')">
                    {{ number_format($row->total_rejected) }}
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="10" class="text-center text-muted p-5">Belum ada data rekap prospek per KC.</td>
              </tr>
            @endforelse
          </tbody>
        </table>

      @elseif($activeTab === 'pengaju')
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th rowspan="2" style="width:70px;">No</th>

              <th rowspan="2" style="min-width:150px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('kode_cabang')">
                  Kode Cabang
                  @if($sortFieldPengaju === 'kode_cabang')
                    <i class="bi {{ $sortDirectionPengaju === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th rowspan="2" style="min-width:260px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('nama_cabang')">
                  Nama Cabang
                  @if($sortFieldPengaju === 'nama_cabang')
                    <i class="bi {{ $sortDirectionPengaju === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th colspan="3" class="group-head" style="min-width:360px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_pengajuan')">
                  Jumlah Pengaju
                  @if($sortFieldPengaju === 'total_pengajuan')
                    <i class="bi {{ $sortDirectionPengaju === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th rowspan="2" class="text-end" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_open')">
                  Open
                  @if($sortFieldPengaju === 'total_open')
                    <i class="bi {{ $sortDirectionPengaju === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th rowspan="2" class="text-end" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_follow_up')">
                  Follow Up
                  @if($sortFieldPengaju === 'total_follow_up')
                    <i class="bi {{ $sortDirectionPengaju === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th rowspan="2" class="text-end" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_closing')">
                  Closing
                  @if($sortFieldPengaju === 'total_closing')
                    <i class="bi {{ $sortDirectionPengaju === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th rowspan="2" class="text-end" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_rejected')">
                  Rejected
                  @if($sortFieldPengaju === 'total_rejected')
                    <i class="bi {{ $sortDirectionPengaju === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>
            </tr>
            <tr>
              <th class="text-end subcol-head" style="min-width:120px;">Total</th>
              <th class="text-end subcol-head" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_pengajuan_ao')">
                  AO
                  @if($sortFieldPengaju === 'total_pengajuan_ao')
                    <i class="bi {{ $sortDirectionPengaju === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>
              <th class="text-end subcol-head" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_pengajuan_non_ao')">
                  NON AO
                  @if($sortFieldPengaju === 'total_pengajuan_non_ao')
                    <i class="bi {{ $sortDirectionPengaju === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>
            </tr>
          </thead>
          <tbody>
            @forelse($items as $i => $row)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td class="fw-semibold">{{ $row->kode_cabang }}</td>
                <td>{{ $row->nama_cabang }}</td>
                <td class="text-end fw-bold text-primary">{{ number_format($row->total_pengajuan) }}</td>
                <td class="text-end text-primary fw-bold">{{ number_format($row->total_pengajuan_ao ?? 0) }}</td>
                <td class="text-end text-dark fw-bold">{{ number_format($row->total_pengajuan_non_ao ?? 0) }}</td>
                <td class="text-end text-secondary fw-bold">{{ number_format($row->total_open) }}</td>
                <td class="text-end text-warning fw-bold">{{ number_format($row->total_follow_up) }}</td>
                <td class="text-end text-success fw-bold">{{ number_format($row->total_closing) }}</td>
                <td class="text-end text-danger fw-bold">{{ number_format($row->total_rejected) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="10" class="text-center text-muted p-5">Belum ada data rekap pengaju per cabang.</td>
              </tr>
            @endforelse
          </tbody>
        </table>

      @else
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th style="width:70px;">No</th>

              <th style="min-width:160px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('name')">
                  Username
                  @if($sortFieldPegawai === 'name')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th style="min-width:220px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('nama_lengkap')">
                  Nama Lengkap
                  @if($sortFieldPegawai === 'nama_lengkap')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th style="min-width:130px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('role')">
                  Role
                  @if($sortFieldPegawai === 'role')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th style="min-width:220px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('job_position')">
                  Jabatan
                  @if($sortFieldPegawai === 'job_position')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th style="min-width:220px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('kode_cabang')">
                  Cabang
                  @if($sortFieldPegawai === 'kode_cabang')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th class="text-end" style="min-width:150px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_pengajuan')">
                  Jumlah Pengajuan
                  @if($sortFieldPegawai === 'total_pengajuan')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th class="text-end" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_open')">
                  Open
                  @if($sortFieldPegawai === 'total_open')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th class="text-end" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_follow_up')">
                  Follow Up
                  @if($sortFieldPegawai === 'total_follow_up')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th class="text-end" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_closing')">
                  Closing
                  @if($sortFieldPegawai === 'total_closing')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th class="text-end" style="min-width:120px;">
                <a href="javascript:void(0)" class="text-decoration-none fw-bold" wire:click="sortBy('total_rejected')">
                  Rejected
                  @if($sortFieldPegawai === 'total_rejected')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </a>
              </th>

              <th style="width:120px;" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($items as $i => $row)
              <tr>
                <td>{{ $items->firstItem() + $i }}</td>
                <td class="fw-semibold">{{ $row->name }}</td>
                <td>{{ $row->nama_lengkap ?: '-' }}</td>
                <td>{{ $row->role ?: '-' }}</td>
                <td>{{ $row->job_position ?: '-' }}</td>
                <td>{{ ($row->kode_cabang ?: '-') . ' - ' . ($row->nama_cabang ?: '-') }}</td>
                <td class="text-end fw-bold">{{ number_format($row->total_pengajuan) }}</td>
                <td class="text-end text-secondary fw-bold">{{ number_format($row->total_open) }}</td>
                <td class="text-end text-warning fw-bold">{{ number_format($row->total_follow_up) }}</td>
                <td class="text-end text-success fw-bold">{{ number_format($row->total_closing) }}</td>
                <td class="text-end text-danger fw-bold">{{ number_format($row->total_rejected) }}</td>
                <td class="text-center">
                  <button type="button"
                          class="btn btn-outline-primary btn-sm rounded-pill px-3"
                          wire:click="openDetailPegawai({{ $row->id }})">
                    <i class="bi bi-eye me-1"></i> Detail
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="12" class="text-center text-muted p-5">Belum ada data rekap prospek per pegawai.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      @endif

    </div>
  </div>

  @if($activeTab === 'pegawai')
    <div class="mt-3">
      {{ $items->links() }}
    </div>
  @endif

  @if($detailPegawai)
    <div class="modal fade" id="modalDetailPegawai" tabindex="-1" aria-hidden="true" wire:ignore.self>
      <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down" style="max-width: 1500px; width: 96vw;">
        <div class="modal-content border-0" style="border-radius:20px;overflow:hidden;">
          <div class="modal-header">
            <div>
              <h5 class="modal-title fw-bold mb-0">Detail Pengajuan Pegawai</h5>
              <div class="text-muted small">
                {{ $detailPegawai->name ?? '-' }}
                @if(!empty($detailPegawai->nama_lengkap))
                  • {{ $detailPegawai->nama_lengkap }}
                @endif
              </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <div class="card-soft p-3 mb-3"
                 data-mobile-filter-panel
                 data-mobile-filter-key="prospect-recap-detail-pegawai">
              <div class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                  <label class="form-label fw-semibold mb-1">Mode Tanggal</label>
                  <select class="form-select" wire:model.live="detailFilterMode">
                    <option value="all">Semua</option>
                    <option value="monthly">Bulanan</option>
                    <option value="range">Range Tanggal</option>
                  </select>
                </div>

                @if($detailFilterMode === 'monthly')
                  <div class="col-6 col-md-3">
                    <label class="form-label fw-semibold mb-1">Bulan</label>
                    <select class="form-select" wire:model.live="detailFilterBulan">
                      @foreach($bulanOptions as $b)
                        <option value="{{ $b['id'] }}">{{ $b['label'] }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-6 col-md-3">
                    <label class="form-label fw-semibold mb-1">Tahun</label>
                    <select class="form-select" wire:model.live="detailFilterTahun">
                      @foreach($tahunOptions as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                      @endforeach
                    </select>
                  </div>
                @elseif($detailFilterMode === 'range')
                  <div class="col-6 col-md-3">
                    <label class="form-label fw-semibold mb-1">Tanggal Awal</label>
                    <input type="date" class="form-control" wire:model.live="detailFilterTanggalAwal">
                  </div>

                  <div class="col-6 col-md-3">
                    <label class="form-label fw-semibold mb-1">Tanggal Akhir</label>
                    <input type="date" class="form-control" wire:model.live="detailFilterTanggalAkhir">
                  </div>
                @endif
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width:70px;">No</th>
                    <th style="min-width:140px;">Tanggal</th>
                    <th style="min-width:220px;">Nama Prospek</th>
                    <th style="min-width:140px;">Produk</th>
                    <th style="min-width:160px;">Jenis Usaha</th>
                    <th style="min-width:120px;">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($detailItems as $i => $row)
                    <tr>
                      <td>{{ $i + 1 }}</td>
                      <td>{{ \Carbon\Carbon::parse($row->tanggal_prospek)->format('d/m/Y') }}</td>
                      <td>{{ $row->nama ?: '-' }}</td>
                      <td>{{ $row->jenis_produk ?: '-' }}</td>
                      <td>{{ $row->jenis_usaha ?: '-' }}</td>
                      <td>{{ $row->status ?: '-' }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center text-muted p-4">Belum ada data detail pengajuan.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if($detailKcCabang)
    <div class="modal fade" id="modalDetailKc" tabindex="-1" aria-hidden="true" wire:ignore.self>
      <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-sm-down">
        <div class="modal-content border-0" style="border-radius:20px;overflow:hidden;">
          <div class="modal-header">
            <div>
              <h5 class="modal-title fw-bold mb-0">Detail Calon Nasabah Per KC</h5>
              <div class="text-muted small">
                {{ $detailKcCabang->kode_cabang ?? '-' }} - {{ $detailKcCabang->nama_cabang ?? '-' }}
                • {{ $detailKcStatusLabel }}
              </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <div class="card-soft p-3 mb-3"
                 data-mobile-filter-panel
                 data-mobile-filter-key="prospect-recap-detail-kc">
              <div class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                  <label class="form-label fw-semibold mb-1">Mode Tanggal</label>
                  <select class="form-select" wire:model.live="detailKcFilterMode">
                    <option value="all">Semua</option>
                    <option value="monthly">Bulanan</option>
                    <option value="range">Range Tanggal</option>
                  </select>
                </div>

                @if($detailKcFilterMode === 'monthly')
                  <div class="col-6 col-md-3">
                    <label class="form-label fw-semibold mb-1">Bulan</label>
                    <select class="form-select" wire:model.live="detailKcFilterBulan">
                      @foreach($bulanOptions as $b)
                        <option value="{{ $b['id'] }}">{{ $b['label'] }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-6 col-md-3">
                    <label class="form-label fw-semibold mb-1">Tahun</label>
                    <select class="form-select" wire:model.live="detailKcFilterTahun">
                      @foreach($tahunOptions as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                      @endforeach
                    </select>
                  </div>
                @elseif($detailKcFilterMode === 'range')
                  <div class="col-6 col-md-3">
                    <label class="form-label fw-semibold mb-1">Tanggal Awal</label>
                    <input type="date" class="form-control" wire:model.live="detailKcFilterTanggalAwal">
                  </div>

                  <div class="col-6 col-md-3">
                    <label class="form-label fw-semibold mb-1">Tanggal Akhir</label>
                    <input type="date" class="form-control" wire:model.live="detailKcFilterTanggalAkhir">
                  </div>
                @endif
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width:70px;">No</th>
                    <th style="min-width:140px;">Tanggal</th>
                    <th style="min-width:220px;">Nama Calon Nasabah</th>
                    <th style="min-width:150px;">No HP</th>
                    <th style="min-width:260px;">Alamat</th>
                    <th style="min-width:140px;">Produk</th>
                    <th style="min-width:160px;">Jenis Usaha</th>
                    <th style="min-width:120px;">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($detailKcItems as $i => $row)
                    <tr>
                      <td>{{ $i + 1 }}</td>
                      <td>{{ \Carbon\Carbon::parse($row->tanggal_prospek)->format('d/m/Y') }}</td>
                      <td>{{ $row->nama ?: '-' }}</td>
                      <td>{{ $row->no_hp ?: '-' }}</td>
                      <td>{{ $row->alamat ?: '-' }}</td>
                      <td>{{ $row->jenis_produk ?: '-' }}</td>
                      <td>{{ $row->jenis_usaha ?: '-' }}</td>
                      <td>{{ $row->status ?: '-' }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center text-muted p-4">Belum ada data calon nasabah untuk filter ini.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', function () {
    let detailPegawaiModalInstance = null;
    let detailKcModalInstance = null;

    function ensureDetailPegawaiModal() {
        const modalEl = document.getElementById('modalDetailPegawai');
        if (!modalEl || typeof bootstrap === 'undefined') return null;
        detailPegawaiModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        return detailPegawaiModalInstance;
    }

    function ensureDetailKcModal() {
        const modalEl = document.getElementById('modalDetailKc');
        if (!modalEl || typeof bootstrap === 'undefined') return null;
        detailKcModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        return detailKcModalInstance;
    }

    Livewire.on('open-detail-pegawai-modal', function () {
        const modal = ensureDetailPegawaiModal();
        if (modal) modal.show();
    });

    Livewire.on('open-detail-kc-modal', function () {
        const modal = ensureDetailKcModal();
        if (modal) modal.show();
    });

    document.addEventListener('hidden.bs.modal', function (event) {
        if (event.target && event.target.id === 'modalDetailPegawai') {
            Livewire.dispatch('closeDetailPegawaiModal');
        }
        if (event.target && event.target.id === 'modalDetailKc') {
            Livewire.dispatch('closeDetailKcModal');
        }
    });
});
</script>
@endpush
