<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Pipeline Prospek') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root{
            --login-green-950:#35469d;
            --login-green-800:#4b61c4;
            --login-green-700:#5d78d6;
            --login-green-500:#6f8ee8;
            --login-mint-100:#e5eaff;
            --login-mint-50:#f5f7ff;
            --login-yellow:#f7c948;
            --login-ink:#2f3f86;
        }

        body.eprospek-login-page{
            font-family:"Instrument Sans",system-ui,-apple-system,"Segoe UI",sans-serif;
        }

        #passwordInput::-ms-reveal,
        #passwordInput::-ms-clear{
            display:none;
            width:0;
            height:0;
        }

        #passwordInput::-webkit-credentials-auto-fill-button{
            display:none !important;
            visibility:hidden;
            pointer-events:none;
        }

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
            body.eprospek-login-page{
                overflow-x:hidden;
                font-family:"Inter",Arial,sans-serif;
                font-size:11px;
                letter-spacing:.1px;
                background:
                    radial-gradient(circle at 96% 46%,rgba(105,221,211,.15),transparent 25%),
                    linear-gradient(180deg,#536fd0 0 218px,#f1f5fb 218px 100%) !important;
            }

            .eprospek-login-shell{
                display:block !important;
                min-height:100dvh !important;
            }

            .eprospek-login-container{
                box-sizing:border-box;
                width:100% !important;
                max-width:none !important;
                padding:0 13px 22px !important;
            }

            .login-desktop-brand,
            .desktop-login-showcase{display:none}

            .mobile-login-hero{
                position:relative;
                box-sizing:border-box;
                width:calc(100% + 26px);
                display:block !important;
                min-height:248px;
                margin:0 -13px -38px;
                padding:15px 18px 57px;
                overflow:hidden;
                isolation:isolate;
                color:#fff;
                background:linear-gradient(145deg,#6f8fe1 0%,#526fd4 50%,#334db7 100%);
                border-radius:0 0 34px 34px;
                box-shadow:0 16px 34px rgba(41,61,145,.18);
            }

            .mobile-login-hero::before,
            .mobile-login-hero::after{
                content:"";
                position:absolute;
                pointer-events:none;
            }

            .mobile-login-hero::before{
                z-index:2;
                width:112%;
                height:88px;
                left:-20%;
                top:103px;
                border-radius:42% 58% 46% 54% / 64% 48% 52% 36%;
                background:rgba(255,255,255,.08);
                transform:rotate(-7deg);
            }

            .mobile-login-hero::after{
                z-index:2;
                width:145px;
                height:145px;
                left:-60px;
                bottom:-90px;
                border-radius:50%;
                background:rgba(126,236,222,.13);
            }

            .mobile-login-brand{
                position:relative;
                z-index:4;
                display:flex;
                align-items:center;
                gap:9px;
                font-size:10px;
                font-weight:850;
                letter-spacing:.01em;
            }

            .mobile-login-logo{
                width:38px;
                height:38px;
                padding:4px;
                display:grid;
                place-items:center;
                border-radius:12px;
                background:rgba(255,255,255,.95);
                box-shadow:0 8px 20px rgba(28,43,115,.24);
            }

            .mobile-login-logo img{width:100%;height:100%;object-fit:contain}
            .mobile-login-copy{position:relative;z-index:4;width:52%;margin-top:19px}
            .mobile-login-eyebrow{color:#bff9ef;font-size:8px;font-weight:850;letter-spacing:.1em;text-transform:uppercase}
            .mobile-login-copy h1{margin:5px 0 6px;font-size:20px;font-weight:950;line-height:1.08;letter-spacing:-.035em}
            .mobile-login-copy p{margin:0;color:rgba(240,246,255,.87);font-size:9px;line-height:1.45}

            .mobile-login-security-chip{
                display:inline-flex;
                align-items:center;
                gap:5px;
                margin-top:10px;
                padding:5px 8px;
                border:1px solid rgba(255,255,255,.18);
                border-radius:999px;
                color:#fff;
                background:rgba(255,255,255,.12);
                box-shadow:inset 0 1px 0 rgba(255,255,255,.12);
                backdrop-filter:blur(5px);
                font-size:8px;
                font-weight:750;
            }

            .mobile-login-security-chip i{color:#78eadb;font-size:9px}

            .mobile-login-visual{
                position:absolute;
                z-index:1;
                inset:0;
                overflow:hidden;
            }

            .mobile-login-visual::before{
                content:"";
                position:absolute;
                z-index:2;
                inset:0;
                background:linear-gradient(
                    90deg,
                    rgba(82,111,212,.96) 0%,
                    rgba(82,111,212,.9) 28%,
                    rgba(82,111,212,.6) 48%,
                    rgba(82,111,212,.12) 70%,
                    transparent 100%
                );
                pointer-events:none;
            }

            .mobile-login-visual img{
                display:block;
                width:100%;
                height:100%;
                object-fit:cover;
                object-position:center 47%;
            }

            .eprospek-login-card{
                position:relative;
                z-index:5;
                box-sizing:border-box;
                width:100%;
                max-width:100%;
                overflow:hidden;
                padding:14px !important;
                border:1px solid rgba(190,201,231,.68);
                border-radius:24px !important;
                background:rgba(255,255,255,.97) !important;
                box-shadow:0 16px 38px rgba(48,65,142,.14) !important;
            }

            .login-card-heading{margin-bottom:14px;text-align:left}
            .login-card-heading h2{color:#1d2c57;font-size:19px;line-height:1.25;letter-spacing:-.02em}
            .login-card-heading p{font-size:9px;line-height:1.55}
            .eprospek-login-card form > .mb-3{margin-bottom:11px !important}
            .eprospek-login-card form > .mb-2{margin-bottom:6px !important}
            .eprospek-login-card .form-label{margin-bottom:6px;color:#3e4d89;font-size:10.5px;font-weight:800}
            .eprospek-login-card .input-group.mt-2{margin-top:5px !important}
            .eprospek-login-card .form-control,
            .eprospek-login-card .input-group-text,
            .eprospek-login-card .btn-outline-secondary{
                min-height:46px;
                border-color:#dce2f7;
                background:#f7f9fd !important;
                font-family:"Inter",Arial,sans-serif;
                font-size:11px;
            }

            .eprospek-login-card .input-group-text{
                width:42px;
                justify-content:center;
                border-radius:14px 0 0 14px;
                color:#5d78d6;
            }

            .eprospek-login-card .form-control{background:#f7f9fd;border-left:0}
            .eprospek-login-card .input-group{width:100%;min-width:0;flex-wrap:nowrap}
            .eprospek-login-card .input-group>.form-control{width:1%;min-width:0}
            .eprospek-login-card .input-group .form-control:last-child{border-radius:0 14px 14px 0}
            .eprospek-login-card .btn-outline-secondary{width:42px;border-radius:0 14px 14px 0;color:#5d78d6}
            .eprospek-login-card .form-control:focus{
                border-color:#8299e4;
                box-shadow:0 0 0 .2rem rgba(93,120,214,.13);
            }

            .eprospek-login-card .form-check-input:checked{background-color:#5d78d6;border-color:#5d78d6}
            .eprospek-login-card .d-flex.my-3{margin-top:12px !important;margin-bottom:12px !important}
            .eprospek-login-card .form-check-label,
            .eprospek-login-card .login-help-link{font-size:9px}
            .login-help-link{color:#4b61c4}

            .eprospek-login-card button[type="submit"]{
                min-height:46px;
                border:0 !important;
                border-radius:14px !important;
                color:#fff;
                background:linear-gradient(135deg,#6f8ee8,#4b61c4) !important;
                box-shadow:0 10px 22px rgba(75,97,196,.23);
                font-family:"Inter",Arial,sans-serif;
                font-size:11px;
                font-weight:800;
            }

            .eprospek-login-card button[type="submit"]:active{transform:translateY(1px)}
            .desktop-login-footer{
                margin-top:10px !important;
                color:#78839f !important;
                font-size:9px;
            }

            .wa-admin-float{
                right:15px;
                bottom:16px;
                width:46px;
                height:46px;
                box-shadow:0 10px 24px rgba(37,211,102,.3);
            }

            .wa-admin-float i{font-size:23px}
            .wa-admin-tooltip{display:none}
        }

        @media (max-width:360px){
            .mobile-login-copy h1{font-size:18px}
            .mobile-login-copy{width:54%}
            .eprospek-login-card{padding:16px 14px 14px !important}
        }

        @media (min-width:577px){
            .mobile-login-hero{display:none}

            body.eprospek-login-page{
                overflow:hidden;
                background:
                    radial-gradient(circle at 50% -12%,rgba(102,128,222,.62),transparent 45%),
                    radial-gradient(circle at 95% 95%,rgba(69,105,190,.18),transparent 27%),
                    #18214d !important;
            }

            .eprospek-login-shell{
                box-sizing:border-box;
                height:100dvh;
                min-height:100dvh !important;
                padding:18px;
                overflow:hidden;
            }
            .eprospek-login-container{
                width:100%;
                max-width:560px !important;
                padding:0 !important;
            }

            .desktop-login-layout{
                overflow:hidden;
                border:1px solid rgba(183,196,228,.7);
                border-radius:30px;
                background:rgba(255,255,255,.96);
                box-shadow:0 30px 80px rgba(5,10,35,.35);
            }

            .desktop-login-showcase{display:none}
            .desktop-login-form-column{
                display:flex;
                min-height:0;
                padding:clamp(28px,5vh,44px);
                flex-direction:column;
                justify-content:center;
                background:
                    radial-gradient(circle at 100% 0,rgba(111,142,232,.1),transparent 27%),
                    #fff;
            }

            .login-desktop-brand{
                display:flex;
                align-items:center;
                gap:12px;
                margin-bottom:38px !important;
                text-align:left !important;
            }

            .desktop-brand-logo{
                width:54px;
                height:54px;
                padding:5px;
                display:grid;
                flex:0 0 54px;
                place-items:center;
                overflow:hidden;
                border:1px solid #e1e7f7;
                border-radius:16px;
                background:#fff;
                box-shadow:0 10px 25px rgba(75,97,196,.13);
            }

            .desktop-brand-logo img{width:100%;height:100%;object-fit:contain}
            .desktop-brand-name{color:#243773;font-size:17px;font-weight:900;letter-spacing:-.02em}
            .desktop-brand-caption{margin-top:2px;color:#8190ad;font-size:10px}

            .eprospek-login-card{
                padding:0 !important;
                border:0;
                border-radius:0 !important;
                background:transparent !important;
                box-shadow:none !important;
            }

            .login-card-heading{margin-bottom:30px;text-align:left}
            .login-card-heading h2{margin-bottom:8px !important;color:#172544;font-size:31px;letter-spacing:-.035em}
            .login-card-heading p{color:#73819e !important;font-size:13px}
            .eprospek-login-card .form-label{margin-bottom:8px;color:#344360;font-size:12px}
            .eprospek-login-card form > .mb-3{margin-bottom:18px !important}
            .eprospek-login-card form > .mb-2{margin-bottom:10px !important}
            .eprospek-login-card .input-group.mt-2{margin-top:8px !important}
            .eprospek-login-card .input-group{flex-wrap:nowrap}
            .eprospek-login-card .input-group>.form-control{width:1%;min-width:0}
            .eprospek-login-card .form-control,
            .eprospek-login-card .input-group-text,
            .eprospek-login-card .btn-outline-secondary{
                min-height:52px;
                border-color:#dce3f2;
                background:#f7f9fd !important;
                font-size:14px;
            }

            .eprospek-login-card .input-group-text{
                width:52px;
                justify-content:center;
                border-radius:15px 0 0 15px;
                color:#5873d4;
            }

            .eprospek-login-card .form-control{border-left:0}
            .eprospek-login-card .input-group .form-control:last-child{border-radius:0 15px 15px 0}
            .eprospek-login-card .btn-outline-secondary{
                width:52px;
                border-radius:0 15px 15px 0;
                color:#5873d4;
            }

            .eprospek-login-card .form-control:focus{
                border-color:#839be8;
                box-shadow:0 0 0 .22rem rgba(93,120,214,.12);
            }

            .eprospek-login-card .d-flex.my-3{margin-top:18px !important;margin-bottom:21px !important}
            .eprospek-login-card .form-check-label,
            .eprospek-login-card .login-help-link{font-size:12px}
            .eprospek-login-card button[type="submit"]{
                min-height:52px;
                border:0;
                border-radius:15px !important;
                background:linear-gradient(135deg,#6f8ee8,#4b61c4);
                box-shadow:0 12px 25px rgba(75,97,196,.23);
                font-size:14px;
            }

            .login-help-link{color:#4b61c4}
            .desktop-login-footer{
                margin-top:28px !important;
                color:#8b97af !important;
                font-size:10px;
            }
        }

        @media (min-width:992px){
            .eprospek-login-container{max-width:1040px !important}
            .desktop-login-layout{
                display:grid;
                grid-template-columns:minmax(0,1.04fr) minmax(400px,.96fr);
                width:100%;
                height:min(650px,calc(100dvh - 36px));
                min-height:0;
            }

            .desktop-login-showcase{
                position:relative;
                display:block;
                min-width:0;
                overflow:hidden;
                isolation:isolate;
                color:#fff;
                background:#4261cf;
            }

            .desktop-showcase-visual,
            .desktop-showcase-visual::after{
                position:absolute;
                inset:0;
            }

            .desktop-showcase-visual{z-index:-2}
            .desktop-showcase-visual::after{
                content:"";
                z-index:1;
                background:
                    linear-gradient(90deg,rgba(54,76,178,.94) 0%,rgba(54,76,178,.76) 43%,rgba(54,76,178,.14) 72%,transparent 100%),
                    linear-gradient(0deg,rgba(24,39,112,.5) 0%,transparent 36%);
            }

            .desktop-showcase-visual img{
                width:100%;
                height:100%;
                display:block;
                object-fit:cover;
                object-position:center 47%;
            }

            .desktop-showcase-content{
                position:relative;
                z-index:3;
                width:58%;
                padding:54px 0 0 50px;
            }

            .desktop-showcase-badge{
                display:inline-flex;
                align-items:center;
                gap:7px;
                padding:7px 11px;
                border:1px solid rgba(255,255,255,.2);
                border-radius:999px;
                background:rgba(255,255,255,.13);
                backdrop-filter:blur(8px);
                font-size:10px;
                font-weight:800;
            }

            .desktop-showcase-badge i{color:#7ee6d8}
            .desktop-showcase-content h1{
                margin:24px 0 12px;
                font-size:38px;
                font-weight:950;
                line-height:1.06;
                letter-spacing:-.045em;
            }

            .desktop-showcase-content p{
                margin:0;
                color:rgba(241,246,255,.84);
                font-size:13px;
                line-height:1.65;
            }

            .desktop-login-form-column{min-height:0;padding:clamp(32px,5vh,46px) 48px}
        }
    </style>
</head>

<body class="eprospek-login-page" style="margin:0; min-height:100vh; background: radial-gradient(900px 520px at 50% -10%, rgba(111,142,232,.68) 0%, rgba(53,70,157,1) 56%, rgba(27,35,82,1) 100%);">

<div class="min-vh-100 d-flex align-items-center eprospek-login-shell">
    <div class="container py-5 eprospek-login-container" style="max-width:520px;">

        <section class="mobile-login-hero" aria-label="Selamat datang di E-Prospek">
            <div class="mobile-login-brand">
                <span class="mobile-login-logo">
                    <img src="{{ asset('images/logo_eprospek.png') }}" alt="">
                </span>
                <span>E-Prospek BKK Jawa Tengah</span>
            </div>
            <div class="mobile-login-copy">
                <div class="mobile-login-eyebrow">Pipeline Prospek Nasabah</div>
                <h1>Prospek lebih dekat, tindak lanjut lebih cepat.</h1>
                <p>Pantau peluang nasabah dan perkembangan pipeline dalam satu aplikasi.</p>
                <span class="mobile-login-security-chip">
                    <i class="bi bi-shield-check"></i>
                    Akses aman &amp; terproteksi
                </span>
            </div>
            <div class="mobile-login-visual" aria-hidden="true">
                <img src="{{ asset('images/mobile/eprospek-login-hero-v5.webp') }}" alt="">
            </div>
        </section>

        <div class="desktop-login-layout">
            <section class="desktop-login-showcase" aria-label="Ringkasan aplikasi E-Prospek">
                <div class="desktop-showcase-visual" aria-hidden="true">
                    <img src="{{ asset('images/mobile/eprospek-login-desktop-mockup-v1.webp') }}" alt="">
                </div>

                <div class="desktop-showcase-content">
                    <span class="desktop-showcase-badge">
                        <i class="bi bi-stars"></i>
                        Pipeline Prospek Terintegrasi
                    </span>
                    <h1>Kelola setiap peluang lebih cepat.</h1>
                    <p>Pantau prospek, lanjutkan follow up, dan jaga peluang closing tetap bergerak dalam satu aplikasi.</p>
                </div>

            </section>

            <div class="desktop-login-form-column">
        <div class="login-desktop-brand">
            <span class="desktop-brand-logo">
                <img src="{{ asset('images/logo_eprospek.png') }}" alt="Logo E-Prospek">
            </span>
            <span>
                <span class="desktop-brand-name d-block">E-Prospek</span>
                <span class="desktop-brand-caption d-block">PT BPR BKK Jawa Tengah</span>
            </span>
        </div>

        <div class="bg-white p-4 eprospek-login-card" style="border-radius:18px;box-shadow:0 18px 55px rgba(0,0,0,.35);">
            <div class="login-card-heading">
                <h2 class="fw-bold mb-1">Selamat datang</h2>
                <p class="text-secondary mb-0">Masuk ke akun untuk melanjutkan ke E-Prospek</p>
            </div>

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
                        <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Masukkan password" required autocomplete="current-password">
                        <button class="btn btn-outline-secondary" type="button" id="btnTogglePass" tabindex="-1" aria-label="Tampilkan atau sembunyikan password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center my-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="rememberMe">Ingat saya</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="small fw-semibold text-decoration-none login-help-link" href="{{ route('password.request') }}">Lupa password?</a>
                    @endif
                </div>

                <button class="btn btn-primary w-100 py-2 fw-bold" type="submit" style="border-radius:12px;">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </button>
            </form>

        </div>

        <div class="text-center mt-3 text-white-50 small desktop-login-footer">
            © {{ date('Y') }} {{ config('app.name','Pipeline Prospek') }}
        </div>
            </div>
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
