<div class="container-fluid px-0">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-4">{{ $id ? 'Edit User' : 'Tambah User' }}</div>
      <div class="text-muted">Isi data user dengan benar sesuai role & cabang</div>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-light rounded-pill px-4">
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

  {{-- ✅ Wrapper biar rata kiri-kanan dan tidak terlalu sempit --}}
  <div <div class="w-100">
    <div class="card-soft p-4 w-100">
      <div class="row g-3">

        <div class="col-12 col-md-6">
          <label class="form-label fw-semibold">Username / Name</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
            <input class="form-control" wire:model="name" placeholder="misal: admin">
          </div>
          @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label fw-semibold">Nama Lengkap</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-card-text"></i></span>
            <input class="form-control" wire:model="nama_lengkap" placeholder="optional">
          </div>
          @error('nama_lengkap')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-6">
        <label class="form-label fw-semibold">Job Posisi</label>
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-briefcase"></i></span>
            <input class="form-control" wire:model.defer="job_position" placeholder="contoh: Customer Service / Teller / AO">
        </div>
        @error('job_position')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label fw-semibold">Email</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
            <input class="form-control" wire:model="email" placeholder="email@domain.com">
          </div>
          @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label fw-semibold">Role</label>
            <select class="form-select" wire:model="role">
                <option value="ADMIN">ADMIN</option>
                <option value="MANAJEMEN">MANAJEMEN</option>
                <option value="SUPERVISOR">SUPERVISOR</option>
                <option value="AO">AO</option>
                <option value="PEGAWAI">PEGAWAI</option>
            </select>
          @error('role')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label fw-semibold">Status</label>
          <select class="form-select" wire:model="aktif">
            <option value="1">AKTIF</option>
            <option value="0">NONAKTIF</option>
          </select>
          @error('aktif')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Cabang (opsional)</label>
          <select class="form-select" wire:model="cabang_id">
            <option value="">- Tidak ada -</option>
            @foreach($cabangs as $c)
              <option value="{{ $c->id }}">{{ $c->kode_cabang }} - {{ $c->nama_cabang }}</option>
            @endforeach
          </select>
          @error('cabang_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          <div class="text-muted small mt-1">
            Untuk role CABANG/PEGAWAI/AO biasanya wajib pilih cabang.
          </div>
        </div>

        <div class="col-12">
          <div class="d-flex align-items-center justify-content-between mt-2">
            <div class="fw-bold">Password</div>
            @if($id)
              <div class="text-muted small">Kosongkan jika tidak diubah</div>
            @endif
          </div>
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" wire:model="password" placeholder="min 6 karakter">
          </div>
          @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label">Konfirmasi Password</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-shield-lock"></i></span>
            <input type="password" class="form-control" wire:model="password_confirmation" placeholder="ulang password">
          </div>
        </div>

        <div class="col-12 d-flex gap-2 mt-2">
          <button class="btn btn-primary rounded-pill px-4" wire:click.prevent="save">
            <i class="bi bi-save me-1"></i> Simpan
          </button>
          <a class="btn btn-light rounded-pill px-4" href="{{ route('users.index') }}">
            Batal
          </a>
        </div>

      </div>
    </div>
  </div>
</div>
