<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Search\AuthorSearchResource;
use App\Http\Resources\Search\BlogSearchResource;
use App\Http\Resources\Search\StorySearchResource;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private readonly SearchService $searchService) {}

    public function index(Request $request): JsonResponse
    {
        $valid = $request->validate([
            'q' => 'nullable|string|max:120',
            'suggest' => 'nullable|boolean',
            'content_type' => 'nullable|in:stories,authors,blogs',
            'length' => 'nullable|in:1-10,10-20,20-40,40+',
            'status' => 'nullable|in:ongoing,completed',
            'genre' => 'nullable|string|max:100',
            'tag' => 'nullable|string|max:100',
            'date' => 'nullable|in:today,week,month',
            'sort' => 'nullable|in:popularity,latest,trending',
        ]);

        $q = trim((string) ($valid['q'] ?? ''));
        $suggest = filter_var($valid['suggest'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $data = $this->searchService->searchApi($q, $valid, $suggest);

        if ($q !== '') {
            $this->searchService->trackKeyword($q, auth()->id());
        }

        return response()->json([
            'stories' => StorySearchResource::collection($data['stories'])->resolve(),
            'authors' => AuthorSearchResource::collection($data['authors'])->resolve(),
            'blogs' => BlogSearchResource::collection($data['blogs'])->resolve(),
            'tags' => $data['tags']->values(),
            'meta' => [
                'q' => $q,
                'suggest' => $suggest,
                'trending_keywords' => $this->searchService->trendingKeywords(),
                'recent_searches' => $this->searchService->recentHistory(auth()->id()),
            ],
        ]);
    }
}
