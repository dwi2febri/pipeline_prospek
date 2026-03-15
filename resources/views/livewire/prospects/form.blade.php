<div class="container-fluid px-4 py-3">

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
                <option value="{{ $c['id'] }}">{{ $c['text'] }}</option>
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
            <option value="PERTANIAN">Pertanian</option>
            <option value="PERIKANAN">Perikanan</option>
            <option value="PETERNAKAN">Peternakan</option>
            <option value="PERDAGANGAN">Perdagangan</option>
            <option value="JASA">Jasa</option>
            <option value="INDUSTRI_RUMAHAN">Industri Rumahan</option>
            <option value="KARYAWAN">Karyawan</option>
            <option value="WIRASWASTA">Wiraswasta</option>
            <option value="LAINNYA">Lainnya</option>
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
            <option value="TABUNGAN">Tabungan</option>
            <option value="DEPOSITO">Deposito</option>
            <option value="KREDIT">Kredit</option>
            <option value="ASET">Aset</option>
          </select>
          @error('jenis_produk')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 col-md-6">
          <label class="form-label fw-semibold">Lokasi (Device)</label>

          <div class="row g-2">
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span>
                <input id="alamat_input"
                       class="form-control"
                       wire:model="alamat"
                       placeholder="Alamat otomatis dari lokasi perangkat..."
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
                <i class="bi bi-crosshair2 me-1"></i> Ambil Lokasi
              </button>
            </div>
          </div>

          <div class="text-muted small mt-2" id="locHint">
            Klik tombol <b>Ambil Lokasi</b> untuk mengisi lokasi dari device.
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

    {{-- preview client-side saat file baru dipilih --}}
    <div id="photoPreviewWrap" class="row g-2"></div>

    {{-- preview livewire setelah upload selesai --}}
    @if(!empty($photos))
      <div id="serverPreviewWrap" class="row g-2 mt-1">
        @foreach($photos as $idx => $p)
          <div class="col-6 col-md-3">
            <div class="card-soft p-2 position-relative">
              <img src="{{ $p->temporaryUrl() }}" class="w-100"
                   style="border-radius:14px;object-fit:cover;aspect-ratio:1/1;">
              <button type="button"
                      class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 m-2"
                      wire:click="removeTempPhoto({{ $idx }})">
                <i class="bi bi-x"></i>
              </button>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  @if($id && isset($docs) && $docs->count())
    <div class="mt-3">
      <div class="fw-semibold mb-2">Foto tersimpan</div>
      <div class="row g-2">
        @foreach($docs as $doc)
          <div class="col-6 col-md-3">
            <div class="card-soft p-2 position-relative">
              <img src="{{ $doc->url }}" class="w-100"
                   style="border-radius:14px;object-fit:cover;aspect-ratio:1/1;">
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

  <script>
    (function(){
      var el = document.getElementById('tanggal_prospek');
      if(!el) return;
      if(!el.value){
        var d = new Date();
        var mm = String(d.getMonth()+1).padStart(2,'0');
        var dd = String(d.getDate()).padStart(2,'0');
        el.value = d.getFullYear() + '-' + mm + '-' + dd;
      }
    })();
  </script>

  <script>
    (function(){
      function $(id){ return document.getElementById(id); }

      function setHint(msg, isErr){
        var hint = $('locHint');
        if(!hint) return;
        hint.innerHTML = msg;
        hint.className = "small mt-2 " + (isErr ? "text-danger" : "text-muted");
      }

      function isSecurePage(){
        return window.isSecureContext === true
          || location.protocol === 'https:'
          || location.hostname === 'localhost'
          || location.hostname === '127.0.0.1';
      }

      async function reverseGeocode(lat, lng){
        var url1 = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=" + encodeURIComponent(lat) + "&lon=" + encodeURIComponent(lng);
        var url2 = "https://geocode.maps.co/reverse?lat=" + encodeURIComponent(lat) + "&lon=" + encodeURIComponent(lng);

        async function tryFetch(url){
          try{
            const res = await fetch(url, {
              method: 'GET',
              headers: { 'Accept': 'application/json' }
            });
            if(!res.ok) return null;
            const data = await res.json();
            if (data && data.display_name) return data.display_name;
            if (data && data.address) return Object.values(data.address).filter(Boolean).join(', ');
            return null;
          }catch(e){
            return null;
          }
        }

        return (await tryFetch(url1)) || (await tryFetch(url2)) || null;
      }

      async function fillLocation(){
        var btn = $('btnGetLoc');
        if(!btn) return;

        if(!navigator.geolocation){
          setHint("Browser tidak mendukung GPS.", true);
          return;
        }

        if(!isSecurePage()){
          setHint("Lokasi hanya bisa dipakai di HTTPS / localhost.", true);
          return;
        }

        btn.disabled = true;
        setHint("Mengambil lokasi dari device...", false);

        navigator.geolocation.getCurrentPosition(
          async function(pos){
            try{
              var lat = String(pos.coords.latitude);
              var lng = String(pos.coords.longitude);

              if(window.Livewire){
                window.Livewire.dispatch('setLatLngProspek', { lat: lat, lng: lng });
              }

              var latInput = $('lokasi_lat');
              var lngInput = $('lokasi_lng');
              if(latInput) latInput.value = lat;
              if(lngInput) lngInput.value = lng;

              var addr = await reverseGeocode(lat, lng);

              if(addr){
                if(window.Livewire){
                  window.Livewire.dispatch('setAlamatProspek', { alamat: addr });
                }

                var alamatInput = $('alamat_input');
                if(alamatInput) alamatInput.value = addr;

                setHint("Alamat terisi otomatis ✅", false);
              } else {
                setHint("Lat/Lng terisi ✅, alamat belum bisa didapat.", true);
              }
            }catch(e){
              console.error(e);
              setHint("Gagal memproses lokasi.", true);
            }finally{
              btn.disabled = false;
            }
          },
          function(err){
            btn.disabled = false;

            if(err && err.code === 1){
              setHint("Izin lokasi ditolak. Aktifkan permission lokasi di browser.", true);
            }else if(err && err.code === 2){
              setHint("Lokasi tidak tersedia. Nyalakan GPS & coba lagi.", true);
            }else if(err && err.code === 3){
              setHint("Request lokasi timeout. Coba lagi.", true);
            }else{
              setHint("Gagal mengambil lokasi.", true);
            }
          },
          {
            enableHighAccuracy: true,
            timeout: 20000,
            maximumAge: 0
          }
        );
      }

      function bindLocation(){
        var btn = $('btnGetLoc');
        if(!btn) return;
        if(btn.dataset.bound === "1") return;
        btn.dataset.bound = "1";
        btn.addEventListener('click', fillLocation);
      }

      document.addEventListener('DOMContentLoaded', bindLocation);
      document.addEventListener('livewire:navigated', bindLocation);
    })();
  </script>

  <script>
    function initWilayahProspek() {
      const PROV_ID = '33';

      const kabSelect = document.getElementById('kabKotaSelect');
      const kecSelect = document.getElementById('kecamatanSelect');
      const desaSelect = document.getElementById('desaSelect');

      const kabHidden = document.getElementById('kab_kota_hidden');
      const kecHidden = document.getElementById('kecamatan_hidden');
      const desaHidden = document.getElementById('desa_hidden');

      const kodeProvHidden = document.getElementById('kode_provinsi_hidden');
      const kodeKabHidden = document.getElementById('kode_kab_kota_hidden');
      const kodeKecHidden = document.getElementById('kode_kecamatan_hidden');
      const kodeDesaHidden = document.getElementById('kode_desa_hidden');

      if (!kabSelect || !kecSelect || !desaSelect || !kabHidden || !kecHidden || !desaHidden) {
        return;
      }

      function setWireValue(hiddenEl, value) {
        hiddenEl.value = value || '';
        hiddenEl.dispatchEvent(new Event('input', { bubbles: true }));
        hiddenEl.dispatchEvent(new Event('change', { bubbles: true }));
      }

      function resetSelect(el, placeholder, disabled = true) {
        el.innerHTML = `<option value="">${placeholder}</option>`;
        el.disabled = disabled;
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

        let found = list.find(item => normalizeText(item.name) === target);
        if (found) return found;

        found = list.find(item =>
          normalizeText(item.name).includes(target) ||
          target.includes(normalizeText(item.name))
        );

        return found || null;
      }

      async function loadKabupaten(initialName = '') {
        resetSelect(kabSelect, '-- Loading Kab/Kota --', true);

        const json = await fetchJson(`/api-wilayah/regencies/${PROV_ID}`);
        const list = Array.isArray(json.data) ? json.data : [];

        kabSelect.innerHTML = '<option value="">-- Pilih Kab/Kota --</option>';

        list.forEach(item => {
          const opt = document.createElement('option');
          opt.value = item.code;
          opt.textContent = item.name;
          kabSelect.appendChild(opt);
        });

        if (initialName) {
          const found = findByNameLoose(list, initialName);
          if (found) {
            kabSelect.value = found.code;
          }
        }

        kabSelect.disabled = false;
        return list;
      }

      async function loadKecamatan(regencyCode, initialName = '') {
        if (!regencyCode) {
          resetSelect(kecSelect, '-- Pilih Kecamatan --', true);
          resetSelect(desaSelect, '-- Pilih Desa --', true);
          return [];
        }

        resetSelect(kecSelect, '-- Loading Kecamatan --', true);

        const json = await fetchJson(`/api-wilayah/districts/${regencyCode}`);
        const list = Array.isArray(json.data) ? json.data : [];

        kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

        list.forEach(item => {
          const opt = document.createElement('option');
          opt.value = item.code;
          opt.textContent = item.name;
          kecSelect.appendChild(opt);
        });

        if (initialName) {
          const found = findByNameLoose(list, initialName);
          if (found) {
            kecSelect.value = found.code;
          }
        }

        kecSelect.disabled = false;
        return list;
      }

      async function loadDesa(districtCode, initialName = '') {
        if (!districtCode) {
          resetSelect(desaSelect, '-- Pilih Desa --', true);
          return [];
        }

        resetSelect(desaSelect, '-- Loading Desa --', true);

        const json = await fetchJson(`/api-wilayah/villages/${districtCode}`);
        const list = Array.isArray(json.data) ? json.data : [];

        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

        list.forEach(item => {
          const opt = document.createElement('option');
          opt.value = item.code;
          opt.textContent = item.name;
          desaSelect.appendChild(opt);
        });

        if (initialName) {
          const found = findByNameLoose(list, initialName);
          if (found) {
            desaSelect.value = found.code;
          }
        }

        desaSelect.disabled = false;
        return list;
      }

      if (kabSelect.dataset.bound !== '1') {
        kabSelect.dataset.bound = '1';
        kabSelect.addEventListener('change', async function () {
          const selectedText = this.value ? this.options[this.selectedIndex].text : '';
          setWireValue(kabHidden, selectedText);
          setWireValue(kodeKabHidden, this.value || '');
          setWireValue(kodeProvHidden, PROV_ID);
          setWireValue(kecHidden, '');
          setWireValue(desaHidden, '');
          setWireValue(kodeKecHidden, '');
          setWireValue(kodeDesaHidden, '');

          resetSelect(desaSelect, '-- Pilih Desa --', true);
          await loadKecamatan(this.value, '');
        });
      }

      if (kecSelect.dataset.bound !== '1') {
        kecSelect.dataset.bound = '1';
        kecSelect.addEventListener('change', async function () {
          const selectedText = this.value ? this.options[this.selectedIndex].text : '';
          setWireValue(kecHidden, selectedText);
          setWireValue(kodeKecHidden, this.value || '');
          setWireValue(desaHidden, '');
          setWireValue(kodeDesaHidden, '');

          await loadDesa(this.value, '');
        });
      }

      if (desaSelect.dataset.bound !== '1') {
        desaSelect.dataset.bound = '1';
        desaSelect.addEventListener('change', function () {
          const selectedText = this.value ? this.options[this.selectedIndex].text : '';
          setWireValue(desaHidden, selectedText);
          setWireValue(kodeDesaHidden, this.value || '');
        });
      }

      (async function () {
        try {
          setWireValue(kodeProvHidden, PROV_ID);

          const initialKab = kabHidden.value || '';
          const initialKec = kecHidden.value || '';
          const initialDesa = desaHidden.value || '';

          const kabList = await loadKabupaten(initialKab);

          if (initialKab) {
            const selectedKab = findByNameLoose(kabList, initialKab);
            if (selectedKab) {
              kabSelect.value = selectedKab.code;
              setWireValue(kodeKabHidden, selectedKab.code);

              const kecList = await loadKecamatan(selectedKab.code, initialKec);

              if (initialKec) {
                const selectedKec = findByNameLoose(kecList, initialKec);
                if (selectedKec) {
                  kecSelect.value = selectedKec.code;
                  setWireValue(kodeKecHidden, selectedKec.code);

                  const desaList = await loadDesa(selectedKec.code, initialDesa);

                  if (initialDesa) {
                    const selectedDesa = findByNameLoose(desaList, initialDesa);
                    if (selectedDesa) {
                      desaSelect.value = selectedDesa.code;
                      setWireValue(kodeDesaHidden, selectedDesa.code);
                    }
                  }
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
      })();
    }

    document.addEventListener('DOMContentLoaded', initWilayahProspek);
    document.addEventListener('livewire:navigated', initWilayahProspek);
  </script>

    <script>
    (function () {
    let mediaStream = null;
    let modalInstance = null;
    let previewUrls = [];

    function isMobileDevice() {
        return /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent || '');
    }

    function getEl(id) {
        return document.getElementById(id);
    }

    function clearPreviewUrls() {
        previewUrls.forEach(function (url) {
        try { URL.revokeObjectURL(url); } catch (e) {}
        });
        previewUrls = [];
    }

    function renderClientPreview(files) {
        const wrap = getEl('photoPreviewWrap');
        if (!wrap) return;

        clearPreviewUrls();
        wrap.innerHTML = '';

        if (!files || !files.length) return;

        Array.from(files).forEach(function (file, idx) {
        if (!file.type || !file.type.startsWith('image/')) return;

        const url = URL.createObjectURL(file);
        previewUrls.push(url);

        const col = document.createElement('div');
        col.className = 'col-6 col-md-3';
        col.innerHTML = `
            <div class="card-soft p-2 position-relative">
            <img src="${url}" class="w-100"
                style="border-radius:14px;object-fit:cover;aspect-ratio:1/1;">
            <button type="button"
                    class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 m-2 btn-remove-preview"
                    data-idx="${idx}">
                <i class="bi bi-x"></i>
            </button>
            </div>
        `;
        wrap.appendChild(col);
        });

        wrap.querySelectorAll('.btn-remove-preview').forEach(function (btn) {
        btn.onclick = function () {
            const removeIdx = parseInt(this.getAttribute('data-idx'), 10);
            const lwPhotos = getEl('lwPhotos');
            if (!lwPhotos || !lwPhotos.files) return;

            const dt = new DataTransfer();
            Array.from(lwPhotos.files).forEach(function (file, i) {
            if (i !== removeIdx) dt.items.add(file);
            });

            lwPhotos.files = dt.files;
            lwPhotos.dispatchEvent(new Event('change', { bubbles: true }));
            renderClientPreview(lwPhotos.files);
        };
        });
    }

    function mergeFilesToLivewire(sourceFiles) {
        const lwPhotos = getEl('lwPhotos');
        if (!lwPhotos || !sourceFiles || !sourceFiles.length) return;

        const dt = new DataTransfer();

        if (lwPhotos.files && lwPhotos.files.length) {
        Array.from(lwPhotos.files).forEach(function (file) {
            dt.items.add(file);
        });
        }

        Array.from(sourceFiles).forEach(function (file) {
        dt.items.add(file);
        });

        lwPhotos.files = dt.files;
        renderClientPreview(lwPhotos.files);
        lwPhotos.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function stopCamera() {
        if (mediaStream) {
        mediaStream.getTracks().forEach(function (track) {
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

        canvas.toBlob(function (blob) {
        if (!blob) return;

        const file = new File(
            [blob],
            'camera-' + Date.now() + '.jpg',
            { type: 'image/jpeg' }
        );

        mergeFilesToLivewire([file]);

        if (modalInstance) modalInstance.hide();
        stopCamera();
        }, 'image/jpeg', 0.92);
    }

    function bindPhoto() {
        const btnCamera = getEl('btnOpenCamera');
        const btnGallery = getEl('btnOpenGallery');
        const cameraInput = getEl('cameraCaptureInput');
        const galleryInput = getEl('galleryInput');
        const snapBtn = getEl('btnSnap');
        const modalEl = getEl('modalCamera');
        const lwPhotos = getEl('lwPhotos');

        if (!btnCamera || !btnGallery || !cameraInput || !galleryInput || !lwPhotos) return;

        btnCamera.onclick = function () {
        if (isMobileDevice()) {
            cameraInput.click();
        } else {
            openDesktopCamera();
        }
        };

        btnGallery.onclick = function () {
        galleryInput.click();
        };

        cameraInput.onchange = function () {
        if (cameraInput.files && cameraInput.files.length) {
            mergeFilesToLivewire(cameraInput.files);
        }
        cameraInput.value = '';
        };

        galleryInput.onchange = function () {
        if (galleryInput.files && galleryInput.files.length) {
            mergeFilesToLivewire(galleryInput.files);
        }
        galleryInput.value = '';
        };

        lwPhotos.onchange = function () {
        renderClientPreview(lwPhotos.files);
        };

        if (snapBtn) {
        snapBtn.onclick = function () {
            snapDesktopPhoto();
        };
        }

        if (modalEl && !modalEl.dataset.bound) {
        modalEl.dataset.bound = '1';
        modalEl.addEventListener('hidden.bs.modal', function () {
            stopCamera();
        });
        }
    }

    function resetClientPreviewWhenUploadDone() {
        const wrap = getEl('photoPreviewWrap');
        if (!wrap) return;
        wrap.innerHTML = '';
        clearPreviewUrls();
    }

    document.addEventListener('livewire-upload-start', function () {
        // preview JS tetap tampil saat upload berjalan
    });

    document.addEventListener('livewire-upload-finish', function () {
        // setelah upload selesai, biarkan preview server-side ($photos) yang tampil
        setTimeout(function () {
        resetClientPreviewWhenUploadDone();
        bindPhoto();
        }, 150);
    });

    document.addEventListener('livewire-upload-error', function () {
        setTimeout(function () {
        bindPhoto();
        }, 150);
    });

    document.addEventListener('DOMContentLoaded', bindPhoto);
    document.addEventListener('livewire:navigated', bindPhoto);

    document.addEventListener('livewire:init', function () {
        if (!window.Livewire) return;
        Livewire.hook('morphed', function () {
        setTimeout(bindPhoto, 50);
        });
    });
    })();
    </script>

</div>
