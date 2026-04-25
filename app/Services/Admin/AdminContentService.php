<?php

namespace App\Services\Admin;

use App\Models\Blog;
use App\Models\Story;
use App\Models\StoryComment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminContentService
{
    public function stories(array $filters): LengthAwarePaginator
    {
        return Story::query()
            ->with(['user:id,name,username', 'category:id,name,slug'])
            ->withCount(['storyReads', 'likes as likes_count'])
            ->when(($filters['q'] ?? null), fn ($q, $term) => $q->where(function ($query) use ($term) {
                $query->where('title', 'like', "%{$term}%")
                    ->orWhere('slug', 'like', "%{$term}%");
            }))
            ->when(($filters['approval'] ?? null), fn ($q, $approval) => $q->where('approval_status', $approval))
            ->when(($filters['category_id'] ?? null), fn ($q, $categoryId) => $q->where('category_id', $categoryId))
            ->when(($filters['tag'] ?? null), fn ($q, $tag) => $q->whereJsonContains('tags', $tag))
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function comments(array $filters): LengthAwarePaginator
    {
        return StoryComment::query()
            ->with(['user:id,name,username', 'storyChapter.story:id,title,slug'])
            ->when(($filters['status'] ?? null), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(30)
            ->withQueryString();
    }

    public function blogs(): LengthAwarePaginator
    {
        return Blog::query()->with('user:id,name,username')->latest()->paginate(20);
    }

    public function storyComments(Story $story, array $filters): LengthAwarePaginator
    {
        return StoryComment::query()
            ->with(['user:id,name,username', 'storyChapter:id,story_id,chapter_title,chapter_number'])
            ->whereHas('storyChapter', fn ($q) => $q->where('story_id', $story->id))
            ->when(($filters['status'] ?? null), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
}
