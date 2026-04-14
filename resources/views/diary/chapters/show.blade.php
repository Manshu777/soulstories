@extends('layouts.app')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;1,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
@endpush

@section('content')
@php
    $plainText = trim(html_entity_decode(strip_tags($chapter->content ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $wordCount = $plainText !== '' ? str_word_count($plainText) : 0;
    $breakAt = (int) ($chapter->continue_reading_after ?? 0);
    $isAuthor = auth()->check() && auth()->id() === $story->user_id;
    $canReadFull = $isAuthor || $breakAt === 0 || $wordCount <= $breakAt || auth()->check();
    $readMins = (int) ($chapter->reading_time ?? $chapter->read_time ?? max(1, (int) ceil($wordCount / 180)));
@endphp

<style>
    .reader-shell {
        background:
            radial-gradient(ellipse 80% 50% at 50% -20%, rgba(167, 139, 250, 0.28), transparent),
            radial-gradient(ellipse 60% 40% at 100% 50%, rgba(196, 181, 253, 0.2), transparent),
            linear-gradient(180deg, #eef2ff 0%, #f8f7ff 50%, #f5f3ff 100%);
    }
    .chapter-content {
        font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
        font-size: 1.125rem;
        line-height: 1.85;
        color: #334155;
    }
    .chapter-content p { margin-bottom: 1.25rem; }
    .chapter-content h1 { font-size: 1.875rem; font-weight: 600; margin: 1.5rem 0 1rem; color: #1e293b; }
    .chapter-content h2 { font-size: 1.5rem; font-weight: 600; margin: 1.25rem 0 0.75rem; color: #1e293b; }
    .chapter-content blockquote {
        border-left: 4px solid #c4b5fd;
        padding-left: 1rem;
        font-style: italic;
        color: #64748b;
        margin: 1.25rem 0;
    }
    .chapter-content ul { list-style: disc; padding-left: 1.5rem; margin: 1rem 0; }
    .chapter-content ol { list-style: decimal; padding-left: 1.5rem; margin: 1rem 0; }
    .chapter-content img { max-width: 100%; height: auto; border-radius: 1rem; margin: 1rem 0; }
    .chapter-content a { color: #7c3aed; text-decoration: underline; text-underline-offset: 2px; }
    .chapter-content strong { font-weight: 600; color: #1e293b; }
</style>

<div class="reader-shell min-h-[calc(100vh-4rem)] pb-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <nav class="text-sm text-slate-500 mb-8 flex flex-wrap items-center gap-2">
            <a href="{{ route('diary.home') }}" class="hover:text-violet-700">Home</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('diary.stories.show', $story->slug) }}" class="hover:text-violet-700 font-medium">{{ $story->title }}</a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-700">{{ $chapter->chapter_title }}</span>
        </nav>

        @if(!$story->isPublic() && $isAuthor)
            <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-100 text-amber-900 px-4 py-3 text-sm">
                This story isn’t public yet. Only you can see this chapter as the author.
            </div>
        @endif

        <article class="rounded-[2rem] bg-white/90 backdrop-blur-sm border border-white/80 shadow-xl shadow-violet-200/30 overflow-hidden">
            <div class="px-6 sm:px-10 pt-10 pb-8">
                <header class="mb-8">
                    <p class="text-xs font-semibold uppercase tracking-wider text-violet-600 mb-2">Chapter {{ $chapter->chapter_number }}</p>
                    <h1 class="text-3xl sm:text-4xl font-semibold text-slate-900 tracking-tight" style="font-family: 'Playfair Display', serif;">
                        {{ $chapter->chapter_title }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-3 mt-4 text-sm text-slate-500">
                        <a href="{{ route('diary.authors.show', $story->user->username) }}" class="inline-flex items-center gap-2 hover:text-violet-700 transition">
                            @if($story->user->avatar)
                                <img src="{{ $story->user->avatar }}" alt="" class="w-9 h-9 rounded-full object-cover ring-2 ring-violet-100">
                            @else
                                <span class="w-9 h-9 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 font-semibold text-sm">{{ Str::upper(Str::limit($story->user->name ?? 'A', 1)) }}</span>
                            @endif
                            <span class="text-slate-700 font-medium">{{ $story->user->name }}</span>
                        </a>
                        <span class="text-slate-300">·</span>
                        <span>{{ $readMins }} min read</span>
                        @if($wordCount > 0)
                            <span class="text-slate-300">·</span>
                            <span>{{ number_format($wordCount) }} words</span>
                        @endif
                    </div>
                </header>

                @if($canReadFull)
                    <div class="chapter-content">
                        {!! $chapter->content !!}
                    </div>
                @else
                    <div class="chapter-content text-slate-700">
                        <p class="whitespace-pre-wrap">{{ Str::words($plainText, $breakAt, '…') }}</p>
                    </div>
                    <div class="mt-8 rounded-2xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100/80 px-5 py-5 text-center">
                        <p class="text-amber-950 font-semibold text-lg mb-1">Continue reading</p>
                        <p class="text-amber-900/80 text-sm mb-4">Sign in to read the rest of this chapter.</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-violet-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-violet-700 transition shadow-lg shadow-violet-300/40">
                            Log in to continue
                        </a>
                    </div>
                @endif

                @if($canReadFull && $chapter->youtube_url)
                    <div class="mt-8 rounded-2xl overflow-hidden border border-violet-100 bg-slate-900 aspect-video max-w-2xl mx-auto">
                        @php
                            $vid = null;
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $chapter->youtube_url, $m)) {
                                $vid = $m[1];
                            }
                        @endphp
                        @if($vid)
                            <iframe class="w-full h-full min-h-[200px]" src="https://www.youtube-nocookie.com/embed/{{ $vid }}" title="YouTube" allowfullscreen loading="lazy"></iframe>
                        @else
                            <p class="p-4 text-white text-sm"><a href="{{ $chapter->youtube_url }}" class="underline" target="_blank" rel="noopener">Open video</a></p>
                        @endif
                    </div>
                @endif

                @if($chapter->audio_file)
                    <div class="mt-8 rounded-2xl bg-violet-50/80 border border-violet-100 p-4">
                        <p class="text-xs font-medium text-violet-800 mb-2">Chapter audio</p>
                        <audio src="{{ $chapter->audio_file }}" controls class="w-full rounded-lg"></audio>
                    </div>
                @endif

                @if($chapter->spotify_playlist)
                    <p class="mt-4">
                        <a href="{{ $chapter->spotify_playlist }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-medium text-violet-700 hover:text-violet-900">
                            <span class="text-lg">🎵</span> Listen on Spotify
                        </a>
                    </p>
                @endif
            </div>

            <div class="px-6 sm:px-10 py-6 bg-slate-50/80 border-t border-violet-100/60 flex flex-wrap items-center gap-3">
                @if($previous)
                    <a href="{{ route('diary.chapters.show', [$story, $previous]) }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-full border border-violet-200 bg-white px-5 py-2.5 text-violet-800 hover:bg-violet-50 transition shadow-sm">
                        ← Previous
                    </a>
                @endif
                @if($next)
                    <a href="{{ route('diary.chapters.show', [$story, $next]) }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-full border border-violet-200 bg-white px-5 py-2.5 text-violet-800 hover:bg-violet-50 transition shadow-sm ml-auto">
                        Next →
                    </a>
                @endif
            </div>
        </article>

        <div class="mt-8 flex flex-wrap items-center gap-3">
            @auth
                <form action="{{ route('diary.like.chapter', [$story, $chapter]) }}" method="post">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition shadow-sm {{ $hasLiked ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-white border border-violet-100 text-violet-800 hover:bg-violet-50' }}">
                        <span>❤️</span> {{ $hasLiked ? 'Liked' : 'Like chapter' }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium bg-white border border-violet-100 text-violet-800 hover:bg-violet-50">Log in to like</a>
            @endauth
            <a href="{{ route('diary.stories.show', $story->slug) }}" class="text-sm text-slate-500 hover:text-violet-700 ml-auto">← Back to story</a>
        </div>

        <section class="mt-12 rounded-[2rem] bg-white/90 backdrop-blur-sm border border-white/80 shadow-lg shadow-violet-200/20 p-6 sm:p-8">
            <h2 class="text-xl font-semibold text-slate-900 mb-6" style="font-family: 'Playfair Display', serif;">Comments</h2>

            @auth
                <form action="{{ route('diary.comments.store') }}" method="post" class="mb-8">
                    @csrf
                    <input type="hidden" name="story_chapter_id" value="{{ $chapter->id }}">
                    <textarea name="body" rows="3" required class="w-full rounded-2xl border border-violet-100 px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-violet-200 outline-none resize-none" placeholder="Share your thoughts…"></textarea>
                    <button type="submit" class="mt-3 rounded-full bg-slate-900 text-white px-6 py-2.5 text-sm font-medium hover:bg-slate-800 transition">Post comment</button>
                </form>
            @else
                <p class="text-slate-500 text-sm mb-6">
                    <a href="{{ route('login') }}" class="text-violet-600 font-medium hover:underline">Log in</a> to join the conversation.
                </p>
            @endauth

            <ul class="space-y-6">
                @forelse($chapter->comments as $comment)
                    <li class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center text-sm font-semibold text-violet-800">
                            {{ Str::upper(Str::limit($comment->user->name ?? 'U', 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-medium text-slate-900">{{ $comment->user->name ?? 'Reader' }}</p>
                            @if($comment->reaction && !$comment->body)
                                <p class="text-slate-600 mt-1">{{ $comment->reaction === 'heart' ? '❤️' : ($comment->reaction === 'fire' ? '🔥' : '😢') }}</p>
                            @elseif($comment->body)
                                <p class="text-slate-600 text-sm mt-1 whitespace-pre-wrap">{{ $comment->body }}</p>
                            @endif
                            @if($comment->selected_text)
                                <p class="mt-2 text-xs text-slate-400 italic border-l-2 border-violet-200 pl-3">“{{ Str::limit($comment->selected_text, 120) }}”</p>
                            @endif
                            @foreach($comment->replies as $reply)
                                <div class="mt-3 pl-4 border-l-2 border-violet-100">
                                    <p class="font-medium text-slate-800 text-sm">{{ $reply->user->name ?? 'User' }}</p>
                                    <p class="text-slate-600 text-sm">{{ $reply->body }}</p>
                                </div>
                            @endforeach
                        </div>
                    </li>
                @empty
                    <li class="text-slate-500 text-sm">No comments yet. Be the first to speak up.</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>
@endsection
