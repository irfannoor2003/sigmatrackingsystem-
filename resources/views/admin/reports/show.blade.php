@extends('layouts.app')

@section('title', 'Visit Details')

@section('content')

<div class="p-4 px-0 md:p-6 text-white">

    <!-- Header -->
    <h1 class="text-3xl font-bold mb-6 tracking-wide">Visit Details</h1>

    <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 p-6 rounded-2xl backdrop-blur-xl border border-white/10 shadow-xl">

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-white/90">

            <!-- Left -->
            <div>
                <p class="mb-3"><strong class="text-white">Salesman:</strong> {{ $visit->salesman->name }}</p>
                <p class="mb-3"><strong class="text-white">Customer:</strong> {{ $visit->customer->name }}</p>
                <p class="mb-3"><strong class="text-white">Purpose:</strong> {{ $visit->purpose }}</p>
                <p class="mb-3"><strong class="text-white">Status:</strong> {{ ucfirst($visit->status) }}</p>
            </div>

            <!-- Right -->
            <div>
                <p class="mb-3">
                    <strong class="text-white">Started At:</strong>
                    {{ $visit->started_at ? $visit->started_at->format('Y-m-d H:i') : '-' }}
                </p>

                <p class="mb-3">
                    <strong class="text-white">Completed At:</strong>
                    {{ $visit->completed_at ? $visit->completed_at->format('Y-m-d H:i') : '-' }}
                </p>

                <!-- Duration -->
                <p class="mb-3">
                    <strong class="text-white">Duration:</strong>

                    @if($visit->started_at && $visit->completed_at)
                        @php
                            $signed = $visit->completed_at->diffInMinutes($visit->started_at, false);
                            $minutes = abs($signed);
                            $hours = intdiv($minutes, 60);
                            $mins  = $minutes % 60;
                            $pretty = $hours > 0 ? "{$hours}h {$mins}m" : "{$mins}m";
                        @endphp

                        <span class="text-white">{{ $pretty }}</span>

                        @if($signed < 0)
                            <span class="ml-2 inline-block text-sm text-yellow-300 bg-yellow-900/40 px-2 py-0.5 rounded-lg">
                                timestamps inconsistent
                            </span>
                        @endif

                    @else
                        <span class="text-white/60">-</span>
                    @endif
                </p>

            </div>

        </div>

        <!-- Notes -->
        <div class="mt-6">
            <p class="text-white mb-2 font-semibold text-lg">Notes:</p>
            <div class="bg-white/5 p-4 rounded-xl border border-white/10">
                {{ $visit->notes ?? 'No notes added.' }}
            </div>
        </div>

        <!-- Images Section -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-3 text-white">Uploaded Images</h2>

            <!-- Button -->
            <button
                onclick="toggleImageModal()"
                class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6]
                       text-white font-semibold shadow hover:opacity-90 transition">
                Preview Documents
            </button>
<div class="mt-8 text-center md:text-right">
            <a href="{{ route('admin.reports.index') }}"
               class="inline-block px-6 py-3 bg-purple-600/80 hover:bg-purple-600
                      text-white font-semibold rounded-xl shadow-lg transition">
                Back to Visits
            </a>
        </div>
            <!-- If no images -->
            @if(!$visit->images || count($visit->images) == 0)
                <p class="text-white/60 mt-3">No images available</p>
            @endif
        </div>

    </div>
</div>

<!-- Image Modal -->
<div id="imageModal"
     class="fixed inset-0 bg-black/70 backdrop-blur-lg hidden justify-center items-center p-6 z-50">

    <div class="bg-gray-900 border border-white/10 rounded-2xl p-5 max-w-4xl w-full shadow-xl">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-white">Uploaded Images</h3>

            <button onclick="toggleImageModal()"
                    class="text-white text-lg hover:text-red-400">
                ✕
            </button>
        </div>

        @if($visit->images && count($visit->images) > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($visit->images as $img)
                    <img src="{{ asset('storage/' . $img) }}"
                         class="rounded-xl border border-white/20 shadow hover:scale-105 transition">
                @endforeach
            </div>
        @else
            <p class="text-white/60 text-center py-10">No Images Available</p>
        @endif

    </div>
</div>

<script>
    function toggleImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
    }
</script>

@endsection
