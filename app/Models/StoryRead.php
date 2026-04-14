<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryRead extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'story_id', 'story_chapter_id'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function storyChapter(): BelongsTo
    {
        return $this->belongsTo(StoryChapter::class, 'story_chapter_id');
    }
}
