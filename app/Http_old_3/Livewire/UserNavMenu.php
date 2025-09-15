<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserNavMenu extends Component
{
    public $loggedIn = false;
    public $user;

    public function mount()
    {
        if(Auth::check()){
            $this->loggedIn = true;
            $this->user = Auth::user();
        }
    }

    public function render()
    {
        return view('livewire.user-nav-menu');
    }
}
