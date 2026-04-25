@php
    $items = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'icon' => 'dashboard'],
        ['label' => 'Users', 'route' => 'admin.users.index', 'match' => 'admin.users.*', 'icon' => 'users'],
        ['label' => 'Content', 'route' => 'admin.content.stories', 'match' => 'admin.content.*', 'icon' => 'content'],
        ['label' => 'Promotions', 'route' => 'admin.promotions.index', 'match' => 'admin.promotions.*', 'icon' => 'promotion'],
        ['label' => 'Payments', 'route' => 'admin.payments.index', 'match' => 'admin.payments.*', 'icon' => 'payments'],
        ['label' => 'Reports', 'route' => 'admin.reports.index', 'match' => 'admin.reports.*', 'icon' => 'reports'],
        ['label' => 'Notifications', 'route' => 'admin.notifications.index', 'match' => 'admin.notifications.*', 'icon' => 'notifications'],
        ['label' => 'Roles', 'route' => 'admin.roles.index', 'match' => 'admin.roles.*', 'icon' => 'roles'],
        ['label' => 'Read & Earn', 'route' => 'admin.read-earn.index', 'match' => 'admin.read-earn.*', 'icon' => 'earn'],
        ['label' => 'Settings', 'route' => 'admin.settings.index', 'match' => 'admin.settings.*', 'icon' => 'settings'],
    ];
@endphp

<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-[260px] -translate-x-full lg:translate-x-0 transform transition-all duration-300 bg-white border-r border-[#E5E7EB] overflow-hidden">
    <div class="h-full flex flex-col">
        <div class="h-16 border-b border-[#E5E7EB] px-5 flex items-center gap-3">
            <div class="h-8 w-8 rounded-lg bg-[#EEF2FF] flex items-center justify-center text-[#6366F1] font-bold">S</div>
            <p class="menu-brand text-sm font-semibold text-[#111827] whitespace-nowrap">Soul Diaries <span class="text-[#6366F1]">Admin</span></p>
        </div>

        <nav class="p-4 space-y-1.5 overflow-y-auto">
            @foreach($items as $item)
                @php($active = request()->routeIs($item['match']) || request()->routeIs($item['route']))
                <a href="{{ route($item['route']) }}"
                   class="group flex items-center gap-3 px-4 py-2.5 rounded-lg border-l-2 transition-all {{ $active ? 'bg-[#EEF2FF] text-[#4F46E5] border-[#6366F1] font-semibold' : 'text-gray-600 border-transparent hover:bg-[#EEF2FF] hover:text-[#4F46E5]' }}">
                    <span class="{{ $active ? 'text-[#6366F1]' : 'text-gray-400 group-hover:text-[#6366F1]' }}">
                        @switch($item['icon'])
                            @case('dashboard')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h8V3H3v9Zm10 9h8v-6h-8v6Zm0-10h8V3h-8v8Zm-10 10h8v-4H3v4Z"/></svg>
                                @break
                            @case('users')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 20a6 6 0 0 0-12 0m12 0h3m-3 0H6m6-9a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm6 9a5 5 0 0 0-3-4.58M3 20a5 5 0 0 1 3-4.58"/></svg>
                                @break
                            @case('content')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6.5A2.5 2.5 0 0 1 6.5 4H20v14.5a1.5 1.5 0 0 1-1.5 1.5H6.5A2.5 2.5 0 0 1 4 17.5v-11Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 8h8M8 12h8M8 16h5"/></svg>
                                @break
                            @case('promotion')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5h6m-3-3v6M4 13h7m-7 4h11M4 9h11"/></svg>
                                @break
                            @case('payments')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M2 10h20"/></svg>
                                @break
                            @case('reports')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6m4 6V7m4 10v-3M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z"/></svg>
                                @break
                            @case('notifications')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4a2 2 0 0 1-.6-1.4V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5m6 0a3 3 0 1 1-6 0h6Z"/></svg>
                                @break
                            @case('roles')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9Z"/></svg>
                                @break
                            @case('earn')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m4-14H9.5a2.5 2.5 0 0 0 0 5H14a2.5 2.5 0 0 1 0 5H8"/></svg>
                                @break
                            @default
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M4.93 4.93l14.14 14.14"/></svg>
                        @endswitch
                    </span>
                    <span class="menu-label whitespace-nowrap">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</aside>
