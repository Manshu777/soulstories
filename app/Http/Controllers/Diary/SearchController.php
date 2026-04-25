<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private readonly SearchService $searchService) {}

    public function index(Request $request)
    {
        $valid = $request->validate([
            'q' => 'nullable|string|max:120',
            'content_type' => 'nullable|in:stories,authors,blogs',
            'length' => 'nullable|in:1-10,10-20,20-40,40+',
            'status' => 'nullable|in:ongoing,completed',
            'genre' => 'nullable|string|max:100',
            'tag' => 'nullable|string|max:100',
            'date' => 'nullable|in:today,week,month',
            'sort' => 'nullable|in:popularity,latest,trending',
        ]);

        $q = trim((string) ($valid['q'] ?? ''));

        $payload = $this->searchService->searchWeb($q, $valid + ['content_type' => $valid['content_type'] ?? 'stories']);

        if ($q !== '') {
            $this->searchService->trackKeyword($q, auth()->id());
        }

        return view('diary.search.index', [
            'q' => $q,
            'filters' => $valid,
            'contentType' => $payload['content_type'],
            'results' => $payload['results'],
            'counts' => $payload['counts'],
            'trendingKeywords' => $this->searchService->trendingKeywords(),
        ]);
    }
}
