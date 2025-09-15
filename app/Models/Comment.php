<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'comment','comment_id','is_reply'];

    //  function getComments($page = 0,$post_id){
    //     $limit = 10;

    //     $comments = $this::where('post_id', $post_id)->where('is_reply',0)->get();

    // }
    
    public function user()    
    {
        return $this->belongsto(User::class,'user_id','id');
    }
    
    public function post()    
    {
        return $this->belongsto(Post::class,'post_id','id');
    }
    
    public function replies()
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }

    public function likes()
    {
        return $this->hasMany(Likes::class);
    }
}
