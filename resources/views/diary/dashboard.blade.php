@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">

        {{-- LEFT PROFILE SIDEBAR --}}
        <div class="lg:col-span-3 order-1">

            <div class="serif bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 text-center">

                <div class="relative inline-block">

                    <img src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('images/avtars/avtar-1.png') }}"
                        class="w-24 h-24 rounded-full">

                    <span onclick="openAvatarModal()"
                        class="absolute bottom-0 right-0 bg-white p-1 rounded-full border cursor-pointer">
                        ✏️
                    </span>

                </div>

                <h3 class="serif font-semibold text-lg text-slate-800">
                    {{ auth()->user()->name }}
                </h3>

                <p class="serif text-slate-500 text-sm mb-4">
                    {{ auth()->user()->username }}
                </p>

            </div>

        </div>


        {{-- RIGHT CONTENT --}}
        <div class="lg:col-span-9 space-y-4 sm:space-y-6 order-2">

            {{-- PROFILE HEADER --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 md:p-8">

                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">

                    <div class="space-y-2 sm:space-y-3 min-w-0">

                        <!-- Name -->
                        <h1 class="serif text-2xl sm:text-3xl font-semibold text-[#1f2a44] tracking-wide break-words">
                            {{ auth()->user()->name }}
                        </h1>

                        <!-- Username -->
                        <p class="text-slate-500 text-sm tracking-wide">
                            {{ auth()->user()->username }}
                        </p>

                        <!-- Bio -->
                        <p class="text-slate-600 text-base sm:text-lg leading-relaxed tracking-wide max-w-2xl">
                            Writer | Romance & Drama Lover 📚 Crafting stories with heart 💙
                        </p>

                        <!-- Stats -->
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 pt-2">

                            <div class="flex justify-center items-center gap-2">
                                <span class="text-2xl font-semibold text-[#33415e]">
                                    {{ $myStories->count() }}
                                </span>
                                <span class="text-md text-[#606980]">
                                    Stories
                                </span>
                            </div>

                            <div class="flex justify-center items-center gap-2">
                                <span class="text-2xl font-semibold text-[#33415e]">
                                    {{ number_format($analytics['total_reads']) }}
                                </span>
                                <span class="text-md text-[#606980]">
                                    Reads
                                </span>
                            </div>

                            <div class="flex justify-center items-center gap-2">
                                <span class="text-2xl font-semibold text-[#33415e]">
                                    {{ $followersCount }}
                                </span>
                                <span class="text-md text-[#606980]">
                                    Followers
                                </span>
                            </div>

                        </div>

                    </div>

                    <!-- Follow Button -->
                    <button type="button"
                        class="serif shrink-0 self-start sm:self-auto px-5 py-2.5 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100 transition w-full sm:w-auto text-center">
                        Follow
                    </button>

                </div>

                <!-- Tabs -->
                <div class="flex flex-wrap gap-4 sm:gap-8 mt-6 border-t pt-4 text-sm">

                    <span class="font-semibold border-b-2 border-slate-800 pb-2">
                        Analytics
                    </span>

                    <span class="text-slate-500 hover:text-slate-700 cursor-pointer">
                        Stats
                    </span>

                </div>

            </div>


            {{-- ANALYTICS CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">

                <div class="serif p-6 rounded-xl bg-gradient-to-r from-blue-100 to-blue-50">

                    <p class="text-2xl font-semibold">
                        {{ number_format($analytics['total_reads']) }}
                    </p>

                    <p class="text-sm text-slate-600">
                        Total reads
                    </p>

                    <p class="text-xs text-slate-500 mt-2">
                        {{ $followersCount }} followers
                    </p>

                </div>


                <div class="serif p-6 rounded-xl bg-gradient-to-r from-orange-100 to-orange-50">

                    <p class="text-2xl font-semibold">
                        {{ number_format($analytics['total_votes']) }}
                    </p>

                    <p class="text-sm text-slate-600">
                        Votes (likes)
                    </p>

                    <p class="text-xs text-slate-500 mt-2">
                        {{ $followersCount }} followers
                    </p>

                </div>


                <div class="serif p-6 rounded-xl bg-gradient-to-r from-gray-100 to-gray-50">

                    <p class="text-2xl font-semibold">
                        {{ number_format($analytics['total_comments']) }}
                    </p>

                    <p class="text-sm text-slate-600">
                        Comments
                    </p>

                    <p class="text-xs text-slate-500 mt-2">
                        {{ $followersCount }} followers
                    </p>

                </div>

            </div>


            {{-- STORIES --}}
            <h2 class="text-lg font-semibold text-slate-800">
                My Stories
            </h2>


            <div class="serif grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">

                @foreach ($myStories as $story)
                    <div class="serif bg-white border border-slate-200 rounded-xl p-4 sm:p-5">

                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">

                            <h3 class="font-semibold text-slate-800">
                                {{ $story->title }}
                            </h3>

                            <a href="{{ route('diary.stories.edit', $story) }}" class="text-sm text-slate-500">
                                Edit
                            </a>

                        </div>


                        <p class="text-sm text-slate-500 mt-1">
                            {{ $story->story_reads_count }} reads
                        </p>


                        <div class="flex gap-3 mt-3 text-sm text-slate-500">

                            <span>
                                👍 {{ $story->likes_count }}
                            </span>

                            <span>
                                👁 {{ number_format($story->story_reads_count) }}
                            </span>

                            @if ($story->approval_status == 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded">
                                    Pending
                                </span>
                            @endif

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

        <div id="avatarModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">

            <div class="bg-white rounded-xl p-4 sm:p-6 w-full max-w-[600px] max-h-[85vh] sm:max-h-[500px] overflow-y-auto">

                <h3 class="text-lg font-semibold mb-4">Choose Avatar</h3>

                <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 sm:gap-3">

                    @for ($i = 1; $i <= 28; $i++)
                        <img src="{{ asset('images/avtars/avtar-' . $i . '.png') }}"
                            class="w-12 h-12 sm:w-16 sm:h-16 rounded-full cursor-pointer border hover:border-blue-500 mx-auto"
                            onclick="selectAvatar('images/avtars/avtar-{{ $i }}.png')">
                    @endfor

                </div>

                <hr class="my-4">

                <form action="{{ route('profile.avatar.upload') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <input type="file" name="avatar" class="mb-3 block w-full border p-2 rounded">

                    <button class="bg-blue-500 text-white px-4 py-2 rounded">
                        Upload Image
                    </button>

                </form>

                <button onclick="closeAvatarModal()" class="mt-3 text-sm text-gray-500 hover:text-black">
                    Close
                </button>

            </div>

        </div>

    </div>

    <script>
        function openAvatarModal() {
            document.getElementById('avatarModal').classList.remove('hidden')
            document.getElementById('avatarModal').classList.add('flex')
        }

        function closeAvatarModal() {
            document.getElementById('avatarModal').classList.add('hidden')
        }

        function selectAvatar(avatar) {

            fetch('/profile/avatar/select', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        avatar: avatar
                    })
                })
                .then(res => location.reload())

        }
    </script>
@endsection
