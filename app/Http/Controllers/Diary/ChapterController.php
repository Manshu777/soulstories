<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\StoryChapter;
use App\Services\ReadTimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChapterController extends Controller
{
    public function __construct(
        protected ReadTimeService $readTime
    ) {}

    public function show(Story $story, StoryChapter $chapter)
    {
        if ($chapter->story_id !== $story->id) {
            abort(404);
        }
        if ($chapter->status !== 'published') {
            if (! auth()->check() || auth()->id() !== $story->user_id) {
                abort(404);
            }
        }

        $story->load('user:id,name,username,avatar');
        $chapter->load(['story', 'comments.user', 'comments.replies.user']);

        $previous = $chapter->previousChapter();
        $next = $chapter->nextChapter();
        $hasLiked = auth()->check() && $chapter->likes()->where('user_id', auth()->id())->exists();

        if (auth()->check()) {
            $story->storyReads()->firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'story_chapter_id' => $chapter->id,
                ],
                ['story_id' => $story->id]
            );
        }

        return view('diary.chapters.show', compact('story', 'chapter', 'previous', 'next', 'hasLiked'));
    }

    public function create(Story $story)
    {
        $this->authorize('update', $story);
        $nextNumber = (int) $story->chapters()->max('chapter_number') + 1;
        return view('diary.chapters.create', compact('story', 'nextNumber'));
    }

    public function createById(int $id)
    {
        $story = Story::findOrFail($id);
        return $this->create($story);
    }

    public function store(Request $request, Story $story)
    {
        $this->authorize('update', $story);

        $valid = $request->validate([
            'chapter_title' => 'required|string|max:255',
            'chapter_number' => 'required|integer|min:1',
            'content' => 'required|string|max:200000',
            'audio_file' => 'nullable|string|max:500',
            'youtube_url' => 'nullable|url|max:500',
            'spotify_playlist' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'continue_reading_after' => 'nullable|integer|min:50',
        ]);

        $wordCount = str_word_count(strip_tags($valid['content']));
        $readingTime = max(1, (int) ceil($wordCount / 180));
        $sortOrder = (int) $story->chapters()->max('sort_order') + 1;

        $story->chapters()->create([
            'chapter_title' => $valid['chapter_title'],
            'chapter_number' => (int) $valid['chapter_number'],
            'content' => $valid['content'],
            'word_count' => $wordCount,
            'read_time' => $readingTime,
            'reading_time' => $readingTime,
            'audio_file' => $valid['audio_file'] ?? null,
            'youtube_url' => $valid['youtube_url'] ?? null,
            'spotify_playlist' => $valid['spotify_playlist'] ?? null,
            'status' => $valid['status'],
            'sort_order' => $sortOrder,
            'continue_reading_after' => $valid['continue_reading_after'] ?? null,
        ]);

        $story->recalculateReadTime();

        return redirect()->route('diary.stories.edit', $story)->with('success', 'Chapter saved.');
    }

    public function autosave(Request $request, Story $story)
    {
        $this->authorize('update', $story);

        $valid = $request->validate([
            'chapter_id' => 'nullable|integer|exists:story_chapters,id',
            'chapter_title' => 'required|string|max:255',
            'chapter_number' => 'required|integer|min:1',
            'content' => 'required|string|max:200000',
            'audio_file' => 'nullable|string|max:500',
            'youtube_url' => 'nullable|url|max:500',
            'spotify_playlist' => 'nullable|string|max:500',
            'status' => 'nullable|in:draft,published',
            'continue_reading_after' => 'nullable|integer|min:50',
        ]);

        $wordCount = str_word_count(strip_tags($valid['content']));
        $readingTime = max(1, (int) ceil($wordCount / 180));

        $chapter = null;
        if (! empty($valid['chapter_id'])) {
            $chapter = $story->chapters()->where('id', $valid['chapter_id'])->first();
        }

        if (! $chapter) {
            $chapter = $story->chapters()->create([
                'chapter_title' => $valid['chapter_title'],
                'chapter_number' => (int) $valid['chapter_number'],
                'content' => $valid['content'],
                'word_count' => $wordCount,
                'read_time' => $readingTime,
                'reading_time' => $readingTime,
                'audio_file' => $valid['audio_file'] ?? null,
                'youtube_url' => $valid['youtube_url'] ?? null,
                'spotify_playlist' => $valid['spotify_playlist'] ?? null,
                'status' => $valid['status'] ?? 'draft',
                'sort_order' => (int) $story->chapters()->max('sort_order') + 1,
                'continue_reading_after' => $valid['continue_reading_after'] ?? null,
            ]);
        } else {
            $chapter->update([
                'chapter_title' => $valid['chapter_title'],
                'chapter_number' => (int) $valid['chapter_number'],
                'content' => $valid['content'],
                'word_count' => $wordCount,
                'read_time' => $readingTime,
                'reading_time' => $readingTime,
                'audio_file' => $valid['audio_file'] ?? null,
                'youtube_url' => $valid['youtube_url'] ?? null,
                'spotify_playlist' => $valid['spotify_playlist'] ?? null,
                'status' => $valid['status'] ?? 'draft',
                'continue_reading_after' => $valid['continue_reading_after'] ?? null,
            ]);
        }

        $story->recalculateReadTime();

        return response()->json([
            'ok' => true,
            'chapter_id' => $chapter->id,
            'word_count' => $wordCount,
            'reading_time' => $readingTime,
            'saved_at' => now()->format('H:i:s'),
        ]);
    }

    public function uploadEditorImage(Request $request, Story $story)
    {
        $this->authorize('update', $story);
        $request->validate(['image' => 'required|image|max:4096']);
        $path = $request->file('image')->store('stories/editor-images', 'public');
        return response()->json(['url' => Storage::disk('public')->url($path)]);
    }

    public function uploadEditorAudio(Request $request, Story $story)
    {
        $this->authorize('update', $story);
        $request->validate(['audio' => 'required|file|mimes:mp3,wav,m4a,ogg|max:12288']);
        $path = $request->file('audio')->store('stories/editor-audio', 'public');
        return response()->json(['url' => Storage::disk('public')->url($path)]);
    }

    public function edit(Story $story, StoryChapter $chapter)
    {
        $this->authorize('update', $story);
        if ($chapter->story_id !== $story->id) {
            abort(404);
        }
        $chapter->load([
            'comments' => fn ($q) => $q->latest()->limit(30),
            'comments.user',
        ]);

        return view('diary.chapters.edit', compact('story', 'chapter'));
    }

    public function update(Request $request, Story $story, StoryChapter $chapter)
    {
        $this->authorize('update', $story);
        if ($chapter->story_id !== $story->id) {
            abort(404);
        }

        $valid = $request->validate([
            'chapter_title' => 'required|string|max:255',
            'chapter_number' => 'required|integer|min:1',
            'content' => 'required|string|max:100000',
            'audio_file' => 'nullable|string|max:500',
            'spotify_playlist' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);

        $wordCount = str_word_count(strip_tags($valid['content']));
        $readingTime = max(1, (int) ceil($wordCount / 180));

        $chapter->update([
            'chapter_title' => $valid['chapter_title'],
            'chapter_number' => (int) $valid['chapter_number'],
            'content' => $valid['content'],
            'word_count' => $wordCount,
            'read_time' => $readingTime,
            'reading_time' => $readingTime,
            'audio_file' => $valid['audio_file'] ?? null,
            'spotify_playlist' => $valid['spotify_playlist'] ?? null,
            'status' => $valid['status'],
        ]);

        $story->recalculateReadTime();

        return back()->with('success', 'Chapter updated.');
    }

    public function destroy(Story $story, StoryChapter $chapter)
    {
        $this->authorize('update', $story);
        if ($chapter->story_id !== $story->id) {
            abort(404);
        }
        $chapter->delete();
        $story->recalculateReadTime();
        return back()->with('success', 'Chapter deleted.');
    }

    public function reorder(Request $request, Story $story)
    {
        $this->authorize('update', $story);

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:story_chapters,id',
        ]);

        foreach ($request->order as $index => $id) {
            $story->chapters()->where('id', $id)->update(['sort_order' => $index]);
        }

        return back()->with('success', 'Chapters reordered.');
    }
}
