<nav class="sticky top-0 z-50 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-y-2 px-4 sm:px-6 py-3">

        <!-- LEFT SECTION -->
        <div class="flex items-center gap-4 sm:gap-8 min-w-0">

            <!-- Logo -->
            <a href="{{ route('diary.home') }}" class="text-xl sm:text-2xl font-bold shrink-0">
                Soul<span class="text-orange-500 italic">Diaries</span>
            </a>

            <!-- Browse Dropdown -->
            <div class="relative group hidden md:block">
                <button class="flex items-center text-gray-700 hover:text-black font-medium">
                    Browse
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-lg mt-3 w-48 py-2 border">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Romance</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Thriller</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Poetry</a>
                </div>
            </div>

            <!-- Community -->
            <a href="#" class="hidden md:block text-gray-700 hover:text-black font-medium">
                Community
            </a>
        </div>


        <!-- SEARCH CENTER -->
        <div class="hidden md:flex flex-1 max-w-lg mx-10">
            <input type="text"
                placeholder="Search stories"
                class="w-full bg-gray-100 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-orange-400">
        </div>


        <!-- RIGHT SECTION -->
        <div class="flex items-center gap-3 sm:gap-6 flex-1 sm:flex-initial justify-end min-w-0">

            <!-- Mobile menu toggle (same links as desktop; hidden md+) -->
            @auth
            <button type="button" id="mobileNavToggle" class="md:hidden p-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50" aria-label="Open menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            @endauth

            <!-- Write Dropdown -->
            <div class="relative group hidden md:block">
                <button class="flex items-center text-gray-700 hover:text-black font-medium">
                    Write
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
<div class="absolute -left-1/2 pt-5">
                <div class=" hidden group-hover:block bg-white shadow-lg rounded-lg  w-56 py-2 border">
                    <a href="{{ route('diary.stories.create') }}" class="block px-4 py-2 hover:bg-gray-100">Create New Story</a>
                    <a href="{{ route('diary.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">My Stories</a>
                    <a href="{{ route('diary.library.index') }}" class="block px-4 py-2 hover:bg-gray-100">Library</a>
                </div>
</div>


            </div>

            <!-- Premium Button -->
            <a href="#"
                class="hidden sm:inline-flex bg-purple-100 text-purple-700 px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-semibold hover:bg-purple-200 transition shrink-0">
                ✨ Try Premium
            </a>

            <!-- Auth Area -->
            <div class="flex items-center gap-2 sm:gap-4 text-sm">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-black whitespace-nowrap">Login</a>
                    <a href="{{ route('register') }}" class="text-gray-600 hover:text-black whitespace-nowrap">Register</a>
                @else
                    <a href="{{ route('diary.dashboard') }}" class="text-gray-600 hover:text-black hidden md:inline whitespace-nowrap">Dashboard</a>
                    <a href="{{ route('diary.stories.create') }}" class="text-gray-600 hover:text-black hidden md:inline whitespace-nowrap">Write</a>
                    <a href="{{ route('diary.library.index') }}" class="text-gray-600 hover:text-black hidden md:inline whitespace-nowrap">Library</a>
                    @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="text-amber-600 hover:text-amber-700 font-medium hidden md:inline whitespace-nowrap">Admin</a>
                    @endif
                    <div class="relative group hidden md:block">
                        <a href="{{ route('diary.authors.show', auth()->user()->username) }}" class="flex items-center gap-2">
                            @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="" class="w-9 h-9 rounded-full border">
                            @else
                            <span class="w-9 h-9 rounded-full border bg-slate-100 flex items-center justify-center text-slate-600 font-medium">{{ Str::upper(Str::limit(auth()->user()->name, 1)) }}</span>
                            @endif
                        </a>
                        <div class="absolute right-0 mt-1 hidden group-hover:block bg-white shadow-lg rounded-lg py-2 border min-w-[140px] z-50">
                            <a href="{{ route('diary.authors.show', auth()->user()->username) }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Profile</a>
                            <a href="{{ route('diary.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">My Stories</a>
                            <form action="{{ route('logout') }}" method="post" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-gray-700">Logout</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

        </div>
    </div>

    @auth
    <div id="mobileNavPanel" class="hidden md:hidden border-t border-gray-100 bg-white px-4 py-3 space-y-1 shadow-inner">
        <a href="{{ route('diary.dashboard') }}" class="block py-2.5 text-gray-700 font-medium border-b border-gray-50">Dashboard</a>
        <a href="{{ route('diary.stories.create') }}" class="block py-2.5 text-gray-700 font-medium border-b border-gray-50">Write · Create story</a>
        <a href="{{ route('diary.library.index') }}" class="block py-2.5 text-gray-700 font-medium border-b border-gray-50">Library</a>
        @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('admin.dashboard') }}" class="block py-2.5 text-amber-600 font-medium border-b border-gray-50">Admin</a>
        @endif
        <a href="{{ route('diary.authors.show', auth()->user()->username) }}" class="block py-2.5 text-gray-700">Profile</a>
        <form action="{{ route('logout') }}" method="post" class="pt-2">
            @csrf
            <button type="submit" class="w-full text-left py-2.5 text-gray-600">Logout</button>
        </form>
    </div>
    <script>
        document.getElementById('mobileNavToggle')?.addEventListener('click', function() {
            var p = document.getElementById('mobileNavPanel');
            if (!p) return;
            p.classList.toggle('hidden');
        });
    </script>
    @endauth
</nav>