@extends('layouts.app')

@section('title','Login')

@section('content')

<div class="flex justify-center items-center py-10">

    <div class="w-full max-w-md p-8 glass rounded-2xl shadow-xl border border-white/10">

        <h2 class="text-3xl font-extrabold text-white text-center tracking-wide mb-2">
            Welcome Back
        </h2>

        <p class="text-center text-gray-300 mb-6">
            Login to continue
        </p>

        {{-- Validation / Auth Errors --}}
@if ($errors->any())
    <div class="mb-4 p-3 rounded-lg
                bg-red-500/10 border border-red-400/40
                text-red-300 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <label class="block text-sm font-medium text-gray-200 mb-1">Email</label>
            <input
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white placeholder-gray-300
                       focus:bg-white/20 outline-none border border-white/10 mb-4"
                placeholder="Enter your email"
            />

            <!-- Password -->
            <label class="block text-sm font-medium text-gray-200 mb-1">Password</label>
            <input
                name="password"
                type="password"
                required
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white placeholder-gray-300
                       focus:bg-white/20 outline-none border border-white/10 mb-4"
                placeholder="Enter your password"
            />

            <!-- Remember + Forgot -->
            {{-- <div class="flex items-center justify-between mb-4 text-gray-200 text-sm">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="underline hover:text-white">
                    Forgot?
                </a>
            </div> --}}

            <!-- Button -->
            <button
                type="submit"
                class="w-full py-3 rounded-lg font-bold tracking-wide
                       bg-[var(--hf-magenta)] hover:bg-[var(--hf-magenta-light)]
                       transition-all shadow-lg shadow-[rgba(214,0,123,0.4)]">
                Sign In
            </button>
        </form>

        <p class="text-center text-gray-300 text-sm mt-6">
            Need an account? Contact admin.
        </p>

    </div>
</div>

@endsection
