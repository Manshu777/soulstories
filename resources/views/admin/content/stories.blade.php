@extends('admin.layouts.app')
@section('title', 'Content - Stories')
@section('content')
<form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-5">
    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search title or slug" class="rounded-lg border border-[#E2E8F0] px-3 py-2 text-sm bg-white">
    <select name="approval" class="rounded-lg border border-[#E2E8F0] px-3 py-2 text-sm bg-white">
        <option value="">All approval</option>
        @foreach(['pending', 'approved', 'rejected'] as $status)
            <option value="{{ $status }}" @selected(($filters['approval'] ?? '') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    <select name="category_id" class="rounded-lg border border-[#E2E8F0] px-3 py-2 text-sm bg-white">
        <option value="">All categories</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected((string) ($filters['category_id'] ?? '') === (string) $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
    <div class="flex gap-2">
        <input type="text" name="tag" value="{{ $filters['tag'] ?? '' }}" placeholder="Tag filter" class="w-full rounded-lg border border-[#E2E8F0] px-3 py-2 text-sm bg-white">
        <button class="rounded-lg bg-[#6366F1] text-white px-4 py-2 text-sm font-semibold">Filter</button>
    </div>
</form>

<div class="rounded-2xl border border-[#E5E7EB] bg-white shadow-[0_12px_30px_-20px_rgba(15,23,42,0.35)] overflow-auto">
    <table class="w-full text-sm">
        <thead class="bg-[#F8FAFC] text-left text-[#6B7280]">
        <tr>
            <th class="p-4 font-semibold">Story</th>
            <th class="p-4 font-semibold">Author</th>
            <th class="p-4 font-semibold">Category</th>
            <th class="p-4 font-semibold">Tags</th>
            <th class="p-4 font-semibold">Status</th>
            <th class="p-4 font-semibold">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($stories as $story)
            <tr class="border-t border-[#F1F5F9] align-top">
                <td class="p-4">
                    <a href="{{ route('admin.content.stories.show', $story) }}" class="font-semibold text-[#0F172A] hover:text-[#4F46E5] transition-all">{{ $story->title }}</a>
                    <div class="text-xs text-[#64748B] mt-1">{{ number_format($story->story_reads_count) }} reads / {{ number_format($story->likes_count) }} likes</div>
                </td>
                <td class="p-4 text-[#334155]">{{ $story->user->name ?? '-' }}</td>
                <td class="p-4 text-[#334155]">{{ $story->category->name ?? '-' }}</td>
                <td class="p-4">
                    <div class="flex flex-wrap gap-1.5">
                        @forelse((array) $story->tags as $tag)
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium bg-[#EEF2FF] text-[#4F46E5]">#{{ $tag }}</span>
                        @empty
                            <span class="text-xs text-[#94A3B8]">-</span>
                        @endforelse
                    </div>
                </td>
                <td class="p-4">
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $story->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($story->approval_status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ ucfirst($story->approval_status) }}
                    </span>
                </td>
                <td class="p-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <form action="{{ route('admin.content.stories.approve', $story) }}" method="POST">
                            @csrf
                            <button class="rounded-lg bg-emerald-600 text-white px-3 py-1.5 text-xs font-semibold hover:bg-emerald-700">Approve</button>
                        </form>
                        <form action="{{ route('admin.content.stories.reject', $story) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input name="admin_notes" class="border border-[#E2E8F0] rounded-lg px-2.5 py-1.5 text-xs" placeholder="Reason">
                            <button class="rounded-lg bg-rose-600 text-white px-3 py-1.5 text-xs font-semibold hover:bg-rose-700">Reject</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-5">{{ $stories->links() }}</div>
@endsection
