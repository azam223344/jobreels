<?php

namespace App\Http\Livewire\Hirers;

use App\Models\User;
use Livewire\Component;

class PendingHirersComponent extends Component
{
    public $users;

    public function mount()
    {
        $this->users = User::where('type', 'hirer')->where('active_publisher', 0)->orderby('created_at','desc')->get();
    }

    public function render()
    {
        return view('livewire.hirers.pending-hirers-component');
    }
}
