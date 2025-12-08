@extends('layouts.app')

@section('title','Create Salesman')

@section('content')

<div class="max-w-xl mx-auto bg-white/10 backdrop-blur-xl border border-white/20
            p-8 rounded-2xl shadow-xl">

    <h2 class="text-2xl font-bold text-white mb-6 tracking-wide">Create Salesman</h2>

    <form method="POST" action="{{ route('admin.salesmen.store') ?? '#' }}">
        @csrf

        <!-- Name -->
        <label class="block text-sm text-white/80 mb-1">Name</label>
        <input
            name="name"
            type="text"
            class="w-full px-4 py-3 mb-4 rounded-lg bg-white/10 text-white
                   placeholder-white/50 focus:bg-white/20 outline-none"
            placeholder="Enter name"
            required
        >

        <!-- Email -->
        <label class="block text-sm text-white/80 mb-1">Email</label>
        <input
            name="email"
            type="email"
            class="w-full px-4 py-3 mb-4 rounded-lg bg-white/10 text-white
                   placeholder-white/50 focus:bg-white/20 outline-none"
            placeholder="Enter email"
            required
        >

        <!-- Password -->
        <label class="block text-sm text-white/80 mb-1">Password</label>
        <input
            name="password"
            type="password"
            class="w-full px-4 py-3 mb-6 rounded-lg bg-white/10 text-white
                   placeholder-white/50 focus:bg-white/20 outline-none"
            placeholder="Enter password"
            required
        >

        <!-- Submit -->
        <button
            class="w-full py-3 rounded-xl text-white font-semibold tracking-wide
                   bg-gradient-to-r from-indigo-500 to-purple-500 shadow-lg
                   hover:opacity-90 transition">
            Create Salesman
        </button>
    </form>

</div>

@endsection
