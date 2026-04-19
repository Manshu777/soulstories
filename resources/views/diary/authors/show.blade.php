@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 sm:px-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
        @if($author->avatar)
        <img src="{{ $author->avatar }}" alt="{{ $author->name }}" class="w-24 h-24 rounded-full object-cover">
        @else
        <div class="w-24 h-24 rounded-full bg-slate-200 flex items-center justify-center text-3xl font-serif text-slate-600">{{ Str::upper(Str::limit($author->name, 1)) }}</div>
        @endif
        <div>
            <h1 class="font-serif text-2xl font-semibold text-slate-800">{{ $author->name }}</h1>
            <p class="text-slate-500">@{{ $author->username }}</p>
            @if($author->bio)
            <p class="mt-2 text-slate-600">{{ $author->bio }}</p>
            @endif
            <div class="flex gap-4 mt-2 text-sm text-slate-500">
                <span>{{ $followersCount }} followers</span>
                <span>{{ $followingCount }} following</span>
            </div>
            @auth
            @if($author->id !== auth()->id())
            @if($isFollowing)
            <form action="{{ route('diary.authors.unfollow', $author) }}" method="post" class="mt-3 inline">
                @csrf
                <button type="submit" class="text-sm px-4 py-2 rounded-full border border-slate-300 text-slate-700 hover:bg-slate-50">Unfollow</button>
            </form>
            @else
            <form action="{{ route('diary.authors.follow', $author) }}" method="post" class="mt-3 inline">
                @csrf
                <button type="submit" class="text-sm px-4 py-2 rounded-full bg-slate-800 text-white hover:bg-slate-700">Follow</button>
            </form>
            @endif
            @endif
            @endauth
        </div>
    </div>

    <section class="mt-10 border-t pt-8">
        <h2 class="font-serif text-xl font-semibold text-slate-800 mb-4">Stories</h2>
        <ul class="space-y-4">
            @foreach($stories as $story)
            <li>
                <a href="{{ route('diary.stories.show', $story->slug) }}" class="block py-3 border-b border-slate-100 hover:border-slate-200">
                    <span class="font-medium text-slate-800">{{ $story->title }}</span>
                    <span class="text-slate-400 text-sm ml-2">{{ $story->read_time }} min · {{ number_format($story->story_reads_count) }} reads</span>
                </a>
            </li>
            @endforeach
        </ul>
        @if($stories->isEmpty())
        <p class="text-slate-500">No stories yet.</p>
        @endif
    </section>
</div>
@endsection
