@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">

        <!-- HEADER -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8">

            <h1 class="text-xl sm:text-2xl font-serif font-semibold text-slate-800">
                Edit Story
            </h1>

            <a href="{{ route('diary.dashboard') }}" class="text-sm text-slate-500 hover:text-slate-700 shrink-0">
                ← Dashboard
            </a>

        </div>


        <form action="{{ route('diary.stories.update', $story) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">

                <!-- LEFT EDITOR -->
                <div class="lg:col-span-8">

                    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 md:p-8">

                        <input type="text" name="title" value="{{ old('title', $story->title) }}"
                            placeholder="Start with your story title..."
                            class="w-full text-2xl sm:text-3xl font-serif border-none outline-none mb-4 sm:mb-6">

                        <textarea name="description" rows="6" placeholder="Write a description or introduction..."
                            class="w-full border-none outline-none text-slate-600 resize-none">
{{ old('description', $story->description) }}
</textarea>

                    </div>


                    <!-- CHAPTER LIST -->
                    <div class="mt-6 sm:mt-8 bg-white border border-slate-200 rounded-2xl p-4 sm:p-6">

                        <h2 class="font-serif text-lg font-semibold mb-4">
                            Chapters
                        </h2>

                        <ul class="space-y-3">

                            @foreach ($story->chapters as $ch)
                                <li class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-4 p-3 rounded-lg hover:bg-slate-50">

                                    <div class="min-w-0">

                                        <span class="font-medium break-words">
                                            {{ $ch->chapter_title }}
                                        </span>

                                        <span class="text-sm text-slate-400 ml-0 sm:ml-2 block sm:inline">
                                            {{ $ch->read_time }} min · {{ $ch->status }}
                                        </span>

                                    </div>

                                    <div class="flex gap-3 shrink-0">

                                        <a href="{{ route('diary.chapters.edit', [$story, $ch]) }}"
                                            class="text-sm text-slate-600 hover:text-slate-800">
                                            Edit
                                        </a>

                                        <form action="{{ route('diary.chapters.destroy', [$story, $ch]) }}" method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button onclick="return confirm('Delete chapter?')"
                                                class="text-sm text-red-500">
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </li>
                            @endforeach

                        </ul>

                        <a href="{{ route('diary.chapters.create', $story) }}"
                            class="inline-block mt-4 text-sm px-4 py-2 bg-slate-100 rounded-lg hover:bg-slate-200">
                            + Add Chapter
                        </a>

                    </div>

                </div>



                <!-- RIGHT SETTINGS -->
                <div class="lg:col-span-4">

                    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 space-y-4">

                        <h3 class="font-semibold text-slate-700">
                            Story Settings
                        </h3>


                        <!-- Cover -->
                        <div class="border-2 border-dashed rounded-xl p-6 text-center">

                            @if ($story->cover_image)
                                <img src="{{ $story->cover_image }}" class="rounded-lg mb-2">
                            @endif

                            <input type="url" name="cover_image" value="{{ old('cover_image', $story->cover_image) }}"
                                placeholder="Cover Image URL" class="w-full border rounded-lg px-3 py-2">

                        </div>


                        <!-- Genre -->
                        <div>

                            <label class="text-sm text-slate-600">
                                Genre
                            </label>

                            <input type="text" name="genre" value="{{ old('genre', $story->genre) }}"
                                class="w-full border rounded-lg px-3 py-2 mt-1">

                        </div>


                        <!-- Tags -->
                        <div>

                            <label class="text-sm text-slate-600">
                                Tags
                            </label>

                            <input type="text" name="tags"
                                value="{{ old('tags', $story->tags ? implode(', ', $story->tags) : '') }}"
                                class="w-full border rounded-lg px-3 py-2 mt-1">

                        </div>


                        <!-- Language -->
                        <div>

                            <label class="text-sm text-slate-600">
                                Language
                            </label>

                            <select name="language" class="w-full border rounded-lg px-3 py-2 mt-1">

                                <option value="hinglish" {{ old('language', $story->language) == 'hinglish' ? 'selected' : '' }}>
                                    Hinglish
                                </option>

                                <option value="hindi" {{ old('language', $story->language) == 'hindi' ? 'selected' : '' }}>
                                    Hindi
                                </option>

                            </select>

                        </div>


                        <!-- Story Type -->
                        <div>

                            <label class="text-sm text-slate-600">
                                Story Type
                            </label>

                            <select name="story_type" class="w-full border rounded-lg px-3 py-2 mt-1">

                                <option value="short_story"
                                    {{ old('story_type', $story->story_type) == 'short_story' ? 'selected' : '' }}>
                                    Short Story
                                </option>

                                <option value="series" {{ old('story_type', $story->story_type) == 'series' ? 'selected' : '' }}>
                                    Series
                                </option>

                            </select>

                        </div>


                        <!-- Publishing -->
                        <div>

                            <label class="text-sm text-slate-600">
                                Content Guidance
                            </label>

                            <input type="text" name="content_guidance"
                                value="{{ old('content_guidance', $story->content_guidance) }}"
                                class="w-full border rounded-lg px-3 py-2 mt-1">

                        </div>


                        <div>

                            <label class="text-sm text-slate-600">
                                Visibility
                            </label>

                            <select name="visibility" class="w-full border rounded-lg px-3 py-2 mt-1">

                                <option value="draft" {{ old('visibility', $story->visibility) == 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>

                                <option value="public" {{ old('visibility', $story->visibility) == 'public' ? 'selected' : '' }}>
                                    Public
                                </option>

                            </select>

                        </div>


                        <div>

                            <label class="text-sm text-slate-600">
                                Status
                            </label>

                            <select name="status" class="w-full border rounded-lg px-3 py-2 mt-1">

                                <option value="ongoing" {{ old('status', $story->status) == 'ongoing' ? 'selected' : '' }}>
                                    Ongoing
                                </option>

                                <option value="completed" {{ old('status', $story->status) == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                            </select>

                        </div>


                        <div class="flex flex-col sm:flex-row gap-3 pt-4">

                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-orange-500 text-white rounded-lg">
                                Save Changes
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>
@endsection
