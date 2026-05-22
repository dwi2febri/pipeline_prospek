<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name','E-Prospek') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles

  <style>
    :root{
      --sidebar-w: 280px;
      --header-h: 74px;
      --mobile-bottom-nav-h: 64px;
      --bg: #f5f7fb;
      --shadow: 0 10px 30px rgba(15,23,42,.08);
      --radius: 18px;
      --desktop-scale: .65;
      --desktop-scale-width: 153.846153846vw;
      --desktop-scale-height: 153.846153846vh;
    }

    html,
    body{
      width:100%;
      height:100%;
      margin:0;
      background:var(--bg);
    }

    body{
      overflow:hidden;
    }

    .app-shell{
      min-height:100vh;
      display:flex;
      overflow:hidden;
    }

    /*
      DESKTOP DEFAULT ZOOM OUT 65%
      Tidak berlaku di mobile karena hanya aktif min-width 768px.
    */
    @media (min-width: 768px){
      html,
      body{
        width:100%;
        height:100%;
        overflow:hidden;
      }

      /*
        DESKTOP ZOOM 65% YANG AMAN UNTUK LIVEWIRE
        Jangan pakai transform:scale pada .app-shell karena membuat modal Bootstrap
        dan wire:click/wire:model Livewire sering bermasalah.
      */
      body{
        zoom:65%;
        width:153.846153846%;
        height:153.846153846%;
      }

      .app-shell{
        width:100%;
        min-height:153.846153846vh;
        transform:none !important;
      }

      .sidebar{
        height:153.846153846vh !important;
      }

      .main{
        height:153.846153846vh !important;
      }

      .main-scroll{
        height:calc(153.846153846vh - var(--header-h)) !important;
      }
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
      display:flex;
      align-items:center;
      gap:12px;
      flex:0 0 auto;
    }

    .brand .logo{
      width:44px;
      height:44px;
      border-radius:14px;
      background:linear-gradient(135deg,#2f7bff,#1a55ff);
      display:flex;
      align-items:center;
      justify-content:center;
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
      display:flex;
      align-items:center;
      gap:12px;
      padding:12px 14px;
      border-radius:14px;
      color:rgba(255,255,255,.78);
      text-decoration:none;
      transition:all .15s ease;
      margin-bottom:6px;
    }

    .sidebar .navlink:hover{
      background:rgba(255,255,255,.08);
      color:#fff;
    }

    .sidebar .navlink.active{
      background:linear-gradient(135deg, rgba(47,123,255,.35), rgba(47,123,255,.18));
      color:#fff;
      box-shadow:0 12px 26px rgba(47,123,255,.18) inset;
      border:1px solid rgba(255,255,255,.06);
    }

    .sidebar .navlink i{
      font-size:18px;
      opacity:.95;
    }

    .sidebar.collapsed .navlink span,
    .sidebar.collapsed .section-title,
    .sidebar.collapsed .brand .title{
      display:none;
    }

    .sidebar .userbox{
      flex:0 0 auto;
      margin:14px;
      background:rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.08);
      border-radius:18px;
      padding:12px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      backdrop-filter: blur(6px);
    }

    .userbox .u{
      display:flex;
      align-items:center;
      gap:10px;
      min-width:0;
    }

    .userbox .avatar{
      width:40px;
      height:40px;
      border-radius:999px;
      background:rgba(255,255,255,.14);
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight:800;
      flex:0 0 40px;
    }

    .userbox .meta{
      min-width:0;
    }

    .userbox .meta .n{
      font-weight:800;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .userbox .meta .r{
      font-size:12px;
      opacity:.75;
    }

    .sidebar.collapsed .userbox .meta{
      display:none;
    }

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
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:10px 14px;
      border-radius:999px;
      background:#eef3ff;
      border:1px solid #dbe7ff;
      color:#1f3fbf;
      font-weight:700;
      font-size:13px;
    }

    .iconbtn{
      width:42px;
      height:42px;
      border-radius:14px;
      border:1px solid #e6ebf5;
      background:#fff;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      box-shadow:0 10px 18px rgba(15,23,42,.06);
    }

    .iconbtn i{
      font-size:18px;
    }

    .profilebtn{
      display:flex;
      align-items:center;
      gap:10px;
      padding:8px 12px;
      border-radius:999px;
      border:1px solid #111827;
      background:#fff;
      box-shadow:0 10px 18px rgba(15,23,42,.06);
      cursor:pointer;
    }

    .profilebtn .pava{
      width:34px;
      height:34px;
      border-radius:999px;
      background:#eef3ff;
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight:900;
      color:#1f3fbf;
      flex:0 0 34px;
    }

    .profilebtn .txt{
      line-height:1;
      min-width:0;
    }

    .profilebtn .txt .n{
      font-weight:900;
    }

    .profilebtn .txt .r{
      font-size:12px;
      color:#6b7280;
      margin-top:2px;
    }

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
      max-width:none;
      margin:0;
    }

    .content-wrap{
      padding-bottom:94px;
    }

    @media (min-width: 768px){
      .content-wrap{
        padding-bottom:0;
      }
    }

    .bottom-nav{
      position:fixed;
      left:0;
      right:0;
      bottom:0;
      z-index:3000;
      background:#fff;
      border-top:1px solid #e9edf5;
      box-shadow:0 -8px 30px rgba(15,23,42,.08);
      transition:all .25s ease;
    }

    .bottom-nav .nav-inner{
      position:relative;
      display:flex;
      text-align:center;
      align-items:stretch;
      justify-content:space-between;
      gap:0;
      min-height:64px;
      padding-left:0;
      padding-right:0;
      z-index:1;
    }

    .mobile-nav-grabber{
      position:absolute;
      top:-16px;
      left:0;
      right:0;
      transform:none;
      width:100%;
      height:18px;
      display:flex;
      align-items:center;
      justify-content:center;
      cursor:pointer;
      z-index:5;
      background:transparent;
      border:0;
      padding:0;
      margin:0;
      border-radius:0;
      box-shadow:none;
    }

    .mobile-nav-grabber::before{
      content:"";
      width:42px;
      height:4px;
      border-radius:999px;
      background:#cbd5e1;
      display:block;
      box-shadow:none;
    }

    .mobile-nav-item,
    .bottom-nav form.mobile-nav-form{
      flex:1 1 0;
      min-width:0;
      display:flex;
    }

    .mobile-nav-item a,
    .bottom-nav form.mobile-nav-form button{
      flex:1 1 auto;
      text-decoration:none;
      color:#6b7280;
      padding:8px 2px 5px;
      font-size:11.5px;
      background:transparent;
      border:0;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap:4px;
      line-height:1.1;
      min-width:0;
    }

    .mobile-nav-item a.active,
    .bottom-nav form.mobile-nav-form button.active{
      color:#111827;
      font-weight:800;
    }

    .mobile-nav-label{
      display:block;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:100%;
    }

    .mobile-svg-icon{
      width:21px;
      height:21px;
      display:block;
      color:currentColor;
      flex:0 0 auto;
    }

    .mobile-svg-icon svg{
      width:100%;
      height:100%;
      display:block;
      stroke:currentColor;
      fill:none;
      stroke-width:1.9;
      stroke-linecap:round;
      stroke-linejoin:round;
    }

    .mobile-menu-sheet-backdrop{
      position:fixed;
      inset:0;
      background:rgba(15,23,42,.42);
      backdrop-filter:blur(2px);
      z-index:9000;
      opacity:0;
      visibility:hidden;
      transition:.22s ease;
      pointer-events:none;
    }

    .mobile-menu-sheet-backdrop.show{
      opacity:1;
      visibility:visible;
      pointer-events:auto;
    }

    .mobile-menu-sheet{
      position:fixed;
      left:0;
      right:0;
      bottom:0;
      z-index:9001;
      background:#fff;
      border-radius:26px 26px 0 0;
      box-shadow:0 -24px 70px rgba(15,23,42,.28);
      transform:translateY(105%);
      transition:transform .25s ease;
      will-change:transform;
      max-height:84vh;
      overflow:hidden;
      pointer-events:none;
    }

    .mobile-menu-sheet.show{
      transform:translateY(0) !important;
      pointer-events:auto;
    }

    .mobile-menu-sheet .sheet-handle{
      height:30px;
      display:flex;
      align-items:center;
      justify-content:center;
      cursor:pointer;
      background:#fff;
    }

    .mobile-menu-sheet .sheet-handle::before{
      content:"";
      width:54px;
      height:6px;
      border-radius:999px;
      background:#cbd5e1;
      display:block;
    }

    .mobile-menu-sheet .sheet-head{
      padding:2px 18px 14px;
      border-bottom:1px solid #eef2f7;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
    }

    .mobile-menu-sheet .sheet-title{
      font-size:1.1rem;
      font-weight:900;
      color:#0f172a;
    }

    .mobile-menu-sheet .sheet-sub{
      color:#64748b;
      font-size:.84rem;
      margin-top:2px;
    }

    .mobile-menu-sheet .sheet-close{
      width:40px;
      height:40px;
      border-radius:13px;
      border:1px solid #e6ebf5;
      background:#fff;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#334155;
    }

    .mobile-menu-sheet .sheet-body{
      max-height:calc(84vh - 92px);
      overflow-y:auto;
      -webkit-overflow-scrolling:touch;
      padding:16px 16px 28px;
      background:#f8fafc;
    }

    .mobile-menu-grid{
      display:grid;
      grid-template-columns:repeat(3, minmax(0, 1fr));
      gap:11px;
    }

    .mobile-menu-link,
    .mobile-menu-logout{
      text-decoration:none;
      color:#0f172a;
      background:#fff;
      border:1px solid #e8eef6;
      border-radius:17px;
      padding:12px 8px;
      box-shadow:0 8px 24px rgba(15,23,42,.05);
      min-height:86px;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      text-align:center;
      gap:8px;
    }

    .mobile-menu-link.active{
      border-color:#bfdbfe;
      background:linear-gradient(180deg,#eff6ff 0%,#ffffff 100%);
      color:#1d4ed8;
    }

    .mobile-menu-logout{
      width:100%;
    }

    .mobile-menu-link .mobile-svg-icon,
    .mobile-menu-logout .mobile-svg-icon{
      width:23px;
      height:23px;
    }

    .mobile-menu-link span,
    .mobile-menu-logout span{
      font-size:.74rem;
      font-weight:700;
      line-height:1.15;
    }

    .card-soft{
      border:0;
      border-radius:var(--radius);
      box-shadow: var(--shadow);
      background:#fff;
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

    .session-expired-modal .modal-content{
      border:0;
      border-radius:24px;
      overflow:hidden;
      box-shadow:0 24px 80px rgba(15,23,42,.24);
    }

    .session-expired-modal .modal-header{
      border-bottom:1px solid #eef2f7;
      background:linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
      padding:20px 22px 16px 22px;
    }

    .session-expired-modal .modal-body{
      padding:22px;
      background:#fff;
    }

    .session-expired-modal .modal-footer{
      border-top:1px solid #eef2f7;
      padding:16px 22px 22px 22px;
      background:#fff;
    }

    .session-expired-head{
      display:flex;
      align-items:flex-start;
      gap:14px;
    }

    .session-expired-icon{
      width:52px;
      height:52px;
      border-radius:18px;
      display:flex;
      align-items:center;
      justify-content:center;
      flex:0 0 52px;
      color:#b45309;
      background:linear-gradient(135deg,#fef3c7 0%,#fde68a 100%);
      box-shadow:0 10px 24px rgba(245,158,11,.18);
      font-size:22px;
    }

    .session-expired-title{
      font-size:1.18rem;
      font-weight:900;
      color:#0f172a;
      line-height:1.2;
    }

    .session-expired-sub{
      color:#64748b;
      font-size:.92rem;
      margin-top:4px;
    }

    .session-expired-card{
      border:1px solid #e8eef6;
      border-radius:18px;
      padding:14px 16px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
    }

    .session-expired-card-title{
      font-size:.92rem;
      font-weight:800;
      color:#0f172a;
      margin-bottom:4px;
    }

    .session-expired-card-desc{
      font-size:.9rem;
      color:#64748b;
      margin:0;
    }

    /*
      MOBILE FINAL
      Scroll aktif, bottom nav pas, dan tidak memengaruhi desktop.
    */
    @media (max-width: 767.98px){
      html,
      body{
        width:100% !important;
        height:100% !important;
        min-height:100dvh !important;
        margin:0 !important;
        padding:0 !important;
        overflow:hidden !important;
        background:#f5f7fb !important;
        touch-action:auto !important;
      }

      body{
        position:relative !important;
      }

      .app-shell{
        display:block !important;
        width:100% !important;
        height:100dvh !important;
        min-height:100dvh !important;
        overflow:hidden !important;
        padding:0 !important;
        margin:0 !important;
        transform:none !important;
      }

      .main{
        display:flex !important;
        flex-direction:column !important;
        width:100% !important;
        height:100dvh !important;
        min-height:100dvh !important;
        overflow:hidden !important;
        padding:0 !important;
        margin:0 !important;
      }

      .header{
        height:auto !important;
      }

      .header.d-md-none{
        position:relative !important;
        top:auto !important;
        flex:0 0 auto !important;
        width:100% !important;
        z-index:1040 !important;
        background:#fff !important;
        box-shadow:0 6px 18px rgba(15,23,42,.08) !important;
      }

      .header.d-md-none .inner{
        min-height:74px !important;
        height:auto !important;
        padding:14px 14px !important;
      }

      .main-scroll{
        flex:1 1 auto !important;
        height:auto !important;
        min-height:0 !important;
        overflow-y:auto !important;
        overflow-x:hidden !important;
        -webkit-overflow-scrolling:touch !important;
        overscroll-behavior-y:contain !important;
        padding:0 !important;
        margin:0 !important;
        touch-action:pan-y !important;
      }

      .content-wrap{
        padding-top:0 !important;
        padding-bottom:calc(var(--mobile-bottom-nav-h) + 42px) !important;
      }

      .page-wrap{
        width:100% !important;
        max-width:100% !important;
        padding:14px 14px 24px 14px !important;
        margin:0 !important;
      }

      .bottom-nav{
        position:fixed !important;
        left:0 !important;
        right:0 !important;
        bottom:0 !important;
        width:100% !important;
        height:64px !important;
        min-height:64px !important;
        padding:0 !important;
        margin:0 !important;
        background:#fff !important;
        border-top:1px solid #e5e7eb !important;
        box-shadow:0 -10px 28px rgba(15,23,42,.08) !important;
        z-index:3000 !important;
        display:flex !important;
        align-items:center !important;
        justify-content:center !important;
        transform:none !important;
      }

      .bottom-nav::before,
      .bottom-nav::after{
        display:none !important;
        content:none !important;
      }

      .bottom-nav .container,
      .bottom-nav .nav-inner{
        width:100% !important;
        max-width:100% !important;
        height:64px !important;
        min-height:64px !important;
        padding:0 6px !important;
        margin:0 !important;
        display:flex !important;
        align-items:center !important;
        justify-content:space-between !important;
      }

      .mobile-nav-item,
      .bottom-nav form.mobile-nav-form{
        height:64px !important;
        flex:1 1 0 !important;
        display:flex !important;
        align-items:center !important;
        justify-content:center !important;
        margin:0 !important;
        padding:0 !important;
      }

      .mobile-nav-item a,
      .bottom-nav form.mobile-nav-form button{
        height:64px !important;
        padding:6px 2px 5px !important;
        margin:0 !important;
        display:flex !important;
        flex-direction:column !important;
        align-items:center !important;
        justify-content:center !important;
        gap:3px !important;
        font-size:11px !important;
        line-height:1.1 !important;
        color:#6b7280 !important;
        background:transparent !important;
        border:0 !important;
        text-decoration:none !important;
      }

      .mobile-nav-item a.active,
      .bottom-nav form.mobile-nav-form button.active{
        color:#111827 !important;
        font-weight:800 !important;
      }

      .mobile-svg-icon{
        width:20px !important;
        height:20px !important;
        margin:0 !important;
        padding:0 !important;
      }

      .mobile-nav-label{
        font-size:11px !important;
        line-height:1.1 !important;
        white-space:nowrap !important;
        overflow:hidden !important;
        text-overflow:ellipsis !important;
        max-width:100% !important;
      }

      .bottom-nav{
        overflow:visible !important;
      }

      .bottom-nav .nav-inner{
        overflow:visible !important;
      }

      .mobile-nav-grabber{
        position:absolute !important;
        top:-16px !important;
        left:0 !important;
        right:0 !important;
        transform:none !important;
        width:100% !important;
        height:18px !important;
        display:flex !important;
        align-items:center !important;
        justify-content:center !important;
        background:transparent !important;
        border:0 !important;
        padding:0 !important;
        margin:0 !important;
        border-radius:0 !important;
        box-shadow:none !important;
        cursor:pointer !important;
        z-index:5 !important;
      }

      .mobile-nav-grabber::before{
        content:"" !important;
        width:42px !important;
        height:4px !important;
        background:#cbd5e1 !important;
        border-radius:999px !important;
        display:block !important;
        box-shadow:none !important;
      }

      .mobile-nav-grabber::after{
        content:none !important;
        display:none !important;
      }

      .mobile-profile-btn{
        padding:0 !important;
        border:0 !important;
        background:transparent !important;
        box-shadow:none !important;
      }

      .mobile-profile-btn .pava{
        width:42px !important;
        height:42px !important;
      }

      .profile-menu{
        min-width:220px !important;
      }

      .notif-wrap{
        position:relative !important;
        z-index:9500 !important;
      }

      .notif-wrap .dropdown-menu.show,
      .notif-wrap .dropdown-menu,
      .notif-wrap > div > div:not(:first-child){
        position:fixed !important;
        top:88px !important;
        left:10px !important;
        right:10px !important;
        width:auto !important;
        max-width:calc(100vw - 20px) !important;
        min-width:0 !important;
        max-height:58vh !important;
        overflow-y:auto !important;
        overflow-x:hidden !important;
        border-radius:18px !important;
        box-shadow:0 18px 50px rgba(15,23,42,.22) !important;
        z-index:9600 !important;
      }

      .notif-wrap h1,
      .notif-wrap h2,
      .notif-wrap h3,
      .notif-wrap h4,
      .notif-wrap .fw-bold,
      .notif-wrap strong{
        font-size:.9rem !important;
        line-height:1.25 !important;
      }

      .notif-wrap p,
      .notif-wrap span,
      .notif-wrap div,
      .notif-wrap a,
      .notif-wrap button{
        font-size:.82rem;
      }

      .notif-wrap [class*="item"],
      .notif-wrap li,
      .notif-wrap .list-group-item{
        padding:10px 12px !important;
      }

      .notif-wrap small{
        font-size:.74rem !important;
      }

      .notif-wrap .badge{
        font-size:.68rem !important;
      }

      .btn-add-prospect,
      .add-prospect-btn,
      .btn-create-prospect,
      .create-prospect-btn,
      .prospect-add-btn,
      .floating-add,
      .floating-plus,
      .fab,
      .floating-action,
      .floating-btn,
      .btn-floating{
        position:fixed !important;
        right:18px !important;
        bottom:86px !important;
        z-index:2999 !important;
      }

      .btn-ai,
      .ai-btn,
      .ai-assistant-btn,
      .floating-ai,
      .chat-ai-btn,
      .assistant-ai-btn,
      button[class*="ai"],
      a[class*="ai"],
      div[class*="ai"][role="button"],
      button[class*="AI"],
      a[class*="AI"],
      div[class*="AI"][role="button"]{
        position:fixed !important;
        right:18px !important;
        bottom:154px !important;
        z-index:2999 !important;
      }

      body.mobile-sheet-open{
        overflow:hidden !important;
        touch-action:none !important;
      }

      body.mobile-sheet-open .main-scroll{
        overflow:hidden !important;
      }

      body.mobile-sheet-open .bottom-nav{
        transform:translateY(110%) !important;
        opacity:0 !important;
        pointer-events:none !important;
      }
    }

    /* ==========================================================
       FIX FINAL MODAL / POPUP
       - Modal tetap di root Livewire supaya wire:click / wire:model tetap aktif
       - Background modal dibuat solid putih agar tidak transparan/blank
       - Aman untuk mobile dan desktop
       ========================================================== */
    body.modal-open{
      overflow:hidden !important;
      padding-right:0 !important;
    }

    .modal{
      position:fixed !important;
      inset:0 !important;
      z-index:12000 !important;
      overflow-x:hidden !important;
      overflow-y:auto !important;
      background:transparent !important;
      pointer-events:none;
    }

    .modal.show{
      display:block !important;
      pointer-events:auto !important;
    }

    .modal-backdrop{
      position:fixed !important;
      inset:0 !important;
      width:100vw !important;
      height:100vh !important;
      z-index:11990 !important;
      background:#0f172a !important;
    }

    .modal-backdrop.show{
      opacity:.56 !important;
    }

    .modal-dialog{
      position:relative !important;
      z-index:12001 !important;
      pointer-events:auto !important;
      transform:none !important;
      opacity:1 !important;
    }

    .modal.fade .modal-dialog,
    .modal.show .modal-dialog{
      transition:none !important;
      transform:none !important;
    }

    .modal-content{
      background:#ffffff !important;
      color:#0f172a !important;
      opacity:1 !important;
      visibility:visible !important;
      border:0 !important;
      border-radius:22px !important;
      box-shadow:0 28px 90px rgba(15,23,42,.32) !important;
      overflow:hidden !important;
    }

    .modal-header,
    .modal-body,
    .modal-footer{
      background:#ffffff !important;
      opacity:1 !important;
      color:#0f172a !important;
    }

    .modal .btn-close{
      opacity:.75 !important;
      z-index:2 !important;
    }


    @media (max-width: 767.98px){
      .modal{
        z-index:12000 !important;
        padding:10px 0 calc(var(--mobile-bottom-nav-h) + 18px) !important;
      }

      .modal-backdrop{
        z-index:11990 !important;
      }

      .modal-dialog{
        width:auto !important;
        max-width:calc(100vw - 20px) !important;
        margin:10px auto calc(var(--mobile-bottom-nav-h) + 18px) !important;
      }

      .modal-dialog.modal-lg,
      .modal-dialog.modal-xl,
      .modal-lg,
      .modal-xl{
        max-width:calc(100vw - 20px) !important;
      }

      .modal-dialog-scrollable{
        height:calc(100dvh - var(--mobile-bottom-nav-h) - 38px) !important;
        max-height:calc(100dvh - var(--mobile-bottom-nav-h) - 38px) !important;
      }

      .modal-dialog-scrollable .modal-content{
        max-height:calc(100dvh - var(--mobile-bottom-nav-h) - 38px) !important;
      }

      .modal-body{
        max-height:calc(100dvh - var(--mobile-bottom-nav-h) - 150px) !important;
        overflow-y:auto !important;
        -webkit-overflow-scrolling:touch !important;
      }

      .modal table{
        font-size:.82rem !important;
      }

      .modal .table-responsive{
        overflow-x:auto !important;
        -webkit-overflow-scrolling:touch !important;
      }
    }



    /* ==========================================================
       FIX FINAL MODAL MOBILE NGEBLINK / KEDIP
       - Matikan fade/transition modal hanya di mobile
       - Desktop tetap aman dengan zoom 65%
       ========================================================== */
    @media (max-width: 767.98px){
      .modal.fade,
      .modal.fade .modal-dialog,
      .modal.show .modal-dialog,
      .modal .modal-dialog,
      .modal .modal-content,
      .modal-backdrop,
      .modal-backdrop.fade{
        transition:none !important;
        animation:none !important;
      }

      .modal.fade .modal-dialog,
      .modal.show .modal-dialog,
      .modal .modal-dialog{
        transform:none !important;
        opacity:1 !important;
        will-change:auto !important;
      }

      .modal .modal-content{
        transform:none !important;
        opacity:1 !important;
        visibility:visible !important;
        background:#ffffff !important;
        backface-visibility:hidden !important;
        -webkit-backface-visibility:hidden !important;
        will-change:auto !important;
      }

      .modal-backdrop.show,
      .modal-backdrop.fade.show{
        opacity:.56 !important;
      }

    }


    /* ==========================================================
       FIX FINAL DESKTOP ZOOM 65% TANPA POTONG & TANPA RUSAK LIVEWIRE
       Catatan:
       - Jangan zoom BODY, karena membuat sidebar/area konten bergeser.
       - Zoom hanya app-shell, lalu lebar app-shell diperbesar agar hasil visual tetap full layar.
       - Modal tetap di dalam root Livewire dan dinormalkan ukurannya.
       - Mobile dipaksa normal 100%.
       ========================================================== */
    @media (min-width: 768px){
      html,
      body{
        width:100vw !important;
        height:100vh !important;
        min-width:100vw !important;
        min-height:100vh !important;
        max-width:100vw !important;
        max-height:100vh !important;
        margin:0 !important;
        padding:0 !important;
        overflow:hidden !important;
        zoom:1 !important;
        background:var(--bg) !important;
      }

      .app-shell{
        zoom:65% !important;
        width:153.846153846vw !important;
        min-width:153.846153846vw !important;
        max-width:153.846153846vw !important;
        height:153.846153846vh !important;
        min-height:153.846153846vh !important;
        max-height:153.846153846vh !important;
        transform:none !important;
        overflow:hidden !important;
      }

      .sidebar{
        height:153.846153846vh !important;
        min-height:153.846153846vh !important;
        max-height:153.846153846vh !important;
        flex:0 0 var(--sidebar-w) !important;
      }

      .sidebar.collapsed{
        flex:0 0 88px !important;
      }

      .main{
        height:153.846153846vh !important;
        min-height:153.846153846vh !important;
        max-height:153.846153846vh !important;
        width:auto !important;
        min-width:0 !important;
        flex:1 1 auto !important;
        overflow:hidden !important;
      }

      .main-scroll{
        height:calc(153.846153846vh - var(--header-h)) !important;
        min-height:0 !important;
        overflow-y:auto !important;
        overflow-x:auto !important;
        -webkit-overflow-scrolling:touch !important;
      }

      .page-wrap{
        width:100% !important;
        max-width:none !important;
        min-width:0 !important;
        box-sizing:border-box !important;
      }

      .content-wrap{
        padding-bottom:0 !important;
      }

      .table-responsive,
      .table-wrap,
      .data-table-wrap,
      .table-container,
      .card-soft,
      .page-wrap > div{
        max-width:100% !important;
      }

      .table-responsive,
      .table-wrap,
      .data-table-wrap,
      .table-container{
        overflow-x:auto !important;
        -webkit-overflow-scrolling:touch !important;
      }

      /* Modal dinormalkan karena parent .app-shell dizoom 65%. */
      .modal,
      .modal-backdrop{
        zoom:153.846153846% !important;
      }

      .modal{
        position:fixed !important;
        inset:0 !important;
        z-index:12000 !important;
        overflow-y:auto !important;
        background:transparent !important;
      }

      .modal-backdrop{
        position:fixed !important;
        inset:0 !important;
        width:100vw !important;
        height:100vh !important;
        z-index:11990 !important;
        background:#0f172a !important;
      }

      .modal-backdrop.show{
        opacity:.56 !important;
      }

      .modal-dialog{
        transform:none !important;
        opacity:1 !important;
      }

      .modal-content{
        background:#fff !important;
        opacity:1 !important;
        visibility:visible !important;
      }
    }

    @media (max-width: 767.98px){
      html,
      body,
      .app-shell{
        zoom:1 !important;
        max-width:none !important;
      }

      .modal,
      .modal-backdrop{
        zoom:1 !important;
      }
    }


    /* ==========================================================
       FIX DASHBOARD AREA KETIKA DESKTOP ZOOM 65%
       - Konten tidak terpotong.
       - Chart card tetap punya tinggi minimal agar render tidak blank.
       ========================================================== */
    @media (min-width: 768px){
      .main-scroll{
        overflow-y:auto !important;
        overflow-x:hidden !important;
      }

      .page-wrap{
        overflow:visible !important;
      }

      canvas,
      svg{
        max-width:100% !important;
      }

      .chart-container,
      .chart-card,
      [id*="chart"],
      [class*="chart"]{
        min-height:1px;
      }
    }

  </style>
</head>

<body>
@php
  use Illuminate\Support\Str;

  $role = strtoupper(trim((string) (auth()->user()->role ?? '')));

  $isAdmin            = $role === 'ADMIN';
  $isManajemen        = in_array($role, ['MANAJEMEN', 'MANAJEMEN KANWIL'], true);
  $isManajemenKanwil  = $role === 'MANAJEMEN KANWIL';
  $isSupervisor       = $role === 'SUPERVISOR';
  $isAo               = in_array($role, ['AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL'], true);
  $isPegawai          = $role === 'PEGAWAI';

  $canDashboard         = $isAdmin || $isManajemen || $isSupervisor;
  $canProspects         = $isAdmin || $isManajemen || $isSupervisor || $isAo || $isPegawai;
  $canProspectsDiajukan = $isAdmin || $isManajemen || $isSupervisor || $isAo;
  $canRekapProspek      = $isAdmin || $isManajemen || $isSupervisor;
  $canRecycleBin        = $isAdmin || $isManajemen || $isSupervisor || $isAo || $isPegawai;
  $canProfile           = auth()->check();
  $canAuditLog          = $isAdmin;
  $canMasterCabang      = $isAdmin;
  $canUsers             = $isAdmin;
  $canKontenApp         = $isAdmin;
  $canSimulasiKredit    = auth()->check();

  $displayName = auth()->user()->nama_lengkap ?: auth()->user()->name ?: 'User';
  $displayInitial = strtoupper(substr($displayName, 0, 1));

  $compactMobileRole = in_array($role, ['ADMIN', 'MANAJEMEN', 'MANAJEMEN KANWIL', 'SUPERVISOR'], true);

  $notificationRedirectUrl = $isAo ? url('/prospects-diajukan') : url('/prospects');

  $mobilePrimaryMenus = [];

  if ($compactMobileRole) {
      if ($canDashboard) {
          $mobilePrimaryMenus[] = [
              'label' => 'Dashboard',
              'url' => url('/dashboard'),
              'active' => request()->is('dashboard'),
              'icon' => 'dashboard',
              'type' => 'link',
          ];
      }

      if ($canProspects) {
          $mobilePrimaryMenus[] = [
              'label' => 'Prospek',
              'url' => url('/prospects'),
              'active' => request()->is('prospects*'),
              'icon' => 'prospects',
              'type' => 'link',
          ];
      }

      if ($canProspectsDiajukan) {
          $mobilePrimaryMenus[] = [
              'label' => 'Diajukan',
              'url' => url('/prospects-diajukan'),
              'active' => request()->is('prospects-diajukan'),
              'icon' => 'send',
              'type' => 'link',
          ];
      }

      $mobilePrimaryMenus[] = [
          'label' => 'Logout',
          'active' => false,
          'icon' => 'logout',
          'type' => 'logout',
      ];
  } else {
      if ($canDashboard) {
          $mobilePrimaryMenus[] = [
              'label' => 'Dashboard',
              'url' => url('/dashboard'),
              'active' => request()->is('dashboard'),
              'icon' => 'dashboard',
              'type' => 'link',
          ];
      }

      if ($canProspects) {
          $mobilePrimaryMenus[] = [
              'label' => 'Prospek',
              'url' => url('/prospects'),
              'active' => request()->is('prospects*'),
              'icon' => 'prospects',
              'type' => 'link',
          ];
      }

      if ($canProfile) {
          $mobilePrimaryMenus[] = [
              'label' => 'Profil',
              'url' => route('profile.index'),
              'active' => request()->is('profile'),
              'icon' => 'profile',
              'type' => 'link',
          ];
      }

      $mobilePrimaryMenus[] = [
          'label' => 'Logout',
          'active' => false,
          'icon' => 'logout',
          'type' => 'logout',
      ];
  }

  $mobileAllMenus = [];

  if ($canDashboard) {
      $mobileAllMenus[] = ['label' => 'Dashboard', 'url' => url('/dashboard'), 'active' => request()->is('dashboard'), 'icon' => 'dashboard'];
  }

  if ($canProspects) {
      $mobileAllMenus[] = ['label' => 'Prospek Saya', 'url' => url('/prospects'), 'active' => request()->is('prospects*'), 'icon' => 'prospects'];
  }

  if ($canRekapProspek) {
      $mobileAllMenus[] = ['label' => 'Rekap Prospek', 'url' => url('/rekap-prospek'), 'active' => request()->is('rekap-prospek'), 'icon' => 'table'];
  }

  if ($canProspectsDiajukan) {
      $mobileAllMenus[] = ['label' => 'Prospek Diajukan', 'url' => url('/prospects-diajukan'), 'active' => request()->is('prospects-diajukan'), 'icon' => 'send'];
  }

  if ($canSimulasiKredit) {
      $mobileAllMenus[] = ['label' => 'Simulasi Kredit', 'url' => route('simulasi-kredit.index'), 'active' => request()->is('simulasi-kredit'), 'icon' => 'calculator'];
  }

  if ($canAuditLog) {
      $mobileAllMenus[] = ['label' => 'Audit Log', 'url' => url('/audit-logs'), 'active' => request()->is('audit-logs'), 'icon' => 'audit'];
  }

  if ($canRecycleBin) {
      $mobileAllMenus[] = ['label' => 'Recycle Bin', 'url' => url('/recycle-bin/prospects'), 'active' => request()->is('recycle-bin/prospects'), 'icon' => 'trash'];
  }

  if ($canMasterCabang) {
      $mobileAllMenus[] = ['label' => 'Master Cabang', 'url' => url('/cabangs'), 'active' => request()->is('cabangs*'), 'icon' => 'building'];
  }

  if ($canUsers) {
      $mobileAllMenus[] = ['label' => 'User', 'url' => url('/users'), 'active' => request()->is('users*'), 'icon' => 'users'];
  }

  if ($canKontenApp) {
      $mobileAllMenus[] = ['label' => 'Konten App', 'url' => route('contents.manager'), 'active' => request()->routeIs('contents.manager'), 'icon' => 'image'];
  }

  if ($canProfile) {
      $mobileAllMenus[] = ['label' => 'Profil', 'url' => route('profile.index'), 'active' => request()->is('profile'), 'icon' => 'profile'];
  }

  $mobileIcon = function ($name) {
      switch ($name) {
          case 'dashboard':
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4.5 13.5a7.5 7.5 0 1 1 15 0"/><path d="M12 12l4-4"/><path d="M12 12l-2.5 3.5"/><path d="M7 17.5h10"/></svg></span>';
          case 'prospects':
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="4" y="4" width="6" height="6" rx="1.5"/><rect x="14" y="4" width="6" height="6" rx="1.5"/><rect x="4" y="14" width="6" height="6" rx="1.5"/><rect x="14" y="14" width="6" height="6" rx="1.5"/></svg></span>';
          case 'table':
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3.5" y="4.5" width="17" height="15" rx="2"/><path d="M3.5 9.5h17M8.5 4.5v15M15.5 4.5v15M3.5 14.5h17"/></svg></span>';
          case 'send':
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M21 3L10 14"/><path d="M21 3l-7 18-4-7-7-4 18-7z"/></svg></span>';
          case 'calculator':
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="6" y="3.5" width="12" height="17" rx="2"/><path d="M9 7.5h6"/><path d="M9 12h.01M12 12h.01M15 12h.01M9 15h.01M12 15h.01M15 15h.01M9 18h.01M12 18h3"/></svg></span>';
          case 'audit':
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M7 3.5h7l4 4v13H7z"/><path d="M14 3.5v4h4"/><path d="M9 12h6M9 16h6M9 8h3"/></svg></span>';
          case 'trash':
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4.5 7.5h15"/><path d="M9.5 3.5h5l1 2h-7l1-2z"/><path d="M7.5 7.5l1 12h7l1-12"/><path d="M10 10.5v6M14 10.5v6"/></svg></span>';
          case 'building':
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4.5 20.5h15"/><path d="M6.5 20.5v-13h11v13"/><path d="M9 10h.01M12 10h.01M15 10h.01M9 13h.01M12 13h.01M15 13h.01M11 20.5v-4h2v4"/></svg></span>';
          case 'users':
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M16.5 19a4.5 4.5 0 0 0-9 0"/><circle cx="12" cy="8" r="3"/><path d="M19.5 18a3.5 3.5 0 0 0-3-3.46"/><path d="M16.5 4.8a3 3 0 0 1 0 6"/></svg></span>';
          case 'image':
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3.5" y="5" width="17" height="14" rx="2"/><circle cx="9" cy="10" r="1.5"/><path d="M20.5 16l-5-5-4.5 4.5-2-2L3.5 19"/></svg></span>';
          case 'profile':
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.2"/><path d="M5.5 19a6.5 6.5 0 0 1 13 0"/></svg></span>';
          case 'logout':
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M14 7l5 5-5 5"/><path d="M19 12H9"/><path d="M11 4.5H6.5A2.5 2.5 0 0 0 4 7v10a2.5 2.5 0 0 0 2.5 2.5H11"/></svg></span>';
          default:
              return '<span class="mobile-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/></svg></span>';
      }
  };
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
              <i class="bi bi-grid"></i><span>Prospek Saya</span>
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

          @if($canSimulasiKredit)
            <a href="{{ route('simulasi-kredit.index') }}" class="navlink {{ request()->is('simulasi-kredit') ? 'active' : '' }}">
              <i class="bi bi-calculator"></i><span>Simulasi Kredit</span>
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

        @if($canMasterCabang || $canUsers || $canKontenApp)
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

            @if($canKontenApp)
              <a href="{{ route('contents.manager') }}" class="navlink {{ request()->routeIs('contents.manager') ? 'active' : '' }}">
                <i class="bi bi-images"></i><span>Konten App</span>
              </a>
            @endif
          </div>
        @endif
      </div>

      <div class="userbox">
        <div class="u">
          <div class="avatar">{{ $displayInitial }}</div>
          <div class="meta">
            <div class="n">{{ $displayName }}</div>
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
            <div class="notif-wrap" data-notif-redirect="{{ $notificationRedirectUrl }}">
              @livewire('notifications.bell', [], key('desktop-bell-' . auth()->id()))
            </div>

            <div class="profile-dropdown-wrap" id="desktopProfileWrap">
              <button class="profilebtn" type="button" id="desktopProfileBtn">
                <div class="pava">{{ $displayInitial }}</div>
                <div class="txt text-start">
                  <div class="n">{{ $displayName }}</div>
                  <div class="r">{{ strtoupper(auth()->user()->role ?? '-') }}</div>
                </div>
                <i class="bi bi-chevron-down ms-1"></i>
              </button>

              <div class="profile-menu" id="desktopProfileMenu">
                <div class="head">
                  <div class="fw-bold">{{ $displayName }}</div>
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

                @if($canSimulasiKredit)
                  <a class="item" href="{{ route('simulasi-kredit.index') }}">
                    <i class="bi bi-calculator me-2"></i> Simulasi Kredit
                  </a>
                @endif

                @if($canKontenApp)
                  <a class="item" href="{{ route('contents.manager') }}">
                    <i class="bi bi-images me-2"></i> Konten App
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
            <div class="notif-wrap" data-notif-redirect="{{ $notificationRedirectUrl }}">
              @livewire('notifications.bell', [], key('mobile-bell-' . auth()->id()))
            </div>

            <div class="profile-dropdown-wrap" id="mobileProfileWrap">
              <button class="profilebtn mobile-profile-btn" type="button" id="mobileProfileBtn">
                <div class="pava">{{ $displayInitial }}</div>
              </button>

              <div class="profile-menu" id="mobileProfileMenu">
                <div class="head">
                  <div class="fw-bold">{{ $displayName }}</div>
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
                    <i class="bi bi-grid me-2"></i> Prospek Saya
                  </a>
                @endif

                @if($canSimulasiKredit)
                  <a class="item" href="{{ route('simulasi-kredit.index') }}">
                    <i class="bi bi-calculator me-2"></i> Simulasi Kredit
                  </a>
                @endif

                @if($canKontenApp)
                  <a class="item" href="{{ route('contents.manager') }}">
                    <i class="bi bi-images me-2"></i> Konten App
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

  <nav class="bottom-nav d-md-none" id="mobileBottomNav">
    <div class="container nav-inner">
      <button type="button" class="mobile-nav-grabber" id="mobileNavGrabber" aria-label="Buka semua menu"></button>

      @foreach($mobilePrimaryMenus as $item)
        @if($item['type'] === 'logout')
          <form method="POST" action="{{ route('logout') }}" class="mobile-nav-form">
            @csrf
            <button type="submit">
              {!! $mobileIcon($item['icon']) !!}
              <span class="mobile-nav-label">{{ $item['label'] }}</span>
            </button>
          </form>
        @else
          <div class="mobile-nav-item">
            <a href="{{ $item['url'] }}" class="{{ $item['active'] ? 'active' : '' }}">
              {!! $mobileIcon($item['icon']) !!}
              <span class="mobile-nav-label">{{ $item['label'] }}</span>
            </a>
          </div>
        @endif
      @endforeach
    </div>
  </nav>

  <div class="mobile-menu-sheet-backdrop d-md-none" id="mobileMenuSheetBackdrop"></div>

  <div class="mobile-menu-sheet d-md-none" id="mobileMenuSheet">
    <div class="sheet-handle" id="mobileMenuSheetHandle"></div>

    <div class="sheet-head">
      <div>
        <div class="sheet-title">Semua Menu</div>
        <div class="sheet-sub">Swipe ke bawah untuk menutup</div>
      </div>
      <button type="button" class="sheet-close" id="mobileMenuSheetClose" aria-label="Tutup menu">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>

    <div class="sheet-body">
      <div class="mobile-menu-grid">
        @foreach($mobileAllMenus as $item)
          <a href="{{ $item['url'] }}" class="mobile-menu-link {{ $item['active'] ? 'active' : '' }}">
            {!! $mobileIcon($item['icon']) !!}
            <span>{{ $item['label'] }}</span>
          </a>
        @endforeach

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="mobile-menu-logout">
            {!! $mobileIcon('logout') !!}
            <span>Logout</span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade session-expired-modal" id="sessionExpiredModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0">
          <div class="session-expired-head">
            <div class="session-expired-icon">
              <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div>
              <div class="session-expired-title">Sesi Halaman Sudah Kedaluwarsa</div>
              <div class="session-expired-sub">Halaman ini sudah terlalu lama terbuka. Untuk melanjutkan, silakan masuk ulang.</div>
            </div>
          </div>
        </div>

        <div class="modal-body pt-0">
          <div class="session-expired-card">
            <div class="session-expired-card-title">Kenapa ini muncul?</div>
            <p class="session-expired-card-desc mb-0">
              Biasanya karena sesi login atau token keamanan Livewire sudah habis. Supaya aman, sistem akan mengarahkan ke halaman login.
            </p>
          </div>
        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
            Tutup
          </button>
          <button type="button" class="btn btn-primary rounded-pill px-4" id="btnRefreshExpiredPage">
            <i class="bi bi-box-arrow-in-right me-1"></i> Ke Halaman Login
          </button>
        </div>
      </div>
    </div>
  </div>

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

    (function(){
      var backdrop = document.getElementById('mobileMenuSheetBackdrop');
      var sheet = document.getElementById('mobileMenuSheet');
      var grabber = document.getElementById('mobileNavGrabber');
      var handle = document.getElementById('mobileMenuSheetHandle');
      var closeBtn = document.getElementById('mobileMenuSheetClose');

      if(!backdrop || !sheet || !grabber || !handle) return;

      var startY = 0;
      var currentY = 0;
      var dragging = false;
      var opened = false;

      function lockBody(){
        document.body.classList.add('mobile-sheet-open');
        document.body.style.overflow = 'hidden';
      }

      function unlockBody(){
        document.body.classList.remove('mobile-sheet-open');
        document.body.style.overflow = '';
      }

      function openSheet(){
        opened = true;
        backdrop.classList.add('show');
        sheet.classList.add('show');
        sheet.style.transform = 'translateY(0)';
        lockBody();
      }

      function closeSheet(){
        opened = false;
        backdrop.classList.remove('show');
        sheet.classList.remove('show');
        sheet.style.transform = '';
        unlockBody();
      }

      function startDrag(clientY){
        dragging = true;
        startY = clientY;
        currentY = clientY;
        sheet.style.transition = 'none';
      }

      function moveDrag(clientY){
        if(!dragging) return;

        currentY = clientY;
        var diff = currentY - startY;

        if(opened){
          if(diff > 0){
            sheet.style.transform = 'translateY(' + diff + 'px)';
          } else {
            sheet.style.transform = 'translateY(0)';
          }
        }
      }

      function endDrag(){
        if(!dragging) return;

        dragging = false;
        sheet.style.transition = '';

        var diff = currentY - startY;

        if(opened && diff > 85){
          closeSheet();
        } else {
          openSheet();
        }
      }

      grabber.addEventListener('click', function(e){
        e.preventDefault();
        openSheet();
      });

      handle.addEventListener('click', function(e){
        e.preventDefault();
        closeSheet();
      });

      backdrop.addEventListener('click', closeSheet);

      if(closeBtn){
        closeBtn.addEventListener('click', function(e){
          e.preventDefault();
          closeSheet();
        });
      }

      handle.addEventListener('touchstart', function(e){
        startDrag(e.touches[0].clientY);
      }, {passive:true});

      handle.addEventListener('touchmove', function(e){
        moveDrag(e.touches[0].clientY);
      }, {passive:true});

      handle.addEventListener('touchend', endDrag);

      sheet.addEventListener('touchstart', function(e){
        if(!opened) return;
        if(e.target.closest('.sheet-body')) return;
        startDrag(e.touches[0].clientY);
      }, {passive:true});

      sheet.addEventListener('touchmove', function(e){
        if(!dragging) return;
        if(e.target.closest('.sheet-body')) return;
        moveDrag(e.touches[0].clientY);
      }, {passive:true});

      sheet.addEventListener('touchend', function(){
        if(!dragging) return;
        endDrag();
      });

      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape' && opened){
          closeSheet();
        }
      });

      closeSheet();
    })();

    (function(){
      var notificationRedirectUrl = @json($notificationRedirectUrl);

      function normalizeText(text){
        return String(text || '').replace(/\s+/g, ' ').trim().toLowerCase();
      }

      function isLihatButton(el){
        if(!el) return false;

        var text = normalizeText(el.innerText || el.textContent || '');
        var aria = normalizeText(el.getAttribute('aria-label') || '');
        var title = normalizeText(el.getAttribute('title') || '');

        return text === 'lihat' || text.indexOf('lihat') !== -1 || aria.indexOf('lihat') !== -1 || title.indexOf('lihat') !== -1;
      }

      document.addEventListener('click', function(e){
        var target = e.target.closest('.notif-wrap a, .notif-wrap button');

        if(!target) return;

        if(isLihatButton(target)){
          e.preventDefault();
          e.stopPropagation();

          var wrap = target.closest('.notif-wrap');
          var url = notificationRedirectUrl;

          if(wrap && wrap.getAttribute('data-notif-redirect')){
            url = wrap.getAttribute('data-notif-redirect');
          }

          window.location.href = url;
        }
      }, true);
    })();

    (function(){
      function isMobile(){
        return window.matchMedia && window.matchMedia('(max-width: 767.98px)').matches;
      }

      function getBottomNavHeight(){
        var nav = document.getElementById('mobileBottomNav');
        if(!nav) return 64;
        return Math.ceil(nav.getBoundingClientRect().height || 64);
      }

      function enableMobileScroll(){
        if(!isMobile()) return;

        var mainScroll = document.querySelector('.main-scroll');
        var bottomNav = document.getElementById('mobileBottomNav');

        if(mainScroll){
          mainScroll.style.setProperty('overflow-y', 'auto', 'important');
          mainScroll.style.setProperty('overflow-x', 'hidden', 'important');
          mainScroll.style.setProperty('-webkit-overflow-scrolling', 'touch', 'important');
          mainScroll.style.setProperty('touch-action', 'pan-y', 'important');
        }

        if(bottomNav){
          var navHeight = getBottomNavHeight();
          document.documentElement.style.setProperty('--mobile-bottom-nav-h', navHeight + 'px');
        }
      }

      function looksLikeFloating(el){
        if(!el || el === document.body || el === document.documentElement) return false;

        var cs = window.getComputedStyle(el);
        var pos = cs.position;
        if(pos !== 'fixed' && pos !== 'absolute') return false;

        var rect = el.getBoundingClientRect();
        if(rect.width < 38 || rect.height < 38) return false;

        var text = (el.innerText || el.textContent || '').trim().toLowerCase();
        var cls = String(el.className || '').toLowerCase();
        var id = String(el.id || '').toLowerCase();
        var aria = String(el.getAttribute('aria-label') || '').toLowerCase();
        var title = String(el.getAttribute('title') || '').toLowerCase();

        var key = cls + ' ' + id + ' ' + aria + ' ' + title + ' ' + text;

        var isCandidate =
          key.indexOf('ai') !== -1 ||
          key.indexOf('assistant') !== -1 ||
          key.indexOf('chat') !== -1 ||
          key.indexOf('tambah') !== -1 ||
          key.indexOf('prospek') !== -1 ||
          key.indexOf('add') !== -1 ||
          key.indexOf('plus') !== -1 ||
          key === '+' ||
          text === '+';

        if(!isCandidate) return false;

        var nearBottom = rect.bottom > (window.innerHeight - 240);
        var onRight = rect.left > (window.innerWidth * .45);

        return nearBottom && onRight;
      }

      function fixFloatingButtons(){
        if(!isMobile()) return;

        var navH = getBottomNavHeight();
        var candidates = document.querySelectorAll('a, button, div[role="button"], .fab, .floating-action, .floating-btn, .btn-floating, [class*="floating"], [class*="fab"], [class*="ai"], [class*="AI"]');

        var found = [];

        candidates.forEach(function(el){
          if(looksLikeFloating(el)){
            found.push(el);
          }
        });

        found.sort(function(a,b){
          return b.getBoundingClientRect().bottom - a.getBoundingClientRect().bottom;
        });

        found.forEach(function(el, index){
          var extra = index === 0 ? 18 : 84;
          el.style.setProperty('bottom', (navH + extra) + 'px', 'important');
          el.style.setProperty('z-index', '2999', 'important');
        });
      }

      function refreshMobileLayout(){
        enableMobileScroll();
        fixFloatingButtons();
      }

      window.addEventListener('load', refreshMobileLayout);
      window.addEventListener('resize', refreshMobileLayout);
      window.addEventListener('orientationchange', function(){
        setTimeout(refreshMobileLayout, 250);
      });

      document.addEventListener('livewire:init', function(){
        setTimeout(refreshMobileLayout, 150);
      });

      document.addEventListener('livewire:navigated', function(){
        setTimeout(refreshMobileLayout, 150);
      });

      setTimeout(refreshMobileLayout, 300);
      setTimeout(refreshMobileLayout, 900);
    })();

    (function(){
      var expiredModalEl = document.getElementById('sessionExpiredModal');
      var refreshBtn = document.getElementById('btnRefreshExpiredPage');
      var expiredModal = null;
      var originalConfirm = window.confirm;
      var isShowingExpiredModal = false;

      function getExpiredModal(){
        if (!expiredModalEl || typeof bootstrap === 'undefined') return null;

        if (!expiredModal) {
          expiredModal = bootstrap.Modal.getOrCreateInstance(expiredModalEl, {
            backdrop: 'static',
            keyboard: false
          });
        }

        return expiredModal;
      }

      function getLoginUrl(){
        return @json(route('login'));
      }

      function goToSafeLogin(){
        window.location.href = getLoginUrl();
      }

      function showExpiredModal(){
        if (isShowingExpiredModal) return;

        isShowingExpiredModal = true;

        var modal = getExpiredModal();

        if (modal) {
          modal.show();
        } else {
          goToSafeLogin();
        }
      }

      if (expiredModalEl) {
        expiredModalEl.addEventListener('hidden.bs.modal', function(){
          isShowingExpiredModal = false;
        });
      }

      if (refreshBtn) {
        refreshBtn.addEventListener('click', function(){
          goToSafeLogin();
        });
      }

      window.confirm = function(message){
        var msg = String(message || '').toLowerCase();

        if (
          msg.indexOf('this page has expired') !== -1 ||
          msg.indexOf('would you like to refresh the page') !== -1 ||
          msg.indexOf('page has expired') !== -1
        ) {
          showExpiredModal();
          return false;
        }

        return originalConfirm.apply(window, arguments);
      };

      if (window.fetch) {
        var originalFetch = window.fetch;

        window.fetch = function(){
          return originalFetch.apply(window, arguments).then(function(response){
            if (response && [401, 403, 419].indexOf(response.status) !== -1) {
              showExpiredModal();
            }

            return response;
          }).catch(function(error){
            throw error;
          });
        };
      }

      if (window.XMLHttpRequest) {
        var OriginalXHR = window.XMLHttpRequest;

        function CustomXHR(){
          var xhr = new OriginalXHR();

          xhr.addEventListener('load', function(){
            if ([401, 403, 419].indexOf(xhr.status) !== -1) {
              showExpiredModal();
            }
          });

          return xhr;
        }

        CustomXHR.UNSENT = OriginalXHR.UNSENT;
        CustomXHR.OPENED = OriginalXHR.OPENED;
        CustomXHR.HEADERS_RECEIVED = OriginalXHR.HEADERS_RECEIVED;
        CustomXHR.LOADING = OriginalXHR.LOADING;
        CustomXHR.DONE = OriginalXHR.DONE;
        CustomXHR.prototype = OriginalXHR.prototype;

        window.XMLHttpRequest = CustomXHR;
      }

      window.addEventListener('session-expired', function(){
        showExpiredModal();
      });

      document.addEventListener('livewire:init', function () {
        try {
          Livewire.hook('request', ({ fail }) => {
            fail(({ status }) => {
              if ([401, 403, 419].indexOf(status) !== -1) {
                showExpiredModal();
              }
            });
          });
        } catch (e) {}
      });
    })();

  </script>













  <script>
    /* ==========================================================
       FIX MODAL STABIL UNTUK LIVEWIRE
       - Tidak memindahkan modal dari root Livewire.
       - Tidak membuat input No Rekening via JS.
       - Tidak mencegat tombol Simpan Status.
       ========================================================== */
    (function(){
      function fixBackdrop(){
        document.querySelectorAll('.modal-backdrop').forEach(function(backdrop){
          backdrop.style.setProperty('position','fixed','important');
          backdrop.style.setProperty('inset','0','important');
          backdrop.style.setProperty('z-index','11990','important');
          backdrop.style.setProperty('background','#0f172a','important');
          backdrop.style.setProperty('opacity','.56','important');
        });
      }

      function fixOpenModal(modal){
        if(!modal) return;
        modal.style.setProperty('position','fixed','important');
        modal.style.setProperty('inset','0','important');
        modal.style.setProperty('z-index','12000','important');
        modal.style.setProperty('overflow-y','auto','important');
        modal.style.setProperty('background','transparent','important');

        var dialog = modal.querySelector('.modal-dialog');
        if(dialog){
          dialog.style.setProperty('z-index','12001','important');
          dialog.style.setProperty('transform','none','important');
          dialog.style.setProperty('opacity','1','important');
        }

        var content = modal.querySelector('.modal-content');
        if(content){
          content.style.setProperty('background','#ffffff','important');
          content.style.setProperty('opacity','1','important');
          content.style.setProperty('visibility','visible','important');
        }
      }

      document.addEventListener('show.bs.modal', function(e){
        fixOpenModal(e.target);
        setTimeout(fixBackdrop, 0);
      }, true);

      document.addEventListener('shown.bs.modal', function(e){
        fixOpenModal(e.target);
        fixBackdrop();
      }, true);

      document.addEventListener('hidden.bs.modal', function(){
        setTimeout(function(){
          if(!document.querySelector('.modal.show')){
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
          }
        }, 80);
      }, true);
    })();
  </script>




  <script>
    /* ==========================================================
       FIX POPUP DETAIL KOSONG SETELAH SIMPAN STATUS/PENGAMBILAN
       Penyebab: setelah Livewire update, property detail kadang di-reset
       sementara modal Bootstrap masih terbuka, sehingga muncul:
       "ID Prospek: -" / "Data detail tidak ditemukan".
       Solusi: setelah tombol simpan diklik, modal detail ditutup bersih.
       Jika modal kosong tetap muncul, otomatis ditutup.
       ========================================================== */
    (function(){
      var closeAfterSaveTimer = null;

      function textOf(el){
        return String((el && (el.innerText || el.textContent)) || '')
          .replace(/\s+/g, ' ')
          .trim();
      }

      function isDetailProspekModal(modal){
        if(!modal) return false;
        var txt = textOf(modal).toLowerCase();
        return txt.indexOf('detail prospek diajukan') !== -1 ||
               txt.indexOf('detail pengajuan pegawai') !== -1 ||
               txt.indexOf('detail prospek') !== -1;
      }

      function isEmptyDetailModal(modal){
        if(!isDetailProspekModal(modal)) return false;
        var txt = textOf(modal).toLowerCase();
        return txt.indexOf('data detail tidak ditemukan') !== -1 ||
               txt.indexOf('id prospek: -') !== -1 ||
               txt.indexOf('id prospek:-') !== -1;
      }

      function getBootstrapModal(modal){
        if(!modal || typeof bootstrap === 'undefined' || !bootstrap.Modal) return null;
        return bootstrap.Modal.getInstance(modal) || bootstrap.Modal.getOrCreateInstance(modal);
      }

      function hardHideModal(modal){
        if(!modal) return;

        try{
          var instance = getBootstrapModal(modal);
          if(instance){
            instance.hide();
          }
        }catch(e){}

        modal.classList.remove('show');
        modal.style.setProperty('display', 'none', 'important');
        modal.setAttribute('aria-hidden', 'true');
        modal.removeAttribute('aria-modal');
        modal.removeAttribute('role');

        document.querySelectorAll('.modal-backdrop').forEach(function(backdrop){
          backdrop.parentNode && backdrop.parentNode.removeChild(backdrop);
        });

        if(!document.querySelector('.modal.show')){
          document.body.classList.remove('modal-open');
          document.body.style.removeProperty('overflow');
          document.body.style.removeProperty('padding-right');
        }
      }

      function closeDetailModals(){
        document.querySelectorAll('.modal.show, .modal').forEach(function(modal){
          if(isDetailProspekModal(modal)){
            hardHideModal(modal);
          }
        });
      }

      function closeEmptyDetailModals(){
        document.querySelectorAll('.modal.show, .modal').forEach(function(modal){
          if(isEmptyDetailModal(modal)){
            hardHideModal(modal);
          }
        });
      }

      function scheduleCloseAfterSave(){
        clearTimeout(closeAfterSaveTimer);

        [80, 250, 600, 1200].forEach(function(ms){
          setTimeout(function(){
            closeDetailModals();
            closeEmptyDetailModals();
          }, ms);
        });

        closeAfterSaveTimer = setTimeout(function(){
          closeEmptyDetailModals();
        }, 1800);
      }

      document.addEventListener('click', function(e){
        var btn = e.target.closest('button, a');
        if(!btn) return;

        var txt = textOf(btn).toLowerCase();
        var attr = String(
          (btn.getAttribute('wire:click') || '') + ' ' +
          (btn.getAttribute('wire:submit') || '') + ' ' +
          (btn.getAttribute('onclick') || '') + ' ' +
          (btn.getAttribute('id') || '') + ' ' +
          (btn.className || '')
        ).toLowerCase();

        var isSaveButton =
          txt.indexOf('simpan status') !== -1 ||
          txt.indexOf('simpan pengambilan') !== -1 ||
          attr.indexOf('updatestatus') !== -1 ||
          attr.indexOf('updatepengambilan') !== -1 ||
          attr.indexOf('simpanstatus') !== -1;

        if(isSaveButton){
          scheduleCloseAfterSave();
        }
      }, true);

      document.addEventListener('shown.bs.modal', function(e){
        setTimeout(function(){
          if(isEmptyDetailModal(e.target)){
            hardHideModal(e.target);
          }
        }, 50);
      }, true);

      document.addEventListener('livewire:init', function(){
        try{
          Livewire.hook('commit', function(){
            setTimeout(closeEmptyDetailModals, 120);
            setTimeout(closeEmptyDetailModals, 500);
          });
        }catch(e){}
      });

      document.addEventListener('livewire:navigated', function(){
        setTimeout(closeEmptyDetailModals, 150);
      });

      var observer = new MutationObserver(function(){
        closeEmptyDetailModals();
      });

      window.addEventListener('load', function(){
        observer.observe(document.body, {
          childList:true,
          subtree:true,
          characterData:true
        });
        setTimeout(closeEmptyDetailModals, 300);
      });
    })();
  </script>


  <script>
    /* ==========================================================
       DASHBOARD SMOOTH REFRESH ONLY
       Catatan penting:
       - Jangan paksa component.$wire.set() di sini karena wire:model.live
         sudah mengirim request sendiri. Double set itulah yang membuat filter
         terlihat glitch/flicker.
       - Script ini hanya memberi sinyal resize/render ulang ke chart setelah
         Livewire selesai update.
       ========================================================== */
    (function(){
      function isDashboardPage(){
        var path = String(window.location.pathname || '').toLowerCase();
        return path === '/dashboard' || path.indexOf('/dashboard') !== -1;
      }

      var t1 = null;
      function scheduleSmoothDashboardRefresh(){
        if(!isDashboardPage()) return;

        clearTimeout(t1);
        t1 = setTimeout(function(){
          try{ window.dispatchEvent(new Event('resize')); }catch(e){}
          try{ document.dispatchEvent(new CustomEvent('dashboard:smooth-refresh')); }catch(e){}
        }, 120);
      }

      document.addEventListener('livewire:init', function(){
        try{
          Livewire.hook('commit', function(payload){
            if(payload && typeof payload.succeed === 'function'){
              payload.succeed(function(){
                setTimeout(scheduleSmoothDashboardRefresh, 60);
                setTimeout(scheduleSmoothDashboardRefresh, 220);
              });
            }else{
              setTimeout(scheduleSmoothDashboardRefresh, 150);
            }
          });
        }catch(e){}
      });

      document.addEventListener('livewire:navigated', function(){
        setTimeout(scheduleSmoothDashboardRefresh, 120);
      });

      window.addEventListener('load', function(){
        setTimeout(scheduleSmoothDashboardRefresh, 250);
      });
    })();
  </script>

  @stack('scripts')
</body>
</html>
