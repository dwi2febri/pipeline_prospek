<?php

namespace App\Livewire\Prospects;

use App\Models\Prospect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $status  = 'ALL';
    public string $search  = '';
    public string $periode = 'semua';

    protected $queryString = [
        'status'  => ['except' => 'ALL'],
        'search'  => ['except' => ''],
        'periode' => ['except' => 'semua'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPeriode(): void
    {
        $this->resetPage();
    }

    public function setStatus(string $s): void
    {
        $allowed = ['ALL', 'OPEN', 'FOLLOW UP', 'REJECTED', 'CLOSING'];

        if (!in_array($s, $allowed, true)) {
            return;
        }

        $this->status = $s;
        $this->resetPage();
    }

    public function trash(int $id): void
    {
        $u = Auth::user();

        $p = Prospect::query()
            ->where('id', $id)
            ->where('input_by', $u->id)
            ->firstOrFail();

        if (strtoupper(trim((string) $p->status)) === 'CLOSING') {
            session()->flash('error', 'Prospek berstatus Closing tidak dapat dihapus.');
            return;
        }

        $p->delete();

        session()->flash('ok', 'Data dipindahkan ke Recycle Bin.');

        $this->resetPage();
    }

    protected function baseUserQuery()
    {
        $user = Auth::user();

        return Prospect::query()
            ->with(['cabang', 'creator'])
            ->where('input_by', $user->id);
    }

    protected function applyPeriode($query)
    {
        if ($this->periode === 'hari_ini') {
            $query->whereDate('tanggal_prospek', now()->toDateString());
        } elseif ($this->periode === 'bulan_ini') {
            $query->whereMonth('tanggal_prospek', now()->month)
                  ->whereYear('tanggal_prospek', now()->year);
        }

        return $query;
    }

    protected function applySearch($query)
    {
        if (trim($this->search) !== '') {
            $s = '%' . trim($this->search) . '%';

            $query->where(function ($w) use ($s) {
                $w->where('nama', 'like', $s)
                  ->orWhere('no_hp', 'like', $s)
                  ->orWhere('nik', 'like', $s)
                  ->orWhere('alamat', 'like', $s)
                  ->orWhere('status', 'like', $s)
                  ->orWhere('jenis_produk', 'like', $s);
            });
        }

        return $query;
    }

    protected function getProdukClass(?string $produk): string
    {
        $produk = strtoupper(trim((string) $produk));

        if ($produk === 'KREDIT' || $produk === 'PINJAMAN') {
            return 'produk-kredit';
        }

        if ($produk === 'TABUNGAN' || $produk === 'SIMPANAN') {
            return 'produk-tabungan';
        }

        if ($produk === 'DEPOSITO') {
            return 'produk-deposito';
        }

        if ($produk === 'ASET' || $produk === 'K-ERIS' || $produk === 'KERIS') {
            return 'produk-aset';
        }

        return 'produk-default';
    }

    public function render()
    {
        $baseSummary = $this->baseUserQuery();

        $summary = [
            'TOTAL'     => (clone $baseSummary)->count(),
            'OPEN'      => (clone $baseSummary)->where('status', 'OPEN')->count(),
            'FOLLOW UP' => (clone $baseSummary)->where('status', 'FOLLOW UP')->count(),
            'REJECTED'  => (clone $baseSummary)->where('status', 'REJECTED')->count(),
            'CLOSING'   => (clone $baseSummary)->where('status', 'CLOSING')->count(),
        ];

        $itemsQuery = $this->baseUserQuery();

        $this->applyPeriode($itemsQuery);
        $this->applySearch($itemsQuery);

        if ($this->status !== 'ALL') {
            $itemsQuery->where('status', $this->status);
        }

        $items = $itemsQuery
            ->latest('tanggal_prospek')
            ->latest('id')
            ->paginate(5);

        $katalogProduk = DB::table('katalog_produk')
            ->where('aktif', 1)
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $row->gambar_url = !empty($row->gambar) ? Storage::url($row->gambar) : null;
                $row->detail_url = route('contents.show', [
                    'jenis' => 'produk',
                    'slug'  => $row->slug,
                ]);
                return $row;
            });

        $tipsTrik = DB::table('tips_trik')
            ->where('aktif', 1)
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $row->gambar_url = !empty($row->gambar) ? Storage::url($row->gambar) : null;
                $row->detail_url = route('contents.show', [
                    'jenis' => 'tips',
                    'slug'  => $row->slug,
                ]);
                return $row;
            });

        return view('livewire.prospects.index', [
            'items'         => $items,
            'summary'       => $summary,
            'katalogProduk' => $katalogProduk,
            'tipsTrik'      => $tipsTrik,
        ])->layout('layouts.bootstrap');
    }
}
