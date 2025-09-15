<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class UsersIndexComponent extends Component
{
    public $users;

    public function mount()
    {
        $this->users = User::where('active_publisher',1)->orderby('created_at','desc')->get();
    }

    public function render()
    {
        return view('livewire.users.users-index-component');
    }
}
