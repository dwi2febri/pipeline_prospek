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
      color:#fff;
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
      border:0;
      width:100%;
      text-align:left;
      position:relative;
      padding:18px 18px 16px 18px;
      overflow:hidden;
      border-radius:28px;
      box-shadow:0 14px 28px rgba(15,23,42,.08);
      min-height:132px;
      transition:all .18s ease;
      color:#fff;
    }

    .summary-btn:hover{
      transform:translateY(-2px);
    }

    .summary-label{
      font-size:1rem;
      font-weight:800;
      opacity:.98;
    }

    .summary-value{
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
      font-size:58px;
      opacity:.16;
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
      content:"";
      position:absolute;
      left:0;
      top:0;
      bottom:0;
      width:6px;
      background:linear-gradient(180deg,#06b6d4 0%,#3b82f6 45%,#8b5cf6 100%);
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

    /* WARNA STATUS DISAMAKAN DENGAN KOTAK SUMMARY ATAS */
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
      border-radius:999px;
      font-weight:800;
      padding:.6rem 1rem;
      min-height:42px;
      box-shadow:none !important;
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

    .mobile-flyer-wrap,
    .mobile-fab-add{
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
    }

    @media (max-width: 767.98px){
      html, body{
        background:linear-gradient(180deg,#f5fbff 0%, #eef4fb 48%, #eaf1f8 100%) !important;
      }

      .page-topbar{
        display:block;
        margin-bottom:10px;
      }

      .desktop-add-btn{
        display:none !important;
      }

      .mobile-flyer-wrap{
        display:block;
        margin:-4px 0 16px 0;
      }

      .flyer-shell{
        border-radius:30px;
        overflow:hidden;
        background:linear-gradient(180deg,#ffffff 0%,#f9fbff 100%);
        box-shadow:0 18px 36px rgba(15,23,42,.10);
        border:1px solid rgba(255,255,255,.88);
      }

      .flyer-shell .carousel-item{
        padding:18px 16px 22px 16px;
      }

      .flyer-card{
        position:relative;
        overflow:hidden;
        min-height:186px;
        border-radius:28px;
        padding:18px 16px;
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
        padding:6px 11px;
        border-radius:999px;
        background:rgba(255,255,255,.18);
        border:1px solid rgba(255,255,255,.20);
        backdrop-filter:blur(6px);
        font-size:.72rem;
        font-weight:800;
      }

      .flyer-title{
        margin-top:12px;
        font-size:1.16rem;
        font-weight:900;
        line-height:1.25;
        letter-spacing:-.02em;
        max-width:62%;
      }

      .flyer-desc{
        margin-top:8px;
        max-width:62%;
        font-size:.84rem;
        line-height:1.48;
        color:rgba(255,255,255,.93);
      }

      .flyer-btn{
        margin-top:14px;
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:10px 14px;
        border-radius:999px;
        background:#fff;
        color:#0f52d6;
        text-decoration:none;
        font-weight:800;
        font-size:.84rem;
        box-shadow:0 10px 20px rgba(255,255,255,.18);
      }

      .flyer-illustration{
        position:absolute;
        right:8px;
        bottom:0;
        width:118px;
        height:118px;
        opacity:.95;
        display:flex;
        align-items:flex-end;
        justify-content:center;
        font-size:62px;
      }

      .flyer-shell .carousel-indicators{
        margin-bottom:6px;
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
        padding:18px 16px;
        border-radius:30px;
      }

      .desktop-summary-row{
        display:none;
      }

      .mobile-summary-total{
        margin-bottom:14px;
      }

      .mobile-summary-total .summary-btn{
        min-height:138px;
      }

      .mobile-summary-grid{
        display:grid;
        grid-template-columns:repeat(2, minmax(0, 1fr));
        gap:14px;
      }

      .summary-btn{
        min-height:126px;
        border-radius:26px;
        box-shadow:
          0 16px 30px rgba(15,23,42,.11),
          inset 0 1px 0 rgba(255,255,255,.16);
      }

      .summary-value{
        font-size:3rem;
      }

      .summary-icon{
        right:14px;
        bottom:12px;
        font-size:52px;
        opacity:.18;
      }

      .filter-card-modern{
        border-radius:28px;
        padding:16px 14px;
        box-shadow:0 16px 28px rgba(15,23,42,.07);
      }

      .catalog-card{
        min-width:306px;
        max-width:306px;
        flex:0 0 306px;
      }

      .tips-card{
        min-width:226px;
        max-width:226px;
        flex:0 0 226px;
      }

      .prospect-modern-card{
        border-radius:26px;
        padding:15px;
      }

      .mobile-fab-add{
        position:fixed;
        right:16px;
        bottom:88px;
        z-index:1045;
        width:62px;
        height:62px;
        border-radius:999px;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#fff;
        text-decoration:none;
        background:linear-gradient(135deg,#14b8a6 0%,#3b82f6 100%);
        box-shadow:0 18px 30px rgba(37,99,235,.28);
      }

      .mobile-fab-add i{
        font-size:1.55rem;
      }
    }
  </style>

  @if(session('ok'))
    <div class="alert alert-success rounded-4 shadow-sm">{{ session('ok') }}</div>
  @endif

  @php
    $summaryCards = [
      ['key'=>'ALL', 'count_key'=>'TOTAL', 'label'=>'Total Pengajuan', 'bg'=>'linear-gradient(135deg,#f39a00 0%,#ea8c00 100%)', 'icon'=>'bi-collection'],
      ['key'=>'OPEN', 'count_key'=>'OPEN', 'label'=>'Open', 'bg'=>'linear-gradient(135deg,#f6c0c0 0%,#efaaaa 100%)', 'icon'=>'bi-folder2-open'],
      ['key'=>'FOLLOW UP', 'count_key'=>'FOLLOW UP', 'label'=>'Follow Up', 'bg'=>'linear-gradient(135deg,#17b07d 0%,#10a36f 100%)', 'icon'=>'bi-arrow-repeat'],
      ['key'=>'REJECTED', 'count_key'=>'REJECTED', 'label'=>'Rejected', 'bg'=>'linear-gradient(135deg,#f34f74 0%,#eb2e5c 100%)', 'icon'=>'bi-x-circle'],
      ['key'=>'CLOSING', 'count_key'=>'CLOSING', 'label'=>'Closing', 'bg'=>'linear-gradient(135deg,#5b97f6 0%,#2f6fe6 100%)', 'icon'=>'bi-check2-circle'],
    ];

    $mobileTop = $summaryCards[0];
    $mobileBottom = [
      $summaryCards[1],
      $summaryCards[2],
      $summaryCards[3],
      $summaryCards[4],
    ];

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
    </div>

    <a href="{{ route('prospects.create') }}" class="btn-add-modern desktop-add-btn d-none d-md-inline-flex">
      <i class="bi bi-plus-lg"></i> Tambah Prospek
    </a>
  </div>

  <div class="mobile-flyer-wrap d-md-none">
    <div class="flyer-shell">
      <div id="mobileFlyerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3200">
        <div class="carousel-indicators">
          @foreach($flyers as $i => $flyer)
            <button type="button"
                    data-bs-target="#mobileFlyerCarousel"
                    data-bs-slide-to="{{ $i }}"
                    class="{{ $i === 0 ? 'active' : '' }}"
                    aria-current="{{ $i === 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $i + 1 }}"></button>
          @endforeach
        </div>

        <div class="carousel-inner">
          @foreach($flyers as $i => $flyer)
            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
              <div class="flyer-card {{ $flyer['bg'] }}">
                @if(!empty($flyer['image']))
                  <div class="flyer-media">
                    <img src="{{ $flyer['image'] }}" alt="{{ $flyer['title'] }}">
                  </div>
                  <div class="flyer-overlay"></div>
                @endif

                <div class="flyer-badge">
                  <i class="bi {{ $flyer['icon'] }}"></i> {{ $flyer['badge'] }}
                </div>

                <div class="flyer-title">{{ $flyer['title'] }}</div>

                <div class="flyer-desc">
                  {{ \Illuminate\Support\Str::limit($flyer['desc'], 95) }}
                </div>

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

  <div class="glass-head">
    <div class="d-none d-md-block">
      <div class="desktop-summary-row">
        @foreach($summaryCards as $c)
          <button
            type="button"
            wire:click="setStatus('{{ $c['key'] }}')"
            class="summary-btn"
            style="
              background:{!! $c['bg'] !!};
              border:{{ $status === $c['key'] ? '2px solid #111827' : '0' }};
            "
          >
            <div class="summary-label">{{ $c['label'] }}</div>
            <div class="summary-value">{{ $summary[$c['count_key']] ?? 0 }}</div>
            <div class="summary-icon"><i class="bi {{ $c['icon'] }}"></i></div>
          </button>
        @endforeach
      </div>
    </div>

    <div class="d-md-none">
      <div class="mobile-summary-total">
        <button
          type="button"
          wire:click="setStatus('{{ $mobileTop['key'] }}')"
          class="summary-btn"
          style="background:{!! $mobileTop['bg'] !!}; border:{{ $status === $mobileTop['key'] ? '3px solid #111827' : '0' }};"
        >
          <div class="summary-label">{{ $mobileTop['label'] }}</div>
          <div class="summary-value">{{ $summary[$mobileTop['count_key']] ?? 0 }}</div>
          <div class="summary-icon"><i class="bi {{ $mobileTop['icon'] }}"></i></div>
        </button>
      </div>

      <div class="mobile-summary-grid">
        @foreach($mobileBottom as $c)
          <button
            type="button"
            wire:click="setStatus('{{ $c['key'] }}')"
            class="summary-btn"
            style="background:{!! $c['bg'] !!}; border:{{ $status === $c['key'] ? '3px solid #111827' : '0' }};"
          >
            <div class="summary-label">{{ $c['label'] }}</div>
            <div class="summary-value">{{ $summary[$c['count_key']] ?? 0 }}</div>
            <div class="summary-icon"><i class="bi {{ $c['icon'] }}"></i></div>
          </button>
        @endforeach
      </div>
    </div>
  </div>

  <div class="filter-card-modern">
    <div class="desktop-filter-row">
      <div>
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
        <select class="form-select" wire:model.live="periode">
          <option value="hari_ini">Hari ini</option>
          <option value="bulan_ini">Bulan ini</option>
          <option value="semua">Semua</option>
        </select>
      </div>

      <div class="text-md-end">
        <span class="badge bg-dark rounded-pill px-3 py-2">
          Status:
          {{ $status === 'ALL' ? 'TOTAL' : $status }}
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
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
          <div class="flex-grow-1">
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
                  <span class="badge-soft" style="background:#111827;color:#fff;">
                    Diambil
                  </span>
                @else
                  <span class="badge-soft" style="background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;">
                    Belum Diambil
                  </span>
                @endif
              </div>
            </div>
          </div>

          <div class="d-flex flex-column gap-2" style="min-width:145px;">
            <a class="btn btn-outline-primary action-btn-modern w-100"
               href="{{ route('prospects.edit', $p->id) }}">
              <i class="bi bi-pencil-square me-1"></i> Detail
            </a>

            <button
              type="button"
              wire:click="trash({{ $p->id }})"
              onclick="return confirm('Pindahkan ke Recycle Bin?')"
              class="btn btn-outline-danger action-btn-modern w-100"
            >
              <i class="bi bi-trash me-1"></i> Hapus
            </button>
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

  <a href="{{ route('prospects.create') }}" class="mobile-fab-add d-md-none" aria-label="Tambah Prospek">
    <i class="bi bi-plus-lg"></i>
  </a>
</div>
