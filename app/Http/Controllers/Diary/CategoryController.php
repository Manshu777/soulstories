<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Story;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->where('status', 'active')
            ->withCount('stories')
            ->orderBy('name')
            ->get();

        return view('diary.categories.index', compact('categories'));
    }

    public function show(Request $request, string $slug)
    {
        $category = Category::query()
            ->where('status', 'active')
            ->where('slug', $slug)
            ->firstOrFail();

        $sort = $request->string('sort', 'latest')->toString();
        $status = $request->string('status')->toString();

        $stories = Story::query()
            ->where('category_id', $category->id)
            ->where('approval_status', 'approved')
            ->whereIn('visibility', ['public', 'premium'])
            ->with('user:id,name,username,avatar')
            ->withCount(['storyReads', 'likes as likes_count', 'publishedChapters as parts_count']);

        if (in_array($status, ['ongoing', 'completed'], true)) {
            $stories->where('status', $status);
        }

        if ($sort === 'popular') {
            $stories->orderByDesc('story_reads_count')->orderByDesc('likes_count');
        } elseif ($sort === 'trending') {
            $stories
                ->withCount([
                    'storyReads as recent_reads_count' => fn ($q) => $q->where('created_at', '>=', now()->subDays(7)),
                    'likes as recent_likes_count' => fn ($q) => $q->where('created_at', '>=', now()->subDays(7)),
                ])
                ->orderByRaw('(recent_reads_count * 2) + (recent_likes_count * 4) DESC');
        } else {
            $stories->latest();
        }

        $stories = $stories->paginate(12)->withQueryString();

        return view('diary.categories.show', compact('category', 'stories', 'sort', 'status'));
    }
}
