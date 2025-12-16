@extends('layouts.app')

@section('title', 'Start Visit')

@section('content')

<div class="max-w-xl mx-auto p-0 md:p-6">

    <h1 class="text-3xl font-bold text-white mb-6 tracking-wide flex items-center">
        {{-- Lucide Icon: send (representing starting the journey/visit) --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-horizontal mr-3 text-[#ff2ba6]">
            <path d="m3 3 3 9-3 9 19-9Z"/>
            <path d="M6 12h16"/>
        </svg>
        Start a Visit
    </h1>

    {{-- Error Message --}}
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-500/20 border border-red-400/40
                     text-red-100 rounded-xl backdrop-blur-lg flex items-center">
            {{-- Lucide Icon: x-circle --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-2">
                <circle cx="12" cy="12" r="10"/>
                <path d="m15 9-6 6"/>
                <path d="m9 9 6 6"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Form Container --}}
    <div class="bg-white/10 backdrop-blur-xl border border-white/20
                 p-8 rounded-2xl shadow-xl">

        <form id="startVisitForm" method="POST" action="{{ route('salesman.visits.store') }}">
            @csrf

            {{-- Customer --}}
            <label for="customer_id" class="block text-sm text-white/80 mb-1 flex items-center">
                {{-- Lucide Icon: building --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building mr-2">
                    <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                    <path d="M9 22v-4h6v4"/>
                </svg>
                Select Customer
            </label>
            <select name="customer_id" id="customer_id"
                    class="w-full px-4 py-3 mb-4 rounded-lg bg-white/10 text-white
                           placeholder-white/50 focus:bg-white/20 outline-none appearance-none"
                    required>
                <option value="" class="text-black">-- Choose Customer --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" class="text-black">
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>

            {{-- Purpose --}}
            <label for="purpose" class="block text-sm text-white/80 mb-1 flex items-center">
                {{-- Lucide Icon: target --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target mr-2">
                    <circle cx="12" cy="12" r="10"/>
                    <circle cx="12" cy="12" r="6"/>
                    <circle cx="12" cy="12" r="2"/>
                </svg>
                Purpose of Visit
            </label>
            <select name="purpose" id="purpose"
                    class="w-full px-4 py-3 mb-6 rounded-lg bg-white/10 text-white
                           placeholder-white/50 focus:bg-white/20 outline-none appearance-none"
                    required>
                <option value="" class="text-black">-- Select Purpose --</option>
                <option value="Follow-up" class="text-black">Follow-up</option>
                <option value="Product Details" class="text-black">Product Details</option>
                <option value="Order Taking" class="text-black">Order Taking</option>
                <option value="Payment Collection" class="text-black">Payment Collection</option>
                <option value="Complaint Visit" class="text-black">Complaint Visit</option>
                <option value="New Lead Visit" class="text-black">New Lead Visit</option>
            </select>


            {{-- Hidden GPS --}}
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            {{-- Submit Button --}}
            <button
                type="submit"
                class="w-full py-3 rounded-xl text-white font-semibold tracking-wide flex items-center justify-center
                       bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] shadow-lg
                       hover:opacity-90 transition">
                {{-- Lucide Icon: navigation --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-navigation mr-2">
                    <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                </svg>
                Start Visit
            </button>

        </form>

    </div>

</div>

<script>
document.getElementById('startVisitForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;

    // Show loading state and disable button
    submitButton.innerHTML = 'Finding location...';
    submitButton.disabled = true;

    if (!navigator.geolocation) {
        alert("Your browser does not support geolocation.");
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
        return;
    }

    // Geolocation options for better accuracy and timeout
    const options = {
        enableHighAccuracy: true,
        timeout: 1000,
        maximumAge: 0
    };

    navigator.geolocation.getCurrentPosition(function(position) {

        document.getElementById('lat').value = position.coords.latitude;
        document.getElementById('lng').value = position.coords.longitude;

        submitButton.innerHTML = 'Starting Visit...';
        e.target.submit(); // Submit the form

    }, function(error) {

        submitButton.innerHTML = originalText;
        submitButton.disabled = false;

        let errorMessage = "Unable to get your location.";

        switch(error.code) {
            case error.PERMISSION_DENIED:
                errorMessage = "GPS permission denied. Please allow location access in your browser settings.";
                break;

            case error.POSITION_UNAVAILABLE:
                errorMessage = "GPS signal not available. Check your device location services.";
                break;

            case error.TIMEOUT:
                errorMessage = "GPS request timed out. Please check your signal and try again.";
                break;
        }

        // Use a more subtle notification if possible, but alert is the simplest for now
        alert(errorMessage);

    }, options);
});

</script>

@endsection
