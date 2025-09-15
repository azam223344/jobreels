<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $appends = ['shelter', 'adopter', 'post'];

    public function getAdopterAttribute()
    {
        return User::find($this->adopter_user_id);
    }

    public function getShelterAttribute()
    {
        return User::find($this->shelter_user_id);
    }

    public function getPostAttribute()
    {
        return Post::find($this->post_id);
    }

    public function adopterRel()
    {
        return $this->belongsTo(User::class, 'adopter_user_id', 'id');
    }

    public function shelterRel()
    {
        return $this->belongsTo(User::class, 'shelter_user_id', 'id');
    }

    public function postRel()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
