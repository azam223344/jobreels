<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $appends = [
        'video_url', 'total_likes', 'total_saves'
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'portfolio',
        'skills',
        'upwork',
        'fiverr',
        'linkedin',
        'instagram',
        'facebook',
        'youtube',
        'tiktok',
        'twitter',
        'video',
        'thumbnail',
        'status',
        'status_description',
        'active',
        'is_featured',
        'is_approved_by_admin',
    ];

    public function getVideoUrlAttribute()
    {
        //return url('/uploads/' . $this->video);

        return $this->video;
    }

    public function getTotalLikesAttribute()
    {
        return PostLike::where('post_id', $this->id)->count();
    }

    public function getTotalSavesAttribute()
    {
        return PostSave::where('post_id', $this->id)->count();
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function saves()
    {
        return $this->hasMany(PostSave::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function UserData()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function followingUser()
    {
        return $this->hasManyThrough(Followings::class, User::class, 'id', 'follower_id', 'user_id', 'id');
    }
}
