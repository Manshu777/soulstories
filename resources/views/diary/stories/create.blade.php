@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8" x-data="storyCreatePage()">
    <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-slate-800">Create Story</h1>
            <p class="text-slate-500 text-sm mt-1">Step 1 of 2: Story details</p>
        </div>
        <div class="text-xs sm:text-sm text-slate-500 flex gap-4">
            <span class="text-orange-600 font-semibold">1. Story</span>
            <span>2. Chapter editor</span>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('diary.stories.store') }}" enctype="multipart/form-data" @submit="return validateBeforeSubmit()" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @csrf

        <section class="lg:col-span-8 space-y-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <label class="block text-sm font-medium text-slate-700 mb-2">Story Title</label>
                <input type="text" name="title" x-model="title" maxlength="255" required
                    class="w-full text-2xl sm:text-3xl font-serif rounded-xl border border-slate-200 px-4 py-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-300"
                    placeholder="Your story title...">
                <p class="text-xs text-slate-400 mt-2" x-text="title.length + ' / 255'"></p>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="10" maxlength="5000"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:ring-2 focus:ring-orange-200 focus:border-orange-300"
                    placeholder="Tell readers what your story is about..."></textarea>
            </div>
        </section>

        <aside class="lg:col-span-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4 sticky top-24">
<<<<<<< HEAD
                <h2 class="font-semibold text-slate-800">Story Settings</h2>

=======
            <div class="flex justify-between items-center"> 
            <h2 class="font-semibold text-slate-800">Story Settings</h2>
    <a href="/ai/image" class="text-blue-600"> Generator image </a>

            </div>
>>>>>>> 7042497cfc8ddf8557fa3ce1bb8f911121717f35
                <div class="rounded-xl border-2 border-dashed border-slate-300 p-4 text-center" @dragover.prevent="dragging=true" @dragleave.prevent="dragging=false" @drop.prevent="handleDrop($event)">
                    <p class="text-sm text-slate-600 mb-2">Cover image</p>
                    <input type="file" name="cover_image" x-ref="coverInput" accept="image/*" class="w-full text-sm">
                    <p class="text-xs text-slate-400 mt-2">Drag & drop or click to upload</p>
                </div>

                <div>
                    <label class="block text-sm text-slate-600 mb-1">Genre</label>
                    <select name="genre" class="w-full rounded-lg border border-slate-200 px-3 py-2">
                        <option value="">Select genre</option>
                        <option>Romance</option><option>Mystery</option><option>Fantasy</option><option>Drama</option><option>Poetry</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm text-slate-600 mb-1">Language</label>
                        <select name="language" required class="w-full rounded-lg border border-slate-200 px-3 py-2">
                            <option value="hindi">Hindi</option>
                            <option value="hinglish" selected>Hinglish</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600 mb-1">Type</label>
                        <select name="story_type" required class="w-full rounded-lg border border-slate-200 px-3 py-2">
                            <option value="short_story">Short Story</option>
                            <option value="series">Series</option>
                            <option value="poems">Poems</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-slate-600 mb-1">Content Guidance</label>
                    <input type="text" name="content_guidance" class="w-full rounded-lg border border-slate-200 px-3 py-2" placeholder="e.g. 16+, violence">
                </div>

                <div>
                    <label class="block text-sm text-slate-600 mb-1">Tags (#hashtags)</label>
                    <input type="text" x-model="tagInput" @keydown.enter.prevent="addTag()" class="w-full rounded-lg border border-slate-200 px-3 py-2" placeholder="#love #night">
                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(tag, index) in tags" :key="index">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs bg-slate-100 text-slate-700">
                                <span x-text="tag"></span>
                                <button type="button" @click="tags.splice(index,1)" class="text-slate-500">×</button>
                            </span>
                        </template>
                    </div>
                    <input type="hidden" name="tags_json" :value="JSON.stringify(tags)">
                </div>

                <div>
                    <label class="block text-sm text-slate-600 mb-1">Visibility</label>
                    <select name="visibility" class="w-full rounded-lg border border-slate-200 px-3 py-2">
                        <option value="draft">Draft</option>
                        <option value="public">Public</option>
                        <option value="premium">Premium</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm text-slate-600 mb-1">Theme</label>
                        <select name="theme" class="w-full rounded-lg border border-slate-200 px-3 py-2">
                            <option value="light">Light</option>
                            <option value="dark">Dark</option>
                            <option value="sepia">Sepia</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600 mb-1">Background color</label>
                        <input type="color" name="bg_color" value="#ffffff" class="w-full h-10 rounded-lg border border-slate-200">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-slate-600 mb-1">Background image (optional)</label>
                    <input type="file" name="bg_image" accept="image/*" class="w-full text-sm">
                </div>

                <div>
                    <label class="block text-sm text-slate-600 mb-1">Progress Status</label>
                    <select name="status" class="w-full rounded-lg border border-slate-200 px-3 py-2">
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <button type="submit" class="w-full rounded-xl bg-slate-900 text-white py-2.5 font-medium hover:bg-slate-800">Save & Continue to Chapter Editor</button>
            </div>
        </aside>
    </form>
</div>

<script>
function storyCreatePage() {
    return {
        title: '',
        tagInput: '',
        tags: [],
        dragging: false,
        addTag() {
            const raw = this.tagInput.trim();
            if (!raw) return;
            const parts = raw.split(/\s+/).map(t => t.startsWith('#') ? t : '#' + t);
            for (const p of parts) {
                if (p.length > 1 && !this.tags.includes(p)) this.tags.push(p);
            }
            this.tagInput = '';
        },
        handleDrop(e) {
            this.dragging = false;
            if (!e.dataTransfer.files?.length) return;
            this.$refs.coverInput.files = e.dataTransfer.files;
        },
        validateBeforeSubmit() {
            if (this.title.trim().length < 3) {
                alert('Title must be at least 3 characters.');
                return false;
            }
            return true;
        }
    }
}
</script>
@endsection
