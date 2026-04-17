<?php

namespace App\Livewire\SimulasiKredit;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.simulasi-kredit.index')
            ->layout('layouts.bootstrap');
    }
}
