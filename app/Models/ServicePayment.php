<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePayment extends Model
{
    protected $fillable = [
        'service_order_id',
        'payment_id',
        'amount',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(ServiceOrder::class, 'service_order_id');
    }
}
