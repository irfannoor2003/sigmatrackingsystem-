@extends('layouts.app')

@section('title', 'Visit Details')

@section('content')

<div class="p-6 text-white">

    <h1 class="text-3xl font-bold mb-6 tracking-wide">Visit Details</h1>

    <div class="bg-white/10 p-6 rounded-2xl backdrop-blur-xl border border-white/20 shadow-xl">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-white/90">

            <!-- Left Section -->
            <div>
                <p class="mb-2"><strong class="text-white">Salesman:</strong> {{ $visit->salesman->name }}</p>
                <p class="mb-2"><strong class="text-white">Customer:</strong> {{ $visit->customer->name }}</p>
                <p class="mb-2"><strong class="text-white">Purpose:</strong> {{ $visit->purpose }}</p>
                <p class="mb-2"><strong class="text-white">Status:</strong> {{ ucfirst($visit->status) }}</p>
            </div>

            <!-- Right Section -->
            <div>
                <p class="mb-2">
                    <strong class="text-white">Started At:</strong>
                    {{ $visit->started_at ? $visit->started_at->format('Y-m-d H:i') : '-' }}
                </p>

                <p class="mb-2">
                    <strong class="text-white">Completed At:</strong>
                    {{ $visit->completed_at ? $visit->completed_at->format('Y-m-d H:i') : '-' }}
                </p>

                <!-- Duration Fixed -->
                <p class="mb-2">
                    <strong class="text-white">Duration:</strong>

                    @if($visit->started_at && $visit->completed_at)
                        @php
                            // Calculate signed difference
                            $signed = $visit->completed_at->diffInMinutes($visit->started_at, false);

                            // Absolute minutes for display
                            $minutes = abs($signed);

                            // Breakdown into hours & minutes
                            $hours = intdiv($minutes, 60);
                            $mins  = $minutes % 60;

                            // Pretty format
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

        <!-- Uploaded Images -->
        <h2 class="text-xl font-semibold mt-8 mb-3 text-white">Uploaded Images</h2>

        @if($visit->images)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($visit->images as $img)
                    <img src="{{ asset('storage/' . $img) }}"
                         class="rounded-xl border border-white/20 shadow hover:scale-105 transition">
                @endforeach
            </div>
        @else
            <p class="text-white/60">No images uploaded</p>
        @endif

    </div>

</div>

@endsection
