<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Notifications; // Replace with your actual model namespace
use Illuminate\Http\JsonResponse;
class NotificationCount extends Component
{
    public $count;

    public function mount()
    {
        // Initialize the count
        $this->count = $this->getUnreadCount();
    }

    public function getUnreadCount()
    {
        // Calculate the unread count
        return Notifications::where('is_read', 0)->where('type','Adminapproval')->where('user_id',auth()->user()->id)->count();
    }

    public function render()
    {
        return view('livewire.notification-count');
    }


    public function getNotificationCount(): JsonResponse
    {
        $count = Notifications::where('is_read', 0)->where('type','Adminapproval')->where('user_id',auth()->user()->id)->count();
    
        return response()->json(['count' => $count]);
    }
}
