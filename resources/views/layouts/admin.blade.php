<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') – Soul Diaries</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 antialiased">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 min-h-screen bg-slate-800 text-white flex-shrink-0 flex flex-col">
            <div class="p-5 border-b border-slate-700">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold tracking-tight">Soul<span class="text-orange-400">Diaries</span></a>
                <span class="block text-xs text-slate-400 mt-0.5 uppercase tracking-wider">Admin panel</span>
            </div>
            <nav class="flex-1 p-3 space-y-0.5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                    <span class="text-lg">📊</span> Dashboard
                </a>
                <a href="{{ route('admin.stories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.stories*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                    <span class="text-lg">📚</span> Stories
                </a>
                <a href="{{ route('admin.comments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.comments*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                    <span class="text-lg">💬</span> Comments
                </a>
            </nav>
            <div class="p-4 border-t border-slate-700">
                <p class="text-slate-400 text-xs truncate" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</p>
                <form action="{{ route('admin.logout') }}" method="post" class="mt-2">
                    @csrf
                    <button type="submit" class="text-sm text-orange-400 hover:text-orange-300 font-medium">Sign out</button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <main class="flex-1 overflow-auto">
            <div class="p-6 lg:p-8">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm font-medium">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm font-medium">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
