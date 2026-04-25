@extends('admin.layouts.app')
@section('title', 'Promotion Management')
@section('content')
<div class="grid lg:grid-cols-2 gap-4">
    <form action="{{ route('admin.promotions.packages.store') }}" method="POST" class="rounded-xl border border-[#E5E7EB] bg-white p-4 space-y-3">
        @csrf
        <p class="font-semibold">Create Promotion Package</p>
        <select name="type" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
            <option value="book">Book</option>
            <option value="brand">Brand</option>
        </select>
        <input name="title" placeholder="Title" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
        <input name="price" type="number" step="0.01" placeholder="Price" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
        <input name="duration_days" type="number" placeholder="Duration (days)" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm">
        <textarea name="features" placeholder="Comma separated features" rows="3" class="w-full rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm"></textarea>
        <button class="rounded-lg bg-[#6366F1] text-white px-4 py-2 text-sm">Save Package</button>
    </form>
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-4">
        <p class="font-semibold mb-3">Recent Promotions</p>
        @foreach($promotions as $promo)
            <div class="text-sm py-2 border-t first:border-0">
                <p>{{ $promo->user->name ?? 'User' }} - {{ $promo->package->title ?? 'Package' }}</p>
                <p class="text-xs text-[#6B7280]">Status: {{ $promo->status }} | {{ $promo->starts_at }} to {{ $promo->ends_at }}</p>
            </div>
        @endforeach
        <div class="mt-3">{{ $promotions->links() }}</div>
    </div>
</div>
@endsection
