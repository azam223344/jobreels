<?php

namespace App\Http\Livewire\Adoptions;

use App\Models\Transaction;
use Livewire\Component;
use Mediconesystems\LivewireDatatables\Column;

class AdoptionsIndexComponent extends Component
{

    public function render()
    {
        return view('livewire.adoptions.adoptions-index-component');
    }
}
