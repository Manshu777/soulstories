<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContestEntry extends Model
{
    protected $fillable = [
        'contest_id',
        'user_id',
        'submission',
    ];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
