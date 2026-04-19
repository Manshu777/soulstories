<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoryComment extends Model
{
    protected $table = 'story_comments';

    protected $fillable = [
        'user_id',
        'story_chapter_id',
        'parent_id',
        'body',
        'line_number',
        'start_index',
        'end_index',
        'selected_text',
        'reaction',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function storyChapter(): BelongsTo
    {
        return $this->belongsTo(StoryChapter::class, 'story_chapter_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(StoryComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(StoryComment::class, 'parent_id')->where('status', 'visible');
    }
}
