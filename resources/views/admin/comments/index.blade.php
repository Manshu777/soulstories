@extends('layouts.admin')

@section('title', 'Comments')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Moderate comments</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-500 hover:text-slate-700">← Dashboard</a>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('admin.comments.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('status') ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">All</a>
        <a href="{{ route('admin.comments.index', ['status' => 'visible']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'visible' ? 'bg-emerald-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-emerald-50' }}">Visible</a>
        <a href="{{ route('admin.comments.index', ['status' => 'hidden']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'hidden' ? 'bg-amber-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-amber-50' }}">Hidden</a>
        <a href="{{ route('admin.comments.index', ['status' => 'removed']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'removed' ? 'bg-red-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-red-50' }}">Removed</a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Comment</th>
                        <th class="px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Author</th>
                        <th class="px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Story / Chapter</th>
                        <th class="px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($comments as $comment)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-4">
                            <p class="text-slate-800 max-w-md">{{ Str::limit($comment->body, 120) }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $comment->created_at->format('M d, Y H:i') }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-slate-700 font-medium">{{ $comment->user->name ?? '—' }}</span>
                            <p class="text-xs text-slate-400">@{{ $comment->user->username ?? '' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            @if($comment->storyChapter && $comment->storyChapter->story)
                            <a href="{{ route('diary.chapters.show', [$comment->storyChapter->story, $comment->storyChapter]) }}" target="_blank" class="text-sm text-slate-700 hover:text-orange-600">
                                {{ Str::limit($comment->storyChapter->story->title, 30) }} <span class="text-slate-400">· Ch.{{ $comment->storyChapter->chapter_number }}</span>
                            </a>
                            @else
                            <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if($comment->status === 'visible')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Visible</span>
                            @elseif($comment->status === 'hidden')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Hidden</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Removed</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex flex-wrap items-center justify-end gap-1">
                                @if($comment->status !== 'hidden')
                                <form action="{{ route('admin.comments.hide', $comment) }}" method="post" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200">Hide</button>
                                </form>
                                @endif
                                @if($comment->status !== 'removed')
                                <form action="{{ route('admin.comments.remove', $comment) }}" method="post" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-red-100 text-red-700 rounded-lg hover:bg-red-200">Remove</button>
                                </form>
                                @endif
                                @if($comment->status !== 'visible')
                                <form action="{{ route('admin.comments.show', $comment) }}" method="post" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200">Show</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-slate-500">No comments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($comments->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $comments->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
