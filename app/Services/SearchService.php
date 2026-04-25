<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Story;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SearchService
{
    public function searchApi(string $q, array $filters = [], bool $suggest = false): array
    {
        $query = trim($q);
        $limit = $suggest ? 5 : 20;

        if ($query === '') {
            return [
                'stories' => collect(),
                'authors' => collect(),
                'blogs' => collect(),
                'tags' => collect(),
            ];
        }

        $cacheKey = 'search:api:' . md5(json_encode([$query, $filters, $suggest]));

        return Cache::remember($cacheKey, now()->addSeconds(90), function () use ($query, $filters, $limit) {
            $storiesQuery = Story::query()
                ->where('approval_status', 'approved')
                ->whereIn('visibility', ['public', 'premium'])
                ->with('user:id,name,username,avatar')
                ->withCount(['storyReads', 'likes as likes_count', 'publishedChapters as parts_count']);

            $blogsQuery = Blog::query()
                ->where('status', 'published')
                ->with('user:id,name,username,avatar');

            $authorsQuery = User::query();

            $this->applyTextQueryToStories($storiesQuery, $query);
            $this->applyTextQueryToBlogs($blogsQuery, $query);
            $this->applyTextQueryToAuthors($authorsQuery, $query);

            $this->applyStoryFilters($storiesQuery, $filters);
            $this->applyBlogFilters($blogsQuery, $filters);

            $stories = $this->sortStories($storiesQuery, $filters)->limit($limit)->get();
            $blogs = $blogsQuery->latest()->limit($limit)->get();
            $authors = $authorsQuery->limit($limit)->get();

            $tags = Story::query()
                ->where('approval_status', 'approved')
                ->whereIn('visibility', ['public', 'premium'])
                ->where('genre', 'like', '%' . $query . '%')
                ->select('genre')
                ->distinct()
                ->limit(5)
                ->pluck('genre')
                ->filter();

            return compact('stories', 'authors', 'blogs', 'tags');
        });
    }

    public function searchWeb(string $q, array $filters = []): array
    {
        $query = trim($q);
        $contentType = $filters['content_type'] ?? 'stories';
        $perPage = 12;

        $storiesQuery = Story::query()
            ->where('approval_status', 'approved')
            ->whereIn('visibility', ['public', 'premium'])
            ->with('user:id,name,username,avatar')
            ->withCount(['storyReads', 'likes as likes_count', 'publishedChapters as parts_count']);

        $blogsQuery = Blog::query()
            ->where('status', 'published')
            ->with('user:id,name,username,avatar');

        $authorsQuery = User::query();

        if ($query !== '') {
            $this->applyTextQueryToStories($storiesQuery, $query);
            $this->applyTextQueryToBlogs($blogsQuery, $query);
            $this->applyTextQueryToAuthors($authorsQuery, $query);
        }

        $this->applyStoryFilters($storiesQuery, $filters);
        $this->applyBlogFilters($blogsQuery, $filters);

        $counts = [
            'stories' => (clone $storiesQuery)->count(),
            'blogs' => (clone $blogsQuery)->count(),
            'authors' => (clone $authorsQuery)->count(),
        ];

        $results = match ($contentType) {
            'authors' => $authorsQuery->paginate($perPage)->withQueryString(),
            'blogs' => $blogsQuery->latest()->paginate($perPage)->withQueryString(),
            default => $this->sortStories($storiesQuery, $filters)->paginate($perPage)->withQueryString(),
        };

        return [
            'content_type' => $contentType,
            'results' => $results,
            'counts' => $counts,
        ];
    }

    public function trackKeyword(string $q, ?int $userId = null): void
    {
        $query = trim(mb_strtolower($q));
        if ($query === '') {
            return;
        }

        try {
            if (! Schema::hasTable('search_keywords')) {
                return;
            }

            DB::table('search_keywords')->upsert([
                'keyword' => $query,
                'count' => 1,
                'last_searched_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ], ['keyword'], [
                'count' => DB::raw('count + 1'),
                'last_searched_at' => now(),
                'updated_at' => now(),
            ]);

            if ($userId && Schema::hasTable('search_histories')) {
                DB::table('search_histories')->insert([
                    'user_id' => $userId,
                    'query' => $query,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (Throwable) {
            // Search tracking should never break user-facing search.
        }
    }

    public function trendingKeywords(int $limit = 8): Collection
    {
        return Cache::remember('search:trending:' . $limit, now()->addMinutes(5), function () use ($limit) {
            try {
                if (! Schema::hasTable('search_keywords')) {
                    return collect();
                }

                return DB::table('search_keywords')
                    ->orderByDesc('count')
                    ->orderByDesc('last_searched_at')
                    ->limit($limit)
                    ->pluck('keyword');
            } catch (Throwable) {
                return collect();
            }
        });
    }

    public function recentHistory(?int $userId, int $limit = 8): Collection
    {
        if (! $userId) {
            return collect();
        }

        try {
            if (! Schema::hasTable('search_histories')) {
                return collect();
            }

            return DB::table('search_histories')
                ->where('user_id', $userId)
                ->latest('id')
                ->limit($limit)
                ->pluck('query')
                ->unique()
                ->values();
        } catch (Throwable) {
            return collect();
        }
    }

    private function applyTextQueryToStories(Builder $query, string $q): void
    {
        $query->where(function (Builder $builder) use ($q) {
            $builder->where('title', 'like', '%' . $q . '%')
                ->orWhere('description', 'like', '%' . $q . '%')
                ->orWhere('genre', 'like', '%' . $q . '%')
                ->orWhere('tags', 'like', '%' . $q . '%');
        });
    }

    private function applyTextQueryToBlogs(Builder $query, string $q): void
    {
        $query->where(function (Builder $builder) use ($q) {
            $builder->where('title', 'like', '%' . $q . '%')
                ->orWhere('body', 'like', '%' . $q . '%');
        });
    }

    private function applyTextQueryToAuthors(Builder $query, string $q): void
    {
        $query->where(function (Builder $builder) use ($q) {
            $builder->where('name', 'like', '%' . $q . '%')
                ->orWhere('username', 'like', '%' . $q . '%');
        });
    }

    private function applyStoryFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['genre'])) {
            $query->where('genre', $filters['genre']);
        }

        if (! empty($filters['date'])) {
            $this->applyDateFilter($query, $filters['date']);
        }

        if (! empty($filters['length'])) {
            [$min, $max] = $this->lengthToRange($filters['length']);
            $query->whereBetween('read_time', [$min, $max]);
        }

        if (! empty($filters['tag'])) {
            $query->where('tags', 'like', '%"' . $filters['tag'] . '"%');
        }
    }

    private function applyBlogFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['date'])) {
            $this->applyDateFilter($query, $filters['date']);
        }
    }

    private function applyDateFilter(Builder $query, string $dateFilter): void
    {
        $now = Carbon::now();

        match ($dateFilter) {
            'today' => $query->whereDate('created_at', $now->toDateString()),
            'week' => $query->where('created_at', '>=', $now->copy()->subDays(7)),
            'month' => $query->where('created_at', '>=', $now->copy()->subDays(30)),
            default => null,
        };
    }

    private function sortStories(Builder $query, array $filters): Builder
    {
        $sort = $filters['sort'] ?? 'latest';

        return match ($sort) {
            'popularity' => $query->orderByDesc('story_reads_count')->orderByDesc('likes_count'),
            'trending' => $query
                ->withCount([
                    'storyReads as recent_reads_count' => fn ($q) => $q->where('created_at', '>=', now()->subDays(7)),
                    'likes as recent_likes_count' => fn ($q) => $q->where('created_at', '>=', now()->subDays(7)),
                ])
                ->orderByDesc(DB::raw('(recent_reads_count * 2) + (recent_likes_count * 4)')),
            default => $query->latest(),
        };
    }

    private function lengthToRange(string $length): array
    {
        return match ($length) {
            '1-10' => [1, 10],
            '10-20' => [10, 20],
            '20-40' => [20, 40],
            '40+' => [40, 10000],
            default => [0, 10000],
        };
    }
}
