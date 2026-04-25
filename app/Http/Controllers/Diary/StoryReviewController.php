<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;

class StoryReviewController extends Controller
{
    public function store(Request $request, Story $story)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $story->reviews()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'rating' => (int) $request->rating,
                'comment' => $request->comment,
                'status' => 'visible',
            ]
        );

        return back()->with('success', 'Your rating and comment were saved.');
    }
}
