<div class="container-fluid px-0">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-3">Rekap Prospek</div>
      <div class="text-muted">Daftar pegawai / AO beserta jumlah pengajuan prospek bulan berjalan</div>
    </div>

    <button type="button"
            class="btn btn-success rounded-pill px-4"
            wire:click="exportExcel">
      <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
    </button>
  </div>

  <div class="card-soft p-3 mb-3">
    <div class="row g-2 align-items-end">
      <div class="col-12 col-md-3">
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
            Cabang otomatis mengikuti cabang supervisor.
          </div>
        @endif
      </div>

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

      <div class="col-12 col-md-5">
        <label class="form-label fw-semibold mb-1">Cari</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input class="form-control"
                 style="border-left:0"
                 placeholder="Cari username / nama / jabatan / cabang..."
                 wire:model.live.debounce.300ms="search">
        </div>
      </div>
    </div>
  </div>

  <div class="card-soft overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:70px;">No</th>

            <th style="min-width:160px;">
              <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                      wire:click="sortBy('name')">
                Username
                @if($sortField === 'name')
                  <i class="bi {{ $sortDirection === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                @else
                  <i class="bi bi-arrow-down-up text-muted"></i>
                @endif
              </button>
            </th>

            <th style="min-width:220px;">
              <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                      wire:click="sortBy('nama_lengkap')">
                Nama Lengkap
                @if($sortField === 'nama_lengkap')
                  <i class="bi {{ $sortDirection === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                @else
                  <i class="bi bi-arrow-down-up text-muted"></i>
                @endif
              </button>
            </th>

            <th style="min-width:130px;">
              <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                      wire:click="sortBy('role')">
                Role
                @if($sortField === 'role')
                  <i class="bi {{ $sortDirection === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                @else
                  <i class="bi bi-arrow-down-up text-muted"></i>
                @endif
              </button>
            </th>

            <th style="min-width:220px;">
              <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                      wire:click="sortBy('job_position')">
                Jabatan
                @if($sortField === 'job_position')
                  <i class="bi {{ $sortDirection === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                @else
                  <i class="bi bi-arrow-down-up text-muted"></i>
                @endif
              </button>
            </th>

            <th style="min-width:220px;">
              <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                      wire:click="sortBy('kode_cabang')">
                Cabang
                @if($sortField === 'kode_cabang')
                  <i class="bi {{ $sortDirection === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                @else
                  <i class="bi bi-arrow-down-up text-muted"></i>
                @endif
              </button>
            </th>

            <th class="text-end" style="min-width:150px;">
              <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                      wire:click="sortBy('total_pengajuan')">
                Jumlah Pengajuan
                @if($sortField === 'total_pengajuan')
                  <i class="bi {{ $sortDirection === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                @else
                  <i class="bi bi-arrow-down-up text-muted"></i>
                @endif
              </button>
            </th>

            <th class="text-end" style="min-width:120px;">
              <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                      wire:click="sortBy('total_follow_up')">
                Follow Up
                @if($sortField === 'total_follow_up')
                  <i class="bi {{ $sortDirection === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                @else
                  <i class="bi bi-arrow-down-up text-muted"></i>
                @endif
              </button>
            </th>

            <th class="text-end" style="min-width:120px;">
              <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                      wire:click="sortBy('total_closing')">
                Closing
                @if($sortField === 'total_closing')
                  <i class="bi {{ $sortDirection === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                @else
                  <i class="bi bi-arrow-down-up text-muted"></i>
                @endif
              </button>
            </th>

            <th class="text-end" style="min-width:120px;">
              <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                      wire:click="sortBy('total_rejected')">
                Rejected
                @if($sortField === 'total_rejected')
                  <i class="bi {{ $sortDirection === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                @else
                  <i class="bi bi-arrow-down-up text-muted"></i>
                @endif
              </button>
            </th>
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
              <td class="text-end text-warning fw-bold">{{ number_format($row->total_follow_up) }}</td>
              <td class="text-end text-success fw-bold">{{ number_format($row->total_closing) }}</td>
              <td class="text-end text-danger fw-bold">{{ number_format($row->total_rejected) }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center text-muted p-5">
                Belum ada data rekap prospek.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $items->links() }}
  </div>
</div>
