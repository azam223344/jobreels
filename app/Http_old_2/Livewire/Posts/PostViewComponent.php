<?php

namespace App\Http\Livewire\Posts;

use App\Models\Comment;
use Livewire\Component;
use App\Models\{Post,User,DeviceToken};
use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;

use App\Models\Notifications;
use Illuminate\Support\Facades\{DB,Validator,Http,Mail};
class PostViewComponent extends Component
{
    public $post_id;
    public $post;
    public $user;
    public $comments;

    protected $queryString = ['post_id'];

    protected function rules(){
        return [
            'post.active' => 'required',
            'post.is_featured' => 'required',
             'post.is_approved_by_admin' => 'required',
        ];
    }
     public function postapproved()
    {
        // FireBAse Notification
        if($this->post->is_approved_by_admin == 1 ){
        $email = User::where('id',$this->post->user_id)->first();
        $body = "Your post is approved by the admin";
        $device_tokens = DeviceToken::where('user_id', $this->post->user_id)->pluck('value')->toArray();
        $additional_info = [
            "type" => "Approved",
            "id"  => $this->post->id,
        ];
          
        if (count($device_tokens) != 0) {
            $result =  sendPushNotification($body ,$device_tokens ,$additional_info);
        }

        # Send Email Notification
        SendEmailJob::dispatchAfterResponse(new SendEmailJob([
            'to' => $email->email,
            'title' => 'Alert | JobReels',
            'body' => "Your Post is Approved By the Admin.",
            'subject' => 'Alert | JobReels'
        ]));

       
        # Add Notification to DB
        Notifications::create([
            'title'=>"JobReels",
            'notification'=>"Your post is approved by the admin",
            'user_id'=>$this->post->user_id,
            'type'=>'Approved',
            'post_id' => $this->post->id,

        ]);
        $this->post->update();
        }
         $this->post->update();
    }

    public function update()
    {
        $this->post->update();
    }

    public function mount()
    {
        $this->post = Post::findOrFail($this->post_id);
        $this->user = User::find($this->post->user_id);
        $this->comments = Comment::where('post_id', $this->post->id)->get();
    }

    public function render()
    {
        return view('livewire.posts.post-view-component');
    }
}
