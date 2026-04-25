<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Story;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.navbar', function ($view) {
            $navbarCategories = Cache::remember('navbar:categories:top', now()->addMinutes(20), function () {
                return Category::query()
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->limit(10)
                    ->get(['id', 'name', 'slug']);
            });

            $navbarTrendingStories = Cache::remember('navbar:stories:trending', now()->addMinutes(10), function () {
                return Story::query()
                    ->where('approval_status', 'approved')
                    ->whereIn('visibility', ['public', 'premium'])
                    ->withCount([
                        'storyReads as recent_reads_count' => fn ($q) => $q->where('created_at', '>=', now()->subDays(7)),
                        'likes as recent_likes_count' => fn ($q) => $q->where('created_at', '>=', now()->subDays(7)),
                    ])
                    ->orderByRaw('(recent_reads_count * 2) + (recent_likes_count * 4) DESC')
                    ->limit(5)
                    ->get(['id', 'title', 'slug', 'cover_image']);
            });

            $view->with(compact('navbarCategories', 'navbarTrendingStories'));
        });
    }
}
