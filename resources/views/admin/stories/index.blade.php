@extends('layouts.admin')

@section('title', 'Stories')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Stories</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-500 hover:text-slate-700">← Dashboard</a>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('admin.stories.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('approval') ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">All</a>
        <a href="{{ route('admin.stories.index', ['approval' => 'pending']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('approval') === 'pending' ? 'bg-amber-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-amber-50' }}">Pending</a>
        <a href="{{ route('admin.stories.index', ['approval' => 'approved']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('approval') === 'approved' ? 'bg-emerald-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-emerald-50' }}">Approved</a>
        <a href="{{ route('admin.stories.index', ['approval' => 'rejected']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('approval') === 'rejected' ? 'bg-red-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-red-50' }}">Rejected</a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Story</th>
                        <th class="px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Author</th>
                        <th class="px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Reads</th>
                        <th class="px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($stories as $story)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-4">
                            <a href="{{ route('diary.stories.show', $story->slug) }}" target="_blank" class="font-medium text-slate-800 hover:text-orange-600">{{ Str::limit($story->title, 45) }}</a>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $story->created_at->format('M d, Y') }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-slate-700">{{ $story->user->name ?? '—' }}</span>
                            <p class="text-xs text-slate-400">@{{ $story->user->username ?? '' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            @if($story->approval_status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>
                            @elseif($story->approval_status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Approved</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-600 font-medium">{{ number_format($story->story_reads_count) }}</td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex flex-wrap items-center justify-end gap-1">
                                @if($story->approval_status === 'pending')
                                <form action="{{ route('admin.stories.approve', $story) }}" method="post" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200">Approve</button>
                                </form>
                                <form action="{{ route('admin.stories.reject', $story) }}" method="post" class="inline flex items-center gap-1" onsubmit="return confirm('Reject this story?');">
                                    @csrf
                                    <input type="text" name="admin_notes" placeholder="Reason (optional)" class="w-28 px-2 py-1 text-xs border border-slate-200 rounded-lg focus:ring-1 focus:ring-slate-300">
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-red-100 text-red-700 rounded-lg hover:bg-red-200 whitespace-nowrap">Reject</button>
                                </form>
                                @endif
                                <form action="{{ route('admin.stories.destroy', $story) }}" method="post" class="inline" onsubmit="return confirm('Delete this story permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200">Delete</button>
                                </form>
                                <form action="{{ route('admin.users.block', $story->user) }}" method="post" class="inline" onsubmit="return confirm('Block author {{ $story->user->name }}?');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200">Block user</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-slate-500">No stories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stories->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $stories->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
