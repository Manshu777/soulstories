@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
    <section class="rounded-2xl border border-[#E5E7EB] bg-white shadow-[0_8px_24px_-16px_rgba(17,24,39,0.25)] p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-5 sm:gap-6">
            @if($author->avatar)
                <img src="{{ $author->avatar }}" alt="{{ $author->name }}" class="w-24 h-24 rounded-full object-cover border border-[#E5E7EB]">
            @else
                <div class="w-24 h-24 rounded-full bg-[#EEF2FF] border border-[#E5E7EB] flex items-center justify-center text-3xl font-semibold text-[#6366F1]">
                    {{ Str::upper(Str::limit($author->name, 1)) }}
                </div>
            @endif

            <div class="min-w-0 flex-1">
                <h1 class="text-2xl sm:text-3xl font-semibold text-[#111827]">{{ $author->name }}</h1>
                <p class="text-sm text-[#6B7280] mt-1">@{{ $author->username }}</p>

                @if($author->bio)
                    <p class="mt-3 text-[#6B7280] leading-relaxed">{{ $author->bio }}</p>
                @endif

                <div class="flex flex-wrap items-center gap-5 mt-4 text-sm text-[#6B7280]">
                    <span><strong class="text-[#111827]">{{ number_format($followers_count) }}</strong> followers</span>
                    <span><strong class="text-[#111827]">{{ number_format($following_count) }}</strong> following</span>
                    <span class="text-[#6366F1]">Followed by {{ number_format($followers_count) }} users</span>
                </div>
            </div>

            <div class="sm:self-start">
                @auth
                    @if($author->id !== auth()->id())
                        @if($isFollowing)
                            <form action="{{ route('diary.unfollow', $author->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center rounded-xl px-5 py-2.5 text-sm font-semibold border border-[#6366F1] text-[#6366F1] hover:bg-[#EEF2FF] transition">
                                    Following
                                </button>
                            </form>
                        @else
                            <form action="{{ route('diary.follow', $author->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center rounded-xl px-5 py-2.5 text-sm font-semibold bg-[#6366F1] text-white border border-[#6366F1] hover:bg-[#4F46E5] transition">
                                    Follow
                                </button>
                            </form>
                        @endif
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl px-5 py-2.5 text-sm font-semibold bg-[#6366F1] text-white border border-[#6366F1] hover:bg-[#4F46E5] transition">
                        Login to Follow
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <section class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-[#111827]">Recent Works</h2>
            <span class="text-xs text-[#6B7280]">Latest {{ min($recent_stories->count(), 10) }} stories</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recent_stories as $story)
                <a href="{{ route('diary.stories.show', $story->slug) }}" class="rounded-2xl border border-[#E5E7EB] bg-white p-3 shadow-[0_8px_24px_-16px_rgba(17,24,39,0.25)] hover:border-[#6366F1] transition">
                    <div class="flex gap-3">
                        @if($story->cover_image)
                            <img src="{{ $story->cover_image }}" alt="{{ $story->title }}" class="w-20 h-24 rounded-lg object-cover border border-[#E5E7EB]">
                        @else
                            <div class="w-20 h-24 rounded-lg bg-[#EEF2FF] border border-[#E5E7EB] flex items-center justify-center text-[#6366F1]">📖</div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <h3 class="font-semibold text-[#111827] truncate">{{ $story->title }}</h3>
                            <p class="text-xs text-[#6B7280] mt-1 line-clamp-2">{{ Str::limit($story->description, 100) }}</p>
                            <div class="mt-2 text-xs text-[#6B7280] flex flex-wrap gap-2">
                                <span>👁 {{ number_format($story->story_reads_count) }}</span>
                                <span>❤️ {{ number_format($story->likes_count) }}</span>
                                <span>📚 {{ $story->parts_count }}</span>
                                <span>⏱ {{ $story->read_time }} min</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-2xl border border-[#E5E7EB] bg-white p-8 text-center text-[#6B7280]">
                    No stories published yet.
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
