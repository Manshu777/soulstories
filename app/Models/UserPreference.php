<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id',
        'notifications',
        'preferred_genres',
        'language',
        'mature_content',
    ];

    protected $casts = [
        'notifications' => 'array',
        'preferred_genres' => 'array',
        'mature_content' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
