<div class="container-fluid px-0">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-4">{{ $id ? 'Edit Cabang' : 'Tambah Cabang' }}</div>
      <div class="text-muted">Isi data cabang dengan benar</div>
    </div>

    <a href="{{ route('cabangs.index') }}" class="btn btn-light rounded-pill px-4">
      <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger rounded-4 shadow-sm">
      <div class="fw-bold mb-1">Validasi gagal</div>
      <ul class="mb-0 small">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="mx-auto" style="max-width: 1400px;">
    <div class="card-soft p-4">
      <div class="row g-3">

        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">Kode Cabang</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-hash"></i></span>
            <input class="form-control" wire:model="kode_cabang" placeholder="misal: 001">
          </div>
          @error('kode_cabang')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label fw-semibold">Nama Cabang</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-building"></i></span>
            <input class="form-control" wire:model="nama_cabang" placeholder="misal: KC Utama">
          </div>
          @error('nama_cabang')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-2">
          <label class="form-label fw-semibold">Status</label>
          <select class="form-select" wire:model="aktif">
            <option value="1">AKTIF</option>
            <option value="0">NONAKTIF</option>
          </select>
          @error('aktif')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Alamat (opsional)</label>
          <textarea class="form-control" rows="3" wire:model="alamat"
                    placeholder="Alamat cabang..."></textarea>
          @error('alamat')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 d-flex gap-2 mt-2">
          <button class="btn btn-primary rounded-pill px-4" wire:click.prevent="save">
            <i class="bi bi-save me-1"></i> Simpan
          </button>
          <a class="btn btn-light rounded-pill px-4" href="{{ route('cabangs.index') }}">
            Batal
          </a>
        </div>

      </div>
    </div>
  </div>
</div>
