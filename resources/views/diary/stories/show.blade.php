<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Artisan Reader | {{ $story->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,800;1,400&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --paper-bg: #fdfaf6;
            --ink-primary: #2c241e;
            --accent-gold: #b68b54;
            --accent-soft: #e9dbc9;
            --border-color: rgba(182, 139, 84, 0.15);
        }

        body {
            background-color: #f4eee4;
            color: var(--ink-primary);
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }

        /* Subtle Paper Texture */
        .reader-shell {
            background-image: 
                radial-gradient(at 0% 0%, rgba(255, 255, 255, 0.5) 0, transparent 50%),
                url("https://www.transparenttextures.com/patterns/natural-paper.png");
            min-height: 100vh;
        }

        /* Sticky Reading Progress */
        .progress-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            z-index: 1000;
            background: rgba(255,255,255,0.1);
        }
        #readingProgressBar {
            height: 100%;
            width: 0%;
            background: linear-gradient(to right, var(--accent-gold), #8b5a2b);
            transition: width 0.1s ease-out;
            box-shadow: 0 0 10px rgba(182, 139, 84, 0.4);
        }

        /* Manuscript Card */
        .manuscript-card {
            background: var(--paper-bg);
            border: 1px solid white;
            box-shadow: 
                0 10px 30px -10px rgba(0,0,0,0.05),
                0 1px 2px rgba(0,0,0,0.05);
            border-radius: 12px;
            position: relative;
        }

        /* The Literary Dropcap */
        .story-content p:first-of-type::first-letter {
            font-family: 'Playfair Display', serif;
            font-size: 5rem;
            float: left;
            line-height: 0.7;
            margin: 0.1em 0.1em 0 0;
            color: var(--accent-gold);
            font-weight: 800;
        }

        .story-content p {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.35rem;
            margin-bottom: 1.8rem;
            text-align: justify;
            hyphens: auto;
        }

        /* Elegant Sidebar List */
        .chapter-link {
            transition: all 0.3s ease;
            border-radius: 8px;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #5c4e40;
            text-decoration: none;
        }
        .chapter-link:hover, .chapter-link.active {
            background: var(--accent-soft);
            color: var(--ink-primary);
            transform: translateX(4px);
        }

        .poetic-quote {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            border-left: 3px solid var(--accent-gold);
            padding-left: 1.5rem;
            margin: 2rem 0;
            color: #6b5a4a;
        }

        .ornament {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            color: var(--accent-gold);
            margin: 3rem 0;
            opacity: 0.6;
        }

        /* Glassmorphism Buttons */
        .btn-artisan {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(8px);
            border: 1px solid var(--border-color);
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-artisan:hover {
            background: var(--paper-bg);
            border-color: var(--accent-gold);
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .story-content p {
                font-size: 1.2rem;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    @extends('layouts.app')

    @section('content')
    <div class="progress-container"><div id="readingProgressBar"></div></div>

    <div class="reader-shell pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            
            <nav class="flex items-center gap-2 text-xs uppercase tracking-widest text-stone-500 mb-10">
                <a href="{{ route('diary.home') }}" class="hover:text-amber-700 transition">Scriptorium</a>
                <span>/</span>
                <span class="text-stone-800 font-semibold">{{ $story->title }}</span>
            </nav>

            <div class="grid lg:grid-cols-12 gap-12">
                
                <aside class="lg:col-span-4 space-y-8">
                    <div class="rounded-xl overflow-hidden shadow-2xl transform -rotate-1 hover:rotate-0 transition-transform duration-500">
                        @if($story->cover_image)
                            <img src="/storage/{{ $story->cover_image }}" class="w-full h-auto object-cover">
                        @else
                            <div class="aspect-[3/4] bg-stone-200 flex items-center justify-center text-4xl">📖</div>
                        @endif
                    </div>

                    <div class="p-6 bg-white/50 backdrop-blur-sm rounded-xl border border-stone-200">
                        <h3 class="font-serif text-xl mb-6 border-b border-stone-200 pb-2">Table of Contents</h3>
                        <nav class="space-y-1">
                            @foreach($story->publishedChapters as $index => $ch)
                                <a href="?chapter={{ $ch->chapter_number }}" 
                                   class="chapter-link {{ request('chapter') == $ch->chapter_number ? 'active' : '' }}">
                                    <span class="w-8 opacity-40 font-serif italic">{{ sprintf('%02d', $index + 1) }}</span>
                                    <span class="flex-1 truncate">{{ $ch->chapter_title ?: 'Untitled Fragment' }}</span>
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </aside>

                <main class="lg:col-span-8">
                    <article class="manuscript-card p-8 sm:p-16">
                        <header class="mb-12">
                            <h1 class="text-5xl font-serif font-bold text-stone-900 mb-6 leading-tight">
                                {{ $story->title }}
                            </h1>
                            
                            <div class="flex flex-wrap items-center gap-6 text-sm text-stone-500 border-y border-stone-100 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-stone-100 border flex items-center justify-center font-serif">
                                        {{ substr($story->user->name, 0, 1) }}
                                    </div>
                                    <span class="italic">{{ $story->user->name }}</span>
                                </div>
                                <span>•</span>
                                <span>{{ $story->read_time }} min read</span>
                                <span>•</span>
                                <span>{{ number_format($story->story_reads_count) }} readings</span>
                            </div>
                        </header>

                        @if($story->description)
                            <div class="poetic-quote text-xl">
                                {{ $story->description }}
                            </div>
                        @endif

                        <div class="story-content">
                            @php
                                $firstChapter = $chapter;
                                $paragraphs = $firstChapter ? preg_split('/\n\s*\n/', $firstChapter->content) : [];
                            @endphp

                            @if($firstChapter)
                                @if($firstChapter->chapter_title)
                                    <h2 class="font-serif text-2xl text-stone-700 mb-8 italic">
                                        {{ $firstChapter->chapter_title }}
                                    </h2>
                                @endif

                                @foreach($paragraphs as $para)
                                    @if(trim($para))
                                        <p>{!! nl2br(e(trim($para))) !!}</p>
                                    @endif
                                @endforeach
                            @else
                                <div class="text-center py-20 opacity-50 italic">
                                    This page remains unwritten...
                                </div>
                            @endif
                        </div>

                        <div class="ornament">
                            <span>❦</span>
                            <div class="h-px w-20 bg-stone-200"></div>
                            <span>❦</span>
                        </div>

                        <footer class="flex flex-wrap gap-3 mt-10">
                            @auth
                                <form action="{{ route('diary.like.story', $story) }}" method="post">
                                    @csrf
                                    <button class="btn-artisan {{ $hasLiked ? 'text-rose-600 border-rose-200 bg-rose-50' : '' }}">
                                        {{ $hasLiked ? '❤️ Liked' : '🤍 Like' }}
                                    </button>
                                </form>
                                <button class="btn-artisan" id="share-story-btn">
                                    🔗 Share
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn-artisan">🔐 Log in to interact</a>
                            @endauth
                        </footer>
                    </article>

                    <div class="mt-8 p-8 rounded-xl bg-stone-900 text-stone-100 flex items-center gap-6">
                        <div class="hidden sm:block">
                            <div class="w-20 h-20 rounded-full border-2 border-stone-700 overflow-hidden">
                                <img src="{{ $story->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($story->user->name) }}" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-serif text-lg">About the Author</h4>
                            <p class="text-stone-400 text-sm mt-1">Dedicated to the craft of storytelling. Follow for more chronicles.</p>
                            <a href="#" class="text-amber-400 text-xs uppercase tracking-widest mt-3 inline-block hover:text-white transition">View Profile</a>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        // Smooth Reading Progress
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            document.getElementById("readingProgressBar").style.width = scrolled + "%";
        });

        // Native Share API
        document.getElementById('share-story-btn')?.addEventListener('click', async () => {
            try {
                await navigator.share({
                    title: '{{ $story->title }}',
                    text: '{{ Str::limit($story->description, 100) }}',
                    url: window.location.href,
                });
            } catch (err) {
                navigator.clipboard.writeText(window.location.href);
                alert('Link copied to parchment!');
            }
        });
    </script>
    @endsection
</body>
</html>
