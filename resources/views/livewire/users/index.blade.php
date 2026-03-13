<div class="container-fluid px-0">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-3">Manajemen User</div>
      <div class="text-muted">Kelola akun admin / manajemen / supervisor / AO / pegawai</div>
    </div>

    <div class="d-flex flex-wrap gap-2">
      <a href="{{ route('users.template') }}" class="btn btn-light rounded-pill px-4">
        <i class="bi bi-download me-1"></i> Template CSV
      </a>

      <button type="button" class="btn btn-outline-primary rounded-pill px-4"
              data-bs-toggle="modal" data-bs-target="#modalImportUsers">
        <i class="bi bi-upload me-1"></i> Upload CSV
      </button>

      <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-circle me-1"></i> Tambah User
      </a>
    </div>
  </div>

  @if(session('ok'))
    <div class="alert alert-success rounded-4 shadow-sm">
      {{ session('ok') }}
    </div>
  @endif

  <div class="card-soft p-3 mb-3">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md-7">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input class="form-control"
                 style="border-left:0"
                 placeholder="Cari nama / email / role..."
                 wire:model.live.debounce.200ms="search">
        </div>
      </div>
      <div class="col-12 col-md-5 text-md-end text-muted small">
        Total: <span class="fw-bold">{{ $items->total() }}</span> user
      </div>
    </div>
  </div>

  <div class="card-soft overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="min-width:260px;">User</th>
            <th style="min-width:220px;">Email</th>
            <th style="min-width:160px;">Role</th>
            <th style="min-width:220px;">Cabang</th>
            <th style="width:110px;">Aktif</th>
            <th style="width:140px;" class="text-end">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($items as $u)
            @php
              $role = strtoupper((string)$u->role);
              $roleBadge = 'bg-dark';
              if($role==='ADMIN') $roleBadge = 'bg-danger';
              if($role==='MANAJEMEN') $roleBadge = 'bg-dark';
              if($role==='SUPERVISOR') $roleBadge = 'bg-warning text-dark';
              if($role==='AO') $roleBadge = 'bg-primary';
              if($role==='PEGAWAI') $roleBadge = 'bg-secondary';
            @endphp

            <tr>
              <td>
                <div class="d-flex align-items-center gap-3">
                  <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                       style="width:44px;height:44px;background:#eef2ff;font-weight:900;">
                    {{ strtoupper(substr(trim($u->name ?? 'U'),0,1)) }}
                  </div>
                  <div>
                    <div class="fw-bold">{{ $u->name }}</div>
                    <div class="text-muted small">{{ $u->nama_lengkap ?? '-' }}</div>
                  </div>
                </div>
              </td>

              <td class="small">{{ $u->email }}</td>

              <td>
                <span class="badge {{ $roleBadge }} rounded-pill px-3 py-2">
                  {{ $role }}
                </span>
              </td>

              <td class="small">
                {{ $u->cabang ? ($u->cabang->kode_cabang.' - '.$u->cabang->nama_cabang) : '-' }}
              </td>

              <td>
                <div class="form-check form-switch m-0">
                  <input class="form-check-input"
                         type="checkbox"
                         role="switch"
                         id="sw{{ $u->id }}"
                         @checked((int)$u->aktif===1)
                         wire:click="toggleAktif({{ $u->id }})">
                </div>
              </td>

              <td class="text-end text-nowrap">
                <a class="btn btn-outline-primary btn-sm rounded-pill px-3"
                   href="{{ route('users.edit',$u->id) }}">
                  <i class="bi bi-pencil-square me-1"></i> Edit
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted p-5">
                Belum ada user.
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

  <!-- Modal Import CSV Users -->
  <div class="modal fade" id="modalImportUsers" tabindex="-1" aria-labelledby="modalImportUsersLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0" style="border-radius:20px; overflow:hidden;">
        <div class="modal-header bg-white">
          <div>
            <h5 class="modal-title fw-bold mb-0" id="modalImportUsersLabel">Upload CSV User</h5>
            <div class="text-muted small">Import data user dari file CSV</div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="alert alert-light border rounded-4 mb-3">
            <div class="fw-semibold mb-1">Format CSV</div>
            <div class="small text-muted">
              Header: <code>username;password;nama_lengkap;role;id_cabang;job_posisi</code><br>
              Role valid: <b>ADMIN</b>, <b>MANAJEMEN</b>, <b>SUPERVISOR</b>, <b>AO</b>, <b>PEGAWAI</b><br>
              Jika username / nama_lengkap sudah ada, data akan <b>diupdate</b>.
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Pilih File CSV</label>
            <input type="file" class="form-control" wire:model="file" accept=".csv,.txt">
            @error('file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
          </div>

          <div wire:loading wire:target="file" class="small text-muted">
            Membaca file...
          </div>

          <div class="d-flex gap-2">
            <a href="{{ route('users.template') }}" class="btn btn-light rounded-pill px-4">
              <i class="bi bi-download me-1"></i> Download Template
            </a>
          </div>
        </div>

        <div class="modal-footer bg-white">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
            Batal
          </button>

          <button type="button" class="btn btn-primary rounded-pill px-4"
                  wire:click="importCsv"
                  wire:loading.attr="disabled"
                  wire:target="importCsv,file">
            <span wire:loading.remove wire:target="importCsv">Import Sekarang</span>
            <span wire:loading wire:target="importCsv">Mengimpor...</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <style>
    .form-switch .form-check-input{
      width:3.2rem;
      height:1.65rem;
      cursor:pointer;
    }
    .form-switch .form-check-input:checked{
      background-color:#22c55e;
      border-color:#22c55e;
    }
  </style>

  <script>
    document.addEventListener('livewire:init', function () {
      Livewire.on('closeImportUsersModal', function () {
        const modalEl = document.getElementById('modalImportUsers');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
      });
    });
  </script>
</div>
