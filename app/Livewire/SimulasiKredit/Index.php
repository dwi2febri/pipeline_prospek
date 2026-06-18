<?php

namespace App\Livewire\SimulasiKredit;

use Livewire\Component;

class Index extends Component
{
    public string $produk = 'mikro';
    public string $pegawai = 'internal';
    public string $metode = 'flat';
    public string $pinjaman = '100000000';
    public string $tenor = '60';
    public float $bunga = 0;

    public float $angsuranPerBulan = 0;
    public float $totalBunga = 0;
    public float $totalPembayaran = 0;
    public array $jadwalAngsuran = [];
    public string $catatan = '';

    public array $produkOptions = [
        'mikro' => 'BKK Mikro',
        'agrari' => 'BKK Agrari',
        'joglo' => 'BKK Joglo',
        'migunani' => 'BKK Migunani',
        'makaryo' => 'BKK Makaryo',
    ];

    public function mount(): void
    {
        $this->hitung();
    }

    public function updatedProduk(): void
    {
        $this->updateBunga();
        $this->hitung();
    }

    public function updatedPegawai(): void
    {
        $this->updateBunga();
        $this->hitung();
    }

    public function updatedMetode(): void
    {
        $this->updateBunga();
        $this->hitung();
    }

    public function updatedPinjaman(): void
    {
        $this->pinjaman = preg_replace('/[^0-9]/', '', (string) $this->pinjaman) ?: '0';
        $this->hitung();
    }

    public function updatedTenor(): void
    {
        $this->tenor = preg_replace('/[^0-9]/', '', (string) $this->tenor) ?: '0';
        $this->updateBunga();
        $this->hitung();
    }

    public function hitung(): void
    {
        $this->resetHasil();
        $this->updateBunga();

        $pinjaman = (float) preg_replace('/[^0-9]/', '', (string) $this->pinjaman);
        $tenor = max(0, (int) $this->tenor);

        if ($pinjaman <= 0 || $tenor <= 0) {
            $this->catatan = 'Masukkan nominal pinjaman dan tenor terlebih dahulu.';
            return;
        }

        $r = $this->bunga / 12 / 100;
        $saldo = $pinjaman;

        if ($this->metode === 'flat') {
            $pokok = $pinjaman / $tenor;
            $bungaFlat = $pinjaman * $r;
            $angsuran = $pokok + $bungaFlat;

            $this->angsuranPerBulan = round($angsuran, 2);
            $this->totalBunga = round($bungaFlat * $tenor, 2);
            $this->totalPembayaran = round($pinjaman + $this->totalBunga, 2);

            for ($bulan = 1; $bulan <= $tenor; $bulan++) {
                $saldo -= $pokok;
                $this->jadwalAngsuran[] = [
                    'bulan' => $bulan,
                    'pokok' => round($pokok, 2),
                    'bunga' => round($bungaFlat, 2),
                    'angsuran' => round($angsuran, 2),
                    'sisa_pokok' => round(max(0, $saldo), 2),
                ];
            }

            return;
        }

        if ($r <= 0) {
            $this->catatan = 'Bunga tidak valid untuk metode anuitas.';
            return;
        }

        $angsuran = $pinjaman * ($r * pow(1 + $r, $tenor)) / (pow(1 + $r, $tenor) - 1);
        $this->angsuranPerBulan = round($angsuran, 2);
        $this->totalPembayaran = round($angsuran * $tenor, 2);
        $this->totalBunga = round($this->totalPembayaran - $pinjaman, 2);

        for ($bulan = 1; $bulan <= $tenor; $bulan++) {
            $bunga = $saldo * $r;
            $pokok = $angsuran - $bunga;
            $saldo -= $pokok;

            $this->jadwalAngsuran[] = [
                'bulan' => $bulan,
                'pokok' => round($pokok, 2),
                'bunga' => round($bunga, 2),
                'angsuran' => round($angsuran, 2),
                'sisa_pokok' => round(max(0, $saldo), 2),
            ];
        }
    }

    protected function updateBunga(): void
    {
        $tenor = (int) $this->tenor;
        $this->catatan = '';

        if (in_array($this->produk, ['mikro', 'agrari'], true)) {
            $this->bunga = $tenor <= 36
                ? ($this->metode === 'flat' ? 9 : 15)
                : ($this->metode === 'flat' ? 11 : 18);
            return;
        }

        if ($this->produk === 'joglo') {
            if ($tenor <= 36) {
                $this->bunga = $this->metode === 'flat' ? 10.5 : 17;
            } elseif ($tenor <= 60) {
                $this->bunga = $this->metode === 'flat' ? 12 : 20;
            } else {
                if ($this->metode === 'flat') {
                    $this->metode = 'anuitas';
                    $this->catatan = 'Tenor BKK Joglo di atas 60 bulan otomatis memakai metode anuitas.';
                }
                $this->bunga = 21;
            }
            return;
        }

        if ($this->produk === 'migunani') {
            $this->bunga = $tenor <= 36
                ? ($this->metode === 'flat' ? 13 : 20)
                : ($this->metode === 'flat' ? 15 : 21);
            return;
        }

        if ($this->produk === 'makaryo') {
            if ($this->pegawai === 'internal') {
                $this->metode = 'flat';
                $this->bunga = 6;
                $this->catatan = 'BKK Makaryo pegawai internal otomatis memakai metode flat.';
                return;
            }

            if ($tenor <= 60) {
                $this->bunga = $this->metode === 'flat' ? 9 : 15;
                return;
            }

            if ($this->metode === 'flat') {
                $this->metode = 'anuitas';
                $this->catatan = 'BKK Makaryo eksternal tenor di atas 60 bulan otomatis memakai metode anuitas.';
            }
            $this->bunga = 16;
        }
    }

    protected function resetHasil(): void
    {
        $this->angsuranPerBulan = 0;
        $this->totalBunga = 0;
        $this->totalPembayaran = 0;
        $this->jadwalAngsuran = [];
    }

    public function render()
    {
        return view('livewire.simulasi-kredit.index')->layout('layouts.bootstrap');
    }
}
