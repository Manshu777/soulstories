<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Models\StoryChapter;
use App\Models\StoryComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $valid = $request->validate([
            'story_chapter_id' => 'required|exists:story_chapters,id',
            'body' => 'nullable|string|max:2000',
            'parent_id' => 'nullable|exists:story_comments,id',
            'line_number' => 'nullable|integer|min:1',
            'start_index' => 'nullable|integer|min:0',
            'end_index' => 'nullable|integer|min:0',
            'selected_text' => 'nullable|string|max:2000',
            'reaction' => 'nullable|in:heart,fire,sad',
        ]);

        if (empty($valid['body']) && empty($valid['reaction'])) {
            return response()->json(['ok' => false, 'message' => 'Comment or reaction required.'], 422);
        }

        $chapter = StoryChapter::with('story')->findOrFail($valid['story_chapter_id']);
        $story = $chapter->story;
        if (! $story->isPublic() && auth()->id() !== $story->user_id) {
            abort(403);
        }

        $comment = StoryComment::create([
            'user_id' => auth()->id(),
            'story_chapter_id' => $valid['story_chapter_id'],
            'parent_id' => $valid['parent_id'] ?? null,
            'body' => $valid['body'] ?? null,
            'line_number' => $valid['line_number'] ?? null,
            'start_index' => $valid['start_index'] ?? null,
            'end_index' => $valid['end_index'] ?? null,
            'selected_text' => $valid['selected_text'] ?? null,
            'reaction' => $valid['reaction'] ?? null,
            'status' => 'visible',
        ]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'comment_id' => $comment->id]);
        }

        return back()->with('success', 'Comment added.');
    }
}
