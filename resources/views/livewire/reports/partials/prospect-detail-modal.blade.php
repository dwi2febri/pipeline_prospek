@php
  $isKc = $scope === 'kc';
  $modalId = $isKc ? 'modalDetailKc' : 'modalDetailPegawai';
  $datePrefix = $isKc ? 'detailKcFilter' : 'detailFilter';
  $dateMode = $isKc ? $detailKcFilterMode : $detailFilterMode;
  $tab = $isKc ? $detailKcProductTab : $detailProductTab;
  $tabAction = $isKc ? 'setDetailKcProductTab' : 'setDetailProductTab';
  $statusModel = $isKc ? 'detailKcStatus' : 'detailStatus';
@endphp
<div class="modal fade recap-detail-modal" id="{{ $modalId }}" tabindex="-1" aria-hidden="true" wire:ignore.self>
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down" style="max-width:1500px;width:96vw;">
    <div class="modal-content border-0" style="border-radius:20px;overflow:hidden;">
      <div class="modal-header">
        <div>
          <h5 class="modal-title fw-bold mb-1">{{ $isKc ? 'Detail Pengajuan Per KC' : 'Detail Pengajuan Pegawai' }}</h5>
          <div class="text-muted small">{{ $subjectLine }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div class="recap-summary-grid mb-3">
          @foreach(['all' => ['All', 'Semua produk', 'bi-grid-1x2'], 'kredit' => ['Kredit', 'Realisasi kredit', 'bi-bank'], 'dana' => ['Dana', 'Deposito / Tabungan', 'bi-wallet2']] as $key => [$label, $caption, $icon])
            <div class="recap-summary-card recap-summary-{{ $key }}">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div><div class="recap-summary-label">{{ $label }}</div><div class="recap-summary-caption">{{ $caption }}</div></div>
                <span class="recap-summary-icon"><i class="bi {{ $icon }}" aria-hidden="true"></i></span>
              </div>
              <div class="recap-summary-value">Rp {{ number_format($summary[$key]['nominal'], 0, ',', '.') }}</div>
              <div class="recap-summary-bottom"><span class="recap-noa">{{ number_format($summary[$key]['noa'], 0, ',', '.') }} NOA</span><span>rekening Closing</span></div>
            </div>
          @endforeach
        </div>
        <div class="text-muted small mb-3">Ringkasan mengikuti periode dan status, mencakup semua produk. NOA dan nominal dihitung dari rekening unik yang cocok dengan nominatif.</div>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
          <div class="pegawai-product-tabs" role="group" aria-label="Filter produk pengajuan">
            @foreach(['all' => ['All', 'Semua produk', 'bi-grid-1x2'], 'kredit' => ['Kredit', 'Pengajuan kredit', 'bi-bank'], 'dana' => ['Dana', 'Deposito / Tabungan', 'bi-wallet2']] as $key => [$label, $caption, $icon])
              <button type="button" class="pegawai-product-tab {{ $tab === $key ? 'is-active' : '' }}" aria-pressed="{{ $tab === $key ? 'true' : 'false' }}"
                      wire:click="{{ $tabAction }}('{{ $key }}')" wire:loading.attr="disabled" wire:target="{{ $tabAction }}">
                <i class="bi {{ $icon }}" aria-hidden="true"></i><span>{{ $label }}<small>{{ $caption }}</small></span>
              </button>
            @endforeach
          </div>
          <div class="text-muted small" aria-live="polite"><strong class="text-dark">{{ number_format($rows->count(), 0, ',', '.') }}</strong> pengajuan</div>
        </div>
        <div class="recap-detail-filters mb-3">
          <div class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
              <label class="form-label fw-semibold mb-1" for="{{ $modalId }}-status">Status</label>
              <select id="{{ $modalId }}-status" class="form-select" data-searchable-filter wire:model.live="{{ $statusModel }}">
                <option value="ALL">Semua Status</option><option value="OPEN">Open</option><option value="FOLLOW UP">Follow Up</option><option value="CLOSING">Closing</option><option value="REJECTED">Rejected</option>
              </select>
            </div>
            <div class="col-6 col-md-3">
              <label class="form-label fw-semibold mb-1" for="{{ $modalId }}-date">Mode Tanggal</label>
              <select id="{{ $modalId }}-date" class="form-select" wire:model.live="{{ $datePrefix }}Mode">
                <option value="all">Semua</option><option value="monthly">Bulanan</option><option value="range">Range Tanggal</option>
              </select>
            </div>
            @if($dateMode === 'monthly')
              <div class="col-6 col-md-3">
                <label class="form-label fw-semibold mb-1" for="{{ $modalId }}-month">Bulan</label>
                <select id="{{ $modalId }}-month" class="form-select" wire:model.live="{{ $datePrefix }}Bulan">
                  @foreach($bulanOptions as $b)<option value="{{ $b['id'] }}">{{ $b['label'] }}</option>@endforeach
                </select>
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label fw-semibold mb-1" for="{{ $modalId }}-year">Tahun</label>
                <select id="{{ $modalId }}-year" class="form-select" wire:model.live="{{ $datePrefix }}Tahun">
                  @foreach($tahunOptions as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach
                </select>
              </div>
            @elseif($dateMode === 'range')
              <div class="col-6 col-md-3"><label class="form-label fw-semibold mb-1" for="{{ $modalId }}-from">Tanggal Awal</label><input id="{{ $modalId }}-from" type="date" class="form-control" wire:model.live="{{ $datePrefix }}TanggalAwal"></div>
              <div class="col-6 col-md-3"><label class="form-label fw-semibold mb-1" for="{{ $modalId }}-to">Tanggal Akhir</label><input id="{{ $modalId }}-to" type="date" class="form-control" wire:model.live="{{ $datePrefix }}TanggalAkhir"></div>
            @endif
          </div>
        </div>
        @if($unavailable)<div class="alert alert-warning small" role="status">Sebagian data nominatif belum dapat dimuat. Ringkasan realisasi mungkin belum lengkap. Silakan coba kembali.</div>@endif
        @if($unmatchedCount > 0)<div class="alert alert-warning small" role="status"><strong>{{ $unmatchedCount }} pengajuan</strong> memiliki nomor rekening yang tidak ditemukan di nominatif produk terkait. Periksa No. Rekening dan Keterangan.</div>@endif
        <div class="text-muted small mb-2">Nama prospek dan nominal realisasi bersumber dari nominatif. Nominal ditampilkan untuk status Closing; data yang belum tersedia ditandai "-".</div>
        <div class="table-responsive recap-table" wire:loading.class="opacity-50" wire:target="{{ $tabAction }},{{ $statusModel }},{{ $datePrefix }}Mode,{{ $datePrefix }}Bulan,{{ $datePrefix }}Tahun,{{ $datePrefix }}TanggalAwal,{{ $datePrefix }}TanggalAkhir">
          <table class="table table-hover align-middle mb-0">
            <thead><tr><th style="width:40px;">No</th><th style="min-width:90px;">Tanggal</th><th style="min-width:170px;">Nama Prospek</th><th style="min-width:125px;">No. Rekening</th><th style="min-width:85px;">Produk</th><th style="min-width:100px;">Jenis Usaha</th><th style="min-width:95px;">Status</th><th class="text-end" style="min-width:130px;">Realisasi</th><th class="pegawai-detail-note">Keterangan</th></tr></thead>
            <tbody>
              @forelse($rows as $row)
                @php($record = $nominatif[$row->id])
                <tr wire:key="detail-{{ $scope }}-prospect-{{ $row->id }}">
                  <td>{{ $loop->iteration }}</td><td>{{ \Carbon\Carbon::parse($row->tanggal_prospek)->format('d/m/Y') }}</td>
                  <td>{{ $record['nama'] ?: '-' }}</td><td class="text-nowrap">{{ $row->no_rekening ?: '-' }}</td><td>{{ $row->jenis_produk ?: '-' }}</td><td>{{ $row->jenis_usaha ?: '-' }}</td>
                  <td><span class="recap-status recap-status-{{ strtolower(str_replace(' ', '-', $row->status)) }}">{{ $row->status ?: '-' }}</span></td>
                  <td class="text-end text-nowrap fw-semibold pegawai-detail-amount {{ $record['nominal'] !== null ? 'text-success' : 'text-muted' }}">{{ $record['nominal'] !== null ? 'Rp '.number_format($record['nominal'], 0, ',', '.') : '-' }}</td>
                  <td class="pegawai-detail-note {{ $record['status'] === 'unmatched' ? 'text-danger' : 'text-muted' }}">{{ $record['keterangan'] }}</td>
                </tr>
              @empty
                <tr><td colspan="9" class="text-center text-muted p-4">Belum ada pengajuan untuk produk, status, dan periode yang dipilih.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer justify-content-between gap-2">
        <div class="small text-muted">PDF memuat pengajuan Closing sesuai produk dan periode.
          @error('validationPdf')<div class="text-danger mt-1" role="alert">{{ $message }}</div>@enderror
        </div>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-primary rounded-pill px-3" wire:click="exportValidationPdf('{{ $scope }}')" wire:loading.attr="disabled" wire:target="exportValidationPdf">
            <span wire:loading.remove wire:target="exportValidationPdf"><i class="bi bi-file-earmark-pdf me-1"></i>Cetak PDF</span><span wire:loading wire:target="exportValidationPdf">Menyiapkan PDF...</span>
          </button>
          <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</div>
