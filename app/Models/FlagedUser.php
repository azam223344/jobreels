<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlagedUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_taken',
        'action_description',
        'resolved'
    ];

    protected $appends = ['user', 'reported_user', 'flag'];

    public function getUserAttribute()
    {
        return User::find($this->user_id);
    }

    public function getReportedUserAttribute()
    {
        return User::find($this->reported_user_id);
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
            'No Action'
        ];
    }
}
