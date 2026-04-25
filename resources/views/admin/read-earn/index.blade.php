@extends('admin.layouts.app')
@section('title', 'Read & Earn Control')
@section('content')
<div class="grid lg:grid-cols-2 gap-4">
    <form action="{{ route('admin.read-earn.tasks.store') }}" method="POST" class="rounded-xl border border-[#E5E7EB] bg-white p-4 space-y-3">
        @csrf
        <p class="font-semibold">Create Task</p>
        <input name="title" placeholder="Task title" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
        <textarea name="description" rows="3" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm"></textarea>
        <input name="reward_amount" type="number" step="0.01" placeholder="Reward" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
        <select name="platform" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
            <option value="instagram">Instagram</option>
            <option value="youtube">YouTube</option>
            <option value="review">Review</option>
        </select>
        <button class="rounded-lg bg-[#6366F1] text-white px-4 py-2 text-sm">Create Task</button>
    </form>

    <div class="rounded-xl border border-[#E5E7EB] bg-white p-4">
        <p class="font-semibold mb-3">Submissions</p>
        @foreach($submissions as $submission)
            <div class="border-t first:border-0 py-2">
                <p class="text-sm">{{ $submission->order->user->name ?? '-' }} - {{ $submission->task->title ?? '-' }}</p>
                <form action="{{ route('admin.read-earn.submissions.update', $submission) }}" method="POST" class="flex gap-2 mt-1">@csrf @method('PATCH')
                    <select name="status" class="border rounded px-2 py-1 text-sm">
                        @foreach(['pending','approved','rejected'] as $status)
                            <option value="{{ $status }}" @selected($submission->status===$status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    <button class="px-2 py-1 border rounded text-sm">Update</button>
                </form>
            </div>
        @endforeach
        <div class="mt-3">{{ $submissions->links() }}</div>
    </div>
</div>
@endsection
