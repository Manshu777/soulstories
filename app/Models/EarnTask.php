<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EarnTask extends Model
{
    protected $fillable = [
        'title',
        'description',
        'reward_amount',
        'platform',
    ];
}
