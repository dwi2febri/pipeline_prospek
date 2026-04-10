<div class="container-fluid px-0">
  <style>
    .detail-wrap{
      max-width:1100px;
      margin:0 auto;
    }

    .back-link{
      display:inline-flex;
      align-items:center;
      gap:8px;
      text-decoration:none;
      font-weight:800;
      color:#334155;
      margin-bottom:16px;
    }

    .detail-card{
      border:0;
      border-radius:28px;
      overflow:hidden;
      background:#fff;
      box-shadow:0 18px 46px rgba(15,23,42,.10);
      margin-bottom:20px;
    }

    .detail-grid{
      display:grid;
      grid-template-columns:420px minmax(0,1fr);
      gap:0;
      min-height:100%;
    }

    .detail-media{
      background:
        radial-gradient(circle at 10% 20%, rgba(59,130,246,.14), transparent 22%),
        radial-gradient(circle at 90% 20%, rgba(139,92,246,.12), transparent 22%),
        linear-gradient(180deg,#f8fbff 0%,#eef5ff 100%);
      min-height:340px;
      display:flex;
      align-items:center;
      justify-content:center;
      overflow:hidden;
    }

    .detail-media img{
      width:100%;
      height:100%;
      object-fit:cover;
      display:block;
    }

    .detail-media-empty{
      font-size:4rem;
      color:#94a3b8;
    }

    .detail-body{
      padding:28px;
    }

    .detail-chip{
      display:inline-flex;
      align-items:center;
      gap:6px;
      padding:8px 14px;
      border-radius:999px;
      font-size:.78rem;
      font-weight:800;
      margin-bottom:14px;
    }

    .chip-produk{
      background:linear-gradient(135deg,#dbeafe 0%,#bfdbfe 100%);
      color:#1d4ed8;
    }

    .chip-tips{
      background:linear-gradient(135deg,#ede9fe 0%,#ddd6fe 100%);
      color:#6d28d9;
    }

    .detail-title{
      font-size:2rem;
      font-weight:900;
      line-height:1.15;
      letter-spacing:-.03em;
      color:#0f172a;
      margin-bottom:12px;
    }

    .detail-meta{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      margin-bottom:16px;
    }

    .meta-pill{
      display:inline-flex;
      align-items:center;
      gap:6px;
      padding:8px 12px;
      border-radius:999px;
      background:#f8fafc;
      border:1px solid #e5e7eb;
      color:#334155;
      font-size:.82rem;
      font-weight:700;
    }

    .detail-content{
      color:#334155;
      font-size:1rem;
      line-height:1.9;
      white-space:pre-line;
    }

    .related-card{
      border:0;
      border-radius:24px;
      background:#fff;
      box-shadow:0 14px 34px rgba(15,23,42,.08);
      padding:18px;
    }

    .related-title{
      font-size:1.1rem;
      font-weight:900;
      color:#0f172a;
      margin-bottom:14px;
    }

    .mini-item{
      border:1px solid #eef2f7;
      border-radius:18px;
      padding:12px;
      height:100%;
      background:#fff;
    }

    .mini-thumb{
      width:100%;
      height:160px;
      object-fit:cover;
      border-radius:14px;
      background:#f8fafc;
      margin-bottom:10px;
    }

    .mini-thumb-empty{
      width:100%;
      height:160px;
      border-radius:14px;
      background:#f8fafc;
      color:#94a3b8;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:2rem;
      margin-bottom:10px;
    }

    .mini-label{
      font-size:.72rem;
      font-weight:800;
      color:#64748b;
      text-transform:uppercase;
      margin-bottom:6px;
      letter-spacing:.04em;
    }

    .mini-title{
      font-size:1rem;
      font-weight:800;
      color:#0f172a;
      line-height:1.35;
      min-height:44px;
    }

    .mini-link{
      display:inline-flex;
      align-items:center;
      gap:6px;
      text-decoration:none;
      color:#2563eb;
      font-weight:800;
      margin-top:8px;
    }

    @media (max-width: 991.98px){
      .detail-grid{
        grid-template-columns:1fr;
      }
      .detail-media{
        min-height:260px;
      }
    }

    @media (max-width: 767.98px){
      .detail-wrap{
        max-width:100%;
      }
      .detail-card{
        border-radius:24px;
      }
      .detail-body{
        padding:18px;
      }
      .detail-title{
        font-size:1.55rem;
      }
      .detail-content{
        font-size:.95rem;
        line-height:1.8;
      }
      .mini-thumb,
      .mini-thumb-empty{
        height:140px;
      }
    }
  </style>

  <div class="detail-wrap">
    <a href="{{ $backUrl ?: route('prospects.index') }}" class="back-link">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="detail-card">
      <div class="detail-grid">
        <div class="detail-media">
          @if($item && $item->gambar_url)
            <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}">
          @else
            <div class="detail-media-empty">
              <i class="bi bi-image"></i>
            </div>
          @endif
        </div>

        <div class="detail-body">
          <div class="detail-chip {{ $jenis === 'produk' ? 'chip-produk' : 'chip-tips' }}">
            @if($jenis === 'produk')
              <i class="bi bi-grid-3x3-gap-fill"></i> Katalog Produk
            @else
              <i class="bi bi-lightbulb"></i> Tips & Trick
            @endif
          </div>

          <div class="detail-title">{{ $item->judul ?? '-' }}</div>

          <div class="detail-meta">
            @if($jenis === 'produk')
              <div class="meta-pill">
                <i class="bi bi-patch-check"></i>
                {{ $item->badge ?: 'Tanpa badge' }}
              </div>
            @else
              <div class="meta-pill">
                <i class="bi bi-bookmark-star"></i>
                {{ $item->kategori ?: 'Tanpa kategori' }}
              </div>
            @endif

            <div class="meta-pill">
              <i class="bi bi-sort-numeric-down"></i>
              Urutan: {{ $item->urutan ?? 0 }}
            </div>

            <div class="meta-pill">
              <i class="bi bi-check-circle"></i>
              {{ (int)($item->aktif ?? 0) === 1 ? 'Aktif' : 'Nonaktif' }}
            </div>
          </div>

          <div class="detail-content">{{ $item->deskripsi ?: '-' }}</div>
        </div>
      </div>
    </div>

    @if(count($relatedItems) > 0)
      <div class="related-card">
        <div class="related-title">
          {{ $jenis === 'produk' ? 'Konten Produk Lainnya' : 'Tips & Trick Lainnya' }}
        </div>

        <div class="row g-3">
          @foreach($relatedItems as $rel)
            <div class="col-12 col-sm-6 col-xl-4">
              <div class="mini-item">
                @if(!empty($rel['gambar_url']))
                  <img src="{{ $rel['gambar_url'] }}" class="mini-thumb" alt="{{ $rel['judul'] }}">
                @else
                  <div class="mini-thumb-empty">
                    <i class="bi bi-image"></i>
                  </div>
                @endif

                <div class="mini-label">
                  {{ $jenis === 'produk' ? ($rel['badge'] ?? 'Produk') : ($rel['kategori'] ?? 'Tips') }}
                </div>

                <div class="mini-title">{{ $rel['judul'] }}</div>

                <a href="{{ $rel['detail_url'] }}" class="mini-link">
                  Lihat lebih banyak <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </div>
</div>
