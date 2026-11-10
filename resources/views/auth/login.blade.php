<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Masuk — {{ config('app.name', 'E-Prospek') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .login-page{display:flex;flex-direction:column;justify-content:center;min-height:100vh;min-height:100dvh;margin:0;padding:40px 28px;background:#f3f5f9;color:#202b40;font-family:'Segoe UI',system-ui,sans-serif;line-height:1.5}
        .login-page *{box-sizing:border-box}
        .login-topbar{display:flex;align-items:center;justify-content:space-between;gap:20px;width:100%;max-width:1120px;margin:0 auto 22px}
        .login-topbar-company{margin:0;color:#667185;font-size:12px;text-align:right}
        .login-layout{display:grid;grid-template-columns:46% 54%;grid-template-areas:'form visual';width:100%;max-width:1120px;margin:0 auto;border:1px solid #e1e6ef;border-radius:26px;background:#fff;box-shadow:0 16px 44px rgba(32,43,64,.045)}
        .login-aside{grid-area:visual;display:flex;align-items:center;margin:16px;padding:34px;background:#edf0f9;color:#293765;border-radius:18px;min-width:0}
        .login-brand{display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit}
        .login-brand img{width:48px;height:48px;object-fit:contain;flex:0 0 auto}
        .login-brand-name{display:block;font-size:24px;font-weight:750;letter-spacing:-.5px;line-height:1.2}
        .login-brand-caption{display:block;margin-top:5px;color:#667185;font-size:12px;line-height:1.4}
        .login-visual{width:100%;margin:0;display:flex;flex-direction:column-reverse;align-items:center;gap:32px;min-width:0}
        .login-visual img{display:block;width:100%;max-width:480px;height:auto;max-height:350px;object-fit:contain}
        .login-copy{text-align:left;width:100%;max-width:440px}
        .login-copy h2{margin:0;font-size:clamp(25px,2vw,30px);font-weight:650;line-height:1.3;letter-spacing:-.6px}
        .login-copy p{margin:14px 0 0;color:#596781;font-size:14px;line-height:1.7;max-width:350px}
        .login-main{grid-area:form;display:flex;align-items:center;justify-content:center;padding:44px clamp(28px,4vw,56px);min-width:0}
        .login-content{width:100%;max-width:400px;min-width:0}
        .login-eyebrow{margin:0 0 12px;color:#35469d;font-size:15px;font-weight:600}
        .login-heading{margin:0;font-size:clamp(28px,2.4vw,34px);font-weight:700;line-height:1.2;letter-spacing:-.8px}
        .login-description{margin:16px 0 28px;color:#667185;font-size:14px}
        .login-field{margin-bottom:22px}
        .login-label{display:block;margin-bottom:9px;font-size:14px;font-weight:600}
        .login-input-wrap{position:relative;display:flex;align-items:center}
        .login-input-icon{position:absolute;left:17px;color:#77849a;font-size:18px;pointer-events:none}
        .login-input{display:block;width:100%;height:54px;padding:12px 16px 12px 48px;border:1px solid #dbe2ed;border-radius:10px;background:#fff;color:#202b40;font:inherit;font-size:16px;outline:none;transition:border-color .12s,box-shadow .12s}
        .login-input::placeholder{color:#69758a;opacity:1}
        .login-input:focus{border-color:#526ac1;box-shadow:0 0 0 3px #edf0fb}
        .login-input[aria-invalid="true"]{border-color:#be3848}
        .login-password-input{padding-right:54px}
        .login-password-toggle{position:absolute;right:5px;display:grid;place-items:center;width:44px;height:44px;padding:0;border:0;border-radius:7px;color:#77849a;background:transparent;font-size:19px;cursor:pointer}
        .login-password-toggle:hover{color:#35469d;background:#f5f6fb}
        .login-options{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:4px 0 25px;font-size:13px}
        .login-remember{display:inline-flex;align-items:center;gap:8px;cursor:pointer}
        .login-remember input{width:16px;height:16px;margin:0;accent-color:#35469d}
        .login-link{color:#35469d;text-decoration:none;font-weight:600}
        .login-link:hover{text-decoration:underline}
        .login-submit{display:flex;align-items:center;justify-content:center;gap:14px;width:100%;min-height:54px;padding:12px 20px;border:1px solid #35469d;border-radius:10px;background:#35469d;color:#fff;font:inherit;font-size:16px;font-weight:600;cursor:pointer;transition:background .12s}
        .login-submit:hover{background:#293981}
        .login-submit:disabled{opacity:.7;cursor:wait}
        .login-password-toggle:focus-visible,.login-submit:focus-visible,.login-link:focus-visible,.login-remember input:focus-visible{outline:3px solid #9eafea;outline-offset:3px}
        .login-help{margin:22px 0 0;color:#667185;font-size:13px;line-height:1.7}
        .login-help i{margin-right:5px}
        .login-footer{margin-top:26px;padding-top:18px;border-top:1px solid #e8ecf3;color:#69758a;font-size:12px;line-height:1.7}
        .login-alert{margin:0 0 22px;padding:13px 15px;border:1px solid #efcbd0;border-radius:9px;background:#fff5f6;color:#a42738;font-size:13px}
        .login-alert ul{margin:5px 0 0;padding-left:18px}
        .login-status{border-color:#c4e3d8;background:#f0faf6;color:#236b51}
        @media(min-width:992px) and (max-height:760px){
            .login-page{padding-block:24px}.login-topbar{margin-bottom:16px}
            .login-visual{gap:22px}.login-visual img{max-height:290px}
            .login-main{padding-block:28px}
        }
        @media(max-width:991.98px){
            .login-page{justify-content:flex-start;padding:24px max(16px,env(safe-area-inset-right)) max(24px,env(safe-area-inset-bottom)) max(16px,env(safe-area-inset-left))}
            .login-topbar{max-width:520px;margin-bottom:20px;padding-inline:8px}
            .login-topbar-company{display:none}
            .login-layout{grid-template-columns:minmax(0,1fr);grid-template-areas:'form' 'visual';max-width:520px;border-radius:20px}
            .login-aside{margin:0 12px 12px;padding:18px;border-radius:13px}
            .login-brand{gap:10px;min-width:0}
            .login-brand img{width:40px;height:40px}
            .login-brand-name{font-size:22px}
            .login-brand-caption{max-width:160px;font-size:11px}
            .login-visual{flex-direction:row-reverse;justify-content:space-between;gap:12px}
            .login-visual img{width:35%;max-width:130px;max-height:100px;flex-shrink:0}
            .login-copy{min-width:0}
            .login-copy h2{font-size:16px;letter-spacing:-.2px;line-height:1.4}
            .login-copy p{display:none}
            .login-main{padding:28px 24px 24px}
            .login-content{max-width:460px}
            .login-eyebrow{font-size:13px;margin-bottom:8px}
            .login-heading{font-size:29px;letter-spacing:-.6px}
            .login-description{margin:12px 0 26px;font-size:14px}
            .login-field{margin-bottom:19px}
            .login-input,.login-submit{min-height:50px;height:50px}
            .login-options{margin-bottom:22px}
            .login-footer{margin-top:22px}
        }
        @media(max-width:359.98px){
            .login-page{padding-inline:12px}.login-topbar{padding-inline:6px}
            .login-brand img{width:32px;height:32px}.login-brand-name{font-size:20px}
            .login-brand-caption{font-size:10px;max-width:125px}
            .login-main{padding:26px 20px 22px}.login-heading{font-size:26px}
            .login-aside{padding:14px}.login-copy h2{font-size:14px}
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
                    <p class="login-eyebrow">Selamat datang kembali</p>
                    <h1 class="login-heading" id="loginHeading">Masuk ke E-Prospek</h1>
                    <p class="login-description">Gunakan username dan password akun Anda.</p>
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
                        <span>Masuk</span><i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>
                <p class="login-help">
                    <i class="bi bi-info-circle" aria-hidden="true"></i>Perlu bantuan akses?
                    <a class="login-link" href="https://wa.me/6282241606980?text=Halo%20Admin%20E-Prospek%2C%20saya%20membutuhkan%20bantuan." target="_blank" rel="noopener">Hubungi administrator.</a>
                </p>
                <footer class="login-footer">&copy; {{ date('Y') }} PT BPR BKK Jateng (Perseroda)</footer>
            </div>
        </section>
        <section class="login-aside" aria-label="Tentang E-Prospek">
            <figure class="login-visual">
                <img src="{{ asset('images/login/eprospek-team-v1.webp') }}" alt="" width="1200" height="900" decoding="async" fetchpriority="high">
                <figcaption class="login-copy">
                    <h2>Kelola prospek dan<br>tindak lanjut nasabah.</h2>
                    <p>Catat calon nasabah dan pantau perkembangan pengajuannya dalam satu aplikasi.</p>
                </figcaption>
            </figure>
        </section>
    </main>
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
