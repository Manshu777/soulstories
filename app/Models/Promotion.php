<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'user_id',
        'content_id',
        'package_id',
        'status',
        'starts_at',
        'ends_at',
    ];

    protected $dates = ['starts_at', 'ends_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(PromotionPackage::class, 'package_id');
    }
}
