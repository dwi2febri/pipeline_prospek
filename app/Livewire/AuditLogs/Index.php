<?php

namespace App\Livewire\AuditLogs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AuditLog;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $q = AuditLog::query()->with('user')->latest('id');

        if (trim($this->search) !== '') {
            $s = '%' . trim($this->search) . '%';
            $q->where(function ($w) use ($s) {
                $w->where('action', 'like', $s)
                  ->orWhere('type', 'like', $s)
                  ->orWhere('model_id', 'like', $s)
                  ->orWhere('ip', 'like', $s)
                  ->orWhere('ip_address', 'like', $s)
                  ->orWhere('actor_name', 'like', $s)
                  ->orWhere('auditable_type', 'like', $s)
                  ->orWhere('auditable_id', 'like', $s)
                  ->orWhere('meta', 'like', $s);
            });
        }

        $items = $q->paginate(15);

        return view('livewire.audit-logs.index', compact('items'))
            ->layout('layouts.bootstrap');
    }
}
