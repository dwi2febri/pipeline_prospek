<x-layouts.app>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <div class="fw-bold fs-4">Prospek</div>
      <div class="text-muted">Data diambil via API</div>
    </div>
    <a href="/app/prospects/create" class="btn btn-primary rounded-pill px-4">+ Input Prospek</a>
  </div>

  <div class="card-soft p-3 mb-3">
    <input id="q" class="form-control" placeholder="Cari nama/no hp/nik...">
  </div>

  <div class="card-soft overflow-hidden">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>No HP</th>
            <th>Cabang</th>
            <th>Status</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody id="rows">
          <tr><td colspan="6" class="p-4 text-center text-muted">Loading...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    const rows = document.getElementById('rows');
    const q = document.getElementById('q');

    async function load(){
      rows.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-muted">Loading...</td></tr>`;
      const res = await API.get('/prospects?search=' + encodeURIComponent(q.value||''));
      const items = res.data || res.items || res;

      if(!items || items.length===0){
        rows.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-muted">Tidak ada data.</td></tr>`;
        return;
      }

      rows.innerHTML = items.map(p=>`
        <tr>
          <td>${p.tanggal_prospek ?? '-'}</td>
          <td>${p.nama ?? '-'}</td>
          <td>${p.no_hp ?? '-'}</td>
          <td>${p.cabang?.nama_cabang ? (p.cabang.kode_cabang+' - '+p.cabang.nama_cabang) : '-'}</td>
          <td>${p.status ?? '-'}</td>
          <td class="text-end">
            <a class="btn btn-outline-primary btn-sm rounded-pill" href="/app/prospects/${p.id}/edit">Edit</a>
          </td>
        </tr>
      `).join('');
    }

    q.addEventListener('input', ()=>{ clearTimeout(window.__t); window.__t=setTimeout(load, 350); });
    load();
  </script>
</x-layouts.app>
