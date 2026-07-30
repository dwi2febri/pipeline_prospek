<div class="container-fluid px-0 android-app-shell">

  <style>
    .android-app-shell{
      position:relative;
      padding-bottom:92px;
    }

    .android-app-shell::before{
      content:"";
      position:fixed;
      inset:0;
      z-index:-3;
      background:
        radial-gradient(circle at top left, rgba(34,197,246,.10), transparent 22%),
        radial-gradient(circle at top right, rgba(99,102,241,.10), transparent 24%),
        linear-gradient(180deg,#f5fbff 0%,#eef4fb 45%,#eaf1f8 100%);
      pointer-events:none;
    }

    .android-app-shell::after{
      content:"";
      position:fixed;
      left:-8%;
      right:-8%;
      top:74px;
      height:260px;
      z-index:-2;
      background:
        radial-gradient(circle at 20% 20%, rgba(37,99,235,.10), transparent 22%),
        radial-gradient(circle at 78% 16%, rgba(14,165,233,.08), transparent 18%),
        radial-gradient(circle at 50% 85%, rgba(249,115,22,.08), transparent 18%);
      filter:blur(18px);
      pointer-events:none;
    }

    .app-page-head{
      margin-bottom:14px;
    }

    .page-topbar{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:14px;
      margin-bottom:12px;
    }

    .app-title{
      font-size:1.72rem;
      font-weight:900;
      color:#0f172a;
      letter-spacing:-.03em;
      line-height:1.06;
    }

    .app-subtitle{
      color:#64748b;
      font-size:.98rem;
      margin-top:6px;
      line-height:1.65;
      max-width:760px;
    }

    .section-title-modern{
      font-size:1.18rem;
      font-weight:900;
      color:#111827;
      margin-bottom:14px;
      letter-spacing:-.02em;
      padding-left:2px;
    }

    .card-soft{
      border:1px solid rgba(255,255,255,.85);
      border-radius:28px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
      box-shadow:0 16px 34px rgba(15,23,42,.08);
    }

    .btn-add-modern{
      display:inline-flex;
      align-items:center;
      gap:8px;
      border-radius:999px;
      padding:12px 18px;
      font-weight:800;
      text-decoration:none;
      color:#46566d;
      background:linear-gradient(135deg,#14b8a6 0%,#06b6d4 100%);
      box-shadow:0 16px 28px rgba(6,182,212,.24);
      border:0;
      white-space:nowrap;
      transition:all .18s ease;
    }

    .btn-add-modern:hover{
      color:#fff;
      transform:translateY(-1px);
    }

    .prospek-top-actions{
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

    .glass-head{
      border-radius:30px;
      background:
        radial-gradient(circle at top right, rgba(59,130,246,.14), transparent 26%),
        linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
      border:1px solid rgba(255,255,255,.88);
      box-shadow:0 18px 36px rgba(15,23,42,.07);
      padding:16px;
      margin-bottom:18px;
      position:relative;
      overflow:hidden;
    }

    .glass-head::after{
      content:"";
      position:absolute;
      width:150px;
      height:150px;
      right:-54px;
      top:-54px;
      border-radius:999px;
      background:rgba(59,130,246,.06);
      pointer-events:none;
    }

    .desktop-summary-row{
      display:grid;
      grid-template-columns:repeat(5, minmax(0, 1fr));
      gap:16px;
    }

    .summary-btn{
      --summary-accent:#2f73e8;
      border:1px solid color-mix(in srgb,var(--summary-accent) 24%,white) !important;
      border-bottom:3px solid var(--summary-accent) !important;
      width:100%;
      text-align:left;
      position:relative;
      padding:18px 18px 16px 18px;
      overflow:hidden;
      border-radius:22px;
      background:linear-gradient(180deg,#ffffff 0%,color-mix(in srgb,var(--summary-accent) 8%,white) 100%) !important;
      box-shadow:0 12px 26px rgba(20,42,82,.07);
      min-height:132px;
      transition:border-color .18s ease,background .18s ease,box-shadow .18s ease,transform .18s ease;
      color:#142a52;
    }

    .summary-btn:hover{
      transform:translateY(-2px);
      border-color:color-mix(in srgb,var(--summary-accent) 48%,white) !important;
      border-bottom-color:var(--summary-accent) !important;
      background:color-mix(in srgb,var(--summary-accent) 9%,white) !important;
      box-shadow:0 15px 30px color-mix(in srgb,var(--summary-accent) 15%,transparent);
    }

    .summary-btn.is-active{
      border-color:color-mix(in srgb,var(--summary-accent) 58%,white) !important;
      border-bottom-color:var(--summary-accent) !important;
      background:linear-gradient(180deg,#fff 0%,color-mix(in srgb,var(--summary-accent) 15%,white) 100%) !important;
      box-shadow:0 0 0 2px color-mix(in srgb,var(--summary-accent) 16%,transparent),0 15px 30px color-mix(in srgb,var(--summary-accent) 16%,transparent);
    }

    .summary-label{
      font-size:1rem;
      font-weight:800;
      color:#526581;
    }

    .summary-value{
      color:var(--summary-accent);
      font-size:3rem;
      font-weight:900;
      line-height:1;
      margin-top:12px;
      letter-spacing:-.04em;
    }

    .summary-icon{
      position:absolute;
      right:18px;
      bottom:12px;
      width:64px;
      height:64px;
      display:grid;
      place-items:center;
      border:1px solid color-mix(in srgb,var(--summary-accent) 24%,white);
      border-radius:18px;
      color:var(--summary-accent);
      background:color-mix(in srgb,var(--summary-accent) 11%,white);
      opacity:1;
    }

    .summary-svg-icon{
      width:42px;
      height:42px;
      display:grid;
      place-items:center;
    }

    .summary-svg-icon svg{
      width:100%;
      height:100%;
      fill:none;
      stroke:currentColor;
      stroke-width:1.65;
      stroke-linecap:round;
      stroke-linejoin:round;
    }

    .filter-card-modern{
      border:1px solid rgba(255,255,255,.88);
      border-radius:28px;
      padding:15px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfcfe 100%);
      box-shadow:0 14px 28px rgba(15,23,42,.05);
    }

    .filter-card-modern .input-group{
      border-radius:18px;
      overflow:hidden;
      background:#fff;
    }

    .filter-card-modern .form-control,
    .filter-card-modern .form-select,
    .filter-card-modern .input-group-text{
      min-height:48px;
      border-color:#e6edf7 !important;
      background:#fff !important;
      box-shadow:none !important;
    }

    .filter-status-pill{
      min-height:32px;
      display:inline-flex;
      align-items:center;
      gap:7px;
      padding:6px 12px;
      border:1px solid #cfe0ff;
      border-radius:999px;
      color:#365dad;
      background:#edf4ff;
      font-size:.72rem;
      font-weight:850;
      box-shadow:0 6px 15px rgba(59,91,166,.1);
    }

    .filter-status-pill::before{
      content:"";
      width:8px;
      height:8px;
      flex:0 0 8px;
      border-radius:50%;
      background:currentColor;
      opacity:.75;
    }

    .filter-status-pill.status-open{color:#57708f;background:#f1f5f9;border-color:#dce5ef}
    .filter-status-pill.status-follow{color:#a76d00;background:#fff8df;border-color:#f5dda0}
    .filter-status-pill.status-rejected{color:#c63d57;background:#fff0f3;border-color:#f5c8d1}
    .filter-status-pill.status-closing{color:#2766cb;background:#edf4ff;border-color:#bed5ff}

    .prospect-list-title{
      font-weight:900;
      font-size:1.06rem;
      color:#111827;
      margin-bottom:14px;
      letter-spacing:-.02em;
    }

    .prospect-modern-card{
      border:1px solid #edf2f7;
      border-radius:26px;
      background:linear-gradient(180deg,#ffffff 0%,#fcfdff 100%);
      box-shadow:0 12px 24px rgba(15,23,42,.05);
      padding:16px;
      margin-bottom:12px;
      position:relative;
      overflow:hidden;
    }

    .prospect-modern-card::before{
      display:none;
    }

    .prospect-modern-card::after{
      content:"";
      position:absolute;
      right:-22px;
      bottom:-22px;
      width:90px;
      height:90px;
      border-radius:999px;
      background:radial-gradient(circle, rgba(59,130,246,.08) 0%, rgba(59,130,246,0) 72%);
      pointer-events:none;
    }

    .prospect-card-layout{
      display:flex;
      justify-content:space-between;
      gap:16px;
    }

    .prospect-card-content{
      min-width:0;
      flex:1 1 auto;
    }

    .prospect-card-actions{
      width:145px;
      min-width:145px;
      display:flex;
      flex-direction:column;
      gap:8px;
    }

    .prospect-name{
      font-weight:900;
      color:#0f172a;
      font-size:1.02rem;
      line-height:1.2;
    }

    .prospect-meta{
      color:#64748b;
      font-size:.86rem;
      margin-top:6px;
      line-height:1.65;
    }

    .badge-soft{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      min-height:36px;
      padding:7px 15px;
      border-radius:999px;
      font-weight:800;
      font-size:.78rem;
      letter-spacing:.01em;
      white-space:nowrap;
      border:1px solid transparent;
    }

    .status-open{
      background:linear-gradient(135deg,#f6c0c0 0%,#efaaaa 100%);
      color:#fff;
      box-shadow:0 10px 20px rgba(239,170,170,.22);
    }

    .status-follow{
      background:linear-gradient(135deg,#17b07d 0%,#10a36f 100%);
      color:#fff;
      box-shadow:0 10px 20px rgba(16,163,111,.20);
    }

    .status-rejected{
      background:linear-gradient(135deg,#f34f74 0%,#eb2e5c 100%);
      color:#fff;
      box-shadow:0 10px 20px rgba(235,46,92,.20);
    }

    .status-closing{
      background:linear-gradient(135deg,#5b97f6 0%,#2f6fe6 100%);
      color:#fff;
      box-shadow:0 10px 20px rgba(47,111,230,.20);
    }

    .produk-kredit{
      background:linear-gradient(135deg,#60a5fa 0%,#3b82f6 100%);
      color:#fff;
      box-shadow:0 10px 20px rgba(59,130,246,.16);
    }

    .produk-tabungan{
      background:linear-gradient(135deg,#14b8a6 0%,#10b981 100%);
      color:#fff;
      box-shadow:0 10px 20px rgba(16,185,129,.16);
    }

    .produk-deposito{
      background:linear-gradient(135deg,#22c55e 0%,#16a34a 100%);
      color:#fff;
      box-shadow:0 10px 20px rgba(34,197,94,.16);
    }

    .produk-aset{
      background:linear-gradient(135deg,#f59e0b 0%,#f97316 100%);
      color:#fff;
      box-shadow:0 10px 20px rgba(249,115,22,.16);
    }

    .produk-default{
      background:linear-gradient(135deg,#cbd5e1 0%,#94a3b8 100%);
      color:#334155;
      box-shadow:0 10px 20px rgba(148,163,184,.18);
    }

    .open-age-badge{
      display:inline-flex;
      align-items:center;
      gap:6px;
      min-height:34px;
      padding:6px 13px;
      border-radius:999px;
      font-weight:800;
      font-size:.76rem;
      letter-spacing:.01em;
      white-space:nowrap;
      border:1px solid transparent;
      box-shadow:0 10px 20px rgba(15,23,42,.08);
    }

    .open-age-green{
      background:linear-gradient(135deg,#22c55e 0%,#16a34a 100%);
      color:#fff;
      box-shadow:0 10px 20px rgba(34,197,94,.18);
    }

    .open-age-yellow{
      background:linear-gradient(135deg,#facc15 0%,#eab308 100%);
      color:#3b2f00;
      box-shadow:0 10px 20px rgba(234,179,8,.18);
    }

    .open-age-red{
      background:linear-gradient(135deg,#fb7185 0%,#ef4444 100%);
      color:#fff;
      box-shadow:0 10px 20px rgba(239,68,68,.18);
    }

    .action-btn-modern{
      display:flex;
      align-items:center;
      justify-content:center;
      gap:5px;
      border-radius:999px;
      font-weight:800;
      padding:.6rem 1rem;
      min-height:42px;
      box-shadow:none !important;
    }

    .taken-badge{
      color:#3f55a5;
      background:#eef2ff;
      border-color:#d8e0ff;
      box-shadow:none;
    }

    .untaken-badge{
      color:#6b7280;
      background:#f3f4f6;
      border-color:#e5e7eb;
      box-shadow:none;
    }

    .section-shell{
      margin-top:18px;
    }

    .mobile-scroll-row{
      display:flex;
      gap:14px;
      overflow-x:auto;
      padding-bottom:4px;
      scroll-snap-type:x mandatory;
      -webkit-overflow-scrolling:touch;
    }

    .mobile-scroll-row::-webkit-scrollbar{
      display:none;
    }

    .catalog-card{
      min-width:294px;
      max-width:294px;
      background:#fff;
      border-radius:26px;
      overflow:hidden;
      border:1px solid #edf2f7;
      box-shadow:0 14px 28px rgba(15,23,42,.08);
      scroll-snap-align:start;
      flex:0 0 294px;
      position:relative;
    }

    .catalog-card::after{
      content:"";
      position:absolute;
      right:-22px;
      top:-22px;
      width:90px;
      height:90px;
      border-radius:999px;
      background:radial-gradient(circle, rgba(59,130,246,.08) 0%, rgba(59,130,246,0) 70%);
      pointer-events:none;
    }

    .catalog-top{
      display:flex;
      min-height:132px;
    }

    .catalog-visual{
      width:94px;
      flex:0 0 94px;
      background:
        linear-gradient(135deg, rgba(20,184,166,.10), rgba(37,99,235,.04)),
        repeating-linear-gradient(135deg, #14b8a6 0 2px, transparent 2px 22px),
        repeating-linear-gradient(45deg, #0ea5e9 0 2px, transparent 2px 22px);
      background-color:#f8fbff;
      border-right:1px solid #e5eefb;
      position:relative;
      overflow:hidden;
    }

    .catalog-image img{
      width:100%;
      height:100%;
      object-fit:cover;
      display:block;
    }

    .catalog-body{
      padding:14px 14px 12px 14px;
      flex:1 1 auto;
    }

    .catalog-badge{
      display:inline-flex;
      align-items:center;
      gap:6px;
      background:linear-gradient(135deg,#e0f2fe 0%,#dbeafe 100%);
      color:#0f52d6;
      border:1px solid #dbe4ff;
      border-radius:999px;
      font-size:.7rem;
      font-weight:800;
      padding:5px 10px;
      margin-bottom:8px;
    }

    .catalog-title{
      font-size:1.22rem;
      font-weight:900;
      color:#111827;
      line-height:1.15;
      margin-bottom:6px;
      letter-spacing:-.02em;
    }

    .catalog-desc{
      color:#374151;
      font-size:.9rem;
      line-height:1.38;
      display:-webkit-box;
      -webkit-line-clamp:3;
      -webkit-box-orient:vertical;
      overflow:hidden;
      min-height:54px;
    }

    .catalog-footer{
      display:flex;
      align-items:center;
      justify-content:flex-end;
      padding:0 14px 14px 14px;
    }

    .catalog-link{
      color:#0f52d6;
      text-decoration:none;
      font-weight:800;
      font-size:.88rem;
    }

    .tips-card{
      min-width:220px;
      max-width:220px;
      background:#fff;
      border-radius:24px;
      overflow:hidden;
      border:1px solid #edf2f7;
      box-shadow:0 14px 26px rgba(15,23,42,.08);
      scroll-snap-align:start;
      flex:0 0 220px;
      position:relative;
    }

    .tips-card::before{
      content:"";
      position:absolute;
      top:0;
      left:0;
      right:0;
      height:4px;
      background:linear-gradient(90deg,#14b8a6 0%,#3b82f6 50%,#8b5cf6 100%);
    }

    .tips-image{
      height:112px;
      background:
        radial-gradient(circle at 18% 24%, rgba(20,184,166,.22), transparent 18%),
        radial-gradient(circle at 70% 30%, rgba(37,99,235,.18), transparent 18%),
        linear-gradient(180deg,#ffffff 0%,#f8fafc 100%);
      display:flex;
      align-items:center;
      justify-content:center;
      overflow:hidden;
    }

    .tips-image img{
      width:100%;
      height:100%;
      object-fit:cover;
      display:block;
    }

    .tips-icon-placeholder{
      font-size:40px;
      color:#cbd5e1;
    }

    .tips-body{
      padding:14px;
    }

    .tips-kategori{
      font-size:.72rem;
      font-weight:800;
      color:#0f52d6;
      margin-bottom:8px;
      text-transform:uppercase;
      letter-spacing:.04em;
    }

    .tips-title{
      font-size:.98rem;
      font-weight:800;
      color:#111827;
      line-height:1.28;
      min-height:38px;
      margin-bottom:6px;
    }

    .tips-desc{
      color:#4b5563;
      font-size:.84rem;
      line-height:1.42;
      display:-webkit-box;
      -webkit-line-clamp:3;
      -webkit-box-orient:vertical;
      overflow:hidden;
      min-height:50px;
    }

    .tips-link{
      display:inline-flex;
      align-items:center;
      gap:6px;
      color:#0f52d6;
      text-decoration:none;
      font-weight:800;
      font-size:.84rem;
      margin-top:10px;
    }

    .empty-mini-card{
      border:1px dashed #dbe3ee;
      border-radius:24px;
      padding:18px;
      color:#64748b;
      background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
    }

    .mobile-flyer-wrap{
      display:none;
    }

    .flyer-media{
      position:absolute;
      inset:0;
      z-index:1;
    }

    .flyer-media img{
      width:100%;
      height:100%;
      object-fit:cover;
      display:block;
    }

    .flyer-overlay{
      position:absolute;
      inset:0;
      z-index:2;
      background:linear-gradient(135deg, rgba(15,23,42,.42) 0%, rgba(37,99,235,.24) 42%, rgba(0,0,0,.08) 100%);
    }

    .flyer-card > *:not(.flyer-media):not(.flyer-overlay){
      position:relative;
      z-index:3;
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

    .mobile-fab-stack .mobile-fab-add,
    .mobile-fab-stack .mobile-fab-ai{
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

    .mobile-fab-stack .mobile-fab-add{
      width:72px;
      height:72px;
      background:rgba(255,255,255,.08);
    }

    .mobile-fab-stack .mobile-fab-ai{
      width:72px;
      height:72px;
      background:rgba(255,255,255,.08);
    }

    .mobile-fab-stack .mobile-fab-add:active,
    .mobile-fab-stack .mobile-fab-ai:active{
      transform:scale(.94);
    }

    .mobile-fab-stack .mobile-fab-add i,
    .mobile-fab-stack .mobile-fab-ai i{
      position:relative;
      z-index:1;
      filter:drop-shadow(0 1px 2px rgba(31,45,70,.14));
    }

    .mobile-fab-stack .mobile-fab-add i{
      font-size:1.7rem;
    }

    .mobile-fab-stack .mobile-fab-ai i{
      font-size:1.35rem;
    }

    @media (min-width: 768px){
      .mobile-scroll-row{
        overflow-x:visible;
        flex-wrap:wrap;
        display:grid;
        grid-template-columns:repeat(3, minmax(0, 1fr));
        gap:16px;
      }

      .catalog-card,
      .tips-card{
        min-width:0;
        max-width:none;
        width:100%;
        flex:auto;
      }

      .mobile-fab-stack,
      .mobile-inline-actions{
        display:none !important;
      }
    }

    @media (max-width: 767.98px){
      html, body{
        background:linear-gradient(180deg,#f5fbff 0%, #eef4fb 48%, #eaf1f8 100%) !important;
      }

      .android-app-shell{
        padding-bottom:82px;
      }

      .page-topbar{
        display:none;
      }

      .app-page-head{
        margin-bottom:8px;
      }

      .app-title{
        font-size:1.16rem;
        line-height:1.12;
        letter-spacing:-.02em;
      }

      .app-subtitle{
        max-width:330px;
        margin-top:4px;
        font-size:.72rem;
        line-height:1.45;
      }

      .desktop-add-btn,
      .desktop-ai-btn{
        display:none !important;
      }

      .mobile-inline-actions{
        display:flex;
        gap:10px;
        margin-top:12px;
        flex-wrap:wrap;
      }

      .mobile-inline-actions .btn-add-modern,
      .mobile-inline-actions .mobile-inline-ai{
        flex:1 1 calc(50% - 5px);
        justify-content:center;
      }

      .mobile-flyer-wrap{
        display:block;
        margin:0 -10px 14px;
      }

      .flyer-shell{
        position:relative;
        border-radius:28px;
        overflow:hidden;
        background:rgba(255,255,255,.62);
        box-shadow:0 16px 34px rgba(31,45,70,.12);
        border:1px solid rgba(255,255,255,.72);
      }

      .prospect-story-carousel,
      .prospect-story-carousel .carousel-inner,
      .prospect-story-carousel .carousel-item{
        height:250px;
      }

      .prospect-story-carousel .carousel-item{
        padding:0;
        backface-visibility:hidden;
      }

      .prospect-banner-stage{
        max-height:0;
        margin:0 10px;
        overflow:hidden;
        opacity:0;
        transform:translateY(-24px);
        pointer-events:none;
        transition:
          max-height .62s cubic-bezier(.22,1,.36,1),
          margin .5s ease,
          opacity .32s ease,
          transform .58s cubic-bezier(.22,1,.36,1);
      }

      .mobile-flyer-wrap.is-collapsed .prospect-banner-stage{
        max-height:270px;
        margin-top:12px;
        opacity:1;
        transform:translateY(0);
        pointer-events:auto;
      }

      .prospect-mobile-hero{
        position:relative;
        height:250px;
        overflow:hidden;
        isolation:isolate;
        padding:12px 14px 25px;
        border-radius:0 0 32px 32px;
        color:#fff;
        background:linear-gradient(142deg,#7196e5 0%,#5d78d6 55%,#4b61c4 100%);
        touch-action:pan-y;
        overflow-anchor:none;
        transition:
          height .46s cubic-bezier(.2,.82,.24,1),
          padding .46s cubic-bezier(.2,.82,.24,1),
          border-radius .4s ease,
          box-shadow .4s ease,
          opacity .24s ease,
          transform .4s cubic-bezier(.2,.82,.24,1);
      }

      .mobile-flyer-wrap.is-collapsed .prospect-mobile-hero,
      .mobile-flyer-wrap.is-scroll-collapsed .prospect-mobile-hero{
        height:72px;
        padding:10px 14px;
        border-radius:0 0 25px 25px;
        box-shadow:0 13px 28px rgba(53,70,157,.22);
      }

      .mobile-flyer-wrap:not(.is-collapsed){
        position:sticky;
        top:0;
        z-index:10010;
      }

      .mobile-flyer-wrap.is-collapsed{
        position:relative;
      }

      .mobile-flyer-wrap.is-scroll-hidden .prospect-mobile-hero{
        height:0;
        padding-top:0;
        padding-bottom:0;
        border-width:0;
        opacity:0;
        transform:translateY(-100%);
        pointer-events:none;
      }

      .mobile-flyer-wrap.hero-reentering .prospect-mobile-hero{
        animation:prospectHeroReturn .64s cubic-bezier(.16,1,.3,1);
      }

      @keyframes prospectHeroReturn{
        from{opacity:.35;transform:translateY(-100%)}
        to{opacity:1;transform:translateY(0)}
      }

      .prospect-mobile-hero::after{
        content:"";position:absolute;z-index:-1;left:-12%;right:-12%;bottom:32px;height:72px;
        border-radius:48% 52% 46% 54% / 62% 44% 56% 38%;
        background:rgba(255,255,255,.09);transform:rotate(-7deg);
      }

      .prospect-hero-art{
        position:absolute;z-index:-1;right:-4px;bottom:0;width:57%;height:100%;
        object-fit:cover;object-position:61% 55%;
        -webkit-mask-image:linear-gradient(90deg,transparent 0%,#000 24%,#000 100%);
        mask-image:linear-gradient(90deg,transparent 0%,#000 24%,#000 100%);
        will-change:transform,opacity;
        backface-visibility:hidden;
        transition:opacity .34s ease,transform .62s cubic-bezier(.16,1,.3,1);
      }

      .prospect-hero-toolbar{
        position:relative;z-index:4;display:flex;align-items:center;justify-content:flex-end;gap:8px;
      }

      .prospect-hero-compact{
        position:absolute;left:14px;top:50%;z-index:4;
        display:flex;align-items:center;gap:10px;
        opacity:0;transform:translateY(-50%) translateX(-16px);
        pointer-events:none;
        will-change:transform,opacity;
        backface-visibility:hidden;
        transition:opacity .3s ease .12s,transform .54s cubic-bezier(.16,1,.3,1) .08s;
      }

      .prospect-hero-compact-icon{
        width:40px;height:40px;display:grid;place-items:center;border:1px solid rgba(255,255,255,.2);
        border-radius:14px;color:#fff;background:rgba(255,255,255,.12);font-size:18px;
      }

      .prospect-hero-compact-title{font-size:12px;font-weight:900}
      .prospect-hero-compact-sub{margin-top:2px;color:rgba(255,255,255,.68);font-size:8px}

      .prospect-hero-notification{
        width:38px;height:38px;display:grid;place-items:center;padding:0;
        border:1px solid rgba(255,255,255,.2);border-radius:50%;
        color:#fff;background:rgba(255,255,255,.1);
      }

      .prospect-hero-notification .notification-bell-root{
        position:static !important;width:100%;height:100%;display:grid;place-items:center;
      }

      .prospect-hero-notification .notification-bell-trigger{
        width:100% !important;height:100% !important;min-width:0 !important;
        display:grid !important;place-items:center !important;margin:0 !important;padding:0 !important;
        border:0 !important;border-radius:50% !important;outline:0 !important;
        color:#fff !important;background:transparent !important;box-shadow:none !important;
        -webkit-tap-highlight-color:transparent;
      }

      .prospect-hero-notification .notification-bell-trigger > i{
        display:block;margin:0;font-size:17px;line-height:1;
      }

      .prospect-hero-notification .notification-bell-trigger .badge{
        top:-3px !important;right:-5px !important;left:auto !important;
        min-width:18px;padding:4px 5px !important;transform:none !important;border:2px solid #6785dc;
      }

      .prospect-hero-notification .notification-panel{
        position:fixed !important;top:60px !important;right:12px !important;left:12px !important;
        width:auto !important;max-width:none !important;max-height:calc(100dvh - 84px) !important;
        margin:0 !important;overflow:hidden !important;border:1px solid rgba(255,255,255,.3) !important;
        border-radius:24px !important;color:#fff !important;
        background:linear-gradient(142deg,#7196e5 0%,#5d78d6 55%,#4b61c4 100%) !important;
        box-shadow:0 24px 60px rgba(34,47,111,.34) !important;z-index:11050 !important;
      }

      .prospect-hero-notification .notification-panel .text-muted{color:rgba(255,255,255,.74) !important}
      .prospect-hero-notification .notification-panel-head,
      .prospect-hero-notification .notification-panel-item{border-color:rgba(255,255,255,.16) !important}
      .prospect-hero-notification .notification-panel-item,
      .prospect-hero-notification .notification-panel-item.bg-light{background:rgba(255,255,255,.09) !important}

      .prospect-hero-avatar{
        width:40px;height:40px;display:grid;place-items:center;border:3px solid rgba(255,255,255,.5);
        border-radius:50%;color:#4b61c4;background:#f3f5ff;font-weight:900;text-decoration:none;
        box-shadow:0 7px 18px rgba(1,13,43,.24);
      }

      .prospect-hero-copy{
        position:relative;z-index:3;width:57%;margin-top:16px;
        will-change:transform,opacity;
        backface-visibility:hidden;
        transition:opacity .34s ease,transform .62s cubic-bezier(.16,1,.3,1);
      }

      .prospect-hero-eyebrow{
        color:#e3e8ff;font-size:8px;font-weight:850;letter-spacing:.055em;text-transform:uppercase;
      }

      .prospect-hero-title{
        margin:5px 0 4px;font-size:22px;font-weight:950;line-height:1.05;letter-spacing:-.03em;
      }

      .prospect-hero-desc{
        color:rgba(244,247,255,.82);font-size:9px;line-height:1.45;
      }

      .prospect-hero-cta{
        display:inline-flex;align-items:center;gap:6px;margin-top:13px;padding:8px 11px;border-radius:999px;
        color:#4056b2;background:#fff;text-decoration:none;font-size:9px;font-weight:850;
      }

      .prospect-hero-swipe-hint{
        position:absolute;
        right:16px;
        bottom:12px;
        z-index:4;
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:5px 9px;
        border:1px solid rgba(255,255,255,.2);
        border-radius:999px;
        color:rgba(255,255,255,.7);
        background:rgba(255,255,255,.1);
        box-shadow:inset 0 1px 0 rgba(255,255,255,.1);
        backdrop-filter:blur(5px);
        -webkit-backdrop-filter:blur(5px);
        font-size:8px;
        font-weight:800;
        letter-spacing:.08em;
        text-transform:uppercase;
        pointer-events:none;
        transition:opacity .24s ease,transform .4s cubic-bezier(.22,1,.36,1);
      }

      .prospect-hero-swipe-arrows{
        display:inline-block;
        font-size:11px;
        letter-spacing:-2px;
        animation:prospectSwipeHint 1.35s ease-in-out infinite;
      }

      @keyframes prospectSwipeHint{
        0%,100%{opacity:.45;transform:translateX(-2px)}
        50%{opacity:1;transform:translateX(2px)}
      }

      @media (prefers-reduced-motion:reduce){
        .prospect-hero-swipe-arrows{animation:none}
      }

      .mobile-flyer-wrap.is-collapsed .prospect-hero-art,
      .mobile-flyer-wrap.is-collapsed .prospect-hero-copy,
      .mobile-flyer-wrap.is-collapsed .prospect-hero-swipe-hint,
      .mobile-flyer-wrap.is-collapsed .prospect-mobile-hero::after,
      .mobile-flyer-wrap.is-scroll-collapsed .prospect-hero-art,
      .mobile-flyer-wrap.is-scroll-collapsed .prospect-hero-copy,
      .mobile-flyer-wrap.is-scroll-collapsed .prospect-hero-swipe-hint,
      .mobile-flyer-wrap.is-scroll-collapsed .prospect-mobile-hero::after{
        opacity:0;
        transform:translateY(-35px);
        pointer-events:none;
      }

      .mobile-flyer-wrap.is-collapsed .prospect-hero-compact,
      .mobile-flyer-wrap.is-scroll-collapsed .prospect-hero-compact{
        opacity:1;
        transform:translateY(-50%) translateX(0);
      }

      .prospect-story-carousel .prospect-banner-slide{
        padding:10px 9px 18px;
      }

      .flyer-card{
        position:relative;
        overflow:hidden;
        height:222px;
        border-radius:20px;
        padding:18px 15px;
        color:#fff;
        box-shadow:0 14px 30px rgba(15,23,42,.12);
      }

      .flyer-card.bg-1{
        background:linear-gradient(135deg,#14b8a6 0%,#06b6d4 45%,#3b82f6 100%);
      }

      .flyer-card.bg-2{
        background:linear-gradient(135deg,#8b5cf6 0%,#6366f1 52%,#06b6d4 100%);
      }

      .flyer-card.bg-3{
        background:linear-gradient(135deg,#0ea5e9 0%,#14b8a6 40%,#22c55e 100%);
      }

      .flyer-badge{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:5px 9px;
        border-radius:999px;
        background:rgba(255,255,255,.18);
        border:1px solid rgba(255,255,255,.20);
        backdrop-filter:blur(6px);
        font-size:.62rem;
        font-weight:800;
      }

      .flyer-title{
        margin-top:12px;
        font-size:1.12rem;
        font-weight:900;
        line-height:1.25;
        letter-spacing:-.02em;
        max-width:62%;
      }

      .flyer-desc{
        margin-top:7px;
        max-width:62%;
        font-size:.72rem;
        line-height:1.4;
        color:rgba(255,255,255,.93);
      }

      .flyer-btn{
        position:absolute !important;
        left:15px;
        bottom:17px;
        margin:0;
        display:inline-flex;
        align-items:center;
        gap:5px;
        padding:6px 9px;
        border-radius:999px;
        background:#fff;
        color:#0f52d6;
        text-decoration:none;
        font-weight:800;
        font-size:.6rem;
        box-shadow:0 7px 16px rgba(15,23,42,.13);
      }

      .flyer-illustration{
        position:absolute;
        right:8px;
        bottom:0;
        width:96px;
        height:96px;
        opacity:.95;
        display:flex;
        align-items:flex-end;
        justify-content:center;
        font-size:48px;
      }

      .flyer-shell .carousel-indicators{
        margin-bottom:7px;
        z-index:8;
      }

      .flyer-shell .carousel-indicators [data-bs-target]{
        width:8px;
        height:8px;
        border-radius:999px;
        border:0;
        background:#cbd5e1;
        opacity:1;
      }

      .flyer-shell .carousel-indicators .active{
        width:22px;
        background:#14b8a6;
      }

      .glass-head{
        padding:12px;
        border:1px solid #dbe7f8;
        border-radius:20px;
        margin-bottom:14px;
        background:#fff;
        box-shadow:0 11px 27px rgba(20,42,82,.07);
      }

      .glass-head::after{
        display:none;
      }

      .desktop-summary-row{
        display:none;
      }

      .glass-head > .d-md-none{
        display:grid !important;
        grid-template-columns:repeat(5,minmax(0,1fr));
        gap:5px;
      }

      .mobile-summary-total{
        display:contents;
      }

      .mobile-summary-total .summary-btn{
        min-height:96px;
      }

      .mobile-summary-grid{
        display:contents;
      }

      .summary-btn{
        --summary-accent:#2f73e8;
        min-width:0;
        min-height:96px;
        padding:49px 5px 7px;
        border:1px solid color-mix(in srgb,var(--summary-accent) 24%,white) !important;
        border-bottom:2px solid var(--summary-accent) !important;
        border-radius:15px;
        color:#142a52 !important;
        background:linear-gradient(180deg,#fff 0%,color-mix(in srgb,var(--summary-accent) 8%,white) 100%) !important;
        box-shadow:
          0 6px 15px rgba(47,115,232,.07),
          inset 0 1px 0 rgba(255,255,255,.85);
      }

      .summary-btn.is-active{
        border-color:color-mix(in srgb,var(--summary-accent) 58%,white) !important;
        border-bottom-color:var(--summary-accent) !important;
        background:color-mix(in srgb,var(--summary-accent) 14%,white) !important;
        box-shadow:
          0 0 0 2px color-mix(in srgb,var(--summary-accent) 16%,transparent),
          0 8px 18px color-mix(in srgb,var(--summary-accent) 16%,transparent) !important;
      }

      .summary-value{
        margin-top:4px;
        color:var(--summary-accent);
        font-size:1.1rem;
      }

      .summary-label{
        overflow:hidden;
        color:#53617b;font-size:.47rem;
        line-height:1.2;
        white-space:nowrap;
        text-overflow:ellipsis;
      }

      .summary-icon{
        left:6px;right:auto;top:6px;bottom:auto;width:39px;height:39px;
        display:grid;place-items:center;border:1px solid color-mix(in srgb,var(--summary-accent) 24%,white);border-radius:10px;
        color:var(--summary-accent);background:color-mix(in srgb,var(--summary-accent) 11%,white);
        opacity:1;
      }

      .summary-icon .summary-svg-icon{
        width:27px;
        height:27px;
      }

      .filter-card-modern{
        border:1px solid rgba(188,199,225,.48);
        border-radius:24px;
        padding:12px 11px;
        background:rgba(255,255,255,.72);
        box-shadow:0 12px 26px rgba(31,45,70,.07);
      }

      .filter-card-modern .mobile-filter-toggle-slot{
        margin-bottom:11px !important;
      }

      .filter-card-modern .mobile-filter-extra + .mobile-filter-extra{
        margin-top:9px;
      }

      .filter-status-pill{
        min-height:28px;
        padding:5px 10px;
        font-size:.58rem;
      }

      .catalog-card{
        min-width:82vw;
        max-width:82vw;
        flex:0 0 82vw;
        border-radius:26px;
        border:1px solid rgba(184,198,226,.52);
        background:rgba(255,255,255,.78);
        box-shadow:0 13px 29px rgba(31,45,70,.09);
      }

      .tips-card{
        min-width:74vw;
        max-width:74vw;
        flex:0 0 74vw;
        border-radius:26px;
        border:1px solid rgba(184,198,226,.52);
        background:rgba(255,255,255,.8);
        box-shadow:0 13px 29px rgba(31,45,70,.09);
      }

      .prospect-modern-card{
        border:1px solid rgba(184,198,226,.5);
        border-radius:21px;
        padding:13px 12px 12px;
        background:
          radial-gradient(circle at 100% 0,rgba(85,116,215,.08),transparent 26%),
          linear-gradient(180deg,rgba(255,255,255,.96),rgba(249,252,255,.94));
        box-shadow:0 10px 24px rgba(31,45,70,.09),inset 0 1px 0 rgba(255,255,255,.9);
      }

      .prospect-modern-card::before{
        top:13px;bottom:13px;left:0;width:4px;border-radius:0 8px 8px 0;
      }

      .prospect-modern-card::after{
        right:-30px;bottom:-34px;width:105px;height:105px;
        background:radial-gradient(circle,rgba(84,112,210,.09) 0%,rgba(84,112,210,0) 70%);
      }

      .prospect-card-layout{
        display:block;
      }

      .prospect-name{
        max-width:65%;
        color:#17213a;
        font-size:.82rem;
        letter-spacing:.005em;
      }

      .prospect-meta{
        margin-top:9px;
        padding:8px 9px;
        border:1px solid rgba(218,226,239,.72);
        border-radius:13px;
        color:#63738d;
        background:rgba(247,250,254,.82);
        line-height:1.52;
      }

      .prospect-meta > div:not(:last-child){
        margin-bottom:2px;
      }

      .prospect-meta .bi{
        color:#7286a6;
      }

      .prospect-card-actions{
        position:relative;
        z-index:1;
        width:100%;
        min-width:0;
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:8px;
        margin-top:10px;
        padding-top:10px;
        border-top:1px solid rgba(218,226,239,.8);
      }

      .prospect-card-actions.is-single{
        grid-template-columns:1fr;
      }

      .prospect-card-actions .action-btn-modern{
        min-height:34px !important;
        padding:5px 8px !important;
        border-radius:11px !important;
        font-size:.61rem !important;
        line-height:1.1;
      }

      .prospect-card-actions .action-btn-modern i{
        margin-right:0 !important;
        font-size:.68rem;
      }

      .section-shell{
        margin-top:14px;
      }

      .section-title-modern,
      .prospect-list-title{
        display:flex;align-items:center;gap:6px;margin-bottom:8px;
        color:#1b2947;font-size:.7rem;font-weight:900;
      }

      .section-title-modern::before,
      .prospect-list-title::before{
        content:"";width:6px;height:6px;border-radius:2px;background:#5d78d6;
        box-shadow:0 0 0 4px rgba(93,120,214,.11);
      }

      .catalog-top{
        min-height:108px;
      }

      .catalog-visual{
        width:78px;flex-basis:78px;
      }

      .catalog-footer{
        padding:6px 9px 9px;border-top:1px solid rgba(221,228,241,.72);
      }

      .tips-image{
        height:92px;
      }

      .mobile-scroll-row{
        gap:9px;margin:0 -2px;padding:2px 2px 9px;
        scroll-padding-left:2px;
      }

      .catalog-card{
        min-width:66vw;
        max-width:66vw;
        flex-basis:66vw;
        border-radius:20px;
      }

      .catalog-body{
        padding:9px 9px 7px;
      }

      .catalog-badge{
        gap:4px;
        margin-bottom:5px;
        padding:3px 6px;
        font-size:.5rem;
      }

      .catalog-title{
        margin-bottom:4px;
        font-size:.76rem;
      }

      .catalog-desc{
        min-height:34px;
        font-size:.58rem;
        line-height:1.35;
        -webkit-line-clamp:2;
      }

      .catalog-link{
        font-size:.58rem;
      }

      .tips-card{
        min-width:58vw;
        max-width:58vw;
        flex-basis:58vw;
        border-radius:20px;
      }

      .tips-body{
        padding:9px;
      }

      .tips-kategori{
        margin-bottom:5px;
        font-size:.5rem;
      }

      .tips-title{
        min-height:28px;
        margin-bottom:4px;
        font-size:.68rem;
      }

      .tips-desc{
        min-height:34px;
        font-size:.56rem;
        line-height:1.35;
        -webkit-line-clamp:2;
      }

      .tips-link{
        margin-top:6px;
        font-size:.56rem;
      }

      .prospect-modern-card,
      .catalog-card,
      .tips-card{
        content-visibility:auto;
        contain-intrinsic-size:260px;
      }

      .empty-mini-card{
        padding:12px;
        border-radius:17px;
        font-size:.64rem;
      }

      .prospect-modern-card{
        padding:11px 10px 10px;
        border-radius:18px;
      }

      .prospect-name{
        font-size:.76rem;
      }

      .prospect-meta{
        margin-top:7px;
        padding:7px 8px;
        font-size:.58rem;
        line-height:1.45;
      }

      .badge-soft,
      .open-age-badge{
        min-height:25px;
        padding:4px 8px;
        font-size:.52rem;
      }

      .action-btn-modern{
        min-height:34px;
        padding:5px 8px;
        font-size:.56rem;
      }

      .mobile-fab-stack{
        right:max(8px,calc(50% - 245px));
        bottom:calc(10px + env(safe-area-inset-bottom));
        gap:10px;
      }
    }

    .prospect-flash-layer{
      position:fixed;
      inset:0;
      z-index:12060;
      display:grid;
      place-items:center;
      padding:24px;
      background:rgba(15,23,42,.28);
      backdrop-filter:blur(3px);
      -webkit-backdrop-filter:blur(3px);
      animation:prospectFlashBackdropIn .2s ease both;
    }

    .prospect-flash-card{
      width:min(340px,100%);
      padding:24px 22px 20px;
      border:1px solid rgba(255,255,255,.82);
      border-radius:26px;
      color:#17213a;
      background:linear-gradient(180deg,#fff 0%,#f8fbff 100%);
      box-shadow:0 24px 70px rgba(15,23,42,.28);
      text-align:center;
      animation:prospectFlashCardIn .3s cubic-bezier(.16,1,.3,1) both;
    }

    .prospect-flash-icon{
      width:64px;
      height:64px;
      display:grid;
      place-items:center;
      margin:0 auto 14px;
      border-radius:22px;
      font-size:28px;
    }

    .prospect-flash-card.is-success .prospect-flash-icon{
      color:#078554;
      background:linear-gradient(145deg,#dff8ec,#c9f1df);
      box-shadow:0 12px 26px rgba(16,185,129,.18);
    }

    .prospect-flash-card.is-warning .prospect-flash-icon{
      color:#b96b09;
      background:linear-gradient(145deg,#fff2cb,#ffe6a7);
      box-shadow:0 12px 26px rgba(245,158,11,.18);
    }

    .prospect-flash-title{
      margin:0;
      color:#111a31;
      font-size:19px;
      font-weight:900;
      line-height:1.2;
    }

    .prospect-flash-message{
      margin:7px 0 18px;
      color:#63728a;
      font-size:12px;
      line-height:1.55;
    }

    .prospect-flash-close{
      width:100%;
      min-height:46px;
      border:0;
      border-radius:15px;
      color:#fff;
      background:linear-gradient(135deg,#2f74ed,#4857c8);
      box-shadow:0 10px 22px rgba(47,92,211,.22);
      font-size:12px;
      font-weight:850;
    }

    .prospect-flash-layer.is-leaving{
      animation:prospectFlashBackdropOut .2s ease both;
    }

    .prospect-flash-layer.is-leaving .prospect-flash-card{
      animation:prospectFlashCardOut .2s ease both;
    }

    @keyframes prospectFlashBackdropIn{
      from{opacity:0}
      to{opacity:1}
    }

    @keyframes prospectFlashBackdropOut{
      from{opacity:1}
      to{opacity:0}
    }

    @keyframes prospectFlashCardIn{
      from{opacity:0;transform:translateY(18px) scale(.94)}
      to{opacity:1;transform:translateY(0) scale(1)}
    }

    @keyframes prospectFlashCardOut{
      from{opacity:1;transform:translateY(0) scale(1)}
      to{opacity:0;transform:translateY(10px) scale(.96)}
    }

    @media (max-width:767.98px){
      .prospect-flash-layer{
        padding:20px;
      }

      .prospect-flash-card{
        width:min(310px,100%);
        padding:22px 18px 18px;
        border-radius:24px;
      }

      .prospect-flash-icon{
        width:58px;
        height:58px;
        margin-bottom:12px;
        border-radius:20px;
        font-size:25px;
      }

      .prospect-flash-title{font-size:17px}
      .prospect-flash-message{font-size:11px}
    }
  </style>

  @if(session('ok'))
    <div class="prospect-flash-layer"
         data-prospect-flash
         role="status"
         aria-live="polite"
         aria-modal="true">
      <div class="prospect-flash-card is-success">
        <div class="prospect-flash-icon" aria-hidden="true">
          <i class="bi bi-check-lg"></i>
        </div>
        <h2 class="prospect-flash-title">Berhasil</h2>
        <p class="prospect-flash-message">{{ session('ok') }}</p>
        <button type="button" class="prospect-flash-close" data-prospect-flash-close>Oke</button>
      </div>
    </div>
  @endif
  @if(session('error'))
    <div class="prospect-flash-layer"
         data-prospect-flash
         role="alert"
         aria-modal="true">
      <div class="prospect-flash-card is-warning">
        <div class="prospect-flash-icon" aria-hidden="true">
          <i class="bi bi-exclamation-lg"></i>
        </div>
        <h2 class="prospect-flash-title">Perhatian</h2>
        <p class="prospect-flash-message">{{ session('error') }}</p>
        <button type="button" class="prospect-flash-close" data-prospect-flash-close>Mengerti</button>
      </div>
    </div>
  @endif

  @php
    $summaryCards = [
      ['key'=>'ALL', 'count_key'=>'TOTAL', 'label'=>'Total Pengajuan', 'icon'=>'total', 'accent'=>'#e79b17'],
      ['key'=>'OPEN', 'count_key'=>'OPEN', 'label'=>'Open', 'icon'=>'open', 'accent'=>'#e28b9c'],
      ['key'=>'FOLLOW UP', 'count_key'=>'FOLLOW UP', 'label'=>'Follow Up', 'icon'=>'follow', 'accent'=>'#27ad83'],
      ['key'=>'REJECTED', 'count_key'=>'REJECTED', 'label'=>'Rejected', 'icon'=>'rejected', 'accent'=>'#e75272'],
      ['key'=>'CLOSING', 'count_key'=>'CLOSING', 'label'=>'Closing', 'icon'=>'closing', 'accent'=>'#4e83e4'],
    ];

    $summaryIcon = function (string $icon): string {
      $svg = match ($icon) {
        'total' => '<path d="M5 7.5h14v11H5z"/><path d="M7.5 4.5h9v3h-9z"/><path d="M8 12h8M8 15h5"/>',
        'open' => '<path d="M3.5 8.5h6l2-2h9v11.5a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2z"/><path d="M3.5 11h17"/>',
        'follow' => '<path d="M19 7v5h-5"/><path d="M18.2 12A6.5 6.5 0 1 1 16 6.4"/><path d="M12 8.5V12l2.5 1.5"/>',
        'rejected' => '<path d="M6.5 3.5h8l3 3v14h-11z"/><path d="M14.5 3.5v3h3"/><path d="m9.5 12 5 5m0-5-5 5"/>',
        'closing' => '<path d="M6.5 3.5h8l3 3v14h-11z"/><path d="M14.5 3.5v3h3"/><path d="m9.5 14 2 2 4-4"/>',
        default => '<circle cx="12" cy="12" r="7"/>',
      };

      return '<span class="summary-svg-icon" aria-hidden="true"><svg viewBox="0 0 24 24">'.$svg.'</svg></span>';
    };

    $mobileTop = $summaryCards[0];
    $mobileBottom = [
      $summaryCards[1],
      $summaryCards[2],
      $summaryCards[3],
      $summaryCards[4],
    ];

    $prospectInitial = strtoupper(substr((string) (auth()->user()->nama_lengkap ?? auth()->user()->name ?? 'U'), 0, 1));

    $flyers = [];

    if (isset($katalogProduk) && $katalogProduk->count()) {
      foreach($katalogProduk->take(3)->values() as $i => $kp){
        $flyers[] = [
          'badge' => $kp->badge ?: 'Katalog Produk',
          'title' => $kp->judul ?: 'Produk Unggulan',
          'desc'  => $kp->deskripsi ?: 'Lihat detail produk unggulan untuk kebutuhan nasabah.',
          'link'  => $kp->detail_url ?? '#',
          'icon'  => $i === 0 ? 'bi-stars' : ($i === 1 ? 'bi-rocket-takeoff' : 'bi-gem'),
          'bg'    => 'bg-' . (($i % 3) + 1),
          'image' => $kp->gambar_url ?? null,
        ];
      }
    }

    if (empty($flyers)) {
      $flyers = [
        [
          'badge'=>'Produk Unggulan',
          'title'=>'Jelajahi Katalog Produk',
          'desc'=>'Temukan produk yang sesuai untuk kebutuhan nasabah dan percepat follow up harian.',
          'link'=>route('prospects.create'),
          'icon'=>'bi-stars',
          'bg'=>'bg-1',
          'image'=>null,
        ],
        [
          'badge'=>'Tips Cepat',
          'title'=>'Bangun Prospek Lebih Efektif',
          'desc'=>'Gunakan data prospek, dokumentasi, dan follow up yang rapi agar peluang closing semakin besar.',
          'link'=>route('prospects.create'),
          'icon'=>'bi-lightning-charge',
          'bg'=>'bg-2',
          'image'=>null,
        ],
        [
          'badge'=>'Mulai Sekarang',
          'title'=>'Tambah Prospek Baru',
          'desc'=>'Input prospek baru langsung dari aplikasi dan pantau perkembangannya dengan lebih mudah.',
          'link'=>route('prospects.create'),
          'icon'=>'bi-plus-circle',
          'bg'=>'bg-3',
          'image'=>null,
        ],
      ];
    }
  @endphp

  <div class="page-topbar">
    <div class="app-page-head mb-0">
      <div class="app-title">Prospek Saya</div>
      <div class="app-subtitle">Pantau prospek, jelajahi katalog produk, dan lihat tips cepat untuk meningkatkan follow up.</div>

      <div class="mobile-inline-actions">
        <a href="{{ route('ai.chat.index') }}" class="desktop-ai-btn mobile-inline-ai">
          <i class="bi bi-stars"></i> Chat AI
        </a>


      </div>
    </div>

    <div class="prospek-top-actions d-none d-md-flex">
      <a href="{{ route('ai.chat.index') }}" class="desktop-ai-btn">
        <i class="bi bi-stars"></i> Chat AI
      </a>

      <a href="{{ route('prospects.create') }}" class="btn-add-modern desktop-add-btn">
        <i class="bi bi-plus-lg"></i> Tambah Prospek
      </a>
    </div>
  </div>

  <div class="mobile-flyer-wrap d-md-none" id="prospectMobileStory">
    <section class="prospect-mobile-hero" id="prospectMobileHero" aria-label="Hero Prospek Saya">
      <img class="prospect-hero-art"
           src="{{ asset('images/mobile/prospects-hero-v1.webp') }}"
           alt=""
           aria-hidden="true">

      <div class="prospect-hero-compact" aria-hidden="true">
        <span class="prospect-hero-compact-icon"><i class="bi bi-grid"></i></span>
        <div>
          <div class="prospect-hero-compact-title">Prospek Saya</div>
          <div class="prospect-hero-compact-sub">Geser banner untuk melihat informasi</div>
        </div>
      </div>

      <div class="prospect-hero-toolbar">
        <div class="prospect-hero-notification notif-wrap" data-notif-redirect="{{ url('/prospects') }}">
          @livewire('notifications.bell', [], key('prospects-mobile-hero-bell-' . auth()->id()))
        </div>

        <a class="prospect-hero-avatar" href="{{ route('profile.index') }}" aria-label="Buka profil">
          {{ $prospectInitial }}
        </a>
      </div>

      <div class="prospect-hero-copy">
        <div class="prospect-hero-eyebrow">Pipeline Prospek Aktif</div>
        <h1 class="prospect-hero-title">Prospek Saya</h1>
        <div class="prospect-hero-desc">
          Pantau calon nasabah, lanjutkan follow up, dan jaga peluang closing tetap bergerak.
        </div>
        <a href="{{ route('prospects.create') }}" class="prospect-hero-cta">
          Tambah Prospek <i class="bi bi-arrow-right"></i>
        </a>
      </div>

      <div class="prospect-hero-swipe-hint" aria-hidden="true">
        <span>Swipe</span>
        <span class="prospect-hero-swipe-arrows">&gt;&gt;</span>
      </div>
    </section>

    <div class="prospect-banner-stage" id="prospectBannerStage" aria-hidden="true">
      <div class="flyer-shell">
        <div id="mobileFlyerCarousel"
             class="carousel slide prospect-story-carousel"
             data-bs-interval="false"
             data-bs-touch="true"
             data-bs-wrap="false">
          <div class="carousel-indicators">
            @foreach($flyers as $i => $flyer)
              <button type="button"
                      data-bs-target="#mobileFlyerCarousel"
                      data-bs-slide-to="{{ $i }}"
                      class="{{ $i === 0 ? 'active' : '' }}"
                      aria-current="{{ $i === 0 ? 'true' : 'false' }}"
                      aria-label="Banner {{ $i + 1 }}"></button>
            @endforeach
          </div>

          <div class="carousel-inner">
            @foreach($flyers as $i => $flyer)
              <div class="carousel-item prospect-banner-slide {{ $i === 0 ? 'active' : '' }}">
                <div class="flyer-card {{ $flyer['bg'] }}">
                  @if(!empty($flyer['image']))
                    <div class="flyer-media">
                      <img src="{{ $flyer['image'] }}" alt="{{ $flyer['title'] }}">
                    </div>
                    <div class="flyer-overlay"></div>
                  @endif

                  <a href="{{ $flyer['link'] }}" class="flyer-btn">
                    Lihat Sekarang <i class="bi bi-arrow-right"></i>
                  </a>

                  @if(empty($flyer['image']))
                    <div class="flyer-illustration">
                      <i class="bi {{ $flyer['icon'] }}"></i>
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="glass-head">
    <div class="d-none d-md-block">
      <div class="desktop-summary-row">
        @foreach($summaryCards as $c)
          <button
            type="button"
            wire:click="setStatus('{{ $c['key'] }}')"
            class="summary-btn {{ $status === $c['key'] ? 'is-active' : '' }}"
            style="--summary-accent:{{ $c['accent'] }}"
          >
            <div class="summary-label">{{ $c['label'] }}</div>
            <div class="summary-value">{{ $summary[$c['count_key']] ?? 0 }}</div>
            <div class="summary-icon">
              {!! $summaryIcon($c['icon']) !!}
            </div>
          </button>
        @endforeach
      </div>
    </div>

    <div class="d-md-none">
      <div class="mobile-summary-total">
        <button
          type="button"
          wire:click="setStatus('{{ $mobileTop['key'] }}')"
          class="summary-btn {{ $status === $mobileTop['key'] ? 'is-active' : '' }}"
          style="--summary-accent:{{ $mobileTop['accent'] }}"
        >
          <div class="summary-label">{{ $mobileTop['label'] }}</div>
          <div class="summary-value">{{ $summary[$mobileTop['count_key']] ?? 0 }}</div>
          <div class="summary-icon">
            {!! $summaryIcon($mobileTop['icon']) !!}
          </div>
        </button>
      </div>

      <div class="mobile-summary-grid">
        @foreach($mobileBottom as $c)
          <button
            type="button"
            wire:click="setStatus('{{ $c['key'] }}')"
            class="summary-btn {{ $status === $c['key'] ? 'is-active' : '' }}"
            style="--summary-accent:{{ $c['accent'] }}"
          >
            <div class="summary-label">{{ $c['label'] }}</div>
            <div class="summary-value">{{ $summary[$c['count_key']] ?? 0 }}</div>
            <div class="summary-icon">
              {!! $summaryIcon($c['icon']) !!}
            </div>
          </button>
        @endforeach
      </div>
    </div>
  </div>

  <div class="filter-card-modern"
       data-mobile-filter-panel
       data-mobile-filter-key="prospects-index">
    <div class="desktop-filter-row">
      <div data-mobile-filter-primary>
        <div class="input-group">
          <span class="input-group-text bg-white border-0 ps-3"><i class="bi bi-search"></i></span>
          <input
            class="form-control border-0 shadow-none"
            placeholder="Cari nama / no hp / nik / alamat..."
            wire:model.live.debounce.400ms="search"
          >
        </div>
      </div>

      <div>
        <select class="form-select" wire:model.live="periode" data-searchable-filter>
          <option value="hari_ini">Hari ini</option>
          <option value="bulan_ini">Bulan ini</option>
          <option value="semua">Semua</option>
        </select>
      </div>

      <div class="text-md-end mobile-filter-extra">
        <span class="filter-status-pill {{ match($status) {
          'OPEN' => 'status-open',
          'FOLLOW UP' => 'status-follow',
          'REJECTED' => 'status-rejected',
          'CLOSING' => 'status-closing',
          default => 'status-all',
        } }}">
          Status {{ $status === 'ALL' ? 'Semua' : $status }}
        </span>
      </div>
    </div>
  </div>

  <div class="section-shell">
    <div class="prospect-list-title">
      Pengajuan Prospek Saya ({{ $items->total() }})
    </div>

    @forelse($items as $p)
      @php
        $statusClass = 'status-open';
        if($p->status === 'FOLLOW UP') $statusClass = 'status-follow';
        elseif($p->status === 'REJECTED') $statusClass = 'status-rejected';
        elseif($p->status === 'CLOSING') $statusClass = 'status-closing';

        $produkClass = $this->getProdukClass($p->jenis_produk);

        $openDays = null;
        $openAgeClass = 'open-age-green';

        if (strtoupper((string) $p->status) === 'OPEN' && !empty($p->tanggal_prospek)) {
          $openDays = \Illuminate\Support\Carbon::parse($p->tanggal_prospek)->startOfDay()->diffInDays(now()->startOfDay());

          if ($openDays <= 7) {
            $openAgeClass = 'open-age-green';
          } elseif ($openDays <= 14) {
            $openAgeClass = 'open-age-yellow';
          } else {
            $openAgeClass = 'open-age-red';
          }
        }
      @endphp

      <div class="prospect-modern-card" wire:key="prospect-card-{{ $p->id }}">
        <div class="prospect-card-layout">
          <div class="prospect-card-content">
            <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
              <div class="prospect-name">{{ $p->nama }}</div>
              <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="badge-soft {{ $statusClass }}">
                  {{ $p->status ?: '-' }}
                </span>

                @if(!is_null($openDays))
                  <span class="open-age-badge {{ $openAgeClass }}">
                    <i class="bi bi-clock-history"></i>
                    {{ $openDays }} hari open
                  </span>
                @endif
              </div>
            </div>

            <div class="prospect-meta">
              <div>
                <i class="bi bi-telephone me-1"></i> {{ $p->no_hp ?: '-' }}
                &nbsp;•&nbsp;
                <i class="bi bi-person-vcard me-1"></i> {{ $p->nik ?: '-' }}
              </div>

              <div>
                <i class="bi bi-calendar-event me-1"></i>
                {{ \Illuminate\Support\Carbon::parse($p->tanggal_prospek)->format('d/m/Y') }}
                &nbsp;•&nbsp;
                <i class="bi bi-building me-1"></i>
                {{ $p->cabang->nama_cabang ?? '-' }}
              </div>

              @if($p->alamat)
                <div>
                  <i class="bi bi-geo-alt me-1"></i> {{ $p->alamat }}
                </div>
              @endif

              <div class="mt-2 d-flex flex-wrap gap-2">
                <span class="badge-soft {{ $produkClass }}">
                  {{ $p->jenis_produk ?: '-' }}
                </span>

                @if((int)($p->is_diambil ?? 0) === 1)
                  <span class="badge-soft taken-badge">
                    Diambil
                  </span>
                @else
                  <span class="badge-soft untaken-badge">
                    Belum Diambil
                  </span>
                @endif
              </div>
            </div>
          </div>

          <div class="prospect-card-actions {{ strtoupper(trim((string) $p->status)) === 'CLOSING' ? 'is-single' : '' }}">
            <a class="btn btn-outline-primary action-btn-modern w-100"
               href="{{ route('prospects.edit', $p->id) }}">
              <i class="bi bi-pencil-square me-1"></i> Detail
            </a>

            @if(strtoupper(trim((string) $p->status)) !== 'CLOSING')
              <button
                type="button"
                wire:click="trash({{ $p->id }})"
                onclick="return confirm('Pindahkan ke Recycle Bin?')"
                class="btn btn-outline-danger action-btn-modern w-100"
              >
                <i class="bi bi-trash me-1"></i> Hapus
              </button>
            @endif
          </div>
        </div>
      </div>
    @empty
      <div class="empty-mini-card">
        Belum ada pengajuan prospek.
      </div>
    @endforelse

    <div class="mt-3">
      {{ $items->links() }}
    </div>
  </div>

  <div class="section-shell">
    <div class="section-title-modern">Katalog Produk</div>

    @if(isset($katalogProduk) && $katalogProduk->count())
      <div class="mobile-scroll-row">
        @foreach($katalogProduk as $kp)
          <div class="catalog-card">
            <div class="catalog-top">
              <div class="catalog-visual {{ !empty($kp->gambar_url) ? 'catalog-image' : '' }}">
                @if(!empty($kp->gambar_url))
                  <img src="{{ $kp->gambar_url }}" alt="{{ $kp->judul }}">
                @endif
              </div>

              <div class="catalog-body">
                @if(!empty($kp->badge))
                  <div class="catalog-badge">
                    <i class="bi bi-stars"></i> {{ $kp->badge }}
                  </div>
                @endif

                <div class="catalog-title">{{ $kp->judul }}</div>
                <div class="catalog-desc">{{ $kp->deskripsi ?: '-' }}</div>
              </div>
            </div>

            <div class="catalog-footer">
              <a href="{{ $kp->detail_url ?? '#' }}" class="catalog-link">
                Lihat lebih banyak <i class="bi bi-chevron-right"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-mini-card">
        Belum ada konten katalog produk.
      </div>
    @endif
  </div>

  <div class="section-shell">
    <div class="section-title-modern">Tips & Trik</div>

    @if(isset($tipsTrik) && $tipsTrik->count())
      <div class="mobile-scroll-row">
        @foreach($tipsTrik as $tip)
          <div class="tips-card">
            <div class="tips-image">
              @if(!empty($tip->gambar_url))
                <img src="{{ $tip->gambar_url }}" alt="{{ $tip->judul }}">
              @else
                <div class="tips-icon-placeholder">
                  <i class="bi bi-lightbulb"></i>
                </div>
              @endif
            </div>

            <div class="tips-body">
              @if(!empty($tip->kategori))
                <div class="tips-kategori">{{ $tip->kategori }}</div>
              @endif

              <div class="tips-title">{{ $tip->judul }}</div>
              <div class="tips-desc">{{ $tip->deskripsi ?: '-' }}</div>

              <a href="{{ $tip->detail_url ?? '#' }}" class="tips-link">
                Baca tips <i class="bi bi-arrow-right-short"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-mini-card">
        Belum ada konten tips & trik.
      </div>
    @endif
  </div>

  <div class="mobile-fab-stack d-md-none">
    <a href="{{ route('ai.chat.index') }}" class="mobile-fab-ai" aria-label="Chat AI">
      <i class="bi bi-stars"></i>
    </a>

    <a href="{{ route('prospects.create') }}" class="mobile-fab-add" aria-label="Tambah Prospek">
      <i class="bi bi-plus-lg"></i>
    </a>
  </div>

  <script>
    (function () {
      function bindProspectFlash() {
        document.querySelectorAll('[data-prospect-flash]:not([data-flash-bound])').forEach(function (layer) {
          layer.dataset.flashBound = '1';

          let closeTimer = null;

          function closeFlash() {
            if (!layer.isConnected || layer.classList.contains('is-leaving')) return;

            window.clearTimeout(closeTimer);
            layer.classList.add('is-leaving');
            window.setTimeout(function () {
              layer.remove();
            }, 220);
          }

          const closeButton = layer.querySelector('[data-prospect-flash-close]');
          if (closeButton) closeButton.addEventListener('click', closeFlash);

          layer.addEventListener('click', function (event) {
            if (event.target === layer) closeFlash();
          });

          closeTimer = window.setTimeout(closeFlash, 4200);
        });
      }

      function bindProspectStory() {
        const story = document.getElementById('prospectMobileStory');
        const hero = document.getElementById('prospectMobileHero');
        const stage = document.getElementById('prospectBannerStage');
        const carousel = document.getElementById('mobileFlyerCarousel');
        if (!story || !hero || !stage || !carousel || story.dataset.storyBound === '1') return;

        story.dataset.storyBound = '1';

        const carouselInstance = bootstrap.Carousel.getOrCreateInstance(carousel, {
          interval: false,
          touch: true,
          wrap: false
        });

        function collapseHero() {
          if (story.classList.contains('is-collapsed')) return;

          story.classList.remove('hero-reentering', 'is-scroll-collapsed', 'is-scroll-hidden');
          story.classList.add('is-collapsed');
          stage.setAttribute('aria-hidden', 'false');
        }

        function expandHero() {
          if (!story.classList.contains('is-collapsed')) return;

          story.classList.remove('is-collapsed', 'is-scroll-collapsed', 'is-scroll-hidden');
          story.classList.add('hero-reentering');
          stage.setAttribute('aria-hidden', 'true');
          carouselInstance.to(0);

          window.setTimeout(function () {
            story.classList.remove('hero-reentering');
          }, 700);
        }

        function activeBannerIndex() {
          return Array.from(carousel.querySelectorAll('.carousel-item'))
            .findIndex(function (item) {
              return item.classList.contains('active');
            });
        }

        let heroTouchX = 0;
        let heroTouchY = 0;

        hero.addEventListener('touchstart', function (event) {
          const touch = event.changedTouches[0];
          heroTouchX = touch.clientX;
          heroTouchY = touch.clientY;
        }, { passive: true });

        hero.addEventListener('touchend', function (event) {
          const touch = event.changedTouches[0];
          const deltaX = touch.clientX - heroTouchX;
          const deltaY = touch.clientY - heroTouchY;

          if (Math.abs(deltaX) > 42 && Math.abs(deltaX) > Math.abs(deltaY)) {
            collapseHero();
          }
        }, { passive: true });

        let bannerTouchX = 0;

        stage.addEventListener('touchstart', function (event) {
          bannerTouchX = event.changedTouches[0].clientX;
        }, { passive: true });

        stage.addEventListener('touchend', function (event) {
          const deltaX = event.changedTouches[0].clientX - bannerTouchX;
          const slides = carousel.querySelectorAll('.carousel-item');
          const activeIndex = activeBannerIndex();

          if (Math.abs(deltaX) <= 42) return;

          const passedLastBanner = deltaX < 0 && activeIndex === slides.length - 1;
          const returnedBeforeFirstBanner = deltaX > 0 && activeIndex === 0;

          if (passedLastBanner || returnedBeforeFirstBanner) {
            expandHero();
          }
        }, { passive: true });
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
          bindProspectFlash();
          bindProspectStory();
        }, { once: true });
      } else {
        bindProspectFlash();
        bindProspectStory();
      }

      if (!window.__prospectFlashObserver && document.body) {
        window.__prospectFlashObserver = new MutationObserver(bindProspectFlash);
        window.__prospectFlashObserver.observe(document.body, {
          childList:true,
          subtree:true
        });
      }

      document.addEventListener('livewire:navigated', bindProspectFlash);
      document.addEventListener('livewire:navigated', bindProspectStory);
    })();
  </script>
</div>
