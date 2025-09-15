<?php

namespace App\Http\Livewire\Freelancers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\DeviceToken;
use App\Models\Notifications;
use Livewire\Component;

use App\Jobs\SendEmailJob;

class FreelancerDetailsComponent extends Component
{
    public $user_id;
    public $adoptionIn;
    public $adoptionOut;
    public $user;

    public $queryString = ['user_id'];

    protected function rules(){
        return [
            'user.active_publisher' => 'required|boolean',
            'user.active' => 'required|boolean'
        ];
    }

    public function update()
    {
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
        $this->user = User::findOrFail($this->user_id);
        $this->adoptionIn = Transaction::where('shelter_user_id', $this->user->id)->get();
        $this->adoptionOut = Transaction::where('adopter_user_id', $this->user->id)->get();
    }

    public function render()
    {
        return view('livewire.freelancers.freelancer-details-component');
    }

    public function setVerificationLevel($level, User $user)
    {
        $user->freelancer->update(['verification_level' => $level]);
    }
}
