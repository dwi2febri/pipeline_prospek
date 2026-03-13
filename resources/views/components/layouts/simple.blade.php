<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Pipeline Prospek') }} - Login</title>

  {{-- Bootstrap 5 + Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  @livewireStyles

  <style>
    body{
      min-height:100vh;
      background: radial-gradient(1200px 600px at 50% -20%, #4b7bec 0%, #0b1220 55%, #05070d 100%);
    }
    .card-soft{border:0;border-radius:18px;box-shadow:0 18px 50px rgba(0,0,0,.35)}
    .brand-dot{
      width:46px;height:46px;border-radius:14px;
      display:grid;place-items:center;
      background:#4b7bec;color:#fff;
      box-shadow:0 10px 30px rgba(75,123,236,.35);
    }
    .muted{color:rgba(255,255,255,.75)}
    .form-control, .form-select{border-radius:12px;padding:.7rem .9rem}
    .btn{border-radius:12px;padding:.75rem 1rem;font-weight:700}
    a{color:#cfe0ff}
    a:hover{color:#ffffff}
  </style>
</head>
<body class="d-flex align-items-center">

  <div class="container py-5" style="max-width:520px;">
    <div class="text-center mb-3">
      <div class="brand-dot mx-auto mb-2"><i class="bi bi-people-fill fs-4"></i></div>
      <div class="text-white fw-bold fs-4">Log in</div>
      <div class="muted small">Pipeline Prospek Nasabah</div>
    </div>

    <div class="card-soft bg-white p-4">
      {{ $slot }}
    </div>

    <div class="text-center mt-3 muted small">
      © {{ date('Y') }} {{ config('app.name','Pipeline Prospek') }}
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @livewireScripts
</body>
</html>
