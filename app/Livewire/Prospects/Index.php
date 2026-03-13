<?php

namespace App\Livewire\Prospects;

use App\Models\Prospect;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $status  = 'ALL';
    public string $search  = '';
    public string $periode = 'bulan_ini';

    protected $queryString = [
        'status'  => ['except' => 'ALL'],
        'search'  => ['except' => ''],
        'periode' => ['except' => 'bulan_ini'],
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
        $this->status = $s;
        $this->resetPage();
    }

    public function trash(int $id): void
    {
        $p = Prospect::findOrFail($id);
        $u = Auth::user();

        if ((int) $p->input_by !== (int) $u->id) {
            abort(403);
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
                  ->orWhere('alamat', 'like', $s);
            });
        }

        return $query;
    }

    public function render()
    {
        $baseSummary = $this->baseUserQuery();

        $summary = [
            'TOTAL'     => (clone $baseSummary)->count(),
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

        return view('livewire.prospects.index', [
            'items'   => $items,
            'summary' => $summary,
        ])->layout('layouts.bootstrap');
    }
}
