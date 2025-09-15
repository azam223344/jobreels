<?php

namespace App\Http\Livewire\Hirers;

use App\Models\User;
use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Facades\Route; // Import the Route facade

class HirerProfileComponent extends Component
{
    public $user;
    public $userposts;
    public function mount()
    {
        // Get the 'userid' from the route parameters
        $userid = Route::current()->parameter('userId');      
        // Fetch the user data based on the 'userid'
        $this->user = User::find($userid);
        $this->userposts = Post::where('user_id',$userid)->where('is_approved_by_admin',1)->get();
    }

    public function render()
    {
        return view('livewire.hirers.hirer-profile-component');
    }
}