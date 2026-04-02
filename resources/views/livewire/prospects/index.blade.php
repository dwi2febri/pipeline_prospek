<div class="container-fluid px-0">

  @if(session('ok'))
    <div class="alert alert-success rounded-4 shadow-sm">{{ session('ok') }}</div>
  @endif

  @php
    $desktopCards = [
      ['key'=>'ALL', 'count_key'=>'TOTAL', 'label'=>'Total', 'bg'=>'linear-gradient(135deg,#f59e0b 0%,#d97706 100%)', 'icon'=>'bi-collection', 'text'=>'#fff', 'border'=>'0'],
      ['key'=>'OPEN', 'count_key'=>'OPEN', 'label'=>'Open', 'bg'=>'linear-gradient(135deg,#ffcfcf 0%,#fc9d9d 100%)', 'icon'=>'bi-folder2-open', 'text'=>'#fff', 'border'=>'1px solid #fc9d9d'],
      ['key'=>'FOLLOW UP', 'count_key'=>'FOLLOW UP', 'label'=>'Follow Up', 'bg'=>'linear-gradient(135deg,#10b981 0%,#059669 100%)', 'icon'=>'bi-arrow-repeat', 'text'=>'#fff', 'border'=>'0'],
      ['key'=>'REJECTED', 'count_key'=>'REJECTED', 'label'=>'Rejected', 'bg'=>'linear-gradient(135deg,#fb7185 0%,#ef4444 100%)', 'icon'=>'bi-x-circle', 'text'=>'#fff', 'border'=>'0'],
      ['key'=>'CLOSING', 'count_key'=>'CLOSING', 'label'=>'Closing', 'bg'=>'linear-gradient(135deg,#60a5fa 0%,#2563eb 100%)', 'icon'=>'bi-check2-circle', 'text'=>'#fff', 'border'=>'0'],
    ];

    $mobileTopCard = ['key'=>'ALL', 'count_key'=>'TOTAL', 'label'=>'Total', 'bg'=>'linear-gradient(135deg,#f59e0b 0%,#d97706 100%)', 'icon'=>'bi-collection', 'text'=>'#fff', 'border'=>'2px solid #111827'];

    $mobileGridCards = [
      ['key'=>'OPEN', 'count_key'=>'OPEN', 'label'=>'Open', 'bg'=>'linear-gradient(135deg,#ffcfcf 0%,#fc9d9d 100%)', 'icon'=>'bi-folder2-open', 'text'=>'#fff', 'border'=>'1px solid #fc9d9d'],
      ['key'=>'FOLLOW UP', 'count_key'=>'FOLLOW UP', 'label'=>'Follow Up', 'bg'=>'linear-gradient(135deg,#10b981 0%,#059669 100%)', 'icon'=>'bi-arrow-repeat', 'text'=>'#fff', 'border'=>'0'],
      ['key'=>'CLOSING', 'count_key'=>'CLOSING', 'label'=>'Closing', 'bg'=>'linear-gradient(135deg,#60a5fa 0%,#2563eb 100%)', 'icon'=>'bi-check2-circle', 'text'=>'#fff', 'border'=>'0'],
      ['key'=>'REJECTED', 'count_key'=>'REJECTED', 'label'=>'Rejected', 'bg'=>'linear-gradient(135deg,#fb7185 0%,#ef4444 100%)', 'icon'=>'bi-x-circle', 'text'=>'#fff', 'border'=>'0'],
    ];
  @endphp

  {{-- DESKTOP / TABLET --}}
  <div class="d-none d-md-block">
    <div class="row g-2">
      @foreach($desktopCards as $c)
        <div class="col-12 col-md-6 col-xl">
          <button
            type="button"
            class="w-100 text-start position-relative p-3"
            wire:click="setStatus('{{ $c['key'] }}')"
            style="
              border: {{ $status === $c['key'] ? '2px solid #111827' : $c['border'] }};
              border-radius:16px;
              color:{{ $c['text'] }};
              overflow:hidden;
              background:{!! $c['bg'] !!};
              box-shadow:0 12px 30px rgba(15,23,42,.12);
              min-height:96px;
            "
          >
            <div style="font-size:.95rem;opacity:.95;font-weight:700;">{{ $c['label'] }}</div>
            <div style="font-size:2.1rem;font-weight:900;line-height:1;">
              {{ $summary[$c['count_key']] ?? 0 }}
            </div>
            <div style="position:absolute;right:14px;bottom:10px;opacity:.22;font-size:48px;">
              <i class="bi {{ $c['icon'] }}"></i>
            </div>
          </button>
        </div>
      @endforeach
    </div>
  </div>

  {{-- MOBILE / ANDROID --}}
  <div class="d-block d-md-none">
    <div class="mb-2">
      <button
        type="button"
        class="w-100 text-start position-relative p-3"
        wire:click="setStatus('{{ $mobileTopCard['key'] }}')"
        style="
          border: {{ $status === $mobileTopCard['key'] ? '2px solid #111827' : $mobileTopCard['border'] }};
          border-radius:16px;
          color:{{ $mobileTopCard['text'] }};
          overflow:hidden;
          background:{!! $mobileTopCard['bg'] !!};
          box-shadow:0 12px 30px rgba(15,23,42,.12);
          min-height:96px;
        "
      >
        <div style="font-size:.95rem;opacity:.95;font-weight:700;">{{ $mobileTopCard['label'] }}</div>
        <div style="font-size:2.1rem;font-weight:900;line-height:1;">
          {{ $summary[$mobileTopCard['count_key']] ?? 0 }}
        </div>
        <div style="position:absolute;right:14px;bottom:10px;opacity:.22;font-size:48px;">
          <i class="bi {{ $mobileTopCard['icon'] }}"></i>
        </div>
      </button>
    </div>

    <div class="row g-2">
      @foreach($mobileGridCards as $c)
        <div class="col-6">
          <button
            type="button"
            class="w-100 text-start position-relative p-3"
            wire:click="setStatus('{{ $c['key'] }}')"
            style="
              border: {{ $status === $c['key'] ? '2px solid #111827' : $c['border'] }};
              border-radius:16px;
              color:{{ $c['text'] }};
              overflow:hidden;
              background:{!! $c['bg'] !!};
              box-shadow:0 12px 30px rgba(15,23,42,.12);
              min-height:96px;
            "
          >
            <div style="font-size:.95rem;opacity:.95;font-weight:700;">{{ $c['label'] }}</div>
            <div style="font-size:2rem;font-weight:900;line-height:1;">
              {{ $summary[$c['count_key']] ?? 0 }}
            </div>
            <div style="position:absolute;right:12px;bottom:10px;opacity:.18;font-size:38px;">
              <i class="bi {{ $c['icon'] }}"></i>
            </div>
          </button>
        </div>
      @endforeach
    </div>
  </div>

  <div class="card-soft p-3 mt-3">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input
            class="form-control"
            placeholder="Cari nama / no hp / nik / alamat..."
            wire:model.live.debounce.400ms="search"
            style="border-left:0;"
          >
        </div>
      </div>

      <div class="col-12 col-md-3">
        <select class="form-select" wire:model.live="periode">
          <option value="hari_ini">Hari ini</option>
          <option value="bulan_ini">Bulan ini</option>
          <option value="semua">Semua</option>
        </select>
      </div>

      <div class="col-12 col-md-3 text-md-end">
        <span class="badge bg-dark rounded-pill px-3 py-2">
          Status:
          {{ $status === 'ALL' ? 'TOTAL' : $status }}
        </span>
      </div>
    </div>
  </div>

  <div class="mt-3">
    <div class="fw-bold mb-2">
      Pengajuan Prospek Saya ({{ $items->total() }})
    </div>

    @forelse($items as $p)
      @php
        $badgeClass = 'bg-secondary';
        $produkBadgeClass = 'bg-light text-dark';

        if($p->status === 'OPEN') $badgeClass = 'bg-info text-dark';
        elseif($p->status === 'FOLLOW UP') $badgeClass = 'bg-warning text-dark';
        elseif($p->status === 'REJECTED') $badgeClass = 'bg-danger';
        elseif($p->status === 'CLOSING') $badgeClass = 'bg-primary';
      @endphp

      <div class="card-soft p-3 mb-2" wire:key="prospect-card-{{ $p->id }}">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
          <div class="flex-grow-1">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
              <div class="fw-bold">{{ $p->nama }}</div>
              <span class="badge {{ $badgeClass }} fw-bold">
                {{ $p->status ?: '-' }}
              </span>
            </div>

            <div class="text-muted small mt-1">
              <i class="bi bi-telephone"></i> {{ $p->no_hp ?: '-' }}
              &nbsp;•&nbsp;
              <i class="bi bi-person-vcard"></i> {{ $p->nik ?: '-' }}
            </div>

            <div class="text-muted small mt-1">
              <i class="bi bi-calendar-event"></i> {{ $p->tanggal_prospek }}
              &nbsp;•&nbsp;
              <i class="bi bi-building"></i> {{ $p->cabang->nama_cabang ?? '-' }}
              &nbsp;•&nbsp;
              <span class="badge {{ $produkBadgeClass }}">{{ $p->jenis_produk ?: '-' }}</span>
            </div>

            @if($p->alamat)
              <div class="text-muted small mt-1">
                <i class="bi bi-geo-alt"></i> {{ $p->alamat }}
              </div>
            @endif
          </div>

          <div class="text-end" style="min-width:140px;">
            <a class="btn btn-outline-primary btn-sm w-100 mb-1"
               href="{{ route('prospects.edit', $p->id) }}">
              <i class="bi bi-pencil"></i> Detail
            </a>

            <button
              type="button"
              class="btn btn-outline-danger btn-sm w-100"
              wire:click="trash({{ $p->id }})"
              wire:loading.attr="disabled"
              wire:target="trash({{ $p->id }})"
              onclick="return confirm('Pindahkan ke Recycle Bin?')">
              <i class="bi bi-trash"></i> Hapus
            </button>
          </div>
        </div>
      </div>
    @empty
      <div class="card-soft p-4 text-center text-muted">
        Belum ada data prospek.
      </div>
    @endforelse

    <div class="mt-3">
      {{ $items->links() }}
    </div>
  </div>

  <a href="{{ route('prospects.create') }}" class="btn btn-primary"
     style="position:fixed;right:18px;bottom:86px;z-index:1040;border-radius:999px;padding:12px 16px;font-weight:800;box-shadow:0 10px 30px rgba(0,0,0,.18);">
    <i class="bi bi-plus-circle"></i> Input Prospek
  </a>
</div>
