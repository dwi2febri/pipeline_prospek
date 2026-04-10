<div class="container-fluid px-0">

  <style>
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
      font-size:1.55rem;
      font-weight:900;
      color:#0f172a;
      letter-spacing:-.02em;
      line-height:1.1;
    }

    .app-subtitle{
      color:#64748b;
      font-size:.92rem;
      margin-top:4px;
    }

    .section-title-modern{
      font-size:1.2rem;
      font-weight:900;
      color:#111827;
      margin-bottom:12px;
      letter-spacing:-.02em;
    }

    .card-soft{
      border:0;
      border-radius:22px;
      background:#fff;
      box-shadow:0 12px 32px rgba(15,23,42,.08);
    }

    .btn-add-modern{
      display:inline-flex;
      align-items:center;
      gap:8px;
      border-radius:999px;
      padding:11px 18px;
      font-weight:800;
      text-decoration:none;
      color:#fff;
      background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%);
      box-shadow:0 14px 30px rgba(37,99,235,.28);
      border:0;
      white-space:nowrap;
      transition:all .18s ease;
    }

    .btn-add-modern:hover{
      color:#fff;
      transform:translateY(-1px);
    }

    .summary-btn{
      border:0;
      width:100%;
      text-align:left;
      position:relative;
      padding:16px;
      overflow:hidden;
      border-radius:20px;
      box-shadow:0 12px 28px rgba(15,23,42,.10);
      min-height:104px;
      transition:all .18s ease;
    }

    .summary-btn:hover{
      transform:translateY(-2px);
    }

    .summary-label{
      font-size:.92rem;
      font-weight:800;
      opacity:.95;
    }

    .summary-value{
      font-size:2.2rem;
      font-weight:900;
      line-height:1;
      margin-top:6px;
    }

    .summary-icon{
      position:absolute;
      right:14px;
      bottom:10px;
      font-size:42px;
      opacity:.18;
    }

    .filter-card-modern{
      border:1px solid #edf2f7;
      border-radius:22px;
      padding:14px;
      background:linear-gradient(180deg,#ffffff 0%,#fbfcfe 100%);
      box-shadow:0 10px 28px rgba(15,23,42,.05);
    }

    .prospect-list-title{
      font-weight:900;
      font-size:1.05rem;
      color:#111827;
      margin-bottom:12px;
    }

    .prospect-modern-card{
      border:1px solid #edf2f7;
      border-radius:22px;
      background:#fff;
      box-shadow:0 10px 24px rgba(15,23,42,.05);
      padding:16px;
      margin-bottom:12px;
      position:relative;
      overflow:hidden;
    }

    .prospect-name{
      font-weight:900;
      color:#0f172a;
      font-size:1rem;
      line-height:1.2;
    }

    .prospect-meta{
      color:#64748b;
      font-size:.85rem;
      margin-top:6px;
      line-height:1.6;
    }

    .badge-soft{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      min-height:34px;
      padding:7px 14px;
      border-radius:999px;
      font-weight:800;
      font-size:.78rem;
      letter-spacing:.01em;
      box-shadow:0 8px 20px rgba(15,23,42,.08);
      border:1px solid transparent;
      white-space:nowrap;
    }

    .status-open{
      background:linear-gradient(135deg,#dbeafe 0%,#93c5fd 100%);
      color:#1e3a8a;
    }
    .status-follow{
      background:linear-gradient(135deg,#fde68a 0%,#fbbf24 100%);
      color:#78350f;
    }
    .status-rejected{
      background:linear-gradient(135deg,#fda4af 0%,#ef4444 100%);
      color:#fff;
    }
    .status-closing{
      background:linear-gradient(135deg,#86efac 0%,#22c55e 100%);
      color:#14532d;
    }

    .produk-kredit{
      background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);
      color:#fff;
    }
    .produk-tabungan{
      background:linear-gradient(135deg,#22c55e 0%,#15803d 100%);
      color:#fff;
    }
    .produk-deposito{
      background:linear-gradient(135deg,#facc15 0%,#eab308 100%);
      color:#3b2f00;
    }
    .produk-aset{
      background:linear-gradient(135deg,#374151 0%,#111827 100%);
      color:#fff;
    }
    .produk-default{
      background:linear-gradient(135deg,#e5e7eb 0%,#cbd5e1 100%);
      color:#334155;
    }

    .action-btn-modern{
      border-radius:999px;
      font-weight:800;
      padding:.55rem 1rem;
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
      min-width:290px;
      max-width:290px;
      background:#fff;
      border-radius:20px;
      overflow:hidden;
      border:1px solid #edf2f7;
      box-shadow:0 12px 28px rgba(15,23,42,.08);
      scroll-snap-align:start;
      flex:0 0 290px;
      position:relative;
    }

    .catalog-top{
      display:flex;
      min-height:128px;
    }

    .catalog-visual{
      width:92px;
      flex:0 0 92px;
      background:
        linear-gradient(135deg, rgba(29,78,216,.08), rgba(37,99,235,.02)),
        repeating-linear-gradient(135deg, #1e3a8a 0 2px, transparent 2px 22px),
        repeating-linear-gradient(45deg, #1d4ed8 0 2px, transparent 2px 22px);
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
      background:#eff6ff;
      color:#1d4ed8;
      border:1px solid #dbeafe;
      border-radius:999px;
      font-size:.7rem;
      font-weight:800;
      padding:5px 10px;
      margin-bottom:8px;
    }

    .catalog-title{
      font-size:1.28rem;
      font-weight:900;
      color:#111827;
      line-height:1.15;
      margin-bottom:6px;
      letter-spacing:-.02em;
    }

    .catalog-desc{
      color:#374151;
      font-size:.9rem;
      line-height:1.35;
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
      color:#fb923c;
      text-decoration:none;
      font-weight:800;
      font-size:.88rem;
    }

    .tips-card{
      min-width:210px;
      max-width:210px;
      background:#fff;
      border-radius:18px;
      overflow:hidden;
      border:1px solid #edf2f7;
      box-shadow:0 12px 28px rgba(15,23,42,.08);
      scroll-snap-align:start;
      flex:0 0 210px;
      position:relative;
    }

    .tips-image{
      height:110px;
      background:
        radial-gradient(circle at 18% 24%, rgba(251,146,60,.22), transparent 18%),
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
      color:#2563eb;
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
      line-height:1.35;
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
      color:#f97316;
      text-decoration:none;
      font-weight:800;
      font-size:.84rem;
      margin-top:10px;
    }

    .empty-mini-card{
      border:1px dashed #dbe3ee;
      border-radius:18px;
      padding:18px;
      color:#64748b;
      background:#fff;
    }

    .glass-head{
      border-radius:24px;
      background:
        radial-gradient(circle at top right, rgba(59,130,246,.14), transparent 26%),
        linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
      border:1px solid #edf2f7;
      box-shadow:0 14px 36px rgba(15,23,42,.06);
      padding:16px;
      margin-bottom:16px;
    }

    .desktop-summary-row{
      display:grid;
      grid-template-columns:repeat(5, minmax(0, 1fr));
      gap:14px;
    }

    .mobile-summary-total{
      margin-bottom:12px;
    }

    .mobile-summary-grid{
      display:grid;
      grid-template-columns:repeat(2, minmax(0, 1fr));
      gap:12px;
    }

    .desktop-filter-row{
      display:grid;
      grid-template-columns:minmax(0, 1.2fr) 220px 180px;
      gap:12px;
      align-items:center;
    }

    .mobile-flyer-wrap{
      display:none;
    }

    .mobile-fab-add{
      display:none;
    }

    @media (max-width: 1199.98px){
      .desktop-summary-row{
        grid-template-columns:repeat(5, minmax(0, 1fr));
      }
    }

    @media (max-width: 991.98px){
      .desktop-summary-row{
        grid-template-columns:repeat(5, minmax(0, 1fr));
      }

      .desktop-filter-row{
        grid-template-columns:1fr;
      }
    }

    @media (max-width: 767.98px){
      body{
        background:
          radial-gradient(circle at 8% 8%, rgba(37,99,235,.16), transparent 24%),
          radial-gradient(circle at 92% 10%, rgba(139,92,246,.16), transparent 26%),
          radial-gradient(circle at 18% 82%, rgba(251,146,60,.12), transparent 20%),
          linear-gradient(180deg,#eef5ff 0%,#f7f9ff 42%,#f5f7fb 100%) !important;
      }

      .page-topbar{
        display:block;
        margin-bottom:10px;
      }

      .desktop-add-btn{
        display:none !important;
      }

      .container-fluid.px-0{
        padding-left:0 !important;
        padding-right:0 !important;
      }

      .mobile-flyer-wrap{
        display:block;
        margin:-6px 0 16px 0;
      }

      .flyer-shell{
        border-radius:28px;
        overflow:hidden;
        background:
          linear-gradient(180deg,#ffffff 0%,#f9fbff 100%);
        box-shadow:0 18px 36px rgba(15,23,42,.10);
        border:1px solid rgba(255,255,255,.85);
      }

      .flyer-shell .carousel-item{
        padding:18px 16px 22px 16px;
      }

      .flyer-card{
        position:relative;
        overflow:hidden;
        min-height:180px;
        border-radius:28px;
        padding:18px 16px;
        color:#fff;
        box-shadow:0 14px 30px rgba(15,23,42,.12);
      }

      .flyer-card.bg-1{
        background:
          radial-gradient(circle at 85% 18%, rgba(255,255,255,.18), transparent 18%),
          radial-gradient(circle at 15% 88%, rgba(255,255,255,.12), transparent 22%),
          linear-gradient(135deg,#5b6cff 0%,#4169ff 45%,#00b8ff 100%);
      }

      .flyer-card.bg-2{
        background:
          radial-gradient(circle at 84% 18%, rgba(255,255,255,.18), transparent 18%),
          radial-gradient(circle at 14% 84%, rgba(255,255,255,.12), transparent 20%),
          linear-gradient(135deg,#8b5cf6 0%,#d946ef 52%,#fb7185 100%);
      }

      .flyer-card.bg-3{
        background:
          radial-gradient(circle at 82% 18%, rgba(255,255,255,.18), transparent 18%),
          radial-gradient(circle at 16% 84%, rgba(255,255,255,.12), transparent 20%),
          linear-gradient(135deg,#0ea5e9 0%,#2563eb 40%,#4f46e5 100%);
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
        font-size:1.18rem;
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
        color:#1d4ed8;
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
        background:#2563eb;
      }

      .glass-head{
        border-radius:30px;
        margin:0 0 18px 0;
        padding:18px 16px 18px 16px;
        background:
          radial-gradient(circle at 10% 12%, rgba(59,130,246,.16), transparent 24%),
          radial-gradient(circle at 88% 18%, rgba(168,85,247,.14), transparent 22%),
          linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
        box-shadow:0 18px 36px rgba(15,23,42,.08);
        border:1px solid rgba(255,255,255,.75);
        position:relative;
        overflow:hidden;
      }

      .glass-head::before{
        content:"";
        position:absolute;
        top:-20px;
        right:-20px;
        width:100px;
        height:100px;
        border-radius:999px;
        background:radial-gradient(circle, rgba(59,130,246,.12) 0%, rgba(59,130,246,0) 70%);
        pointer-events:none;
      }

      .glass-head::after{
        content:"";
        position:absolute;
        left:-18px;
        bottom:-18px;
        width:92px;
        height:92px;
        border-radius:999px;
        background:radial-gradient(circle, rgba(249,115,22,.10) 0%, rgba(249,115,22,0) 72%);
        pointer-events:none;
      }

      .app-title{
        font-size:1.42rem;
        margin-bottom:2px;
      }

      .app-subtitle{
        font-size:.98rem;
        line-height:1.65;
        max-width:95%;
      }

      .summary-btn{
        min-height:124px;
        padding:18px 16px;
        border-radius:24px;
        box-shadow:
          0 16px 30px rgba(15,23,42,.11),
          inset 0 1px 0 rgba(255,255,255,.16);
      }

      .summary-value{
        font-size:3rem;
      }

      .summary-icon{
        font-size:52px;
        right:14px;
        bottom:12px;
        opacity:.20;
      }

      .mobile-summary-total .summary-btn{
        min-height:138px;
      }

      .filter-card-modern{
        border-radius:28px;
        padding:16px 14px;
        background:
          radial-gradient(circle at 10% 10%, rgba(59,130,246,.08), transparent 24%),
          radial-gradient(circle at 92% 88%, rgba(249,115,22,.08), transparent 26%),
          linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
        box-shadow:0 16px 28px rgba(15,23,42,.07);
      }

      .filter-card-modern .input-group,
      .filter-card-modern select,
      .filter-card-modern input{
        border-radius:18px !important;
      }

      .filter-card-modern .input-group{
        background:#f8fbff;
        border:1px solid #eef2ff;
        padding:2px;
      }

      .filter-card-modern .form-control,
      .filter-card-modern .form-select,
      .filter-card-modern .input-group-text{
        background:transparent !important;
      }

      .catalog-card{
        min-width:306px;
        max-width:306px;
        flex:0 0 306px;
        border-radius:24px;
        box-shadow:0 16px 30px rgba(15,23,42,.08);
      }

      .catalog-card::after{
        content:"";
        position:absolute;
        right:-20px;
        top:-20px;
        width:86px;
        height:86px;
        border-radius:999px;
        background:radial-gradient(circle, rgba(59,130,246,.10) 0%, rgba(59,130,246,0) 70%);
        pointer-events:none;
      }

      .catalog-visual{
        background:
          radial-gradient(circle at 22% 22%, rgba(59,130,246,.16), transparent 20%),
          linear-gradient(135deg, rgba(29,78,216,.08), rgba(37,99,235,.02)),
          repeating-linear-gradient(135deg, #1e40af 0 2px, transparent 2px 22px),
          repeating-linear-gradient(45deg, #2563eb 0 2px, transparent 2px 22px);
      }

      .catalog-badge{
        background:linear-gradient(135deg,#eef2ff 0%,#dbeafe 100%);
      }

      .tips-card{
        min-width:226px;
        max-width:226px;
        flex:0 0 226px;
        border-radius:24px;
        box-shadow:0 16px 28px rgba(15,23,42,.08);
      }

      .tips-card::before{
        content:"";
        position:absolute;
        top:0;
        left:0;
        right:0;
        height:4px;
        background:linear-gradient(90deg,#3b82f6 0%,#8b5cf6 50%,#f97316 100%);
      }

      .tips-image{
        background:
          radial-gradient(circle at 18% 24%, rgba(251,146,60,.24), transparent 18%),
          radial-gradient(circle at 70% 30%, rgba(37,99,235,.20), transparent 18%),
          radial-gradient(circle at 50% 80%, rgba(139,92,246,.14), transparent 20%),
          linear-gradient(180deg,#ffffff 0%,#f8fafc 100%);
      }

      .prospect-modern-card{
        border-radius:24px;
        padding:15px;
        box-shadow:0 14px 26px rgba(15,23,42,.06);
        background:linear-gradient(180deg,#ffffff 0%,#fcfdff 100%);
      }

      .prospect-modern-card::before{
        content:"";
        position:absolute;
        top:0;
        left:0;
        width:6px;
        height:100%;
        background:linear-gradient(180deg,#3b82f6 0%,#8b5cf6 45%,#f97316 100%);
        opacity:.9;
      }

      .prospect-modern-card::after{
        content:"";
        position:absolute;
        right:-18px;
        bottom:-18px;
        width:86px;
        height:86px;
        border-radius:999px;
        background:radial-gradient(circle, rgba(59,130,246,.08) 0%, rgba(59,130,246,0) 72%);
        pointer-events:none;
      }

      .prospect-name{
        font-size:1.03rem;
      }

      .prospect-meta{
        font-size:.86rem;
        line-height:1.65;
      }

      .section-title-modern{
        font-size:1.24rem;
        margin-bottom:14px;
        padding-left:2px;
      }

      .empty-mini-card{
        border-radius:24px;
        background:
          radial-gradient(circle at 85% 15%, rgba(59,130,246,.08), transparent 22%),
          linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
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
        background:linear-gradient(135deg,#2563eb 0%,#7c3aed 100%);
        box-shadow:0 18px 30px rgba(37,99,235,.32);
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
      ['key'=>'ALL', 'count_key'=>'TOTAL', 'label'=>'Total', 'bg'=>'linear-gradient(135deg,#f59e0b 0%,#d97706 100%)', 'icon'=>'bi-collection'],
      ['key'=>'OPEN', 'count_key'=>'OPEN', 'label'=>'Open', 'bg'=>'linear-gradient(135deg,#60a5fa 0%,#2563eb 100%)', 'icon'=>'bi-folder2-open'],
      ['key'=>'FOLLOW UP', 'count_key'=>'FOLLOW UP', 'label'=>'Follow Up', 'bg'=>'linear-gradient(135deg,#facc15 0%,#eab308 100%)', 'icon'=>'bi-arrow-repeat'],
      ['key'=>'REJECTED', 'count_key'=>'REJECTED', 'label'=>'Rejected', 'bg'=>'linear-gradient(135deg,#fb7185 0%,#ef4444 100%)', 'icon'=>'bi-x-circle'],
      ['key'=>'CLOSING', 'count_key'=>'CLOSING', 'label'=>'Closing', 'bg'=>'linear-gradient(135deg,#22c55e 0%,#16a34a 100%)', 'icon'=>'bi-check2-circle'],
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
        ];
      }
    }

    if (empty($flyers)) {
      $flyers = [
        ['badge'=>'Produk Unggulan','title'=>'Jelajahi Katalog Produk','desc'=>'Temukan produk yang sesuai untuk kebutuhan nasabah dan percepat follow up harian.','link'=>route('prospects.create'),'icon'=>'bi-stars','bg'=>'bg-1'],
        ['badge'=>'Tips Cepat','title'=>'Bangun Prospek Lebih Efektif','desc'=>'Gunakan data prospek, dokumentasi, dan follow up yang rapi agar peluang closing semakin besar.','link'=>route('prospects.create'),'icon'=>'bi-lightning-charge','bg'=>'bg-2'],
        ['badge'=>'Mulai Sekarang','title'=>'Tambah Prospek Baru','desc'=>'Input prospek baru langsung dari aplikasi dan pantau perkembangannya dengan lebih mudah.','link'=>route('prospects.create'),'icon'=>'bi-plus-circle','bg'=>'bg-3'],
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
                    aria-label="Slide {{ $i+1 }}"></button>
          @endforeach
        </div>

        <div class="carousel-inner">
          @foreach($flyers as $i => $flyer)
            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
              <div class="flyer-card {{ $flyer['bg'] }}">
                <div class="flyer-badge">
                  <i class="bi {{ $flyer['icon'] }}"></i> {{ $flyer['badge'] }}
                </div>

                <div class="flyer-title">{{ $flyer['title'] }}</div>
                <div class="flyer-desc">{{ $flyer['desc'] }}</div>

                <a href="{{ $flyer['link'] }}" class="flyer-btn">
                  Lihat Sekarang <i class="bi bi-arrow-right"></i>
                </a>

                <div class="flyer-illustration">
                  <i class="bi {{ $flyer['icon'] }}"></i>
                </div>
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
            class="summary-btn"
            wire:click="setStatus('{{ $c['key'] }}')"
            style="
              background:{!! $c['bg'] !!};
              color:#fff;
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

    <div class="d-block d-md-none">
      <div class="mobile-summary-total">
        <button
          type="button"
          class="summary-btn"
          wire:click="setStatus('{{ $mobileTop['key'] }}')"
          style="
            background:{!! $mobileTop['bg'] !!};
            color:#fff;
            border:{{ $status === $mobileTop['key'] ? '2px solid #111827' : '0' }};
          "
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
            class="summary-btn"
            wire:click="setStatus('{{ $c['key'] }}')"
            style="
              background:{!! $c['bg'] !!};
              color:#fff;
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
  </div>

  <div class="filter-card-modern">
    <div class="d-none d-md-block">
      <div class="desktop-filter-row">
        <div class="input-group">
          <span class="input-group-text bg-white border-0"><i class="bi bi-search"></i></span>
          <input
            class="form-control border-0 shadow-none"
            placeholder="Cari nama / no hp / nik / alamat..."
            wire:model.live.debounce.400ms="search"
          >
        </div>

        <select class="form-select border-0 shadow-none" wire:model.live="periode">
          <option value="hari_ini">Hari ini</option>
          <option value="bulan_ini">Bulan ini</option>
          <option value="semua">Semua</option>
        </select>

        <div class="text-end">
          <span class="badge bg-dark rounded-pill px-3 py-2">
            Status: {{ $status === 'ALL' ? 'TOTAL' : $status }}
          </span>
        </div>
      </div>
    </div>

    <div class="d-block d-md-none">
      <div class="row g-2 align-items-center">
        <div class="col-12">
          <div class="input-group">
            <span class="input-group-text bg-white border-0"><i class="bi bi-search"></i></span>
            <input
              class="form-control border-0 shadow-none"
              placeholder="Cari nama / no hp / nik / alamat..."
              wire:model.live.debounce.400ms="search"
            >
          </div>
        </div>

        <div class="col-7">
          <select class="form-select border-0 shadow-none" wire:model.live="periode">
            <option value="hari_ini">Hari ini</option>
            <option value="bulan_ini">Bulan ini</option>
            <option value="semua">Semua</option>
          </select>
        </div>

        <div class="col-5 text-end">
          <span class="badge bg-dark rounded-pill px-3 py-2">
            {{ $status === 'ALL' ? 'TOTAL' : $status }}
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="section-shell">
    <div class="section-title-modern">Katalog Produk</div>

    @if(isset($katalogProduk) && $katalogProduk->count())
      <div class="mobile-scroll-row">
        @foreach($katalogProduk as $kp)
          <div class="catalog-card">
            <div class="catalog-top">
              @if(!empty($kp->gambar_url))
                <div class="catalog-visual catalog-image">
                  <img src="{{ $kp->gambar_url }}" alt="{{ $kp->judul }}">
                </div>
              @else
                <div class="catalog-visual"></div>
              @endif

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
    <div class="section-title-modern">Tips &amp; Trik</div>

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
        Belum ada konten tips &amp; trik.
      </div>
    @endif
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
      @endphp

      <div class="prospect-modern-card" wire:key="prospect-card-{{ $p->id }}">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
          <div class="flex-grow-1">
            <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
              <div class="prospect-name">{{ $p->nama }}</div>
              <span class="badge-soft {{ $statusClass }}">
                {{ $p->status ?: '-' }}
              </span>
            </div>

            <div class="prospect-meta">
              <div><i class="bi bi-telephone me-1"></i> {{ $p->no_hp ?: '-' }} &nbsp;•&nbsp; <i class="bi bi-person-vcard me-1"></i> {{ $p->nik ?: '-' }}</div>
              <div><i class="bi bi-calendar-event me-1"></i> {{ \Illuminate\Support\Carbon::parse($p->tanggal_prospek)->format('d/m/Y') }} &nbsp;•&nbsp; <i class="bi bi-building me-1"></i> {{ $p->cabang->nama_cabang ?? '-' }}</div>
              @if($p->alamat)
                <div><i class="bi bi-geo-alt me-1"></i> {{ $p->alamat }}</div>
              @endif
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3">
              <span class="badge-soft {{ $produkClass }}">
                {{ $p->jenis_produk ?: '-' }}
              </span>

              @if((int)($p->is_diambil ?? 0) === 1)
                <span class="badge-soft" style="background:#111827;color:#fff;">
                  Diambil
                </span>
              @else
                <span class="badge-soft" style="background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;box-shadow:none;">
                  Belum Diambil
                </span>
              @endif
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

  <a href="{{ route('prospects.create') }}" class="mobile-fab-add d-md-none" aria-label="Tambah Prospek">
    <i class="bi bi-plus-lg"></i>
  </a>
</div>
