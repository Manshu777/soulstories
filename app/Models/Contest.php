<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'slug',
        'description',
        'cover_image',
        'status',
        'is_paid',
    ];

    public function entries()
    {
        return $this->hasMany(ContestEntry::class);
    }
}
