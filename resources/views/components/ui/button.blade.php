@props([
    'variant' => 'primary',
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold transition disabled:cursor-not-allowed disabled:bg-gray-200 disabled:text-gray-500 disabled:border-gray-200';
    $variants = [
        'primary' => 'bg-[#6366F1] text-white hover:bg-[#4F46E5] border border-[#6366F1] shadow-sm',
        'outline' => 'bg-white text-[#6366F1] border border-[#6366F1] hover:bg-[#EEF2FF]',
        'ghost' => 'bg-transparent text-[#6366F1] border border-transparent hover:bg-[#EEF2FF]',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $base.' '.($variants[$variant] ?? $variants['primary'])]) }}>
    {{ $slot }}
</button>
