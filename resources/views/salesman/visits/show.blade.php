@extends('layouts.app')

@section('title', 'Visit Details')

@section('content')

<div class="p-6 text-white">

    <h1 class="text-3xl font-bold mb-6">Visit Details</h1>

    <div class="bg-white/10 p-6 rounded-2xl backdrop-blur-xl">

        <p><strong>Customer:</strong> {{ $visit->customer->name }}</p>
        <p><strong>Purpose:</strong> {{ $visit->purpose }}</p>
        <p><strong>Status:</strong> {{ ucfirst($visit->status) }}</p>
        <p><strong>Notes:</strong> {{ $visit->notes ?? '-' }}</p>

        <h2 class="text-xl font-semibold mt-6 mb-3">Uploaded Images</h2>

        @if($visit->images)
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($visit->images as $img)
                    <img src="{{ asset('storage/' . $img) }}"
                         class="rounded-xl border border-white/20 shadow">
                @endforeach
            </div>
        @else
            <p class="text-white/60">No images uploaded</p>
        @endif

    </div>

</div>

@endsection
