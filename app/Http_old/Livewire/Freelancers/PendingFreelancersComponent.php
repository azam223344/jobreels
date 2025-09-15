<?php

namespace App\Http\Livewire\Freelancers;

use App\Models\User;
use Livewire\Component;

class PendingFreelancersComponent extends Component
{
    public function render()
    {
        return view('livewire.freelancers.pending-freelancers-component');
    }
}
