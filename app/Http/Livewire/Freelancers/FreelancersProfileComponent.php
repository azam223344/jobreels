<?php

namespace App\Http\Livewire\Freelancers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\DeviceToken;
use App\Models\Notifications;
use Livewire\Component;
use App\Models\Post;
use App\Models\FlagedContent;
use App\Jobs\SendEmailJob;
use App\Models\FlagedUser;
use App\Models\ApprovalHistory;
use Illuminate\Support\Facades\Route; // Import the Route facade

class FreelancersProfileComponent extends Component
{
    public $user;
    public $userposts;
    public $user_id;
    public $adoptionIn;
    public $adoptionOut;
    public $myreports;
    public $otherreports;
    public $beforepublish;
    public $queryString = ['user_id'];
    protected function rules(){
        return [
            'user.active_publisher' => 'required|boolean',
            'user.active' => 'required|boolean'
        ];
    }

    public function update()
    {  
        $this->beforepublish=$this->user->active_publisher;
        if($this->beforepublish===true){
         ApprovalHistory::create([
             'user_id'=>$this->user->id,
              'type'=>'Approved'
         ]);
     }elseif($this->beforepublish===false){
         ApprovalHistory::create([
             'user_id'=>$this->user->id,
              'type'=>'Deactive'
         ]);
     }
        $this->user->update();

        if ($this->user->active_publisher)
        {
            // FireBAse Notification
            $body = "Your account is approved now";
            $device_tokens = DeviceToken::where('user_id', $this->user->id)->pluck('value')->toArray();
            $additional_info = [
                "type" => "Approval",
            ];

            if (count($device_tokens) != 0) {
                $result =  sendPushNotification($body ,$device_tokens ,$additional_info);
            }

            //Send Email Notification
            SendEmailJob::dispatchAfterResponse(new SendEmailJob([
                'to' => $this->user->email,
                'title' => 'Alert | JobReels',
                'body' => "{$this->user->first_name} {$this->user->last_name}, Your account is approved now.",
                'subject' => 'Your account is approved now'
            ]));

            //Add Notification to DB
            Notifications::create([
                'title'=>"JobReels",
                'notification'=>"Your account is approved now",
                'user_id'=>$this->user->id,
                'type'=>'Approval',
            ]);
        }
    }
    public function mount()
    {
        // Get the 'userid' from the route parameters
        $userid = Route::current()->parameter('userId');      
        // Fetch the user data based on the 'userid'
        $this->user = User::find($this->user_id);
        $this->userposts = Post::where('user_id',$this->user_id)->where('is_approved_by_admin',1)->get();
        $this->myreports = FlagedContent::where('user_id', $this->user_id)->where('resolved', 0)->get();
        $this->otherreports = FlagedUser::where('reported_user_id', $this->user_id)->where('resolved', 0)->get();
    }

    public function render()
    {
        return view('livewire.freelancers.freelancers-profile-component');
    }
    public function setVerificationLevel($level, User $user)
    {
        $user->freelancer->update(['verification_level' => $level]);
    }
}