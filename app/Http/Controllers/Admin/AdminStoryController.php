<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\StoryComment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminStoryController extends Controller
{
    public function dashboard()
    {
        $pendingStories = Story::where('approval_status', 'pending')->count();
        $approvedStories = Story::where('approval_status', 'approved')->count();
        $rejectedStories = Story::where('approval_status', 'rejected')->count();
        $totalStories = Story::count();
        $totalComments = StoryComment::count();
        $totalUsers = User::where('status', 'active')->count();
        $blockedUsers = User::where('status', 'blocked')->count();

        return view('admin.dashboard', compact(
            'pendingStories', 'approvedStories', 'rejectedStories', 'totalStories',
            'totalComments', 'totalUsers', 'blockedUsers'
        ));
    }

    public function index(Request $request)
    {
        $query = Story::with('user:id,name,username,email')
            ->withCount('storyReads');

        if ($request->filled('approval')) {
            $query->where('approval_status', $request->approval);
        }

        $stories = $query->latest()->paginate(20);

        return view('admin.stories.index', compact('stories'));
    }

    public function approve(Story $story)
    {
        $story->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'rejected_at' => null,
            'admin_notes' => null,
        ]);
        return back()->with('success', 'Story approved.');
    }

    public function reject(Request $request, Story $story)
    {
        $story->update([
            'approval_status' => 'rejected',
            'rejected_at' => now(),
            'approved_at' => null,
            'admin_notes' => $request->input('admin_notes'),
        ]);
        return back()->with('success', 'Story rejected.');
    }

    public function destroy(Story $story)
    {
        $story->delete();
        return back()->with('success', 'Story deleted.');
    }

    public function blockUser(User $user)
    {
        $user->update(['status' => 'blocked']);
        return back()->with('success', 'User blocked.');
    }

    public function unblockUser(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', 'User unblocked.');
    }

    public function moderateComments(Request $request)
    {
        $query = StoryComment::with(['user:id,name,username', 'storyChapter.story:id,title,slug']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $comments = $query->latest()->paginate(30);

        return view('admin.comments.index', compact('comments'));
    }

    public function hideComment(StoryComment $comment)
    {
        $comment->update(['status' => 'hidden']);
        return back()->with('success', 'Comment hidden.');
    }

    public function removeComment(StoryComment $comment)
    {
        $comment->update(['status' => 'removed']);
        return back()->with('success', 'Comment removed.');
    }

    public function showComment(StoryComment $comment)
    {
        $comment->update(['status' => 'visible']);
        return back()->with('success', 'Comment visible again.');
    }
}
