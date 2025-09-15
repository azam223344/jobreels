<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Freelancer extends Model
{
    protected $fillable = [
        'user_id',
        'photo',
        'photo_of_govt_id',
        'photo_of_govt_id_back',
        'photo_with_govt_id',
        'bills',
        'portfolio_website',
        'description',
        'salary_requirements',
        'full_time',
        'hourly_rate',
        'skills_experience',
        'skills_assessment',
        'upwork',
        'fiverr',
        'linkedin',
        'instagram',
        'facebook',
        'youtube',
        'tiktok',
        'twitter',
        'verification_level',
        'date_of_birth',
        'gender',
        'years_experience',
        'verification_score',
        'job_title',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
