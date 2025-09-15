<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Followings extends Model
{
    use HasFactory;
    protected $table = 'followings';

    protected $fillable = ['user_id', 'follower_id'];
  public function User() // person who is following user or dukan
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
