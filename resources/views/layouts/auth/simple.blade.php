<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Login - {{ config('app.name','Pipeline Prospek') }}</title>

  {{-- INI YANG PENTING: pakai asset internal hasil build vite --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @livewireStyles

  <style>
    body{
      min-height:100vh;
      background: radial-gradient(1100px 520px at 50% -15%, rgba(75,123,236,.55) 0%, rgba(11,18,32,1) 55%, rgba(5,7,13,1) 100%);
    }
    .card-soft{border:0;border-radius:18px;box-shadow:0 18px 55px rgba(0,0,0,.35)}
    .brand-dot{
      width:54px;height:54px;border-radius:16px;
      display:grid;place-items:center;
      background:#4b7bec;color:#fff;
      box-shadow:0 14px 40px rgba(75,123,236,.35);
    }
    .form-control,.btn,.input-group-text{border-radius:12px}
    .input-group-text{background:#fff}
    .form-control{padding:.75rem .9rem}
    .btn{padding:.8rem 1rem;font-weight:800}
  </style>
</head>

<body class="d-flex align-items-center">
  <div class="container py-5" style="max-width:520px;">
    <div class="text-center mb-3">
      <div class="brand-dot mx-auto mb-2"><i class="bi bi-people-fill fs-3"></i></div>
      <div class="text-white fw-bold fs-4">Pipeline Prospek</div>
      <div class="text-white-50 small">Login untuk melanjutkan</div>
    </div>

    <div class="card-soft bg-white p-4">
      {{ $slot }}
    </div>

    <div class="text-center mt-3 text-white-50 small">
      © {{ date('Y') }} {{ config('app.name','Pipeline Prospek') }}
    </div>
  </div>

  @livewireScripts
</body>
</html>
