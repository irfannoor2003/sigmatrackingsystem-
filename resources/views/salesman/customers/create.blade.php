@extends('layouts.app')
@section('title', 'Add Customer')

@section('content')

<div class="max-w-xl mx-auto bg-white/10 backdrop-blur-xl border border-white/20
            p-8 rounded-2xl shadow-xl">

    <h2 class="text-2xl font-bold text-white mb-6 tracking-wide">Add Customer</h2>

    <form action="{{ route('salesman.customers.store') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Customer Name -->
        <div>
            <label class="block text-sm text-white/80 mb-1">Customer Name</label>
            <input
                name="name"
                type="text"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                placeholder="Enter customer name"
                required>
        </div>

        <!-- Contact Person -->
        <div>
            <label class="block text-sm text-white/80 mb-1">Contact Person</label>
            <input
                name="contact_person"
                type="text"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                placeholder="Enter contact person">
        </div>

        <!-- Phone 1 -->
        <div>
            <label class="block text-sm text-white/80 mb-1">Phone 1</label>
            <input
                name="phone1"
                type="text"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                placeholder="Primary phone number"
                required>
        </div>

        <!-- Phone 2 -->
        <div>
            <label class="block text-sm text-white/80 mb-1">Phone 2</label>
            <input
                name="phone2"
                type="text"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                placeholder="Optional phone number">
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm text-white/80 mb-1">Email</label>
            <input
                name="email"
                type="email"
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                       placeholder-white/50 focus:bg-white/20 outline-none"
                placeholder="Enter email address">
        </div>

        <!-- Submit Button -->
        <button
            class="w-full py-3 rounded-xl text-white font-semibold tracking-wide
                   bg-gradient-to-r from-indigo-500 to-purple-500 shadow-lg
                   hover:opacity-90 transition">
            Save Customer
        </button>


    </form>

</div>

@endsection
