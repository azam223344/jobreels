<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlagedContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_taken',
        'action_description',
        'resolved'
    ];

    protected $appends = ['user', 'post', 'flag'];

    public function getUserAttribute()
    {
        return User::find($this->user_id);
    }

    public function getPostAttribute()
    {
        return Post::find($this->post_id);
    }

    public function getFlagAttribute()
    {
        return Flag::find($this->flag_id);
    }

    public static function actions()
    {
        return [
            'User Deactivated',
            'User Warned',
            'Post Deactivated',
            'No Action'
        ];
    }
}
