<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\StoryReport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request, Story $story)
    {
        $valid = $request->validate([
            'reason' => 'nullable|string|max:100',
            'details' => 'nullable|string|max:1000',
        ]);

        StoryReport::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'story_id' => $story->id,
            ],
            [
                'reason' => $valid['reason'] ?? null,
                'details' => $valid['details'] ?? null,
            ]
        );

        return back()->with('success', 'Report submitted. We will review it.');
    }
}
