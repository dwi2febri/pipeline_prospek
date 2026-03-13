<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Pipeline Prospek') }}</title>

  {{-- Bootstrap 5 + Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  @livewireStyles

  <style>
    body{background:#f5f7fb;}
    .topbar{background:#4b7bec;color:#fff;}
    .stat-card{border:0;border-radius:14px;color:#fff;overflow:hidden}
    .stat-card .icon{opacity:.25;font-size:42px;position:absolute;right:12px;bottom:6px}
    .stat-card .label{font-size:.9rem;opacity:.9}
    .stat-card .value{font-size:2.0rem;font-weight:800;line-height:1}
    .stat-yellow{background:#f4b400;}
    .stat-green{background:#34a853;}
    .stat-red{background:#ea4335;}
    .stat-blue{background:#4285f4;}
    .card-soft{border:0;border-radius:14px;box-shadow:0 6px 22px rgba(15,23,42,.06)}
    .badge-status{font-weight:700;letter-spacing:.3px}
    .fab{
      position:fixed;right:18px;bottom:76px;z-index:999;
      border-radius:999px;padding:12px 16px;font-weight:700;
      box-shadow:0 10px 30px rgba(0,0,0,.18);
    }
    .bottom-nav{
      position:fixed;left:0;right:0;bottom:0;z-index:998;
      background:#fff;border-top:1px solid #e9edf5;
    }
    .bottom-nav a{flex:1;text-decoration:none;color:#6b7280;padding:10px 0;font-size:12px}
    .bottom-nav a.active{color:#111827;font-weight:700}
    .content-wrap{padding-bottom:78px;}
  </style>
</head>
<body>

  <div class="topbar py-3">
    <div class="container d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <i class="bi bi-people fs-4"></i>
        <div>
          <div class="fw-bold lh-1">Prospek Nasabah</div>
          <div class="small opacity-75">Pipeline Prospek</div>
        </div>
      </div>

      <div class="d-flex align-items-center gap-2">
        <a class="btn btn-light btn-sm" href="/prospects"><i class="bi bi-grid"></i></a>
        <a class="btn btn-light btn-sm" href="/audit-logs"><i class="bi bi-shield-check"></i></a>
        <a class="btn btn-light btn-sm" href="/recycle-bin/prospects"><i class="bi bi-trash3"></i></a>
      </div>
    </div>
  </div>

  <div class="content-wrap">
    {{ $slot }}
  </div>

  <nav class="bottom-nav">
    <div class="container d-flex text-center">
      <a href="/prospects" class="{{ request()->is('prospects') ? 'active' : '' }}">
        <div><i class="bi bi-grid fs-5"></i></div>
        Dashboard
      </a>
      <a href="/prospects" class="{{ request()->is('prospects*') ? 'active' : '' }}">
        <div><i class="bi bi-people fs-5"></i></div>
        Prospek
      </a>
      <a href="/audit-logs" class="{{ request()->is('audit-logs') ? 'active' : '' }}">
        <div><i class="bi bi-file-earmark-text fs-5"></i></div>
        Laporan
      </a>
    </div>
  </nav>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @livewireScripts
</body>
</html>
