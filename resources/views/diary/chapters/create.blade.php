@extends('layouts.app', ['hideFooter' => true])

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;1,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
@endpush

@section('content')
<style>
    [x-cloak] { display: none !important; }
    .chapter-editor-shell {
        font-family: 'Inter', system-ui, sans-serif;
        background:
            radial-gradient(ellipse 80% 50% at 50% -20%, rgba(167, 139, 250, 0.35), transparent),
            radial-gradient(ellipse 60% 40% at 100% 50%, rgba(196, 181, 253, 0.25), transparent),
            radial-gradient(ellipse 50% 30% at 0% 80%, rgba(221, 214, 254, 0.4), transparent),
            linear-gradient(180deg, #eef2ff 0%, #f5f3ff 45%, #ede9fe 100%);
    }
    .chapter-editor-card {
        box-shadow: 0 25px 50px -12px rgba(109, 40, 217, 0.12), 0 0 0 1px rgba(255,255,255,0.8) inset;
    }
    /* Quill — floating toolbar look */
    .chapter-quill-wrap .ql-toolbar.ql-snow {
        border: none;
        background: rgba(255,255,255,0.95);
        border-radius: 9999px;
        padding: 0.5rem 1rem;
        box-shadow: 0 10px 40px -10px rgba(91, 33, 182, 0.2);
        margin-bottom: 1rem;
    }
    .chapter-quill-wrap .ql-container.ql-snow {
        border: none;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.25rem;
        line-height: 1.75;
        min-height: 420px;
    }
    .chapter-quill-wrap .ql-editor { min-height: 420px; padding: 1.5rem 0.5rem; }
    .chapter-quill-wrap .ql-editor.ql-blank::before {
        font-family: 'Playfair Display', Georgia, serif;
        font-style: italic;
        color: #a1a1aa;
        font-size: 1.5rem;
    }
    .theme-light .chapter-editor-inner { background: #fff; color: #1e293b; }
    .theme-dark .chapter-editor-inner { background: #1e1b2e; color: #f1f5f9; }
    .theme-dark .chapter-quill-wrap .ql-editor { color: #f1f5f9; }
    .theme-sepia .chapter-editor-inner { background: #faf5eb; color: #3d3428; }
    .theme-sepia .chapter-quill-wrap .ql-editor { color: #3d3428; }
</style>

<div class="chapter-editor-shell min-h-[calc(100vh-4rem)] pb-16" x-data="chapterEditorPage()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-4">
        {{-- Header row --}}
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-3xl sm:text-4xl font-semibold text-slate-900 tracking-tight" style="font-family: 'Playfair Display', serif;">
                    Chapter Title
                </h1>
                <p class="text-slate-500 mt-2 text-sm sm:text-base">
                    Step 2 of 2 · <span class="text-slate-700 font-medium">{{ $story->title }}</span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-4 lg:justify-end">
                <div class="flex items-center gap-6 text-sm text-slate-600 bg-white/70 backdrop-blur-sm rounded-full px-5 py-2.5 shadow-sm border border-white/80">
                    <span>Words: <strong class="text-slate-900" x-text="wordCount"></strong></span>
                    <span class="text-slate-300">|</span>
                    <span>Reading: <strong class="text-slate-900" x-text="readingTime"></strong> min</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 uppercase tracking-wide">Saved</span>
                    <button type="button"
                        @click="autosaveEnabled = !autosaveEnabled"
                        class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors"
                        :class="autosaveEnabled ? 'bg-emerald-500' : 'bg-slate-300'">
                        <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition translate-x-1"
                            :class="autosaveEnabled ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
                <div class="flex rounded-full bg-white/80 border border-violet-100 p-1 shadow-sm">
                    <button type="button" @click="editorTheme = 'light'" class="p-2 rounded-full transition" :class="editorTheme === 'light' ? 'bg-violet-100 text-violet-800' : 'text-slate-400 hover:text-slate-600'" title="Light">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <button type="button" @click="editorTheme = 'dark'" class="p-2 rounded-full transition" :class="editorTheme === 'dark' ? 'bg-violet-200 text-violet-900' : 'text-slate-400 hover:text-slate-600'" title="Dark">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </button>
                    <button type="button" @click="editorTheme = 'sepia'" class="p-2 rounded-full transition" :class="editorTheme === 'sepia' ? 'bg-amber-100 text-amber-900' : 'text-slate-400 hover:text-slate-600'" title="Sepia">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 xl:gap-8 relative">
            {{-- Main editor column --}}
            <div class="xl:col-span-8 space-y-4">
                <section class="chapter-editor-card rounded-[2rem] bg-white/90 backdrop-blur-md border border-white/60 overflow-hidden"
                    :class="[fullscreen ? 'fixed inset-4 z-50 shadow-2xl' : '', 'theme-' + editorTheme]">
                    <div class="chapter-editor-inner px-5 sm:px-8 pt-6 pb-4 transition-colors duration-300">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <input type="text" x-model="chapterTitle" placeholder="Your chapter title..."
                                class="w-full rounded-2xl border border-violet-100 bg-white/80 px-4 py-3 text-slate-800 placeholder:text-slate-400 focus:ring-2 focus:ring-violet-200 focus:border-violet-300 outline-none transition">
                            <input type="number" x-model="chapterNumber" min="1"
                                class="w-full rounded-2xl border border-violet-100 bg-white/80 px-4 py-3 text-slate-800 focus:ring-2 focus:ring-violet-200 outline-none transition">
                        </div>

                        {{-- Secondary toolbar: media & blocks --}}
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            <button type="button" @click="togglePreview()" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-xl bg-violet-50 text-violet-800 border border-violet-100 hover:bg-violet-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span x-text="previewMode ? 'Edit' : 'Preview'"></span>
                            </button>
                            <button type="button" @click="toggleFullscreen()" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-xl bg-white border border-slate-200 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                <span x-text="fullscreen ? 'Exit' : 'Fullscreen'"></span>
                            </button>
                            <button type="button" @click="insertDivider()" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs rounded-xl bg-white border border-slate-200 hover:bg-slate-50">➖ Divider</button>
                            <button type="button" @click="insertContinueBlock()" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs rounded-xl bg-amber-50 border border-amber-100 text-amber-900 hover:bg-amber-100">⏭ Continue block</button>
                            <div class="flex items-center gap-2 flex-1 min-w-[200px]">
                                <input type="url" x-model="youtubeUrl" placeholder="YouTube URL" class="flex-1 min-w-0 rounded-xl border border-slate-200 px-3 py-2 text-xs">
                                <button type="button" @click="embedYoutube()" class="shrink-0 px-3 py-2 rounded-xl bg-violet-600 text-white text-xs font-medium hover:bg-violet-700">Embed</button>
                            </div>
                            <label class="inline-flex items-center gap-1.5 px-3 py-2 text-xs rounded-xl bg-white border border-slate-200 cursor-pointer hover:bg-slate-50">
                                🖼️ Image
                                <input type="file" class="hidden" accept="image/*" @change="uploadImage($event)">
                            </label>
                            <label class="inline-flex items-center gap-1.5 px-3 py-2 text-xs rounded-xl bg-white border border-slate-200 cursor-pointer hover:bg-slate-50">
                                🎵 Audio
                                <input type="file" class="hidden" accept="audio/*" @change="uploadAudio($event)">
                            </label>
                        </div>

                        <div x-show="!previewMode" x-cloak class="chapter-quill-wrap">
                            <div id="toolbar">
                                <span class="ql-formats">
                                    <select class="ql-header"><option value="1"></option><option value="2"></option><option selected></option></select>
                                    <button class="ql-bold"></button>
                                    <button class="ql-italic"></button>
                                    <button class="ql-underline"></button>
                                    <button class="ql-blockquote"></button>
                                    <button class="ql-list" value="ordered"></button>
                                    <button class="ql-list" value="bullet"></button>
                                    <select class="ql-align"></select>
                                    <button class="ql-clean"></button>
                                </span>
                            </div>
                            <div id="editor"></div>
                        </div>

                        <div x-show="previewMode" x-cloak class="prose prose-lg max-w-none px-2 py-6 min-h-[420px] rounded-2xl border border-violet-100/50" x-html="contentHtml"></div>

                        {{-- Continue reading preview strip (when threshold set) --}}
                        <div x-show="continueAfter && Number(continueAfter) > 0" x-cloak class="mt-6 rounded-2xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100/80 px-4 py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 text-amber-900 text-sm font-medium">
                                <span class="text-lg">→</span>
                                <span>Continue Reading… <span class="text-amber-700/80 font-normal">(after <span x-text="continueAfter"></span> words)</span></span>
                            </div>
                        </div>

                        {{-- Footer stats + progress --}}
                        <div class="mt-8 pt-4 border-t border-violet-100/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-sm text-slate-500">
                                Words: <strong class="text-slate-800" x-text="wordCount"></strong>
                                <span class="mx-2 text-slate-300">|</span>
                                Reading: <strong class="text-slate-800" x-text="readingTime"></strong> min
                                <span class="mx-2 text-slate-300">|</span>
                                <span x-show="savedAt" class="text-emerald-600 font-medium">✓ Saved <span x-text="savedAt"></span></span>
                                <span x-show="!savedAt" class="text-slate-400">Not saved yet</span>
                            </p>
                            <div class="w-full sm:w-48 h-2 rounded-full bg-violet-100 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-violet-500 to-fuchsia-500 transition-all duration-300"
                                    :style="'width: ' + progressPercent + '%'"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <template x-if="audioPreview">
                    <div class="rounded-2xl bg-white/80 border border-violet-100 p-4 shadow-sm">
                        <p class="text-xs text-slate-500 mb-2">Audio preview</p>
                        <audio :src="audioPreview" controls class="w-full rounded-lg"></audio>
                    </div>
                </template>
            </div>

            {{-- Sidebar --}}
            <aside class="xl:col-span-4 space-y-5">
                <div class="rounded-[1.75rem] bg-white/90 backdrop-blur-md border border-white/70 shadow-lg shadow-violet-200/40 p-6">
                    <h3 class="font-semibold text-slate-900 text-lg mb-4" style="font-family: 'Playfair Display', serif;">Publish</h3>
                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Status</label>
                    <select x-model="status" class="w-full rounded-2xl border border-violet-100 bg-violet-50/50 px-4 py-3 text-slate-800 mb-4 focus:ring-2 focus:ring-violet-200 outline-none">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Reading break (words)</label>
                    <p class="text-xs text-slate-400 mb-2">Show “Continue reading” after this many words (leave empty for none).</p>
                    <input type="number" x-model="continueAfter" min="50" placeholder="e.g. 300"
                        class="w-full rounded-2xl border border-violet-100 px-4 py-3 mb-5 focus:ring-2 focus:ring-violet-200 outline-none">
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="saveDraft()" class="rounded-2xl py-3 text-sm font-semibold bg-violet-100 text-violet-900 hover:bg-violet-200 transition border border-violet-200/60">
                            Save Draft
                        </button>
                        <button type="button" @click="publishChapter()" class="rounded-2xl py-3 text-sm font-semibold bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white hover:from-violet-700 hover:to-fuchsia-700 shadow-md shadow-violet-300/50 transition">
                            Publish
                        </button>
                    </div>
                    <button type="button" @click="saveNow(true)" class="w-full mt-3 rounded-2xl py-3 text-sm font-medium border border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                        Save & return to story
                    </button>
                </div>

                <div class="rounded-[1.75rem] bg-white/90 backdrop-blur-md border border-white/70 shadow-lg shadow-violet-200/40 p-6">
                    <h3 class="font-semibold text-slate-900 text-lg mb-1" style="font-family: 'Playfair Display', serif;">Comments</h3>
                    <p class="text-xs text-slate-500 mb-4">Highlight text in the editor, then comment here.</p>
                    <div class="space-y-3 mb-4 max-h-40 overflow-y-auto">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-violet-200 flex-shrink-0"></div>
                            <div>
                                <p class="text-xs font-medium text-slate-800">Reader · <span class="text-slate-400 font-normal">Just now</span></p>
                                <p class="text-sm text-slate-600">Love this opening! 💜</p>
                            </div>
                        </div>
                    </div>
                    <textarea x-model="selectionComment" rows="3" placeholder="Highlight text and leave a comment…"
                        class="w-full rounded-2xl border border-violet-100 px-4 py-3 text-sm focus:ring-2 focus:ring-violet-200 outline-none resize-none"></textarea>
                    <div class="flex items-center justify-between mt-3">
                        <div class="flex gap-1">
                            <button type="button" @click="saveSelectionComment('heart')" class="p-2 rounded-xl hover:bg-violet-50 text-lg" title="Heart">❤️</button>
                            <button type="button" @click="saveSelectionComment('fire')" class="p-2 rounded-xl hover:bg-violet-50 text-lg" title="Fire">🔥</button>
                            <button type="button" @click="saveSelectionComment('sad')" class="p-2 rounded-xl hover:bg-violet-50 text-lg" title="Sad">😢</button>
                        </div>
                        <button type="button" @click="saveSelectionComment()" class="px-5 py-2 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-800">Send</button>
                    </div>
                </div>

                <div class="rounded-[1.75rem] bg-white/90 backdrop-blur-md border border-white/70 shadow-lg shadow-violet-200/40 p-6">
                    <h3 class="font-semibold text-slate-900 text-lg mb-3" style="font-family: 'Playfair Display', serif;">Customize</h3>
                    <p class="text-xs text-slate-500 mb-3">Editor canvas theme (reading theme is set in Step 1).</p>
                    <div class="flex rounded-2xl bg-slate-100 p-1 gap-1">
                        <button type="button" @click="editorTheme = 'light'" class="flex-1 py-2.5 rounded-xl text-sm font-medium transition"
                            :class="editorTheme === 'light' ? 'bg-white shadow text-violet-800' : 'text-slate-500'">Light</button>
                        <button type="button" @click="editorTheme = 'dark'" class="flex-1 py-2.5 rounded-xl text-sm font-medium transition"
                            :class="editorTheme === 'dark' ? 'bg-slate-800 text-white shadow' : 'text-slate-500'">Dark</button>
                        <button type="button" @click="editorTheme = 'sepia'" class="flex-1 py-2.5 rounded-xl text-sm font-medium transition"
                            :class="editorTheme === 'sepia' ? 'bg-amber-100 text-amber-900 shadow' : 'text-slate-500'">Sepia</button>
                    </div>
                </div>
            </aside>

            {{-- Floating action rail --}}
            <div class="hidden xl:flex flex-col fixed right-6 top-1/2 -translate-y-1/2 z-40 gap-3">
                <button type="button" @click="$refs.imageFab.click()" class="w-12 h-12 rounded-full bg-white shadow-lg border border-violet-100 flex items-center justify-center text-violet-600 hover:bg-violet-50 transition" title="Image">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </button>
                <input x-ref="imageFab" type="file" class="hidden" accept="image/*" @change="uploadImage($event)">
                <button type="button" @click="$refs.audioFab.click()" class="w-12 h-12 rounded-full bg-white shadow-lg border border-violet-100 flex items-center justify-center text-violet-600 hover:bg-violet-50 transition" title="Audio">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                </button>
                <input x-ref="audioFab" type="file" class="hidden" accept="audio/*" @change="uploadAudio($event)">
                <button type="button" @click="togglePreview()" class="w-12 h-12 rounded-full bg-white shadow-lg border border-violet-100 flex items-center justify-center text-violet-600 hover:bg-violet-50 transition" title="Preview">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
function chapterEditorPage() {
    return {
        quill: null,
        chapterId: null,
        chapterTitle: '',
        chapterNumber: {{ (int) $nextNumber }},
        status: 'draft',
        continueAfter: '',
        contentHtml: '',
        wordCount: 0,
        readingTime: 0,
        savedAt: null,
        fullscreen: false,
        previewMode: false,
        youtubeUrl: '',
        audioPreview: '',
        selectionComment: '',
        selectedRange: null,
        editorTheme: 'light',
        autosaveEnabled: true,
        autosaveTimer: null,

        get progressPercent() {
            const target = Number(this.continueAfter) || 1000;
            return Math.min(100, Math.round((this.wordCount / target) * 100));
        },

        init() {
            if (typeof Quill === 'undefined') {
                alert('Editor failed to load. Please refresh.');
                return;
            }
            this.quill = new Quill('#editor', {
                theme: 'snow',
                modules: { toolbar: '#toolbar' },
                placeholder: 'Start writing your chapter...'
            });
            this.quill.on('text-change', () => {
                this.contentHtml = this.quill.root.innerHTML;
                this.recalculateStats();
            });
            this.quill.on('selection-change', (range) => { this.selectedRange = range; });
            this.contentHtml = this.quill.root.innerHTML;
            this.recalculateStats();
            this.autosaveTimer = setInterval(() => {
                if (this.autosaveEnabled) this.saveNow(false);
            }, 5000);
        },

        recalculateStats() {
            if (!this.quill) return;
            const text = this.quill.getText().trim();
            this.wordCount = text ? text.split(/\s+/).length : 0;
            this.readingTime = Math.max(1, Math.ceil(this.wordCount / 180));
        },

        togglePreview() {
            this.previewMode = !this.previewMode;
            if (this.previewMode && this.quill) this.contentHtml = this.quill.root.innerHTML;
        },
        toggleFullscreen() { this.fullscreen = !this.fullscreen; },

        insertDivider() {
            if (!this.quill) return;
            const range = this.quill.getSelection(true) || { index: this.quill.getLength(), length: 0 };
            this.quill.insertText(range.index, "\n--------------------------\n", 'user');
        },
        insertContinueBlock() {
            if (!this.quill) return;
            const range = this.quill.getSelection(true) || { index: this.quill.getLength(), length: 0 };
            this.quill.insertText(range.index, "\n[ Continue Reading ]\n", 'user');
        },
        embedYoutube() {
            if (!this.quill || !this.youtubeUrl) return;
            const range = this.quill.getSelection(true) || { index: this.quill.getLength(), length: 0 };
            this.quill.insertText(range.index, `\nYouTube: ${this.youtubeUrl}\n`, 'user');
            this.youtubeUrl = '';
        },
        async uploadImage(e) {
            if (!this.quill) return;
            const file = e.target.files[0];
            if (!file) return;
            const fd = new FormData();
            fd.append('image', file);
            const res = await fetch('{{ route('diary.chapters.upload-image', $story) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd
            });
            const data = await res.json();
            if (data.url) {
                const range = this.quill.getSelection(true) || { index: this.quill.getLength(), length: 0 };
                this.quill.insertEmbed(range.index, 'image', data.url, 'user');
            }
            e.target.value = '';
        },
        async uploadAudio(e) {
            if (!this.quill) return;
            const file = e.target.files[0];
            if (!file) return;
            const fd = new FormData();
            fd.append('audio', file);
            const res = await fetch('{{ route('diary.chapters.upload-audio', $story) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd
            });
            const data = await res.json();
            if (data.url) {
                this.audioPreview = data.url;
                const range = this.quill.getSelection(true) || { index: this.quill.getLength(), length: 0 };
                this.quill.insertText(range.index, `\nAudio: ${data.url}\n`, 'user');
            }
            e.target.value = '';
        },

        saveDraft() {
            this.status = 'draft';
            this.saveNow(false);
        },
        publishChapter() {
            this.status = 'published';
            this.saveNow(true);
        },

        async saveNow(finalSave = false) {
            if (!this.quill) return;
            const title = (this.chapterTitle && this.chapterTitle.trim()) ? this.chapterTitle.trim() : 'Untitled chapter';
            const payload = {
                chapter_id: this.chapterId,
                chapter_title: title,
                chapter_number: Number(this.chapterNumber || 1),
                content: this.quill.root.innerHTML,
                status: this.status,
                continue_reading_after: this.continueAfter ? Number(this.continueAfter) : null,
                audio_file: this.audioPreview || null,
                youtube_url: null,
            };
            const res = await fetch('{{ route('diary.chapters.autosave', $story) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.ok) {
                this.chapterId = data.chapter_id;
                this.wordCount = data.word_count;
                this.readingTime = data.reading_time;
                this.savedAt = data.saved_at;
                if (finalSave) {
                    window.location.href = '{{ route('diary.stories.edit', $story) }}';
                }
            }
        },

        async saveSelectionComment(reaction = null) {
            if (!this.quill || !this.chapterId || !this.selectedRange) {
                alert('Select text in the editor and wait for autosave (first save).');
                return;
            }
            const selectedText = this.quill.getText(this.selectedRange.index, this.selectedRange.length).trim();
            if (!selectedText && !reaction) return;
            const res = await fetch('{{ route('diary.comments.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    story_chapter_id: this.chapterId,
                    body: this.selectionComment || null,
                    start_index: this.selectedRange.index,
                    end_index: this.selectedRange.index + this.selectedRange.length,
                    selected_text: selectedText,
                    reaction: reaction
                })
            });
            if (res.ok) {
                this.selectionComment = '';
                alert('Sent!');
            }
        }
    };
}
</script>
@endsection
