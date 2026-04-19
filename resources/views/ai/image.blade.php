@extends('layouts.app', ['hideFooter' => true])

@section('content')
<div class="h-[90vh] max-w-5xl mx-auto flex flex-col gap-4 p-6 relative">
    
    <div class="flex items-center justify-between pb-4 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">AI Image Generator</h1>
        
        <div id="loader" class="hidden flex items-center gap-2 text-blue-600 font-medium">
            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Generating...
        </div>
    </div>

    <div class="flex-1 overflow-y-auto flex flex-col gap-6 p-4 bg-gray-50 rounded-xl border border-gray-100" id="main">
        <div class="text-center text-gray-400 mt-10" id="empty-state">
            Enter a prompt below to generate your first image.
        </div>
    </div>

    <div class="w-full flex gap-3 pt-2">
        <input 
            type="text" 
            id="prompt" 
            placeholder="Describe the image you want to create..." 
            class="w-full border border-gray-300 rounded-lg p-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-500"
            onkeypress="handleKeyPress(event)"
        >
        <button 
            id="generateBtn"
            onclick="generateImage()" 
            class="bg-blue-600 text-white font-semibold px-8 py-4 rounded-lg shadow-sm hover:bg-blue-700 transition-colors disabled:bg-blue-400 disabled:cursor-not-allowed"
        >
            Generate
        </button>
    </div>

</div>

<script>
async function generateImage() {
    const promptInput = document.getElementById("prompt");
    const promptText = promptInput.value.trim();
    const loader = document.getElementById("loader");
    const main = document.getElementById("main");
    const generateBtn = document.getElementById("generateBtn");
    const emptyState = document.getElementById("empty-state");

    if (!promptText) {
        alert("Please enter a valid prompt.");
        return;
    }

    // 1. Set Loading State
    loader.classList.remove("hidden");
    promptInput.disabled = true;
    generateBtn.disabled = true;

    if (emptyState) {
        emptyState.style.display = 'none';
    }

    try {
        const response = await fetch("{{ route('generate.image') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ prompt: promptText })
        });

        const data = await response.json();

        if (data.error) {
            alert(data.error);
            return;
        }

        // Support both direct URLs and Base64 strings safely
        const isBase64 = !!data?.artifacts?.[0]?.base64;
        const imageUrl = isBase64 ? data.artifacts[0].base64 : data?.image_url;

        if (imageUrl) {
            const finalImageSrc = isBase64 ? `data:image/png;base64,${imageUrl}` : imageUrl;

            // Create a nice card wrapper for the result
            const resultCard = document.createElement("div");
            resultCard.className = "bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col gap-3 w-fit";

            // Add the prompt text above the image
            const promptLabel = document.createElement("p");
            promptLabel.className = "text-sm text-gray-700 font-medium bg-gray-100 px-3 py-2 rounded-md";
            promptLabel.innerText = "✨ " + promptText;

            // --- NEW: Image Container for Overlay ---
            const imageContainer = document.createElement("div");
            imageContainer.className = "relative group w-fit rounded-lg overflow-hidden"; // 'group' enables hover effects

            // Add the actual image
            const image = document.createElement("img");
            image.src = finalImageSrc;
            image.className = "block max-w-full md:max-w-md h-auto object-contain";
            image.alt = promptText;

            // --- NEW: Download Button Overlay ---
            const downloadBtn = document.createElement("button");
            // Hidden by default (opacity-0), visible on hover (group-hover:opacity-100)
            downloadBtn.className = "absolute top-2 right-2 bg-black/60 hover:bg-black/80 text-white p-2.5 rounded-lg backdrop-blur-sm transition-all opacity-0 group-hover:opacity-100 shadow-lg";
            downloadBtn.title = "Download Image";
            downloadBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
            `;
            
            // Bind the download function
            downloadBtn.onclick = () => triggerDownload(finalImageSrc, promptText);

            // Append elements together
            imageContainer.appendChild(image);
            imageContainer.appendChild(downloadBtn);

            resultCard.appendChild(promptLabel);
            resultCard.appendChild(imageContainer);
            main.appendChild(resultCard);

            // Auto-scroll to the bottom to see the new image
            main.scrollTop = main.scrollHeight;

            // Clear the input field for the next prompt
            promptInput.value = "";
        } else {
            alert("Image generated, but URL format is unknown.");
        }

    } catch (error) {
        console.error("Image generation failed:", error);
        alert("An error occurred while generating the image. Please try again.");
    } finally {
        // 2. Restore UI State
        loader.classList.add("hidden");
        promptInput.disabled = false;
        generateBtn.disabled = false;
        promptInput.focus();
    }
}

// --- NEW: Helper function to force download ---
async function triggerDownload(imageSrc, promptText) {
    try {
        // Create a safe, clean filename based on the prompt
        const safeName = promptText.replace(/[^a-z0-9]/gi, '_').toLowerCase().substring(0, 30);
        const filename = `ai_image_${safeName}.png`;

        // Fetch the image as a Blob. This forces the browser to download it 
        // instead of just opening it in a new tab.
        const response = await fetch(imageSrc);
        const blob = await response.blob();
        const blobUrl = URL.createObjectURL(blob);

        // Create a temporary hidden link to trigger the download
        const link = document.createElement("a");
        link.href = blobUrl;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        
        // Clean up the DOM and memory
        document.body.removeChild(link);
        URL.revokeObjectURL(blobUrl);

    } catch (error) {
        console.error("Download failed via Blob, attempting fallback...", error);
        // Fallback for strict CORS restrictions
        const link = document.createElement("a");
        link.href = imageSrc;
        link.download = "generated_image.png";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Allow generating by pressing "Enter"
function handleKeyPress(event) {
    if (event.key === "Enter") {
        generateImage();
    }
}
</script>
@endsection