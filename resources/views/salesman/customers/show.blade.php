@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-4">

    <!-- Main Card -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20
                rounded-3xl shadow-2xl p-6 md:p-8">

        <!-- Heading -->
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-6 tracking-wide">
            Customer Details
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">

            <!-- Info Section -->
            <div>
                <h3 class="text-xl md:text-2xl font-semibold text-white">
                    {{ $customer->name }}
                </h3>

                <p class="text-indigo-200 font-medium mb-4 capitalize">
                    {{ $customer->category->name ?? 'No Category' }}
                </p>

                <!-- Details -->
                <div class="space-y-3">

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Contact Person</span>
                        <span class="text-white">{{ $customer->contact_person ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Phone 1</span>
                        <span class="text-white">{{ $customer->phone1 }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Phone 2</span>
                        <span class="text-white">{{ $customer->phone2 ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Email</span>
                        <span class="text-white">{{ $customer->email ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">City</span>
                        <span class="text-white capitalize">{{ $customer->city?->name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Industry</span>
                        <span class="text-white">{{ $customer->industry?->name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Address</span>
                        <span class="text-white">{{ $customer->address ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Added On</span>
                        <span class="text-white">{{ $customer->created_at->format('d M, Y') }}</span>
                    </div>

                </div>

                <!-- BUTTONS -->
                <div class="mt-6 flex gap-4">
                    <button id="showImageBtn"
                        class="px-5 py-3 bg-purple-600/80 hover:bg-purple-600
                               text-white font-semibold rounded-xl shadow-lg transition">
                        Preview Document
                    </button>

                    <button id="closeImageBtn"
                        class="px-5 py-3 bg-red-600/70 hover:bg-red-600
                               text-white font-semibold rounded-xl shadow-lg transition hidden">
                        Close Preview
                    </button>
                </div>
            </div>
        </div>

        <!-- IMAGE AREA (HIDDEN BY DEFAULT) -->
        <div id="imageDisplaySection" class="mt-8 hidden">

            <h3 class="text-2xl font-semibold text-white mb-4">Customer Document</h3>

            @if ($customer->image)
                <div class="rounded-xl overflow-hidden bg-white/10 border border-white/10 shadow-lg cursor-pointer"
                     id="imageBox"
                     data-image="{{ asset('storage/' . $customer->image) }}">

                    <img src="{{ asset('storage/' . $customer->image) }}"
                         class="w-full h-64 object-cover rounded-xl">
                </div>
            @else
                <p class="text-white/60 text-lg">No document uploaded</p>
            @endif

        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center md:text-right">
            <a href="{{ route('salesman.customers.index') }}"
                class="inline-block px-6 py-3 bg-purple-600/80 hover:bg-purple-600
                      text-white font-semibold rounded-xl shadow-lg transition">
                Back to Customers
            </a>
        </div>

    </div>
</div>

<!-- ===================== FULLSCREEN PREVIEW MODAL ===================== -->
<div id="imagePreviewModal"
     class="fixed inset-0 bg-black/80 hidden items-center justify-center z-[9999] p-4">

    <div class="relative w-full max-w-3xl mx-auto">

        <!-- Close Button -->
        <button id="closePreview"
            class="absolute top-3 right-3 bg-black text-white px-3 py-1
                   rounded-lg hover:bg-black/40 text-xl md:text-2xl">
            ✕
        </button>

        <!-- Image -->
        <img id="previewImage" src=""
             class="w-full max-h-[85vh] md:max-h-[90vh] object-contain rounded-2xl shadow-2xl">
    </div>
</div>

<!-- ========================== JAVASCRIPT ========================== -->
<script>
    const showBtn = document.getElementById('showImageBtn');
    const closeBtn = document.getElementById('closeImageBtn');
    const section = document.getElementById('imageDisplaySection');

    // SHOW DOCUMENT
    showBtn.addEventListener('click', () => {
        section.classList.remove('hidden');
        showBtn.classList.add('hidden');
        closeBtn.classList.remove('hidden');
    });

    // HIDE DOCUMENT
    closeBtn.addEventListener('click', () => {
        section.classList.add('hidden');
        showBtn.classList.remove('hidden');
        closeBtn.classList.add('hidden');
    });

    // FULLSCREEN PREVIEW
    const imageBox = document.getElementById("imageBox");

    if (imageBox) {
        imageBox.addEventListener("click", () => {
            document.getElementById("previewImage").src =
                imageBox.getAttribute("data-image");

            const modal = document.getElementById("imagePreviewModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });
    }

    // CLOSE MODAL
    document.getElementById("closePreview").addEventListener("click", () => {
        const modal = document.getElementById("imagePreviewModal");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    });

    // CLICK OUTSIDE TO CLOSE
    document.getElementById("imagePreviewModal").addEventListener("click", (e) => {
        if (e.target.id === "imagePreviewModal") {
            const modal = document.getElementById("imagePreviewModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }
    });
</script>

@endsection
