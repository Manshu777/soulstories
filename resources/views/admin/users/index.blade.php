@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
<form class="grid md:grid-cols-3 gap-3 mb-5">
    <input name="q" value="{{ request('q') }}" placeholder="Search by name, username, email..." class="rounded-xl border border-[#E2E8F0] px-3 py-2.5 text-sm bg-white">
    <select name="status" class="rounded-xl border border-[#E2E8F0] px-3 py-2.5 text-sm bg-white">
        <option value="">All status</option>
        @foreach(['active','inactive','blocked'] as $status)
            <option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    <button class="rounded-xl bg-[#6366F1] text-white px-4 py-2.5 text-sm font-semibold hover:bg-[#4F46E5]">Filter</button>
</form>

<div class="rounded-2xl border border-[#E5E7EB] bg-white shadow-[0_12px_30px_-20px_rgba(15,23,42,0.35)] overflow-auto">
    <table class="w-full text-sm">
        <thead class="bg-[#F8FAFC] text-left text-[#6B7280]">
        <tr>
            <th class="p-4 font-semibold">User</th>
            <th class="p-4 font-semibold">Status</th>
            <th class="p-4 font-semibold">Role</th>
            <th class="p-4 font-semibold">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr class="border-t border-[#F1F5F9]">
                <td class="p-4">
                    <p class="font-semibold text-[#0F172A]">{{ $user->name }}</p>
                    <div class="text-xs text-[#64748B]">{{ $user->email }}</div>
                </td>
                <td class="p-4">
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->status === 'active' ? 'bg-emerald-100 text-emerald-700' : ($user->status === 'blocked' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </td>
                <td class="p-4 text-[#334155]">{{ $user->roles->pluck('name')->join(', ') ?: '-' }}</td>
                <td class="p-4">
                    <div class="flex flex-wrap gap-2">
                        <form action="{{ route('admin.users.role', $user) }}" method="POST" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="role" class="rounded-lg border border-[#E2E8F0] px-2.5 py-1.5 text-xs">
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" @selected($user->roles->pluck('name')->contains($role))>{{ $role }}</option>
                                @endforeach
                            </select>
                            <button class="px-3 py-1.5 border border-[#E2E8F0] rounded-lg text-xs font-semibold hover:bg-[#F8FAFC]">Set Role</button>
                        </form>
                        <form action="{{ route('admin.users.status', $user) }}" method="POST" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="status" class="rounded-lg border border-[#E2E8F0] px-2.5 py-1.5 text-xs">
                                @foreach(['active','inactive','blocked'] as $status)
                                    <option value="{{ $status }}" @selected($user->status===$status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <button class="px-3 py-1.5 border border-[#E2E8F0] rounded-lg text-xs font-semibold hover:bg-[#F8FAFC]">Update</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="mt-5">{{ $users->links() }}</div>
@endsection
