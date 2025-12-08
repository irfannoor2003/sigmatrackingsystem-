@extends('layouts.app')

@section('title','Add Customer')

@section('content')

<div class="max-w-xl mx-auto p-6">

    <h1 class="text-3xl font-extrabold text-white mb-6 tracking-wide">
        Add New Customer
    </h1>

    <div class="glass p-6 rounded-2xl border border-white/20 shadow-xl">

        <form method="POST" action="{{ route('admin.customers.store') }}">
            @csrf

            <!-- Customer Name -->
            <label class="block text-white/80 text-sm mb-1">Customer Name</label>
            <input
                type="text"
                name="name"
                placeholder="Enter customer name"
                required
                class="w-full p-3 mb-4 rounded-lg bg-white/10 text-white placeholder-white/50
                       focus:bg-white/20 outline-none transition"
            >

            <!-- Phone -->
            <label class="block text-white/80 text-sm mb-1">Phone</label>
            <input
                type="text"
                name="phone1"
                placeholder="Enter phone"
                required
                class="w-full p-3 mb-4 rounded-lg bg-white/10 text-white placeholder-white/50
                       focus:bg-white/20 outline-none transition"
            >

            <!-- City -->
            <label class="block text-white/80 text-sm mb-1">City</label>
            <input
                type="text"
                name="city"
                placeholder="Enter city"
                class="w-full p-3 mb-6 rounded-lg bg-white/10 text-white placeholder-white/50
                       focus:bg-white/20 outline-none transition"
            >

            <!-- Save Button -->
            <button
                class="w-full py-3 rounded-xl bg-magenta text-white font-bold tracking-wide
                       shadow-lg hover:bg-magenta-light transition-all duration-300">
                Save Customer
            </button>

        </form>

    </div>

</div>

@endsection
