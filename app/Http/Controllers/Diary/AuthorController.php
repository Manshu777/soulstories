<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function show(string $username)
    {
        $author = User::where('username', $username)
            ->where('status', 'active')
            ->firstOrFail();

        $stories = $author->stories()
            ->where('approval_status', 'approved')
            ->where('visibility', 'public')
            ->withCount('storyReads')
            ->latest()
            ->get();

        $followersCount = $author->followers()->count();
        $followingCount = $author->following()->count();
        $isFollowing = auth()->check() && $author->followers()->where('follower_id', auth()->id())->exists();

        return view('diary.authors.show', compact('author', 'stories', 'followersCount', 'followingCount', 'isFollowing'));
    }

    public function follow(Request $request, User $author)
    {
        if ($author->id === auth()->id()) {
            return back()->with('error', 'You cannot follow yourself.');
        }
        auth()->user()->following()->syncWithoutDetaching([$author->id]);
        return back()->with('success', 'Following ' . $author->name);
    }

    public function unfollow(Request $request, User $author)
    {
        auth()->user()->following()->detach($author->id);
        return back()->with('success', 'Unfollowed.');
    }
}
