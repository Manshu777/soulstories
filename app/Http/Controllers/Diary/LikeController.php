<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\StoryChapter;
use App\Models\StoryLike;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function story(Story $story)
    {
        $existing = StoryLike::where('user_id', auth()->id())->where('story_id', $story->id)->whereNull('story_chapter_id')->first();
        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Like removed.');
        }
        StoryLike::create([
            'user_id' => auth()->id(),
            'story_id' => $story->id,
            'story_chapter_id' => null,
        ]);
        return back()->with('success', 'Story liked.');
    }

    public function chapter(Story $story, StoryChapter $chapter)
    {
        if ($chapter->story_id !== $story->id) {
            abort(404);
        }
        $existing = StoryLike::where('user_id', auth()->id())->where('story_chapter_id', $chapter->id)->first();
        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Like removed.');
        }
        StoryLike::create([
            'user_id' => auth()->id(),
            'story_id' => null,
            'story_chapter_id' => $chapter->id,
        ]);
        return back()->with('success', 'Chapter liked.');
    }
}
