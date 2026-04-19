<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoryChapter extends Model
{
    protected $table = 'story_chapters';

    protected $fillable = [
        'story_id',
        'chapter_title',
        'chapter_number',
        'content',
        'word_count',
        'read_time',
        'reading_time',
        'audio_file',
        'youtube_url',
        'spotify_playlist',
        'status',
        'sort_order',
        'continue_reading_after',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(StoryComment::class, 'story_chapter_id')->where('status', 'visible')->whereNull('parent_id');
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(StoryComment::class, 'story_chapter_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(StoryLike::class, 'story_chapter_id');
    }

    public function storyReads(): HasMany
    {
        return $this->hasMany(StoryRead::class, 'story_chapter_id');
    }

    public function previousChapter(): ?StoryChapter
    {
        return $this->story->publishedChapters()
            ->where('sort_order', '<', $this->sort_order)
            ->orderByDesc('sort_order')
            ->first();
    }

    public function nextChapter(): ?StoryChapter
    {
        return $this->story->publishedChapters()
            ->where('sort_order', '>', $this->sort_order)
            ->orderBy('sort_order')
            ->first();
    }
}
