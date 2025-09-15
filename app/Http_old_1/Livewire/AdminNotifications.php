<?php

namespace App\Http\Livewire;

use App\Models\Notifications;
use Livewire\Component;

class AdminNotifications extends Component
{
    public $notifications;
    public function mount()
    {
       $this->notifications=Notifications::where('is_read', 0)->where('type','Adminapproval')->where('user_id',auth()->user()->id)->latest()->get();
       Notifications::where('is_read', 0)->where('type','Adminapproval')->where('user_id',auth()->user()->id)->update([
        'is_read'=>1,
       ]);
    }
    public function render()
    {
        return view('livewire.notifications');
    }
}
