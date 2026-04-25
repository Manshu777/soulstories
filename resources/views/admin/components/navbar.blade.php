@php($adminUser = auth('admin')->user() ?? auth()->user())
<header class="sticky top-0 z-30 h-16 bg-white border-b border-[#E5E7EB] flex items-center justify-between px-4 sm:px-6">
    <div class="flex items-center gap-3">
        <button id="admin-sidebar-toggle" type="button" class="lg:hidden inline-flex items-center justify-center h-9 w-9 rounded-lg border border-[#E5E7EB] text-gray-600 hover:text-[#4F46E5] hover:border-[#C7D2FE] transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <button id="admin-sidebar-collapse" type="button" class="hidden lg:inline-flex items-center justify-center h-9 w-9 rounded-lg border border-[#E5E7EB] text-gray-600 hover:text-[#4F46E5] hover:border-[#C7D2FE] transition-all" title="Collapse sidebar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m15 19-7-7 7-7"/></svg>
        </button>
        <div>
            <p class="text-xs uppercase tracking-[0.14em] text-gray-500">Admin Workspace</p>
            <h1 class="text-lg font-semibold text-[#111827]">@yield('title', 'Dashboard')</h1>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="button" class="relative inline-flex items-center justify-center h-10 w-10 rounded-lg border border-[#E5E7EB] text-gray-600 hover:text-[#4F46E5] hover:border-[#C7D2FE] transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4a2 2 0 0 1-.6-1.4V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5m6 0a3 3 0 1 1-6 0h6Z"/></svg>
        </button>

        <div class="hidden sm:flex items-center gap-2 pl-1">
            <div class="h-9 w-9 rounded-full bg-[#EEF2FF] text-[#6366F1] flex items-center justify-center text-sm font-semibold">
                {{ strtoupper(substr($adminUser->name ?? 'A', 0, 1)) }}
            </div>
            <div class="leading-tight">
                <p class="text-sm font-semibold text-[#111827]">{{ $adminUser->name ?? 'Admin User' }}</p>
                <p class="text-xs text-gray-500">Administrator</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="inline-flex items-center rounded-lg border border-[#E5E7EB] px-3 py-2 text-sm font-medium text-[#4F46E5] hover:bg-[#EEF2FF] transition-all">Logout</button>
        </form>
    </div>
</header>
