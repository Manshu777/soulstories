<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiChapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChapterWriterController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'prompt' => 'required|string|min:10|max:3000',
            'mood' => 'required|string|in:love,heartbreak,motivation,dark,life',
            'context' => 'nullable|string|max:5000',
        ]);

        $systemPrompt = <<<'PROMPT'
You are an emotional storytelling AI writing for a platform called "Soul Diaries".
Write deeply engaging, human-like diary chapters.

Rules:
- 800-1200 words
- Emotional, realistic, and relatable
- Start with a strong 2-3 line hook
- Add inner thoughts and emotional twists
- End with a soft cliffhanger or deep thought
- Keep natural language (English or Hinglish)

Return ONLY valid JSON in this shape:
{
  "title": "...",
  "content": "...",
  "continue_reading_after": 300
}
PROMPT;

        $userPrompt = "Mood: {$data['mood']}\nTopic: {$data['prompt']}\nContext: " . ($data['context'] ?? 'N/A');

        $apiKey = config('services.nvidia.api_key');
        if (! $apiKey) {
            return response()->json(['message' => 'NVIDIA_API_KEY is not configured.'], 500);
        }

        $response = Http::baseUrl(rtrim(config('services.nvidia.base_url'), '/'))
            ->withToken($apiKey)
            ->timeout(120)
            ->post('/chat/completions', [
                'model' => config('services.nvidia.model'),
                'temperature' => 0.85,
                'max_tokens' => 2200,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
            ]);

        if (! $response->successful()) {
            return response()->json([
                'message' => 'AI provider error',
                'provider_status' => $response->status(),
                'provider_body' => $response->json(),
            ], 502);
        }

        $raw = data_get($response->json(), 'choices.0.message.content', '');
        $parsed = $this->decodeJsonPayload($raw);

        if (! is_array($parsed) || empty($parsed['title']) || empty($parsed['content'])) {
            return response()->json([
                'message' => 'Failed to parse AI response.',
                'raw' => $raw,
            ], 422);
        }

        $parsed['continue_reading_after'] = (int) ($parsed['continue_reading_after'] ?? 300);

        return response()->json($parsed);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:120000',
            'mood' => 'required|string|in:love,heartbreak,motivation,dark,life',
            'continue_reading_after' => 'nullable|integer|min:50|max:2000',
            'status' => 'nullable|string|in:draft,published',
        ]);

        $chapter = AiChapter::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'mood' => $data['mood'],
            'user_id' => auth()->id(),
            'continue_reading_after' => (int) ($data['continue_reading_after'] ?? 300),
            'status' => $data['status'] ?? 'draft',
        ]);

        return response()->json([
            'message' => 'Chapter saved successfully.',
            'chapter_id' => $chapter->id,
        ], 201);
    }

    private function decodeJsonPayload(string $raw): ?array
    {
        $trimmed = trim($raw);
        $decoded = json_decode($trimmed, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        if (preg_match('/\{.*\}/s', $trimmed, $m)) {
            $decoded = json_decode($m[0], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return [
            'title' => Str::limit(strip_tags($trimmed), 70, ''),
            'content' => $trimmed,
            'continue_reading_after' => 300,
        ];
    }
}
