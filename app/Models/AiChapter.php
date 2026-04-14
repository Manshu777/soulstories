<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiChapter extends Model
{
    protected $table = 'ai_chapters';

    protected $fillable = [
        'title',
        'content',
        'mood',
        'user_id',
        'continue_reading_after',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
