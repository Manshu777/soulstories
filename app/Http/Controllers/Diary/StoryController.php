<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = Story::query()
            ->where('approval_status', 'approved')
            ->whereIn('visibility', ['public', 'premium'])
            ->with('user:id,name,username,avatar')
            ->withCount([
                'storyReads',
                'likes as likes_count',
            ]);

        if ($request->filled('genre')) {
            $baseQuery->where('genre', $request->genre);
        }
        if ($request->filled('language')) {
            $baseQuery->where('language', $request->language);
        }

        $stories = (clone $baseQuery)->latest()->get();

        $topViewed = (clone $baseQuery)
            ->orderByDesc('story_reads_count')
            ->orderByDesc('id')
            ->limit(12)
            ->get();

        $topLiked = (clone $baseQuery)
            ->orderByDesc('likes_count')
            ->orderByDesc('id')
            ->limit(12)
            ->get();

        $storiesByGenre = $stories
            ->filter(fn ($story) => filled($story->genre))
            ->groupBy(fn ($story) => Str::lower(trim((string) $story->genre)))
            ->map(function ($group) {
                return $group
                    ->sortByDesc('story_reads_count')
                    ->take(12)
                    ->values();
            })
            ->sortKeys();

        return view('diary.stories.index', compact('stories', 'topViewed', 'topLiked', 'storiesByGenre'));
    }

    public function show(string $slug)
    {
        $story = Story::where('slug', $slug)
            ->with(['user:id,name,username,avatar,bio', 'publishedChapters'])
            ->withCount('storyReads')
            ->firstOrFail();

        if (! $story->isPublic()) {
            if (! auth()->check() || auth()->id() !== $story->user_id) {
                abort(404);
            }
        }

        $likesCount = $story->likes()->count();
        $commentsCount = $story->chapters->sum(fn ($c) => $c->allComments()->where('status', 'visible')->count());
        $inLibrary = auth()->check() && auth()->user()->library()->where('story_id', $story->id)->exists();
        $hasLiked = auth()->check() && $story->likes()->where('user_id', auth()->id())->exists();

        return view('diary.stories.show', compact('story', 'likesCount', 'commentsCount', 'inLibrary', 'hasLiked'));
    }

    public function create()
    {
        return view('diary.stories.create');
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'cover_image' => 'nullable',
            'genre' => 'nullable|string|max:100',
            'language' => 'required|in:hindi,hinglish',
            'story_type' => 'required|in:short_story,series,poems',
            'content_guidance' => 'nullable|string|max:255',
            'visibility' => 'required|in:public,draft,premium',
            'theme' => 'required|in:light,dark,sepia',
            'bg_color' => 'nullable|string|max:20',
            'bg_image' => 'nullable',
            'tags_json' => 'nullable|string',
            'status' => 'required|in:ongoing,completed',
        ]);

        $slug = Str::slug($request->title);
        if (Story::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . uniqid();
        }

        $tags = [];
        if ($request->filled('tags_json')) {
            $decoded = json_decode($request->tags_json, true);
            if (is_array($decoded)) {
                $tags = array_values(array_filter(array_map(fn ($t) => trim((string) $t), $decoded)));
            }
        }

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $request->validate(['cover_image' => 'image|max:3072']);
            $coverPath = $request->file('cover_image')->store('stories/covers', 'public');
        } elseif (is_string($request->cover_image) && $request->cover_image !== '') {
            $coverPath = $request->cover_image;
        }

        $bgPath = null;
        if ($request->hasFile('bg_image')) {
            $request->validate(['bg_image' => 'image|max:4096']);
            $bgPath = $request->file('bg_image')->store('stories/backgrounds', 'public');
        } elseif (is_string($request->bg_image) && $request->bg_image !== '') {
            $bgPath = $request->bg_image;
        }

        $story = auth()->user()->stories()->create([
            'title' => $valid['title'],
            'slug' => $slug,
            'description' => $valid['description'] ?? null,
            'cover_image' => $coverPath,
            'genre' => $valid['genre'] ?? null,
            'tags' => $tags ?: null,
            'language' => $valid['language'],
            'story_type' => $valid['story_type'],
            'type' => $valid['story_type'],
            'content_guidance' => $valid['content_guidance'] ?? null,
            'visibility' => $valid['visibility'],
            'theme' => $valid['theme'],
            'bg_color' => $valid['bg_color'] ?? null,
            'bg_image' => $bgPath,
            'status' => $valid['status'],
            'approval_status' => 'pending',
        ]);

        return redirect()->route('diary.chapters.create', $story)->with('success', 'Step 1 done. Start writing your first chapter.');
    }

    public function edit(Story $story)
    {
        $this->authorize('update', $story);
        $story->load('chapters');
        $nextNumber = $story->chapters()->max('chapter_number') + 1;
        return view('diary.stories.edit', compact('story', 'nextNumber'));
    }

    public function update(Request $request, Story $story)
    {
        $this->authorize('update', $story);

        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'cover_image' => 'nullable',
            'genre' => 'nullable|string|max:100',
            'language' => 'required|in:hindi,hinglish',
            'story_type' => 'required|in:short_story,series,poems',
            'content_guidance' => 'nullable|string|max:255',
            'visibility' => 'required|in:public,draft,premium',
            'theme' => 'required|in:light,dark,sepia',
            'bg_color' => 'nullable|string|max:20',
            'bg_image' => 'nullable',
            'tags_json' => 'nullable|string',
            'status' => 'required|in:ongoing,completed',
        ]);

        $tags = [];
        if ($request->filled('tags_json')) {
            $decoded = json_decode($request->tags_json, true);
            if (is_array($decoded)) {
                $tags = array_values(array_filter(array_map(fn ($t) => trim((string) $t), $decoded)));
            }
        }

        $data = [
            'title' => $valid['title'],
            'description' => $valid['description'] ?? null,
            'genre' => $valid['genre'] ?? null,
            'tags' => $tags ?: null,
            'language' => $valid['language'],
            'story_type' => $valid['story_type'],
            'type' => $valid['story_type'],
            'content_guidance' => $valid['content_guidance'] ?? null,
            'visibility' => $valid['visibility'],
            'theme' => $valid['theme'],
            'bg_color' => $valid['bg_color'] ?? null,
            'status' => $valid['status'],
        ];

        if ($request->hasFile('cover_image')) {
            $request->validate(['cover_image' => 'image|max:3072']);
            if ($story->cover_image && !str_starts_with($story->cover_image, 'http')) {
                Storage::disk('public')->delete($story->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('stories/covers', 'public');
        } elseif (is_string($request->cover_image) && $request->cover_image !== '') {
            $data['cover_image'] = $request->cover_image;
        }

        if ($request->hasFile('bg_image')) {
            $request->validate(['bg_image' => 'image|max:4096']);
            if ($story->bg_image && !str_starts_with($story->bg_image, 'http')) {
                Storage::disk('public')->delete($story->bg_image);
            }
            $data['bg_image'] = $request->file('bg_image')->store('stories/backgrounds', 'public');
        } elseif (is_string($request->bg_image) && $request->bg_image !== '') {
            $data['bg_image'] = $request->bg_image;
        }

        $story->update($data);

        return back()->with('success', 'Story updated.');
    }

    public function destroy(Story $story)
    {
        $this->authorize('delete', $story);
        $story->delete();
        return redirect()->route('diary.dashboard')->with('success', 'Story deleted.');
    }
}
