@extends('layouts.app')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;1,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
@endpush

@section('content')
<style>
    .reader-shell {
        background:
            radial-gradient(ellipse 80% 50% at 50% -20%, rgba(167, 139, 250, 0.28), transparent),
            radial-gradient(ellipse 60% 40% at 100% 50%, rgba(196, 181, 253, 0.2), transparent),
            linear-gradient(180deg, #eef2ff 0%, #f8f7ff 50%, #f5f3ff 100%);
    }
</style>

<div class="reader-shell min-h-[calc(100vh-4rem)] pb-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <nav class="text-sm text-slate-500 mb-8">
            <a href="{{ route('diary.home') }}" class="hover:text-violet-700">Home</a>
            <span class="mx-2 text-slate-300">/</span>
            <span class="text-slate-700">{{ $story->title }}</span>
        </nav>

        @if(!$story->isPublic())
            <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-100 px-4 py-3 text-amber-900 text-sm">
                This story is {{ $story->visibility === 'draft' ? 'a draft' : 'pending approval' }}. Only you can see it.
            </div>
        @endif

        <article class="rounded-[2rem] bg-white/90 backdrop-blur-sm border border-white/80 shadow-xl shadow-violet-200/30 overflow-hidden">
            @if($story->cover_image)
                <img src="{{ $story->cover_image }}" alt="{{ $story->title }}" class="w-full aspect-[2/1] object-cover">
            @else
                <div class="w-full aspect-[2/1] bg-gradient-to-br from-violet-100 to-fuchsia-100 flex items-center justify-center text-6xl">📖</div>
            @endif

            <div class="px-6 sm:px-10 py-8 sm:py-10">
                <h1 class="text-3xl sm:text-4xl font-semibold text-slate-900 tracking-tight" style="font-family: 'Playfair Display', serif;">
                    {{ $story->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-3 mt-5 text-sm text-slate-500">
                    <a href="{{ route('diary.authors.show', $story->user->username) }}" class="inline-flex items-center gap-2 hover:text-violet-700 transition">
                        @if($story->user->avatar)
                            <img src="{{ $story->user->avatar }}" alt="" class="w-9 h-9 rounded-full object-cover ring-2 ring-violet-100">
                        @else
                            <span class="w-9 h-9 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 font-semibold text-sm">{{ Str::upper(Str::limit($story->user->name, 1)) }}</span>
                        @endif
                        <span class="text-slate-700 font-medium">{{ $story->user->name }}</span>
                    </a>
                    <span class="text-slate-300">·</span>
                    <span>{{ $story->read_time }} min read</span>
                    <span class="text-slate-300">·</span>
                    <span>{{ number_format($likesCount) }} likes</span>
                    <span class="text-slate-300">·</span>
                    <span>{{ number_format($story->story_reads_count) }} reads</span>
                </div>

                @if($story->tags && count($story->tags))
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach($story->tags as $tag)
                            <span class="text-xs px-3 py-1 rounded-full bg-violet-50 text-violet-800 border border-violet-100">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                @if($story->description)
                    <div class="mt-8 text-slate-600 leading-relaxed text-lg" style="font-family: 'Playfair Display', Georgia, serif;">
                        {{ $story->description }}
                    </div>
                @endif

                <div class="flex flex-wrap gap-2 mt-8">
                    @auth
                        <form action="{{ route('diary.authors.follow', $story->user) }}" method="post" class="inline">
                            @csrf
                            <button type="submit" class="text-sm px-5 py-2.5 rounded-full border border-violet-200 bg-white text-violet-900 hover:bg-violet-50 font-medium shadow-sm transition">Follow</button>
                        </form>
                        <form action="{{ route('diary.like.story', $story) }}" method="post" class="inline">
                            @csrf
                            @if(!$hasLiked)
                                <button type="submit" class="text-sm px-5 py-2.5 rounded-full bg-violet-100 text-violet-900 hover:bg-violet-200 font-medium transition">❤️ Like</button>
                            @else
                                <button type="submit" class="text-sm px-5 py-2.5 rounded-full bg-red-50 text-red-600 border border-red-100 font-medium">Liked</button>
                            @endif
                        </form>
                        @if($inLibrary)
                            <form action="{{ route('diary.library.destroy', $story) }}" method="post" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm px-5 py-2.5 rounded-full border border-slate-200 text-slate-700 hover:bg-slate-50">Remove from library</button>
                            </form>
                        @else
                            <form action="{{ route('diary.library.store', $story) }}" method="post" class="inline">
                                @csrf
                                <button type="submit" class="text-sm px-5 py-2.5 rounded-full border border-violet-200 text-violet-800 hover:bg-violet-50">Add to library</button>
                            </form>
                        @endif
                        <form action="{{ route('diary.report.store', $story) }}" method="post" class="inline">
                            @csrf
                            <button type="submit" class="text-sm px-4 py-2.5 text-slate-500 hover:text-red-600">Report</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm px-5 py-2.5 rounded-full border border-violet-200 text-violet-800 hover:bg-violet-50 font-medium">Log in to like & save</a>
                    @endauth
                    <button type="button" id="share-story-btn" class="text-sm px-4 py-2.5 text-slate-500 hover:text-violet-700">Share</button>
                </div>
            </div>
        </article>

        <section class="mt-10 rounded-[2rem] bg-white/90 backdrop-blur-sm border border-white/80 shadow-lg shadow-violet-200/20 p-6 sm:p-8">
            <h2 class="text-xl font-semibold text-slate-900 mb-6" style="font-family: 'Playfair Display', serif;">Chapters</h2>
            <ul class="space-y-1">
                @foreach($story->publishedChapters as $ch)
                    <li>
                        <a href="{{ route('diary.chapters.show', [$story, $ch]) }}" class="flex items-center justify-between gap-4 rounded-2xl py-3 px-4 hover:bg-violet-50/80 border border-transparent hover:border-violet-100 transition group">
                            <span class="font-medium text-slate-800 group-hover:text-violet-900">{{ $ch->chapter_title }}</span>
                            <span class="text-slate-400 text-sm shrink-0">{{ $ch->read_time ?? $ch->reading_time }} min</span>
                        </a>
                    </li>
                @endforeach
            </ul>
            @if($story->publishedChapters->isEmpty())
                <p class="text-slate-500 text-sm">No chapters published yet.</p>
            @endif
        </section>
    </div>
</div>

<script>
document.getElementById('share-story-btn')?.addEventListener('click', function () {
    const title = @json($story->title);
    if (navigator.share) {
        navigator.share({ title: title, url: window.location.href }).catch(function () {});
    } else {
        navigator.clipboard.writeText(window.location.href).then(function () {
            alert('Link copied to clipboard');
        }).catch(function () {});
    }
});
</script>
@endsection
