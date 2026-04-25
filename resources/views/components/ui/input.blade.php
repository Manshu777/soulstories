@props([
    'type' => 'text',
])

<input type="{{ $type }}" {{ $attributes->merge([
    'class' => 'w-full rounded-xl border border-[#E5E7EB] bg-white px-3.5 py-2.5 text-sm text-[#111827] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#6366F1]/20 focus:border-[#6366F1]'
]) }}>
