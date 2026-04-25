@extends('admin.layouts.app')
@section('title', 'Email & Notifications')
@section('content')
<div class="grid lg:grid-cols-2 gap-4">
    <form action="{{ route('admin.notifications.send') }}" method="POST" class="rounded-xl border border-[#E5E7EB] bg-white p-4 space-y-3">
        @csrf
        <p class="font-semibold">Send Broadcast</p>
        <select name="audience" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
            <option value="all">All Users</option>
            <option value="writers">All Writers</option>
            <option value="selected">Specific Users</option>
        </select>
        <input name="subject" placeholder="Subject" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
        <textarea name="body" rows="6" placeholder="HTML allowed" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm"></textarea>
        <select name="user_ids[]" multiple class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
        <button class="rounded-lg bg-[#6366F1] text-white px-4 py-2 text-sm">Queue Broadcast</button>
    </form>

    <form action="{{ route('admin.notifications.templates.store') }}" method="POST" class="rounded-xl border border-[#E5E7EB] bg-white p-4 space-y-3">
        @csrf
        <p class="font-semibold">Create Template</p>
        <input name="name" placeholder="Template name" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
        <input name="slug" placeholder="template-slug" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
        <input name="subject" placeholder="Subject" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
        <textarea name="body" rows="5" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm"></textarea>
        <button class="rounded-lg bg-[#6366F1] text-white px-4 py-2 text-sm">Save Template</button>
    </form>
</div>

<div class="mt-4 rounded-xl border border-[#E5E7EB] bg-white p-4">
    <p class="font-semibold mb-3">Templates</p>
    @foreach($templates as $template)
        <div class="py-2 border-t first:border-0">
            <p class="text-sm font-medium">{{ $template->name }} <span class="text-xs text-[#6B7280]">({{ $template->slug }})</span></p>
            <p class="text-xs text-[#6B7280]">{{ $template->subject }}</p>
        </div>
    @endforeach
    <div class="mt-3">{{ $templates->links() }}</div>
</div>
@endsection
