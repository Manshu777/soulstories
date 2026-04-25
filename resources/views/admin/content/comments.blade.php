@extends('admin.layouts.app')
@section('title', 'Content - Comments')
@section('content')
<div class="rounded-xl border border-[#E5E7EB] bg-white overflow-auto">
    <table class="w-full text-sm">
        <thead class="bg-[#F9FAFB] text-left text-[#6B7280]"><tr><th class="p-3">Comment</th><th class="p-3">Story</th><th class="p-3">Status</th><th class="p-3">Action</th></tr></thead>
        <tbody>
        @foreach($comments as $comment)
            <tr class="border-t">
                <td class="p-3">{{ \Illuminate\Support\Str::limit($comment->body ?? '[empty]', 120) }}</td>
                <td class="p-3">{{ $comment->storyChapter->story->title ?? '-' }}</td>
                <td class="p-3">{{ $comment->status }}</td>
                <td class="p-3">
                    <form action="{{ route('admin.content.comments.moderate', $comment) }}" method="POST" class="flex gap-2">@csrf @method('PATCH')
                        <select name="status" class="border rounded px-2 py-1">
                            @foreach(['visible','hidden','removed'] as $status)
                                <option value="{{ $status }}" @selected($comment->status===$status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        <button class="px-2 py-1 border rounded">Save</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $comments->links() }}</div>
@endsection
