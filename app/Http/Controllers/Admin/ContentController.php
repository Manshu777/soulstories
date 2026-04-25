<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Story;
use App\Models\StoryComment;
use App\Services\Admin\AdminContentService;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function __construct(private readonly AdminContentService $contentService)
    {
    }

    public function stories(Request $request)
    {
        $filters = $request->only(['q', 'approval', 'category_id', 'tag']);
        $stories = $this->contentService->stories($filters);
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.content.stories', compact('stories', 'categories', 'filters'));
    }

    public function showStory(Request $request, Story $story)
    {
        $story->load(['user:id,name,username,email', 'category:id,name,slug']);
        $comments = $this->contentService->storyComments($story, $request->only(['status']));

        return view('admin.content.show', compact('story', 'comments'));
    }

    public function comments(Request $request)
    {
        $comments = $this->contentService->comments($request->only(['status']));
        return view('admin.content.comments', compact('comments'));
    }

    public function blogs()
    {
        $blogs = $this->contentService->blogs();
        return view('admin.content.blogs', compact('blogs'));
    }

    public function approveStory(Story $story)
    {
        $story->update(['approval_status' => 'approved', 'approved_at' => now(), 'rejected_at' => null]);
        return back()->with('success', 'Story approved.');
    }

    public function rejectStory(Request $request, Story $story)
    {
        $data = $request->validate(['admin_notes' => ['nullable', 'string', 'max:500']]);
        $story->update(['approval_status' => 'rejected', 'rejected_at' => now(), 'approved_at' => null, 'admin_notes' => $data['admin_notes'] ?? null]);
        return back()->with('success', 'Story rejected.');
    }

    public function moderateComment(Request $request, StoryComment $comment)
    {
        $data = $request->validate(['status' => ['required', 'in:visible,hidden,removed']]);
        $comment->update(['status' => $data['status']]);
        return back()->with('success', 'Comment moderation updated.');
    }
}
