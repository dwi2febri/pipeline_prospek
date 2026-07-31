<div class="container-fluid px-0">

  <style>
    .dash-title{
      font-size:2.2rem;
      font-weight:900;
      letter-spacing:-.03em;
      color:#1e293b;
      line-height:1.1;
    }

    .dash-subtitle{
      color:#64748b;
      font-size:1rem;
    }

    .eprospek-mobile-hero,
    .mobile-dashboard-filter-toggle{
      display:none;
    }

    .dash-top-actions{
      display:flex;
      align-items:center;
      gap:10px;
      flex-wrap:wrap;
    }

    .desktop-ai-btn{
      display:inline-flex;
      align-items:center;
      gap:8px;
      border-radius:999px;
      padding:12px 18px;
      font-weight:800;
      text-decoration:none;
      color:#fff;
      background:linear-gradient(135deg,#7c3aed 0%,#2563eb 100%);
      box-shadow:0 16px 28px rgba(37,99,235,.24);
      border:0;
      white-space:nowrap;
      transition:all .18s ease;
    }

    .desktop-ai-btn:hover{
      color:#fff;
      transform:translateY(-1px);
    }

    .mobile-inline-actions{
      display:none;
    }

    .mobile-inline-ai{
      display:inline-flex;
      align-items:center;
      gap:8px;
      justify-content:center;
    }

    .mobile-fab-stack{
      position:fixed;
      right:max(8px,calc(50% - 245px));
      bottom:calc(10px + env(safe-area-inset-bottom));
      z-index:10030;
      display:flex;
      flex-direction:column;
      align-items:center;
      gap:10px;
      width:72px;
    }

    .mobile-fab-stack .mobile-fab-ai,
    .mobile-fab-stack .mobile-fab-dashboard-add{
      position:static !important;
      flex:0 0 auto;
      border-radius:999px;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#171b22;
      text-decoration:none;
      overflow:hidden;
      isolation:isolate;
      border:1px solid rgba(255,255,255,.55);
      box-shadow:
        0 10px 24px rgba(31,45,70,.1),
        inset 0 1px 0 rgba(255,255,255,.66),
        inset 0 -1px 0 rgba(255,255,255,.12);
      backdrop-filter:blur(12px) saturate(108%);
      -webkit-backdrop-filter:blur(12px) saturate(108%);
      transition:transform .18s ease,box-shadow .18s ease;
    }

    .mobile-fab-stack .mobile-fab-ai{
      width:72px;
      height:72px;
      background:rgba(255,255,255,.08);
    }

    .mobile-fab-stack .mobile-fab-dashboard-add{
      width:72px;
      height:72px;
      background:rgba(255,255,255,.08);
    }

    .mobile-fab-stack .mobile-fab-ai:active,
    .mobile-fab-stack .mobile-fab-dashboard-add:active{
      transform:scale(.94);
    }

    .mobile-fab-stack .mobile-fab-ai i,
    .mobile-fab-stack .mobile-fab-dashboard-add i{
      position:relative;
      z-index:1;
      filter:drop-shadow(0 1px 2px rgba(31,45,70,.14));
    }

    .mobile-fab-stack .mobile-fab-ai i{
      font-size:1.35rem;
    }

    .mobile-fab-stack .mobile-fab-dashboard-add i{
      font-size:1.7rem;
    }

    .dash-filter-card{
      border:1px solid #e9eef5;
      border-radius:26px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
      box-shadow:0 14px 34px rgba(15,23,42,.06);
    }

    .dash-stat-card{
      position:relative;
      overflow:hidden;
      border:0;
      border-radius:26px;
      padding:22px 22px 18px 22px;
      color:#fff;
      min-height:140px;
      box-shadow:0 18px 38px rgba(15,23,42,.12);
    }

    .dash-stat-card .label{
      font-size:.98rem;
      font-weight:700;
      opacity:.95;
      margin-bottom:8px;
    }

    .dash-stat-card .value{
      font-size:2.5rem;
      font-weight:900;
      line-height:1;
      letter-spacing:-.03em;
    }

    .dash-stat-card .icon{
      position:absolute;
      right:16px;
      bottom:10px;
      font-size:54px;
      opacity:.18;
    }

    .bg-total{ background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%); }
    .bg-open{ background:linear-gradient(135deg,#ffcfcf 0%,#fc9d9d 100%); color:#7f1d1d; }
    .bg-follow{ background:linear-gradient(135deg,#10b981 0%,#059669 100%); }
    .bg-rejected{ background:linear-gradient(135deg,#fb7185 0%,#e11d48 100%); }
    .bg-closing{ background:linear-gradient(135deg,#60a5fa 0%,#2563eb 100%); }

    .dash-panel{
      border:1px solid #e9eef5;
      border-radius:26px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
      box-shadow:0 14px 34px rgba(15,23,42,.06);
      overflow:hidden;
    }

    .dash-panel .panel-head{
      padding:18px 20px 0 20px;
    }

    .dash-panel .panel-title{
      font-size:1.12rem;
      font-weight:800;
      color:#1f2937;
      margin-bottom:4px;
    }

    .dash-panel .panel-sub{
      font-size:.88rem;
      color:#64748b;
    }

    .dash-panel .panel-body{
      padding:16px 20px 20px 20px;
    }

    .summary-note{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:10px 14px;
      border-radius:999px;
      background:#f8fafc;
      border:1px solid #e5e7eb;
      color:#64748b;
      font-size:.9rem;
      font-weight:600;
    }

    .modern-table thead th{
      border-bottom:1px solid #e9eef5 !important;
      background:#f8fafc !important;
      color:#334155;
      font-size:.9rem;
      font-weight:800;
      white-space:nowrap;
      vertical-align:middle;
    }

    .modern-table tbody td{
      border-color:#eef2f7 !important;
      vertical-align:middle;
    }

    .modern-table tbody tr:hover{
      background:#fbfdff;
    }

    .rank-badge{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      width:28px;
      height:28px;
      border-radius:999px;
      background:#e0ecff;
      color:#1d4ed8;
      font-size:.82rem;
      font-weight:800;
    }

    .status-chip{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:6px 12px;
      border-radius:999px;
      font-size:.75rem;
      font-weight:800;
      letter-spacing:.02em;
      white-space:nowrap;
    }

    .status-open{
      background:linear-gradient(180deg,#f8fafc 0%,#e2e8f0 100%);
      color:#475569;
      border:1px solid #cbd5e1;
    }

    .status-follow{
      background:linear-gradient(135deg,#fde68a 0%,#fbbf24 100%);
      color:#4b3a00;
    }

    .status-rejected{
      background:linear-gradient(135deg,#fda4af 0%,#f43f5e 100%);
      color:#fff;
    }

    .status-closing{
      background:linear-gradient(135deg,#86efac 0%,#22c55e 100%);
      color:#14532d;
    }

    .produk-chip{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:6px 12px;
      border-radius:999px;
      font-size:.75rem;
      font-weight:800;
      letter-spacing:.02em;
      white-space:nowrap;
      color:#fff;
    }

    .produk-kredit{ background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%); }
    .produk-tabungan{ background:linear-gradient(135deg,#22c55e 0%,#15803d 100%); }
    .produk-deposito{ background:linear-gradient(135deg,#facc15 0%,#eab308 100%); color:#4b3a00; }
    .produk-aset{ background:linear-gradient(135deg,#374151 0%,#111827 100%); }

    .dashboard-map{
      height:460px;
      border-radius:22px;
      overflow:hidden;
      border:1px solid #e5e7eb;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.35);
    }

    .map-panel{
      background:linear-gradient(180deg,#f8fafc 0%,#f1f5f9 100%);
      border-radius:24px;
      padding:12px;
      border:1px solid #e5e7eb;
    }

    .legend-wrap{
      background:#f8fafc;
      border:1px solid #e5e7eb;
      border-radius:20px;
      padding:14px 16px;
    }

    .legend-chip{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 12px;
      border-radius:999px;
      background:#fff;
      border:1px solid #e5e7eb;
      font-size:.9rem;
      font-weight:600;
      box-shadow:0 1px 2px rgba(15,23,42,.04);
    }

    .legend-dot{
      width:12px;
      height:12px;
      border-radius:999px;
      display:inline-block;
      flex:0 0 auto;
    }

    .dashboard-prospect-popup .leaflet-popup-content-wrapper{
      overflow:hidden;
      border:1px solid rgba(203,213,225,.85);
      border-radius:18px;
      background:rgba(255,255,255,.98);
      box-shadow:0 18px 44px rgba(15,23,42,.2);
      backdrop-filter:blur(12px);
    }

    .dashboard-prospect-popup .leaflet-popup-content{
      margin:0;
      color:#334155;
      line-height:1.35;
    }

    .dashboard-prospect-popup .leaflet-popup-content.leaflet-popup-scrolled{
      overflow-x:hidden;
      overflow-y:auto;
      overscroll-behavior:contain;
      border:0;
      -webkit-overflow-scrolling:touch;
    }

    .dashboard-prospect-popup .leaflet-popup-tip{
      background:#fff;
      box-shadow:3px 3px 8px rgba(15,23,42,.08);
    }

    .dashboard-prospect-popup .leaflet-popup-close-button{
      top:8px;
      right:8px;
      z-index:3;
      width:28px;
      height:28px;
      display:grid;
      place-items:center;
      padding:0;
      border:1px solid #dbe4f0;
      border-radius:10px;
      color:#64748b;
      background:rgba(255,255,255,.9);
      font-size:19px;
      line-height:1;
    }

    .map-popup-card{
      width:100%;
      min-width:0;
    }

    .map-popup-head{
      padding:13px 44px 11px 14px;
      border-bottom:1px solid #e7eef8;
      background:
        radial-gradient(circle at top right,rgba(59,130,246,.15),transparent 46%),
        linear-gradient(145deg,#f8fbff,#eef5ff);
    }

    .map-popup-title{
      overflow:hidden;
      color:#0f172a;
      font-size:14px;
      font-weight:900;
      line-height:1.2;
      text-overflow:ellipsis;
      white-space:nowrap;
    }

    .map-popup-subtitle{
      display:flex;
      align-items:center;
      gap:6px;
      margin-top:5px;
      overflow:hidden;
      color:#64748b;
      font-size:11px;
      font-weight:700;
      text-overflow:ellipsis;
      white-space:nowrap;
    }

    .map-popup-subtitle i{
      flex:0 0 auto;
      color:#3b82f6;
    }

    .map-popup-body{
      padding:10px 14px 13px;
    }

    .map-popup-badges{
      display:flex;
      flex-wrap:wrap;
      gap:5px;
      margin-bottom:8px;
    }

    .map-popup-badge{
      display:inline-flex;
      align-items:center;
      padding:4px 8px;
      border:1px solid #cfe0fa;
      border-radius:999px;
      color:#2456a6;
      background:#edf5ff;
      font-size:10px;
      font-weight:850;
      line-height:1;
    }

    .map-popup-row{
      display:grid;
      grid-template-columns:17px minmax(0,1fr);
      gap:7px;
      margin-bottom:6px;
      color:#475569;
      font-size:11.5px;
      line-height:1.32;
      overflow-wrap:anywhere;
    }

    .map-popup-row:last-child{
      margin-bottom:0;
    }

    .map-popup-row > i{
      margin-top:1px;
      color:#3b82f6;
      text-align:center;
    }

    .map-popup-row strong{
      color:#1e293b;
      font-weight:850;
    }

    .map-popup-photo{
      width:100%;
      height:96px;
      display:block;
      object-fit:cover;
      border:1px solid #dbe4f0;
      border-radius:12px;
    }

    .map-popup-photo-link{
      display:block;
      width:100%;
      margin-top:9px;
      cursor:zoom-in;
    }

    .map-popup-photo-empty{
      display:flex;
      align-items:center;
      justify-content:center;
      gap:6px;
      margin-top:8px;
      padding:6px 9px;
      border:1px dashed #cbd5e1;
      border-radius:10px;
      color:#64748b;
      background:#f8fafc;
      font-size:10px;
      font-weight:700;
      text-align:center;
    }

    @media (max-width:767.98px){
      .dashboard-prospect-popup{
        max-width:calc(100vw - 56px) !important;
      }

      .dashboard-prospect-popup .leaflet-popup-content-wrapper{
        border-radius:15px;
        box-shadow:0 14px 32px rgba(15,23,42,.22);
      }

      .dashboard-prospect-popup .leaflet-popup-content{
        max-width:calc(100vw - 72px) !important;
        max-height:138px !important;
        overflow-x:hidden;
        overflow-y:auto;
        overscroll-behavior:contain;
        -webkit-overflow-scrolling:touch;
      }

      .dashboard-prospect-popup .leaflet-popup-close-button{
        top:6px;
        right:6px;
        width:27px;
        height:27px;
        border-radius:9px;
      }

      .map-popup-head{
        padding:10px 39px 8px 11px;
      }

      .map-popup-title{
        font-size:12px;
      }

      .map-popup-subtitle{
        margin-top:3px;
        font-size:9.5px;
      }

      .map-popup-body{
        padding:8px 11px 10px;
      }

      .map-popup-badges{
        margin-bottom:6px;
      }

      .map-popup-badge{
        padding:3px 7px;
        font-size:8.5px;
      }

      .map-popup-row{
        grid-template-columns:15px minmax(0,1fr);
        gap:5px;
        margin-bottom:4px;
        font-size:9.5px;
        line-height:1.25;
      }

      .map-popup-photo{
        height:72px;
        border-radius:9px;
      }

      .map-popup-photo-link{
        margin-top:7px;
      }

      .map-popup-photo-empty{
        margin-top:6px;
        padding:5px 7px;
        font-size:8.5px;
      }
    }



    /* Smooth chart area saat filter Livewire berubah */
    .dashboard-chart-box{
      position:relative;
      width:100%;
      height:320px !important;
      min-height:320px !important;
      max-height:320px !important;
      overflow:hidden;
      transition:none !important;
    }

    .dashboard-chart-box canvas{
      display:block !important;
      width:100% !important;
      height:100% !important;
      max-width:100% !important;
      max-height:100% !important;
    }

    .dashboard-chart-box.is-updating{
      opacity:1 !important;
      filter:none !important;
    }

    @media (max-width: 767.98px){
      .eprospek-mobile-hero{
        position:relative;
        top:auto;
        z-index:20;
        display:block;
        height:244px;
        min-height:244px;
        margin:0 -10px 12px;
        padding:11px 14px 24px;
        overflow:hidden;
        isolation:isolate;
        border-radius:0 0 32px 32px;
        color:#fff;
        background:linear-gradient(142deg,#7196e5 0%,#5d78d6 55%,#4b61c4 100%) !important;
        box-shadow:0 17px 36px rgba(53,70,157,.22);
        overflow-anchor:none;
        transition:
          height .46s cubic-bezier(.2,.82,.24,1),
          min-height .46s cubic-bezier(.2,.82,.24,1),
          padding .46s cubic-bezier(.2,.82,.24,1),
          border-radius .4s ease,
          opacity .24s ease,
          transform .4s cubic-bezier(.2,.82,.24,1);
      }

      .eprospek-mobile-hero.is-compact{
        z-index:10010;
        height:68px;
        min-height:68px;
        padding:10px 14px;
        border-radius:0 0 24px 24px;
      }

      .eprospek-mobile-hero.is-scroll-hidden{
        height:0;
        min-height:0;
        margin-bottom:0;
        padding-top:0;
        padding-bottom:0;
        border-width:0;
        opacity:0;
        transform:translateY(-100%);
        pointer-events:none;
      }

      .eprospek-mobile-hero::before{
        content:"";
        position:absolute;
        inset:7px;
        z-index:-1;
        border:1px solid rgba(255,255,255,.08);
        border-top:0;
        border-radius:0 0 27px 27px;
      }

      .eprospek-mobile-hero::after{
        content:"";
        position:absolute;
        z-index:-1;
        width:128%;height:88px;right:-19%;top:86px;
        border-radius:44% 56% 48% 52% / 62% 45% 55% 38%;
        background:rgba(255,255,255,.09);
        transform:rotate(-9deg);
      }

      .eprospek-hero-toolbar{
        position:relative;
        z-index:4;
        display:flex;
        align-items:center;
        justify-content:flex-end;
      }

      .eprospek-hero-compact{
        position:absolute;left:14px;top:50%;z-index:5;
        display:flex;align-items:center;gap:9px;
        opacity:0;transform:translateY(-50%) translateX(-14px);
        pointer-events:none;
        will-change:transform,opacity;
        backface-visibility:hidden;
        transition:opacity .3s ease .12s,transform .52s cubic-bezier(.16,1,.3,1) .08s;
      }

      .eprospek-hero-compact-icon{
        width:38px;height:38px;display:grid;place-items:center;border:1px solid rgba(255,255,255,.2);
        border-radius:13px;color:#fff;background:rgba(255,255,255,.12);font-size:17px;
      }

      .eprospek-hero-compact-title{font-size:11px;font-weight:900}
      .eprospek-hero-compact-sub{margin-top:1px;color:rgba(255,255,255,.68);font-size:7px}

      .eprospek-mobile-hero.is-compact .eprospek-hero-compact{
        opacity:1;
        transform:translateY(-50%) translateX(0);
      }

      .eprospek-mobile-hero.is-compact .eprospek-hero-copy,
      .eprospek-mobile-hero.is-compact .eprospek-hero-visual,
      .eprospek-mobile-hero.is-compact::after{
        opacity:0;
        transform:translateY(-28px);
        pointer-events:none;
      }

      .eprospek-hero-actions{display:flex;align-items:center;gap:8px}
      .eprospek-hero-notification{
        width:38px;height:38px;display:grid;place-items:center;padding:0;
        border:1px solid rgba(255,255,255,.17);border-radius:50%;
        background:rgba(255,255,255,.09);color:#fff;overflow:visible;
      }
      .eprospek-hero-notification .notification-bell-root{
        position:static !important;
        width:100%;height:100%;display:grid;place-items:center;
      }
      .eprospek-hero-notification .notification-bell-trigger{
        width:100% !important;height:100% !important;min-width:0 !important;
        display:grid !important;place-items:center !important;
        margin:0 !important;padding:0 !important;border-radius:50% !important;
        outline:0 !important;-webkit-tap-highlight-color:transparent;
      }
      .eprospek-hero-notification .notification-bell-trigger:focus,
      .eprospek-hero-notification .notification-bell-trigger:focus-visible,
      .eprospek-hero-notification .notification-bell-trigger:active{
        border-radius:50% !important;
        outline:0 !important;
        background:rgba(255,255,255,.16) !important;
        box-shadow:inset 0 0 0 1px rgba(255,255,255,.28) !important;
      }
      .eprospek-hero-notification .notification-bell-trigger > i{
        display:block;margin:0 !important;font-size:17px;line-height:1;
        transform:none !important;
      }
      .eprospek-hero-notification .notification-bell-trigger .badge{
        top:-3px !important;right:-5px !important;left:auto !important;
        min-width:18px;padding:4px 5px !important;transform:none !important;
        border:2px solid #6785dc;
      }
      .eprospek-hero-notification button,
      .eprospek-hero-notification a{
        color:#fff !important;background:transparent !important;border:0 !important;box-shadow:none !important;
      }
      .eprospek-hero-notification .notification-panel{
        position:fixed !important;
        top:60px !important;right:12px !important;left:12px !important;
        width:auto !important;max-width:none !important;max-height:calc(100dvh - 84px) !important;
        margin:0 !important;overflow:hidden !important;
        border:1px solid rgba(255,255,255,.3) !important;border-radius:24px !important;
        color:#fff !important;
        background:linear-gradient(142deg,#7196e5 0%,#5d78d6 55%,#4b61c4 100%) !important;
        box-shadow:0 24px 60px rgba(34,47,111,.34),inset 0 1px 0 rgba(255,255,255,.18) !important;
        z-index:11050 !important;
      }
      .eprospek-hero-notification .notification-panel::before,
      .eprospek-hero-notification .notification-panel::after{
        content:"";position:absolute;z-index:0;left:-12%;width:124%;pointer-events:none;
        border-radius:46% 54% 48% 52% / 58% 43% 57% 42%;
        background:rgba(255,255,255,.09);
      }
      .eprospek-hero-notification .notification-panel::before{
        top:72px;height:68px;transform:rotate(-5deg);
      }
      .eprospek-hero-notification .notification-panel::after{
        bottom:34px;height:82px;transform:rotate(6deg);background:rgba(37,55,139,.12);
      }
      .eprospek-hero-notification .notification-panel-head,
      .eprospek-hero-notification .notification-panel-list{
        position:relative;z-index:1;
      }
      .eprospek-hero-notification .notification-panel-head{
        border-color:rgba(255,255,255,.18) !important;background:rgba(255,255,255,.06) !important;
      }
      .eprospek-hero-notification .notification-panel .text-muted{
        color:rgba(255,255,255,.74) !important;
      }
      .eprospek-hero-notification .notification-panel-list{
        max-height:calc(100dvh - 164px) !important;
      }
      .eprospek-hero-notification .notification-panel-item{
        border-color:rgba(255,255,255,.16) !important;background:rgba(255,255,255,.035) !important;
      }
      .eprospek-hero-notification .notification-panel-item.bg-light{
        background:rgba(255,255,255,.13) !important;
      }
      .eprospek-hero-notification .notification-panel button,
      .eprospek-hero-notification .notification-panel a{
        width:auto !important;height:auto !important;padding:6px 10px !important;
        border:1px solid rgba(255,255,255,.32) !important;border-radius:999px !important;
        color:#fff !important;background:rgba(255,255,255,.12) !important;
      }
      .eprospek-hero-avatar{
        width:40px;height:40px;display:grid;place-items:center;border-radius:50%;
        color:#4b61c4;background:#f3f5ff;border:3px solid rgba(255,255,255,.5);
        font-weight:900;text-decoration:none;box-shadow:0 7px 18px rgba(1,13,43,.24);
      }

      .eprospek-hero-copy{
        position:relative;z-index:3;width:61%;margin-top:17px;
        will-change:transform,opacity;backface-visibility:hidden;
        transition:opacity .34s ease,transform .58s cubic-bezier(.16,1,.3,1);
      }
      .eprospek-hero-eyebrow{font-size:8px;font-weight:850;letter-spacing:.045em;text-transform:uppercase;color:#e3e8ff}
      .eprospek-hero-copy h1{margin:5px 0 2px;font-size:21px;font-weight:950;line-height:1.08;letter-spacing:-.025em}
      .eprospek-hero-date{font-size:9px;color:rgba(232,246,252,.78)}
      .eprospek-hero-role{
        display:inline-flex;align-items:center;gap:6px;max-width:100%;margin-top:13px;padding:7px 9px;
        border:1px solid rgba(255,255,255,.14);border-radius:999px;
        background:rgba(255,255,255,.1);font-size:7.8px;font-weight:700;
      }
      .eprospek-hero-role i{color:#ffd829}
      .eprospek-hero-visual{
        position:absolute;z-index:2;right:3px;bottom:3px;width:158px;height:204px;
        display:flex;align-items:flex-end;justify-content:flex-end;pointer-events:none;
        will-change:transform,opacity;backface-visibility:hidden;
        transition:opacity .34s ease,transform .58s cubic-bezier(.16,1,.3,1);
      }
      .eprospek-hero-visual{
        background-position:right bottom;background-repeat:no-repeat;background-size:contain;
        filter:none;
      }
      .eprospek-hero-visual img{
        width:100%;
        height:100%;
        display:block;
        object-fit:contain;
        object-position:right bottom;
      }

      .dash-desktop-heading{display:none !important}
      .mobile-dashboard-filter-toggle{
        width:100%;display:flex;align-items:center;justify-content:space-between;
        margin:0 0 9px;padding:11px 13px;border:1px solid rgba(93,120,214,.22);border-radius:18px;
        color:#3f519e;background:linear-gradient(145deg,#fff,#f2f5ff);box-shadow:0 9px 23px rgba(53,70,157,.09);
        font-size:10px;font-weight:800;
      }
      .mobile-dashboard-filter-toggle:focus,
      .mobile-dashboard-filter-toggle:active{outline:0 !important;box-shadow:0 9px 23px rgba(53,70,157,.09),0 0 0 3px rgba(93,120,214,.1) !important}
      .mobile-dashboard-filter-toggle i{color:#5d78d6}
      .dash-filter-card{
        display:block;
        max-height:0;
        margin-bottom:0 !important;
        padding:0 12px !important;
        overflow:hidden;
        border:0 solid transparent !important;
        border-radius:23px !important;
        background:linear-gradient(155deg,#fff,#f7f9ff) !important;
        box-shadow:none !important;
        opacity:0;
        transform:translateY(-7px);
        transition:max-height .4s cubic-bezier(.22,1,.36,1),padding .35s ease,margin .35s ease,opacity .25s ease,transform .35s ease,box-shadow .35s ease;
      }
      .dash-filter-card.mobile-open{
        max-height:720px;
        margin-bottom:13px !important;
        padding:12px !important;
        border-width:1px !important;
        border-color:rgba(183,195,233,.68) !important;
        box-shadow:0 14px 30px rgba(53,70,157,.09) !important;
        opacity:1;
        transform:translateY(0);
      }
      .mobile-filter-sheet-head{display:flex;align-items:center;gap:10px;margin-bottom:11px;padding:2px 2px 10px;border-bottom:1px solid #e8ecf8}
      .mobile-filter-sheet-icon{width:34px;height:34px;display:grid;place-items:center;flex:0 0 34px;border-radius:12px;color:#fff;background:linear-gradient(145deg,#7196e5,#4b61c4);box-shadow:0 7px 15px rgba(75,97,196,.2)}
      .mobile-filter-sheet-title{color:#25346f;font-size:.76rem;font-weight:850;line-height:1.15}
      .mobile-filter-sheet-sub{margin-top:2px;color:#7b86a8;font-size:.59rem;line-height:1.25}
      .dash-filter-card .row{--bs-gutter-x:10px;--bs-gutter-y:9px}
      .dash-filter-card .form-label{color:#4a587f;font-size:.65rem;font-weight:800 !important}
      .dash-filter-card .form-select,
      .dash-filter-card .form-control{
        min-height:40px;
        border:1px solid #dfe5f5;
        border-radius:13px;
        color:#263451;
        background-color:#fbfcff;
        font-size:.72rem;
        box-shadow:none !important;
      }
      .dash-filter-card .summary-note{width:100%;justify-content:flex-start;border-radius:15px;background:#f7f9ff}
      .dash-title{font-size:1.7rem}
      .desktop-ai-btn{display:none !important}
      .mobile-inline-actions{display:flex;gap:10px;margin-top:12px;flex-wrap:wrap}
      .mobile-inline-actions .mobile-inline-ai,
      .mobile-inline-actions .mobile-inline-add{flex:1 1 calc(50% - 5px);justify-content:center}

      .dash-stat-card{
        --metric-accent:#5d78d6;
        min-height:82px;
        padding:35px 5px 7px;
        border:1px solid rgba(187,210,226,.65);
        border-radius:15px;
        color:#16335c;
        background:linear-gradient(145deg,#fff,#f6fbff);
        box-shadow:0 7px 17px rgba(53,70,157,.07);
      }
      .dash-stat-card::after{
        content:"";position:absolute;left:-8%;bottom:-18px;width:116%;height:34px;
        border-radius:48% 52% 46% 54% / 62% 48% 52% 38%;
        background:color-mix(in srgb,var(--metric-accent) 11%,transparent);
        transform:rotate(-3deg);
      }
      .dash-stat-card .value{position:relative;z-index:2;font-size:1.04rem;line-height:1}
      .dash-stat-card .label{overflow:hidden;color:#647b96;font-size:.46rem;line-height:1.15;margin-bottom:4px;white-space:nowrap;text-overflow:ellipsis}
      .dash-stat-card .icon{
        left:6px;right:auto;top:6px;bottom:auto;width:24px;height:24px;display:grid;place-items:center;
        border-radius:8px;font-size:11px;color:var(--metric-accent);opacity:1;
        background:color-mix(in srgb,var(--metric-accent) 12%,white);
      }
      .dashboard-summary-cards{
        --bs-gutter-x:0;
        --bs-gutter-y:0;
        display:grid !important;
        grid-template-columns:repeat(5,minmax(0,1fr));
        gap:5px;
        margin:0 0 13px !important;
      }
      .dashboard-summary-cards > div{
        width:auto !important;
        max-width:none !important;
        padding:0 !important;
        grid-column:auto !important;
        grid-row:auto !important;
        float:none !important;
      }
      .dashboard-summary-cards .bg-total .value{color:#d3a600}
      .dashboard-summary-cards .bg-open .value{color:#087fbd}
      .dashboard-summary-cards .bg-follow .value{color:#0b2b6f}
      .dashboard-summary-cards .bg-rejected .value{color:#d74a5c}
      .dashboard-summary-cards .bg-closing .value{color:#178fbd}
      .dashboard-summary-cards .bg-total{--metric-accent:#e7a900;background:linear-gradient(145deg,#fffdf3,#fff)}
      .dashboard-summary-cards .bg-open{--metric-accent:#209bc7;background:linear-gradient(145deg,#f2fbff,#fff)}
      .dashboard-summary-cards .bg-follow{--metric-accent:#4b61c4;background:linear-gradient(145deg,#f3f5ff,#fff)}
      .dashboard-summary-cards .bg-rejected{--metric-accent:#e85b72;background:linear-gradient(145deg,#fff4f6,#fff)}
      .dashboard-summary-cards .bg-closing{--metric-accent:#18a7c8;background:linear-gradient(145deg,#f0fcff,#fff)}
      .mobile-analysis-menu{
        margin:0 0 14px;
        padding:11px 10px 10px;
        border:1px solid #dbe7f8;
        border-radius:18px;
        background:#fff;
        box-shadow:0 10px 26px rgba(20,42,82,.07);
      }
      .mobile-analysis-menu-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        margin-bottom:9px;
        padding:0 1px;
      }
      .mobile-analysis-menu-title{
        color:#142a52;
        font-size:.76rem;
        font-weight:900;
        letter-spacing:-.01em;
      }
      .mobile-analysis-menu-sub{
        display:inline-flex;
        align-items:center;
        gap:4px;
        color:#8292ad;
        font-size:.49rem;
        font-weight:600;
      }
      .mobile-analysis-menu-sub i{
        color:#5b8def;
        font-size:.55rem;
      }
      .mobile-analysis-grid{
        display:grid;
        grid-template-columns:repeat(6,minmax(0,1fr));
        gap:5px;
      }
      .mobile-analysis-link{
        min-width:0;
        min-height:91px;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:flex-start;
        gap:3px;
        padding:5px 3px 4px;
        overflow:hidden;
        border:1px solid #dce8f8;
        border-bottom:2px solid #2f73e8;
        border-radius:12px;
        color:#142a52;
        background:linear-gradient(180deg,#fff 0%,#f7faff 100%);
        text-decoration:none;
        box-shadow:0 5px 12px rgba(47,115,232,.07);
        transition:border-color .18s ease,background .18s ease,box-shadow .18s ease,transform .18s ease;
      }
      .mobile-analysis-link:hover,
      .mobile-analysis-link:focus-visible{
        outline:0;
        border-color:#9fc2f6;
        border-bottom-color:#1769e0;
        background:#f2f7ff;
        box-shadow:0 7px 16px rgba(47,115,232,.13);
      }
      .mobile-analysis-link:active{
        transform:translateY(1px) scale(.98);
        box-shadow:0 3px 8px rgba(47,115,232,.1);
      }
      .mobile-analysis-mockup{
        width:100%;
        height:62px;
        display:block;
        object-fit:contain;
        border:0;
        border-radius:9px;
        background:#f8fbff;
        mix-blend-mode:multiply;
      }
      .mobile-analysis-link span{
        width:100%;
        overflow:hidden;
        padding:0 1px 1px;
        color:#142a52;
        font-size:.48rem;
        font-weight:850;
        line-height:1.15;
        text-align:center;
        white-space:nowrap;
        text-overflow:ellipsis;
      }
      .dash-panel{border-radius:19px;box-shadow:0 10px 23px rgba(11,43,111,.07)}
      .mobile-analysis-panel{
        --insight-rgb:47,115,232;
        position:relative;scroll-margin-top:10px;
        border-color:rgba(var(--insight-rgb),.25);
        background:linear-gradient(155deg,rgba(var(--insight-rgb),.09),#fff 58%);
      }
      .mobile-analysis-panel::before{
        content:"";position:absolute;left:0;right:0;top:0;height:3px;
        background:linear-gradient(90deg,#1769e0,#5b8def);
      }
      .mobile-analysis-panel .analysis-kicker{
        display:inline-flex;align-items:center;gap:5px;margin-bottom:7px;padding:5px 8px;border-radius:999px;
        color:rgb(var(--insight-rgb));background:rgba(var(--insight-rgb),.13);
        font-size:.57rem;font-weight:850;text-transform:uppercase;letter-spacing:.04em;
      }
      .dash-panel .panel-title{font-size:.78rem}
      .dash-panel .panel-sub{font-size:.66rem}
      .dash-panel .panel-head{padding:12px 12px 0}
      .dash-panel .panel-body{padding:10px 12px 12px}
      .dash-panel .form-label{font-size:.66rem}
      .dash-panel .form-select,
      .dash-panel .form-control{min-height:34px;font-size:.72rem}
      .summary-note{gap:5px;padding:7px 9px;font-size:.64rem}
      .legend-chip{padding:6px 8px;font-size:.64rem}
      .modern-table{font-size:.68rem}
      .modern-table thead th{font-size:.62rem}
      .mobile-fab-stack{
        right:max(8px,calc(50% - 245px));
        bottom:calc(10px + env(safe-area-inset-bottom));
        gap:10px;
      }
      .mobile-analysis-panel .panel-head > div[style]{width:100%;min-width:0 !important;margin-top:7px}
      .dashboard-chart-box{height:190px !important;min-height:190px !important;max-height:190px !important}
      .dashboard-map{height:250px}
      .dash-panel{
        content-visibility:auto;
        contain-intrinsic-size:420px;
      }
    }

    @media (min-width: 768px){
      .mobile-fab-stack,
      .mobile-inline-actions{
        display:none !important;
      }
    }
  </style>

  <section class="eprospek-mobile-hero d-md-none" aria-label="Ringkasan akun">
    <div class="eprospek-hero-compact" aria-hidden="true">
      <span class="eprospek-hero-compact-icon"><i class="bi bi-speedometer2"></i></span>
      <div>
        <div class="eprospek-hero-compact-title">Dashboard</div>
        <div class="eprospek-hero-compact-sub">Ringkasan pipeline prospek</div>
      </div>
    </div>

    <div class="eprospek-hero-toolbar">
      <div class="eprospek-hero-actions">
        <div class="eprospek-hero-notification notif-wrap" data-notif-redirect="{{ url('/prospects') }}">
          @livewire('notifications.bell', [], key('dashboard-mobile-hero-bell-' . auth()->id()))
        </div>
        <a href="{{ route('profile.index') }}" class="eprospek-hero-avatar" aria-label="Buka profil">
          {{ strtoupper(substr(auth()->user()->nama_lengkap ?: auth()->user()->name ?: 'U', 0, 1)) }}
        </a>
      </div>
    </div>
    <div class="eprospek-hero-copy">
      <div class="eprospek-hero-eyebrow">Ringkasan Pipeline Cabang</div>
      <h1>Halo,<br>{{ \Illuminate\Support\Str::limit(auth()->user()->nama_lengkap ?: auth()->user()->name, 24) }}</h1>
      <div class="eprospek-hero-date">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</div>
      <div class="eprospek-hero-role">
        <i class="bi bi-lightning-charge-fill"></i>
        <span>{{ strtoupper(auth()->user()->role ?? 'PEGAWAI') }} · Pipeline prospek Anda</span>
      </div>
    </div>
    <div class="eprospek-hero-visual" aria-hidden="true">
      <img src="{{ asset('images/mobile/eprospek-hero-v1.webp') }}"
           alt=""
           decoding="async"
           fetchpriority="high">
    </div>
  </section>

  <div class="mobile-home-compact-bar d-md-none"
       id="dashboardMobileCompactBar"
       data-mobile-home-compact
       aria-hidden="true">
    <div class="mobile-home-compact-identity">
      <span class="mobile-home-compact-icon" aria-hidden="true">
        <i class="bi bi-speedometer2"></i>
      </span>
      <div class="mobile-home-compact-copy">
        <div class="mobile-home-compact-title">Dashboard</div>
        <div class="mobile-home-compact-sub">Ringkasan pipeline prospek</div>
      </div>
    </div>
    <div class="mobile-home-compact-actions">
      <a href="{{ url('/prospects') }}" class="mobile-home-compact-action" aria-label="Buka notifikasi">
        <i class="bi bi-bell"></i>
      </a>
      <a href="{{ route('profile.index') }}" class="mobile-home-compact-avatar" aria-label="Buka profil">
        {{ strtoupper(substr(auth()->user()->nama_lengkap ?: auth()->user()->name ?: 'U', 0, 1)) }}
      </a>
    </div>
  </div>

  <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3 dash-desktop-heading">
    <div>
      <div class="dash-title">Dashboard CRM Prospek</div>
      <div class="dash-subtitle">Ringkasan prospek, status, produk, jenis usaha, dan peta persebaran Jawa Tengah</div>

      <div class="mobile-inline-actions">
        <a href="{{ route('ai.chat.index') }}" class="desktop-ai-btn mobile-inline-ai">
          <i class="bi bi-stars"></i> Chat AI
        </a>


      </div>
    </div>

    <div class="dash-top-actions d-none d-md-flex">
      <a href="{{ route('ai.chat.index') }}" class="desktop-ai-btn">
        <i class="bi bi-stars"></i> Chat AI
      </a>


    </div>
  </div>

  <button type="button" class="mobile-dashboard-filter-toggle d-md-none" id="mobileDashboardFilterToggle"
          aria-controls="dashboardFilterCard" aria-expanded="false">
    <span><i class="bi bi-sliders2 me-2"></i>Filter Dashboard</span>
    <i class="bi bi-chevron-down"></i>
  </button>

  <div class="dash-filter-card p-3 mb-4" id="dashboardFilterCard">
    <div class="mobile-filter-sheet-head d-md-none">
      <span class="mobile-filter-sheet-icon"><i class="bi bi-sliders2"></i></span>
      <div>
        <div class="mobile-filter-sheet-title">Atur Tampilan Dashboard</div>
        <div class="mobile-filter-sheet-sub">Pilih wilayah dan periode data yang ingin dianalisis.</div>
      </div>
    </div>

    <div class="row g-3 align-items-end">
      <div class="col-12 col-md-3">
        <label class="form-label fw-semibold mb-1">Filter Cabang / Kanwil</label>
        <select class="form-select"
                wire:model.live="filterCabang"
                @if($lockCabangFilter) disabled @endif>
          <option value="">-- Semua Cabang --</option>
          @foreach($cabangs as $c)
            <option value="{{ in_array($c->kode_cabang, ['100','200','300','400']) ? $c->kode_cabang : $c->id }}">
              {{ $c->kode_cabang }} - {{ $c->nama_cabang }}
            </option>
          @endforeach
        </select>

        @if($lockCabangFilter)
          <div class="small text-muted mt-1">
            Filter cabang otomatis mengikuti cabang user supervisor.
          </div>
        @endif
      </div>

      <div class="col-12 col-md-2">
        <label class="form-label fw-semibold mb-1">Mode Tanggal</label>
        <select class="form-select" wire:model.live="filterDateMode">
          <option value="all">Semua Data</option>
          <option value="monthly">Bulanan</option>
          <option value="range">Range Tanggal</option>
        </select>
      </div>

      @if($filterDateMode === 'monthly')
        <div class="col-6 col-md-2">
          <label class="form-label fw-semibold mb-1">Bulan</label>
          <select class="form-select" wire:model.live="filterBulan">
            @foreach($bulanOptions as $b)
              <option value="{{ $b['id'] }}">{{ $b['label'] }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-semibold mb-1">Tahun</label>
          <select class="form-select" wire:model.live="filterTahun">
            @foreach($tahunOptions as $t)
              <option value="{{ $t }}">{{ $t }}</option>
            @endforeach
          </select>
        </div>
      @elseif($filterDateMode === 'range')
        <div class="col-6 col-md-2">
          <label class="form-label fw-semibold mb-1">Tanggal Awal</label>
          <input type="date" class="form-control" wire:model.live="filterTanggalAwal">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-semibold mb-1">Tanggal Akhir</label>
          <input type="date" class="form-control" wire:model.live="filterTanggalAkhir">
        </div>
      @endif

      <div class="col-12 col-md">
        <div class="summary-note">
          <i class="bi bi-info-circle"></i>
          Dashboard akan menyesuaikan seluruh rekap sesuai filter yang dipilih.
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4 dashboard-summary-cards">
    <div class="col-12 col-sm-6 col-xl">
      <div class="dash-stat-card bg-total h-100">
        <div class="label">Total Pengajuan</div>
        <div class="value">{{ number_format($summary['total']) }}</div>
        <div class="icon"><i class="bi bi-collection"></i></div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
      <div class="dash-stat-card bg-open h-100">
        <div class="label">Open</div>
        <div class="value">{{ number_format($summary['open']) }}</div>
        <div class="icon"><i class="bi bi-folder2-open"></i></div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
      <div class="dash-stat-card bg-follow h-100">
        <div class="label">Follow Up</div>
        <div class="value">{{ number_format($summary['follow_up']) }}</div>
        <div class="icon"><i class="bi bi-arrow-repeat"></i></div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
      <div class="dash-stat-card bg-rejected h-100">
        <div class="label">Rejected</div>
        <div class="value">{{ number_format($summary['rejected']) }}</div>
        <div class="icon"><i class="bi bi-x-circle"></i></div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
      <div class="dash-stat-card bg-closing h-100">
        <div class="label">Closing</div>
        <div class="value">{{ number_format($summary['closing']) }}</div>
        <div class="icon"><i class="bi bi-check2-circle"></i></div>
      </div>
    </div>
  </div>

  <section class="mobile-analysis-menu d-md-none" aria-label="Menu analisis dashboard">
    <div class="mobile-analysis-menu-head">
      <div class="mobile-analysis-menu-title">Menu Analisis</div>
      <div class="mobile-analysis-menu-sub">Pilih insight <i class="bi bi-info-circle"></i></div>
    </div>
    <div class="mobile-analysis-grid">
      <a class="mobile-analysis-link" href="#analysis-closing">
        <img class="mobile-analysis-mockup"
             src="{{ asset('images/mobile/analysis-blue-v2/closing.webp') }}"
             alt="" aria-hidden="true">
        <span>Closing</span>
      </a>
      <a class="mobile-analysis-link" href="#analysis-product">
        <img class="mobile-analysis-mockup"
             src="{{ asset('images/mobile/analysis-blue-v2/product.webp') }}"
             alt="" aria-hidden="true">
        <span>Produk</span>
      </a>
      <a class="mobile-analysis-link" href="#analysis-status">
        <img class="mobile-analysis-mockup"
             src="{{ asset('images/mobile/analysis-blue-v2/status.webp') }}"
             alt="" aria-hidden="true">
        <span>Status</span>
      </a>
      <a class="mobile-analysis-link" href="#analysis-business">
        <img class="mobile-analysis-mockup"
             src="{{ asset('images/mobile/analysis-blue-v2/business.webp') }}"
             alt="" aria-hidden="true">
        <span>Usaha</span>
      </a>
      <a class="mobile-analysis-link" href="#analysis-trend">
        <img class="mobile-analysis-mockup"
             src="{{ asset('images/mobile/analysis-blue-v2/trend.webp') }}"
             alt="" aria-hidden="true">
        <span>Tren</span>
      </a>
      <a class="mobile-analysis-link" href="#analysis-map">
        <img class="mobile-analysis-mockup"
             src="{{ asset('images/mobile/analysis-blue-v2/map.webp') }}"
             alt="" aria-hidden="true">
        <span>Peta</span>
      </a>
    </div>
  </section>

  <div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
      <div class="dash-panel h-100 mobile-analysis-panel" id="analysis-closing">
        <div class="panel-head d-flex flex-wrap align-items-start justify-content-between gap-3">
          <div>
            <div class="analysis-kicker d-md-none"><i class="bi bi-bar-chart-line"></i> Analisis Closing</div>
            <div class="panel-title">{{ $grafikUtamaTitle }}</div>
            <div class="panel-sub">{{ $grafikUtamaSubtitle }}</div>
          </div>

          <div style="min-width:260px;">
            <label class="form-label small fw-semibold mb-1">Mode Grafik</label>
            <select class="form-select form-select-sm" wire:model.live="filterGrafikClosingMode">
              <option value="closing">Per KC (Closing)</option>
              <option value="pengaju">Per KC By Pengajuan</option>
              <option value="per_kc_non_closing_rejected">Per KC (Open + Follow Up)</option>
              <option value="per_kc_follow_up">Per KC (Follow Up)</option>
              <option value="per_kc_rejected">Per KC (Rejected)</option>
            </select>
          </div>
        </div>

        <div class="panel-body">
          <div wire:ignore class="dashboard-chart-box" data-mobile-height="190px">
            <canvas id="chartClosingCabang"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="dash-panel h-100 mobile-analysis-panel" id="analysis-product">
        <div class="panel-head">
          <div class="analysis-kicker d-md-none"><i class="bi bi-pie-chart"></i> Analisis Produk</div>
          <div class="panel-title">Pengajuan per Rekomendasi Produk</div>
          <div class="panel-sub">Komposisi produk yang paling banyak diajukan</div>
        </div>
        <div class="panel-body">
          <div wire:ignore class="dashboard-chart-box" data-mobile-height="155px">
            <canvas id="chartProduk"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-xl-4">
      <div class="dash-panel h-100 mobile-analysis-panel" id="analysis-status">
        <div class="panel-head">
          <div class="analysis-kicker d-md-none"><i class="bi bi-speedometer2"></i> Analisis Status</div>
          <div class="panel-title">Distribusi Status</div>
          <div class="panel-sub">Mencakup OPEN, FOLLOW UP, REJECTED, dan CLOSING</div>
        </div>
        <div class="panel-body">
          <div wire:ignore class="dashboard-chart-box" data-mobile-height="155px">
            <canvas id="chartStatus"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="dash-panel h-100 mobile-analysis-panel" id="analysis-business">
        <div class="panel-head">
          <div class="analysis-kicker d-md-none"><i class="bi bi-briefcase"></i> Analisis Usaha</div>
          <div class="panel-title">Top Jenis Usaha</div>
          <div class="panel-sub">Jenis usaha yang paling dominan</div>
        </div>
        <div class="panel-body">
          <div wire:ignore class="dashboard-chart-box" data-mobile-height="175px">
            <canvas id="chartUsaha"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="dash-panel h-100 mobile-analysis-panel" id="analysis-trend">
        <div class="panel-head">
          <div class="analysis-kicker d-md-none"><i class="bi bi-graph-up-arrow"></i> Analisis Tren</div>
          <div class="panel-title">Tren Pengajuan Bulanan</div>
          <div class="panel-sub">Pergerakan jumlah input prospek per bulan</div>
        </div>
        <div class="panel-body">
          <div wire:ignore class="dashboard-chart-box" data-mobile-height="175px">
            <canvas id="chartTrend"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
      <div class="dash-panel mobile-analysis-panel" id="analysis-map">
        <div class="panel-head">
          <div class="analysis-kicker d-md-none"><i class="bi bi-geo-alt"></i> Analisis Wilayah</div>
          <div class="panel-title">Peta Persebaran Pengajuan Jawa Tengah</div>
          <div class="panel-sub">Warna marker mengikuti master jenis usaha dari database</div>
        </div>

        <div class="panel-body">
          <div class="row g-2 mb-3">
            <div class="col-12 col-md-4">
              <label class="form-label small fw-semibold mb-1">Filter Status Map</label>
              <select class="form-select form-select-sm" wire:model.live="filterMapStatus">
                <option value="">-- Semua Status --</option>
                <option value="OPEN">OPEN</option>
                <option value="FOLLOW UP">FOLLOW UP</option>
                <option value="CLOSING">CLOSING</option>
                <option value="REJECTED">REJECTED</option>
              </select>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label small fw-semibold mb-1">Filter Jenis Usaha</label>
              <select class="form-select form-select-sm" wire:model.live="filterMapJenisUsaha">
                <option value="">-- Semua Jenis Usaha --</option>
                @foreach($mapJenisUsahaOptions as $opt)
                  <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label small fw-semibold mb-1">Filter Rekomendasi Produk</label>
              <select class="form-select form-select-sm" wire:model.live="filterMapProduk">
                <option value="">-- Semua Produk --</option>
                @foreach($mapProdukOptions as $opt)
                  <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="map-panel mb-3">
            <div wire:ignore>
              <div id="jatengMap" class="dashboard-map"></div>
            </div>
          </div>

          <div class="legend-wrap">
            <div class="small fw-semibold text-secondary mb-2">Legend Jenis Usaha</div>
            <div class="d-flex flex-wrap gap-2">
              @forelse($legendUsaha as $lg)
                <div class="legend-chip">
                  <span class="legend-dot" style="background:{{ $lg['color'] }};"></span>
                  <span>{{ $lg['nama'] }}</span>
                </div>
              @empty
                <div class="text-muted small">Belum ada legend jenis usaha.</div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="dash-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Top Cabang Pengajuan</div>
          <div class="panel-sub">5 cabang dengan pengajuan terbanyak</div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table modern-table align-middle mb-0">
              <thead>
                <tr>
                  <th>Cabang</th>
                  <th class="text-end">Total</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topCabang as $i => $r)
                  <tr>
                    <td>
                      <span class="rank-badge me-2">{{ $i + 1 }}</span>
                      {{ $r->kode_cabang }} - {{ $r->nama_cabang }}
                    </td>
                    <td class="text-end fw-bold">{{ number_format($r->total) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="2" class="text-center text-muted">Belum ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-xl-6">
      <div class="dash-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Top 5 Cabang Closing Terbanyak</div>
          <div class="panel-sub">Cabang dengan jumlah closing tertinggi</div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table modern-table align-middle mb-0">
              <thead>
                <tr>
                  <th>Cabang</th>
                  <th class="text-end">Closing</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topClosingCabang as $i => $r)
                  <tr>
                    <td>
                      <span class="rank-badge me-2">{{ $i + 1 }}</span>
                      {{ $r->kode_cabang }} - {{ $r->nama_cabang }}
                    </td>
                    <td class="text-end fw-bold text-success">{{ number_format($r->total) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="2" class="text-center text-muted">Belum ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-6">
      <div class="dash-panel h-100">
        <div class="panel-head">
          <div class="panel-title">Top 5 Pegawai / AO Berdasarkan Jumlah Pengajuan</div>
          <div class="panel-sub">Pegawai paling aktif menginput prospek</div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table modern-table align-middle mb-0">
              <thead>
                <tr>
                  <th>Username</th>
                  <th>Nama</th>
                  <th class="text-end">Total</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topPegawai as $i => $r)
                  <tr>
                    <td>{{ $r->name }}</td>
                    <td>{{ $r->nama_lengkap ?: '-' }}</td>
                    <td class="text-end fw-bold">{{ number_format($r->total) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="3" class="text-center text-muted">Belum ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="dash-panel">
    <div class="panel-head">
      <div class="panel-title">Prospek Terbaru</div>
      <div class="panel-sub">10 data prospek terbaru</div>
    </div>
    <div class="panel-body">
      <div class="table-responsive">
        <table class="table modern-table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Nama</th>
              <th>No HP</th>
              <th>Produk</th>
              <th>Status</th>
              <th>Cabang</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recent as $p)
              @php
                $statusClass = 'status-open';
                if($p->status === 'FOLLOW UP') $statusClass = 'status-follow';
                elseif($p->status === 'REJECTED') $statusClass = 'status-rejected';
                elseif($p->status === 'CLOSING') $statusClass = 'status-closing';

                $produkClass = 'produk-kredit';
                if($p->jenis_produk === 'TABUNGAN') $produkClass = 'produk-tabungan';
                elseif($p->jenis_produk === 'DEPOSITO') $produkClass = 'produk-deposito';
                elseif($p->jenis_produk === 'ASET') $produkClass = 'produk-aset';
              @endphp
              <tr>
                <td>{{ $p->tanggal_prospek }}</td>
                <td class="fw-semibold">{{ $p->nama }}</td>
                <td>{{ $p->no_hp }}</td>
                <td>
                  <span class="produk-chip {{ $produkClass }}">{{ $p->jenis_produk }}</span>
                </td>
                <td>
                  <span class="status-chip {{ $statusClass }}">{{ $p->status }}</span>
                </td>
                <td>{{ $p->cabang?->nama_cabang ?? '-' }}</td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script type="application/json" id="dashboard-data-closing-labels">@json($closingCabangLabels)</script>
  <script type="application/json" id="dashboard-data-closing-values">@json($closingCabangValues)</script>
  <script type="application/json" id="dashboard-data-closing-dataset">@json($grafikUtamaDataset)</script>
  <script type="application/json" id="dashboard-data-produk-labels">@json($produkLabels)</script>
  <script type="application/json" id="dashboard-data-produk-values">@json($produkValues)</script>
  <script type="application/json" id="dashboard-data-status-labels">@json($statusLabels)</script>
  <script type="application/json" id="dashboard-data-status-values">@json($statusValues)</script>
  <script type="application/json" id="dashboard-data-usaha-labels">@json($usahaLabels)</script>
  <script type="application/json" id="dashboard-data-usaha-values">@json($usahaValues)</script>
  <script type="application/json" id="dashboard-data-trend-labels">@json($trendLabels)</script>
  <script type="application/json" id="dashboard-data-trend-values">@json($trendValues)</script>
  <script type="application/json" id="dashboard-data-map-items">@json($mapItems)</script>
  <script type="application/json" id="dashboard-data-usaha-color-map">@json($usahaColorMap)</script>

  <div class="mobile-fab-stack d-md-none">
    <a href="{{ route('ai.chat.index') }}" class="mobile-fab-ai" aria-label="Chat AI">
      <i class="bi bi-stars"></i>
    </a>

    <a href="{{ route('prospects.create') }}" class="mobile-fab-dashboard-add" aria-label="Tambah Prospek">
      <i class="bi bi-plus-lg"></i>
    </a>
  </div>

  <script>
    (function () {
      const button = document.getElementById('mobileDashboardFilterToggle');
      const card = document.getElementById('dashboardFilterCard');
      if (!button || !card || button.dataset.bound === '1') return;
      button.dataset.bound = '1';
      button.addEventListener('click', function () {
        const open = card.classList.toggle('mobile-open');
        button.setAttribute('aria-expanded', open ? 'true' : 'false');
        const chevron = button.querySelector('.bi-chevron-down, .bi-chevron-up');
        if (chevron) {
          chevron.classList.toggle('bi-chevron-down', !open);
          chevron.classList.toggle('bi-chevron-up', open);
        }
      });
    })();
  </script>

  @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
    (function () {
      if (window.__crmDashboardSmoothBound) return;
      window.__crmDashboardSmoothBound = true;

      let chartClosingCabang = null;
      let chartProduk = null;
      let chartStatus = null;
      let chartUsaha = null;
      let chartTrend = null;
      let mapInstance = null;
      let mapLayerGroup = null;
      let mapDataSignature = null;
      let renderTimer = null;

      function parseJsonScript(id, fallback) {
        const el = document.getElementById(id);
        if (!el) return fallback;
        try {
          return JSON.parse(el.textContent || 'null') ?? fallback;
        } catch (e) {
          return fallback;
        }
      }

      function esc(v) {
        return String(v ?? '')
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');
      }


      function pick(item, keys, fallback = '-') {
        if (!item) return fallback;

        for (const key of keys) {
          const parts = String(key).split('.');
          let val = item;

          for (const part of parts) {
            if (val && Object.prototype.hasOwnProperty.call(val, part)) {
              val = val[part];
            } else {
              val = undefined;
              break;
            }
          }

          if (val !== undefined && val !== null && String(val).trim() !== '') {
            return val;
          }
        }

        return fallback;
      }

      function getDashboardPayload() {
        return {
          closingLabels: parseJsonScript('dashboard-data-closing-labels', []),
          closingValues: parseJsonScript('dashboard-data-closing-values', []),
          closingDataset: parseJsonScript('dashboard-data-closing-dataset', 'Closing'),
          produkLabels: parseJsonScript('dashboard-data-produk-labels', []),
          produkValues: parseJsonScript('dashboard-data-produk-values', []),
          statusLabels: parseJsonScript('dashboard-data-status-labels', []),
          statusValues: parseJsonScript('dashboard-data-status-values', []),
          usahaLabels: parseJsonScript('dashboard-data-usaha-labels', []),
          usahaValues: parseJsonScript('dashboard-data-usaha-values', []),
          trendLabels: parseJsonScript('dashboard-data-trend-labels', []),
          trendValues: parseJsonScript('dashboard-data-trend-values', []),
          mapItems: parseJsonScript('dashboard-data-map-items', []),
          usahaColorMap: parseJsonScript('dashboard-data-usaha-color-map', {})
        };
      }

      function getUsahaColor(kode, payload) {
        const map = (payload && payload.usahaColorMap) ? payload.usahaColorMap : {};
        return map[String(kode || '').toUpperCase()] || '#94a3b8';
      }

      function makeCircleIcon(color) {
        return L.divIcon({
          className: '',
          html: '<div style="width:16px;height:16px;border-radius:999px;background:' + color + ';border:2px solid #fff;box-shadow:0 0 0 2px rgba(15,23,42,.12), 0 4px 10px rgba(15,23,42,.18);"></div>',
          iconSize: [16, 16],
          iconAnchor: [8, 8]
        });
      }

      function setChartLoading(on) {
        // No opacity/fade supaya filter tidak terlihat glitch/kedip.
      }

      function forceCanvasSize(canvas) {
        if (!canvas) return;
        var box = canvas.closest('.dashboard-chart-box');
        if (!box) return;

        var isMobileChart = window.matchMedia && window.matchMedia('(max-width: 767.98px)').matches;
        var chartHeight = isMobileChart ? (box.dataset.mobileHeight || '180px') : '320px';
        box.style.setProperty('height', chartHeight, 'important');
        box.style.setProperty('min-height', chartHeight, 'important');
        box.style.setProperty('max-height', chartHeight, 'important');

        canvas.style.setProperty('width', '100%', 'important');
        canvas.style.setProperty('height', '100%', 'important');
        canvas.style.setProperty('display', 'block', 'important');
      }

      function refreshChartSize(chart, canvas) {
        forceCanvasSize(canvas);
        if (!chart) return;
        setTimeout(function(){ try { chart.resize(); chart.update('none'); } catch(e){} }, 30);
        setTimeout(function(){ try { chart.resize(); chart.update('none'); } catch(e){} }, 180);
      }

      function upsertChart(current, canvas, config) {
        if (!canvas || !window.Chart) return current;

        forceCanvasSize(canvas);

        if (current) {
          current.data.labels = config.data.labels || [];
          current.data.datasets = config.data.datasets || [];
          current.options = config.options;
          current.update('none');
          refreshChartSize(current, canvas);
          return current;
        }

        var chart = new Chart(canvas, config);
        refreshChartSize(chart, canvas);
        return chart;
      }

      function renderCharts() {
        const data = getDashboardPayload();
        const mobileChart = window.matchMedia && window.matchMedia('(max-width: 767.98px)').matches;
        const compactLegend = {
          position: 'bottom',
          labels: {
            usePointStyle: true,
            pointStyle: 'circle',
            boxWidth: 8,
            padding: mobileChart ? 8 : 12,
            font: { size: mobileChart ? 9 : 12 }
          }
        };

        const elClosing = document.getElementById('chartClosingCabang');
        const elProduk  = document.getElementById('chartProduk');
        const elStatus  = document.getElementById('chartStatus');
        const elUsaha   = document.getElementById('chartUsaha');
        const elTrend   = document.getElementById('chartTrend');

        if (!elClosing || !elProduk || !elStatus || !elUsaha || !elTrend || !window.Chart) return;

        chartClosingCabang = upsertChart(chartClosingCabang, elClosing, {
          type: 'bar',
          data: {
            labels: data.closingLabels,
            datasets: [{
              label: data.closingDataset,
              data: data.closingValues,
              backgroundColor: '#93c5fd',
              borderColor: '#60a5fa',
              borderWidth: 1,
              borderRadius: 12,
              barThickness: 18,
              maxBarThickness: 24
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: { legend: { display: true, ...compactLegend } },
            scales: {
              y: { beginAtZero: true, ticks: { precision: 0, font: { size: mobileChart ? 9 : 12 } }, grid: { color: 'rgba(148,163,184,.18)' } },
              x: {
                ticks: {
                  autoSkip: mobileChart,
                  maxTicksLimit: mobileChart ? 6 : undefined,
                  maxRotation: mobileChart ? 0 : 90,
                  minRotation: 0,
                  font: { size: mobileChart ? 8 : 12 }
                },
                grid: { display: false }
              }
            }
          }
        });

        chartProduk = upsertChart(chartProduk, elProduk, {
          type: 'doughnut',
          data: {
            labels: data.produkLabels,
            datasets: [{
              data: data.produkValues,
              backgroundColor: ['#38bdf8','#fb7185','#fb923c','#facc15','#34d399','#818cf8']
            }]
          },
          options: { responsive: true, maintainAspectRatio: false, animation: false, cutout: '55%', plugins: { legend: compactLegend } }
        });

        chartStatus = upsertChart(chartStatus, elStatus, {
          type: 'pie',
          data: {
            labels: data.statusLabels,
            datasets: [{
              data: data.statusValues,
              backgroundColor: ['#cbd5e1','#fbbf24','#f43f5e','#22c55e']
            }]
          },
          options: { responsive: true, maintainAspectRatio: false, animation: false, plugins: { legend: compactLegend } }
        });

        chartUsaha = upsertChart(chartUsaha, elUsaha, {
          type: 'bar',
          data: {
            labels: data.usahaLabels,
            datasets: [{ label: 'Jumlah', data: data.usahaValues, backgroundColor: '#60a5fa', borderRadius: 10 }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
              x: { beginAtZero: true, ticks: { precision: 0, font: { size: mobileChart ? 9 : 12 } }, grid: { color: 'rgba(148,163,184,.18)' } },
              y: { ticks: { font: { size: mobileChart ? 8 : 12 } }, grid: { display: false } }
            }
          }
        });

        chartTrend = upsertChart(chartTrend, elTrend, {
          type: 'line',
          data: {
            labels: data.trendLabels,
            datasets: [{
              label: 'Pengajuan',
              data: data.trendValues,
              borderColor: '#2563eb',
              backgroundColor: 'rgba(37,99,235,.15)',
              fill: true,
              tension: .35,
              pointRadius: 3,
              pointBackgroundColor: '#2563eb'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: { legend: compactLegend },
            scales: {
              y: { beginAtZero: true, ticks: { precision: 0, font: { size: mobileChart ? 9 : 12 } }, grid: { color: 'rgba(148,163,184,.18)' } },
              x: { ticks: { maxTicksLimit: mobileChart ? 6 : undefined, font: { size: mobileChart ? 8 : 12 } }, grid: { display: false } }
            }
          }
        });
      }

      function renderMap() {
        const payload = getDashboardPayload();
        const items = payload.mapItems || [];
        const mapEl = document.getElementById('jatengMap');
        if (!mapEl || !window.L) return;

        if (!mapInstance) {
          mapInstance = L.map('jatengMap').setView([-7.150975, 110.140259], 8);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
          }).addTo(mapInstance);

          mapLayerGroup = L.layerGroup().addTo(mapInstance);
        }

        const nextMapDataSignature = JSON.stringify({
          items: items,
          usahaColorMap: payload.usahaColorMap || {}
        });

        // Jangan bangun ulang marker atau menjalankan fitBounds bila datanya
        // tidak berubah. Ini mempertahankan zoom, posisi, dan popup pengguna.
        if (mapDataSignature === nextMapDataSignature) {
          mapInstance.invalidateSize();
          return;
        }

        mapDataSignature = nextMapDataSignature;
        mapLayerGroup.clearLayers();

        const bounds = [];
        const isMobilePopup = window.matchMedia('(max-width: 767.98px)').matches;
        const mapWidth = mapEl.clientWidth || window.innerWidth || 320;
        const mapHeight = mapEl.clientHeight || 250;
        const popupMaxWidth = isMobilePopup
          ? Math.min(252, Math.max(180, mapWidth - 44))
          : 300;
        const popupMaxHeight = isMobilePopup
          ? Math.max(112, Math.min(138, Math.floor(mapHeight * .55)))
          : 380;
        const popupOptions = {
          className: 'dashboard-prospect-popup',
          minWidth: isMobilePopup ? Math.min(205, popupMaxWidth) : 240,
          maxWidth: popupMaxWidth,
          maxHeight: popupMaxHeight,
          autoPan: true,
          keepInView: true,
          autoPanPaddingTopLeft: L.point(14, isMobilePopup ? 54 : 18),
          autoPanPaddingBottomRight: L.point(14, 14)
        };

        items.forEach(item => {
          const lat = parseFloat(item.latitude ?? item.lat ?? 0);
          const lng = parseFloat(item.longitude ?? item.lng ?? 0);

          if (!lat || !lng) return;

          const color = getUsahaColor(item.kode_jenis_usaha || item.jenis_usaha_kode || item.jenis_usaha, payload);
          const marker = L.marker([lat, lng], { icon: makeCircleIcon(color) });

          const nama = pick(item, ['nama', 'nama_prospek', 'prospek', 'name'], '-');
          const cabang = pick(item, [
            'cabang',
            'nama_cabang',
            'cabang_nama',
            'branch_name',
            'cabang.nama_cabang',
            'cabang.nama',
            'branch.nama_cabang'
          ], '-');
          const noHp = pick(item, ['no_hp', 'hp', 'phone', 'telepon', 'no_telp', 'noTelp', 'noHandphone'], '-');
          const status = pick(item, ['status'], '-');
          const produk = pick(item, ['jenis_produk', 'produk', 'rekomendasi_produk'], '-');
          const usaha = pick(item, [
            'jenis_usaha',
            'usaha',
            'nama_jenis_usaha',
            'keterangan_usaha',
            'jenisUsaha.nama',
            'jenis_usaha_nama'
          ], '-');
          const alamat = pick(item, ['alamat', 'address'], '');
          const wilayah = [
            pick(item, ['desa'], ''),
            pick(item, ['kecamatan'], ''),
            pick(item, ['kab_kota', 'kabupaten', 'kota'], '')
          ].filter(v => String(v || '').trim() !== '').join(', ');
          const fotoUrl = pick(item, ['foto_url', 'photo_url', 'file_url', 'image_url', 'foto', 'photo'], '');

          const foto = fotoUrl
            ? '<a class="map-popup-photo-link" href="' + esc(fotoUrl) + '" target="_blank" rel="noopener noreferrer" title="Buka foto ukuran penuh">' +
                '<img class="map-popup-photo" src="' + esc(fotoUrl) + '" alt="Foto ' + esc(nama) + '" loading="lazy">' +
              '</a>'
            : '<div class="map-popup-photo-empty"><i class="bi bi-image"></i> Foto belum tersedia</div>';

          const alamatHtml = alamat
            ? '<div class="map-popup-row"><i class="bi bi-geo-alt"></i><div><strong>Alamat:</strong> ' + esc(alamat) + '</div></div>'
            : '';

          const wilayahHtml = wilayah
            ? '<div class="map-popup-row"><i class="bi bi-map"></i><div><strong>Wilayah:</strong> ' + esc(wilayah) + '</div></div>'
            : '';

          const popupHtml =
            '<div class="map-popup-card">' +
              '<div class="map-popup-head">' +
                '<div class="map-popup-title">' + esc(nama) + '</div>' +
                '<div class="map-popup-subtitle"><i class="bi bi-building"></i><span>' + esc(cabang) + '</span></div>' +
              '</div>' +
              '<div class="map-popup-body">' +
                '<div class="map-popup-badges">' +
                  '<span class="map-popup-badge">' + esc(status) + '</span>' +
                  '<span class="map-popup-badge">' + esc(produk) + '</span>' +
                '</div>' +
                '<div class="map-popup-row"><i class="bi bi-telephone"></i><div><strong>No HP:</strong> ' + esc(noHp) + '</div></div>' +
                '<div class="map-popup-row"><i class="bi bi-briefcase"></i><div><strong>Usaha:</strong> ' + esc(usaha) + '</div></div>' +
                alamatHtml +
                wilayahHtml +
                foto +
              '</div>' +
            '</div>';

          marker.bindPopup(popupHtml, popupOptions);
          marker.on('popupopen', function (event) {
            const popupElement = event.popup && event.popup.getElement
              ? event.popup.getElement()
              : null;
            const image = popupElement
              ? popupElement.querySelector('.map-popup-photo')
              : null;
            const updatePopup = function () {
              window.requestAnimationFrame(function () {
                if (event.popup && event.popup.isOpen && event.popup.isOpen()) {
                  event.popup.update();
                }
              });
            };

            if (image && image.dataset.errorBound !== '1') {
              image.dataset.errorBound = '1';
              image.addEventListener('load', updatePopup, { once: true });
              image.addEventListener('error', function () {
                const link = image.closest('.map-popup-photo-link');
                if (link) {
                  link.outerHTML = '<div class="map-popup-photo-empty"><i class="bi bi-image"></i> Foto gagal dimuat</div>';
                }
                updatePopup();
              }, { once: true });
            }

            updatePopup();
          });
          marker.addTo(mapLayerGroup);
          bounds.push([lat, lng]);
        });

        if (bounds.length > 0) {
          mapInstance.fitBounds(bounds, { padding: [30, 30] });
        } else {
          mapInstance.setView([-7.150975, 110.140259], 8);
        }

        setTimeout(() => {
          if (mapInstance) mapInstance.invalidateSize();
        }, 120);
      }

      function renderAll() {
        clearTimeout(renderTimer);
        renderTimer = setTimeout(function(){
          requestAnimationFrame(function(){
            renderCharts();
            renderMap();
          });
        }, 60);
      }

      function renderAllStable() {
        renderAll();
        setTimeout(renderAll, 180);
        setTimeout(renderAll, 420);
      }

      function watchDashboardJson() {
        var targets = document.querySelectorAll('script[id^="dashboard-data-"]');
        if (!targets.length || !window.MutationObserver) return;

        if (window.__crmDashboardJsonObserver) {
          try { window.__crmDashboardJsonObserver.disconnect(); } catch(e){}
        }

        var observer = new MutationObserver(function(){
          renderAllStable();
        });

        targets.forEach(function(el){
          observer.observe(el, { childList:true, characterData:true, subtree:true });
        });

        window.__crmDashboardJsonObserver = observer;
      }

      document.addEventListener('dashboard:smooth-refresh', function(){
        setTimeout(function(){ watchDashboardJson(); renderAllStable(); }, 120);
      });

      document.addEventListener('livewire:navigated', function () {
        setTimeout(function(){ watchDashboardJson(); renderAllStable(); }, 120);
      });

      document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function(){ watchDashboardJson(); renderAllStable(); }, 120);
      });

      window.addEventListener('resize', function(){ setTimeout(renderAllStable, 120); });
      window.addEventListener('load', function(){ setTimeout(function(){ watchDashboardJson(); renderAllStable(); }, 180); });

      document.addEventListener('livewire:init', function(){
        try{
          Livewire.hook('commit', function(payload){
            if(payload && typeof payload.succeed === 'function'){
              payload.succeed(function(){
                setTimeout(function(){ watchDashboardJson(); renderAllStable(); }, 180);
                setTimeout(renderAllStable, 520);
              });
            }else{
              setTimeout(renderAllStable, 220);
            }
          });
        }catch(e){}

        try{ Livewire.hook('morph.updated', function(){ setTimeout(renderAllStable, 180); }); }catch(e){}
        try{ Livewire.hook('message.processed', function(){ setTimeout(renderAllStable, 180); }); }catch(e){}
      });
    })();
    </script>
  @endpush
</div>
