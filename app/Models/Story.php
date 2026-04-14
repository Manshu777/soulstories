<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Story extends Model
{
    protected $table = 'stories';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'cover_image',
        'description',
        'genre',
        'language',
        'story_type',
        'type',
        'content_guidance',
        'tags',
        'visibility',
        'theme',
        'bg_image',
        'bg_color',
        'status',
        'approval_status',
        'approved_at',
        'rejected_at',
        'admin_notes',
        'read_time',
    ];

    protected $casts = [
        'tags' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(StoryChapter::class, 'story_id')->orderBy('sort_order')->orderBy('chapter_number');
    }

    public function publishedChapters(): HasMany
    {
        return $this->hasMany(StoryChapter::class, 'story_id')
            ->where('status', 'published')
            ->orderBy('sort_order')
            ->orderBy('chapter_number');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(StoryLike::class)->whereNull('story_chapter_id');
    }

    public function libraries(): HasMany
    {
        return $this->hasMany(Library::class);
    }

    public function storyReads(): HasMany
    {
        return $this->hasMany(StoryRead::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(StoryReport::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPublic(): bool
    {
        return in_array($this->visibility, ['public', 'premium'], true) && $this->isApproved();
    }

    public function recalculateReadTime(): void
    {
        $this->update([
            'read_time' => (int) $this->publishedChapters()->sum('reading_time'),
        ]);
    }
}
