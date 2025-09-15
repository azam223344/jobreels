<?php

namespace App\Http\Livewire\Freelancers;

use App\Models\User;
use Livewire\Component;

class PendingFreelancersComponent extends Component
{
    public $users;
    public $totalFreelancers;
    public $approvedFreelancers;
    public $pendingFreelancers;
    public function mount()
    {
        $this->users = User::orderby('users.created_at','desc')->where('type', 'freelancer')->where('active_publisher',0)->get();
        $this->approvedFreelancers= $this->users->count();
        $this->totalFreelancers = User::orderby('users.created_at','desc')->where('type', 'freelancer')->count();
        $this->pendingFreelancers =  User::orderby('users.created_at','desc')->where('type', 'freelancer')->where('active_publisher',1)->count();
    }
    public function render()
    {
        return view('livewire.freelancers.pending-freelancers-component');
    }
}
