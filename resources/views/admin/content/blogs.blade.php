@extends('admin.layouts.app')
@section('title', 'Content - Blogs')
@section('content')
<div class="rounded-xl border border-[#E5E7EB] bg-white overflow-auto">
    <table class="w-full text-sm">
        <thead class="bg-[#F9FAFB] text-left text-[#6B7280]"><tr><th class="p-3">Blog</th><th class="p-3">Author</th><th class="p-3">Created</th></tr></thead>
        <tbody>
        @foreach($blogs as $blog)
            <tr class="border-t">
                <td class="p-3">{{ $blog->title ?? 'Untitled' }}</td>
                <td class="p-3">{{ $blog->user->name ?? '-' }}</td>
                <td class="p-3">{{ $blog->created_at?->diffForHumans() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $blogs->links() }}</div>
@endsection
