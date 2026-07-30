@php
  $status = strtoupper((string) ($prospect->status ?: 'OPEN'));
  $statusClass = match ($status) {
      'CLOSING' => 'is-closing',
      'REJECTED' => 'is-rejected',
      'FOLLOW UP' => 'is-follow',
      default => 'is-open',
  };

  $product = strtoupper((string) ($prospect->jenis_produk ?: '-'));
  $productClass = match ($product) {
      'TABUNGAN' => 'is-saving',
      'DEPOSITO' => 'is-deposit',
      'ASET' => 'is-asset',
      default => 'is-credit',
  };

  $phoneDigits = preg_replace('/[^0-9]/', '', (string) ($prospect->no_hp ?? ''));
  if ($phoneDigits !== '') {
      if (str_starts_with($phoneDigits, '0')) {
          $phoneDigits = '62' . substr($phoneDigits, 1);
      } elseif (!str_starts_with($phoneDigits, '62')) {
          $phoneDigits = '62' . $phoneDigits;
      }
  }

  $prospectBranch = $prospect->cabang
      ? trim(($prospect->cabang->kode_cabang ?: '') . ' - ' . ($prospect->cabang->nama_cabang ?: ''), ' -')
      : '-';
  $creatorBranch = optional($prospect->creator)->cabang
      ? trim((optional($prospect->creator->cabang)->kode_cabang ?: '') . ' - ' . (optional($prospect->creator->cabang)->nama_cabang ?: ''), ' -')
      : '-';
  $hasCoordinates = filled($prospect->lokasi_lat) && filled($prospect->lokasi_lng);
  $isCreditProduct = $product === 'KREDIT';
  $isActivePipeline = in_array($status, ['OPEN', 'FOLLOW UP'], true);
  $hasSavedEstimate = filled($prospect->estimasi_nominal_realisasi);
  $mustSaveEstimate = $isCreditProduct && $isActivePipeline && !$hasSavedEstimate;
  $canChooseFinalStatus = $isActivePipeline && !$mustSaveEstimate;
  $googleMapsUrl = $hasCoordinates
      ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($prospect->lokasi_lat . ',' . $prospect->lokasi_lng)
      : null;
  $googleMapsEmbedUrl = $hasCoordinates
      ? 'https://maps.google.com/maps?q=' . rawurlencode($prospect->lokasi_lat . ',' . $prospect->lokasi_lng) . '&z=16&output=embed'
      : null;
@endphp

<div class="submission-detail-page">
  <style>
    .submission-detail-page{
      --detail-blue:#3f55b1;
      --detail-navy:#17213a;
      --detail-muted:#718096;
      --detail-line:#e5ebf3;
      max-width:1120px;
      margin:0 auto;
      padding-bottom:24px;
      color:var(--detail-navy);
    }
    .submission-detail-toolbar{
      display:flex;
      align-items:center;
      gap:14px;
      margin-bottom:18px;
    }
    .submission-detail-back{
      width:42px;height:42px;flex:0 0 42px;display:grid;place-items:center;
      border:1px solid #dce5f0;border-radius:14px;color:#35469d;background:#fff;
      box-shadow:0 8px 20px rgba(37,54,98,.07);text-decoration:none;
    }
    .submission-detail-heading{min-width:0}
    .submission-detail-heading h1{margin:0;font-size:24px;font-weight:850;letter-spacing:-.025em}
    .submission-detail-heading p{margin:3px 0 0;color:var(--detail-muted);font-size:12px}
    .submission-detail-hero{
      position:relative;overflow:hidden;padding:22px;border-radius:26px;color:#fff;
      background:linear-gradient(135deg,#526fd0 0%,#35469d 58%,#243274 100%);
      box-shadow:0 18px 42px rgba(35,50,116,.2);
    }
    .submission-detail-hero::before{
      content:"";
      position:absolute;
      width:370px;
      height:125px;
      right:-82px;
      top:-22px;
      border-radius:0;
      background:linear-gradient(180deg,rgba(255,255,255,.11),rgba(80,205,236,.07));
      -webkit-mask:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20360%20120'%3E%3Cpath%20d='M0%2068C58%2021%20108%2098%20177%2053C246%208%20294%2085%20360%2040V120H0Z'/%3E%3C/svg%3E") center/100% 100% no-repeat;
      mask:url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20360%20120'%3E%3Cpath%20d='M0%2068C58%2021%20108%2098%20177%2053C246%208%20294%2085%20360%2040V120H0Z'/%3E%3C/svg%3E") center/100% 100% no-repeat;
      transform:rotate(-5deg) scaleY(-1);
      pointer-events:none;
    }
    .submission-detail-person{position:relative;z-index:1;display:flex;align-items:center;gap:14px}
    .submission-detail-avatar{
      width:54px;height:54px;min-width:54px;aspect-ratio:1/1;display:grid;place-items:center;
      border:2px solid rgba(255,255,255,.72);border-radius:18px;background:#f4f6ff;
      color:#35469d;font-size:19px;font-weight:900;box-shadow:0 9px 22px rgba(17,29,82,.2);
    }
    .submission-detail-name{font-size:20px;font-weight:900;line-height:1.15}
    .submission-detail-meta{margin-top:5px;color:rgba(255,255,255,.75);font-size:11px}
    .submission-detail-badges{position:relative;z-index:1;display:flex;flex-wrap:wrap;gap:8px;margin-top:17px}
    .submission-detail-badge{
      display:inline-flex;align-items:center;min-height:28px;padding:5px 11px;border-radius:999px;
      font-size:9px;font-weight:850;letter-spacing:.02em;background:rgba(255,255,255,.15);
      border:1px solid rgba(255,255,255,.18);
    }
    .submission-detail-badge.is-saving{background:#10a664}.submission-detail-badge.is-deposit{background:#d69e00}
    .submission-detail-badge.is-credit{background:#2878e8}.submission-detail-badge.is-asset{background:#202b42}
    .submission-detail-badge.is-closing{background:#10a97a}.submission-detail-badge.is-rejected{background:#d94b62}
    .submission-detail-badge.is-follow{background:#eba900;color:#3a2b00}.submission-detail-badge.is-open{background:#fff;color:#40506a}
    .submission-detail-grid{display:grid;grid-template-columns:minmax(0,1.1fr) minmax(0,.9fr);gap:16px;margin-top:16px}
    .submission-detail-card{
      border:1px solid var(--detail-line);border-radius:22px;background:#fff;
      box-shadow:0 12px 32px rgba(35,55,82,.07);padding:18px;
    }
    .submission-detail-card-title{
      display:flex;align-items:center;gap:9px;margin-bottom:14px;color:#293a59;
      font-size:12px;font-weight:850;
    }
    .submission-detail-card-title i{
      width:30px;height:30px;display:grid;place-items:center;border-radius:10px;
      color:var(--detail-blue);background:#eef2ff;font-size:14px;
    }
    .submission-info-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0 18px}
    .submission-info-item{padding:11px 0;border-bottom:1px solid #edf1f6;min-width:0}
    .submission-info-label{display:block;margin-bottom:4px;color:var(--detail-muted);font-size:9px;font-weight:650}
    .submission-info-value{color:#22304a;font-size:11px;font-weight:750;overflow-wrap:anywhere}
    .submission-contact-row{display:flex;gap:9px;margin-top:14px}
    .submission-contact-btn{
      flex:1;min-height:42px;display:flex;align-items:center;justify-content:center;gap:7px;
      border:1px solid #dce5f0;border-radius:14px;color:#35469d;background:#f7f9ff;
      font-size:10px;font-weight:800;text-decoration:none;
    }
    .submission-contact-btn.is-wa{border-color:#bcebd5;color:#087c4c;background:#effbf5}
    .submission-location{
      padding:14px;border:1px solid #e4eaf2;border-radius:17px;background:#f8fafc;
      color:#42516a;font-size:10px;line-height:1.55;
    }
    .submission-location-actions{display:flex;gap:8px;margin-top:11px}
    .submission-location-link{
      flex:1;display:flex;align-items:center;justify-content:center;gap:6px;min-height:38px;
      border:0;border-radius:12px;background:#edf2ff;color:#35469d;font-size:9px;font-weight:800;text-decoration:none;
    }
    .submission-documents{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px}
    .submission-document{
      display:block;overflow:hidden;border:1px solid #e5eaf1;border-radius:14px;background:#f8fafc;
      color:#43516a;text-decoration:none;
    }
    .submission-document img{width:100%;height:105px;display:block;object-fit:cover}
    .submission-document-file{height:105px;display:grid;place-items:center;color:#586a87;font-size:24px}
    .submission-document-name{padding:8px;font-size:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .submission-status-form{display:grid;grid-template-columns:1fr 1fr auto;gap:10px;align-items:end}
    .submission-field label{display:block;margin-bottom:6px;color:#5e6d84;font-size:9px;font-weight:750}
    .submission-field select,.submission-field input{
      width:100%;height:44px;border:1px solid #dce4ef;border-radius:13px;padding:0 12px;
      color:#26354f;background:#fff;font-size:10px;outline:none;
    }
    .submission-field select:focus,.submission-field input:focus{border-color:#6d82da;box-shadow:0 0 0 3px rgba(82,111,208,.12)}
    .submission-estimate-saved{
      min-height:44px;display:flex;align-items:center;justify-content:space-between;gap:12px;
      padding:0 12px;border:1px solid #dce4ef;border-radius:13px;color:#26354f;background:#f7f9fc;
      font-size:10px;font-weight:800;
    }
    .submission-estimate-saved i{color:#16a36e}
    .submission-status-mobile-trigger{
      width:100%;height:43px;display:none;align-items:center;justify-content:space-between;
      padding:0 12px;border:1px solid #dce4ef;border-radius:13px;color:#26354f;background:#fff;
      font-size:10px;text-align:left;
    }
    .submission-terminal-note{
      padding:13px;border:1px solid #dce5f0;border-radius:14px;color:#536179;
      background:#f8fafc;font-size:10px;line-height:1.5;
    }
    .submission-save{
      height:44px;padding:0 20px;border:0;border-radius:13px;color:#fff;
      background:linear-gradient(135deg,#526fd0,#35469d);font-size:10px;font-weight:850;
      box-shadow:0 9px 20px rgba(53,70,157,.22);
    }
    .submission-error{margin-top:5px;color:#d3344d;font-size:8px}
    .submission-success{
      display:flex;align-items:center;gap:8px;margin-bottom:14px;padding:11px 13px;
      border:1px solid #bcebd5;border-radius:14px;color:#087c4c;background:#effbf5;font-size:10px;font-weight:750;
    }
    .submission-sheet-handle{
      display:none;width:54px;height:6px;margin:10px auto 2px;border-radius:999px;
      background:#d7dfec;touch-action:none;
    }
    .submission-sheet-title-icon{
      width:44px;height:44px;display:grid;place-items:center;flex:0 0 44px;border-radius:14px;
      color:#5365cf;background:#eef2ff;font-size:18px;
    }
    .submission-sheet-close{
      width:44px;height:44px;display:grid;place-items:center;flex:0 0 44px;border:1px solid #e3e9f2;
      border-radius:14px;color:#526078;background:#f8fafc;font-size:20px;
    }
    .submission-sheet-header{
      display:grid;grid-template-columns:minmax(0,1fr) 44px;align-items:center;gap:12px;
    }
    .submission-sheet-heading{
      min-width:0;display:flex;align-items:center;gap:12px;
    }
    .submission-sheet-heading-copy{
      min-width:0;line-height:1.25;
    }
    .submission-sheet-heading-copy .fw-bold{
      overflow-wrap:anywhere;
    }
    .submission-sheet-heading-copy .text-muted{
      display:block;margin-top:3px;line-height:1.35;
    }
    .submission-sheet-header .submission-sheet-close{
      justify-self:end;margin:0;
    }
    .submission-status-options{display:grid;gap:8px}
    .submission-status-option{
      width:100%;min-height:54px;display:flex;align-items:center;gap:12px;padding:10px 14px;
      border:1px solid transparent;border-radius:16px;color:#33445f;background:#f8fafc;
      font-size:12px;font-weight:750;text-align:left;
    }
    .submission-status-option-dot{
      width:14px;height:14px;flex:0 0 14px;border:3px solid #bdc8d9;border-radius:50%;background:#fff;
    }
    .submission-status-option.is-selected{
      border-color:#a9b9ff;color:#4d4bc8;background:#eff2ff;
    }
    .submission-status-option.is-selected .submission-status-option-dot{
      border-color:#6c55ee;box-shadow:inset 0 0 0 2px #fff;background:#6c55ee;
    }
    .submission-map-frame{
      width:100%;height:440px;display:block;border:0;border-radius:18px;background:#eef2f7;
    }
    .submission-map-address{
      margin-top:10px;padding:11px 13px;border:1px solid #e4eaf2;border-radius:14px;
      color:#4a5870;background:#f8fafc;font-size:10px;line-height:1.5;
    }
    .submission-map-external{
      min-height:48px;display:flex;align-items:center;justify-content:center;gap:8px;
      border-radius:14px;color:#fff;background:linear-gradient(135deg,#526fd0,#35469d);
      font-size:11px;font-weight:850;text-decoration:none;
    }
    .submission-photo-preview{
      width:100%;max-height:68vh;display:block;object-fit:contain;border-radius:18px;background:#eef2f7;
    }
    .submission-photo-caption{
      margin-top:10px;color:#536179;font-size:10px;overflow-wrap:anywhere;
    }
    @media(max-width:767.98px){
      .submission-detail-page{margin:-2px -1px 0;padding-bottom:94px}
      .submission-detail-toolbar{margin:0 2px 12px}
      .submission-detail-back{width:38px;height:38px;flex-basis:38px;border-radius:13px}
      .submission-detail-heading h1{font-size:18px !important}
      .submission-detail-heading p{font-size:9px}
      .submission-detail-hero{padding:17px;border-radius:22px}
      .submission-detail-avatar{width:46px;height:46px;min-width:46px;border-radius:15px;font-size:16px}
      .submission-detail-name{font-size:15px}.submission-detail-meta{font-size:9px}
      .submission-detail-badges{margin-top:14px}.submission-detail-badge{min-height:25px;padding:4px 9px;font-size:8px}
      .submission-detail-grid{grid-template-columns:1fr;gap:11px;margin-top:11px}
      .submission-detail-card{padding:14px;border-radius:19px}
      .submission-detail-card-title{margin-bottom:8px;font-size:11px}
      .submission-info-list{grid-template-columns:1fr}
      .submission-info-item{display:grid;grid-template-columns:116px minmax(0,1fr);gap:10px;padding:9px 0}
      .submission-info-label{margin:0;font-size:8.5px}.submission-info-value{font-size:10px;text-align:right}
      .submission-contact-btn{min-height:39px;font-size:9px}
      .submission-documents{grid-template-columns:repeat(2,minmax(0,1fr))}
      .submission-document img,.submission-document-file{height:92px}
      .submission-document{width:100%;padding:0;text-align:left}
      .submission-status-form{grid-template-columns:1fr}
      .submission-field select,.submission-field input,.submission-save{height:43px}
      .submission-status-native{position:absolute!important;width:1px!important;height:1px!important;opacity:0!important;pointer-events:none!important}
      .submission-status-mobile-trigger{display:flex}
      .submission-save{width:100%}

      .submission-sheet-modal{padding:0!important}
      .submission-sheet-modal .modal-dialog{
        width:100vw!important;max-width:100vw!important;min-height:100dvh!important;
        display:flex!important;align-items:flex-end!important;margin:0!important;
      }
      .submission-sheet-modal .modal-content{
        width:100%!important;max-height:calc(100dvh - 10px)!important;margin-top:auto!important;
        border-radius:30px 30px 0 0!important;padding-bottom:env(safe-area-inset-bottom);
        box-shadow:0 -22px 60px rgba(15,23,42,.24)!important;
      }
      body.mobile-app-density .submission-sheet-modal{padding:0!important}
      body.mobile-app-density .submission-sheet-modal .modal-dialog{
        width:100vw!important;max-width:100vw!important;min-height:100dvh!important;margin:0!important;
      }
      body.mobile-app-density .submission-sheet-modal .modal-content{border-radius:30px 30px 0 0!important}
      .submission-sheet-handle{display:block}
      .submission-sheet-modal .modal-header{padding:12px 10px 10px 14px}
      .submission-sheet-modal .modal-body{padding:8px 18px 18px;overflow-y:auto;overscroll-behavior:contain}
      body.mobile-app-density .submission-sheet-modal .modal-header{padding:12px 10px 10px 14px!important}
      body.mobile-app-density .submission-sheet-modal .modal-body{
        max-height:none!important;padding:8px 18px 18px!important;
      }
      .submission-sheet-heading{gap:10px}
      .submission-sheet-heading-copy .fw-bold{font-size:15px;line-height:1.2}
      .submission-sheet-heading-copy .text-muted{font-size:10px!important}
      .submission-status-sheet .modal-content{height:min(64dvh,520px)}
      .submission-map-sheet .modal-content{height:min(88dvh,760px)}
      .submission-photo-sheet .modal-content{height:min(88dvh,760px)}
      .submission-map-sheet .modal-body,.submission-photo-sheet .modal-body{
        min-height:0;display:flex;flex-direction:column;
      }
      .submission-map-frame{height:auto;min-height:300px;flex:1 1 auto}
      .submission-map-external{margin-top:12px;flex:0 0 48px}
      .submission-photo-preview{min-height:0;flex:1 1 auto}
    }
  </style>

  <div class="submission-detail-toolbar">
    <a href="{{ route('prospects.submissions') }}" class="submission-detail-back" aria-label="Kembali ke pipeline">
      <i class="bi bi-arrow-left"></i>
    </a>
    <div class="submission-detail-heading">
      <h1>Lihat Prospek</h1>
      <p>Detail pipeline nasabah &bull; ID {{ $prospect->id }}</p>
    </div>
  </div>

  @if(session('success'))
    <div class="submission-success">
      <i class="bi bi-check-circle-fill"></i>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  <section class="submission-detail-hero">
    <div class="submission-detail-person">
      <div class="submission-detail-avatar">{{ strtoupper(substr((string) ($prospect->nama ?: 'P'), 0, 1)) }}</div>
      <div>
        <div class="submission-detail-name">{{ $prospect->nama ?: '-' }}</div>
        <div class="submission-detail-meta">
          {{ optional($prospect->tanggal_prospek)->format('d/m/Y') ?: '-' }} &bull; {{ $prospectBranch }}
        </div>
      </div>
    </div>
    <div class="submission-detail-badges">
      <span class="submission-detail-badge {{ $productClass }}">{{ $product }}</span>
      <span class="submission-detail-badge {{ $statusClass }}">{{ $status }}</span>
      <span class="submission-detail-badge">{{ (int) $prospect->is_diambil === 1 ? 'DITUGASKAN' : 'BELUM DITUGASKAN' }}</span>
    </div>
  </section>

  <div class="submission-detail-grid">
    <section class="submission-detail-card">
      <div class="submission-detail-card-title"><i class="bi bi-person-vcard"></i><span>Informasi Prospek</span></div>
      <div class="submission-info-list">
        <div class="submission-info-item"><span class="submission-info-label">Nama calon nasabah</span><div class="submission-info-value">{{ $prospect->nama ?: '-' }}</div></div>
        <div class="submission-info-item"><span class="submission-info-label">Nomor HP</span><div class="submission-info-value">{{ $prospect->no_hp ?: '-' }}</div></div>
        <div class="submission-info-item"><span class="submission-info-label">NIK</span><div class="submission-info-value">{{ $prospect->nik ?: '-' }}</div></div>
        <div class="submission-info-item"><span class="submission-info-label">Tanggal prospek</span><div class="submission-info-value">{{ optional($prospect->tanggal_prospek)->format('d/m/Y') ?: '-' }}</div></div>
        <div class="submission-info-item"><span class="submission-info-label">Jenis produk</span><div class="submission-info-value">{{ $product }}</div></div>
        @if($isCreditProduct)
          <div class="submission-info-item"><span class="submission-info-label">Estimasi Nominal Realisasi</span><div class="submission-info-value">{{ filled($prospect->estimasi_nominal_realisasi) ? 'Rp ' . number_format((int) $prospect->estimasi_nominal_realisasi, 0, ',', '.') : '-' }}</div></div>
        @endif
        <div class="submission-info-item"><span class="submission-info-label">Nomor rekening</span><div class="submission-info-value">{{ $prospect->no_rekening ?? '-' }}</div></div>
        <div class="submission-info-item"><span class="submission-info-label">Jenis usaha</span><div class="submission-info-value">{{ $prospect->jenis_usaha ?: '-' }}</div></div>
        <div class="submission-info-item"><span class="submission-info-label">Keterangan usaha</span><div class="submission-info-value">{{ $prospect->keterangan_usaha ?: '-' }}</div></div>
        <div class="submission-info-item"><span class="submission-info-label">Catatan</span><div class="submission-info-value">{{ $prospect->catatan ?: '-' }}</div></div>
        <div class="submission-info-item"><span class="submission-info-label">AO penugasan</span><div class="submission-info-value">{{ $takenByFullName ?: ($prospect->diambil_oleh ?: '-') }}</div></div>
      </div>
      <div class="submission-contact-row">
        @if($prospect->no_hp)
          <a href="tel:{{ $prospect->no_hp }}" class="submission-contact-btn"><i class="bi bi-telephone"></i> Telepon</a>
        @endif
        @if($phoneDigits !== '')
          <a href="https://api.whatsapp.com/send?phone={{ $phoneDigits }}" target="_blank" rel="noopener" class="submission-contact-btn is-wa">
            <i class="bi bi-whatsapp"></i> WhatsApp
          </a>
        @endif
      </div>
    </section>

    <div>
      <section class="submission-detail-card">
        <div class="submission-detail-card-title"><i class="bi bi-geo-alt"></i><span>Pengaju & Lokasi</span></div>
        <div class="submission-info-list">
          <div class="submission-info-item"><span class="submission-info-label">Pengaju</span><div class="submission-info-value">{{ $prospect->creator->nama_lengkap ?? $prospect->creator->name ?? '-' }}</div></div>
          <div class="submission-info-item"><span class="submission-info-label">Cabang pengaju</span><div class="submission-info-value">{{ $creatorBranch }}</div></div>
          <div class="submission-info-item"><span class="submission-info-label">Cabang prospek</span><div class="submission-info-value">{{ $prospectBranch }}</div></div>
          <div class="submission-info-item"><span class="submission-info-label">Kabupaten/Kota</span><div class="submission-info-value">{{ $prospect->kab_kota ?: '-' }}</div></div>
          <div class="submission-info-item"><span class="submission-info-label">Kecamatan</span><div class="submission-info-value">{{ $prospect->kecamatan ?: '-' }}</div></div>
          <div class="submission-info-item"><span class="submission-info-label">Desa</span><div class="submission-info-value">{{ $prospect->desa ?: '-' }}</div></div>
        </div>
        <div class="submission-location mt-3">
          <i class="bi bi-pin-map me-1"></i>{{ $prospect->alamat ?: 'Alamat belum tersedia.' }}
          @if($hasCoordinates)
            <div class="submission-location-actions">
              <button type="button"
                      class="submission-location-link"
                      data-bs-toggle="modal"
                      data-bs-target="#submissionMapModal">
                <i class="bi bi-map"></i> Buka Peta
              </button>
            </div>
          @endif
        </div>
      </section>
    </div>
  </div>

  @if($prospect->documents && $prospect->documents->count())
    <section class="submission-detail-card mt-3">
      <div class="submission-detail-card-title"><i class="bi bi-paperclip"></i><span>Foto & Dokumen</span></div>
      <div class="submission-documents">
        @foreach($prospect->documents as $document)
          @php
            $extension = strtolower(pathinfo((string) $document->file_path, PATHINFO_EXTENSION));
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true);
          @endphp
          @if($isImage)
            <button type="button"
                    class="submission-document submission-photo-open"
                    data-photo-url="{{ $document->url }}"
                    data-photo-name="{{ basename((string) $document->file_path) }}">
              <img src="{{ $document->url }}" alt="Dokumen {{ $loop->iteration }}">
              <div class="submission-document-name">{{ basename((string) $document->file_path) }}</div>
            </button>
          @else
            <a href="{{ $document->url }}" target="_blank" rel="noopener" class="submission-document">
              <div class="submission-document-file"><i class="bi bi-file-earmark-text"></i></div>
              <div class="submission-document-name">{{ basename((string) $document->file_path) }}</div>
            </a>
          @endif
        @endforeach
      </div>
    </section>
  @endif

  <section class="submission-detail-card mt-3">
    <div class="submission-detail-card-title"><i class="bi bi-arrow-repeat"></i><span>Perbarui Status Pipeline</span></div>
    @if($mustSaveEstimate)
      <form wire:submit="saveEstimate" class="submission-status-form">
        <div class="submission-field">
          <label for="submission-estimate">Estimasi Nominal Realisasi</label>
          <input id="submission-estimate" type="text" inputmode="numeric"
                 wire:model="estimasiNominalRealisasi"
                 placeholder="Contoh: 100.000.000"
                 oninput="this.value=this.value.replace(/[^0-9]/g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')">
          @error('estimasiNominalRealisasi')<div class="submission-error">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="submission-save" wire:loading.attr="disabled" wire:target="saveEstimate">
          <span wire:loading.remove wire:target="saveEstimate"><i class="bi bi-check2-circle me-1"></i> Simpan Estimasi</span>
          <span wire:loading wire:target="saveEstimate">Menyimpan...</span>
        </button>
      </form>
    @elseif($canChooseFinalStatus)
      <form wire:submit="updateStatus" class="submission-status-form">
        @if($isCreditProduct && $hasSavedEstimate)
          <div class="submission-field">
            <label>Estimasi Nominal Realisasi</label>
            <div class="submission-estimate-saved">
              <span>Rp {{ number_format((int) $prospect->estimasi_nominal_realisasi, 0, ',', '.') }}</span>
              <i class="bi bi-check-circle-fill" aria-label="Sudah tersimpan"></i>
            </div>
          </div>
        @endif

        <div class="submission-field">
          <label for="submission-status">Status tindak lanjut</label>
          <select id="submission-status" class="submission-status-native" wire:model.live="statusUpdate">
            <option value="">-- Pilih Status --</option>
            <option value="CLOSING">CLOSING</option>
            <option value="REJECTED">REJECTED</option>
          </select>
          <button type="button"
                  class="submission-status-mobile-trigger"
                  data-bs-toggle="modal"
                  data-bs-target="#submissionStatusModal"
                  aria-haspopup="dialog">
            <span>{{ $statusUpdate ?: '-- Pilih Status --' }}</span>
            <i class="bi bi-chevron-down"></i>
          </button>
          @error('statusUpdate')<div class="submission-error">{{ $message }}</div>@enderror
        </div>

        @if($statusUpdate === 'CLOSING')
          <div class="submission-field">
            <label for="submission-account">Nomor rekening</label>
            <input id="submission-account"
                   type="text"
                   inputmode="numeric"
                   wire:model="noRekening"
                   placeholder="Masukkan nomor rekening"
                   oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            @error('noRekening')<div class="submission-error">{{ $message }}</div>@enderror
          </div>
        @endif

        <button type="submit" class="submission-save" wire:loading.attr="disabled" wire:target="updateStatus">
          <span wire:loading.remove wire:target="updateStatus"><i class="bi bi-check2-circle me-1"></i> Simpan Status</span>
          <span wire:loading wire:target="updateStatus">Menyimpan...</span>
        </button>
      </form>
    @else
      <div class="submission-terminal-note">
        Status akhir prospek ini sudah ditetapkan menjadi <strong>{{ $status }}</strong>.
      </div>
    @endif
  </section>

  @if($canChooseFinalStatus)
    <div class="modal fade submission-sheet-modal submission-status-sheet"
         id="submissionStatusModal"
         tabindex="-1"
         aria-hidden="true"
         wire:ignore.self>
      <div class="modal-dialog">
        <div class="modal-content border-0">
          <span class="submission-sheet-handle" aria-hidden="true"></span>
          <div class="modal-header border-0 submission-sheet-header">
            <div class="submission-sheet-heading">
              <span class="submission-sheet-title-icon"><i class="bi bi-arrow-repeat"></i></span>
              <div class="submission-sheet-heading-copy">
                <div class="fw-bold">Pilih Status</div>
                <div class="text-muted small">Tentukan hasil akhir tindak lanjut</div>
              </div>
            </div>
            <button type="button" class="submission-sheet-close" data-bs-dismiss="modal" aria-label="Tutup">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="submission-status-options" role="listbox">
              <button type="button"
                      class="submission-status-option {{ $statusUpdate === 'CLOSING' ? 'is-selected' : '' }}"
                      wire:click="$set('statusUpdate', 'CLOSING')"
                      data-bs-dismiss="modal">
                <span class="submission-status-option-dot"></span>
                <span>CLOSING</span>
              </button>
              <button type="button"
                      class="submission-status-option {{ $statusUpdate === 'REJECTED' ? 'is-selected' : '' }}"
                      wire:click="$set('statusUpdate', 'REJECTED')"
                      data-bs-dismiss="modal">
                <span class="submission-status-option-dot"></span>
                <span>REJECTED</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if($hasCoordinates)
    <div class="modal fade submission-sheet-modal submission-map-sheet"
         id="submissionMapModal"
         tabindex="-1"
         aria-hidden="true"
         wire:ignore.self>
      <div class="modal-dialog">
        <div class="modal-content border-0">
          <span class="submission-sheet-handle" aria-hidden="true"></span>
          <div class="modal-header border-0 submission-sheet-header">
            <div class="submission-sheet-heading">
              <span class="submission-sheet-title-icon"><i class="bi bi-geo-alt"></i></span>
              <div class="submission-sheet-heading-copy">
                <div class="fw-bold">Lokasi Prospek</div>
                <div class="text-muted small">Titik lokasi melalui Google Maps</div>
              </div>
            </div>
            <button type="button" class="submission-sheet-close" data-bs-dismiss="modal" aria-label="Tutup">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
          <div class="modal-body">
            <iframe class="submission-map-frame"
                    src="{{ $googleMapsEmbedUrl }}"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Peta lokasi prospek"></iframe>
            <div class="submission-map-address">
              <i class="bi bi-pin-map me-1"></i>{{ $prospect->alamat ?: 'Alamat belum tersedia.' }}
            </div>
            <a href="{{ $googleMapsUrl }}"
               target="_blank"
               rel="noopener"
               class="submission-map-external">
              <i class="bi bi-google"></i> Buka di Aplikasi Google Maps
            </a>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if($prospect->documents && $prospect->documents->count())
    <div class="modal fade submission-sheet-modal submission-photo-sheet"
         id="submissionPhotoModal"
         tabindex="-1"
         aria-hidden="true"
         wire:ignore.self>
      <div class="modal-dialog">
        <div class="modal-content border-0">
          <span class="submission-sheet-handle" aria-hidden="true"></span>
          <div class="modal-header border-0 submission-sheet-header">
            <div class="submission-sheet-heading">
              <span class="submission-sheet-title-icon"><i class="bi bi-image"></i></span>
              <div class="submission-sheet-heading-copy">
                <div class="fw-bold">Foto Prospek</div>
                <div class="text-muted small">Pratinjau dokumentasi</div>
              </div>
            </div>
            <button type="button" class="submission-sheet-close" data-bs-dismiss="modal" aria-label="Tutup">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
          <div class="modal-body">
            <img id="submissionPhotoPreview" class="submission-photo-preview" alt="Foto prospek">
            <div id="submissionPhotoCaption" class="submission-photo-caption"></div>
          </div>
        </div>
      </div>
    </div>
  @endif

  <script>
    (function () {
      function bindSubmissionDetailSheets() {
        document.querySelectorAll('.submission-photo-open').forEach(function (button) {
          if (button.dataset.sheetBound === '1') return;
          button.dataset.sheetBound = '1';

          button.addEventListener('click', function () {
            var modalElement = document.getElementById('submissionPhotoModal');
            var preview = document.getElementById('submissionPhotoPreview');
            var caption = document.getElementById('submissionPhotoCaption');
            if (!modalElement || !preview || !window.bootstrap) return;

            preview.src = this.dataset.photoUrl || '';
            preview.alt = this.dataset.photoName || 'Foto prospek';
            if (caption) caption.textContent = this.dataset.photoName || '';
            bootstrap.Modal.getOrCreateInstance(modalElement).show();
          });
        });

        document.querySelectorAll('.submission-sheet-modal').forEach(function (modalElement) {
          if (modalElement.dataset.swipeBound === '1') return;
          modalElement.dataset.swipeBound = '1';

          var content = modalElement.querySelector('.modal-content');
          if (!content) return;

          var startY = 0;
          var distanceY = 0;
          var dragging = false;

          function resetPosition() {
            content.style.removeProperty('transition');
            content.style.removeProperty('transform');
          }

          content.addEventListener('touchstart', function (event) {
            if (event.touches.length !== 1) return;

            var startedOnHandle = event.target.closest('.submission-sheet-handle');
            var startedOnHeader = event.target.closest('.modal-header');
            var body = modalElement.querySelector('.modal-body');
            var interactive = event.target.closest('input,button,select,textarea,a,iframe');

            if (!startedOnHandle && !startedOnHeader && (interactive || (body && body.scrollTop > 0))) {
              return;
            }

            startY = event.touches[0].clientY;
            distanceY = 0;
            dragging = true;
            content.style.setProperty('transition', 'none', 'important');
          }, { passive: true });

          content.addEventListener('touchmove', function (event) {
            if (!dragging || event.touches.length !== 1) return;
            distanceY = Math.max(0, event.touches[0].clientY - startY);
            if (distanceY <= 0) return;

            event.preventDefault();
            content.style.setProperty('transform', 'translateY(' + distanceY + 'px)', 'important');
          }, { passive: false });

          content.addEventListener('touchend', function () {
            if (!dragging) return;
            dragging = false;

            if (distanceY >= 72 && window.bootstrap) {
              var instance = bootstrap.Modal.getInstance(modalElement);
              if (instance) instance.hide();
            }

            distanceY = 0;
            resetPosition();
          });

          content.addEventListener('touchcancel', resetPosition);
          modalElement.addEventListener('hidden.bs.modal', resetPosition);
        });
      }

      document.addEventListener('DOMContentLoaded', bindSubmissionDetailSheets);
      document.addEventListener('livewire:navigated', function () {
        window.setTimeout(bindSubmissionDetailSheets, 80);
      });
      document.addEventListener('livewire:init', function () {
        if (!window.Livewire) return;
        Livewire.hook('morphed', function () {
          window.setTimeout(bindSubmissionDetailSheets, 80);
        });
      });
    })();
  </script>
</div>
