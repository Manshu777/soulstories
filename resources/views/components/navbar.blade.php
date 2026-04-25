<nav class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3">
        <div class="flex items-center gap-3 md:gap-6">
            <button type="button" id="mobileNavToggle" class="md:hidden p-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50" aria-label="Open menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <a href="{{ route('diary.home') }}" class="text-xl sm:text-2xl font-bold shrink-0 text-slate-900">
                Soul<span class="text-[#6366F1] italic">Diaries</span>
            </a>

            <div class="relative hidden md:block group" id="browseMenuDesktop">
                <button type="button" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-700 hover:text-slate-900">
                    Browse
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div class="absolute left-0 mt-3 w-[560px] hidden group-hover:block rounded-2xl border border-slate-200 bg-white shadow-xl p-4">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Top Categories</p>
                            <div class="grid grid-cols-2 gap-2">
                                @forelse($navbarCategories ?? [] as $cat)
                                    <a href="{{ route('diary.category.show', $cat->slug) }}" class="text-sm px-3 py-2 rounded-lg bg-slate-50 text-slate-700 hover:bg-[#EEF2FF] hover:text-[#6366F1] transition">
                                        {{ $cat->name }}
                                    </a>
                                @empty
                                    <p class="text-sm text-slate-400 col-span-2">No categories yet.</p>
                                @endforelse
                            </div>
                            <a href="{{ route('diary.categories.index') }}" class="inline-flex mt-3 text-xs font-semibold text-[#6366F1] hover:text-[#4F46E5]">View all categories →</a>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Trending Stories</p>
                            <div class="space-y-2">
                                @forelse($navbarTrendingStories ?? [] as $trend)
                                    <a href="{{ route('diary.stories.show', $trend->slug) }}" class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-50 transition">
                                        @if($trend->cover_image)
                                            <img src="{{ $trend->cover_image }}" alt="" class="w-8 h-10 rounded object-cover border border-slate-100">
                                        @else
                                            <div class="w-8 h-10 rounded bg-slate-100 flex items-center justify-center text-xs">📖</div>
                                        @endif
                                        <span class="text-sm text-slate-700 truncate">{{ $trend->title }}</span>
                                    </a>
                                @empty
                                    <p class="text-sm text-slate-400">No trending stories yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex flex-1 max-w-xl relative">
                <input id="globalSearchInputDesktop" type="text" autocomplete="off"
                    value="{{ request('q', '') }}"
                    placeholder="Search stories, authors, blogs..."
                    class="w-full bg-slate-100 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#6366F1]/20">
                <div id="globalSearchDropdownDesktop" class="hidden absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-xl z-50 max-h-[70vh] overflow-auto"></div>
            </div>

            <div class="ml-auto flex items-center gap-2 sm:gap-4 text-sm">
                @guest
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-slate-900">Login</a>
                    <a href="{{ route('register') }}" class="text-slate-600 hover:text-slate-900">Register</a>
                @else
                    <a href="{{ route('diary.stories.create') }}" class="hidden md:inline text-slate-600 hover:text-slate-900">Write</a>
                    <a href="{{ route('diary.library.index') }}" class="hidden md:inline text-slate-600 hover:text-slate-900">Library</a>
                    <div class="relative group">
                        <a href="{{ route('diary.authors.show', auth()->user()->username) }}" class="flex items-center gap-2">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="" class="w-9 h-9 rounded-full border border-slate-200 object-cover">
                            @else
                                <span class="w-9 h-9 rounded-full border border-slate-200 bg-slate-100 flex items-center justify-center text-slate-600 font-medium">{{ Str::upper(Str::limit(auth()->user()->name, 1)) }}</span>
                            @endif
                        </a>
                        <div class="absolute right-0 mt-2 hidden group-hover:block bg-white shadow-lg rounded-lg py-2 border border-slate-200 min-w-[160px] z-50">
                            <a href="{{ route('diary.authors.show', auth()->user()->username) }}" class="block px-4 py-2 hover:bg-slate-50 text-slate-700">Profile</a>
                            <a href="{{ route('diary.dashboard') }}" class="block px-4 py-2 hover:bg-slate-50 text-slate-700">Dashboard</a>
                            <a href="{{ route('diary.settings') }}" class="block px-4 py-2 hover:bg-slate-50 text-slate-700">Settings</a>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-slate-50 text-slate-700">Logout</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>

        <div class="md:hidden mt-3 relative">
            <input id="globalSearchInputMobile" type="text" autocomplete="off"
                value="{{ request('q', '') }}"
                placeholder="Search stories, authors, blogs..."
                class="w-full bg-slate-100 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#6366F1]/20">
            <div id="globalSearchDropdownMobile" class="hidden absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-xl z-50 max-h-[70vh] overflow-auto"></div>
        </div>
    </div>

    <div id="mobileNavPanel" class="md:hidden hidden border-t border-slate-100 bg-white px-4 py-3">
        <div class="mb-3">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Browse Categories</p>
            <div class="grid grid-cols-2 gap-2">
                @foreach(($navbarCategories ?? collect())->take(8) as $cat)
                    <a href="{{ route('diary.category.show', $cat->slug) }}" class="text-sm px-3 py-2 rounded-lg bg-slate-50 text-slate-700">{{ $cat->name }}</a>
                @endforeach
            </div>
            <a href="{{ route('diary.categories.index') }}" class="inline-flex mt-2 text-xs font-semibold text-[#6366F1]">View all categories →</a>
        </div>

        @auth
            <a href="{{ route('diary.dashboard') }}" class="block py-2 text-slate-700 border-t border-slate-100">Dashboard</a>
            <a href="{{ route('diary.stories.create') }}" class="block py-2 text-slate-700">Write</a>
            <a href="{{ route('diary.library.index') }}" class="block py-2 text-slate-700">Library</a>
            <a href="{{ route('diary.settings') }}" class="block py-2 text-slate-700">Settings</a>
        @endauth
    </div>

    <script>
        document.getElementById('mobileNavToggle')?.addEventListener('click', function () {
            document.getElementById('mobileNavPanel')?.classList.toggle('hidden');
        });

        (function () {
            const endpoint = '/api/search';
            function debounce(fn, delay = 300) { let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), delay); }; }
            function escapeHtml(str) { return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;'); }
            function highlight(text, q) { const safe = escapeHtml(text); if (!q) return safe; const regex = new RegExp(`(${q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'ig'); return safe.replace(regex, '<mark class="bg-[#EEF2FF] text-[#4F46E5] rounded px-0.5">$1</mark>'); }
            function rowTemplate(item, q, fallbackEmoji = '📖') {
                const thumb = item.thumbnail ? `<img src="${escapeHtml(item.thumbnail)}" class="w-8 h-10 rounded object-cover border border-slate-100" alt="">` : `<div class="w-8 h-10 rounded bg-slate-100 flex items-center justify-center text-xs">${fallbackEmoji}</div>`;
                return `<a href="${escapeHtml(item.url || '#')}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-50">${thumb}<div class="min-w-0"><p class="text-sm text-slate-800 truncate">${highlight(item.title || item.name || '', q)}</p><p class="text-xs text-slate-500 uppercase tracking-wide">${escapeHtml(item.type || '')}</p></div></a>`;
            }
            function bindSearch(inputId, dropdownId) {
                const input = document.getElementById(inputId); const dropdown = document.getElementById(dropdownId); if (!input || !dropdown) return;
                const render = (payload, q) => {
                    const stories = payload.stories || []; const authors = payload.authors || []; const blogs = payload.blogs || []; const tags = payload.tags || [];
                    if (!stories.length && !authors.length && !blogs.length && !tags.length) { dropdown.innerHTML = `<div class="px-4 py-3 text-sm text-slate-500">No results found</div>`; dropdown.classList.remove('hidden'); return; }
                    const section = (label, items, emoji) => items.length ? `<div class="py-1"><p class="px-3 pt-2 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">${label}</p>${items.map(item => rowTemplate(item, q, emoji)).join('')}</div>` : '';
                    const tagsHtml = tags.length ? `<div class="py-2 border-t border-slate-100 px-3"><p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Tags / Genres</p><div class="flex flex-wrap gap-1.5">${tags.map(tag => `<a href="/search?q=${encodeURIComponent(tag)}&content_type=stories" class="text-xs px-2 py-1 rounded-full bg-[#EEF2FF] text-[#6366F1] hover:bg-[#E0E7FF]">#${highlight(tag, q)}</a>`).join('')}</div></div>` : '';
                    dropdown.innerHTML = `${section('Stories', stories, '📖')}${section('Authors', authors, '👤')}${section('Blogs', blogs, '📝')}${tagsHtml}<div class="border-t border-slate-100 px-3 py-2 bg-slate-50"><a href="/search?q=${encodeURIComponent(q)}" class="text-sm font-medium text-[#6366F1] hover:text-[#4F46E5]">View all results for "${escapeHtml(q)}"</a></div>`;
                    dropdown.classList.remove('hidden');
                };
                const fetchSuggest = debounce(async (q) => {
                    if (!q || q.length < 1) { dropdown.classList.add('hidden'); dropdown.innerHTML = ''; return; }
                    try { const res = await fetch(`${endpoint}?q=${encodeURIComponent(q)}&suggest=1`, { headers: { 'Accept': 'application/json' } }); const data = await res.json(); render(data, q); }
                    catch (e) { dropdown.innerHTML = `<div class="px-4 py-3 text-sm text-[#6366F1]">Search unavailable</div>`; dropdown.classList.remove('hidden'); }
                }, 300);
                input.addEventListener('input', (e) => fetchSuggest(e.target.value.trim()));
                input.addEventListener('keydown', (e) => { if (e.key === 'Enter') { e.preventDefault(); const q = input.value.trim(); if (q) window.location.href = `/search?q=${encodeURIComponent(q)}`; } });
                document.addEventListener('click', (e) => { if (!dropdown.contains(e.target) && e.target !== input) dropdown.classList.add('hidden'); });
                input.addEventListener('focus', () => { if (dropdown.innerHTML.trim()) dropdown.classList.remove('hidden'); });
            }
            bindSearch('globalSearchInputDesktop', 'globalSearchDropdownDesktop');
            bindSearch('globalSearchInputMobile', 'globalSearchDropdownMobile');
        })();
    </script>
</nav>
