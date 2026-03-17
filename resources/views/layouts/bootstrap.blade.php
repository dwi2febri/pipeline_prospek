<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name','Pipeline Prospek') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles

  <style>
    :root{
      --sidebar-w: 280px;
      --header-h: 74px;
      --mobile-header-h: 74px;
      --bg: #f5f7fb;
      --shadow: 0 10px 30px rgba(15,23,42,.08);
      --radius: 18px;
    }

    html, body { height:100%; }
    body{ background:var(--bg); margin:0; }

    @media (min-width: 768px){
      body{ overflow:hidden; }
    }
    @media (max-width: 767.98px){
      body{ overflow:auto; }
    }

    .app-shell{
      min-height:100vh;
      display:flex;
      overflow:hidden;
    }

    .sidebar{
      width:var(--sidebar-w);
      height:100vh;
      background:linear-gradient(180deg,#0b1220 0%, #0a1b35 100%);
      color:#fff;
      flex:0 0 var(--sidebar-w);
      transition: width .25s ease, transform .25s ease;
      border-right:1px solid rgba(255,255,255,.06);
      display:flex;
      flex-direction:column;
      overflow:hidden;
    }
    .sidebar.collapsed{
      width:88px;
      flex:0 0 88px;
    }

    .sidebar .brand{
      padding:18px 18px 14px 18px;
      border-bottom:1px solid rgba(255,255,255,.08);
      display:flex;align-items:center;gap:12px;
      flex:0 0 auto;
    }
    .brand .logo{
      width:44px;height:44px;border-radius:14px;
      background:linear-gradient(135deg,#2f7bff,#1a55ff);
      display:flex;align-items:center;justify-content:center;
      box-shadow:0 10px 30px rgba(26,85,255,.35);
      flex:0 0 44px;
    }
    .brand .title{line-height:1.1}
    .brand .title .h{font-weight:800}
    .brand .title .s{font-size:12px;opacity:.75}

    .sidebar-scroll{
      flex:1 1 auto;
      overflow:auto;
      padding-bottom:8px;
    }
    .sidebar .section-title{
      padding:14px 18px 8px 18px;
      font-size:12px;
      letter-spacing:.14em;
      opacity:.55;
      font-weight:700;
      text-transform:uppercase;
    }
    .sidebar .navwrap{padding:8px 14px;}
    .sidebar .navlink{
      display:flex;align-items:center;gap:12px;
      padding:12px 14px;border-radius:14px;
      color:rgba(255,255,255,.78);
      text-decoration:none;
      transition:all .15s ease;
      margin-bottom:6px;
    }
    .sidebar .navlink:hover{background:rgba(255,255,255,.08);color:#fff}
    .sidebar .navlink.active{
      background:linear-gradient(135deg, rgba(47,123,255,.35), rgba(47,123,255,.18));
      color:#fff;
      box-shadow:0 12px 26px rgba(47,123,255,.18) inset;
      border:1px solid rgba(255,255,255,.06);
    }
    .sidebar .navlink i{font-size:18px;opacity:.95}
    .sidebar.collapsed .navlink span,
    .sidebar.collapsed .section-title,
    .sidebar.collapsed .brand .title{display:none;}

    .sidebar .userbox{
      flex:0 0 auto;
      margin:14px;
      background:rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.08);
      border-radius:18px;
      padding:12px;
      display:flex;align-items:center;justify-content:space-between;
      gap:10px;
      backdrop-filter: blur(6px);
    }
    .userbox .u{display:flex;align-items:center;gap:10px;min-width:0;}
    .userbox .avatar{
      width:40px;height:40px;border-radius:999px;
      background:rgba(255,255,255,.14);
      display:flex;align-items:center;justify-content:center;
      font-weight:800;
      flex:0 0 40px;
    }
    .userbox .meta{min-width:0}
    .userbox .meta .n{font-weight:800;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .userbox .meta .r{font-size:12px;opacity:.75}
    .sidebar.collapsed .userbox .meta{display:none;}

    .main{
      flex:1 1 auto;
      min-width:0;
      height:100vh;
      display:flex;
      flex-direction:column;
      overflow:hidden;
    }

    .header{
      height:var(--header-h);
      background:#fff;
      border-bottom:1px solid #e9edf5;
      box-shadow:0 10px 30px rgba(15,23,42,.06);
      flex:0 0 auto;
      z-index:1040;
      overflow:visible;
      position:relative;
    }
    .header .inner{
      height:100%;
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:0 18px;
      gap:12px;
    }

    .main-scroll{
      flex:1 1 auto;
      height:calc(100vh - var(--header-h));
      overflow-y:auto;
      overflow-x:hidden;
      min-height:0;
      padding-bottom:24px;
    }

    .chip{
      display:inline-flex;align-items:center;gap:8px;
      padding:10px 14px;border-radius:999px;
      background:#eef3ff;
      border:1px solid #dbe7ff;
      color:#1f3fbf;
      font-weight:700;
      font-size:13px;
    }
    .iconbtn{
      width:42px;height:42px;border-radius:14px;
      border:1px solid #e6ebf5;
      background:#fff;
      display:inline-flex;align-items:center;justify-content:center;
      box-shadow:0 10px 18px rgba(15,23,42,.06);
    }
    .iconbtn i{font-size:18px}

    .profilebtn{
      display:flex;align-items:center;gap:10px;
      padding:8px 12px;
      border-radius:999px;
      border:1px solid #111827;
      background:#fff;
      box-shadow:0 10px 18px rgba(15,23,42,.06);
      cursor:pointer;
    }
    .profilebtn .pava{
      width:34px;height:34px;border-radius:999px;
      background:#eef3ff;
      display:flex;align-items:center;justify-content:center;
      font-weight:900;color:#1f3fbf;
      flex:0 0 34px;
    }
    .profilebtn .txt{line-height:1;min-width:0}
    .profilebtn .txt .n{font-weight:900}
    .profilebtn .txt .r{font-size:12px;color:#6b7280;margin-top:2px}

    .profile-dropdown-wrap{
      position:relative;
      z-index:3000;
    }

    .profile-menu{
      position:absolute;
      top:calc(100% + 10px);
      right:0;
      min-width:260px;
      background:#fff;
      border:1px solid #edf1f7;
      border-radius:16px;
      box-shadow:0 20px 60px rgba(15,23,42,.18);
      overflow:hidden;
      z-index:5000;
      display:none;
    }
    .profile-menu.show{
      display:block;
    }
    .profile-menu .head{
      padding:12px 14px;
      border-bottom:1px solid #edf1f7;
    }
    .profile-menu .item{
      display:block;
      width:100%;
      text-align:left;
      padding:10px 14px;
      text-decoration:none;
      color:#111827;
      background:#fff;
      border:0;
    }
    .profile-menu .item:hover{
      background:#f8fafc;
    }

    .page-wrap{
      width:100%;
      padding:18px;
      max-width:1400px;
      margin:0 auto;
    }

    .content-wrap{padding-bottom:78px;}
    @media (min-width: 768px){
      .content-wrap{padding-bottom:0;}
    }

    .bottom-nav{
      position:fixed;
      left:0;
      right:0;
      bottom:0;
      z-index:1030;
      background:#fff;
      border-top:1px solid #e9edf5;
    }
    .bottom-nav a,
    .bottom-nav button{
      flex:1;
      text-decoration:none;
      color:#6b7280;
      padding:10px 0;
      font-size:12px;
      background:transparent;
      border:0;
    }
    .bottom-nav a.active{color:#111827;font-weight:800}

    .card-soft{
      border:0;
      border-radius:var(--radius);
      box-shadow: var(--shadow);
      background:#fff;
    }

    @media (max-width: 767.98px){
      .app-shell{
        display:block;
        min-height:100vh;
        overflow:visible;
      }

      .main{
        min-height:100vh;
        height:auto;
        overflow:visible;
      }

      .header{
        height:auto;
      }

      .header.d-md-none{
        position:sticky;
        top:0;
        z-index:1040;
        background:#fff;
        box-shadow:0 6px 18px rgba(15,23,42,.08);
      }

      .page-wrap{
        max-width:100%;
        margin:0;
        padding:14px;
      }

      .main-scroll{
        height:auto;
        overflow:visible;
        padding-bottom:0;
      }

      .content-wrap{
        padding-top:0;
        padding-bottom:78px;
      }

      .mobile-profile-btn{
        padding:0;
        border:0;
        background:transparent;
        box-shadow:none;
      }

      .mobile-profile-btn .pava{
        width:42px;
        height:42px;
      }

      .profile-menu{
        min-width:220px;
      }
    }

    .modal-backdrop.show{
      z-index:2000 !important;
    }
    .modal.show{
      z-index:2001 !important;
    }

    .leaflet-control-attribution {
      display: none !important;
    }

    .leaflet-control-zoom {
      margin-top: 12px !important;
      margin-right: 12px !important;
    }

    .notif-wrap{
      position: relative;
      z-index: 7000;
    }
  </style>
</head>

<body>
  @php
    $role = strtoupper(trim((string) (auth()->user()->role ?? '')));

    $isAdmin      = $role === 'ADMIN';
    $isManajemen  = $role === 'MANAJEMEN';
    $isSupervisor = $role === 'SUPERVISOR';
    $isAo         = in_array($role, ['AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL']);
    $isPegawai    = $role === 'PEGAWAI';

    $canDashboard         = $isAdmin || $isManajemen || $isSupervisor;
    $canProspects         = $isAdmin || $isManajemen || $isSupervisor || $isAo || $isPegawai;
    $canProspectsDiajukan = $isAdmin || $isManajemen || $isSupervisor || $isAo;
    $canRekapProspek = $isAdmin || $isManajemen || $isSupervisor;
    $canRecycleBin        = $isAdmin || $isManajemen || $isSupervisor || $isAo || $isPegawai;
    $canProfile           = auth()->check();
    $canAuditLog          = $isAdmin;
    $canMasterCabang      = $isAdmin;
    $canUsers             = $isAdmin;
  @endphp

  <div class="app-shell" id="appShell">

    <aside class="sidebar d-none d-md-flex" id="sidebar">
      <div class="brand">
        <div class="logo"><i class="bi bi-people-fill fs-4"></i></div>
        <div class="title">
          <div class="h">E-Prospek</div>
          <div class="s">App Pipeline Prospek</div>
        </div>
      </div>

      <div class="sidebar-scroll">
        <div class="section-title">Menu</div>
        <div class="navwrap">

          @if($canDashboard)
            <a href="/dashboard" class="navlink {{ request()->is('dashboard') ? 'active' : '' }}">
              <i class="bi bi-speedometer2"></i><span>Dashboard</span>
            </a>
          @endif

          @if($canProspects)
            <a href="/prospects" class="navlink {{ request()->is('prospects') || request()->is('prospects/create') || request()->is('prospects/*/edit') ? 'active' : '' }}">
              <i class="bi bi-grid"></i><span>Prospek</span>
            </a>
          @endif

            @if($canRekapProspek)
            <a href="/rekap-prospek" class="navlink {{ request()->is('rekap-prospek') ? 'active' : '' }}">
                <i class="bi bi-table"></i><span>Rekap Prospek</span>
            </a>
            @endif

          @if($canProspectsDiajukan)
            <a href="/prospects-diajukan" class="navlink {{ request()->is('prospects-diajukan') ? 'active' : '' }}">
              <i class="bi bi-send-check"></i><span>Prospek Diajukan</span>
            </a>
          @endif

          @if($canAuditLog)
            <a href="/audit-logs" class="navlink {{ request()->is('audit-logs') ? 'active' : '' }}">
              <i class="bi bi-file-earmark-text"></i><span>Audit Log</span>
            </a>
          @endif

          @if($canRecycleBin)
            <a href="/recycle-bin/prospects" class="navlink {{ request()->is('recycle-bin/prospects') ? 'active' : '' }}">
              <i class="bi bi-trash3"></i><span>Recycle Bin</span>
            </a>
          @endif

          @if($canProfile)
            <a href="{{ route('profile.index') }}" class="navlink {{ request()->is('profile') ? 'active' : '' }}">
              <i class="bi bi-person-circle"></i><span>Profil Saya</span>
            </a>
          @endif
        </div>

        @if($canMasterCabang || $canUsers)
          <div class="section-title">Admin</div>
          <div class="navwrap">
            @if($canMasterCabang)
              <a href="/cabangs" class="navlink {{ request()->is('cabangs*') ? 'active' : '' }}">
                <i class="bi bi-building"></i><span>Master Cabang</span>
              </a>
            @endif

            @if($canUsers)
              <a href="/users" class="navlink {{ request()->is('users*') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i><span>Manajemen User</span>
              </a>
            @endif
          </div>
        @endif
      </div>

      <div class="userbox">
        <div class="u">
          <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U',0,1)) }}</div>
          <div class="meta">
            <div class="n">{{ auth()->user()->name ?? 'User' }}</div>
            <div class="r">{{ strtoupper(auth()->user()->role ?? '-') }}</div>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Logout">
            <i class="bi bi-box-arrow-right"></i>
          </button>
        </form>
      </div>
    </aside>

    <main class="main">

      <div class="header d-none d-md-block">
        <div class="inner">
          <div class="d-flex align-items-center gap-2">
            <button class="iconbtn" type="button" id="btnToggleSidebar" title="Toggle Sidebar">
              <i class="bi bi-list"></i>
            </button>
            <div class="chip">
              <i class="bi bi-lightning-charge-fill"></i> Pipeline Prospek Nasabah
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <div class="notif-wrap">
              @livewire('notifications.bell', [], key('desktop-bell-' . auth()->id()))
            </div>

            <div class="profile-dropdown-wrap" id="desktopProfileWrap">
              <button class="profilebtn" type="button" id="desktopProfileBtn">
                <div class="pava">{{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}</div>
                <div class="txt text-start">
                  <div class="n">{{ auth()->user()->name ?? 'User' }}</div>
                  <div class="r">{{ strtoupper(auth()->user()->role ?? '-') }}</div>
                </div>
                <i class="bi bi-chevron-down ms-1"></i>
              </button>

              <div class="profile-menu" id="desktopProfileMenu">
                <div class="head">
                  <div class="fw-bold">{{ auth()->user()->name ?? 'User' }}</div>
                  <div class="text-muted small">{{ auth()->user()->email ?? '' }}</div>
                </div>

                @if($canProfile)
                  <a class="item" href="{{ route('profile.index') }}">
                    <i class="bi bi-person-circle me-2"></i> Profil Saya
                  </a>
                @endif

                @if($canProspects)
                  <a class="item" href="/prospects">
                    <i class="bi bi-grid me-2"></i> Prospek
                  </a>
                @endif

                <div class="px-3 py-2 border-top">
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">
                      <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="header d-md-none" style="background:#fff;">
        <div class="inner" style="padding:14px 14px;height:auto;">
          <div class="d-flex align-items-center gap-2">
            <div class="logo" style="width:42px;height:42px;border-radius:14px;">
              <i class="bi bi-people-fill fs-5 text-white"></i>
            </div>
            <div>
              <div class="fw-bold">E-Prospek</div>
              <div class="text-muted small">Pipeline Prospek Nasabah</div>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <div class="notif-wrap">
              @livewire('notifications.bell', [], key('mobile-bell-' . auth()->id()))
            </div>

            <div class="profile-dropdown-wrap" id="mobileProfileWrap">
              <button class="profilebtn mobile-profile-btn" type="button" id="mobileProfileBtn">
                <div class="pava">
                  {{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}
                </div>
              </button>

              <div class="profile-menu" id="mobileProfileMenu">
                <div class="head">
                  <div class="fw-bold">{{ auth()->user()->name ?? 'User' }}</div>
                  <div class="text-muted small">{{ strtoupper(auth()->user()->role ?? '-') }}</div>
                  <div class="text-muted small">{{ auth()->user()->email ?? '' }}</div>
                </div>

                @if($canProfile)
                  <a class="item" href="{{ route('profile.index') }}">
                    <i class="bi bi-person-circle me-2"></i> Profil Saya
                  </a>
                @endif

                @if($canProspects)
                  <a class="item" href="/prospects">
                    <i class="bi bi-grid me-2"></i> Prospek
                  </a>
                @endif

                <div class="px-3 py-2 border-top">
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">
                      <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="main-scroll content-wrap">
        <div class="page-wrap">
          {{ $slot }}
        </div>
      </div>
    </main>
  </div>

  <nav class="bottom-nav d-md-none">
    <div class="container d-flex text-center">
      @if($canDashboard)
        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
          <div><i class="bi bi-speedometer2 fs-5"></i></div>
          Dashboard
        </a>
      @endif

      @if($canProspects)
        <a href="/prospects" class="{{ request()->is('prospects*') ? 'active' : '' }}">
          <div><i class="bi bi-grid fs-5"></i></div>
          Prospek
        </a>
      @endif

        @if($canRekapProspek)
        <a href="/rekap-prospek" class="{{ request()->is('rekap-prospek') ? 'active' : '' }}">
            <div><i class="bi bi-table fs-5"></i></div>
            Rekap
        </a>
        @endif

      @if($canProspectsDiajukan)
        <a href="/prospects-diajukan" class="{{ request()->is('prospects-diajukan') ? 'active' : '' }}">
          <div><i class="bi bi-send-check fs-5"></i></div>
          Diajukan
        </a>
      @endif

      @if($canAuditLog)
        <a href="/audit-logs" class="{{ request()->is('audit-logs') ? 'active' : '' }}">
          <div><i class="bi bi-file-earmark-text fs-5"></i></div>
          Audit
        </a>
      @endif

      @if($canUsers)
        <a href="/users" class="{{ request()->is('users*') ? 'active' : '' }}">
          <div><i class="bi bi-person-gear fs-5"></i></div>
          User
        </a>
      @endif

      @if($canProfile)
        <a href="{{ route('profile.index') }}" class="{{ request()->is('profile') ? 'active' : '' }}">
          <div><i class="bi bi-person fs-5"></i></div>
          Profil
        </a>
      @endif

      <form method="POST" action="{{ route('logout') }}" style="flex:1;">
        @csrf
        <button type="submit" class="w-100">
          <div><i class="bi bi-box-arrow-right fs-5"></i></div>
          Logout
        </button>
      </form>
    </div>
  </nav>

  <script
    src="/vendor/livewire/livewire.js"
    data-csrf="{{ csrf_token() }}"
    data-update-uri="{{ route('default-livewire.update') }}"
    data-upload-uri="{{ route('livewire.upload-file') }}">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    (function(){
      var btn = document.getElementById('btnToggleSidebar');
      var sidebar = document.getElementById('sidebar');
      if(!btn || !sidebar) return;
      btn.addEventListener('click', function(){
        sidebar.classList.toggle('collapsed');
      });
    })();

    (function(){
      function bindProfileDropdown(btnId, menuId, wrapId){
        var btn = document.getElementById(btnId);
        var menu = document.getElementById(menuId);
        var wrap = document.getElementById(wrapId);
        if(!btn || !menu || !wrap) return;

        btn.addEventListener('click', function(e){
          e.preventDefault();
          e.stopPropagation();

          document.querySelectorAll('.profile-menu.show').forEach(function(el){
            if(el !== menu) el.classList.remove('show');
          });

          menu.classList.toggle('show');
        });

        menu.addEventListener('click', function(e){
          e.stopPropagation();
        });
      }

      bindProfileDropdown('desktopProfileBtn', 'desktopProfileMenu', 'desktopProfileWrap');
      bindProfileDropdown('mobileProfileBtn', 'mobileProfileMenu', 'mobileProfileWrap');

      document.addEventListener('click', function(){
        document.querySelectorAll('.profile-menu.show').forEach(function(el){
          el.classList.remove('show');
        });
      });
    })();
  </script>

  @stack('scripts')
</body>
</html>
