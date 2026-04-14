@extends('layouts.app', ['hideFooter' => true])

@section('content')
<style>
    [x-cloak] { display: none !important; }
    .writer-shell {
        background:
            radial-gradient(ellipse 80% 55% at 50% -20%, rgba(251, 207, 232, 0.45), transparent),
            radial-gradient(ellipse 65% 45% at 100% 40%, rgba(221, 214, 254, 0.4), transparent),
            linear-gradient(180deg, #fff1f8 0%, #fdf4ff 45%, #eef2ff 100%);
    }
    .writer-card {
        box-shadow: 0 20px 50px -20px rgba(109, 40, 217, 0.25);
    }
    .dark .writer-shell {
        background: linear-gradient(180deg, #0f172a 0%, #111827 45%, #1f2937 100%);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/axios@1.11.0/dist/axios.min.js"></script>

<div class="writer-shell min-h-[calc(100vh-4rem)] py-8 px-4 sm:px-6" x-data="chapterWriterPage()" x-init="init()">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl sm:text-4xl font-semibold text-slate-900">AI Chapter Writer</h1>
                <p class="text-sm text-slate-600 mt-1">Generate emotional chapters for Soul Diaries.</p>
            </div>
            <button @click="dark = !dark" class="px-4 py-2 rounded-full text-sm font-semibold border border-slate-300 bg-white">
                <span x-text="dark ? 'Light Mode' : 'Dark Mode'"></span>
            </button>
        </div>

        <div :class="dark ? 'dark' : ''">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <section class="lg:col-span-2 writer-card rounded-3xl p-5 bg-white/90 border border-white">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Story Prompt</label>
                    <textarea x-model="form.prompt" rows="6" class="w-full rounded-2xl border border-violet-200 p-3 text-sm text-slate-800" placeholder="Write your chapter idea..."></textarea>

                    <label class="block text-sm font-medium text-slate-700 mt-4 mb-2">Mood</label>
                    <select x-model="form.mood" class="w-full rounded-xl border border-violet-200 p-3 text-sm text-slate-800">
                        <option value="love">Love</option>
                        <option value="heartbreak">Heartbreak</option>
                        <option value="motivation">Motivation</option>
                        <option value="dark">Dark</option>
                        <option value="life">Life</option>
                    </select>

                    <label class="block text-sm font-medium text-slate-700 mt-4 mb-2">Previous Context (optional)</label>
                    <textarea x-model="form.context" rows="4" class="w-full rounded-2xl border border-violet-200 p-3 text-sm text-slate-800" placeholder="Previous chapter context..."></textarea>

                    <div class="flex flex-wrap gap-2 mt-5">
                        <button @click="generate()" :disabled="loading" class="px-4 py-2 rounded-xl bg-violet-600 text-white text-sm font-semibold disabled:opacity-50">Generate Chapter</button>
                        <button @click="generate()" :disabled="loading" class="px-4 py-2 rounded-xl bg-fuchsia-100 text-fuchsia-800 text-sm font-semibold disabled:opacity-50">Regenerate</button>
                        <button @click="save('published')" :disabled="saving || !result.content" class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-semibold disabled:opacity-50">Save</button>
                        <button @click="save('draft')" :disabled="saving || !result.content" class="px-4 py-2 rounded-xl border border-slate-300 text-sm font-semibold disabled:opacity-50">Save Draft</button>
                    </div>
                </section>

                <section id="writer-output" class="lg:col-span-3 writer-card rounded-3xl p-5 bg-white/90 border border-white">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <h2 class="text-xl sm:text-2xl font-semibold text-slate-900">Generated Chapter</h2>
                        <div class="text-xs text-slate-500 flex items-center gap-3">
                            <span><span x-text="wordCount"></span> words</span>
                            <button @click="copy()" class="px-3 py-1 rounded-lg border border-slate-300 text-slate-700">Copy</button>
                        </div>
                    </div>

                    <div x-show="loading" class="space-y-3 animate-pulse" x-cloak>
                        <div class="h-8 w-2/3 bg-slate-200 rounded-lg"></div>
                        <div class="h-4 w-full bg-slate-200 rounded"></div>
                        <div class="h-4 w-full bg-slate-200 rounded"></div>
                        <div class="h-4 w-5/6 bg-slate-200 rounded"></div>
                    </div>

                    <div x-show="!loading && result.content" x-cloak>
                        <input x-model="result.title" class="w-full bg-transparent text-2xl font-semibold text-slate-900 outline-none mb-4" />

                        <p class="text-[1.05rem] leading-8 whitespace-pre-wrap text-slate-700" x-text="teaserText"></p>

                        <div x-show="showContinueBreak" x-cloak class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-900">
                            Continue Reading Break · after <span x-text="result.continue_reading_after"></span> words
                        </div>

                        <label class="block text-sm font-medium text-slate-700 mt-6 mb-2">Edit AI Content</label>
                        <textarea x-model="result.content" rows="14" class="w-full rounded-2xl border border-violet-200 p-3 text-sm text-slate-800"></textarea>
                    </div>

                    <div x-show="!loading && !result.content" class="py-20 text-center text-sm text-slate-500" x-cloak>
                        Your chapter will appear here with typewriter effect.
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div x-show="toast.message" x-cloak class="fixed top-5 right-5 z-50 px-4 py-2 rounded-xl text-white shadow-lg"
         :class="toast.type === 'error' ? 'bg-red-600' : 'bg-slate-900'">
        <span x-text="toast.message"></span>
    </div>
</div>

<script>
function chapterWriterPage() {
    return {
        dark: false,
        loading: false,
        saving: false,
        form: { prompt: '', mood: 'love', context: '' },
        result: { title: '', content: '', continue_reading_after: 300 },
        typed: '',
        toast: { message: '', type: 'success' },
        init() {},
        get wordCount() {
            if (!this.result.content) return 0;
            return this.result.content.trim().split(/\s+/).filter(Boolean).length;
        },
        get teaserText() {
            const words = this.typed.trim() ? this.typed.trim().split(/\s+/) : [];
            const cap = Number(this.result.continue_reading_after || 300);
            if (words.length <= cap) return this.typed;
            return words.slice(0, cap).join(' ') + '...';
        },
        get showContinueBreak() {
            return this.wordCount > Number(this.result.continue_reading_after || 300);
        },
        notify(message, type = 'success') {
            this.toast = { message, type };
            setTimeout(() => { this.toast.message = ''; }, 2400);
        },
        async generate() {
            if (!this.form.prompt.trim()) {
                this.notify('Please enter story prompt.', 'error');
                return;
            }
            this.loading = true;
            this.typed = '';
            try {
                const { data } = await axios.post('/api/generate-chapter', this.form);
                this.result = {
                    title: data.title || 'Untitled Chapter',
                    content: data.content || '',
                    continue_reading_after: Number(data.continue_reading_after || 300),
                };
                this.typeWriter(this.result.content);
                this.notify('Chapter generated.');
                setTimeout(() => {
                    document.getElementById('writer-output')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            } catch (e) {
                this.notify(e?.response?.data?.message || 'Generation failed.', 'error');
            } finally {
                this.loading = false;
            }
        },
        typeWriter(text) {
            this.typed = '';
            let i = 0;
            const timer = setInterval(() => {
                i += 5;
                this.typed = text.slice(0, i);
                if (i >= text.length) clearInterval(timer);
            }, 8);
        },
        async save(status) {
            if (!this.result.content) {
                this.notify('Generate chapter first.', 'error');
                return;
            }
            this.saving = true;
            try {
                await axios.post('/api/chapters', {
                    title: this.result.title,
                    content: this.result.content,
                    mood: this.form.mood,
                    continue_reading_after: this.result.continue_reading_after,
                    status,
                });
                this.notify(status === 'draft' ? 'Draft saved.' : 'Chapter saved.');
            } catch (e) {
                this.notify(e?.response?.data?.message || 'Save failed.', 'error');
            } finally {
                this.saving = false;
            }
        },
        async copy() {
            if (!this.result.content) return;
            try {
                await navigator.clipboard.writeText(`Title: ${this.result.title}\n\n${this.result.content}`);
                this.notify('Copied to clipboard.');
            } catch (e) {
                this.notify('Copy failed.', 'error');
            }
        },
    };
}
</script>
@endsection
