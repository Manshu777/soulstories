@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <p class="text-xs uppercase tracking-wider text-[#6366F1] font-semibold">Category</p>
            <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">{{ $category->name }}</h1>
        </div>
        <a href="{{ route('diary.categories.index') }}" class="text-sm text-[#6366F1] hover:text-[#4F46E5]">All categories →</a>
    </div>

    <form method="GET" class="rounded-2xl border border-slate-200 bg-white p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="text-xs font-semibold uppercase text-slate-500">Sort by</label>
                <select name="sort" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="latest" @selected($sort === 'latest')>Latest</option>
                    <option value="popular" @selected($sort === 'popular')>Popular</option>
                    <option value="trending" @selected($sort === 'trending')>Trending</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase text-slate-500">Status</label>
                <select name="status" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">All</option>
                    <option value="ongoing" @selected($status === 'ongoing')>Ongoing</option>
                    <option value="completed" @selected($status === 'completed')>Completed</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full rounded-lg bg-[#6366F1] text-white text-sm font-semibold py-2.5">Apply</button>
            </div>
        </div>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($stories as $story)
            <a href="{{ route('diary.stories.show', $story->slug) }}" class="rounded-2xl border border-slate-200 bg-white p-3 hover:border-[#6366F1] transition">
                <div class="flex gap-3">
                    @if($story->cover_image)
                        <img src="{{ $story->cover_image }}" alt="{{ $story->title }}" class="w-20 h-24 object-cover rounded-lg">
                    @else
                        <div class="w-20 h-24 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">📖</div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h3 class="font-semibold text-slate-900 truncate">{{ $story->title }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $story->user->name }}</p>
                        <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ Str::limit($story->description, 110) }}</p>
                        <div class="mt-2 text-xs text-slate-500 flex flex-wrap gap-2">
                            <span>👁 {{ number_format($story->story_reads_count) }}</span>
                            <span>❤️ {{ number_format($story->likes_count) }}</span>
                            <span>📚 {{ $story->parts_count }}</span>
                            <span>⏱ {{ $story->read_time }} min</span>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-500">No stories in this category yet.</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $stories->withQueryString()->links() }}
    </div>
</div>
@endsection
