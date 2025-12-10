@extends('layouts.app')

@section('title', 'Visit Details')

@section('content')

<div class="max-w-4xl mx-auto mt-10 px-4"> <!-- Added px-4 for small screens -->

    <!-- Main Card -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20
                rounded-3xl shadow-2xl p-6 md:p-8"> <!-- Responsive padding -->

        <!-- Heading -->
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-6 tracking-wide">
            Visit Details
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10"> <!-- Responsive gap -->

            <!-- Info Section -->
            <div class="w-full">

                <h3 class="text-xl md:text-2xl font-semibold text-white">
                    {{ $visit->customer->name }}
                </h3>

                <p class="text-indigo-200 font-medium mb-4 capitalize">
                    Visit Purpose: {{ $visit->purpose }}
                </p>

                <!-- Details Table -->
                <div class="space-y-3">

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
                        <span class="text-white/70">Status</span>
                        <span class="text-white capitalize">
                            {{ $visit->status }}
                        </span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
                        <span class="text-white/70">Notes</span>
                        <span class="text-white">
                            {{ $visit->notes ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl text-sm md:text-base">
                        <span class="text-white/70">Visited On</span>
                        <span class="text-white">
                            {{ $visit->created_at->format('d M, Y h:i A') }}
                        </span>
                    </div>

                </div>
            </div>

        </div>

        <!-- Buttons -->
        <div class="mt-10 flex flex-col md:flex-row gap-4"> <!-- Stack on mobile -->
            <button id="showImagesBtn"
                class="w-full md:w-auto px-6 py-3 bg-purple-600/80 hover:bg-purple-600
                       text-white font-semibold rounded-xl shadow-lg transition">
                Preview Document
            </button>

            <button id="closeImagesBtn"
                class="w-full md:w-auto px-6 py-3 bg-red-600/70 hover:bg-red-600
                       text-white font-semibold rounded-xl shadow-lg transition hidden">
                Close Preview
            </button>
        </div>

        <!-- Images Section (Hidden Initially) -->
        <div id="imagesSection" class="mt-6 hidden">

            <h3 class="text-2xl font-semibold text-white mb-4">Uploaded Images</h3>

            @if($visit->images && count($visit->images))
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 md:gap-5"> <!-- Better responsive grid -->
                    @foreach($visit->images as $img)
                        <div
                            class="rounded-xl overflow-hidden bg-white/10 border border-white/10 shadow-lg cursor-pointer preview-image"
                            data-image="{{ asset('storage/' . $img) }}"
                        >
                            <img src="{{ asset('storage/' . $img) }}"
                                 class="w-full h-32 sm:h-40 object-cover"> <!-- Responsive height -->
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-white/60 text-lg">No images available</p>
            @endif

        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center md:text-right">
            <a href="{{ route('salesman.visits.index') }}"
               class="inline-block px-6 py-3 bg-purple-600/80 hover:bg-purple-600
                      text-white font-semibold rounded-xl shadow-lg
                      transition-all duration-200">
                Back to Visits
            </a>
        </div>

    </div>

</div>

<!-- ========================= -->
<!-- IMAGE PREVIEW MODAL -->
<!-- ========================= -->
<div id="imagePreviewModal"
     class="fixed inset-0 bg-black/80 hidden items-center justify-center z-[9999] p-4">

    <div class="relative w-full max-w-3xl mx-auto">

        <!-- Close Button -->
        <button id="closePreview"
            class="absolute top-3 right-3 bg-black text-white px-3 py-1
                   rounded-lg hover:bg-black/40 text-xl md:text-2xl">
            ✕
        </button>

        <!-- Full Image -->
        <img id="previewImage"
             src=""
             class="w-full max-h-[85vh] md:max-h-[90vh] object-contain rounded-2xl shadow-2xl">
    </div>
</div>

<!-- ========================= -->
<!-- JAVASCRIPT -->
<!-- ========================= -->
<script>

    const imagesSection = document.getElementById('imagesSection');
    const showBtn = document.getElementById('showImagesBtn');
    const closeBtn = document.getElementById('closeImagesBtn');

    // SHOW IMAGES
    showBtn.addEventListener('click', () => {
        imagesSection.classList.remove('hidden');
        showBtn.classList.add('hidden');
        closeBtn.classList.remove('hidden');
    });

    // CLOSE IMAGES
    closeBtn.addEventListener('click', () => {
        imagesSection.classList.add('hidden');
        showBtn.classList.remove('hidden');
        closeBtn.classList.add('hidden');
    });

    // FULL SCREEN PREVIEW
    document.querySelectorAll(".preview-image").forEach(imgBox => {
        imgBox.addEventListener("click", () => {
            const src = imgBox.getAttribute("data-image");
            document.getElementById("previewImage").src = src;

            const modal = document.getElementById("imagePreviewModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });
    });

    // CLOSE MODAL
    document.getElementById("closePreview").addEventListener("click", () => {
        const modal = document.getElementById("imagePreviewModal");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    });

    // CLOSE WHEN CLICKING OUTSIDE IMAGE
    document.getElementById("imagePreviewModal").addEventListener("click", (e) => {
        if (e.target.id === "imagePreviewModal") {
            const modal = document.getElementById("imagePreviewModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }
    });

</script>

@endsection
