<?php

namespace App\Livewire\SimulasiKredit;

use Livewire\Component;

class Index extends Component
{
    public string $produk = 'KMB';
    public string $plafon = '';
    public string $jangka_waktu = '';

    public array $produkOptions = [];
    public array $tenorOptions = [];

    public ?array $selectedRule = null;

    public float $bungaPersen = 0;
    public string $bungaLabel = '';
    public string $metodeAngsuran = '';

    public float $provisiPersen = 1.5;
    public float $provisiNominal = 0;
    public float $biayaAdmin = 0;
    public float $penerimaanBersih = 0;

    public float $angsuranPokok = 0;
    public float $angsuranBunga = 0;
    public float $angsuranPerBulan = 0;
    public float $totalBunga = 0;
    public float $totalPengembalian = 0;

    public string $catatan = '';

    public function mount(): void
    {
        $this->produkOptions = $this->getProdukConfig();
        $this->refreshTenorOptions();
        $this->produk = 'KMB';
        $this->plafon = '';
        $this->jangka_waktu = '';
        $this->hitung();
    }

    public function updatedProduk(): void
    {
        $this->jangka_waktu = '';
        $this->refreshTenorOptions();
        $this->hitung();
    }

    public function updatedPlafon(): void
    {
        $this->plafon = preg_replace('/[^0-9]/', '', (string) $this->plafon);
        $this->hitung();
    }

    public function updatedJangkaWaktu(): void
    {
        $this->hitung();
    }

    protected function getProdukConfig(): array
    {
        return [
            [
                'kode' => 'KMB',
                'nama' => 'Kredit Mikro BKK (KMB)',
                'min_plafon' => 1000000,
                'max_plafon' => 1000000000,
                'rules' => [
                    [
                        'tenor_min' => 1,
                        'tenor_max' => 36,
                        'bunga' => 9.00,
                        'tipe_bunga' => 'flat',
                        'metode' => 'flat_bulanan',
                        'label' => 'Angsuran tetap (pokok + bunga) per bulan',
                    ],
                    [
                        'tenor_min' => 37,
                        'tenor_max' => 60,
                        'bunga' => 12.00,
                        'tipe_bunga' => 'flat',
                        'metode' => 'flat_bulanan',
                        'label' => 'Angsuran tetap (pokok + bunga) per bulan',
                    ],
                ],
            ],
            [
                'kode' => 'JOGLO',
                'nama' => 'Kredit BKK Joglo',
                'min_plafon' => 1000000,
                'max_plafon' => 3000000000,
                'rules' => [
                    [
                        'tenor_min' => 1,
                        'tenor_max' => 60,
                        'bunga' => 10.50,
                        'tipe_bunga' => 'flat',
                        'metode' => 'flat_bulanan',
                        'label' => 'Angsuran tetap (pokok + bunga) per bulan',
                    ],
                    [
                        'tenor_min' => 61,
                        'tenor_max' => 120,
                        'bunga' => 11.00,
                        'tipe_bunga' => 'flat',
                        'metode' => 'flat_bulanan',
                        'label' => 'Angsuran tetap (pokok + bunga) per bulan',
                    ],
                    [
                        'tenor_min' => 121,
                        'tenor_max' => 180,
                        'bunga' => 12.00,
                        'tipe_bunga' => 'flat',
                        'metode' => 'flat_bulanan',
                        'label' => 'Angsuran tetap (pokok + bunga) per bulan',
                        'catatan' => 'Suku bunga dapat ditinjau ulang untuk tenor di atas 120 bulan.',
                    ],
                ],
            ],
            [
                'kode' => 'SINDEN',
                'nama' => 'Kredit BKK Sinden',
                'min_plafon' => 1000000,
                'max_plafon' => 1000000000,
                'rules' => [
                    ['tenor_min' => 1, 'tenor_max' => 36, 'bunga' => 12.00, 'tipe_bunga' => 'flat', 'metode' => 'flat_bulanan', 'label' => 'Angsuran tetap (pokok + bunga) per bulan'],
                    ['tenor_min' => 37, 'tenor_max' => 60, 'bunga' => 14.00, 'tipe_bunga' => 'flat', 'metode' => 'flat_bulanan', 'label' => 'Angsuran tetap (pokok + bunga) per bulan'],
                    ['tenor_min' => 61, 'tenor_max' => 84, 'bunga' => 15.00, 'tipe_bunga' => 'flat', 'metode' => 'flat_bulanan', 'label' => 'Angsuran tetap (pokok + bunga) per bulan'],
                ],
            ],
            [
                'kode' => 'KORPORASI',
                'nama' => 'Kredit BKK Korporasi',
                'min_plafon' => 100000000,
                'max_plafon' => 10000000000,
                'rules' => [
                    [
                        'tenor_min' => 1,
                        'tenor_max' => 12,
                        'bunga' => 15.00,
                        'tipe_bunga' => 'flat',
                        'metode' => 'bunga_bulanan_pokok_jatuh_tempo',
                        'label' => 'Bunga dibayar tiap bulan, pokok dibayar saat jatuh tempo',
                    ],
                    [
                        'tenor_min' => 1,
                        'tenor_max' => 60,
                        'bunga' => 20.00,
                        'tipe_bunga' => 'efektif',
                        'metode' => 'anuitas',
                        'label' => 'Angsuran anuitas (pokok + bunga efektif)',
                    ],
                ],
            ],
            [
                'kode' => 'BUMDES',
                'nama' => 'Kredit BKK BUMDES',
                'min_plafon' => 50000000,
                'max_plafon' => 1000000000,
                'rules' => [
                    [
                        'tenor_min' => 1,
                        'tenor_max' => 12,
                        'bunga' => 15.00,
                        'tipe_bunga' => 'flat',
                        'metode' => 'bunga_bulanan_pokok_jatuh_tempo',
                        'label' => 'Bunga dibayar tiap bulan, pokok dibayar saat jatuh tempo',
                    ],
                    [
                        'tenor_min' => 1,
                        'tenor_max' => 60,
                        'bunga' => 12.00,
                        'tipe_bunga' => 'flat',
                        'metode' => 'flat_bulanan',
                        'label' => 'Angsuran tetap (pokok + bunga) per bulan',
                    ],
                ],
            ],
            [
                'kode' => 'K3',
                'nama' => 'Kredit Kolektif Karyawan (K3)',
                'min_plafon' => 1000000,
                'max_plafon' => 200000000,
                'rules' => [
                    [
                        'tenor_min' => 1,
                        'tenor_max' => 12,
                        'bunga' => 16.00,
                        'tipe_bunga' => 'flat',
                        'metode' => 'bunga_bulanan_pokok_jatuh_tempo',
                        'label' => 'Bunga dibayar tiap bulan, pokok dibayar saat jatuh tempo',
                    ],
                    [
                        'tenor_min' => 1,
                        'tenor_max' => 36,
                        'bunga' => 10.00,
                        'tipe_bunga' => 'flat',
                        'metode' => 'flat_bulanan',
                        'label' => 'Angsuran tetap (pokok + bunga) per bulan',
                    ],
                    [
                        'tenor_min' => 37,
                        'tenor_max' => 60,
                        'bunga' => 11.00,
                        'tipe_bunga' => 'flat',
                        'metode' => 'flat_bulanan',
                        'label' => 'Angsuran tetap (pokok + bunga) per bulan',
                    ],
                    [
                        'tenor_min' => 61,
                        'tenor_max' => 120,
                        'bunga' => 12.00,
                        'tipe_bunga' => 'flat',
                        'metode' => 'flat_bulanan',
                        'label' => 'Angsuran tetap (pokok + bunga) per bulan',
                    ],
                ],
            ],
            [
                'kode' => 'KUB',
                'nama' => 'Kredit Umum BKK (KUB)',
                'min_plafon' => 1000000,
                'max_plafon' => 500000000,
                'rules' => [
                    ['tenor_min' => 1, 'tenor_max' => 60, 'bunga' => 13.00, 'tipe_bunga' => 'flat', 'metode' => 'flat_bulanan', 'label' => 'Angsuran tetap (pokok + bunga) per bulan'],
                ],
            ],
            [
                'kode' => 'KKPP',
                'nama' => 'Kredit Kesejahteraan Pengurus Dan Pegawai (KKPP)',
                'min_plafon' => 1000000,
                'max_plafon' => 1000000000,
                'rules' => [
                    ['tenor_min' => 1, 'tenor_max' => 180, 'bunga' => 5.00, 'tipe_bunga' => 'flat', 'metode' => 'flat_bulanan', 'label' => 'Angsuran tetap (pokok + bunga) per bulan'],
                ],
            ],
            [
                'kode' => 'JOGLO_MITRA',
                'nama' => 'Kredit BKK Joglo Mitra',
                'min_plafon' => 100000000,
                'max_plafon' => 15000000000,
                'rules' => [
                    ['tenor_min' => 1, 'tenor_max' => 36, 'bunga' => 20.00, 'tipe_bunga' => 'efektif', 'metode' => 'anuitas', 'label' => 'Angsuran anuitas (pokok + bunga efektif)'],
                    ['tenor_min' => 1, 'tenor_max' => 12, 'bunga' => 15.00, 'tipe_bunga' => 'flat', 'metode' => 'bunga_bulanan_pokok_jatuh_tempo', 'label' => 'Bunga dibayar tiap bulan, pokok dibayar saat jatuh tempo'],
                ],
            ],
            [
                'kode' => 'BAHARI_PERORANGAN',
                'nama' => 'Kredit Bahari - Debitur Perorangan',
                'min_plafon' => 1000000,
                'max_plafon' => 1000000000,
                'rules' => [
                    ['tenor_min' => 1, 'tenor_max' => 36, 'bunga' => 10.50, 'tipe_bunga' => 'flat', 'metode' => 'flat_bulanan', 'label' => 'Angsuran tetap (pokok + bunga) per bulan'],
                    ['tenor_min' => 37, 'tenor_max' => 60, 'bunga' => 12.00, 'tipe_bunga' => 'flat', 'metode' => 'flat_bulanan', 'label' => 'Angsuran tetap (pokok + bunga) per bulan'],
                    ['tenor_min' => 1, 'tenor_max' => 12, 'bunga' => 15.00, 'tipe_bunga' => 'flat', 'metode' => 'bunga_bulanan_pokok_jatuh_tempo', 'label' => 'Bunga dibayar tiap bulan, pokok dibayar saat jatuh tempo'],
                ],
            ],
            [
                'kode' => 'BAHARI_KELOMPOK',
                'nama' => 'Kredit Bahari - Debitur Kelompok',
                'min_plafon' => 25000000,
                'max_plafon' => 2000000000,
                'rules' => [
                    ['tenor_min' => 1, 'tenor_max' => 36, 'bunga' => 13.00, 'tipe_bunga' => 'flat', 'metode' => 'flat_bulanan', 'label' => 'Angsuran tetap (pokok + bunga) per bulan'],
                    ['tenor_min' => 1, 'tenor_max' => 12, 'bunga' => 18.00, 'tipe_bunga' => 'flat', 'metode' => 'bunga_bulanan_pokok_jatuh_tempo', 'label' => 'Bunga dibayar tiap bulan, pokok dibayar saat jatuh tempo'],
                ],
            ],
            [
                'kode' => 'AGRO_PERORANGAN',
                'nama' => 'Kredit Agro - Debitur Perorangan',
                'min_plafon' => 1000000,
                'max_plafon' => 500000000,
                'rules' => [
                    ['tenor_min' => 1, 'tenor_max' => 36, 'bunga' => 10.50, 'bunga_max' => 14.00, 'tipe_bunga' => 'flat', 'metode' => 'flat_bulanan', 'label' => 'Angsuran tetap (pokok + bunga) per bulan'],
                    ['tenor_min' => 1, 'tenor_max' => 12, 'bunga' => 15.00, 'tipe_bunga' => 'flat', 'metode' => 'bunga_bulanan_pokok_jatuh_tempo', 'label' => 'Bunga dibayar tiap bulan, pokok dibayar saat jatuh tempo'],
                ],
            ],
            [
                'kode' => 'AGRO_KELOMPOK',
                'nama' => 'Kredit Agro - Debitur Kelompok/Gabungan Kelompok',
                'min_plafon' => 25000000,
                'max_plafon' => 1000000000,
                'rules' => [
                    ['tenor_min' => 1, 'tenor_max' => 36, 'bunga' => 11.00, 'bunga_max' => 15.00, 'tipe_bunga' => 'flat', 'metode' => 'flat_bulanan', 'label' => 'Angsuran tetap (pokok + bunga) per bulan'],
                    ['tenor_min' => 1, 'tenor_max' => 12, 'bunga' => 15.00, 'tipe_bunga' => 'flat', 'metode' => 'bunga_bulanan_pokok_jatuh_tempo', 'label' => 'Bunga dibayar tiap bulan, pokok dibayar saat jatuh tempo'],
                ],
            ],
        ];
    }

    protected function refreshTenorOptions(): void
    {
        $this->tenorOptions = [];
        $produk = $this->findProduk($this->produk);

        if (!$produk) {
            return;
        }

        $map = [];
        foreach ($produk['rules'] as $rule) {
            for ($i = $rule['tenor_min']; $i <= $rule['tenor_max']; $i++) {
                $map[$i] = $i;
            }
        }

        ksort($map);

        foreach ($map as $bulan) {
            $this->tenorOptions[] = [
                'id' => $bulan,
                'label' => $bulan . ' Bulan',
            ];
        }
    }

    protected function findProduk(string $kode): ?array
    {
        foreach ($this->produkOptions as $item) {
            if ($item['kode'] === $kode) {
                return $item;
            }
        }
        return null;
    }

    protected function findRule(?array $produk, int $tenor): ?array
    {
        if (!$produk) {
            return null;
        }

        foreach ($produk['rules'] as $rule) {
            if ($tenor >= $rule['tenor_min'] && $tenor <= $rule['tenor_max']) {
                return $rule;
            }
        }

        return null;
    }

    protected function getBiayaAdmin(float $plafon): float
    {
        if ($plafon <= 100000000) {
            return 100000;
        }
        if ($plafon <= 250000000) {
            return 250000;
        }
        if ($plafon <= 500000000) {
            return 500000;
        }
        if ($plafon <= 1000000000) {
            return 1000000;
        }
        return 5000000;
    }

    protected function resetHasil(): void
    {
        $this->selectedRule = null;
        $this->bungaPersen = 0;
        $this->bungaLabel = '';
        $this->metodeAngsuran = '';
        $this->provisiNominal = 0;
        $this->biayaAdmin = 0;
        $this->penerimaanBersih = 0;
        $this->angsuranPokok = 0;
        $this->angsuranBunga = 0;
        $this->angsuranPerBulan = 0;
        $this->totalBunga = 0;
        $this->totalPengembalian = 0;
        $this->catatan = '';
    }

    public function hitung(): void
    {
        $this->resetHasil();

        $plafon = (float) preg_replace('/[^0-9]/', '', (string) $this->plafon);
        $tenor  = (int) $this->jangka_waktu;
        $produk = $this->findProduk($this->produk);

        if (!$produk || $plafon <= 0 || $tenor <= 0) {
            return;
        }

        if ($plafon < $produk['min_plafon'] || $plafon > $produk['max_plafon']) {
            $this->catatan = 'Plafon tidak sesuai dengan batas produk yang dipilih.';
            return;
        }

        $rule = $this->findRule($produk, $tenor);
        if (!$rule) {
            $this->catatan = 'Jangka waktu tidak tersedia untuk produk yang dipilih.';
            return;
        }

        $this->selectedRule = $rule;
        $this->bungaPersen = (float) $rule['bunga'];
        $this->bungaLabel = isset($rule['bunga_max'])
            ? number_format((float) $rule['bunga'], 2, ',', '.') . '% - ' . number_format((float) $rule['bunga_max'], 2, ',', '.') . '% p.a. ' . $rule['tipe_bunga']
            : number_format((float) $rule['bunga'], 2, ',', '.') . '% p.a. ' . $rule['tipe_bunga'];

        $this->metodeAngsuran = $rule['label'];

        $this->provisiNominal = round($plafon * ($this->provisiPersen / 100), 2);
        $this->biayaAdmin = $this->getBiayaAdmin($plafon);
        $this->penerimaanBersih = round($plafon - $this->provisiNominal - $this->biayaAdmin, 2);

        if ($rule['metode'] === 'flat_bulanan') {
            $this->angsuranPokok = round($plafon / $tenor, 2);
            $this->angsuranBunga = round(($plafon * ($this->bungaPersen / 100)) / 12, 2);
            $this->angsuranPerBulan = round($this->angsuranPokok + $this->angsuranBunga, 2);
            $this->totalBunga = round($this->angsuranBunga * $tenor, 2);
            $this->totalPengembalian = round($plafon + $this->totalBunga, 2);
        } elseif ($rule['metode'] === 'bunga_bulanan_pokok_jatuh_tempo') {
            $this->angsuranPokok = 0;
            $this->angsuranBunga = round(($plafon * ($this->bungaPersen / 100)) / 12, 2);
            $this->angsuranPerBulan = $this->angsuranBunga;
            $this->totalBunga = round($this->angsuranBunga * $tenor, 2);
            $this->totalPengembalian = round($plafon + $this->totalBunga, 2);
            $this->catatan = 'Pokok dibayar sekaligus pada saat jatuh tempo.';
        } elseif ($rule['metode'] === 'anuitas') {
            $r = ($this->bungaPersen / 100) / 12;
            if ($r > 0) {
                $this->angsuranPerBulan = round(($plafon * $r) / (1 - pow(1 + $r, -$tenor)), 2);
                $this->angsuranBunga = round($plafon * $r, 2);
                $this->angsuranPokok = round($this->angsuranPerBulan - $this->angsuranBunga, 2);
                $this->totalPengembalian = round($this->angsuranPerBulan * $tenor, 2);
                $this->totalBunga = round($this->totalPengembalian - $plafon, 2);
            }
        }

        if (!empty($rule['catatan'])) {
            $this->catatan = trim(($this->catatan ? $this->catatan . ' ' : '') . $rule['catatan']);
        }
    }

    public function render()
    {
        return view('livewire.simulasi-kredit.index', [
            'plafonMinProduk' => $this->findProduk($this->produk)['min_plafon'] ?? 0,
            'plafonMaxProduk' => $this->findProduk($this->produk)['max_plafon'] ?? 0,
        ])->layout('layouts.bootstrap');
    }
}
