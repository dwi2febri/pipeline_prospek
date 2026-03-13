<?php

namespace App\Livewire\AuditLogs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AuditLog;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $q = AuditLog::query()->with('user')->latest('id');

        if ($this->search !== '') {
            $s = '%' . $this->search . '%';
            $q->where(function($w) use ($s){
                $w->where('action','like',$s)
                  ->orWhere('type','like',$s)
                  ->orWhere('model_id','like',$s)
                  ->orWhere('ip','like',$s)
                  ->orWhere('actor_name','like',$s);
            });
        }

        $items = $q->paginate(15);

        return view('livewire.audit-logs.index', compact('items'))
            ->layout('layouts.bootstrap');
    }
}
