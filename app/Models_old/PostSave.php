<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostSave extends Model
{
    use HasFactory;
    
    public function user()    
    {
        return $this->belongsto(User::class,'user_id','id');
    }
    
    public function post()    
    {
        return $this->belongsto(Post::class,'post_id','id');
    }
}
