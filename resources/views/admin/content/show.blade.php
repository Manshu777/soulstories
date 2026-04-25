@extends('admin.layouts.app')
@section('title', 'Story Review')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <section class="xl:col-span-2 rounded-xl border border-[#E5E7EB] bg-white p-5 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-[#111827]">{{ $story->title }}</h2>
                <p class="text-sm text-gray-600 mt-1">by {{ $story->user->name ?? '-' }} · {{ $story->slug }}</p>
            </div>
            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $story->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($story->approval_status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                {{ ucfirst($story->approval_status) }}
            </span>
        </div>

        <div class="mt-4 grid sm:grid-cols-2 gap-3 text-sm">
            <div class="rounded-lg border border-[#E5E7EB] p-3">
                <p class="text-xs uppercase text-gray-500">Category</p>
                <p class="mt-1 font-medium text-[#111827]">{{ $story->category->name ?? '-' }}</p>
            </div>
            <div class="rounded-lg border border-[#E5E7EB] p-3">
                <p class="text-xs uppercase text-gray-500">Type</p>
                <p class="mt-1 font-medium text-[#111827]">{{ $story->story_type ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-4">
            <p class="text-xs uppercase text-gray-500 mb-2">Tags</p>
            <div class="flex flex-wrap gap-1.5">
                @forelse((array) $story->tags as $tag)
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-[#EEF2FF] text-[#4F46E5]">#{{ $tag }}</span>
                @empty
                    <span class="text-sm text-gray-500">No tags</span>
                @endforelse
            </div>
        </div>

        @if($story->description)
            <div class="mt-5 rounded-lg border border-[#E5E7EB] p-4">
                <p class="text-xs uppercase text-gray-500 mb-2">Description</p>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $story->description }}</p>
            </div>
        @endif
    </section>

    <aside class="rounded-xl border border-[#E5E7EB] bg-white p-5 shadow-sm">
        <p class="text-sm font-semibold text-[#111827] mb-3">Moderation</p>
        <form action="{{ route('admin.content.stories.approve', $story) }}" method="POST">
            @csrf
            <button class="w-full rounded-lg bg-emerald-600 text-white px-4 py-2.5 text-sm font-semibold hover:bg-emerald-700">Approve Story</button>
        </form>
        <form action="{{ route('admin.content.stories.reject', $story) }}" method="POST" class="mt-2 space-y-2">
            @csrf
            <textarea name="admin_notes" rows="3" placeholder="Reason for rejection" class="w-full rounded-lg border border-[#E2E8F0] px-3 py-2 text-sm"></textarea>
            <button class="w-full rounded-lg bg-rose-600 text-white px-4 py-2.5 text-sm font-semibold hover:bg-rose-700">Reject Story</button>
        </form>
    </aside>
</div>

<section class="mt-6 rounded-xl border border-[#E5E7EB] bg-white p-5 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <h3 class="text-lg font-semibold text-[#111827]">Comments Moderation</h3>
        <form method="GET" class="flex items-center gap-2">
            <select name="status" class="rounded-lg border border-[#E2E8F0] px-3 py-2 text-sm">
                <option value="">All status</option>
                @foreach(['visible', 'hidden', 'removed'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button class="rounded-lg bg-[#6366F1] text-white px-3 py-2 text-sm font-semibold">Filter</button>
        </form>
    </div>

    <div class="space-y-3">
        @forelse($comments as $comment)
            <article class="rounded-lg border border-[#E5E7EB] p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-[#111827]">{{ $comment->user->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Chapter {{ $comment->storyChapter->chapter_number ?? '-' }}: {{ $comment->storyChapter->chapter_title ?? 'Untitled' }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $comment->status === 'visible' ? 'bg-emerald-100 text-emerald-700' : ($comment->status === 'hidden' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                        {{ ucfirst($comment->status) }}
                    </span>
                </div>
                <p class="text-sm text-gray-700 mt-3 whitespace-pre-wrap">{{ $comment->body ?? '[No text body]' }}</p>
                <div class="mt-3 flex items-center gap-2">
                    <form action="{{ route('admin.content.comments.moderate', $comment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="visible">
                        <button class="rounded-lg border border-emerald-200 text-emerald-700 bg-emerald-50 px-3 py-1.5 text-xs font-semibold">Approve</button>
                    </form>
                    <form action="{{ route('admin.content.comments.moderate', $comment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="hidden">
                        <button class="rounded-lg border border-rose-200 text-rose-700 bg-rose-50 px-3 py-1.5 text-xs font-semibold">Disapprove</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="text-sm text-gray-500">No comments found for this story.</p>
        @endforelse
    </div>

    <div class="mt-5">{{ $comments->links() }}</div>
</section>
@endsection
