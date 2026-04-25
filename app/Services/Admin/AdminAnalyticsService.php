<?php

namespace App\Services\Admin;

use App\Models\Story;
use App\Models\StoryLike;
use App\Models\StoryRead;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsService
{
    public function dashboardMetrics(): array
    {
        return Cache::remember('admin:dashboard:metrics', now()->addMinutes(10), function () {
            $dailyRegistrations = User::query()
                ->selectRaw('DATE(created_at) as metric_day, COUNT(*) as total_count')
                ->where('created_at', '>=', now()->subDays(14))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'))
                ->get();

            $dailyEngagement = StoryRead::query()
                ->selectRaw('DATE(read_at) as metric_day, COUNT(*) as read_count')
                ->where('read_at', '>=', now()->subDays(14))
                ->groupBy(DB::raw('DATE(read_at)'))
                ->orderBy(DB::raw('DATE(read_at)'))
                ->get()
                ->map(function ($row) {
                    $likes = StoryLike::whereDate('created_at', $row->metric_day)->count();
                    return ['day' => $row->metric_day, 'reads' => (int) $row->read_count, 'likes' => $likes];
                });

            return [
                'total_users' => User::count(),
                'active_users' => User::where('status', 'active')->count(),
                'total_stories' => Story::count(),
                'total_reads' => StoryRead::count(),
                'revenue' => (float) (
                    DB::table('promotion_payments')->where('status', 'paid')->sum('amount')
                    + DB::table('service_payments')->where('status', 'paid')->sum('amount')
                    + DB::table('earn_payments')->where('status', 'paid')->sum('amount')
                ),
                'top_authors' => User::withCount('stories')
                    ->orderByDesc('stories_count')
                    ->limit(5)
                    ->get(['id', 'name', 'username']),
                'top_stories' => Story::withCount(['storyReads', 'likes as likes_count'])
                    ->orderByDesc('story_reads_count')
                    ->limit(5)
                    ->get(['id', 'title', 'slug']),
                'daily_registrations' => $dailyRegistrations,
                'daily_engagement' => $dailyEngagement,
            ];
        });
    }
}
