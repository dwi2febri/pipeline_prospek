<div class="container-fluid px-0">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <div class="fw-bold fs-3">Audit Logs</div>
      <div class="text-muted">Riwayat aktivitas user (otomatis tercatat)</div>
    </div>
  </div>

  <div class="card-soft p-3 mb-3">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md-7">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input class="form-control" style="border-left:0"
                 placeholder="Cari action/type/id/ip/nama..."
                 wire:model.debounce.400ms="search">
        </div>
      </div>
      <div class="col-12 col-md-5 text-md-end text-muted small">
        Total: <span class="fw-bold">{{ $items->total() }}</span> log
      </div>
    </div>
  </div>

  <div class="card-soft overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="min-width:170px;">Waktu</th>
            <th style="min-width:320px;">Action</th>
            <th style="min-width:140px;">Actor</th>
            <th style="min-width:140px;">IP</th>
            <th style="min-width:220px;">Meta</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $l)
            <tr>
              <td class="small text-muted">
                {{ $l->created_at?->format('d/m/Y H:i:s') }}
              </td>
              <td class="small">
                <div class="fw-semibold">{{ $l->action }}</div>
                @if($l->type || $l->model_id)
                  <div class="text-muted">Type: {{ $l->type ?? '-' }} • ID: {{ $l->model_id ?? '-' }}</div>
                @endif
              </td>
              <td class="small">
                {{ $l->actor_name ?? ($l->user->name ?? '-') }}
              </td>
              <td class="small">{{ $l->ip ?? '-' }}</td>
              <td class="small text-muted" style="max-width:420px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ $l->meta }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted p-5">
                Belum ada log. Coba lakukan aksi (toggle, simpan, delete) lalu refresh.
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
</div>
