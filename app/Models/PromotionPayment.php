<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionPayment extends Model
{
    protected $fillable = [
        'promotion_id',
        'payment_id',
        'amount',
        'status',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
