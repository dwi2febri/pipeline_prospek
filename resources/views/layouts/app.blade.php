<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name','Pipeline Prospek') }}</title>

  @vite(['resources/css/app.css','resources/js/app.js'])

  <style>
    :root{
      --sidebar-w: 280px;
      --header-h: 74px;
      --bg: #f5f7fb;
      --shadow: 0 10px 30px rgba(15,23,42,.08);
      --radius: 18px;
    }
    html,body{height:100%}
    body{background:var(--bg)}
    @media (min-width:768px){ body{overflow:hidden;} }
    @media (max-width:767.98px){ body{overflow:auto;} }

    .app-shell{height:100vh;display:flex;overflow:hidden}
    .sidebar{
      width:var(--sidebar-w);height:100vh;flex:0 0 var(--sidebar-w);
      background:linear-gradient(180deg,#0b1220 0%, #0a1b35 100%);
      color:#fff;border-right:1px solid rgba(255,255,255,.06);
      display:flex;flex-direction:column;overflow:hidden;
      transition:width .25s ease;
    }
    .sidebar.collapsed{width:88px;flex:0 0 88px;}
    .brand{padding:18px 18px 14px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;gap:12px;align-items:center}
    .logo{width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#2f7bff,#1a55ff);display:flex;align-items:center;justify-content:center;box-shadow:0 10px 30px rgba(26,85,255,.35)}
    .title .h{font-weight:800;line-height:1}
    .title .s{font-size:12px;opacity:.75}

    .sidebar-scroll{flex:1 1 auto;overflow:auto;padding-bottom:8px}
    .section-title{padding:14px 18px 8px;font-size:12px;letter-spacing:.14em;opacity:.55;font-weight:700;text-transform:uppercase}
    .navwrap{padding:8px 14px}
    .navlink{display:flex;gap:12px;align-items:center;padding:12px 14px;border-radius:14px;color:rgba(255,255,255,.78);text-decoration:none;margin-bottom:6px}
    .navlink:hover{background:rgba(255,255,255,.08);color:#fff}
    .navlink.active{background:linear-gradient(135deg, rgba(47,123,255,.35), rgba(47,123,255,.18));color:#fff;border:1px solid rgba(255,255,255,.06)}
    .sidebar.collapsed .navlink span,
    .sidebar.collapsed .section-title,
    .sidebar.collapsed .brand .title{display:none;}

    .userbox{flex:0 0 auto;margin:14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);border-radius:18px;padding:12px;display:flex;justify-content:space-between;align-items:center;gap:10px}
    .avatar{width:40px;height:40px;border-radius:999px;background:rgba(255,255,255,.14);display:flex;align-items:center;justify-content:center;font-weight:800}
    .meta .n{font-weight:800}
    .meta .r{font-size:12px;opacity:.75}

    .main{flex:1 1 auto;min-width:0;height:100vh;display:flex;flex-direction:column;overflow:hidden}
    .header{height:var(--header-h);background:#fff;border-bottom:1px solid #e9edf5;box-shadow:0 10px 30px rgba(15,23,42,.06);flex:0 0 auto}
    .header .inner{height:100%;display:flex;align-items:center;justify-content:space-between;padding:0 18px;gap:12px}
    .main-scroll{flex:1 1 auto;overflow-y:auto;overflow-x:hidden}
    .page-wrap{width:100%;padding:18px;max-width:1400px;margin:0 auto}
    .iconbtn{width:42px;height:42px;border-radius:14px;border:1px solid #e6ebf5;background:#fff;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 10px 18px rgba(15,23,42,.06)}
    .chip{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:999px;background:#eef3ff;border:1px solid #dbe7ff;color:#1f3fbf;font-weight:700;font-size:13px}

    .bottom-nav{position:fixed;left:0;right:0;bottom:0;z-index:1030;background:#fff;border-top:1px solid #e9edf5}
    .bottom-nav a{flex:1;text-decoration:none;color:#6b7280;padding:10px 0;font-size:12px;text-align:center}
    .bottom-nav a.active{color:#111827;font-weight:800}
    @media (max-width:767.98px){ .page-wrap{padding-bottom:120px} }
  </style>
</head>
<body>

  <div class="d-none d-md-flex app-shell" id="appShell">
    <aside class="sidebar" id="sidebar">
      <div class="brand">
        <div class="logo">👥</div>
        <div class="title">
          <div class="h">E-Prospek</div>
          <div class="s">App Pipeline Prospek</div>
        </div>
      </div>

      <div class="sidebar-scroll">
        <div class="section-title">Menu</div>
        <div class="navwrap">
          <a href="/app/prospects" class="navlink {{ request()->is('app/prospects*') ? 'active' : '' }}"><span>Prospek</span></a>
          <a href="/app/cabangs" class="navlink {{ request()->is('app/cabangs*') ? 'active' : '' }}"><span>Master Cabang</span></a>
          <a href="/app/users" class="navlink {{ request()->is('app/users*') ? 'active' : '' }}"><span>Manajemen User</span></a>
        </div>
      </div>

      <div class="userbox">
        <div class="d-flex align-items-center gap-2">
          <div class="avatar" id="meAvatar">A</div>
          <div class="meta">
            <div class="n" id="meName">User</div>
            <div class="r" id="meRole">-</div>
          </div>
        </div>
        <button class="btn btn-light btn-sm rounded-circle" onclick="doApiLogout()" title="Logout">⎋</button>
      </div>
    </aside>

    <main class="main">
      <div class="header">
        <div class="inner">
          <div class="d-flex align-items-center gap-2">
            <button class="iconbtn" type="button" id="btnToggleSidebar">☰</button>
            <div class="chip">⚡ Pipeline Prospek Nasabah</div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <button class="iconbtn position-relative" type="button">🔔</button>
          </div>
        </div>
      </div>

      <div class="main-scroll">
        <div class="page-wrap">
          {{ $slot }}
        </div>
      </div>
    </main>
  </div>

  <div class="d-md-none">
    <div class="page-wrap">
      {{ $slot }}
    </div>

    <nav class="bottom-nav">
      <div class="container d-flex">
        <a href="/app/prospects" class="{{ request()->is('app/prospects*') ? 'active' : '' }}">Prospek</a>
        <a href="/app/cabangs" class="{{ request()->is('app/cabangs*') ? 'active' : '' }}">Cabang</a>
        <a href="/app/users" class="{{ request()->is('app/users*') ? 'active' : '' }}">User</a>
        <a href="#" onclick="doApiLogout()">Logout</a>
      </div>
    </nav>
  </div>

  <script src="/js/api.js"></script>

  <script>
    // Sidebar toggle
    (function(){
      var btn = document.getElementById('btnToggleSidebar');
      var sidebar = document.getElementById('sidebar');
      if(btn && sidebar){
        btn.addEventListener('click', function(){ sidebar.classList.toggle('collapsed'); });
      }
    })();

    // Guard token: hanya untuk halaman /app/*
    (function(){
      if(!location.pathname.startsWith('/app')) return;
      const t = localStorage.getItem('api_token');
      if(!t){ location.href='/login'; return; }

      // load profile
      API.get('/me').then(res=>{
        const me = res.data || res;
        document.getElementById('meName').textContent = me.name || 'User';
        document.getElementById('meRole').textContent = (me.role || '-').toString();
        document.getElementById('meAvatar').textContent = (me.name || 'U').trim().substring(0,1).toUpperCase();
      }).catch(()=>{
        localStorage.removeItem('api_token');
        location.href='/login';
      });
    })();

    // Logout
    async function doApiLogout(){
      try{ await API.post('/logout', {}); }catch(e){}
      localStorage.removeItem('api_token');
      location.href='/login';
    }
  </script>

</body>
</html>
