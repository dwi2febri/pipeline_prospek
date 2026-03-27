<div class="container-fluid px-0">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-4">{{ $id ? 'Detail Prospek' : 'Input Prospek' }}</div>
      <div class="text-muted">Isi data prospek nasabah dengan lengkap</div>
    </div>

    <a href="{{ route('prospects.index') }}" class="btn btn-light rounded-pill px-4">
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
            <input class="form-control"
                   type="text"
                   inputmode="numeric"
                   pattern="[0-9]*"
                   wire:model.live="no_hp"
                   oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                   placeholder="08xxxx">
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
        <div class="text-muted small mt-1">Pegawai bebas memilih cabang.</div>
        </div>

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

        <div class="col-12">
          <label class="form-label fw-semibold">Keterangan Usaha</label>
          <textarea class="form-control" rows="3" wire:model="keterangan_usaha"
                    placeholder="Contoh: jualan mainan anak..."></textarea>
          @error('keterangan_usaha')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
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

        <div class="col-12 col-md-6">
            <label class="form-label fw-semibold">Lokasi</label>

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
                <button type="button" class="btn btn-primary" id="btnGetLoc">
                    <i class="bi bi-crosshair2 me-1"></i> Lokasi Saat Ini
                </button>
                </div>

                <div class="col-12 d-grid">
                <button type="button" class="btn btn-outline-primary" id="btnOpenMapPicker">
                    <i class="bi bi-map me-1"></i> Pilih Titik di Peta
                </button>
                </div>
            </div>

            <div class="text-muted small mt-2" id="locHint">
                Pilih <b>Lokasi Saat Ini</b> atau gunakan <b>Pilih Titik di Peta</b>.
            </div>

            @error('lokasi_lat')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            @error('lokasi_lng')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">Kab/Kota</label>
          <div wire:ignore>
            <select id="kabKotaSelect" class="form-select">
              <option value="">-- Pilih Kab/Kota --</option>
            </select>
          </div>
          <input type="hidden" id="kab_kota_hidden" wire:model.live="kab_kota">
          <input type="hidden" id="kode_kab_kota_hidden" wire:model.live="kode_kab_kota">
          @error('kab_kota')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">Kecamatan</label>
          <div wire:ignore>
            <select id="kecamatanSelect" class="form-select" disabled>
              <option value="">-- Pilih Kecamatan --</option>
            </select>
          </div>
          <input type="hidden" id="kecamatan_hidden" wire:model.live="kecamatan">
          <input type="hidden" id="kode_kecamatan_hidden" wire:model.live="kode_kecamatan">
          @error('kecamatan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">Desa</label>
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

<div class="col-12">
  <label class="form-label fw-semibold">Dokumentasi (Foto)</label>

  <input id="lwPhotos" type="file" class="d-none" accept="image/*" multiple wire:model="photos">
  <input id="cameraCaptureInput" type="file" class="d-none" accept="image/*" capture="environment">
  <input id="galleryInput" type="file" class="d-none" accept="image/*" multiple>

  <div class="d-flex flex-wrap gap-2 align-items-center">
    <button type="button" class="btn btn-primary rounded-pill px-4" id="btnOpenCamera">
      <i class="bi bi-camera me-1"></i> Ambil Foto
    </button>

    <button type="button" class="btn btn-outline-primary rounded-pill px-4" id="btnOpenGallery">
      <i class="bi bi-images me-1"></i> Pilih dari Galeri
    </button>

    <div class="text-muted small">
      Maksimal 5MB per foto.
    </div>
  </div>

  <div class="small text-muted mt-2" wire:loading wire:target="photos">Mengunggah foto...</div>
  @error('photos') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
  @error('photos.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

  <div class="mt-3">
    <div class="fw-semibold mb-2">Preview foto dipilih</div>
    <div id="photoPreviewWrap" class="row g-2" wire:ignore></div>
  </div>

  @if($id && isset($docs) && $docs->count())
    <div class="mt-3">
      <div class="fw-semibold mb-2">Foto tersimpan</div>
      <div class="row g-2">
        @foreach($docs as $doc)
          <div class="col-6 col-md-3">
            <div class="card-soft p-2 position-relative">
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

        <div class="col-12">
          <label class="form-label fw-semibold">Catatan</label>
          <textarea class="form-control" rows="3" wire:model="catatan"
                    placeholder="Catatan tambahan..."></textarea>
          @error('catatan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 d-flex flex-wrap gap-2 mt-2">
          <button class="btn btn-primary rounded-pill px-4" wire:click.prevent="save">
            <i class="bi bi-save me-1"></i> Simpan
          </button>

          <a class="btn btn-light rounded-pill px-4" href="{{ route('prospects.index') }}">
            Batal
          </a>
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

    <style>
    #mapPicker .leaflet-container,
    #mapPicker {
        background: #f8fafc !important;
    }
    </style>

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
