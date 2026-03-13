<div class="container-fluid px-4 py-3">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-4">Master Cabang</div>
      <div class="text-muted">Kelola data cabang, status aktif, dan detail alamat</div>
    </div>

    <a href="{{ route('cabangs.create') }}" class="btn btn-primary rounded-pill px-4">
      <i class="bi bi-plus-circle me-1"></i> Tambah Cabang
    </a>
  </div>

  @if(session('ok'))
    <div class="alert alert-success rounded-4 shadow-sm">{{ session('ok') }}</div>
  @endif

  <div class="card-soft p-3 mb-3">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input class="form-control" style="border-left:0"
                 placeholder="Cari kode / nama / alamat..."
                 wire:model.live.debounce.200ms="search">
        </div>
      </div>
      <div class="col-12 col-md-6 text-md-end text-muted small">
        Total: <span class="fw-bold">{{ $items->total() }}</span> cabang
      </div>
    </div>
  </div>

  <div class="card-soft overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="min-width:120px;">Kode</th>
            <th style="min-width:240px;">Nama Cabang</th>
            <th>Alamat</th>
            <th style="width:110px;">Aktif</th>
            <th style="width:140px;" class="text-end">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($items as $c)
            <tr>
              <td class="fw-bold">{{ $c->kode_cabang }}</td>
              <td>
                <div class="fw-bold">{{ $c->nama_cabang }}</div>
                <div class="text-muted small">ID: {{ $c->id }}</div>
              </td>
              <td class="text-muted small">{{ $c->alamat ?? '-' }}</td>

              <td>
                <div class="form-check form-switch m-0">
                  <input class="form-check-input"
                         type="checkbox"
                         role="switch"
                         id="swc{{ $c->id }}"
                         @checked((int)$c->aktif===1)
                         wire:click="toggleAktif({{ $c->id }})">
                </div>
              </td>

              <td class="text-end text-nowrap">
                <a class="btn btn-outline-primary btn-sm rounded-pill px-3"
                   href="{{ route('cabangs.edit', $c->id) }}">
                  <i class="bi bi-pencil-square me-1"></i> Edit
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted p-5">
                Belum ada data cabang.
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

  <style>
    .form-switch .form-check-input{
      width:3.2rem;height:1.65rem;cursor:pointer;
    }
    .form-switch .form-check-input:checked{
      background-color:#22c55e;border-color:#22c55e;
    }
  </style>
</div>
