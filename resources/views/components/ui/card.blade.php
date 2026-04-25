@props([])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-[#E5E7EB] bg-white shadow-[0_8px_24px_-16px_rgba(17,24,39,0.25)]']) }}>
    {{ $slot }}
</div>
