@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Search results</h1>
        <p class="text-sm text-slate-500 mt-1">Query: <span class="font-medium text-slate-700">{{ $q ?: 'All' }}</span></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <aside class="lg:col-span-3 rounded-2xl border border-slate-200 bg-white p-4 h-fit sticky top-24">
            <form method="GET" action="{{ route('diary.search') }}" class="space-y-4">
                <input type="hidden" name="q" value="{{ $q }}">

                <div>
                    <label class="text-xs font-semibold uppercase text-slate-500">Content Type</label>
                    <select name="content_type" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                        <option value="stories" @selected(($contentType ?? 'stories') === 'stories')>Stories ({{ $counts['stories'] }})</option>
                        <option value="authors" @selected(($contentType ?? '') === 'authors')>Authors ({{ $counts['authors'] }})</option>
                        <option value="blogs" @selected(($contentType ?? '') === 'blogs')>Blogs ({{ $counts['blogs'] }})</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase text-slate-500">Length</label>
                    <select name="length" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                        <option value="">Any</option>
                        <option value="1-10" @selected(($filters['length'] ?? '') === '1-10')>1-10 min</option>
                        <option value="10-20" @selected(($filters['length'] ?? '') === '10-20')>10-20 min</option>
                        <option value="20-40" @selected(($filters['length'] ?? '') === '20-40')>20-40 min</option>
                        <option value="40+" @selected(($filters['length'] ?? '') === '40+')>40+ min</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase text-slate-500">Status</label>
                    <select name="status" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                        <option value="">Any</option>
                        <option value="ongoing" @selected(($filters['status'] ?? '') === 'ongoing')>Ongoing</option>
                        <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Completed</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase text-slate-500">Genre</label>
                    <input type="text" name="genre" value="{{ $filters['genre'] ?? '' }}" placeholder="romance" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase text-slate-500">Date</label>
                    <select name="date" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                        <option value="">Any</option>
                        <option value="today" @selected(($filters['date'] ?? '') === 'today')>Today</option>
                        <option value="week" @selected(($filters['date'] ?? '') === 'week')>This Week</option>
                        <option value="month" @selected(($filters['date'] ?? '') === 'month')>This Month</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase text-slate-500">Sort</label>
                    <select name="sort" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                        <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>Latest</option>
                        <option value="popularity" @selected(($filters['sort'] ?? '') === 'popularity')>Popularity</option>
                        <option value="trending" @selected(($filters['sort'] ?? '') === 'trending')>Trending</option>
                    </select>
                </div>

                <button type="submit" class="w-full rounded-lg bg-[#6366F1] text-white py-2 text-sm font-semibold">Apply Filters</button>
            </form>

            @if($trendingKeywords->isNotEmpty())
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <p class="text-xs font-semibold uppercase text-slate-500 mb-2">Trending Searches</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($trendingKeywords as $keyword)
                            <a href="{{ route('diary.search', ['q' => $keyword, 'content_type' => $contentType]) }}" class="text-xs px-2.5 py-1 rounded-full bg-[#EEF2FF] text-[#6366F1] hover:bg-[#E0E7FF]">#{{ $keyword }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>

        <section class="lg:col-span-9 space-y-4">
            @forelse($results as $item)
                @php($isStory = ($contentType ?? 'stories') === 'stories')
                @php($isAuthor = ($contentType ?? '') === 'authors')
                @php($url = $isAuthor ? route('diary.authors.show', $item->username) : ($isStory ? route('diary.stories.show', $item->slug) : '#'))
                <a href="{{ $url }}" class="block rounded-2xl border border-slate-200 bg-white p-4 hover:border-[#6366F1] transition">
                    <div class="flex gap-4">
                        @php($thumb = $isAuthor ? $item->avatar : $item->cover_image)
                        @if($thumb)
                            <img src="{{ $thumb }}" alt="" class="w-20 h-24 rounded-lg object-cover">
                        @else
                            <div class="w-20 h-24 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">📖</div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <h3 class="text-lg font-semibold text-slate-900 truncate">{{ $isAuthor ? $item->name : $item->title }}</h3>
                            <p class="text-xs uppercase tracking-wide text-[#6366F1] font-semibold mt-0.5">{{ strtoupper($contentType ?? 'stories') }}</p>

                            @if($isStory)
                                <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ Str::limit($item->description, 170) }}</p>
                                <div class="flex flex-wrap gap-2 text-xs text-slate-500 mt-2">
                                    <span>👁 {{ number_format($item->story_reads_count ?? 0) }} Reads</span>
                                    <span>❤️ {{ number_format($item->likes_count ?? 0) }} Votes</span>
                                    <span>📚 {{ $item->parts_count ?? 0 }} Parts</span>
                                    <span>⏱ {{ $item->read_time }} min</span>
                                </div>
                            @elseif($isAuthor)
                                <p class="text-sm text-slate-600 mt-1">@{{ $item->username }}</p>
                                <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $item->bio ?: 'No bio available.' }}</p>
                            @else
                                <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ Str::limit(strip_tags($item->body), 170) }}</p>
                                <div class="text-xs text-slate-500 mt-2">🗓 {{ $item->created_at?->format('M d, Y') }}</div>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-500">No results found.</div>
            @endforelse

            <div>
                {{ $results->withQueryString()->links() }}
            </div>
        </section>
    </div>
</div>
@endsection
