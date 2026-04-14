@extends('layouts.app')

@section('content')
<style>
    .story-slider { scrollbar-width: none; }
    .story-slider::-webkit-scrollbar { display: none; }
</style>

<div class="max-w-7xl mx-auto px-4 py-8 sm:px-6" x-data>
    <h1 class="text-2xl sm:text-3xl font-serif font-semibold text-slate-900 mb-8">Stories from genres you like</h1>

    @php
        $sliderSections = [
            'Most Viewed Stories' => $topViewed,
            'Most Liked Stories' => $topLiked,
        ];
    @endphp

    @foreach($sliderSections as $sectionTitle => $sectionStories)
        @if($sectionStories->isNotEmpty())
            <section class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-slate-900">{{ $sectionTitle }}</h2>
                    <div class="hidden sm:flex items-center gap-2">
                        <button type="button" @click="$refs['{{ Str::slug($sectionTitle) }}']?.scrollBy({ left: -420, behavior: 'smooth' })" class="w-9 h-9 rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">←</button>
                        <button type="button" @click="$refs['{{ Str::slug($sectionTitle) }}']?.scrollBy({ left: 420, behavior: 'smooth' })" class="w-9 h-9 rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">→</button>
                    </div>
                </div>
                <div class="story-slider flex gap-4 overflow-x-auto pb-1" x-ref="{{ Str::slug($sectionTitle) }}">
                    @foreach($sectionStories as $story)
                        <a href="{{ route('diary.stories.show', $story->slug) }}" class="w-36 sm:w-40 flex-shrink-0 group">
                            @if($story->cover_image)
                                <img src="{{ $story->cover_image }}" alt="{{ $story->title }}" class="w-full h-52 sm:h-56 object-cover rounded-xl ring-1 ring-slate-100 group-hover:ring-violet-200 transition">
                            @else
                                <div class="w-full h-52 sm:h-56 rounded-xl bg-slate-100 flex items-center justify-center text-4xl">📖</div>
                            @endif
                            <h3 class="mt-2 text-sm font-semibold text-slate-800 truncate">{{ $story->title }}</h3>
                            <p class="text-xs text-slate-500 truncate">{{ $story->genre ?: 'General' }}</p>
                            <div class="mt-1 text-xs text-slate-400 flex items-center gap-1">
                                <span>👁</span><span>{{ number_format($story->story_reads_count) }}</span>
                                <span class="mx-1">·</span>
                                <span>❤️ {{ number_format($story->likes_count) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach

    @foreach($storiesByGenre as $genreKey => $genreStories)
        @php($genreRef = 'genre-'.Str::slug($genreKey))
        <section class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">{{ Str::title($genreKey) }}</h2>
                    <p class="text-sm text-slate-500">Popular in {{ Str::title($genreKey) }}</p>
                </div>
                <div class="hidden sm:flex items-center gap-2">
                    <button type="button" @click="$refs['{{ $genreRef }}']?.scrollBy({ left: -420, behavior: 'smooth' })" class="w-9 h-9 rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">←</button>
                    <button type="button" @click="$refs['{{ $genreRef }}']?.scrollBy({ left: 420, behavior: 'smooth' })" class="w-9 h-9 rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">→</button>
                </div>
            </div>

            <div class="story-slider flex gap-4 overflow-x-auto pb-1" x-ref="{{ $genreRef }}">
                @foreach($genreStories as $story)
                    <a href="{{ route('diary.stories.show', $story->slug) }}" class="w-36 sm:w-40 flex-shrink-0 group">
                        @if($story->cover_image)
                            <img src="{{ $story->cover_image }}" alt="{{ $story->title }}" class="w-full h-52 sm:h-56 object-cover rounded-xl ring-1 ring-slate-100 group-hover:ring-violet-200 transition">
                        @else
                            <div class="w-full h-52 sm:h-56 rounded-xl bg-slate-100 flex items-center justify-center text-4xl">📖</div>
                        @endif
                        <h3 class="mt-2 text-sm font-semibold text-slate-800 truncate">{{ $story->title }}</h3>
                        <p class="text-xs text-slate-500 truncate">{{ $story->user->name }}</p>
                        <div class="mt-1 text-xs text-slate-400 flex items-center gap-1">
                            <span>👁</span><span>{{ number_format($story->story_reads_count) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endforeach

    @if($storiesByGenre->isEmpty() && $stories->isEmpty())
        <p class="text-slate-500 py-12 text-center">No stories yet. Be the first to publish.</p>
    @endif

    <section class="mt-12">
        <h2 class="text-xl font-semibold text-slate-900 mb-4">All Stories</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($stories->take(12) as $story)
                <a href="{{ route('diary.stories.show', $story->slug) }}" class="rounded-2xl border border-slate-100 bg-white p-3 hover:border-violet-200 transition">
                    <div class="flex gap-3">
                        @if($story->cover_image)
                            <img src="{{ $story->cover_image }}" alt="{{ $story->title }}" class="w-16 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-16 h-20 rounded-lg bg-slate-100 flex items-center justify-center">📖</div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $story->title }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ $story->user->name }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ number_format($story->story_reads_count) }} reads</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    
    </div>
</div>
@endsection
