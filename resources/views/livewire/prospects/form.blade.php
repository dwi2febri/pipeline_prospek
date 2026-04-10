<div class="container-fluid px-0">

  <style>
    .mobile-back-btn{
  display:none;
}

.form-title-row{
  display:block;
}

@media (max-width: 767.98px){
  .form-page-top{
    margin:-14px -14px 14px -14px;
    padding:18px 16px 14px 16px;
    background:
      radial-gradient(circle at 8% 12%, rgba(59,130,246,.14), transparent 22%),
      radial-gradient(circle at 92% 16%, rgba(168,85,247,.12), transparent 24%),
      linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
    border-bottom-left-radius:28px;
    border-bottom-right-radius:28px;
    box-shadow:0 14px 28px rgba(15,23,42,.06);
  }

  .form-title-row{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:4px;
  }

  .mobile-back-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:44px;
    height:44px;
    flex:0 0 44px;
    border-radius:14px;
    border:1px solid #e5e7eb;
    background:#ffffff;
    color:#0f172a;
    text-decoration:none;
    box-shadow:0 8px 20px rgba(15,23,42,.08);
  }

  .mobile-back-btn i{
    font-size:1.05rem;
  }

  .back-btn-modern{
    display:none !important;
  }

  .form-page-title{
    margin-bottom:0;
    line-height:1.1;
  }

  .form-page-subtitle{
    margin-left:56px;
  }
}
    .form-page-top{
      display:flex;
      flex-wrap:wrap;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
    }

    .form-page-title{
      font-size:1.55rem;
      font-weight:900;
      color:#0f172a;
      letter-spacing:-.02em;
      line-height:1.1;
    }

    .form-page-subtitle{
      color:#64748b;
      font-size:.92rem;
      margin-top:4px;
    }

    .back-btn-modern{
      border-radius:999px;
      padding:.75rem 1.1rem;
      font-weight:800;
      border:1px solid #e5e7eb;
      background:#fff;
      color:#0f172a;
      box-shadow:0 10px 24px rgba(15,23,42,.06);
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      gap:8px;
    }

    .card-soft{
      border:0;
      border-radius:28px;
      background:#fff;
      box-shadow:0 18px 42px rgba(15,23,42,.08);
      position:relative;
      overflow:hidden;
    }

    .card-soft::before{
      content:"";
      position:absolute;
      top:-48px;
      right:-48px;
      width:150px;
      height:150px;
      border-radius:999px;
      background:radial-gradient(circle, rgba(59,130,246,.10) 0%, rgba(59,130,246,0) 72%);
      pointer-events:none;
    }

    .card-soft::after{
      content:"";
      position:absolute;
      left:-40px;
      bottom:-40px;
      width:130px;
      height:130px;
      border-radius:999px;
      background:radial-gradient(circle, rgba(249,115,22,.08) 0%, rgba(249,115,22,0) 72%);
      pointer-events:none;
    }

    .form-label.fw-semibold{
      font-weight:800 !important;
      color:#0f172a;
      margin-bottom:.55rem;
    }

    .input-group{
      border-radius:18px;
      overflow:hidden;
      border:1px solid #e7edf5;
      background:#fff;
      box-shadow:0 6px 18px rgba(15,23,42,.04);
    }

    .input-group-text{
      border:0 !important;
      background:#fff !important;
      color:#64748b;
      min-width:48px;
      justify-content:center;
    }

    .form-control,
    .form-select,
    textarea.form-control{
      border-radius:18px !important;
      border:1px solid #e7edf5 !important;
      min-height:50px;
      padding:.85rem 1rem;
      box-shadow:0 6px 18px rgba(15,23,42,.04);
      background:#fff;
      color:#0f172a;
    }

    .input-group .form-control{
      border:0 !important;
      box-shadow:none !important;
      min-height:48px;
    }

    .input-group .btn{
      border:0 !important;
      border-left:1px solid #eef2f7 !important;
      border-radius:0 !important;
      background:#fff !important;
      color:#334155 !important;
      font-weight:700;
    }

    .form-control:focus,
    .form-select:focus,
    textarea.form-control:focus{
      border-color:#93c5fd !important;
      box-shadow:0 0 0 .22rem rgba(59,130,246,.12) !important;
    }

    textarea.form-control{
      min-height:110px;
      resize:vertical;
    }

    .section-soft{
      border:1px solid #edf2f7;
      border-radius:24px;
      padding:16px;
      background:
        radial-gradient(circle at top right, rgba(59,130,246,.05), transparent 22%),
        linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
      box-shadow:0 10px 24px rgba(15,23,42,.04);
    }

    .section-soft-title{
      font-size:.82rem;
      font-weight:900;
      text-transform:uppercase;
      letter-spacing:.08em;
      color:#94a3b8;
      margin-bottom:12px;
    }

    .hint-soft{
      color:#64748b;
      font-size:.84rem;
      line-height:1.55;
    }

    .btn-app-primary{
      border:0;
      border-radius:18px;
      min-height:50px;
      padding:.8rem 1rem;
      font-weight:800;
      background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%);
      color:#fff;
      box-shadow:0 14px 28px rgba(37,99,235,.22);
    }

    .btn-app-primary:hover{
      color:#fff;
    }

    .btn-app-outline{
      border-radius:18px;
      min-height:50px;
      padding:.8rem 1rem;
      font-weight:800;
      background:#fff;
      border:1px solid #dbeafe;
      color:#2563eb;
      box-shadow:0 10px 22px rgba(37,99,235,.08);
    }

    .btn-app-light{
      border-radius:18px;
      min-height:50px;
      padding:.8rem 1rem;
      font-weight:800;
      background:#fff;
      border:1px solid #e5e7eb;
      color:#334155;
      box-shadow:0 10px 22px rgba(15,23,42,.05);
    }

    .photo-action-row{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      align-items:center;
    }

    .upload-pill{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 12px;
      border-radius:999px;
      background:#f8fafc;
      color:#64748b;
      font-size:.82rem;
      font-weight:700;
      border:1px solid #eef2f7;
    }

    .preview-title{
      font-weight:800;
      color:#0f172a;
      margin-bottom:10px;
    }

    .preview-card{
      border-radius:18px;
      overflow:hidden;
      background:#fff;
      border:1px solid #edf2f7;
      box-shadow:0 10px 22px rgba(15,23,42,.05);
    }

    .sticky-action-bar{
      position:sticky;
      bottom:0;
      z-index:20;
      margin-top:18px;
      background:rgba(255,255,255,.92);
      backdrop-filter:blur(10px);
      border:1px solid #eef2f7;
      border-radius:22px;
      padding:12px;
      box-shadow:0 14px 28px rgba(15,23,42,.08);
    }

    .sticky-action-grid{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:10px;
    }

    .alert.rounded-4{
      border-radius:18px !important;
    }

    .modal-content{
      border-radius:24px !important;
      overflow:hidden;
    }

    .modal-header,
    .modal-footer{
      border-color:#eef2f7 !important;
    }

    #mapPicker .leaflet-container,
    #mapPicker{
      background:#f8fafc !important;
    }

    @media (max-width: 767.98px){
      body{
        background:
          radial-gradient(circle at 8% 8%, rgba(37,99,235,.14), transparent 22%),
          radial-gradient(circle at 92% 10%, rgba(139,92,246,.14), transparent 24%),
          radial-gradient(circle at 12% 88%, rgba(251,146,60,.10), transparent 20%),
          linear-gradient(180deg,#eef5ff 0%,#f6f8ff 42%,#f5f7fb 100%) !important;
      }

      .container-fluid.px-0{
        padding-left:0 !important;
        padding-right:0 !important;
      }

      .form-page-top{
        margin:-14px -14px 14px -14px;
        padding:18px 16px 14px 16px;
        background:
          radial-gradient(circle at 8% 12%, rgba(59,130,246,.14), transparent 22%),
          radial-gradient(circle at 92% 16%, rgba(168,85,247,.12), transparent 24%),
          linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
        border-bottom-left-radius:28px;
        border-bottom-right-radius:28px;
        box-shadow:0 14px 28px rgba(15,23,42,.06);
      }

      .form-page-title{
        font-size:1.35rem;
      }

      .form-page-subtitle{
        font-size:.98rem;
        line-height:1.55;
        max-width:96%;
      }

      .back-btn-modern{
        display:none;
      }

      .card-soft{
        border-radius:30px;
        padding:18px 14px !important;
        box-shadow:0 16px 34px rgba(15,23,42,.08);
      }

      .section-soft{
        border-radius:22px;
        padding:14px;
      }

      .form-control,
      .form-select,
      textarea.form-control{
        min-height:54px;
        border-radius:18px !important;
        font-size:16px;
      }

      .input-group{
        border-radius:18px;
      }

      .input-group .form-control{
        min-height:52px;
        font-size:16px;
      }

      .input-group .btn{
        min-width:52px;
      }

      .btn-app-primary,
      .btn-app-outline,
      .btn-app-light{
        min-height:52px;
        border-radius:18px;
        font-size:.98rem;
      }

      .photo-action-row{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:10px;
      }

      .photo-action-row .upload-pill{
        grid-column:1 / -1;
        justify-content:center;
      }

      .sticky-action-bar{
        position:sticky;
        bottom:76px;
        border-radius:22px;
      }

      .sticky-action-grid{
        grid-template-columns:1fr;
      }

      .mobile-hidden-label{
        display:none;
      }
    }
  </style>

<div class="form-page-top">
  <div class="w-100 w-md-auto">
    <div class="form-title-row">
      <a href="{{ route('prospects.index') }}" class="mobile-back-btn d-inline-flex d-md-none">
        <i class="bi bi-arrow-left"></i>
      </a>

      <div class="form-page-title">{{ $id ? 'Detail Prospek' : 'Input Prospek' }}</div>
    </div>

    <div class="form-page-subtitle">Isi data prospek nasabah dengan lengkap</div>
  </div>

  <a href="{{ route('prospects.index') }}" class="back-btn-modern d-none d-md-inline-flex">
    <i class="bi bi-arrow-left"></i> Kembali
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

        <div class="col-12">
          <div class="section-soft">
            <div class="section-soft-title">Informasi Utama</div>

            <div class="row g-3">
              <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Tanggal Prospek</label>
                <div class="input-group">
                  <span class="input-group-text bg-white"><i class="bi bi-calendar-event"></i></span>
                  <input type="date" class="form-control" wire:model="tanggal_prospek" id="tanggal_prospek">
                </div>
                @error('tanggal_prospek')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 col-md-8">
                <label class="form-label fw-semibold">Nama Calon Debitur</label>
                <div class="input-group">
                  <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                  <input class="form-control" wire:model="nama" placeholder="Nama calon debitur">
                </div>
                @error('nama')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">No HP</label>
                <div class="input-group">
                  <span class="input-group-text bg-white"><i class="bi bi-telephone"></i></span>

                  <input id="no_hp_input"
                        class="form-control"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        wire:model.live="no_hp"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                        placeholder="08xxxx">

                  <button type="button"
                          class="btn btn-outline-secondary"
                          id="btnPickContact"
                          title="Ambil dari kontak HP">
                    <i class="bi bi-person-lines-fill"></i>
                  </button>
                </div>

                <div class="hint-soft mt-2" id="contactHint">
                  Klik ikon kontak untuk ambil nomor dari kontak HP.
                </div>

                @error('no_hp')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">NIK (opsional)</label>
                <div class="input-group">
                  <span class="input-group-text bg-white"><i class="bi bi-person-vcard"></i></span>
                  <input class="form-control"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        wire:model.live="nik"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                        placeholder="boleh dikosongi">
                </div>
                @error('nik')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Cabang</label>
                <select class="form-select" wire:model="cabang_id">
                  <option value="">-- Pilih Cabang --</option>
                  @if(!empty($cabangOptions))
                    @foreach($cabangOptions as $c)
                      @php
                        $kodeCabang = trim((string)($c['kode_cabang'] ?? ''));
                      @endphp

                      @if($kodeCabang >= '001' && $kodeCabang <= '028')
                        <option value="{{ $c['id'] }}">{{ $c['text'] }}</option>
                      @endif
                    @endforeach
                  @endif
                </select>
                @error('cabang_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                <div class="hint-soft mt-2">Pegawai bebas memilih cabang.</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="section-soft">
            <div class="section-soft-title">Data Usaha & Produk</div>

            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Jenis Usaha</label>
                <select class="form-select" wire:model="jenis_usaha">
                  <option value="">-- Pilih Jenis Usaha --</option>
                  @foreach($jenisUsahaOptions as $opt)
                    <option value="{{ $opt['kode'] }}">{{ $opt['nama'] }}</option>
                  @endforeach
                </select>
                @error('jenis_usaha')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Rekomendasi Produk</label>
                <select class="form-select" wire:model="jenis_produk">
                  @foreach($produkOptions as $opt)
                    <option value="{{ $opt['kode'] }}">{{ $opt['nama'] }}</option>
                  @endforeach
                </select>
                @error('jenis_produk')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Keterangan Usaha</label>
                <textarea class="form-control" rows="3" wire:model="keterangan_usaha"
                          placeholder="Contoh: jualan mainan anak..."></textarea>
                @error('keterangan_usaha')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="section-soft">
            <div class="section-soft-title">Lokasi</div>

            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Alamat & Koordinat</label>

                <div class="row g-2">
                  <div class="col-12">
                    <div class="input-group">
                      <span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span>
                      <input id="alamat_input"
                            class="form-control"
                            wire:model="alamat"
                            placeholder="Alamat akan terisi dari lokasi saat ini atau titik peta..."
                            readonly>
                    </div>
                  </div>

                  <div class="col-6 col-md-4">
                    <input id="lokasi_lat" class="form-control" wire:model="lokasi_lat" placeholder="Lat" readonly>
                  </div>

                  <div class="col-6 col-md-4">
                    <input id="lokasi_lng" class="form-control" wire:model="lokasi_lng" placeholder="Lng" readonly>
                  </div>

                  <div class="col-12 col-md-4 d-grid">
                    <button type="button" class="btn btn-app-primary" id="btnGetLoc">
                      <i class="bi bi-crosshair2 me-1"></i> Lokasi Saat Ini
                    </button>
                  </div>

                  <div class="col-12 d-grid">
                    <button type="button" class="btn btn-app-outline" id="btnOpenMapPicker">
                      <i class="bi bi-map me-1"></i> Pilih Titik di Peta
                    </button>
                  </div>
                </div>

                <div class="hint-soft mt-2" id="locHint">
                  Pilih <b>Lokasi Saat Ini</b> atau gunakan <b>Pilih Titik di Peta</b>.
                </div>

                @error('lokasi_lat')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                @error('lokasi_lng')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Wilayah Administratif</label>

                <div class="row g-3">
                  <div class="col-12">
                    <div wire:ignore>
                      <select id="kabKotaSelect" class="form-select">
                        <option value="">-- Pilih Kab/Kota --</option>
                      </select>
                    </div>
                    <input type="hidden" id="kab_kota_hidden" wire:model.live="kab_kota">
                    <input type="hidden" id="kode_kab_kota_hidden" wire:model.live="kode_kab_kota">
                    @error('kab_kota')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-12">
                    <div wire:ignore>
                      <select id="kecamatanSelect" class="form-select" disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                      </select>
                    </div>
                    <input type="hidden" id="kecamatan_hidden" wire:model.live="kecamatan">
                    <input type="hidden" id="kode_kecamatan_hidden" wire:model.live="kode_kecamatan">
                    @error('kecamatan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-12">
                    <div wire:ignore>
                      <select id="desaSelect" class="form-select" disabled>
                        <option value="">-- Pilih Desa --</option>
                      </select>
                    </div>
                    <input type="hidden" id="desa_hidden" wire:model.live="desa">
                    <input type="hidden" id="kode_desa_hidden" wire:model.live="kode_desa">
                    <input type="hidden" id="kode_provinsi_hidden" wire:model.live="kode_provinsi" value="33">
                    @error('desa')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="section-soft">
            <div class="section-soft-title">Dokumentasi</div>

            <label class="form-label fw-semibold">Dokumentasi (Foto)</label>

            <input id="lwPhotos" type="file" class="d-none" accept="image/*" multiple wire:model="photos">
            <input id="cameraCaptureInput" type="file" class="d-none" accept="image/*" capture="environment">
            <input id="galleryInput" type="file" class="d-none" accept="image/*" multiple>

            <div class="photo-action-row">
              <button type="button" class="btn btn-app-primary" id="btnOpenCamera">
                <i class="bi bi-camera me-1"></i> Ambil Foto
              </button>

              <button type="button" class="btn btn-app-outline" id="btnOpenGallery">
                <i class="bi bi-images me-1"></i> Pilih dari Galeri
              </button>

              <div class="upload-pill">
                <i class="bi bi-info-circle"></i> Maksimal 5MB per foto
              </div>
            </div>

            <div class="small text-muted mt-2" wire:loading wire:target="photos">Mengunggah foto...</div>
            @error('photos') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            @error('photos.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

            <div class="mt-3">
              <div class="preview-title">Preview foto dipilih</div>
              <div id="photoPreviewWrap" class="row g-2" wire:ignore></div>
            </div>

            @if($id && isset($docs) && $docs->count())
              <div class="mt-3">
                <div class="preview-title">Foto tersimpan</div>
                <div class="row g-2">
                  @foreach($docs as $doc)
                    <div class="col-6 col-md-3">
                      <div class="preview-card p-2 position-relative">
                        <img src="{{ $doc->url }}" class="w-100"
                            style="border-radius:14px;object-fit:cover;aspect-ratio:1/1;"
                            loading="lazy">
                        <button type="button"
                                class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 m-2"
                                wire:click="deleteDoc({{ $doc->id }})"
                                onclick="return confirm('Hapus foto ini?')">
                          <i class="bi bi-trash"></i>
                        </button>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        </div>

        <div class="col-12">
          <div class="section-soft">
            <div class="section-soft-title">Catatan Tambahan</div>
            <label class="form-label fw-semibold mobile-hidden-label">Catatan</label>
            <textarea class="form-control" rows="3" wire:model="catatan"
                      placeholder="Catatan tambahan..."></textarea>
            @error('catatan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="col-12">
          <div class="sticky-action-bar">
            <div class="sticky-action-grid">
              <button class="btn btn-app-primary" wire:click.prevent="save">
                <i class="bi bi-save me-1"></i> Simpan
              </button>

              <a class="btn btn-app-light text-center text-decoration-none d-flex align-items-center justify-content-center"
                href="{{ route('prospects.index') }}">
                Batal
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="modal fade" id="modalCamera" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content" style="border-radius:18px;border:0;box-shadow:0 20px 60px rgba(15,23,42,.18)">
        <div class="modal-header">
          <div>
            <div class="fw-bold">Ambil Foto</div>
            <div class="text-muted small">Klik “Jepret” untuk mengambil gambar.</div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="alert alert-warning rounded-4 small mb-2 d-none" id="camWarn"></div>
          <div style="border-radius:14px;overflow:hidden;border:1px solid #e5e7eb;">
            <video id="camVideo" autoplay playsinline muted
                   style="width:100%;height:420px;object-fit:cover;background:#000"></video>
          </div>
          <canvas id="camCanvas" class="d-none"></canvas>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary rounded-pill px-4" id="btnSnap">
            <i class="bi bi-circle-fill me-1"></i> Jepret
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalMapPicker" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-sm-down">
      <div class="modal-content border-0" style="border-radius:18px;overflow:hidden;">
        <div class="modal-header">
          <div>
            <div class="fw-bold">Pilih Titik Lokasi</div>
            <div class="text-muted small">Cari lokasi, lalu klik titik pada peta untuk memilih.</div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" id="mapSearchInput" class="form-control" placeholder="Cari lokasi / alamat / desa / kecamatan...">
                <button type="button" class="btn btn-primary" id="btnMapSearch">
                  Cari
                </button>
              </div>
              <div class="small text-muted mt-1" id="mapSearchHint">
                Ketik lokasi lalu klik <b>Cari</b>, atau langsung klik titik di peta.
              </div>
            </div>

            <div class="col-12" wire:ignore>
              <div id="mapPickerWrap" style="border-radius:16px;overflow:hidden;border:1px solid #e5e7eb;background:#f8fafc;">
                <div id="mapPicker" style="height:420px;width:100%;display:block;"></div>
              </div>
            </div>

            <div class="col-12">
              <div class="card-soft p-3">
                <div class="row g-2">
                  <div class="col-12">
                    <div class="small text-muted">Alamat Dipilih</div>
                    <div class="fw-semibold" id="pickedAddressPreview">Belum ada titik dipilih.</div>
                  </div>
                  <div class="col-6">
                    <div class="small text-muted">Latitude</div>
                    <div class="fw-semibold" id="pickedLatPreview">-</div>
                  </div>
                  <div class="col-6">
                    <div class="small text-muted">Longitude</div>
                    <div class="fw-semibold" id="pickedLngPreview">-</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light rounded-pill px-4" id="btnResetPickedPoint">
            Reset Titik
          </button>
          <button type="button" class="btn btn-primary rounded-pill px-4" id="btnUsePickedPoint">
            Gunakan Titik Ini
          </button>
        </div>
      </div>
    </div>
  </div>

  @if($showDuplicateHpModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(15,23,42,.55);">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:20px; overflow:hidden;">
          <div class="modal-header">
            <div>
              <h5 class="modal-title fw-bold mb-0">Nomor HP Sudah Pernah Diajukan</h5>
              <div class="text-muted small">Pengajuan tidak bisa disimpan.</div>
            </div>
            <button type="button" class="btn-close" wire:click="closeDuplicateHpModal"></button>
          </div>

          <div class="modal-body">
            <div class="alert alert-warning rounded-4 mb-0">
              Nomor HP <b>{{ $duplicateHp }}</b> sudah ada di database prospek dan tidak bisa diajukan ulang.
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-primary rounded-pill px-4" wire:click="closeDuplicateHpModal">
              Oke
            </button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
  (function () {
    if (window.__prospectFormLocationBound) return;
    window.__prospectFormLocationBound = true;

    function getEl(id) {
      return document.getElementById(id);
    }

    function setInputValue(el, value) {
      if (!el) return;
      el.value = value || '';
      el.dispatchEvent(new Event('input', { bubbles: true }));
      el.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function setHint(msg, isError) {
      var hint = getEl('locHint');
      if (!hint) return;
      hint.innerHTML = msg;
      hint.className = 'small mt-2 ' + (isError ? 'text-danger' : 'text-muted');
    }

    function setContactHint(msg, isError) {
      var hint = getEl('contactHint');
      if (!hint) return;
      hint.innerHTML = msg;
      hint.className = 'small mt-1 ' + (isError ? 'text-danger' : 'text-muted');
    }

    function isSecurePage() {
      return window.isSecureContext === true
        || location.protocol === 'https:'
        || location.hostname === 'localhost'
        || location.hostname === '127.0.0.1';
    }

    async function fetchJson(url) {
      const res = await fetch(url, {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
      });

      if (!res.ok) {
        throw new Error('HTTP ' + res.status + ' - ' + url);
      }

      return await res.json();
    }

    function normalizeText(str) {
      return String(str || '')
        .toUpperCase()
        .replace(/\./g, '')
        .replace(/KABUPATEN/g, '')
        .replace(/KOTA/g, '')
        .replace(/KECAMATAN/g, '')
        .replace(/KELURAHAN/g, '')
        .replace(/DESA/g, '')
        .replace(/\s+/g, ' ')
        .trim();
    }

    function findByNameLoose(list, text) {
      if (!text) return null;
      const target = normalizeText(text);

      let found = list.find(function(item) {
        return normalizeText(item.name) === target;
      });
      if (found) return found;

      found = list.find(function(item) {
        const n = normalizeText(item.name);
        return n.includes(target) || target.includes(n);
      });

      return found || null;
    }

    function normalizePhoneNumber(phone) {
      let cleaned = String(phone || '').trim();

      if (cleaned.indexOf('+62') === 0) {
        cleaned = '0' + cleaned.substring(3);
      }

      cleaned = cleaned.replace(/[^0-9]/g, '');

      if (cleaned.indexOf('62') === 0) {
        cleaned = '0' + cleaned.substring(2);
      }

      if (cleaned.indexOf('8') === 0) {
        cleaned = '0' + cleaned;
      }

      cleaned = cleaned.replace(/^00+/, '0');

      return cleaned;
    }

    window.setPickedContactFromAndroid = function(name, phone) {
      const input = getEl('no_hp_input');

      if (!input) return;

      const cleanPhone = normalizePhoneNumber(phone || '');

      if (!cleanPhone) {
        setContactHint('Tidak ada kontak yang dipilih.', true);
        return;
      }

      setInputValue(input, cleanPhone);
      setContactHint('Nomor dari kontak "' + (name || 'Kontak') + '" berhasil diisi ✅', false);
    };

    async function reverseGeocode(lat, lng) {
      const url1 = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + encodeURIComponent(lat) + '&lon=' + encodeURIComponent(lng);
      const url2 = 'https://geocode.maps.co/reverse?lat=' + encodeURIComponent(lat) + '&lon=' + encodeURIComponent(lng);

      async function tryFetch(url) {
        try {
          const res = await fetch(url, {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
          });
          if (!res.ok) return null;
          const data = await res.json();
          if (data && data.display_name) return data.display_name;
          if (data && data.address) return Object.values(data.address).filter(Boolean).join(', ');
          return null;
        } catch (e) {
          return null;
        }
      }

      return (await tryFetch(url1)) || (await tryFetch(url2)) || null;
    }

    async function searchLocation(keyword) {
      const q = String(keyword || '').trim();
      if (!q) return [];

      const url = 'https://nominatim.openstreetmap.org/search?format=jsonv2&limit=8&q=' + encodeURIComponent(q);

      try {
        const res = await fetch(url, {
          method: 'GET',
          headers: { 'Accept': 'application/json' }
        });

        if (!res.ok) return [];
        const data = await res.json();
        return Array.isArray(data) ? data : [];
      } catch (e) {
        return [];
      }
    }

    async function pickPhoneFromContacts() {
      const input = getEl('no_hp_input');

      if (!input) return;

      if (window.Android && typeof window.Android.pickContact === 'function') {
        setContactHint('Membuka daftar kontak...', false);
        try {
          window.Android.pickContact();
        } catch (err) {
          console.error('Android pickContact error:', err);
          setContactHint('Gagal membuka kontak dari aplikasi.', true);
        }
        return;
      }

      if (!isSecurePage()) {
        setContactHint('Fitur kontak hanya bisa dipakai di HTTPS / localhost.', true);
        return;
      }

      if (!('contacts' in navigator) || !navigator.contacts || typeof navigator.contacts.select !== 'function') {
        setContactHint('Browser ini belum mendukung akses kontak. Gunakan aplikasi Android atau Chrome Android.', true);
        return;
      }

      try {
        setContactHint('Membuka daftar kontak...', false);

        let props = ['tel', 'name'];

        if (typeof navigator.contacts.getProperties === 'function') {
          try {
            const supported = await navigator.contacts.getProperties();
            props = props.filter(function(p) {
              return Array.isArray(supported) ? supported.includes(p) : true;
            });
          } catch (e) {}
        }

        if (!props.includes('tel')) {
          setContactHint('Browser mendukung kontak, tapi field nomor telepon tidak tersedia.', true);
          return;
        }

        const contacts = await navigator.contacts.select(props, { multiple: false });

        if (!contacts || !contacts.length) {
          setContactHint('Tidak ada kontak yang dipilih.', true);
          return;
        }

        const picked = contacts[0];
        const telList = Array.isArray(picked.tel) ? picked.tel : [];
        const firstPhone = telList.length ? normalizePhoneNumber(telList[0]) : '';

        if (!firstPhone) {
          setContactHint('Kontak terpilih tidak memiliki nomor telepon.', true);
          return;
        }

        setInputValue(input, firstPhone);

        const pickedName = Array.isArray(picked.name) && picked.name.length ? picked.name[0] : 'Kontak';
        setContactHint('Nomor dari kontak "' + pickedName + '" berhasil diisi ✅', false);
      } catch (err) {
        console.error('Contact picker error:', err);

        if (err && (err.name === 'NotAllowedError' || err.name === 'SecurityError')) {
          setContactHint('Akses kontak ditolak atau butuh klik manual dari user.', true);
          return;
        }

        if (err && err.name === 'InvalidStateError') {
          setContactHint('Pemilih kontak sudah terbuka atau halaman bukan top-level.', true);
          return;
        }

        if (err && err.name === 'TypeError') {
          setContactHint('Fitur kontak tidak didukung browser ini.', true);
          return;
        }

        setContactHint('Gagal mengambil nomor dari kontak.', true);
      }
    }

    function resetSelect(el, placeholder, disabled) {
      if (!el) return;
      el.innerHTML = '<option value="">' + placeholder + '</option>';
      el.disabled = typeof disabled === 'boolean' ? disabled : true;
    }

    async function fillLocation() {
      const btn = getEl('btnGetLoc');
      const latInput = getEl('lokasi_lat');
      const lngInput = getEl('lokasi_lng');
      const alamatInput = getEl('alamat_input');

      if (!btn) return;

      if (!navigator.geolocation) {
        setHint('Browser tidak mendukung GPS.', true);
        return;
      }

      if (!isSecurePage()) {
        setHint('Lokasi hanya bisa dipakai di HTTPS / localhost.', true);
        return;
      }

      btn.disabled = true;
      setHint('Mengambil lokasi saat ini dari device...', false);

      navigator.geolocation.getCurrentPosition(
        async function (pos) {
          try {
            const lat = String(pos.coords.latitude || '');
            const lng = String(pos.coords.longitude || '');

            setInputValue(latInput, lat);
            setInputValue(lngInput, lng);

            if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
              window.Livewire.dispatch('setLatLngProspek', { lat: lat, lng: lng });
            }

            const addr = await reverseGeocode(lat, lng);

            if (addr) {
              setInputValue(alamatInput, addr);

              if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
                window.Livewire.dispatch('setAlamatProspek', { alamat: addr });
              }

              setHint('Lokasi saat ini berhasil diambil ✅', false);
            } else {
              setHint('Lat/Lng berhasil diambil, tapi alamat belum didapat.', true);
            }
          } catch (e) {
            console.error('Gagal proses lokasi:', e);
            setHint('Gagal memproses lokasi.', true);
          } finally {
            btn.disabled = false;
          }
        },
        function (err) {
          btn.disabled = false;

          if (err && err.code === 1) {
            setHint('Izin lokasi ditolak. Aktifkan permission lokasi di browser.', true);
          } else if (err && err.code === 2) {
            setHint('Lokasi tidak tersedia. Nyalakan GPS dan coba lagi.', true);
          } else if (err && err.code === 3) {
            setHint('Request lokasi timeout. Coba lagi.', true);
          } else {
            setHint('Gagal mengambil lokasi.', true);
          }
        },
        {
          enableHighAccuracy: true,
          timeout: 20000,
          maximumAge: 0
        }
      );
    }

    async function initWilayahProspek() {
      const PROV_ID = '33';

      const kabSelect = getEl('kabKotaSelect');
      const kecSelect = getEl('kecamatanSelect');
      const desaSelect = getEl('desaSelect');

      const kabHidden = getEl('kab_kota_hidden');
      const kecHidden = getEl('kecamatan_hidden');
      const desaHidden = getEl('desa_hidden');

      const kodeProvHidden = getEl('kode_provinsi_hidden');
      const kodeKabHidden = getEl('kode_kab_kota_hidden');
      const kodeKecHidden = getEl('kode_kecamatan_hidden');
      const kodeDesaHidden = getEl('kode_desa_hidden');

      if (!kabSelect || !kecSelect || !desaSelect || !kabHidden || !kecHidden || !desaHidden) {
        return;
      }

      setInputValue(kodeProvHidden, PROV_ID);

      async function loadKabupaten(initialName) {
        resetSelect(kabSelect, '-- Loading Kab/Kota --', true);

        const json = await fetchJson('/api-wilayah/regencies/' + PROV_ID);
        const list = Array.isArray(json.data) ? json.data : [];

        kabSelect.innerHTML = '<option value="">-- Pilih Kab/Kota --</option>';

        list.forEach(function(item) {
          const opt = document.createElement('option');
          opt.value = item.code;
          opt.textContent = item.name;
          kabSelect.appendChild(opt);
        });

        kabSelect.disabled = false;

        if (initialName) {
          const found = findByNameLoose(list, initialName);
          if (found) {
            kabSelect.value = found.code;
            setInputValue(kabHidden, found.name);
            setInputValue(kodeKabHidden, found.code);
          }
        }

        return list;
      }

      async function loadKecamatan(regencyCode, initialName) {
        if (!regencyCode) {
          resetSelect(kecSelect, '-- Pilih Kecamatan --', true);
          resetSelect(desaSelect, '-- Pilih Desa --', true);
          return [];
        }

        resetSelect(kecSelect, '-- Loading Kecamatan --', true);

        const json = await fetchJson('/api-wilayah/districts/' + regencyCode);
        const list = Array.isArray(json.data) ? json.data : [];

        kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

        list.forEach(function(item) {
          const opt = document.createElement('option');
          opt.value = item.code;
          opt.textContent = item.name;
          kecSelect.appendChild(opt);
        });

        kecSelect.disabled = false;

        if (initialName) {
          const found = findByNameLoose(list, initialName);
          if (found) {
            kecSelect.value = found.code;
            setInputValue(kecHidden, found.name);
            setInputValue(kodeKecHidden, found.code);
          }
        }

        return list;
      }

      async function loadDesa(districtCode, initialName) {
        if (!districtCode) {
          resetSelect(desaSelect, '-- Pilih Desa --', true);
          return [];
        }

        resetSelect(desaSelect, '-- Loading Desa --', true);

        const json = await fetchJson('/api-wilayah/villages/' + districtCode);
        const list = Array.isArray(json.data) ? json.data : [];

        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

        list.forEach(function(item) {
          const opt = document.createElement('option');
          opt.value = item.code;
          opt.textContent = item.name;
          desaSelect.appendChild(opt);
        });

        desaSelect.disabled = false;

        if (initialName) {
          const found = findByNameLoose(list, initialName);
          if (found) {
            desaSelect.value = found.code;
            setInputValue(desaHidden, found.name);
            setInputValue(kodeDesaHidden, found.code);
          }
        }

        return list;
      }

      if (kabSelect.dataset.bound !== '1') {
        kabSelect.dataset.bound = '1';
        kabSelect.addEventListener('change', async function () {
          const selectedText = this.value ? this.options[this.selectedIndex].text : '';

          setInputValue(kabHidden, selectedText);
          setInputValue(kodeKabHidden, this.value || '');
          setInputValue(kecHidden, '');
          setInputValue(desaHidden, '');
          setInputValue(kodeKecHidden, '');
          setInputValue(kodeDesaHidden, '');

          resetSelect(desaSelect, '-- Pilih Desa --', true);
          await loadKecamatan(this.value || '', '');
        });
      }

      if (kecSelect.dataset.bound !== '1') {
        kecSelect.dataset.bound = '1';
        kecSelect.addEventListener('change', async function () {
          const selectedText = this.value ? this.options[this.selectedIndex].text : '';

          setInputValue(kecHidden, selectedText);
          setInputValue(kodeKecHidden, this.value || '');
          setInputValue(desaHidden, '');
          setInputValue(kodeDesaHidden, '');

          await loadDesa(this.value || '', '');
        });
      }

      if (desaSelect.dataset.bound !== '1') {
        desaSelect.dataset.bound = '1';
        desaSelect.addEventListener('change', function () {
          const selectedText = this.value ? this.options[this.selectedIndex].text : '';
          setInputValue(desaHidden, selectedText);
          setInputValue(kodeDesaHidden, this.value || '');
        });
      }

      try {
        const initialKab = kabHidden.value || '';
        const initialKec = kecHidden.value || '';
        const initialDesa = desaHidden.value || '';

        const kabList = await loadKabupaten(initialKab);

        if (initialKab) {
          const selectedKab = findByNameLoose(kabList, initialKab);
          if (selectedKab) {
            const kecList = await loadKecamatan(selectedKab.code, initialKec);

            if (initialKec) {
              const selectedKec = findByNameLoose(kecList, initialKec);
              if (selectedKec) {
                await loadDesa(selectedKec.code, initialDesa);
              }
            }
          }
        }
      } catch (e) {
        console.error('Wilayah gagal dimuat:', e);
        resetSelect(kabSelect, '-- Gagal memuat Kab/Kota --', true);
        resetSelect(kecSelect, '-- Pilih Kecamatan --', true);
        resetSelect(desaSelect, '-- Pilih Desa --', true);
      }
    }

    let mediaStream = null;
    let modalInstance = null;

    let mapPickerInstance = null;
    let mapPickerMarker = null;
    let mapPickerModalInstance = null;
    let pickedLat = '';
    let pickedLng = '';
    let pickedAddress = '';

    function isMobileDevice() {
      return /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent || '');
    }

    function clearPhotoPreview() {
      const wrap = getEl('photoPreviewWrap');
      if (wrap) wrap.innerHTML = '';
    }

    function fileToDataUrl(file) {
      return new Promise(function(resolve, reject) {
        const reader = new FileReader();
        reader.onload = function(e) { resolve(e.target.result); };
        reader.onerror = function() { reject(new Error('Gagal baca file')); };
        reader.readAsDataURL(file);
      });
    }

    async function renderPhotoPreview(files) {
      const wrap = getEl('photoPreviewWrap');
      const lwPhotos = getEl('lwPhotos');

      if (!wrap) return;

      clearPhotoPreview();

      if (!files || !files.length) return;

      const arr = Array.from(files);

      for (let i = 0; i < arr.length; i++) {
        const file = arr[i];
        if (!file.type || !file.type.startsWith('image/')) continue;

        try {
          const src = await fileToDataUrl(file);

          const col = document.createElement('div');
          col.className = 'col-6 col-md-3';
          col.innerHTML = `
            <div class="card-soft p-2 position-relative">
              <img src="${src}" class="w-100" style="border-radius:14px;object-fit:cover;aspect-ratio:1/1;" loading="lazy">
              <button type="button" class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 m-2 btn-remove-preview" data-idx="${i}">
                <i class="bi bi-x"></i>
              </button>
            </div>
          `;
          wrap.appendChild(col);
        } catch (e) {
          console.error('Preview gagal:', e);
        }
      }

      wrap.querySelectorAll('.btn-remove-preview').forEach(function(btn) {
        btn.onclick = function() {
          const idx = parseInt(this.getAttribute('data-idx'), 10);
          if (!lwPhotos || !lwPhotos.files) return;

          const dt = new DataTransfer();
          Array.from(lwPhotos.files).forEach(function(file, i) {
            if (i !== idx) dt.items.add(file);
          });

          lwPhotos.files = dt.files;
          renderPhotoPreview(lwPhotos.files);
          lwPhotos.dispatchEvent(new Event('change', { bubbles: true }));
        };
      });
    }

    function validateFiles(files) {
      const maxSize = 5 * 1024 * 1024;
      const valid = [];
      const errors = [];

      Array.from(files || []).forEach(function(file) {
        if (!file.type || !file.type.startsWith('image/')) {
          errors.push(file.name + ' bukan file gambar.');
          return;
        }
        if (file.size > maxSize) {
          errors.push(file.name + ' melebihi 5MB.');
          return;
        }
        valid.push(file);
      });

      if (errors.length) {
        alert(errors.join('\n'));
      }

      return valid;
    }

    async function mergeFilesToLivewire(sourceFiles) {
      const lwPhotos = getEl('lwPhotos');
      if (!lwPhotos || !sourceFiles || !sourceFiles.length) return;

      const validFiles = validateFiles(sourceFiles);
      if (!validFiles.length) return;

      const dt = new DataTransfer();

      if (lwPhotos.files && lwPhotos.files.length) {
        Array.from(lwPhotos.files).forEach(function(file) {
          dt.items.add(file);
        });
      }

      validFiles.forEach(function(file) {
        dt.items.add(file);
      });

      lwPhotos.files = dt.files;
      await renderPhotoPreview(lwPhotos.files);
      lwPhotos.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function stopCamera() {
      if (mediaStream) {
        mediaStream.getTracks().forEach(function(track) {
          track.stop();
        });
        mediaStream = null;
      }
    }

    function showCamWarn(msg) {
      const el = getEl('camWarn');
      if (!el) return;
      el.classList.remove('d-none');
      el.innerText = msg;
    }

    function hideCamWarn() {
      const el = getEl('camWarn');
      if (!el) return;
      el.classList.add('d-none');
      el.innerText = '';
    }

    async function openDesktopCamera() {
      const modalEl = getEl('modalCamera');
      const video = getEl('camVideo');
      if (!modalEl || !video) return;

      hideCamWarn();

      try {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
          showCamWarn('Browser desktop ini tidak mendukung webcam.');
          return;
        }

        mediaStream = await navigator.mediaDevices.getUserMedia({
          video: {
            facingMode: 'environment',
            width: { ideal: 1280 },
            height: { ideal: 720 }
          },
          audio: false
        });

        video.srcObject = mediaStream;

        if (!modalInstance) {
          modalInstance = new bootstrap.Modal(modalEl);
        }

        modalInstance.show();
      } catch (e) {
        console.error(e);
        showCamWarn('Kamera tidak bisa dibuka. Pastikan izin kamera diberikan.');
      }
    }

    function snapDesktopPhoto() {
      const video = getEl('camVideo');
      const canvas = getEl('camCanvas');
      if (!video || !canvas) return;

      const width = video.videoWidth || 1280;
      const height = video.videoHeight || 720;

      canvas.width = width;
      canvas.height = height;

      const ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0, width, height);

      canvas.toBlob(async function(blob) {
        if (!blob) return;

        const file = new File([blob], 'camera-' + Date.now() + '.jpg', {
          type: 'image/jpeg'
        });

        await mergeFilesToLivewire([file]);

        if (modalInstance) modalInstance.hide();
        stopCamera();
      }, 'image/jpeg', 0.92);
    }

    function updatePickedPreview() {
      const addrEl = getEl('pickedAddressPreview');
      const latEl = getEl('pickedLatPreview');
      const lngEl = getEl('pickedLngPreview');

      if (addrEl) addrEl.textContent = pickedAddress || 'Belum ada titik dipilih.';
      if (latEl) latEl.textContent = pickedLat || '-';
      if (lngEl) lngEl.textContent = pickedLng || '-';
    }

    async function setPickedPoint(lat, lng, addressText) {
      pickedLat = String(lat || '');
      pickedLng = String(lng || '');

      if (mapPickerMarker && mapPickerInstance) {
        mapPickerMarker.setLatLng([lat, lng]);
      } else if (mapPickerInstance) {
        mapPickerMarker = L.marker([lat, lng], { draggable: true }).addTo(mapPickerInstance);

        mapPickerMarker.on('dragend', async function(e) {
          const pos = e.target.getLatLng();
          pickedLat = String(pos.lat);
          pickedLng = String(pos.lng);
          const addr = await reverseGeocode(pos.lat, pos.lng);
          pickedAddress = addr || '';
          updatePickedPreview();
        });
      }

      if (addressText) {
        pickedAddress = addressText;
      } else {
        const addr = await reverseGeocode(lat, lng);
        pickedAddress = addr || '';
      }

      updatePickedPreview();
    }

    function initMapPicker() {
      const mapEl = getEl('mapPicker');
      if (!mapEl || typeof L === 'undefined') return;

      if (!mapPickerInstance) {
        mapPickerInstance = L.map(mapEl, {
          zoomControl: true
        }).setView([-7.150975, 110.140259], 8);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap'
        }).addTo(mapPickerInstance);

        mapPickerInstance.on('click', async function(e) {
          const lat = e.latlng.lat;
          const lng = e.latlng.lng;
          await setPickedPoint(lat, lng, '');
        });
      }

      const currentLat = parseFloat(getEl('lokasi_lat')?.value || '');
      const currentLng = parseFloat(getEl('lokasi_lng')?.value || '');

      if (!isNaN(currentLat) && !isNaN(currentLng)) {
        mapPickerInstance.setView([currentLat, currentLng], 16);
        setPickedPoint(currentLat, currentLng, getEl('alamat_input')?.value || '');
      } else {
        mapPickerInstance.setView([-7.150975, 110.140259], 8);
        pickedLat = '';
        pickedLng = '';
        pickedAddress = '';
        if (mapPickerMarker) {
          mapPickerInstance.removeLayer(mapPickerMarker);
          mapPickerMarker = null;
        }
        updatePickedPreview();
      }

      setTimeout(function() {
        mapPickerInstance.invalidateSize();
      }, 250);
    }

    function openMapPicker() {
      const modalEl = getEl('modalMapPicker');
      if (!modalEl) return;

      if (!mapPickerModalInstance) {
        mapPickerModalInstance = new bootstrap.Modal(modalEl);
      }

      mapPickerModalInstance.show();

      setTimeout(function() {
        initMapPicker();
      }, 250);
    }

    async function doMapSearch() {
      const input = getEl('mapSearchInput');
      const hint = getEl('mapSearchHint');

      if (!input || !mapPickerInstance) return;

      const keyword = String(input.value || '').trim();
      if (!keyword) {
        if (hint) hint.innerHTML = 'Masukkan kata kunci lokasi terlebih dahulu.';
        return;
      }

      if (hint) hint.innerHTML = 'Mencari lokasi...';

      const results = await searchLocation(keyword);

      if (!results.length) {
        if (hint) hint.innerHTML = 'Lokasi tidak ditemukan. Coba kata kunci lain.';
        return;
      }

      const first = results[0];
      const lat = parseFloat(first.lat);
      const lng = parseFloat(first.lon);

      if (isNaN(lat) || isNaN(lng)) {
        if (hint) hint.innerHTML = 'Hasil lokasi tidak valid.';
        return;
      }

      mapPickerInstance.setView([lat, lng], 16);
      await setPickedPoint(lat, lng, first.display_name || '');

      if (hint) hint.innerHTML = 'Lokasi ditemukan. Anda bisa klik titik lain di peta jika perlu.';
    }

    function usePickedPoint() {
      if (!pickedLat || !pickedLng) {
        alert('Silakan pilih titik pada peta terlebih dahulu.');
        return;
      }

      const latInput = getEl('lokasi_lat');
      const lngInput = getEl('lokasi_lng');
      const alamatInput = getEl('alamat_input');

      setInputValue(latInput, pickedLat);
      setInputValue(lngInput, pickedLng);
      setInputValue(alamatInput, pickedAddress);

      if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
        window.Livewire.dispatch('setLatLngProspek', { lat: pickedLat, lng: pickedLng });
        window.Livewire.dispatch('setAlamatProspek', { alamat: pickedAddress || '' });
      }

      setHint('Lokasi dari titik peta berhasil dipilih ✅', false);

      if (mapPickerModalInstance) {
        mapPickerModalInstance.hide();
      }
    }

    function resetPickedPoint() {
      pickedLat = '';
      pickedLng = '';
      pickedAddress = '';

      if (mapPickerInstance && mapPickerMarker) {
        mapPickerInstance.removeLayer(mapPickerMarker);
        mapPickerMarker = null;
      }

      updatePickedPreview();
    }

    function bindLocationButton() {
      const btn = getEl('btnGetLoc');
      if (!btn) return;

      if (btn.dataset.bound !== '1') {
        btn.dataset.bound = '1';
        btn.addEventListener('click', fillLocation);
      }

      const btnOpenMap = getEl('btnOpenMapPicker');
      if (btnOpenMap && btnOpenMap.dataset.bound !== '1') {
        btnOpenMap.dataset.bound = '1';
        btnOpenMap.addEventListener('click', openMapPicker);
      }

      const btnSearch = getEl('btnMapSearch');
      if (btnSearch && btnSearch.dataset.bound !== '1') {
        btnSearch.dataset.bound = '1';
        btnSearch.addEventListener('click', doMapSearch);
      }

      const searchInput = getEl('mapSearchInput');
      if (searchInput && searchInput.dataset.bound !== '1') {
        searchInput.dataset.bound = '1';
        searchInput.addEventListener('keydown', function(e) {
          if (e.key === 'Enter') {
            e.preventDefault();
            doMapSearch();
          }
        });
      }

      const btnUsePoint = getEl('btnUsePickedPoint');
      if (btnUsePoint && btnUsePoint.dataset.bound !== '1') {
        btnUsePoint.dataset.bound = '1';
        btnUsePoint.addEventListener('click', usePickedPoint);
      }

      const btnResetPoint = getEl('btnResetPickedPoint');
      if (btnResetPoint && btnResetPoint.dataset.bound !== '1') {
        btnResetPoint.dataset.bound = '1';
        btnResetPoint.addEventListener('click', resetPickedPoint);
      }

      const modalMap = getEl('modalMapPicker');
      if (modalMap && modalMap.dataset.bound !== '1') {
        modalMap.dataset.bound = '1';

        modalMap.addEventListener('shown.bs.modal', function() {
          setTimeout(function() {
            if (mapPickerInstance) {
              mapPickerInstance.invalidateSize();
            } else {
              initMapPicker();
            }
          }, 250);
        });
      }
    }

    function bindContactPicker() {
      const btn = getEl('btnPickContact');
      const input = getEl('no_hp_input');

      if (btn && btn.dataset.bound !== '1') {
        btn.dataset.bound = '1';
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          pickPhoneFromContacts();
        });
      }

      if (input && input.dataset.contactBound !== '1') {
        input.dataset.contactBound = '1';
      }
    }

    function bindPhoto() {
      const btnCamera = getEl('btnOpenCamera');
      const btnGallery = getEl('btnOpenGallery');
      const cameraInput = getEl('cameraCaptureInput');
      const galleryInput = getEl('galleryInput');
      const lwPhotos = getEl('lwPhotos');
      const snapBtn = getEl('btnSnap');
      const modalEl = getEl('modalCamera');

      if (!btnCamera || !btnGallery || !cameraInput || !galleryInput || !lwPhotos) return;

      if (btnCamera.dataset.bound !== '1') {
        btnCamera.dataset.bound = '1';
        btnCamera.onclick = function() {
          if (isMobileDevice()) {
            cameraInput.click();
          } else {
            openDesktopCamera();
          }
        };
      }

      if (btnGallery.dataset.bound !== '1') {
        btnGallery.dataset.bound = '1';
        btnGallery.onclick = function() {
          galleryInput.click();
        };
      }

      cameraInput.onchange = async function() {
        if (cameraInput.files && cameraInput.files.length) {
          await mergeFilesToLivewire(cameraInput.files);
        }
        cameraInput.value = '';
      };

      galleryInput.onchange = async function() {
        if (galleryInput.files && galleryInput.files.length) {
          await mergeFilesToLivewire(galleryInput.files);
        }
        galleryInput.value = '';
      };

      lwPhotos.onchange = async function() {
        if (lwPhotos.files && lwPhotos.files.length) {
          await renderPhotoPreview(lwPhotos.files);
        } else {
          clearPhotoPreview();
        }
      };

      if (snapBtn && snapBtn.dataset.bound !== '1') {
        snapBtn.dataset.bound = '1';
        snapBtn.onclick = function() {
          snapDesktopPhoto();
        };
      }

      if (modalEl && !modalEl.dataset.bound) {
        modalEl.dataset.bound = '1';
        modalEl.addEventListener('hidden.bs.modal', function() {
          stopCamera();
        });
      }
    }

    function initTanggalDefault() {
      var el = getEl('tanggal_prospek');
      if (!el) return;
      if (!el.value) {
        var d = new Date();
        var mm = String(d.getMonth() + 1).padStart(2, '0');
        var dd = String(d.getDate()).padStart(2, '0');
        el.value = d.getFullYear() + '-' + mm + '-' + dd;
      }
    }

    function bootAll() {
      initTanggalDefault();
      bindLocationButton();
      bindContactPicker();
      initWilayahProspek();
      bindPhoto();
      updatePickedPreview();
    }

    document.addEventListener('DOMContentLoaded', bootAll);
    document.addEventListener('livewire:navigated', function() {
      setTimeout(bootAll, 100);
    });

    document.addEventListener('livewire:init', function() {
      if (!window.Livewire) return;
      Livewire.hook('morphed', function() {
        setTimeout(bootAll, 100);
      });
    });
  })();
  </script>

</div>
