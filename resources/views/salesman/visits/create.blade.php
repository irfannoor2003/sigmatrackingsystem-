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
<select name="purpose"
        class="w-full px-4 py-3 mb-6 rounded-lg bg-white/10 text-white
               placeholder-white/50 focus:bg-white/20 outline-none"
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

        switch(error.code) {
            case error.PERMISSION_DENIED:
                alert("GPS permission denied. Please allow location.");
                break;

            case error.POSITION_UNAVAILABLE:
                alert("GPS signal not available.");
                break;

            case error.TIMEOUT:
                alert("GPS request timed out. Try again.");
                break;

            default:
                alert("Unable to get your location.");
        }

    }, { timeout: 10000 });
});

</script>

@endsection
