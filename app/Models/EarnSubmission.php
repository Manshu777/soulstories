<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EarnSubmission extends Model
{
    protected $fillable = [
        'earn_order_id',
        'task_id',
        'proof_link',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(EarnOrder::class, 'earn_order_id');
    }

    public function task()
    {
        return $this->belongsTo(EarnTask::class);
    }
}
