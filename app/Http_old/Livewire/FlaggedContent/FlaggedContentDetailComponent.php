<?php

namespace App\Http\Livewire\FlaggedContent;

use App\Models\Comment;
use App\Models\FlagedContent;
use App\Models\Post;
use App\Models\User;
use Livewire\Component;

class FlaggedContentDetailComponent extends Component
{
    public $post_id;
    public $post;
    public $user;
    public $comments;
    public $reports;
    public $actions;
    public $userStatus;
    public $postStatus;
    public $actionDescription;
    public $actionTaken;

    protected $queryString = ['post_id'];

    protected function rules(){
        return [
            'user.active' => 'required',
            'post.active' => 'required',
        ];
    }

    public function save()
    {
        FlagedContent::where('post_id', $this->post->id)->where('resolved', 0)->update([
            'resolved' => 1,
            'action_taken' => $this->actionTaken,
            'action_description' => $this->actionDescription
        ]);

        $this->user->active = $this->userStatus;
        $this->user->update();

        $this->post->active = $this->postStatus;
        $this->post->update();

        return redirect('/posts/flagged');
    }

    public function mount()
    {
        $this->post = Post::findOrFail($this->post_id);
        $this->user = User::find($this->post->user_id);
        $this->userStatus = $this->user->active;
        $this->postStatus = $this->post->active;
        $this->comments = Comment::where('post_id', $this->post->id)->get();
        $this->reports = FlagedContent::where('post_id', $this->post->id)->where('resolved', 0)->get();
        $this->actions = FlagedContent::actions();
    }

    public function render()
    {
        return view('livewire.flagged-content.flagged-content-detail-component');
    }
}
