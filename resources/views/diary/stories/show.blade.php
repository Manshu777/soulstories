@extends('layouts.app')

@section('content')
@php
    $activeChapterNumber = (int) request('chapter', $firstChapter?->chapter_number ?? 1);
    $activeChapter = $story->publishedChapters->firstWhere('chapter_number', $activeChapterNumber) ?? $firstChapter;
    $paragraphs = $activeChapter ? preg_split('/\n\s*\n/', (string) $activeChapter->content) : [];
@endphp

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    .reader-page { background: #f8f6f2; }
    .manuscript-card { background: #fffdf9; border: 1px solid #ebe7df; box-shadow: 0 16px 40px -24px rgba(17,24,39,0.35); }
    .toc-card { background: #fff; border: 1px solid #ebe7df; }
    .story-title { font-family: 'Playfair Display', serif; }
    .story-body { font-family: 'Cormorant Garamond', serif; font-size: 1.35rem; line-height: 1.9; color: #2f2a24; }
    .story-body p { margin-bottom: 1.2rem; }
    .dropcap:first-letter {
        float: left;
        font-family: 'Playfair Display', serif;
        font-size: 4.8rem;
        line-height: .75;
        padding-right: .5rem;
        color: #a5783f;
        font-weight: 700;
    }
    .toc-link { transition: all .2s ease; }
    .toc-link:hover, .toc-link.active { background: #f5efe5; color: #111827; transform: translateX(3px); }
</style>

<div class="reader-page min-h-[calc(100vh-4rem)] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid lg:grid-cols-12 gap-8">
            <aside class="lg:col-span-4 space-y-5">
                <div class="rounded-xl overflow-hidden shadow-[0_20px_40px_-20px_rgba(17,24,39,0.35)]">
                    @if($story->cover_image)
                        <img src="{{ str_starts_with($story->cover_image, 'http') ? $story->cover_image : '/storage/'.ltrim($story->cover_image, '/') }}" alt="{{ $story->title }}" class="w-full aspect-[3/4] object-cover">
                    @else
                        <div class="aspect-[3/4] bg-[#eef2ff] flex items-center justify-center text-4xl text-[#6366F1]">📖</div>
                    @endif
                </div>

                <div class="toc-card rounded-xl p-4">
                    <h3 class="text-lg text-[#111827] story-title mb-3">Table of Contents</h3>
                    <div class="space-y-1">
                        @forelse($story->publishedChapters as $index => $ch)
                            <a href="{{ route('diary.story.show', ['slug' => $story->slug, 'chapter' => $ch->chapter_number]) }}"
                               class="toc-link {{ $activeChapter && $activeChapter->id === $ch->id ? 'active' : '' }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-[#6B7280]">
                                <span class="w-7 text-xs opacity-60">{{ sprintf('%02d', $index + 1) }}</span>
                                <span class="truncate">{{ $ch->chapter_title ?: 'Untitled chapter' }}</span>
                            </a>
                        @empty
                            <p class="text-sm text-[#6B7280]">No parts yet.</p>
                        @endforelse
                    </div>
                </div>
            </aside>

            <main class="lg:col-span-8">
                <article class="manuscript-card rounded-xl p-6 sm:p-10">
                    <h1 class="story-title text-4xl sm:text-5xl font-bold text-[#111827] leading-tight mb-4">{{ $story->title }}</h1>

                    <div class="flex flex-wrap items-center gap-3 text-sm text-[#6B7280] mb-6 border-b border-[#ece8e0] pb-4">
                        <span class="w-8 h-8 rounded-full border border-[#e4ddcf] bg-white flex items-center justify-center text-xs text-[#8a7a63]">{{ Str::upper(Str::limit($story->user->name, 1)) }}</span>
                        <a href="{{ route('diary.authors.show', $story->user->username) }}" class="hover:text-[#4F46E5] italic">{{ $story->user->name }}</a>
                        <span>•</span>
                        <span>{{ $story->read_time }} min read</span>
                        <span>•</span>
                        <span>{{ number_format($story->story_reads_count) }} readings</span>
                    </div>

                    @if($story->description)
                        <blockquote class="border-l-2 border-[#cda873] pl-4 text-[#7b6a54] italic mb-8 text-lg">{{ $story->description }}</blockquote>
                    @endif

                    @if($activeChapter)
                        @if($activeChapter->chapter_title)
                            <h2 class="story-title text-2xl text-[#3f352b] mb-6 italic">{{ $activeChapter->chapter_title }}</h2>
                        @endif
                        <div class="story-body">
                            @foreach($paragraphs as $i => $para)
                                @if(trim($para))
                                    <p class="{{ $i === 0 ? 'dropcap' : '' }}">{!! nl2br(e(trim($para))) !!}</p>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-center py-16 text-[#6B7280] italic">This story has no published chapters yet.</p>
                    @endif

                    <div class="mt-8 flex flex-wrap gap-2">
                        @auth
                            <form action="{{ route('diary.like.story', $story) }}" method="post">
                                @csrf
                                <button class="rounded-full border border-[#E5E7EB] bg-white px-4 py-2 text-sm text-[#111827] hover:bg-[#f6f6f6]">{{ $hasLiked ? '❤️ Liked' : '🤍 Like' }}</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="rounded-full border border-[#E5E7EB] bg-white px-4 py-2 text-sm text-[#111827] hover:bg-[#f6f6f6]">Login to like</a>
                        @endauth
                        <button id="shareStoryBtn" type="button" class="rounded-full border border-[#E5E7EB] bg-white px-4 py-2 text-sm text-[#111827] hover:bg-[#f6f6f6]">🔗 Share</button>
                    </div>
                </article>
            </main>
        </div>

        <section class="mt-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 lg:col-start-5 rounded-xl border border-[#E5E7EB] bg-white p-5 sm:p-6">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <h3 class="text-xl story-title text-[#111827]">Ratings & Comments</h3>
                    <div class="text-sm text-[#6B7280]">
                        <span class="font-semibold text-[#111827]">{{ $averageRating > 0 ? number_format($averageRating, 1) : '0.0' }}</span>
                        <span class="mx-1">★</span>
                        <span>({{ $story->reviews_count }} ratings)</span>
                    </div>
                </div>

                @auth
                    <form action="{{ route('diary.story.review', $story) }}" method="POST" class="rounded-xl border border-[#E5E7EB] p-4 mb-5">
                        @csrf
                        <label class="text-xs uppercase font-semibold text-[#6B7280]">Your Rating</label>
                        <div class="flex items-center gap-2 mt-2 mb-3">
                            <select name="rating" class="w-28 rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
                                @for($r=5; $r>=1; $r--)
                                    <option value="{{ $r }}" @selected((int) old('rating', $myReview->rating ?? 0) === $r)>{{ $r }} ★</option>
                                @endfor
                            </select>
                            <span class="text-xs text-[#6B7280]">Rate this story</span>
                        </div>
                        <label class="text-xs uppercase font-semibold text-[#6B7280]">Comment</label>
                        <textarea name="comment" rows="3" placeholder="Share your thoughts..." class="mt-1 w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm text-[#111827]">{{ old('comment', $myReview->comment ?? '') }}</textarea>
                        @error('rating') <p class="text-xs text-[#6366F1] mt-1">{{ $message }}</p> @enderror
                        @error('comment') <p class="text-xs text-[#6366F1] mt-1">{{ $message }}</p> @enderror
                        <button type="submit" class="mt-3 rounded-lg bg-[#6366F1] text-white px-4 py-2 text-sm font-semibold border border-[#6366F1] hover:bg-[#4F46E5]">Submit Rating</button>
                    </form>
                @else
                    <div class="rounded-xl border border-[#E5E7EB] p-4 mb-5 text-sm text-[#6B7280]">
                        <a href="{{ route('login') }}" class="text-[#6366F1] font-semibold hover:text-[#4F46E5]">Login</a> to rate and comment.
                    </div>
                @endauth

                <div class="space-y-3">
                    @forelse($story->reviews->sortByDesc('created_at') as $review)
                        <div class="rounded-xl border border-[#E5E7EB] p-4">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-[#111827]">{{ $review->user->name ?? 'Reader' }}</p>
                                <p class="text-xs text-[#6B7280]">{{ $review->created_at?->diffForHumans() }}</p>
                            </div>
                            <p class="text-sm text-[#6B7280] mt-1">{{ str_repeat('★', (int) $review->rating) }}<span class="ml-1 text-[#9ca3af]">{{ str_repeat('★', max(0, 5 - (int) $review->rating)) }}</span></p>
                            @if($review->comment)
                                <p class="mt-2 text-sm text-[#111827] leading-relaxed whitespace-pre-wrap">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-[#6B7280]">No ratings or comments yet. Be the first to review this story.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    document.getElementById('shareStoryBtn')?.addEventListener('click', async () => {
        try {
            if (navigator.share) {
                await navigator.share({ title: @json($story->title), url: window.location.href });
            } else {
                await navigator.clipboard.writeText(window.location.href);
                alert('Link copied');
            }
        } catch (e) {}
    });
</script>
@endsection
