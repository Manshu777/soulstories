<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $stories = $request->user()->library()
            ->with('user:id,name,username')
            ->withCount('storyReads')
            ->orderByPivot('created_at', 'desc')
            ->paginate(12);

        return view('diary.library.index', compact('stories'));
    }

    public function store(Request $request, Story $story)
    {
        $request->user()->library()->syncWithoutDetaching([$story->id]);
        return back()->with('success', 'Added to library.');
    }

    public function destroy(Request $request, Story $story)
    {
        $request->user()->library()->detach($story->id);
        return back()->with('success', 'Removed from library.');
    }
}
