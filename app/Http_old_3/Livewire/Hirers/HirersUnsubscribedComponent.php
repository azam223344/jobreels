<?php

namespace App\Http\Livewire\Hirers;

use App\Models\User;
use Livewire\Component;

class HirersUnsubscribedComponent extends Component
{
    public $users;
    public $totalsubscribed;
    public $approvedsubscribed;
    public $pendingsubscribed;
    public function mount()
    {
        $this->users = User::orderby('users.created_at','desc')->where('type','hirer')->get();
        $this->approvedsubscribed= $this->users->count();
        $this->totalsubscribed = User::orderby('users.created_at','desc')->where('type','hirer')->count();
        $this->pendingsubscribed = User::orderby('users.created_at','desc')->where('type','hirer')->count();
    }
    public function render()
    {
        return view('livewire.hirers.hirers-unsubscribed-component');
    }
    public function showUserProfile($userId)
    {
        $this->emit('showUserProfile', $userId);
    }
}
