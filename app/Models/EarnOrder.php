<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EarnOrder extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function submissions()
    {
        return $this->hasMany(EarnSubmission::class);
    }
}
