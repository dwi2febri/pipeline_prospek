<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Pipeline Prospek') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="margin:0; min-height:100vh; background: radial-gradient(1100px 520px at 50% -15%, rgba(75,123,236,.55) 0%, rgba(11,18,32,1) 55%, rgba(5,7,13,1) 100%);">

<div class="min-vh-100 d-flex align-items-center">
    <div class="container py-5" style="max-width:520px;">

        <div class="text-center mb-3">
            <div class="d-inline-flex align-items-center justify-content-center mb-2"
                style="width:110px;height:110px;border-radius:24px;background:#ffffff;box-shadow:0 14px 40px rgba(75,123,236,.25);overflow:hidden;padding:8px;">
                <img src="{{ asset('images/logo_eprospek.png') }}"
                    alt="Logo E-Prospek"
                    style="width:96px;height:96px;object-fit:contain;display:block;">
            </div>
            <div class="text-white fw-bold fs-4">E-Prospek</div>
            <div class="text-white-50 small">Login untuk melanjutkan</div>
        </div>

        <div class="bg-white p-4" style="border-radius:18px;box-shadow:0 18px 55px rgba(0,0,0,.35);">

            @if ($errors->any())
                <div class="alert alert-danger rounded-4">
                    <div class="fw-bold mb-1">
                        <i class="bi bi-exclamation-triangle"></i> Login gagal
                    </div>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Masukkan username" required autofocus autocomplete="username">
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold mb-0">Password</label>
                    <div class="input-group mt-2">
                        <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Password" required autocomplete="current-password">
                        <button class="btn btn-outline-secondary" type="button" id="btnTogglePass" tabindex="-1">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center my-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="rememberMe">Ingat saya</label>
                    </div>
                    <span class="badge text-bg-light">v1</span>
                </div>

                <button class="btn btn-primary w-100 py-2 fw-bold" type="submit" style="border-radius:12px;">
                    <i class="bi bi-box-arrow-in-right"></i> Log in
                </button>
            </form>

        </div>

        <div class="text-center mt-3 text-white-50 small">
            © {{ date('Y') }} {{ config('app.name','Pipeline Prospek') }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('btnTogglePass');
    var input = document.getElementById('passwordInput');

    if (btn && input) {
        btn.addEventListener('click', function () {
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                input.type = 'password';
                btn.innerHTML = '<i class="bi bi-eye"></i>';
            }
        });
    }
});
</script>

</body>
</html>
