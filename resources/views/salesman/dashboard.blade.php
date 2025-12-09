@extends('layouts.app')

@section('title','Salesman Dashboard')

@section('content')

<div class="p-6">

    <!-- Page Title -->
    <h1 class="text-3xl font-bold text-white tracking-wide mb-8">
        Salesman Dashboard
    </h1>

    <!-- Welcome Card -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg">
        <h2 class="text-xl font-semibold text-magenta-300">Welcome!</h2>
        <p class="mt-2 text-white/80">
            Here you can add customers, record visits, and check your daily progress.
        </p>
    </div>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">

        <!-- Add Customer -->
        <a href="{{ route('salesman.customers.create') }}"
           class="block bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-lg p-6 hover:bg-white/20 transition">
            <h3 class="text-xl font-semibold text-magenta-300">Add Customer</h3>
            <p class="text-white/70 mt-2">Register a new customer.</p>
        </a>

        <!-- Add Visit -->
        <a href="{{ route('salesman.visits.create') }}"
           class="block bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-lg p-6 hover:bg-white/20 transition">
            <h3 class="text-xl font-semibold text-magenta-300">Add Visit</h3>
            <p class="text-white/70 mt-2">Record a new visit for today.</p>
        </a>

        <!-- My Visits -->
        <a href="{{ route('salesman.visits.index') }}"
           class="block bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-lg p-6 hover:bg-white/20 transition">
            <h3 class="text-xl font-semibold text-magenta-300">My Visits</h3>
            <p class="text-white/70 mt-2">View all your visit history.</p>
        </a>

    </div>

</div>

@endsection

