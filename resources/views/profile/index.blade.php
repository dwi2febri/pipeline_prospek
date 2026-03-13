@component('layouts.bootstrap')
<div class="container-fluid px-0 profile-page">
  @if(session('ok'))
    <div class="alert alert-success rounded-4 shadow-sm">
      {{ session('ok') }}
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger rounded-4 shadow-sm">
      <div class="fw-bold mb-1">Validasi gagal</div>
      <ul class="mb-0 small">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-3">Profil User</div>
      <div class="text-muted">Informasi akun dan ganti password</div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-4">
      <div class="card-soft p-4 h-100">
        <div class="text-center">
          <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
               style="width:92px;height:92px;border-radius:999px;background:#eef3ff;color:#1f3fbf;font-size:2rem;font-weight:900;">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
          </div>

          <div class="fw-bold fs-4">{{ auth()->user()->name ?? '-' }}</div>
          <div class="text-muted">{{ auth()->user()->nama_lengkap ?? '-' }}</div>

          <div class="mt-3">
            <span class="badge bg-primary rounded-pill px-3 py-2">
              {{ strtoupper(auth()->user()->role ?? '-') }}
            </span>
          </div>
        </div>

        <hr>

        <div class="small text-muted">Email</div>
        <div class="fw-semibold mb-3">{{ auth()->user()->email ?? '-' }}</div>

        <div class="small text-muted">Jabatan</div>
        <div class="fw-semibold mb-3">{{ auth()->user()->job_position ?? '-' }}</div>

        <div class="small text-muted">Cabang</div>
        <div class="fw-semibold">{{ $cabangName ?? '-' }}</div>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card-soft p-4">
        <div class="fw-bold fs-5 mb-3">Ganti Password</div>

        <form method="POST" action="{{ route('profile.password.update') }}">
          @csrf

          <div class="mb-3">
            <label class="form-label fw-semibold">Password Lama</label>
            <input type="password"
                   name="current_password"
                   class="form-control"
                   placeholder="Masukkan password lama">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Password Baru</label>
            <input type="password"
                   name="password"
                   class="form-control"
                   placeholder="Masukkan password baru">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
            <input type="password"
                   name="password_confirmation"
                   class="form-control"
                   placeholder="Ulangi password baru">
          </div>

          <div class="d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
              <i class="bi bi-key me-1"></i> Simpan Password
            </button>

            <a href="/prospects" class="btn btn-light rounded-pill px-4">
              Kembali
            </a>
          </div>
        </form>
      </div>

      <div class="card-soft p-4 mt-3">
        <div class="fw-bold fs-5 mb-3">Informasi Akun</div>

        <div class="row g-3">
          <div class="col-12 col-md-6">
            <div class="text-muted small">Username</div>
            <div class="fw-semibold">{{ auth()->user()->name ?? '-' }}</div>
          </div>

          <div class="col-12 col-md-6">
            <div class="text-muted small">Nama Lengkap</div>
            <div class="fw-semibold">{{ auth()->user()->nama_lengkap ?? '-' }}</div>
          </div>

          <div class="col-12 col-md-6">
            <div class="text-muted small">Email</div>
            <div class="fw-semibold">{{ auth()->user()->email ?? '-' }}</div>
          </div>

          <div class="col-12 col-md-6">
            <div class="text-muted small">Role</div>
            <div class="fw-semibold">{{ strtoupper(auth()->user()->role ?? '-') }}</div>
          </div>

          <div class="col-12 col-md-6">
            <div class="text-muted small">Jabatan</div>
            <div class="fw-semibold">{{ auth()->user()->job_position ?? '-' }}</div>
          </div>

          <div class="col-12 col-md-6">
            <div class="text-muted small">Cabang</div>
            <div class="fw-semibold">{{ $cabangName ?? '-' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div style="height:24px;"></div>
</div>
@endcomponent
