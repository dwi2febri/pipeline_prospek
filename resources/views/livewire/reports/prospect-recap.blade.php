<div class="container-fluid px-0">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-3">Rekap Prospek</div>
      <div class="text-muted">
        {{ $activeTab === 'kc'
            ? 'Rekap jumlah pengajuan prospek per kantor cabang 001 - 028'
            : 'Daftar pegawai / AO beserta jumlah pengajuan prospek bulan berjalan' }}
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
    </div>
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
                 placeholder="{{ $activeTab === 'kc' ? 'Cari kode cabang / nama cabang...' : 'Cari username / nama / jabatan / cabang...' }}"
                 wire:model.live.debounce.300ms="search">
        </div>
      </div>
    </div>
  </div>

  <div class="card-soft overflow-hidden">
    <div class="table-responsive">

      @if($activeTab === 'kc')
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:70px;">No</th>

              <th style="min-width:150px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('kode_cabang')">
                  Kode Cabang
                  @if($sortFieldKc === 'kode_cabang')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th style="min-width:260px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('nama_cabang')">
                  Nama Cabang
                  @if($sortFieldKc === 'nama_cabang')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th class="text-end" style="min-width:150px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('total_pengajuan')">
                  Jumlah Pengajuan
                  @if($sortFieldKc === 'total_pengajuan')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th class="text-end" style="min-width:120px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('total_open')">
                  Open
                  @if($sortFieldKc === 'total_open')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th class="text-end" style="min-width:120px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('total_follow_up')">
                  Follow Up
                  @if($sortFieldKc === 'total_follow_up')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th class="text-end" style="min-width:120px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('total_closing')">
                  Closing
                  @if($sortFieldKc === 'total_closing')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th class="text-end" style="min-width:120px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('total_rejected')">
                  Rejected
                  @if($sortFieldKc === 'total_rejected')
                    <i class="bi {{ $sortDirectionKc === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
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
                <td class="fw-semibold">{{ $row->kode_cabang }}</td>
                <td>{{ $row->nama_cabang }}</td>
                <td class="text-end fw-bold">{{ number_format($row->total_pengajuan) }}</td>
                <td class="text-end text-secondary fw-bold">{{ number_format($row->total_open) }}</td>
                <td class="text-end text-warning fw-bold">{{ number_format($row->total_follow_up) }}</td>
                <td class="text-end text-success fw-bold">{{ number_format($row->total_closing) }}</td>
                <td class="text-end text-danger fw-bold">{{ number_format($row->total_rejected) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted p-5">
                  Belum ada data rekap prospek per KC.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      @else
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:70px;">No</th>

              <th style="min-width:160px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('name')">
                  Username
                  @if($sortFieldPegawai === 'name')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th style="min-width:220px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('nama_lengkap')">
                  Nama Lengkap
                  @if($sortFieldPegawai === 'nama_lengkap')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th style="min-width:130px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('role')">
                  Role
                  @if($sortFieldPegawai === 'role')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th style="min-width:220px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('job_position')">
                  Jabatan
                  @if($sortFieldPegawai === 'job_position')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th style="min-width:220px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('kode_cabang')">
                  Cabang
                  @if($sortFieldPegawai === 'kode_cabang')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th class="text-end" style="min-width:150px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('total_pengajuan')">
                  Jumlah Pengajuan
                  @if($sortFieldPegawai === 'total_pengajuan')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th class="text-end" style="min-width:120px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('total_open')">
                  Open
                  @if($sortFieldPegawai === 'total_open')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th class="text-end" style="min-width:120px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('total_follow_up')">
                  Follow Up
                  @if($sortFieldPegawai === 'total_follow_up')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th class="text-end" style="min-width:120px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('total_closing')">
                  Closing
                  @if($sortFieldPegawai === 'total_closing')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
              </th>

              <th class="text-end" style="min-width:120px;">
                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-dark"
                        wire:click="sortBy('total_rejected')">
                  Rejected
                  @if($sortFieldPegawai === 'total_rejected')
                    <i class="bi {{ $sortDirectionPegawai === 'asc' ? 'bi-sort-up' : 'bi-sort-down' }}"></i>
                  @else
                    <i class="bi bi-arrow-down-up text-muted"></i>
                  @endif
                </button>
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
                <td colspan="12" class="text-center text-muted p-5">
                  Belum ada data rekap prospek per pegawai.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      @endif

    </div>
  </div>

  <div class="mt-3">
    {{ $items->links() }}
  </div>

  <div class="modal fade" id="modalDetailPegawai" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-sm-down">
      <div class="modal-content border-0" style="border-radius:20px;overflow:hidden;">
        <div class="modal-header">
          <div>
            <h5 class="modal-title fw-bold mb-0">Detail Pengajuan Pegawai</h5>
            <div class="text-muted small">
              {{ $detailPegawaiUser?->name ?: '-' }}
              @if(!empty($detailPegawaiUser?->nama_lengkap))
                • {{ $detailPegawaiUser->nama_lengkap }}
              @endif
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="card-soft p-3 mb-3">
            <div class="row g-2 align-items-end">
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

              <div class="col-12 col-md-6 text-md-end text-muted small">
                Total detail:
                <span class="fw-bold">{{ $detailItems->count() }}</span> pengajuan
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width:70px;">No</th>
                  <th style="min-width:140px;">Tanggal</th>
                  <th style="min-width:220px;">Nama Prospek</th>
                  <th style="min-width:160px;">Jenis Produk</th>
                  <th style="min-width:180px;">Jenis Usaha</th>
                  <th style="min-width:140px;">Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse($detailItems as $i => $d)
                  <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($d->tanggal_prospek)->format('d/m/Y') }}</td>
                    <td>{{ $d->nama ?: '-' }}</td>
                    <td>{{ $d->jenis_produk ?: '-' }}</td>
                    <td>{{ $d->jenis_usaha ?: '-' }}</td>
                    <td>
                      @php
                        $badgeClass = 'bg-secondary';
                        if($d->status === 'OPEN') $badgeClass = 'bg-light text-dark';
                        elseif($d->status === 'FOLLOW UP') $badgeClass = 'bg-warning text-dark';
                        elseif($d->status === 'CLOSING') $badgeClass = 'bg-success';
                        elseif($d->status === 'REJECTED') $badgeClass = 'bg-danger';
                      @endphp
                      <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                        {{ $d->status ?: '-' }}
                      </span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted p-5">
                      Tidak ada data pengajuan pada periode ini.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('livewire:init', function () {
      let detailModalInstance = null;

      function getDetailModal() {
        const modalEl = document.getElementById('modalDetailPegawai');
        if (!modalEl || typeof bootstrap === 'undefined') return null;
        if (!detailModalInstance) {
          detailModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl, {
            backdrop: true,
            keyboard: true
          });
        }
        return detailModalInstance;
      }

      Livewire.on('open-detail-pegawai-modal', function () {
        const modal = getDetailModal();
        if (modal) modal.show();
      });

      Livewire.on('close-detail-pegawai-modal', function () {
        const modal = getDetailModal();
        if (modal) modal.hide();
      });

      const modalEl = document.getElementById('modalDetailPegawai');
      if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
          if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
            window.Livewire.dispatch('closeDetailPegawaiModal');
          }
        });
      }
    });
  </script>
</div>
