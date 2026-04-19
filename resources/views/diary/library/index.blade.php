@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 sm:px-6">
    <h1 class="font-serif text-2xl font-semibold text-slate-800 mb-6">My Library</h1>

    @if(session('success'))
    <p class="mb-4 text-green-600 text-sm">{{ session('success') }}</p>
    @endif

    <div class="space-y-4">
        @forelse($stories as $story)
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 py-4 border-b border-slate-100">
            <a href="{{ route('diary.stories.show', $story->slug) }}" class="flex-1 min-w-0">
                <p class="font-medium text-slate-800 truncate">{{ $story->title }}</p>
                <p class="text-sm text-slate-500">{{ $story->user->name ?? 'Author' }} · {{ $story->read_time }} min · {{ number_format($story->story_reads_count) }} reads</p>
            </a>
            <form action="{{ route('diary.library.destroy', $story) }}" method="post" class="flex-shrink-0 ml-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-slate-500 hover:text-red-600">Remove</button>
            </form>
        </div>
        @empty
        <p class="text-slate-500 py-12 text-center">No saved stories. <a href="{{ route('diary.home') }}" class="text-orange-500 hover:underline">Browse stories</a>.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $stories->links() }}
    </div>
</div>
@endsection
