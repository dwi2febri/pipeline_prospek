<div class="container-fluid px-0">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-3">Recycle Bin - Prospek</div>
      <div class="text-muted">Data prospek yang sudah dihapus (soft delete)</div>
    </div>

    <a href="{{ route('prospects.index') }}" class="btn btn-light rounded-pill px-4">
      <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  @if(session('ok'))
    <div class="alert alert-success rounded-4 shadow-sm">
      <i class="bi bi-check-circle me-1"></i> {{ session('ok') }}
    </div>
  @endif

  <div class="card-soft p-3 mb-3">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md-7">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input class="form-control" style="border-left:0"
                 placeholder="Cari nama / no hp / nik..."
                 wire:model.debounce.400ms="search">
        </div>
      </div>
      <div class="col-12 col-md-5 text-md-end text-muted small">
        Total: <span class="fw-bold">{{ $items->total() }}</span> prospek (terhapus)
      </div>
    </div>
  </div>

  <div class="card-soft overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="min-width:140px;">Tanggal</th>
            <th style="min-width:260px;">Prospek</th>
            <th style="min-width:160px;">No HP</th>
            <th style="min-width:220px;">Cabang</th>
            <th style="min-width:170px;">Dihapus</th>
            <th style="width:210px;" class="text-end">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($items as $p)
            <tr>
              <td class="small">
                {{ \Illuminate\Support\Carbon::parse($p->tanggal_prospek)->format('d/m/Y') }}
              </td>

              <td>
                <div class="fw-bold">{{ $p->nama }}</div>
                <div class="text-muted small">
                  NIK: {{ $p->nik ?: '-' }}
                  <span class="mx-1">•</span>
                  Input: {{ $p->creator->name ?? '-' }}
                </div>
              </td>

              <td class="small">{{ $p->no_hp ?: '-' }}</td>

              <td class="small">
                {{ $p->cabang ? ($p->cabang->kode_cabang.' - '.$p->cabang->nama_cabang) : '-' }}
              </td>

              <td class="small text-muted">
                {{ optional($p->deleted_at)->format('d/m/Y H:i') }}
              </td>

              <td class="text-end text-nowrap">
                <button type="button"
                        class="btn btn-success btn-sm rounded-pill px-3"
                        wire:click="restore({{ $p->id }})">
                  <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                </button>

                <button type="button"
                        class="btn btn-outline-danger btn-sm rounded-pill px-3"
                        onclick="if(!confirm('Hapus permanen? Data tidak bisa dikembalikan.')) return false;"
                        wire:click="forceDelete({{ $p->id }})">
                  <i class="bi bi-trash3 me-1"></i> Hapus
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted p-5">
                Recycle bin kosong.
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
