<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Pipeline Prospek') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .wa-admin-float{
            position:fixed;
            right:22px;
            bottom:22px;
            width:58px;
            height:58px;
            border-radius:50%;
            background:#25D366;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            text-decoration:none;
            box-shadow:0 12px 30px rgba(37,211,102,.35);
            z-index:9999;
            transition:all .2s ease;
        }

        .wa-admin-float:hover{
            color:#fff;
            background:#1ebe5d;
            transform:translateY(-3px);
            box-shadow:0 16px 38px rgba(37,211,102,.45);
        }

        .wa-admin-float i{
            font-size:30px;
            line-height:1;
        }

        .wa-admin-tooltip{
            position:absolute;
            right:68px;
            top:50%;
            transform:translateY(-50%);
            background:#ffffff;
            color:#0f172a;
            font-size:13px;
            font-weight:700;
            white-space:nowrap;
            padding:8px 12px;
            border-radius:999px;
            box-shadow:0 10px 28px rgba(15,23,42,.18);
            opacity:0;
            pointer-events:none;
            transition:all .2s ease;
        }

        .wa-admin-float:hover .wa-admin-tooltip{
            opacity:1;
            right:74px;
        }

        @media (max-width: 576px){
            .wa-admin-float{
                right:18px;
                bottom:18px;
                width:54px;
                height:54px;
            }

            .wa-admin-float i{
                font-size:28px;
            }

            .wa-admin-tooltip{
                display:none;
            }
        }
    </style>
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

<a href="https://wa.me/6282241606980?text=Halo%20Admin%20E-Prospek%2C%20saya%20membutuhkan%20bantuan."
   target="_blank"
   rel="noopener"
   class="wa-admin-float"
   aria-label="Hubungi Admin via WhatsApp">
    <span class="wa-admin-tooltip">Hubungi Admin</span>
    <i class="bi bi-whatsapp"></i>
</a>

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
