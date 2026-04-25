@extends('admin.layouts.app')
@section('title', 'Role Management')
@section('content')
<div class="grid lg:grid-cols-2 gap-4">
    @foreach($roles as $role)
        <form method="POST" action="{{ route('admin.roles.permissions', $role) }}" class="rounded-xl border border-[#E5E7EB] bg-white p-4">
            @csrf @method('PATCH')
            <p class="font-semibold mb-3">{{ $role->name }}</p>
            <div class="grid sm:grid-cols-2 gap-2 text-sm">
                @foreach($permissions as $permission)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked($role->permissions->contains('name', $permission->name))>
                        <span>{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>
            <button class="mt-4 rounded-lg bg-[#6366F1] text-white px-4 py-2 text-sm">Save Permissions</button>
        </form>
    @endforeach
</div>
@endsection
