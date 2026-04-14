@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('admin.stories.index', ['approval' => 'pending']) }}" class="block p-5 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md hover:border-amber-300 transition">
            <p class="text-3xl font-bold text-amber-600">{{ $pendingStories }}</p>
            <p class="text-slate-600 text-sm font-medium mt-1">Pending stories</p>
        </a>
        <a href="{{ route('admin.stories.index', ['approval' => 'approved']) }}" class="block p-5 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-300 transition">
            <p class="text-3xl font-bold text-emerald-600">{{ $approvedStories }}</p>
            <p class="text-slate-600 text-sm font-medium mt-1">Approved</p>
        </a>
        <a href="{{ route('admin.stories.index') }}" class="block p-5 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition">
            <p class="text-3xl font-bold text-slate-800">{{ $totalStories }}</p>
            <p class="text-slate-600 text-sm font-medium mt-1">Total stories</p>
        </a>
        <a href="{{ route('admin.comments.index') }}" class="block p-5 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md hover:border-violet-300 transition">
            <p class="text-3xl font-bold text-violet-600">{{ $totalComments }}</p>
            <p class="text-slate-600 text-sm font-medium mt-1">Comments</p>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="p-5 bg-white rounded-xl border border-slate-200 shadow-sm">
            <p class="text-2xl font-bold text-slate-800">{{ $totalUsers }}</p>
            <p class="text-slate-600 text-sm">Active users</p>
        </div>
        <div class="p-5 bg-white rounded-xl border border-slate-200 shadow-sm">
            <p class="text-2xl font-bold text-red-600">{{ $blockedUsers }}</p>
            <p class="text-slate-600 text-sm">Blocked users</p>
        </div>
    </div>

    <div class="mt-8 flex gap-3">
        <a href="{{ route('admin.stories.index') }}" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-700">Manage stories</a>
        <a href="{{ route('admin.comments.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200">Moderate comments</a>
    </div>
</div>
@endsection
