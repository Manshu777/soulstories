@extends('layouts.app')

@php
    // Dummy Data for the UI
    $poems = [
        ['title' => 'Neon Solitude', 'excerpt' => 'The city hums a frequency only the lonely can hear, a vibration of glass...', 'author' => 'Aria Thorne'],
        ['title' => 'Paper Boats', 'excerpt' => 'We folded our regrets into small white triangles and let the rain carry them...', 'author' => 'Julian Voss'],
        ['title' => 'The Sun Thief', 'excerpt' => 'He kept the golden hours in a mason jar beneath his floorboards...', 'author' => 'Elara Vance'],
        ['title' => 'Quiet Wars', 'excerpt' => 'The loudest battles are fought over morning coffee and cold bedsheets...', 'author' => 'Sia K.']
    ];

    $earnTasks = [
        ['title' => 'The Silk Road Review', 'reward' => '$4.50', 'time' => '15 min read'],
        ['title' => 'Beta Read: Lunar Echoes', 'reward' => '$12.00', 'time' => 'Series Task'],
        ['title' => 'Share & Promote: Zen Poetry', 'reward' => '$2.00', 'time' => 'Social Task']
    ];
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-8">
    
    <section class="py-20 flex flex-col md:flex-row gap-16 items-center">
        <div class="md:w-3/5 space-y-8">
            <div class="inline-flex items-center space-x-3 bg-orange-50 px-4 py-1.5 rounded-full border border-orange-100">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                </span>
                <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-orange-700 font-sans">Live Reading Now</span>
            </div>
            <h1 class="serif text-6xl md:text-8xl leading-[1.1] text-slate-900">
                Stories for the <br><span class="italic text-orange-500 font-light">Quiet Hours.</span>
            </h1>
            <p class="text-xl text-slate-500 font-light max-w-lg leading-relaxed">
                A sanctuary for writers to bleed ink and readers to find home. Join the world's most elegant digital diary collective.
            </p>
            <div class="flex items-center space-x-8 pt-4">
                <a href="#" class="group flex items-center space-x-4">
                    <span class="w-14 h-14 flex items-center justify-center bg-slate-900 text-white rounded-full group-hover:bg-orange-600 transition-all duration-500 shadow-xl shadow-slate-200">
                        →
                    </span>
                    <span class="font-medium tracking-wide text-slate-800">Explore the Archives</span>
                </a>
            </div>
        </div>
        
        <div class="md:w-2/5 relative">
            <div class="aspect-[4/5] bg-slate-100 rounded-[3rem] overflow-hidden rotate-2 hover:rotate-0 transition-transform duration-1000 shadow-2xl">
                <img src="https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-700">
            </div>
            <div class="absolute -bottom-10 -left-10 bg-white/90 backdrop-blur-lg p-6 rounded-2xl border border-orange-100 shadow-xl max-w-[220px]">
                <p class="serif italic text-sm text-slate-600">"Every story is a ghost of the person who wrote it."</p>
                <div class="flex items-center mt-4 space-x-2">
                    <div class="w-4 h-[1px] bg-orange-500"></div>
                    <p class="text-[10px] uppercase tracking-widest text-orange-500 font-bold">M. Nightfall</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 border-t border-orange-50">
        <div class="flex flex-col md:flex-row justify-between items-baseline mb-16 gap-4">
            <h2 class="serif text-4xl">The Daily Verse</h2>
            <p class="text-slate-400 max-w-sm text-sm font-light leading-relaxed">Brief, haunting, and beautiful fragments of the human experience.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @foreach($poems as $poem)
            <div class="p-10 rounded-[2.5rem] bg-white border border-slate-50 hover:border-orange-200 hover:shadow-2xl hover:shadow-orange-100/30 transition-all duration-500 group">
                <div class="text-orange-200 mb-8 group-hover:text-orange-500 transition text-2xl">✦</div>
                <h3 class="serif text-2xl mb-4 italic leading-snug text-slate-800">{{ $poem['title'] }}</h3>
                <p class="text-slate-500 text-sm font-light leading-relaxed mb-8 italic">"{{ $poem['excerpt'] }}"</p>
                <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $poem['author'] }}</span>
                    <button class="text-slate-300 group-hover:text-orange-500 transition">♥</button>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <section class="py-24">
        <div class="bg-orange-50/50 rounded-[4rem] p-12 md:p-20 border border-orange-100">
            <div class="grid md:grid-cols-2 gap-16">
                <div>
                    <span class="bg-white text-orange-600 px-4 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border border-orange-200">Earn Rewards</span>
                    <h2 class="serif text-5xl mt-6 mb-8 text-slate-900 leading-tight">Your Attention is <br><span class="italic text-orange-500">Valuable.</span></h2>
                    <p class="text-slate-500 text-lg font-light leading-relaxed mb-10">
                        Help authors grow by providing thoughtful feedback and sharing their work. Earn credits that you can withdraw or use to promote your own stories.
                    </p>
                    <a href="#" class="inline-block border-b-2 border-slate-900 pb-1 font-bold text-sm tracking-widest uppercase hover:text-orange-600 transition">View Dashboard</a>
                </div>
                <div class="space-y-4">
                    @foreach($earnTasks as $task)
                    <div class="bg-white p-6 rounded-3xl flex justify-between items-center shadow-sm border border-orange-100/50 hover:translate-x-2 transition duration-300">
                        <div>
                            <p class="text-xs text-slate-400 mb-1">{{ $task['time'] }}</p>
                            <h4 class="font-medium text-slate-800">{{ $task['title'] }}</h4>
                        </div>
                        <div class="text-right">
                            <p class="text-orange-600 font-bold">{{ $task['reward'] }}</p>
                            <p class="text-[10px] uppercase text-slate-300">Available</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-slate-950 rounded-[4rem] px-8 md:px-16 text-white overflow-hidden relative">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-orange-500/10 blur-[120px] rounded-full"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-end mb-20">
            <div>
                <span class="text-orange-400 text-xs font-bold uppercase tracking-[0.3em]">Editor's Pick</span>
                <h2 class="serif text-5xl mt-4">The Long Reads</h2>
            </div>
            <a href="#" class="text-sm border-b border-orange-500/30 hover:border-orange-500 pb-1 transition text-slate-400">View all series</a>
        </div>

        <div class="grid lg:grid-cols-3 gap-12 relative z-10">
            @foreach([1, 2, 3] as $item)
            <div class="group cursor-pointer">
                <div class="aspect-[16/10] rounded-[2rem] overflow-hidden mb-8 shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition duration-1000 opacity-80 group-hover:opacity-100">
                </div>
                <div class="px-2">
                    <div class="flex items-center space-x-3 mb-4 text-xs text-orange-400 font-bold uppercase tracking-widest">
                        <span>Series</span>
                        <span class="w-1 h-1 bg-slate-700 rounded-full"></span>
                        <span>12 Chapters</span>
                    </div>
                    <h4 class="serif text-2xl mb-4 text-slate-100">The Architect of Forgotten Dreams</h4>
                    <p class="text-slate-400 text-sm font-light leading-relaxed line-clamp-2">A deep dive into the subconscious of a man who builds cities for the souls of the deceased.</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <section class="py-32 grid md:grid-cols-2 gap-24 items-center">
        <div class="relative flex justify-center">
            <div class="relative w-full max-w-md aspect-square">
                <img src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=400" class="absolute top-0 right-0 w-2/3 rounded-[2rem] shadow-2xl z-20">
                <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400" class="absolute bottom-0 left-0 w-2/3 rounded-[2rem] shadow-xl z-10 grayscale">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-orange-100 rounded-full blur-3xl opacity-50"></div>
            </div>
        </div>
        <div class="space-y-10">
            <div>
                <h2 class="serif text-5xl mb-6 leading-tight">Meet the <br><span class="italic font-light text-slate-400">Soul-Keepers.</span></h2>
                <p class="text-slate-500 text-lg font-light leading-relaxed">Our top contributors don't just write; they create worlds. Explore the minds behind the ink.</p>
            </div>
            <div class="space-y-4">
                @foreach(['Thomas Wright' => '4.2k followers', 'Sia K.' => '1.8k followers', 'Julian Voss' => '9.5k followers'] as $name => $stats)
                <div class="flex items-center justify-between p-6 rounded-2xl border border-slate-50 hover:bg-slate-50 transition cursor-pointer group">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full bg-slate-200"></div>
                        <div>
                            <h5 class="font-bold text-slate-800">{{ $name }}</h5>
                            <p class="text-[10px] uppercase text-slate-400 tracking-widest">{{ $stats }}</p>
                        </div>
                    </div>
                    <button class="text-xs font-bold text-orange-500 opacity-0 group-hover:opacity-100 transition tracking-widest uppercase">Follow +</button>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-24 border-t border-slate-100">
        <div class="grid md:grid-cols-3 gap-12">
            <div class="space-y-6">
                <h3 class="serif text-3xl">Active Contests</h3>
                <div class="bg-slate-50 p-8 rounded-[2rem] space-y-6">
                    <div>
                        <p class="text-orange-500 text-[10px] font-bold uppercase tracking-widest mb-2">Ends in 2 Days</p>
                        <h4 class="font-bold text-slate-800">The Winter Haiku Challenge</h4>
                        <p class="text-xs text-slate-500 mt-2">Prize: $500 & Featured Slot</p>
                    </div>
                    <button class="w-full py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-slate-900 hover:text-white transition">Submit Entry</button>
                </div>
            </div>
            <div class="col-span-2">
                <h3 class="serif text-3xl mb-8">Writer Services</h3>
                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="p-8 border border-slate-100 rounded-[2rem] hover:bg-orange-50/30 transition">
                        <h4 class="font-bold text-slate-800 mb-2">Editorial Review</h4>
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">Get professional feedback on your manuscript from senior editors.</p>
                        <span class="text-orange-600 font-bold">$49.00</span>
                    </div>
                    <div class="p-8 border border-slate-100 rounded-[2rem] hover:bg-orange-50/30 transition">
                        <h4 class="font-bold text-slate-800 mb-2">Custom Cover Art</h4>
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">Bespoke, minimalist cover designs that capture the soul of your book.</p>
                        <span class="text-orange-600 font-bold">$120.00</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="my-32 relative py-24 px-12 rounded-[4rem] bg-gradient-to-br from-slate-900 to-slate-800 text-center overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>
        <div class="relative z-10 space-y-8">
            <h2 class="serif text-5xl md:text-7xl text-white">Ready to begin?</h2>
            <p class="text-slate-400 max-w-xl mx-auto text-lg font-light">Whether you're here to lose yourself in a story or find yourself in writing, your diary is waiting.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-6 pt-6">
                <button class="bg-orange-500 text-white px-12 py-5 rounded-full font-bold shadow-2xl shadow-orange-500/20 hover:scale-105 transition-all">Create Writer Account</button>
                <button class="bg-white/10 backdrop-blur-md text-white border border-white/20 px-12 py-5 rounded-full font-bold hover:bg-white/20 transition-all">Browse as Guest</button>
            </div>
        </div>
    </section>
</div>
@endsection