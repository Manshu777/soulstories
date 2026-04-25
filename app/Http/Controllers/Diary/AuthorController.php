<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function show(string $username)
    {
        $author = User::query()
            ->where('username', $username)
            ->where('status', 'active')
            ->firstOrFail();

        $recentStories = $author->stories()
            ->where('approval_status', 'approved')
            ->whereIn('visibility', ['public', 'premium'])
            ->withCount(['storyReads', 'likes as likes_count', 'publishedChapters as parts_count'])
            ->latest()
            ->limit(10)
            ->get();

        $followersCount = $author->followers()->count();
        $followingCount = $author->following()->count();

        $isFollowing = auth()->check()
            && auth()->id() !== $author->id
            && auth()->user()->following()->where('following_id', $author->id)->exists();

        return view('diary.authors.show', [
            'author' => $author,
            'followers_count' => $followersCount,
            'following_count' => $followingCount,
            'isFollowing' => $isFollowing,
            'recent_stories' => $recentStories,
        ]);
    }

    public function follow(Request $request, User $author)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if ($author->id === auth()->id()) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        auth()->user()->following()->syncWithoutDetaching([$author->id]);

        return back()->with('success', 'Following ' . $author->name);
    }

    public function unfollow(Request $request, User $author)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        auth()->user()->following()->detach($author->id);

        return back()->with('success', 'Unfollowed ' . $author->name);
    }
}
