@extends('layouts.app')

@section('title', 'Start Visit')

@section('content')

<div class="max-w-xl mx-auto p-6">

    <h1 class="text-3xl font-bold text-white mb-6 tracking-wide">Start a Visit</h1>

    {{-- Error Message --}}
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-500/20 border border-red-400/40
                    text-red-100 rounded-xl backdrop-blur-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Form Container --}}
    <div class="bg-white/10 backdrop-blur-xl border border-white/20
                p-8 rounded-2xl shadow-xl">

        <form id="startVisitForm" method="POST" action="{{ route('salesman.visits.store') }}">
            @csrf

            {{-- Customer --}}
            <label class="block text-sm text-white/80 mb-1">Select Customer</label>
            <select name="customer_id"
                    class="w-full px-4 py-3 mb-4 rounded-lg bg-white/10 text-white
                           placeholder-white/50 focus:bg-white/20 outline-none"
                    required>
                <option value="" class="text-black">-- Choose Customer --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" class="text-black">
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>

            {{-- Purpose --}}
            <label class="block text-sm text-white/80 mb-1">Purpose of Visit</label>
            <input type="text"
                   name="purpose"
                   class="w-full px-4 py-3 mb-6 rounded-lg bg-white/10 text-white
                          placeholder-white/50 focus:bg-white/20 outline-none"
                   placeholder="Enter purpose"
                   required>

            {{-- Hidden GPS --}}
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            {{-- Submit Button --}}
            <button
                type="submit"
                class="w-full py-3 rounded-xl text-white font-semibold tracking-wide
                       bg-gradient-to-r from-indigo-500 to-purple-500 shadow-lg
                       hover:opacity-90 transition">
                Start Visit
            </button>

        </form>

    </div>

</div>

<script>
document.getElementById('startVisitForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!navigator.geolocation) {
        alert("Your browser does not support geolocation.");
        return;
    }

    navigator.geolocation.getCurrentPosition(function(position) {
        document.getElementById('lat').value = position.coords.latitude;
        document.getElementById('lng').value = position.coords.longitude;
        e.target.submit();
    }, function(error) {
        alert("Unable to get your location. Make sure location services are enabled.");
    });
});
</script>

@endsection
