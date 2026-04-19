<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $myStories = Story::where('user_id', $user->id)
            ->withCount(['storyReads', 'likes'])
            ->latest()
            ->get();

        $drafts = $myStories->where('visibility', 'draft');
        $published = $myStories->where('visibility', 'public');
        $pending = $myStories->where('approval_status', 'pending');

        $analytics = [
            'total_reads' => $myStories->sum(fn ($s) => $s->story_reads_count),
            'total_votes' => $myStories->sum(fn ($s) => $s->likes_count),
            'total_comments' => 0,
        ];

        foreach ($myStories as $s) {
            $analytics['total_comments'] += \App\Models\StoryComment::whereIn('story_chapter_id', $s->chapters()->pluck('id'))->where('status', 'visible')->count();
        }

        $followersCount = $user->followers()->count();

        return view('diary.dashboard', [
            'myStories' => $myStories,
            'drafts' => $drafts,
            'published' => $published,
            'pending' => $pending,
            'analytics' => $analytics,
            'followersCount' => $followersCount,
        ]);
    }
}
