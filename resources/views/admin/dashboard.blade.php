@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $statCards = [
        ['label' => 'Total Users', 'value' => number_format($metrics['total_users']), 'icon' => 'users'],
        ['label' => 'Active Users', 'value' => number_format($metrics['active_users']), 'icon' => 'active'],
        ['label' => 'Total Stories', 'value' => number_format($metrics['total_stories']), 'icon' => 'story'],
        ['label' => 'Total Reads', 'value' => number_format($metrics['total_reads']), 'icon' => 'reads'],
        // ['label' => 'Revenue', 'value' => '₹'.number_format($metrics['revenue'], 2), 'icon' => 'revenue'],
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($statCards as $card)
        <div class="rounded-xl border border-[#E5E7EB] bg-white p-5 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <p class="text-sm font-medium text-gray-600">{{ $card['label'] }}</p>
                <span class="inline-flex h-9 w-9 rounded-lg bg-[#EEF2FF] items-center justify-center text-[#6366F1]">
                    @if($card['icon'] === 'users')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 20a6 6 0 0 0-12 0m12 0h3m-3 0H6m6-9a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg>
                    @elseif($card['icon'] === 'active')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    @elseif($card['icon'] === 'story')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6.5A2.5 2.5 0 0 1 6.5 4H20v14.5a1.5 1.5 0 0 1-1.5 1.5H6.5A2.5 2.5 0 0 1 4 17.5v-11Z"/></svg>
                    @elseif($card['icon'] === 'reads')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/><circle cx="12" cy="12" r="3"/></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m4-14H9.5a2.5 2.5 0 0 0 0 5H14a2.5 2.5 0 0 1 0 5H8"/></svg>
                    @endif
                </span>
            </div>
            <p class="mt-3 text-3xl font-bold tracking-tight text-gray-900">{{ $card['value'] }}</p>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6">
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-5 shadow-sm">
        <div class="flex items-end justify-between">
            <div>
                <p class="text-lg font-semibold text-gray-900">Top Authors</p>
                <p class="text-xs text-gray-500 mt-1">Best performing writers</p>
            </div>
        </div>
        <div class="my-4 border-t border-[#E5E7EB]"></div>
        @foreach($metrics['top_authors'] as $author)
            <div class="text-sm py-2 px-2 rounded-lg flex items-center justify-between hover:bg-[#F8FAFC] transition-all">
                <span class="text-gray-700 font-medium">{{ $author->name }}</span>
                <span class="text-gray-500 text-xs">{{ $author->stories_count }} stories</span>
            </div>
        @endforeach
    </div>
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-5 shadow-sm">
        <p class="text-lg font-semibold text-gray-900">Top Stories</p>
        <p class="text-xs text-gray-500 mt-1">Most read content this cycle</p>
        <div class="my-4 border-t border-[#E5E7EB]"></div>
        @foreach($metrics['top_stories'] as $story)
            <div class="text-sm py-2 px-2 rounded-lg flex items-center justify-between hover:bg-[#F8FAFC] transition-all">
                <span class="truncate text-gray-700 font-medium">{{ $story->title }}</span>
                <span class="text-gray-500 text-xs whitespace-nowrap ml-3">{{ $story->story_reads_count }} reads</span>
            </div>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6">
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-5 shadow-sm">
        <p class="text-lg font-semibold text-gray-900">Daily Registrations</p>
        <p class="text-xs text-gray-500 mt-1">Past 14 days</p>
        <div class="mt-4 h-72">
            <canvas id="registrationChart"></canvas>
        </div>
    </div>
    <div class="rounded-xl border border-[#E5E7EB] bg-white p-5 shadow-sm">
        <p class="text-lg font-semibold text-gray-900">Engagement</p>
        <p class="text-xs text-gray-500 mt-1">Reads vs likes (past 14 days)</p>
        <div class="mt-4 h-72">
            <canvas id="engagementChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const registrationLabels = @json(collect($metrics['daily_registrations'])->pluck('metric_day')->values());
        const registrationValues = @json(collect($metrics['daily_registrations'])->pluck('total_count')->values());
        const engagementLabels = @json(collect($metrics['daily_engagement'])->pluck('day')->values());
        const engagementReads = @json(collect($metrics['daily_engagement'])->pluck('reads')->values());
        const engagementLikes = @json(collect($metrics['daily_engagement'])->pluck('likes')->values());

        new Chart(document.getElementById('registrationChart'), {
            type: 'line',
            data: {
                labels: registrationLabels,
                datasets: [{
                    label: 'Registrations',
                    data: registrationValues,
                    borderColor: '#6366F1',
                    backgroundColor: 'rgba(99,102,241,0.12)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: '#F3F4F6' }, ticks: { color: '#6B7280' } },
                    y: { grid: { color: '#F3F4F6' }, ticks: { color: '#6B7280' } }
                }
            }
        });

        new Chart(document.getElementById('engagementChart'), {
            type: 'bar',
            data: {
                labels: engagementLabels,
                datasets: [
                    { label: 'Reads', data: engagementReads, backgroundColor: '#6366F1', borderRadius: 6, maxBarThickness: 22 },
                    { label: 'Likes', data: engagementLikes, backgroundColor: '#C7D2FE', borderRadius: 6, maxBarThickness: 22 }
                ]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#6B7280' } }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#6B7280' } },
                    y: { grid: { color: '#F3F4F6' }, ticks: { color: '#6B7280' } }
                }
            }
        });
    </script>
@endpush
