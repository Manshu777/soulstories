<?php

namespace App\Services;

class ReadTimeService
{
    /** Average reading speed: words per minute (Hindi/Hinglish ~150-200). */
    protected int $wordsPerMinute = 180;

    public function estimateMinutes(string $content): int
    {
        $text = strip_tags($content);
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $wordCount = count($words);
        $minutes = (int) ceil($wordCount / $this->wordsPerMinute);

        return max(1, min($minutes, 120)); // 1–120 min
    }
}
