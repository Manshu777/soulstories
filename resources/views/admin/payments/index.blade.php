@extends('admin.layouts.app')
@section('title', 'Payment Management')
@section('content')
<a href="{{ route('admin.payments.export') }}" class="inline-flex mb-4 rounded-lg border border-[#E5E7EB] bg-white px-3 py-2 text-sm hover:bg-[#F3F4F6]">Export CSV</a>
<div class="grid lg:grid-cols-2 gap-4">
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-4">
        <p class="font-semibold mb-3">Service Payments</p>
        @foreach($servicePayments as $payment)
            <div class="text-sm py-2 border-t first:border-0">
                <p>Payment #{{ $payment->payment_id }}</p>
                <p class="text-xs text-[#6B7280]">Amount: {{ $payment->amount }} | Status: {{ $payment->status }}</p>
            </div>
        @endforeach
        <div class="mt-3">{{ $servicePayments->links() }}</div>
    </div>
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-4">
        <p class="font-semibold mb-3">Promotion Payments</p>
        @foreach($promotionPayments as $payment)
            <div class="text-sm py-2 border-t first:border-0">
                <p>Payment #{{ $payment->payment_id }}</p>
                <p class="text-xs text-[#6B7280]">Amount: {{ $payment->amount }} | Status: {{ $payment->status }}</p>
            </div>
        @endforeach
        <div class="mt-3">{{ $promotionPayments->links() }}</div>
    </div>
</div>
@endsection
