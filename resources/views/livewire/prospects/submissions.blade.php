<div class="container-fluid px-0">

  @if(session('ok'))
    <div class="alert alert-success rounded-4 shadow-sm">
      {{ session('ok') }}
    </div>
  @endif

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-3">Prospek Diajukan</div>
      <div class="text-muted">Daftar prospek yang diajukan oleh pegawai / AO</div>
    </div>
  </div>

  <div class="card-soft p-3 mb-3">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md-5">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input class="form-control"
                 style="border-left:0"
                 placeholder="Cari nama / no hp / nik / status..."
                 wire:model.live.debounce.300ms="search">
        </div>
      </div>

      <div class="col-12 col-md-3">
        <select class="form-select" wire:model.live="filterStatus">
          <option value="">-- Semua Status --</option>
          <option value="FOLLOW UP">FOLLOW UP</option>
          <option value="CLOSING">CLOSING</option>
          <option value="REJECTED">REJECTED</option>
        </select>
      </div>

      <div class="col-12 col-md-4 text-md-end text-muted small">
        Total: <span class="fw-bold">{{ $items->total() }}</span> pengajuan
      </div>
    </div>
  </div>

  <div class="card-soft overflow-hidden d-none d-md-block">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="min-width:140px;">Tanggal</th>
            <th style="min-width:240px;">Prospek</th>
            <th style="min-width:180px;">Pengaju</th>
            <th style="min-width:220px;">Cabang</th>
            <th style="min-width:140px;">Status</th>
            <th style="width:120px;" class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $p)
            @php
              $badgeClass = 'bg-secondary';
              if($p->status === 'FOLLOW UP') $badgeClass = 'bg-warning text-dark';
              elseif($p->status === 'CLOSING') $badgeClass = 'bg-success';
              elseif($p->status === 'REJECTED') $badgeClass = 'bg-danger';
            @endphp
            <tr>
              <td class="small">
                {{ \Illuminate\Support\Carbon::parse($p->tanggal_prospek)->format('d/m/Y') }}
              </td>
              <td>
                <div class="fw-bold">{{ $p->nama }}</div>
                <div class="text-muted small">
                  {{ $p->no_hp ?: '-' }} <span class="mx-1">•</span> NIK: {{ $p->nik ?: '-' }}
                </div>
              </td>
              <td class="small">
                <div class="fw-semibold">{{ $p->creator->name ?? '-' }}</div>
                <div class="text-muted">{{ $p->creator->nama_lengkap ?? '-' }}</div>
              </td>
              <td class="small">
                {{ $p->cabang ? ($p->cabang->kode_cabang.' - '.$p->cabang->nama_cabang) : '-' }}
              </td>
              <td>
                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                  {{ $p->status ?: '-' }}
                </span>
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
              <td colspan="6" class="text-center text-muted p-5">
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
        $badgeClass = 'bg-secondary';
        if($p->status === 'FOLLOW UP') $badgeClass = 'bg-warning text-dark';
        elseif($p->status === 'CLOSING') $badgeClass = 'bg-success';
        elseif($p->status === 'REJECTED') $badgeClass = 'bg-danger';
      @endphp

      <div class="card-soft p-3 mb-2">
        <div class="fw-bold">{{ $p->nama }}</div>

        <div class="text-muted small mt-1">
          <i class="bi bi-telephone"></i> {{ $p->no_hp ?: '-' }}
          &nbsp;•&nbsp;
          <i class="bi bi-person-vcard"></i> {{ $p->nik ?: '-' }}
        </div>

        <div class="text-muted small mt-1">
          <i class="bi bi-calendar-event"></i>
          {{ \Illuminate\Support\Carbon::parse($p->tanggal_prospek)->format('d/m/Y') }}
          &nbsp;•&nbsp;
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

        <div class="mt-2">
          <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2 fw-bold">
            {{ $p->status ?: '-' }}
          </span>
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
      <div class="card-soft p-4 text-center text-muted">
        Belum ada pengajuan prospek dari pegawai / AO.
      </div>
    @endforelse
  </div>

  <div class="mt-3">
    {{ $items->links() }}
  </div>

  <div wire:ignore.self class="modal fade" id="prospectDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-fullscreen-sm-down">
      <div class="modal-content border-0" style="border-radius:20px; overflow:hidden;">
        <div class="modal-header">
          <div>
            <h5 class="modal-title fw-bold mb-0">Detail Prospek Diajukan</h5>
            <div class="text-muted small">ID Prospek: {{ $detail->id ?? '-' }}</div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          @if($detail)
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <div class="small text-muted">Nama Prospek</div>
                <div class="fw-semibold">{{ $detail->nama }}</div>
              </div>

              <div class="col-12 col-md-6">
                <div class="small text-muted">Tanggal Prospek</div>
                <div class="fw-semibold">{{ \Illuminate\Support\Carbon::parse($detail->tanggal_prospek)->format('d/m/Y') }}</div>
              </div>

              <div class="col-12 col-md-6">
                <div class="small text-muted">No HP</div>
                <div class="fw-semibold">{{ $detail->no_hp ?: '-' }}</div>
              </div>

              <div class="col-12 col-md-6">
                <div class="small text-muted">NIK</div>
                <div class="fw-semibold">{{ $detail->nik ?: '-' }}</div>
              </div>

              <div class="col-12 col-md-6">
                <div class="small text-muted">Pengaju</div>
                <div class="fw-semibold">{{ $detail->creator->name ?? '-' }}</div>
                <div class="text-muted small">{{ $detail->creator->nama_lengkap ?? '-' }}</div>
              </div>

              <div class="col-12 col-md-6">
                <div class="small text-muted">Cabang</div>
                <div class="fw-semibold">
                  {{ $detail->cabang ? ($detail->cabang->kode_cabang.' - '.$detail->cabang->nama_cabang) : '-' }}
                </div>
              </div>

              <div class="col-12 col-md-6">
                <div class="small text-muted">Jenis Produk</div>
                <div class="fw-semibold">{{ $detail->jenis_produk ?: '-' }}</div>
              </div>

              <div class="col-12 col-md-6">
                <div class="small text-muted">Status Saat Ini</div>
                <div class="fw-semibold">{{ $detail->status ?: '-' }}</div>
              </div>

              <div class="col-12">
                <div class="small text-muted">Alamat</div>
                <div class="fw-semibold">{{ $detail->alamat ?: '-' }}</div>
              </div>

              <div class="col-12">
                <div class="small text-muted">Keterangan Usaha</div>
                <div class="fw-semibold">{{ $detail->keterangan_usaha ?: '-' }}</div>
              </div>

              <div class="col-12">
                <div class="small text-muted">Catatan</div>
                <div class="fw-semibold">{{ $detail->catatan ?: '-' }}</div>
              </div>

              <div class="col-12 col-md-6">
                <div class="small text-muted">Latitude</div>
                <div class="fw-semibold">{{ $detail->lokasi_lat ?: '-' }}</div>
              </div>

              <div class="col-12 col-md-6">
                <div class="small text-muted">Longitude</div>
                <div class="fw-semibold">{{ $detail->lokasi_lng ?: '-' }}</div>
              </div>

              <div class="col-12">
                <hr>
                <div class="fw-semibold mb-2">Foto / Dokumen</div>

                @if($detail->documents && $detail->documents->count())
                  <div class="row g-2">
                    @foreach($detail->documents as $doc)
                      <div class="col-6 col-md-4">
                        <div class="border rounded-4 p-2 h-100">
                          @php
                            $path = $doc->file_path ?? '';
                            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                          @endphp

                          @if($isImage)
                            <a href="{{ $doc->url }}" target="_blank">
                              <img src="{{ $doc->url }}"
                                   class="img-fluid rounded-3"
                                   style="width:100%;height:180px;object-fit:cover;">
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

              <div class="col-12">
                <hr>
                <div class="fw-semibold mb-2">Update Status (opsional)</div>

                <div class="row g-2 align-items-end">
                  <div class="col-12 col-md-7">
                    <label class="form-label small text-muted">Pilih Status</label>
                    <select class="form-select" wire:model.live="statusUpdate">
                      <option value="">-- Pilih Status --</option>
                      <option value="FOLLOW UP">FOLLOW UP</option>
                      <option value="CLOSING">CLOSING</option>
                      <option value="REJECTED">REJECTED</option>
                    </select>
                    @error('statusUpdate')
                      <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-12 col-md-5">
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
<script>
document.addEventListener('livewire:init', function () {
    function setupProspectModal() {
        const modalEl = document.getElementById('prospectDetailModal');
        if (!modalEl || typeof bootstrap === 'undefined') return;

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl, {
            backdrop: true,
            keyboard: true
        });

        if (!modalEl.dataset.boundHidden) {
            modalEl.dataset.boundHidden = '1';

            modalEl.addEventListener('hidden.bs.modal', function () {
                Livewire.dispatch('forceCloseProspectDetailModal');
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
            });
        }
    }

    setupProspectModal();

    document.addEventListener('livewire:navigated', setupProspectModal);
});
</script>
@endpush
