<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Surat Validasi Pengajuan Closing</title>
  <style>
    @page { size: A4 landscape; margin: 30px 38px 45px; }
    body { font-family: "DejaVu Sans", sans-serif; font-size: 9px; color: #182538; line-height: 1.45; }
    .letterhead { border-bottom: 2px solid #234a7c; padding-bottom: 10px; margin-bottom: 15px; }
    .brand { font-size: 16px; font-weight: bold; color: #234a7c; }
    .muted { color: #627085; }
    h1 { text-align: center; font-size: 14px; margin: 0 0 3px; letter-spacing: .5px; }
    .subtitle { text-align: center; font-size: 9px; color: #627085; margin-bottom: 15px; }
    .meta { width: 100%; margin-bottom: 9px; }
    .meta td { vertical-align: top; padding: 2px 0; }
    .meta .label { width: 100px; color: #627085; }
    .summary { width: 100%; margin: 10px 0 12px; border-collapse: collapse; }
    .summary td { width: 33.33%; padding: 8px 10px; background: #f0f5fb; border: 1px solid #d5dfeb; }
    .summary strong { font-size: 12px; }
    .records { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .records th { background: #e9eff7; color: #234a7c; font-size: 8px; text-align: left; }
    .records td, .records th { padding: 6px 5px; border: 1px solid #d5dfeb; vertical-align: top; overflow-wrap: break-word; }
    .records td { font-size: 8px; }
    thead { display: table-header-group; }
    tr { page-break-inside: avoid; }
    .right { text-align: right !important; }
    .amount { white-space: nowrap; }
    .note { font-size: 8px; color: #627085; margin: 8px 0; }
    .signature { width: 260px; margin: 20px 0 0 auto; text-align: center; page-break-inside: avoid; }
    .signature-space { height: 62px; }
    .signer-name { font-weight: bold; text-decoration: underline; }
    @media print { body { print-color-adjust: exact; -webkit-print-color-adjust: exact; } }
  </style>
</head>
<body>
  <div class="letterhead">
    <div class="brand">BANK BKK JATENG</div>
    <div>E-Prospek &nbsp; | &nbsp; {{ $branch ? $branch->kode_cabang.' - '.$branch->nama_cabang : 'Rekap Pengajuan Pegawai' }}</div>
  </div>
  <h1>SURAT VALIDASI PENGAJUAN CLOSING</h1>
  <div class="subtitle">{{ $scope === 'kc' ? 'Rekap pengajuan per kantor cabang' : 'Rekap pengajuan per pegawai' }}</div>
  <table class="meta">
    <tr><td class="label">{{ $scope === 'kc' ? 'Kantor cabang' : 'Pegawai pengaju' }}</td><td>: {{ $scope === 'kc' ? $subject->kode_cabang.' - '.$subject->nama_cabang : ($subject->nama_lengkap ?: $subject->name).' ('.$subject->name.')' }}</td><td class="label">Tanggal cetak</td><td style="width:175px;">: {{ $printedAt->translatedFormat('d F Y') }}</td></tr>
    <tr><td class="label">Periode prospek</td><td>: {{ $period }}</td><td class="label">Produk</td><td>: {{ $productLabel }}</td></tr>
  </table>
  <p>Berikut daftar pengajuan berstatus <strong>Closing</strong> untuk diperiksa dan divalidasi. Nama nasabah dan nominal realisasi mengacu pada data nominatif sesuai produk dan nomor rekening.</p>
  <table class="summary"><tr>
    @foreach(['all' => 'TOTAL REALISASI', 'kredit' => 'KREDIT', 'dana' => 'DANA'] as $key => $label)
      <td><div class="muted">{{ $label }}</div><strong>Rp {{ number_format($summary[$key]['nominal'], 0, ',', '.') }}</strong><div>{{ number_format($summary[$key]['noa'], 0, ',', '.') }} NOA</div></td>
    @endforeach
  </tr></table>
  <table class="records">
    <thead><tr>
      <th style="width:3%;">No</th><th style="width:9%;">Tanggal Prospek</th><th style="width:19%;">Nama Nasabah</th><th style="width:13%;">No. Rekening</th><th style="width:8%;">Produk</th><th style="width:10%;">Jenis Usaha</th><th style="width:7%;">Status</th><th class="right" style="width:13%;">Realisasi</th><th style="width:18%;">Keterangan</th>
    </tr></thead>
    <tbody>
      @foreach($items as $row)
        @php($record = $nominatif[$row->id])
        <tr>
          <td>{{ $loop->iteration }}</td><td>{{ \Carbon\Carbon::parse($row->tanggal_prospek)->format('d/m/Y') }}</td>
          <td>{{ $record['nama'] ?: '-' }}</td><td>{{ $row->no_rekening ?: '-' }}</td><td>{{ $row->jenis_produk }}</td>
          <td>{{ $row->jenis_usaha ?: '-' }}</td><td>Closing</td>
          <td class="right amount">{{ $record['nominal'] !== null ? 'Rp '.number_format($record['nominal'], 0, ',', '.') : '-' }}</td><td>{{ $record['keterangan'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <p class="note">Jumlah pengajuan Closing: {{ $items->count() }}. Total realisasi dan NOA dihitung dari rekening unik per produk yang cocok dengan nominatif. Rekening yang belum cocok tidak dimasukkan dalam total realisasi dan NOA.</p>
  <div class="signature">
    <div>{{ $printedAt->translatedFormat('d F Y') }}</div>
    <div>{{ $scope === 'kc' ? 'Kepala Cabang' : 'Yang Mengajukan' }}</div>
    @if($scope === 'kc')<div>{{ $branch->nama_cabang }}</div>@endif
    <div class="signature-space"></div>
    <div class="signer-name">{{ $signer ? ($signer->nama_lengkap ?: $signer->name) : '(........................................)' }}</div>
    @if($signer)<div>ID Pegawai: {{ $signer->name }}</div>@else<div class="note">Nama Kepala Cabang belum dapat ditentukan dari data pegawai aktif.</div>@endif
  </div>
</body>
</html>
