<<<<<<< HEAD
@extends('layouts.app')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;1,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
@endpush

@section('content')
<style>
    .reader-shell {
        background:
            radial-gradient(ellipse 80% 50% at 50% -20%, rgba(167, 139, 250, 0.28), transparent),
            radial-gradient(ellipse 60% 40% at 100% 50%, rgba(196, 181, 253, 0.2), transparent),
            linear-gradient(180deg, #eef2ff 0%, #f8f7ff 50%, #f5f3ff 100%);
    }
</style>

<div class="reader-shell min-h-[calc(100vh-4rem)] pb-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <nav class="text-sm text-slate-500 mb-8">
            <a href="{{ route('diary.home') }}" class="hover:text-violet-700">Home</a>
            <span class="mx-2 text-slate-300">/</span>
            <span class="text-slate-700">{{ $story->title }}</span>
        </nav>

        @if(!$story->isPublic())
            <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-100 px-4 py-3 text-amber-900 text-sm">
                This story is {{ $story->visibility === 'draft' ? 'a draft' : 'pending approval' }}. Only you can see it.
            </div>
        @endif

        <article class="rounded-[2rem] bg-white/90 backdrop-blur-sm border border-white/80 shadow-xl shadow-violet-200/30 overflow-hidden">
            @if($story->cover_image)
                <img src="{{ $story->cover_image }}" alt="{{ $story->title }}" class="w-full aspect-[2/1] object-cover">
            @else
                <div class="w-full aspect-[2/1] bg-gradient-to-br from-violet-100 to-fuchsia-100 flex items-center justify-center text-6xl">📖</div>
            @endif

            <div class="px-6 sm:px-10 py-8 sm:py-10">
                <h1 class="text-3xl sm:text-4xl font-semibold text-slate-900 tracking-tight" style="font-family: 'Playfair Display', serif;">
                    {{ $story->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-3 mt-5 text-sm text-slate-500">
                    <a href="{{ route('diary.authors.show', $story->user->username) }}" class="inline-flex items-center gap-2 hover:text-violet-700 transition">
                        @if($story->user->avatar)
                            <img src="{{ $story->user->avatar }}" alt="" class="w-9 h-9 rounded-full object-cover ring-2 ring-violet-100">
                        @else
                            <span class="w-9 h-9 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 font-semibold text-sm">{{ Str::upper(Str::limit($story->user->name, 1)) }}</span>
                        @endif
                        <span class="text-slate-700 font-medium">{{ $story->user->name }}</span>
                    </a>
                    <span class="text-slate-300">·</span>
                    <span>{{ $story->read_time }} min read</span>
                    <span class="text-slate-300">·</span>
                    <span>{{ number_format($likesCount) }} likes</span>
                    <span class="text-slate-300">·</span>
                    <span>{{ number_format($story->story_reads_count) }} reads</span>
                </div>

                @if($story->tags && count($story->tags))
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach($story->tags as $tag)
                            <span class="text-xs px-3 py-1 rounded-full bg-violet-50 text-violet-800 border border-violet-100">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                @if($story->description)
                    <div class="mt-8 text-slate-600 leading-relaxed text-lg" style="font-family: 'Playfair Display', Georgia, serif;">
                        {{ $story->description }}
                    </div>
                @endif

                <div class="flex flex-wrap gap-2 mt-8">
                    @auth
                        <form action="{{ route('diary.authors.follow', $story->user) }}" method="post" class="inline">
                            @csrf
                            <button type="submit" class="text-sm px-5 py-2.5 rounded-full border border-violet-200 bg-white text-violet-900 hover:bg-violet-50 font-medium shadow-sm transition">Follow</button>
                        </form>
                        <form action="{{ route('diary.like.story', $story) }}" method="post" class="inline">
                            @csrf
                            @if(!$hasLiked)
                                <button type="submit" class="text-sm px-5 py-2.5 rounded-full bg-violet-100 text-violet-900 hover:bg-violet-200 font-medium transition">❤️ Like</button>
                            @else
                                <button type="submit" class="text-sm px-5 py-2.5 rounded-full bg-red-50 text-red-600 border border-red-100 font-medium">Liked</button>
                            @endif
                        </form>
                        @if($inLibrary)
                            <form action="{{ route('diary.library.destroy', $story) }}" method="post" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm px-5 py-2.5 rounded-full border border-slate-200 text-slate-700 hover:bg-slate-50">Remove from library</button>
                            </form>
                        @else
                            <form action="{{ route('diary.library.store', $story) }}" method="post" class="inline">
                                @csrf
                                <button type="submit" class="text-sm px-5 py-2.5 rounded-full border border-violet-200 text-violet-800 hover:bg-violet-50">Add to library</button>
                            </form>
                        @endif
                        <form action="{{ route('diary.report.store', $story) }}" method="post" class="inline">
                            @csrf
                            <button type="submit" class="text-sm px-4 py-2.5 text-slate-500 hover:text-red-600">Report</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm px-5 py-2.5 rounded-full border border-violet-200 text-violet-800 hover:bg-violet-50 font-medium">Log in to like & save</a>
                    @endauth
                    <button type="button" id="share-story-btn" class="text-sm px-4 py-2.5 text-slate-500 hover:text-violet-700">Share</button>
                </div>
            </div>
        </article>

        <section class="mt-10 rounded-[2rem] bg-white/90 backdrop-blur-sm border border-white/80 shadow-lg shadow-violet-200/20 p-6 sm:p-8">
            <h2 class="text-xl font-semibold text-slate-900 mb-6" style="font-family: 'Playfair Display', serif;">Chapters</h2>
            <ul class="space-y-1">
                @foreach($story->publishedChapters as $ch)
                    <li>
                        <a href="{{ route('diary.chapters.show', [$story, $ch]) }}" class="flex items-center justify-between gap-4 rounded-2xl py-3 px-4 hover:bg-violet-50/80 border border-transparent hover:border-violet-100 transition group">
                            <span class="font-medium text-slate-800 group-hover:text-violet-900">{{ $ch->chapter_title }}</span>
                            <span class="text-slate-400 text-sm shrink-0">{{ $ch->read_time ?? $ch->reading_time }} min</span>
                        </a>
                    </li>
                @endforeach
            </ul>
            @if($story->publishedChapters->isEmpty())
                <p class="text-slate-500 text-sm">No chapters published yet.</p>
            @endif
        </section>
    </div>
</div>

<script>
document.getElementById('share-story-btn')?.addEventListener('click', function () {
    const title = @json($story->title);
    if (navigator.share) {
        navigator.share({ title: title, url: window.location.href }).catch(function () {});
    } else {
        navigator.clipboard.writeText(window.location.href).then(function () {
            alert('Link copied to clipboard');
        }).catch(function () {});
    }
});
</script>
@endsection
=======
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
>>>>>>> 7042497cfc8ddf8557fa3ce1bb8f911121717f35
