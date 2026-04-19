<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionPackage extends Model
{
    protected $fillable = [
        'type',
        'title',
        'price',
        'duration_days',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];
}
