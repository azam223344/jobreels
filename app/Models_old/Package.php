<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'name',
        'tagline',
        'paddle_id_monthly',
        'amount_monthly',
        'discounted_amount_monthly',
        'paddle_id_annually',
        'amount_annually',
        'discounted_amount_annually',
        'details',
        'highlighted_details',
        'active',
    ];
}
