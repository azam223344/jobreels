<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Paddle\Billable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;
    
    protected $appends = [
        'NoOfFollowing', 'NoOfFollowers', 'totalLike','profile_picture_url',
        //'state',
        'DeviceToken'
    ];
    protected $fillable = [
        'freelancer_id',
        'name',
        'email',
        'password',
        'username',
        'phone',
        'address',
        'profile_picture',

        'business_name',
        'first_name',
        'last_name',
        'state',
        'city',
        'address',
        'zip_code',
        'industry',
        'employee_id',
        'description',
        'uuid',
        'is_email_private',
        'is_phone_private',
    ];

   // protected $appends = ['profile_picture_url'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getProfilePictureUrlAttribute()
    {
        return url('/uploads/' . $this->profile_picture);
    }

    public function freelancer()
    {
        return $this->hasOne(Freelancer::class);
    }

    public function likesCount()
    {
        return PostLike::where('user_id', $this->id)->count();
    }

    public function savedCount()
    {
        return PostSave::where('user_id', $this->id)->count();
    }

    public function postsCount()
    {
        return Post::where('user_id', $this->id)->count();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likedVideos()
    {
        $postIds = PostLike::join('posts', 'posts.id', 'post_likes.post_id')->select('posts.*')->where('post_likes.user_id', $this->id)->pluck('id');
        return Post::whereIn('id', $postIds)->get();
    }

    public function savedVideos()
    {
        $postIds = PostSave::join('posts', 'posts.id', 'post_saves.post_id')->select('posts.*')->where('post_saves.user_id', $this->id)->pluck('id');
        return Post::whereIn('id', $postIds)->get();
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
     public function followingUser() //
     {
        return $this->hasOne(Followings::class,'follower_id','id');
    }
      public function followerUser() //
      {
        return $this->hasOne(Followings::class,'user_id','id');
    }
    public function postLike()
    {
        return $this->hasMany(PostLike::class);
    }
    public function getNoOfFollowingAttribute()
    {
        return Followings::where('follower_id', $this->id)->count();
    }
    public function getNoOfFollowersAttribute()
    {
        return Followings::where('user_id', $this->id)->count();
    }
    public function followings()
    {
        return $this->hasMany(Followings::class, 'follower_id');
    }
    public function followers()
    {
        return $this->hasMany(Followings::class, 'user_id');
    }
    public function gettotalLikeAttribute()
    {
        return Followings::where('user_id', $this->id)->count();
    }
    public function getDeviceTokenAttribute()
    {
        return DeviceToken::where('user_id', $this->id)->orderby('id','desc')->first();
    }    
    public function DeviceToken()
    {
        return $this->hasMany(DeviceToken::class,'user_id');
    }
}
