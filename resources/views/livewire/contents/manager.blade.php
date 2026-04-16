<div class="container-fluid px-0">
  <style>
    .content-page-head{
      display:flex;
      flex-wrap:wrap;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:16px;
    }
    .content-title{
      font-size:1.6rem;
      font-weight:900;
      color:#0f172a;
      letter-spacing:-.02em;
    }
    .content-subtitle{
      color:#64748b;
      font-size:.92rem;
      margin-top:4px;
    }
    .card-soft{
      border:0;
      border-radius:24px;
      background:#fff;
      box-shadow:0 12px 30px rgba(15,23,42,.08);
    }
    .tab-pill-wrap{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      margin-bottom:16px;
    }
    .tab-pill{
      border:0;
      border-radius:999px;
      padding:10px 16px;
      font-weight:800;
      background:#eef2ff;
      color:#334155;
    }
    .tab-pill.active{
      background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%);
      color:#fff;
      box-shadow:0 10px 24px rgba(37,99,235,.24);
    }
    .btn-add-content{
      border:0;
      border-radius:999px;
      padding:12px 18px;
      font-weight:800;
      color:#fff;
      background:linear-gradient(135deg,#2563eb 0%,#4f46e5 100%);
      box-shadow:0 14px 28px rgba(37,99,235,.24);
    }
    .table-card{
      border:1px solid #eef2f7;
      border-radius:22px;
      overflow:hidden;
    }
    .table-modern thead th{
      background:#f8fafc;
      color:#334155;
      font-size:.88rem;
      font-weight:800;
      border-bottom:1px solid #e9eef5;
      white-space:nowrap;
    }
    .table-modern tbody td{
      vertical-align:middle;
      border-color:#eef2f7;
    }
    .thumb-mini{
      width:58px;
      height:58px;
      border-radius:14px;
      object-fit:cover;
      border:1px solid #e5e7eb;
      background:#fff;
    }
    .thumb-mini-empty{
      width:58px;
      height:58px;
      border-radius:14px;
      border:1px solid #e5e7eb;
      background:#f8fafc;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#94a3b8;
      font-size:1.1rem;
    }
    .badge-soft{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      min-height:32px;
      padding:6px 12px;
      border-radius:999px;
      font-size:.78rem;
      font-weight:800;
      white-space:nowrap;
    }
    .badge-on{
      background:linear-gradient(135deg,#86efac 0%,#22c55e 100%);
      color:#14532d;
    }
    .badge-off{
      background:linear-gradient(135deg,#e5e7eb 0%,#cbd5e1 100%);
      color:#334155;
    }
    .btn-action{
      border-radius:999px;
      padding:.45rem 1rem;
      font-weight:700;
    }
    .btn-detail{
      border-radius:999px;
      padding:.45rem 1rem;
      font-weight:700;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      justify-content:center;
    }
    .empty-box{
      padding:28px;
      text-align:center;
      color:#64748b;
    }
    .form-label{
      font-weight:800;
      color:#0f172a;
    }
    .form-control,
    .form-select,
    textarea.form-control{
      border-radius:16px;
      border:1px solid #e5e7eb;
      min-height:48px;
      box-shadow:none;
    }
    textarea.form-control{
      min-height:140px;
    }
    .preview-box{
      border:1px dashed #cbd5e1;
      border-radius:18px;
      padding:14px;
      background:#f8fafc;
      text-align:center;
      min-height:170px;
      display:flex;
      align-items:center;
      justify-content:center;
      flex-direction:column;
    }
    .preview-box img{
      width:100%;
      max-width:280px;
      border-radius:16px;
      object-fit:cover;
      display:block;
      margin:auto;
    }

    .content-modal .modal-content{
      border:0;
      border-radius:26px;
      overflow:hidden;
      box-shadow:0 30px 90px rgba(15,23,42,.28);
    }
    .content-modal .modal-header{
      padding:20px 22px;
      border-bottom:1px solid #eef2f7;
      background:linear-gradient(135deg,#f8fbff 0%,#eef2ff 100%);
    }
    .content-modal .modal-body{
      padding:22px;
    }
    .content-modal .modal-footer{
      padding:18px 22px 22px;
      border-top:1px solid #eef2f7;
    }

    @media (max-width: 767.98px){
      .content-title{
        font-size:1.35rem;
      }
      .btn-add-content{
        width:100%;
      }
    }
  </style>

  <div class="content-page-head">
    <div>
      <div class="content-title">Konten Aplikasi</div>
      <div class="content-subtitle">Kelola konten katalog produk dan tips & trick untuk tampilan mobile.</div>
    </div>

    <button type="button" class="btn btn-add-content" wire:click="openCreateModal">
      <i class="bi bi-plus-lg me-1"></i> Tambah Konten
    </button>
  </div>

  <div class="tab-pill-wrap">
    <button type="button"
            class="tab-pill {{ $tab === 'produk' ? 'active' : '' }}"
            wire:click="switchTab('produk')">
      Katalog Produk
    </button>

    <button type="button"
            class="tab-pill {{ $tab === 'tips' ? 'active' : '' }}"
            wire:click="switchTab('tips')">
      Tips & Trick
    </button>
  </div>

  <div class="card-soft p-3 p-md-4">
    <div class="fw-bold fs-5 mb-3">
      {{ $tab === 'produk' ? 'Daftar Katalog Produk' : 'Daftar Tips & Trick' }}
    </div>

    <div class="table-card">
      <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
          <thead>
            <tr>
              <th style="width:70px;">Gambar</th>
              <th>Judul</th>
              <th>{{ $tab === 'produk' ? 'Badge' : 'Kategori' }}</th>
              <th>Urutan</th>
              <th>Status</th>
              <th style="width:280px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php
              $rows = $tab === 'produk' ? $produkItems : $tipsItems;
            @endphp

            @forelse($rows as $row)
              <tr wire:key="content-row-{{ $tab }}-{{ $row->id }}">
                <td>
                  @if($row->gambar_url)
                    <img src="{{ $row->gambar_url }}" class="thumb-mini" alt="{{ $row->judul }}">
                  @else
                    <div class="thumb-mini-empty">
                      <i class="bi bi-image"></i>
                    </div>
                  @endif
                </td>

                <td>
                  <div class="fw-bold">{{ $row->judul }}</div>
                  <div class="text-muted small text-break">{{ $row->slug }}</div>
                </td>

                <td>
                  {{ $tab === 'produk' ? ($row->badge ?? '-') : ($row->kategori ?? '-') }}
                </td>

                <td>{{ $row->urutan }}</td>

                <td>
                  @if((int)$row->aktif === 1)
                    <span class="badge-soft badge-on">Aktif</span>
                  @else
                    <span class="badge-soft badge-off">Nonaktif</span>
                  @endif
                </td>

                <td>
                  <div class="d-flex flex-wrap gap-2">
                    <button type="button"
                            class="btn btn-outline-primary btn-sm btn-action"
                            wire:click="edit('{{ $tab }}', {{ $row->id }})">
                      Edit
                    </button>

                    <button type="button"
                            class="btn btn-outline-danger btn-sm btn-action"
                            wire:click="askDelete('{{ $tab }}', {{ $row->id }})">
                      Hapus
                    </button>

                    <a href="{{ $row->detail_url }}"
                       class="btn btn-outline-secondary btn-sm btn-detail">
                      Lihat
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="empty-box">
                  Belum ada data.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div wire:ignore.self class="modal fade content-modal" id="contentManagerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <div class="fw-bold fs-3 mb-1">{{ $editingId ? 'Edit Konten' : 'Tambah Konten' }}</div>
            <div class="text-muted">{{ $tab === 'produk' ? 'Katalog Produk' : 'Tips & Trick' }}</div>
          </div>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body" wire:key="content-modal-form-{{ $tab }}-{{ $editingId ?: 'new' }}">
          <div class="row g-4">
            <div class="col-12 col-lg-7">
              <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" wire:model.live="judul" placeholder="Masukkan judul">
                @error('judul') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" class="form-control" wire:model.live="slug" placeholder="Otomatis jika dikosongi">
                @error('slug') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              @if($tab === 'produk')
                <div class="mb-3">
                  <label class="form-label">Badge</label>
                  <input type="text" class="form-control" wire:model.live="badge" placeholder="Contoh: Produk Unggulan">
                  @error('badge') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
              @else
                <div class="mb-3">
                  <label class="form-label">Kategori</label>
                  <input type="text" class="form-control" wire:model.live="kategori" placeholder="Contoh: Marketing">
                  @error('kategori') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
              @endif

              <div class="mb-3">
                <label class="form-label">Deskripsi / Isi Konten</label>
                <textarea class="form-control" wire:model.live="deskripsi" placeholder="Masukkan isi konten di sini"></textarea>
                @error('deskripsi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="row g-3">
                <div class="col-6">
                  <label class="form-label">Urutan</label>
                  <input type="number" class="form-control" wire:model.live="urutan" min="1">
                  @error('urutan') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-6">
                  <label class="form-label">Status</label>
                  <select class="form-select" wire:model.live="aktif">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                  </select>
                  @error('aktif') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>

            <div class="col-12 col-lg-5">
              <div class="mb-3">
                <label class="form-label">Upload Gambar</label>
                <input type="file" class="form-control" wire:model="gambar" accept="image/*">
                @error('gambar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="preview-box">
                <div class="fw-semibold mb-2">Preview Gambar</div>

                @if($gambar)
                  <img src="{{ $gambar->temporaryUrl() }}" alt="preview">
                @elseif($existingGambar)
                  <img src="{{ $existingGambar }}" alt="preview">
                @else
                  <div class="text-muted small">Belum ada gambar.</div>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
            Batal
          </button>

          <button type="button" class="btn btn-primary rounded-pill px-4" wire:click="save">
            <i class="bi bi-check2-circle me-1"></i>
            {{ $editingId ? 'Update' : 'Simpan' }}
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('livewire:init', function () {
      let contentModal = null;

      function getContentModal() {
        const el = document.getElementById('contentManagerModal');
        if (!el || typeof bootstrap === 'undefined') return null;

        contentModal = bootstrap.Modal.getOrCreateInstance(el, {
          backdrop: 'static',
          keyboard: true
        });

        if (!el.dataset.boundModal) {
          el.dataset.boundModal = '1';

          el.addEventListener('hidden.bs.modal', function () {
            Livewire.dispatch('forceCloseContentModal');
          });
        }

        return contentModal;
      }

      Livewire.on('open-content-modal', function () {
        setTimeout(function () {
          const modal = getContentModal();
          if (modal) modal.show();
        }, 120);
      });

      Livewire.on('close-content-modal', function () {
        const modal = getContentModal();
        if (modal) modal.hide();
      });

      Livewire.on('swal', function (data) {
        if (Array.isArray(data)) data = data[0];

        if (typeof Swal === 'undefined') {
          alert(data.text || 'Selesai');
          return;
        }

        Swal.fire({
          icon: data.icon || 'success',
          title: data.title || 'Berhasil',
          text: data.text || '',
          confirmButtonColor: '#2563eb',
          customClass: {
            popup: 'rounded-4'
          }
        });
      });

      Livewire.on('askDelete', function (data) {
        if (Array.isArray(data)) data = data[0];

        if (typeof Swal === 'undefined') {
          if (confirm(data.text || 'Yakin hapus?')) {
            Livewire.dispatch('deleteConfirmed', { tab: data.tab, id: data.id });
          }
          return;
        }

        Swal.fire({
          icon: 'warning',
          title: data.title || 'Hapus data?',
          text: data.text || 'Data akan dihapus permanen.',
          showCancelButton: true,
          confirmButtonText: 'Ya, hapus',
          cancelButtonText: 'Batal',
          confirmButtonColor: '#dc2626',
          cancelButtonColor: '#64748b',
          reverseButtons: true,
          customClass: {
            popup: 'rounded-4'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            Livewire.dispatch('deleteConfirmed', { tab: data.tab, id: data.id });
          }
        });
      });
    });
  </script>
</div>
