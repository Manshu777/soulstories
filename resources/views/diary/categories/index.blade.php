@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
    <h1 class="text-2xl font-semibold text-slate-900 mb-6">All Categories</h1>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
        @forelse($categories as $category)
            <a href="{{ route('diary.category.show', $category->slug) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 hover:border-[#6366F1] hover:bg-[#EEF2FF]/40 transition">
                <p class="font-semibold text-slate-800">{{ $category->name }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ $category->stories_count }} stories</p>
            </a>
        @empty
            <p class="text-slate-500 col-span-full">No categories found.</p>
        @endforelse
    </div>
</div>
@endsection
