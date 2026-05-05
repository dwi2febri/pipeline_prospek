<div class="container-fluid px-0">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-4">{{ $id ? 'Edit User' : 'Tambah User' }}</div>
      <div class="text-muted">Isi data user dengan benar sesuai role, cabang, dan struktur organisasi</div>
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

  <div class="w-100">
    <div class="card-soft p-4 w-100">
      <div class="row g-3">

        <div class="col-12 col-md-6">
          <label class="form-label fw-semibold">Username / Name</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
            <input class="form-control" wire:model="name" placeholder="misal: admin / 111-021">
          </div>
          @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label fw-semibold">Kode</label>
          <input class="form-control" wire:model="kode" placeholder="misal: 001">
          @error('kode')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label fw-semibold">Employee ID</label>
          <input class="form-control" wire:model="employee_id" placeholder="misal: 111-021">
          @error('employee_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label fw-semibold">Nama Lengkap</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-card-text"></i></span>
            <input class="form-control" wire:model="nama_lengkap" placeholder="nama lengkap">
          </div>
          @error('nama_lengkap')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label fw-semibold">Email</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
            <input class="form-control" wire:model="email" placeholder="email@domain.com">
          </div>
          @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label fw-semibold">Job Position</label>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-briefcase"></i></span>
            <input class="form-control" wire:model.defer="job_position" placeholder="contoh: Customer Service / AO Kredit / Kepala Kantor Wilayah">
          </div>
          @error('job_position')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label fw-semibold">Level</label>
          <input class="form-control" wire:model="level" placeholder="contoh: Kepala Bidang">
          @error('level')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label fw-semibold">Group Jabatan</label>
          <input class="form-control" wire:model="group_jabatan" placeholder="contoh: PS">
          @error('group_jabatan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label fw-semibold">Branch Name</label>
          <input class="form-control" wire:model="branch_name" placeholder="contoh: Kc. Utama / Kantor Wilayah Semarang">
          @error('branch_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label fw-semibold">Unit Kerja</label>
          <input class="form-control" wire:model="unit_kerja" placeholder="contoh: Kantor Cabang / Area Kantor Wilayah">
          @error('unit_kerja')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">Role</label>
          <select class="form-select" wire:model="role">
            <option value="ADMIN">ADMIN</option>
            <option value="MANAJEMEN">MANAJEMEN</option>
            <option value="MANAJEMEN KANWIL">MANAJEMEN KANWIL</option>
            <option value="SUPERVISOR">SUPERVISOR</option>
            <option value="AO">AO</option>
            <option value="PEGAWAI">PEGAWAI</option>
          </select>
          @error('role')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          <div class="text-muted small mt-1">
            Role akan mengikuti logika sistem saat simpan/import.
          </div>
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">Status</label>
          <select class="form-select" wire:model="aktif">
            <option value="1">AKTIF</option>
            <option value="0">NONAKTIF</option>
          </select>
          @error('aktif')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">Cabang</label>
          <select class="form-select" wire:model="cabang_id">
            <option value="">- Tidak ada -</option>
            @foreach($cabangs as $c)
              <option value="{{ $c->id }}">{{ $c->kode_cabang }} - {{ $c->nama_cabang }}</option>
            @endforeach
          </select>
          @error('cabang_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
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
