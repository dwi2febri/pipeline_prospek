<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Masuk — {{ config('app.name', 'E-Prospek') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .login-page{display:flex;flex-direction:column;justify-content:center;min-height:100vh;min-height:100dvh;margin:0;padding:36px 28px;background:#f4f6fa;color:#202b40;font-family:'Segoe UI',system-ui,sans-serif;line-height:1.5}
        .login-page *{box-sizing:border-box}
        .login-topbar{display:flex;align-items:center;justify-content:space-between;gap:20px;width:100%;max-width:1120px;margin:0 auto 26px;padding-inline:4px}
        .login-topbar-company{margin:0;color:#667185;font-size:12px;text-align:right}
        .login-layout{display:grid;grid-template-columns:46% 54%;grid-template-areas:'form visual';width:100%;max-width:1120px;margin:0 auto;border:1px solid #e1e6ef;border-radius:26px;background:#fff;box-shadow:0 24px 64px rgba(30,45,82,.07);isolation:isolate}
        .login-aside{grid-area:visual;display:flex;align-items:center;padding:42px;background:#edf1fa;color:#263657;border-left:1px solid #e4eaf6;border-radius:0 25px 25px 0;min-width:0}
        .login-brand{display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit}
        .login-brand img{width:56px;height:56px;object-fit:contain;flex:0 0 auto;background:#fff;border:1px solid #e1e6ef;border-radius:15px;padding:5px}
        .login-brand-name{display:block;font-size:24px;font-weight:750;letter-spacing:-.5px;line-height:1.2}
        .login-brand-caption{display:block;margin-top:4px;color:#667185;font-size:12px;line-height:1.4}
        .login-visual{width:100%;margin:0;display:flex;flex-direction:column-reverse;align-items:center;gap:30px;min-width:0}
        .login-visual img{display:block;width:100%;max-width:490px;height:auto;max-height:330px;object-fit:contain}
        .login-copy{text-align:left;width:100%;max-width:440px}
        .login-copy h2{margin:0;font-size:clamp(28px,2.3vw,34px);font-weight:700;line-height:1.27;letter-spacing:-.8px}
        .login-copy h2 span{color:#35469d}
        .login-copy p{margin:16px 0 0;color:#596781;font-size:14px;line-height:1.75;max-width:360px}
        .login-main{grid-area:form;display:flex;align-items:center;justify-content:center;padding:52px clamp(28px,4vw,56px);min-width:0}
        .login-content{width:100%;max-width:400px;min-width:0}
        .login-eyebrow{margin:0 0 10px;color:#35469d;font-size:13px;font-weight:600}
        .login-heading{margin:0;font-size:clamp(29px,2.6vw,36px);font-weight:700;line-height:1.2;letter-spacing:-1px}
        .login-description{margin:14px 0 32px;color:#667185;font-size:14px;line-height:1.7}
        .login-field{margin-bottom:23px}
        .login-label{display:block;margin-bottom:9px;font-size:14px;font-weight:600}
        .login-input-wrap{position:relative;display:flex;align-items:center}
        .login-input-icon{position:absolute;left:17px;color:#77849a;font-size:18px;pointer-events:none}
        .login-input{display:block;width:100%;height:56px;padding:12px 16px 12px 48px;border:1px solid #e0e5ee;border-radius:12px;background:#f8f9fc;color:#202b40;font:inherit;font-size:16px;outline:none;transition:border-color .12s,box-shadow .12s,background .12s}
        .login-input::placeholder{color:#69758a;opacity:1}
        .login-input:focus{border-color:#526ac1;background:#fff;box-shadow:0 0 0 3px #edf0fb}
        .login-input-wrap:focus-within .login-input-icon{color:#35469d}
        .login-input[aria-invalid="true"]{border-color:#be3848}
        .login-password-input{padding-right:54px}
        .login-password-toggle{position:absolute;right:5px;display:grid;place-items:center;width:44px;height:44px;padding:0;border:0;border-radius:7px;color:#77849a;background:transparent;font-size:19px;cursor:pointer}
        .login-password-toggle:hover{color:#35469d;background:#f5f6fb}
        .login-options{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:4px 0 25px;font-size:13px}
        .login-remember{display:inline-flex;align-items:center;gap:8px;min-height:24px;cursor:pointer}
        .login-remember input{width:16px;height:16px;margin:0;accent-color:#35469d}
        .login-link{color:#35469d;text-decoration:none;font-weight:600}
        .login-link:hover{text-decoration:underline}
        .login-submit{display:flex;align-items:center;justify-content:center;gap:14px;width:100%;min-height:54px;padding:12px 20px;border:1px solid #35469d;border-radius:12px;background:#35469d;color:#fff;box-shadow:0 5px 12px rgba(53,70,157,.15);font:inherit;font-size:16px;font-weight:600;cursor:pointer;transition:background .12s}
        .login-submit:hover{background:#293981}
        .login-submit:disabled{opacity:.7;cursor:wait}
        .login-password-toggle:focus-visible,.login-submit:focus-visible,.login-link:focus-visible,.login-remember input:focus-visible{outline:3px solid #9eafea;outline-offset:3px}
        .login-help{margin:28px 0 0;padding-top:22px;border-top:1px solid #edf0f5;color:#667185;font-size:13px;line-height:1.7}
        .login-help i{margin-right:5px}
        .login-footer{width:100%;max-width:1120px;margin:22px auto 0;color:#69758a;font-size:12px;line-height:1.7;text-align:center}
        .login-alert{margin:0 0 22px;padding:13px 15px;border:1px solid #efcbd0;border-radius:9px;background:#fff5f6;color:#a42738;font-size:13px}
        .login-alert ul{margin:5px 0 0;padding-left:18px}
        .login-status{border-color:#c4e3d8;background:#f0faf6;color:#236b51}
        .login-mobile-text{display:none}
        .login-visual picture{display:contents}
        @media(min-width:992px) and (max-height:760px){
            .login-page{padding-block:20px}.login-topbar{margin-bottom:16px}
            .login-visual{gap:22px}.login-visual img{max-height:275px}
            .login-main{padding-block:28px}.login-aside{padding:32px}
            .login-field{margin-bottom:18px}.login-description{margin-bottom:24px}
            .login-help{margin-top:24px;padding-top:18px}.login-footer{margin-top:16px}
        }
        @media(max-width:991.98px){
            .login-page{justify-content:flex-start;min-height:0;height:100vh;height:100dvh;padding:0;background:#eef2fb}
            .login-topbar{flex:none;max-width:520px;margin:0 auto;padding:calc(14px + env(safe-area-inset-top)) max(24px,env(safe-area-inset-right)) 8px max(24px,env(safe-area-inset-left));background:#29327e;color:#fff}
            .login-topbar-company{display:none}
            .login-brand{gap:10px;min-width:0}
            .login-brand img{width:28px;height:28px;border:0;border-radius:0;background:transparent;padding:0}
            .login-brand-name{font-size:21px;letter-spacing:-.5px}
            .login-brand-caption{color:#d9dff7;max-width:none;font-size:11px}
            .login-layout{display:flex;flex-direction:column;flex:1 0 auto;min-height:0;max-width:520px;border:0;border-radius:0;box-shadow:none;background:#29327e}
            .login-aside{order:0;position:relative;display:block;flex:1 1 0;min-height:0;margin:0;padding:0;border:0;border-radius:0;background:#29327e;overflow:hidden}
            .login-visual{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:flex-end;align-items:center;height:100%;gap:0}
            .login-visual img{display:block;width:100%;max-width:none;height:100%;max-height:none;object-fit:cover;object-position:center top;flex-shrink:0}
            .login-copy,.login-eyebrow,.login-desktop-text{display:none}
            .login-mobile-text{display:inline}
            .login-main{order:1;position:relative;flex:none;margin:0;padding:26px max(26px,env(safe-area-inset-right)) 16px max(26px,env(safe-area-inset-left));background:#fff;border-radius:28px 28px 0 0}
            .login-content{max-width:460px}
            .login-heading{font-size:29px;line-height:1.25;letter-spacing:-.8px}
            .login-description{margin:8px 0 22px;font-size:14px;line-height:1.5}
            .login-field{margin-bottom:16px}
            .login-label{margin-bottom:7px}
            .login-input{height:50px;min-height:50px;background:#fff;border-color:#d5dcec}
            .login-options{margin:2px 0 18px;min-height:28px;font-size:13px}
            .login-submit{height:50px;min-height:50px;background:#29327e;border-color:#29327e;box-shadow:none}
            .login-help{margin:18px 0 0;padding:0;border:0;text-align:center;font-size:12px}
            .login-help i{display:none}
            .login-footer{flex:none;max-width:520px;margin:0 auto;padding:8px 16px max(16px,env(safe-area-inset-bottom));background:#fff;font-size:11px}
            .login-alert{margin-bottom:16px}
        }
        @media(max-width:991.98px) and (max-height:740px){
            .login-topbar{padding-top:calc(10px + env(safe-area-inset-top))}
            .login-main{padding-top:20px;padding-bottom:12px;border-radius:24px 24px 0 0}
            .login-heading{font-size:26px}
            .login-description{margin:6px 0 16px;font-size:13px}
            .login-field{margin-bottom:12px}.login-label{margin-bottom:5px;font-size:13px}
            .login-input,.login-submit{height:46px;min-height:46px}
            .login-options{margin-bottom:12px}.login-help{margin-top:14px}
            .login-footer{padding-top:6px;padding-bottom:max(12px,env(safe-area-inset-bottom))}
        }
        @media(max-width:359.98px){
            .login-topbar{padding-inline:20px}
            .login-main{padding-inline:20px}.login-heading{font-size:26px}
            .login-options{font-size:12px;gap:8px}
        }
        @media(max-width:991.98px) and (max-height:600px){
            .login-brand img{width:28px;height:28px}.login-brand-name{font-size:20px}
            .login-main{padding-top:16px;padding-bottom:10px}.login-heading{font-size:24px}
            .login-description{margin-bottom:12px}.login-field{margin-bottom:8px}
            .login-input,.login-submit{height:44px;min-height:44px}
            .login-options{margin-bottom:8px;min-height:24px}.login-help{margin-top:10px;font-size:11px}
        }
        @media(max-width:991.98px) and (max-height:480px){
            .login-aside{display:none}
            .login-topbar{padding-block:8px}.login-brand img{width:28px;height:28px}
            .login-brand-name{font-size:20px}.login-brand-caption{display:none}
        }
        @media(min-width:600px) and (max-width:991.98px) and (max-height:480px){
            .login-topbar,.login-layout,.login-footer{max-width:680px}
            .login-main{padding-block:12px}.login-content{max-width:600px}
            #loginForm{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:8px 16px}
            .login-field{margin:0}.login-options,.login-submit{grid-column:1 / -1}
            .login-options{margin:0}.login-description{margin-bottom:10px}
            .login-help{margin-top:8px}.login-footer{padding-top:4px;padding-bottom:max(8px,env(safe-area-inset-bottom))}
        }
        @media(prefers-reduced-motion:reduce){.login-input,.login-submit{transition:none}}
    </style>
    @include('partials.ios-compat')
</head>
<body class="login-page">
    <header class="login-topbar">
        <div class="login-brand">
            <img src="{{ asset('images/logo_eprospek.png') }}" alt="" width="52" height="52">
            <div>
                <span class="login-brand-name">E-Prospek</span>
                <span class="login-brand-caption">Pipeline Prospek Nasabah</span>
            </div>
        </div>
        <p class="login-topbar-company">PT BPR BKK Jateng (Perseroda)</p>
    </header>
    <main class="login-layout">
        <section class="login-main" aria-labelledby="loginHeading">
            <div class="login-content">
                <header>
                    <p class="login-eyebrow">Selamat datang di E-Prospek</p>
                    <h1 class="login-heading" id="loginHeading"><span class="login-desktop-text">Masuk ke akun Anda</span><span class="login-mobile-text">Selamat datang</span></h1>
                    <p class="login-description"><span class="login-desktop-text">Gunakan username dan password akun Anda.</span><span class="login-mobile-text">Masuk untuk kelola prospek Anda.</span></p>
                </header>

                @if(session('status'))
                    <div class="login-alert login-status" role="status">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="login-alert" role="alert">
                        <strong>Login belum berhasil</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" novalidate id="loginForm">
                    @csrf
                    <div class="login-field">
                        <label class="login-label" for="loginUsername">Username</label>
                        <div class="login-input-wrap">
                            <i class="bi bi-person login-input-icon" aria-hidden="true"></i>
                            <input class="login-input" id="loginUsername" type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan username" required autocomplete="username" autocapitalize="none" spellcheck="false" @error('name') aria-invalid="true" @enderror>
                        </div>
                    </div>
                    <div class="login-field">
                        <label class="login-label" for="passwordInput">Password</label>
                        <div class="login-input-wrap">
                            <i class="bi bi-lock login-input-icon" aria-hidden="true"></i>
                            <input class="login-input login-password-input" type="password" name="password" id="passwordInput" placeholder="Masukkan password" required autocomplete="current-password" @error('password') aria-invalid="true" @enderror>
                            <button class="login-password-toggle" type="button" id="btnTogglePass" aria-label="Tampilkan password" aria-controls="passwordInput" aria-pressed="false">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="login-options">
                        <label class="login-remember" for="rememberMe">
                            <input type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                            <span>Ingat saya</span>
                        </label>
                        @if(Route::has('password.request'))
                            <a class="login-link" href="{{ route('password.request') }}">Lupa password?</a>
                        @endif
                    </div>
                    <button class="login-submit" type="submit">
                        <span>Masuk ke aplikasi</span><i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>
                <p class="login-help">
                    <i class="bi bi-info-circle" aria-hidden="true"></i>Perlu bantuan akses?
                    <a class="login-link" href="https://wa.me/6282241606980?text=Halo%20Admin%20E-Prospek%2C%20saya%20membutuhkan%20bantuan." target="_blank" rel="noopener">Hubungi administrator.</a>
                </p>
            </div>
        </section>
        <section class="login-aside" aria-label="Tentang E-Prospek">
            <figure class="login-visual">
                <picture>
                <source media="(max-width: 991.98px)" srcset="{{ asset('images/login/eprospek-mobile-hero-v3.webp') }}">
                <img src="{{ asset('images/login/eprospek-team-v1.webp') }}" alt="" width="1200" height="900" decoding="async" fetchpriority="high">
                </picture>
                <figcaption class="login-copy">
                    <h2>Prospek tercatat.<br><span>Tindak lanjut terpantau.</span></h2>
                    <p>Kelola calon nasabah, penugasan, dan status pengajuan dalam satu tempat.</p>
                </figcaption>
            </figure>
        </section>
    </main>
    <footer class="login-footer">&copy; {{ date('Y') }} PT BPR BKK Jateng (Perseroda)</footer>
    <script>
        (function(){
            var button = document.getElementById('btnTogglePass');
            var input = document.getElementById('passwordInput');
            button.addEventListener('click', function(){
                var visible = input.type === 'password';
                input.type = visible ? 'text' : 'password';
                button.setAttribute('aria-pressed', visible ? 'true' : 'false');
                button.setAttribute('aria-label', visible ? 'Sembunyikan password' : 'Tampilkan password');
                button.querySelector('i').className = visible ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        })();
    </script>
</body>
</html>
