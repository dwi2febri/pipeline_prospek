<div class="container-fluid px-0">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-3">
        {{ $activeTab === 'simpeg' ? 'Manajemen User SIMPEG' : 'Manajemen User Pipeline Prospek' }}
      </div>
      <div class="text-muted">
        {{ $activeTab === 'simpeg'
          ? 'Data pegawai dan jabatan aktif langsung dari database SIMPEG.'
          : 'Kelola akun admin / manajemen / manajemen kanwil / supervisor / AO / pegawai.' }}
      </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
      @if($activeTab === 'local')
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
      @else
        <button type="button" class="btn btn-primary rounded-pill px-4"
                onclick="startSimpegGenerate()">
          <i class="bi bi-arrow-repeat me-1"></i> Generate ke Pipeline
        </button>
      @endif
    </div>
  </div>

  @if(session('ok'))
    <div class="alert alert-success rounded-4 shadow-sm">{{ session('ok') }}</div>
  @endif

  <div class="user-source-tabs card-soft p-2 mb-3 d-inline-flex gap-2" role="tablist">
    <button type="button"
            class="btn rounded-pill px-4 {{ $activeTab === 'local' ? 'btn-primary' : 'btn-light' }}"
            wire:click="setActiveTab('local')">
      <i class="bi bi-people me-1"></i> Manajemen User Pipeline
    </button>
    <button type="button"
            class="btn rounded-pill px-4 {{ $activeTab === 'simpeg' ? 'btn-primary' : 'btn-light' }}"
            wire:click="setActiveTab('simpeg')">
      <i class="bi bi-database me-1"></i> Manajemen User SIMPEG
    </button>
  </div>

  @if($activeTab === 'local')
    <div class="card-soft p-3 mb-3"
         data-mobile-filter-panel
         data-mobile-filter-key="users-local">
      <div class="row g-2 align-items-center">
        <div class="col-12 col-lg-3" data-mobile-filter-primary>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input class="form-control border-start-0"
                   placeholder="Cari nama / email / role / employee id..."
                   wire:model.live.debounce.300ms="search">
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-2">
          <select class="form-select" wire:model.live="filterCabang">
            <option value="">Semua Cabang</option>
            @foreach($cabangs as $c)
              <option value="{{ $c->id }}">{{ $c->kode_cabang }} - {{ $c->nama_cabang }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 col-sm-6 col-lg-2">
          <select class="form-select" wire:model.live="filterRole">
            <option value="">Semua Role</option>
            @foreach(['ADMIN','MANAJEMEN','MANAJEMEN KANWIL','SUPERVISOR','AO','PEGAWAI'] as $role)
              <option value="{{ $role }}">{{ $role }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 col-sm-6 col-lg-2">
          <select class="form-select" wire:model.live="filterAktif">
            <option value="">Semua Status</option>
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
          </select>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 d-grid">
          <button type="button" class="btn btn-light rounded-pill" wire:click="resetFilter">
            <i class="bi bi-arrow-clockwise me-1"></i> Reset Filter
          </button>
        </div>
        <div class="col-12 col-md-3">
          <select class="form-select" wire:model.live="filterJobPosition">
            <option value="">Semua Job Position</option>
            @foreach($jobPositions as $jp)<option value="{{ $jp }}">{{ $jp }}</option>@endforeach
          </select>
        </div>
        <div class="col-12 col-md-3">
          <select class="form-select" wire:model.live="filterBranchName">
            <option value="">Semua Branch Name</option>
            @foreach($branchNames as $bn)<option value="{{ $bn }}">{{ $bn }}</option>@endforeach
          </select>
        </div>
        <div class="col-12 col-md-3">
          <select class="form-select" wire:model.live="filterUnitKerja">
            <option value="">Semua Unit Kerja</option>
            @foreach($unitKerjas as $uk)<option value="{{ $uk }}">{{ $uk }}</option>@endforeach
          </select>
        </div>
        <div class="col-12 col-md-3">
          <select class="form-select" wire:model.live="filterSync">
            <option value="">Semua Hasil Generate</option>
            <option value="NEW">User Baru</option>
            <option value="UPDATED">Diperbarui</option>
            <option value="FAILED">Gagal</option>
            <option value="DEACTIVATED">Nonaktif</option>
          </select>
        </div>
        <div class="col-12 text-md-end text-muted small pt-1 mobile-filter-extra">
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
              <th style="min-width:180px;">Role</th>
              <th style="min-width:220px;">Cabang</th>
              <th style="min-width:220px;">Job Position</th>
              <th style="min-width:220px;">Branch Name</th>
              <th style="min-width:220px;">Unit Kerja</th>
              <th style="width:110px;">Aktif</th>
              <th style="min-width:165px;">Hasil Generate</th>
              <th style="width:140px;" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($items as $u)
              @php
                $role = strtoupper((string)$u->role);
                $roleBadge = match($role) {
                  'ADMIN' => 'bg-danger',
                  'MANAJEMEN' => 'bg-dark',
                  'MANAJEMEN KANWIL' => 'bg-info text-dark',
                  'SUPERVISOR' => 'bg-warning text-dark',
                  'AO' => 'bg-primary',
                  default => 'bg-secondary',
                };
                $syncStatus = strtoupper((string)($u->simpeg_sync_status ?? ''));
                if ((int) $u->aktif !== 1) {
                  $syncStatus = 'DEACTIVATED';
                }
                [$syncLabel, $syncBadge, $syncRow] = match($syncStatus) {
                  'NEW' => ['USER BARU', 'sync-badge-new', 'sync-row-new'],
                  'UPDATED' => ['DIPERBARUI', 'sync-badge-updated', 'sync-row-updated'],
                  'FAILED' => ['GAGAL', 'sync-badge-failed', 'sync-row-failed'],
                  'DEACTIVATED' => ['NONAKTIF', 'sync-badge-deactivated', 'sync-row-deactivated'],
                  default => ['-', '', ''],
                };
              @endphp
              <tr class="{{ $syncRow }}">
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center user-avatar">
                      {{ strtoupper(substr(trim($u->name ?? 'U'),0,1)) }}
                    </div>
                    <div>
                      <div class="fw-bold">{{ $u->name }}</div>
                      <div class="text-muted small">{{ $u->nama_lengkap ?? '-' }}</div>
                      <div class="text-muted small">{{ $u->employee_id ?? '-' }}</div>
                    </div>
                  </div>
                </td>
                <td class="small">{{ $u->email }}</td>
                <td><span class="badge {{ $roleBadge }} rounded-pill px-3 py-2">{{ $role }}</span></td>
                <td class="small">{{ $u->cabang ? ($u->cabang->kode_cabang.' - '.$u->cabang->nama_cabang) : '-' }}</td>
                <td class="small">{{ $u->job_position ?: '-' }}</td>
                <td class="small">{{ $u->branch_name ?: '-' }}</td>
                <td class="small">{{ $u->unit_kerja ?: '-' }}</td>
                <td>
                  <div class="form-check form-switch m-0">
                    <input class="form-check-input" type="checkbox" role="switch"
                           id="sw{{ $u->id }}" @checked((int)$u->aktif===1)
                           wire:click="toggleAktif({{ $u->id }})">
                  </div>
                </td>
                <td title="{{ $u->simpeg_sync_message ?: '' }}">
                  @if($syncStatus)
                    <span class="badge rounded-pill px-3 py-2 {{ $syncBadge }}">{{ $syncLabel }}</span>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td class="text-end text-nowrap">
                  <a class="btn btn-outline-primary btn-sm rounded-pill px-3"
                     href="{{ route('users.edit',$u->id) }}">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                  </a>
                </td>
              </tr>
            @empty
              <tr><td colspan="10" class="text-center text-muted p-5">Belum ada user.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="mt-3">{{ $items->links() }}</div>

    <div class="modal fade" id="modalImportUsers" tabindex="-1" aria-hidden="true" wire:ignore.self>
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
          <div class="modal-header bg-white">
            <div>
              <h5 class="modal-title fw-bold mb-0">Upload CSV User</h5>
              <div class="text-muted small">Import data user dari file CSV</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-light border rounded-4 mb-3">
              <div class="fw-semibold mb-1">Format CSV</div>
              <div class="small text-muted">
                Header: <code>kode;employee_id;full_name;branch_name;unit_kerja;job_position;level;group_jabatan</code><br>
                Role ditentukan otomatis. Data dengan nama/Employee ID yang sama akan diperbarui.
              </div>
            </div>
            <label class="form-label fw-semibold">Pilih File CSV</label>
            <input type="file" class="form-control" wire:model="file" accept=".csv,.txt">
            @error('file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            <div wire:loading wire:target="file" class="small text-muted mt-2">Membaca file...</div>
          </div>
          <div class="modal-footer bg-white">
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary rounded-pill px-4"
                    wire:click="importCsv" wire:loading.attr="disabled" wire:target="importCsv,file">
              <span wire:loading.remove wire:target="importCsv">Import Sekarang</span>
              <span wire:loading wire:target="importCsv">Mengimpor...</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  @else
    @if($simpegError)
      <div class="alert alert-warning rounded-4">
        <strong>Koneksi SIMPEG belum tersedia.</strong><br>{{ $simpegError }}
      </div>
    @endif

    <div class="card-soft p-3 mb-3"
         data-mobile-filter-panel
         data-mobile-filter-key="users-simpeg">
      <div class="row g-2 align-items-center">
        <div class="col-12 col-lg-4" data-mobile-filter-primary>
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input class="form-control border-start-0"
                   placeholder="Employee ID, nama, kantor, unit kerja, jabatan..."
                   wire:model.live.debounce.400ms="simpegSearch">
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-2">
          <select class="form-select" wire:model.live="simpegFilterKode">
            <option value="">Semua Cabang</option>
            @foreach($simpegOptions['kode'] as $value)<option value="{{ $value }}">{{ $value }}</option>@endforeach
          </select>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <select class="form-select" wire:model.live="simpegFilterKantor">
            <option value="">Semua Kode Kantor</option>
            @foreach($simpegOptions['kantor'] as $value)<option value="{{ $value }}">{{ $value }}</option>@endforeach
          </select>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <select class="form-select" wire:model.live="simpegFilterUnit">
            <option value="">Semua Unit Kerja</option>
            @foreach($simpegOptions['unit'] as $value)<option value="{{ $value }}">{{ $value }}</option>@endforeach
          </select>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <select class="form-select" wire:model.live="simpegFilterJabatan">
            <option value="">Semua Jabatan</option>
            @foreach($simpegOptions['jabatan'] as $value)<option value="{{ $value }}">{{ $value }}</option>@endforeach
          </select>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <select class="form-select" wire:model.live="simpegFilterLevel">
            <option value="">Semua Level</option>
            @foreach($simpegOptions['level'] as $value)<option value="{{ $value }}">{{ $value }}</option>@endforeach
          </select>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <select class="form-select" wire:model.live="simpegFilterGroup">
            <option value="">Semua Group Jabatan</option>
            @foreach($simpegOptions['group'] as $value)<option value="{{ $value }}">{{ $value }}</option>@endforeach
          </select>
        </div>
        <div class="col-12 col-lg-3 d-grid">
          <button type="button" class="btn btn-light rounded-pill" wire:click="resetSimpegFilter">
            <i class="bi bi-arrow-clockwise me-1"></i> Reset Filter
          </button>
        </div>
        <div class="col-12 text-md-end text-muted small pt-1 mobile-filter-extra">
          Total: <span class="fw-bold">{{ $simpegItems->total() }}</span> pegawai aktif
        </div>
      </div>
    </div>

    <div class="card-soft overflow-hidden">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 simpeg-table">
          <thead class="table-light">
            <tr>
              <th>Kode Cabang</th>
              <th>Kode Kantor</th>
              <th>Employee ID</th>
              <th>Nama Lengkap</th>
              <th>Kantor/Cabang</th>
              <th>Unit Kerja</th>
              <th>Jabatan</th>
              <th>Level</th>
              <th>Group Jabatan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($simpegItems as $pegawai)
              <tr>
                <td>{{ $pegawai->kode_cabang ?? '-' }}</td>
                <td>{{ $pegawai->kode_kantor ?? '-' }}</td>
                <td class="fw-bold text-primary">{{ $pegawai->employee_id }}</td>
                <td>{{ $pegawai->full_name }}</td>
                <td>{{ $pegawai->branch_name ?? '-' }}</td>
                <td>{{ $pegawai->unit_kerja ?? '-' }}</td>
                <td>{{ $pegawai->job_position ?? '-' }}</td>
                <td>{{ $pegawai->level ?? '-' }}</td>
                <td>{{ $pegawai->group_jabatan ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center text-muted p-5">
                  {{ $simpegError ?: 'Tidak ada pegawai aktif yang cocok dengan filter.' }}
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
      <div class="text-muted small">
        Data ditampilkan langsung dari SIMPEG, bersifat <strong>baca-saja</strong>, dan hanya memuat jabatan aktif.
      </div>
      <div>{{ $simpegItems->links() }}</div>
    </div>
  @endif

  <div class="modal fade" id="modalGenerateSimpeg" tabindex="-1" aria-hidden="true"
       data-bs-backdrop="static" data-bs-keyboard="false" wire:ignore>
    <div class="modal-dialog modal-dialog-centered simpeg-generate-dialog">
      <div class="modal-content border-0 overflow-hidden simpeg-generate-modal">
        <div class="modal-header simpeg-generate-header">
          <div>
            <h5 class="modal-title fw-bold mb-1">Generate User SIMPEG ke Pipeline Prospek</h5>
            <div class="simpeg-generate-subtitle" id="simpegGenerateMessage">Mempersiapkan data pegawai aktif...</div>
          </div>
          <button type="button" id="simpegGenerateClose"
                  class="btn-close btn-close-white"
                  data-bs-dismiss="modal" disabled
                  aria-label="Tutup"></button>
        </div>

        <div class="modal-body simpeg-generate-body">
          <div class="simpeg-generate-stats">
            @foreach(['total' => 'Total Data', 'new' => 'User Baru', 'updated' => 'Diperbarui', 'failed' => 'Gagal', 'deactivated' => 'Dinonaktifkan'] as $key => $label)
              <div class="simpeg-generate-stat simpeg-stat-{{ $key }}">
                <div class="simpeg-generate-stat-label">{{ $label }}</div>
                <div class="simpeg-generate-stat-value" id="simpegCount{{ ucfirst($key) }}">0</div>
              </div>
            @endforeach
          </div>

          <div class="simpeg-result-filters" id="simpegGenerateFilters" aria-label="Filter hasil generate">
            <span class="simpeg-result-filter-label">Tampilkan hasil:</span>
            <button type="button" class="simpeg-result-filter is-active" data-generate-filter="ALL">
              Semua
              <span id="simpegFilterCountAll">0</span>
            </button>
            <button type="button" class="simpeg-result-filter" data-generate-filter="FAILED">
              Gagal
              <span id="simpegFilterCountFailed">0</span>
            </button>
            <button type="button" class="simpeg-result-filter" data-generate-filter="DEACTIVATED">
              Dinonaktifkan
              <span id="simpegFilterCountDeactivated">0</span>
            </button>
          </div>

          <div class="simpeg-generate-table-wrap">
            <table class="table align-middle mb-0 simpeg-generate-table">
              <thead>
                <tr>
                  <th>Employee ID</th>
                  <th>Nama Lengkap</th>
                  <th>Role</th>
                  <th>Kantor/Cabang</th>
                  <th>Hasil</th>
                  <th>Alasan/Keterangan</th>
                </tr>
              </thead>
              <tbody id="simpegGenerateRows">
                <tr id="simpegGenerateEmpty" class="simpeg-generate-placeholder">
                  <td colspan="6">
                    <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                    Menunggu proses...
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="modal-footer simpeg-generate-footer">
          <div class="simpeg-progress-meta">
            <span id="simpegGenerateFooterStatus">Menunggu proses generate</span>
            <strong id="simpegGeneratePercent">0%</strong>
          </div>
          <div class="progress simpeg-generate-progress">
            <div id="simpegGenerateProgress"
                 class="progress-bar"
                 style="width:0%"></div>
          </div>
          <div class="simpeg-generate-actions d-none" id="simpegGenerateActions">
            <a href="{{ route('users.index') }}" id="simpegViewUsers"
               class="btn btn-primary rounded-pill px-4 d-none">
              <i class="bi bi-people me-1"></i> Lihat Manajemen User Pipeline
            </a>
            <button type="button" class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal" id="simpegGenerateCloseButton" disabled>
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .user-avatar{width:44px;height:44px;background:#eef2ff;font-weight:900;flex:0 0 44px}
    .form-switch .form-check-input{width:3.2rem;height:1.65rem;cursor:pointer}
    .form-switch .form-check-input:checked{background-color:#22c55e;border-color:#22c55e}
    .sync-row-new > *{background-color:#e7f8ed!important}
    .sync-row-updated > *{background-color:#fff4cf!important}
    .sync-row-failed > *{background-color:#fde3e5!important}
    .sync-row-deactivated > *{background-color:#eceff3!important;color:#697180}
    .sync-row-new > *:first-child{box-shadow:inset 5px 0 #27ae60}
    .sync-row-updated > *:first-child{box-shadow:inset 5px 0 #e2a400}
    .sync-row-failed > *:first-child{box-shadow:inset 5px 0 #dc3545}
    .sync-row-deactivated > *:first-child{box-shadow:inset 5px 0 #8a929e}
    .sync-badge-new{background:#c9f2d7;color:#137a3d}
    .sync-badge-updated{background:#ffe69a;color:#8a5200}
    .sync-badge-failed{background:#f8c7cc;color:#a51d2a}
    .sync-badge-deactivated{background:#d8dde4;color:#4b5563}
    .simpeg-table th,.simpeg-table td{min-width:145px;font-size:.82rem;white-space:nowrap}
    .simpeg-table th:nth-child(4),.simpeg-table td:nth-child(4){min-width:250px}
    .simpeg-table th:nth-child(5),.simpeg-table td:nth-child(5),
    .simpeg-table th:nth-child(7),.simpeg-table td:nth-child(7){min-width:220px}

    .simpeg-generate-dialog{
      width:calc(100vw - 76px);
      max-width:1590px;
      margin-right:auto;
      margin-left:auto;
    }
    .simpeg-generate-modal{
      height:min(790px,calc(100dvh - 30px));
      max-height:calc(100dvh - 30px);
      border-radius:24px!important;
      background:#fff!important;
      box-shadow:0 32px 90px rgba(8,18,48,.34);
    }
    .simpeg-generate-modal .simpeg-generate-header{
      flex:0 0 auto;
      min-height:0;
      padding:15px 22px!important;
      color:#fff!important;
      border:0;
      background:linear-gradient(115deg,#153466 0%,#124c6d 56%,#167a78 100%)!important;
    }
    .simpeg-generate-header .modal-title{color:#fff!important;font-size:1.1rem;letter-spacing:-.015em}
    .simpeg-generate-subtitle{color:rgba(255,255,255,.76)!important;font-size:.72rem}
    .simpeg-generate-header .btn-close{opacity:.86}
    .simpeg-generate-header .btn-close:not(:disabled):hover{opacity:1}
    .simpeg-generate-modal .simpeg-generate-body{
      display:flex;
      flex:1 1 auto;
      flex-direction:column;
      min-height:0;
      padding:13px 18px 10px!important;
      overflow:hidden!important;
      background:#fff!important;
    }
    .simpeg-generate-stats{
      display:grid;
      grid-template-columns:repeat(5,minmax(0,1fr));
      flex:0 0 auto;
      gap:8px;
      margin-bottom:9px;
    }
    .simpeg-generate-stat{
      min-width:0;
      padding:9px 11px;
      border:1px solid #dce3ef;
      border-radius:14px;
      background:#f8fafd;
    }
    .simpeg-generate-stat-label{
      color:#687890;
      font-size:.6rem;
      font-weight:800;
      letter-spacing:.035em;
      text-transform:uppercase;
    }
    .simpeg-generate-stat-value{
      margin-top:4px;
      color:#162238;
      font-size:1.12rem;
      font-weight:900;
      line-height:1;
    }
    .simpeg-stat-new{background:#f0fbf4}
    .simpeg-stat-updated{background:#fff9e7}
    .simpeg-stat-failed{background:#fff2f3}
    .simpeg-stat-deactivated{background:#f2f4f7}
    .simpeg-result-filters{
      display:flex;
      align-items:center;
      gap:8px;
      flex:0 0 auto;
      margin-bottom:8px;
    }
    .simpeg-result-filter-label{margin-right:2px;color:#64748b;font-size:.76rem;font-weight:750}
    .simpeg-result-filter{
      display:inline-flex;
      align-items:center;
      gap:7px;
      min-height:31px;
      padding:4px 10px;
      border:1px solid #d6deeb;
      border-radius:999px;
      color:#4f5d73;
      background:#fff;
      font-size:.68rem;
      font-weight:750;
      transition:.18s ease;
    }
    .simpeg-result-filter span{
      min-width:20px;
      padding:2px 6px;
      border-radius:999px;
      color:#67758b;
      background:#edf1f7;
      font-size:.65rem;
      text-align:center;
    }
    .simpeg-result-filter:hover{border-color:#9db0d1;color:#244e9a}
    .simpeg-result-filter.is-active{
      border-color:#2459d7;
      color:#fff;
      background:#2459d7;
      box-shadow:0 7px 18px rgba(36,89,215,.19);
    }
    .simpeg-result-filter.is-active span{color:#2459d7;background:#fff}
    .simpeg-generate-table-wrap{
      flex:1 1 auto;
      min-height:0;
      height:auto;
      overflow:auto;
      border:1px solid #dce3ed;
      border-radius:16px;
      background:#fff;
    }
    .simpeg-generate-table{min-width:1040px;font-size:.68rem}
    .simpeg-generate-table thead{position:sticky;top:0;z-index:3}
    .simpeg-generate-table th{
      padding:9px 11px;
      border-bottom:1px solid #d3dbe8;
      color:#172238;
      background:#f1f5fa;
      font-size:.6rem;
      font-weight:900;
      letter-spacing:.035em;
      text-transform:uppercase;
      white-space:nowrap;
    }
    .simpeg-generate-table td{padding:7px 11px;border-color:#e0e5ed;line-height:1.25}
    .simpeg-generate-table tr.generate-row-new > *{background:#ecf9f1}
    .simpeg-generate-table tr.generate-row-updated > *{background:#fff9e7}
    .simpeg-generate-table tr.generate-row-failed > *{background:#fff0f1}
    .simpeg-generate-table tr.generate-row-deactivated > *{background:#f0f2f5;color:#657080}
    .simpeg-generate-status{
      display:inline-flex;
      padding:4px 8px;
      border-radius:999px;
      font-size:.58rem;
      font-weight:900;
      white-space:nowrap;
    }
    .simpeg-generate-status.status-new{color:#137a3d;background:#caf2d8}
    .simpeg-generate-status.status-updated{color:#955100;background:#ffe9a6}
    .simpeg-generate-status.status-failed{color:#a51d2a;background:#f8c7cc}
    .simpeg-generate-status.status-deactivated{color:#4b5563;background:#d8dde4}
    .simpeg-generate-placeholder td{
      height:90px;
      color:#748196;
      text-align:center;
      background:#fff!important;
    }
    .simpeg-generate-placeholder.is-error td{color:#b42332;background:#fff6f6!important}
    .simpeg-generate-modal .simpeg-generate-footer{
      display:block;
      flex:0 0 auto;
      padding:9px 18px 11px!important;
      border-color:#e0e6ef;
      background:#f8fafd!important;
    }
    .simpeg-progress-meta{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:15px;
      margin-bottom:6px;
      color:#5e6d84;
      font-size:.68rem;
    }
    .simpeg-progress-meta strong{color:#172238;font-size:.86rem}
    .simpeg-generate-progress{height:8px;border-radius:999px;background:#e1e7f0}
    .simpeg-generate-progress .progress-bar{
      border-radius:999px;
      background:linear-gradient(90deg,#2962dc,#13b8c2);
      transition:width .22s ease;
    }
    .simpeg-generate-actions{
      display:flex;
      justify-content:flex-end;
      gap:9px;
      margin-top:8px;
    }
    @media(max-width:767.98px){
      .user-source-tabs{display:flex!important;width:100%}
      .user-source-tabs .btn{flex:1;padding-left:.75rem!important;padding-right:.75rem!important;font-size:.78rem}
      .simpeg-generate-dialog{width:calc(100vw - 18px);margin:9px auto}
      .simpeg-generate-modal{height:calc(100dvh - 18px);max-height:calc(100dvh - 18px);border-radius:22px!important}
      .simpeg-generate-header{padding:17px 16px}
      .simpeg-generate-header .modal-title{font-size:1rem}
      .simpeg-generate-body{padding:13px}
      .simpeg-generate-stats{grid-template-columns:repeat(2,minmax(0,1fr));gap:7px}
      .simpeg-generate-stat:first-child{grid-column:1/-1}
      .simpeg-generate-stat{padding:10px}
      .simpeg-result-filters{overflow-x:auto;padding-bottom:2px;white-space:nowrap}
      .simpeg-result-filter-label{display:none}
      .simpeg-generate-footer{padding:13px}
      .simpeg-generate-actions{flex-wrap:wrap}
      .simpeg-generate-actions .btn{flex:1}
    }
  </style>

  <script>
    window.startSimpegGenerate = async function () {
      const modalElement = document.getElementById('modalGenerateSimpeg');
      if (!modalElement || !window.bootstrap || !window.Livewire) return;

      const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
      const component = Livewire.find(@js($this->getId()));
      const rowsElement = document.getElementById('simpegGenerateRows');
      const messageElement = document.getElementById('simpegGenerateMessage');
      const progressElement = document.getElementById('simpegGenerateProgress');
      const percentElement = document.getElementById('simpegGeneratePercent');
      const footerStatusElement = document.getElementById('simpegGenerateFooterStatus');
      const tableWrap = modalElement.querySelector('.simpeg-generate-table-wrap');
      const closeTop = document.getElementById('simpegGenerateClose');
      const closeBottom = document.getElementById('simpegGenerateCloseButton');
      const viewUsers = document.getElementById('simpegViewUsers');
      const actionsElement = document.getElementById('simpegGenerateActions');
      const filterButtons = Array.from(modalElement.querySelectorAll('[data-generate-filter]'));
      const counts = {total: 0, new: 0, updated: 0, failed: 0, deactivated: 0};
      let activeFilter = 'ALL';

      const setCount = (key) => {
        const el = document.getElementById('simpegCount' + key.charAt(0).toUpperCase() + key.slice(1));
        if (el) el.textContent = counts[key];
      };
      const statusMeta = {
        NEW: {label: 'USER BARU', rowClass: 'generate-row-new', badgeClass: 'status-new'},
        UPDATED: {label: 'DIPERBARUI', rowClass: 'generate-row-updated', badgeClass: 'status-updated'},
        FAILED: {label: 'GAGAL', rowClass: 'generate-row-failed', badgeClass: 'status-failed'},
        DEACTIVATED: {label: 'DINONAKTIFKAN', rowClass: 'generate-row-deactivated', badgeClass: 'status-deactivated'}
      };
      const updateFilterCounts = () => {
        const all = document.getElementById('simpegFilterCountAll');
        const failed = document.getElementById('simpegFilterCountFailed');
        const deactivated = document.getElementById('simpegFilterCountDeactivated');
        if (all) all.textContent = rowsElement.querySelectorAll('tr[data-generate-status]').length;
        if (failed) failed.textContent = counts.failed;
        if (deactivated) deactivated.textContent = counts.deactivated;
      };
      const applyFilter = () => {
        rowsElement.querySelectorAll('tr[data-generate-status]').forEach((row) => {
          row.hidden = activeFilter !== 'ALL' && row.dataset.generateStatus !== activeFilter;
        });
        filterButtons.forEach((button) => {
          const selected = button.dataset.generateFilter === activeFilter;
          button.classList.toggle('is-active', selected);
          button.setAttribute('aria-pressed', selected ? 'true' : 'false');
        });
      };
      filterButtons.forEach((button) => {
        button.onclick = function () {
          activeFilter = button.dataset.generateFilter || 'ALL';
          applyFilter();
        };
      });
      const setProgress = (processed, total) => {
        const percent = total ? Math.min(100, Math.round(processed / total * 100)) : 0;
        progressElement.style.width = percent + '%';
        percentElement.textContent = percent + '%';
        footerStatusElement.textContent = total
          ? 'Memproses ' + processed + ' dari ' + total + ' user'
          : 'Menunggu data SIMPEG';
      };
      const finishWithError = (message) => {
        const finalMessage = message || 'Generate gagal diproses.';
        messageElement.textContent = finalMessage;
        footerStatusElement.textContent = finalMessage;
        percentElement.textContent = 'Gagal';
        progressElement.classList.remove('progress-bar-animated');
        progressElement.classList.add('bg-danger');
        if (!rowsElement.querySelector('tr[data-generate-status]')) {
          rowsElement.innerHTML =
            '<tr class="simpeg-generate-placeholder is-error"><td colspan="6">' +
            '<i class="bi bi-exclamation-triangle me-2"></i>' +
            'Proses tidak dapat dilanjutkan. Tutup modal lalu coba kembali.</td></tr>';
        }
        closeTop.disabled = false;
        closeBottom.disabled = false;
        actionsElement.classList.remove('d-none');
      };
      const appendRow = (row) => {
        const status = String(row.status || 'FAILED').toUpperCase();
        const meta = statusMeta[status] || statusMeta.FAILED;
        const tr = document.createElement('tr');
        tr.dataset.generateStatus = status;
        tr.className = meta.rowClass;

        const values = [row.employee_id, row.full_name, row.role, row.branch];
        values.forEach((value) => {
          const td = document.createElement('td');
          td.textContent = value || '-';
          tr.appendChild(td);
        });

        const statusCell = document.createElement('td');
        const badge = document.createElement('span');
        badge.className = 'simpeg-generate-status ' + meta.badgeClass;
        badge.textContent = meta.label;
        statusCell.appendChild(badge);
        tr.appendChild(statusCell);

        const messageCell = document.createElement('td');
        messageCell.textContent = row.message || '-';
        tr.appendChild(messageCell);
        rowsElement.appendChild(tr);
      };

      rowsElement.innerHTML =
        '<tr class="simpeg-generate-placeholder"><td colspan="6">' +
        '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>' +
        'Membaca data SIMPEG...</td></tr>';
      messageElement.textContent = 'Mempersiapkan data pegawai aktif...';
      progressElement.style.width = '0%';
      progressElement.className = 'progress-bar';
      percentElement.textContent = '0%';
      footerStatusElement.textContent = 'Membaca data pegawai aktif dari SIMPEG';
      closeTop.disabled = true;
      closeBottom.disabled = true;
      viewUsers.classList.add('d-none');
      actionsElement.classList.add('d-none');
      activeFilter = 'ALL';
      Object.keys(counts).forEach((key) => { counts[key] = 0; setCount(key); });
      updateFilterCounts();
      applyFilter();
      modal.show();

      try {
        if (!component) {
          return finishWithError('Komponen generate belum siap. Muat ulang halaman lalu coba kembali.');
        }

        const start = await component.call('startSimpegGenerate');
        if (!start || !start.ok) return finishWithError(start && start.message);

        counts.total = Number(start.total || 0);
        setCount('total');
        rowsElement.innerHTML = '';
        let processed = 0;
        messageElement.textContent = 'Data SIMPEG ditemukan. Generate sedang berjalan...';
        setProgress(0, counts.total);

        while (processed < counts.total) {
          const result = await component.call('processSimpegGenerate', start.job_id, processed);
          if (!result || !result.ok) return finishWithError(result && result.message);

          (result.rows || []).forEach((row) => {
            appendRow(row);
            if (row.status === 'NEW') counts.new++;
            else if (row.status === 'UPDATED') counts.updated++;
            else if (row.status === 'FAILED') counts.failed++;
            else if (row.status === 'DEACTIVATED') counts.deactivated++;
          });
          counts.deactivated = Math.max(counts.deactivated, Number(result.deactivated || 0));
          ['new','updated','failed','deactivated'].forEach(setCount);
          updateFilterCounts();
          applyFilter();

          const nextProcessed = Number(result.processed || 0);
          if (!result.done && nextProcessed <= processed) {
            return finishWithError('Proses batch berhenti karena tidak ada kemajuan data.');
          }

          processed = nextProcessed;
          setProgress(processed, counts.total);
          messageElement.textContent = 'Generate berjalan: ' + processed + ' dari ' + counts.total + ' user diproses.';
          if (tableWrap && activeFilter === 'ALL') tableWrap.scrollTop = tableWrap.scrollHeight;
          if (result.done) break;
        }

        setProgress(counts.total, counts.total);
        progressElement.classList.remove('progress-bar-animated');
        messageElement.textContent = 'Generate selesai: ' + counts.new + ' baru, ' + counts.updated +
          ' diperbarui, ' + counts.failed + ' gagal, ' + counts.deactivated + ' dinonaktifkan.';
        footerStatusElement.textContent = 'Generate selesai · ' + counts.total + ' user telah diperiksa';
        closeTop.disabled = false;
        closeBottom.disabled = false;
        actionsElement.classList.remove('d-none');
        viewUsers.classList.remove('d-none');
      } catch (error) {
        const expired = Number(error && error.status) === 419 ||
          String((error && error.message) || '').includes('419');
        finishWithError(expired
          ? 'Sesi halaman kedaluwarsa. Muat ulang halaman lalu jalankan generate kembali.'
          : 'Generate gagal diproses. Periksa koneksi lalu coba kembali.');
      }
    };

    document.addEventListener('livewire:init', function () {
      Livewire.on('closeImportUsersModal', function () {
        const element = document.getElementById('modalImportUsers');
        if (element) bootstrap.Modal.getOrCreateInstance(element).hide();
      });
    });
  </script>
</div>
