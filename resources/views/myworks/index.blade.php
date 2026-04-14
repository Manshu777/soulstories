@extends('layouts.app', ['hideNavbar' => true, 'hideFooter' => true])
@section('content')

<div class="min-h-screen bg-[#fafafa]">

    <!-- Top Bar -->
    <div class="border-b sticky top-0 bg-white z-50">
        <div class="max-w-6xl mx-auto flex justify-between items-center px-6 py-5">
            <h1 class="text-lg font-semibold tracking-tight">
                Create Story
            </h1>

            <div class="flex justify-center items-center gap-3">
                <a href="/dashboard"
                   class="text-sm text-gray-500 hover:text-black transition">
                    Cancel
                </a>

                <button
                    class="px-5 py-2 text-sm rounded-full bg-orange-500 text-white hover:bg-orange-600 transition shadow-sm">
                    Publish
                </button>
            </div>
        </div>
    </div>


    <!-- Layout -->
    <div class="max-w-6xl mx-auto px-6 py-14">
        <div class="grid md:grid-cols-[30%_70%] gap-16">

            <!-- LEFT COLUMN -->
            <div class="space-y-6">

                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                    Cover Image
                </h2>

                <label class="relative block w-full h-[500px] border border-dashed border-gray-300 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-orange-400 transition group bg-white shadow-sm overflow-hidden">

                    <!-- Preview Image -->
                    <img id="imagePreview" class="absolute inset-0 w-full h-full object-cover hidden" />

                    <div id="uploadContent" class="flex flex-col items-center justify-center">
                        <svg class="w-10 h-10 text-gray-300 group-hover:text-orange-400 transition"
                            fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 16l4-4a3 3 0 014 0l4 4m0 0l4-4m-4 4V4" />
                        </svg>

                        <span class="mt-4 text-sm text-gray-400 group-hover:text-orange-500 transition">
                            Click to upload cover
                        </span>
                    </div>

                    <input type="file" id="coverInput" class="hidden" accept="image/*">
                </label>
            </div>


            <!-- RIGHT COLUMN -->
            <div class="space-y-10">

                <!-- Title -->
                 <div class="space-y-3">
    <div class="flex items-center gap-2">

        <label class="text-sm font-medium text-gray-700">
            Title
        </label>

        <!-- Info Icon -->
        <div class="relative group cursor-pointer">
            <svg class="w-4 h-4 text-gray-400 hover:text-orange-500 transition"
                 fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>

            <!-- Tooltip -->
            <div class="absolute bottom-7 left-1/2 -translate-x-1/2 
                        bg-black text-white text-xs px-3 py-2 rounded-md 
                        opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-50">
                Choose a catchy and unique title (5–80 characters)
            </div>
        </div>

    </div>

    <input type="text"
        placeholder="Your story title"
        class="w-full border-b border-gray-300 bg-transparent py-3 text-xl focus:outline-none focus:border-orange-500 transition">
</div>


                <!-- Description -->
                <div class="space-y-3">
                    <label class="text-sm font-medium text-gray-700">Description</label>
                    <textarea rows="4"
                        placeholder="Write a short description..."
                        class="w-full border-b border-gray-300 bg-transparent py-3 focus:outline-none focus:border-orange-500 transition"></textarea>
                </div>


                <!-- Category -->
                <div class="space-y-3">
                    <label class="text-sm font-medium text-gray-700">Category</label>
                    <select class="w-full border-b border-gray-300 bg-transparent py-3 focus:outline-none focus:border-orange-500 transition">
                        <option>Select category</option>
                        <option>Romance</option>
                        <option>Thriller</option>
                        <option>Fantasy</option>
                        <option>Horror</option>
                    </select>
                </div>


                <!-- Tags -->
                <div class="space-y-3">
                    <label class="text-sm font-medium text-gray-700">Tags</label>

                    <div class="border-b border-gray-300 py-2">
                        <div id="tagsContainer" class="flex flex-wrap gap-2"></div>

                        <input id="tagInput"
                               type="text"
                               placeholder="Type tag and press Enter"
                               class="w-full bg-transparent focus:outline-none mt-2">
                    </div>
                </div>


                <!-- Language -->
                <div class="space-y-3">
                    <label class="text-sm font-medium text-gray-700">Language</label>
                    <select class="w-full border-b border-gray-300 bg-transparent py-3 focus:outline-none focus:border-orange-500 transition">
                        <option>English</option>
                        <option>Hindi</option>
                        <option>Hinglish</option>
                    </select>
                </div>


                <!-- Publishing Options -->
                <div class="pt-6 border-t space-y-6">

                    <h2 class="text-xs uppercase tracking-wide text-gray-400">
                        Publishing Options
                    </h2>

                    <!-- Toggle Template -->
                    @php
                        $options = [
                            ['label' => 'Public Story', 'tooltip' => 'Everyone can see this story'],
                            ['label' => 'Mature Content', 'tooltip' => 'Mark if story contains adult themes'],
                            ['label' => 'Enable Monetization', 'tooltip' => 'Earn money from this story'],
                        ];
                    @endphp

                    @foreach($options as $option)
                    <div class="flex items-center justify-between">

                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-700">{{ $option['label'] }}</span>

                            <!-- Info Tooltip -->
                            <div class="relative group cursor-pointer">
                                <svg class="w-4 h-4 text-gray-400 hover:text-orange-500"
                                     fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 16v-4M12 8h.01"/>
                                </svg>

                                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 
                                            bg-black text-white text-xs px-3 py-2 rounded-md 
                                            opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                                    {{ $option['tooltip'] }}
                                </div>
                            </div>
                        </div>

                        <!-- Toggle Switch -->
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 rounded-full peer 
                                        peer-checked:bg-orange-500 transition"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white 
                                        rounded-full transition peer-checked:translate-x-5"></div>
                        </label>

                    </div>
                    @endforeach

                </div>

            </div>

        </div>
    </div>
</div>


<!-- Scripts -->
<script>

    // Image Preview
    const coverInput = document.getElementById("coverInput");
    const imagePreview = document.getElementById("imagePreview");
    const uploadContent = document.getElementById("uploadContent");

    coverInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove("hidden");
                uploadContent.classList.add("hidden");
            };
            reader.readAsDataURL(file);
        }
    });


    // Tag System
    const tagInput = document.getElementById("tagInput");
    const tagsContainer = document.getElementById("tagsContainer");

    tagInput.addEventListener("keydown", function(e) {
        if (e.key === "Enter" && tagInput.value.trim() !== "") {
            e.preventDefault();

            const tag = document.createElement("span");
            tag.className = "flex items-center gap-2 bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-sm";

            tag.innerHTML = `
                ${tagInput.value}
                <button type="button" class="text-orange-500 hover:text-red-500 font-bold">&times;</button>
            `;

            tag.querySelector("button").addEventListener("click", () => {
                tag.remove();
            });

            tagsContainer.appendChild(tag);
            tagInput.value = "";
        }
    });

</script>

@endsection