<?php

namespace App\Http\Livewire\FlaggedContent;

use App\Models\Post;
use Livewire\Component;

class FlaggedContentIndexComponent extends Component
{
    public $posts;

    public function mount()
    {
        $this->posts = Post::whereRaw(\DB::raw("((select count(id) from flaged_contents where flaged_contents.post_id = posts.id ) != 0)"))->select('posts.*', \DB::raw('(select count(id) from flaged_contents where flaged_contents.post_id = posts.id and resolved = 0) as flag_count'))->orderby('created_at')->get();
    }

    public function render()
    {
        return view('livewire.flagged-content.flagged-content-index-component');
    }
}
