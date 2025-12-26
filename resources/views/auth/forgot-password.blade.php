@extends('layouts.app')

@section('title','Forgot Password')

@section('content')

<div class="flex justify-center items-center py-10">

    <div class="w-full max-w-md p-8 glass rounded-2xl shadow-xl border border-white/10">

        <h2 class="text-3xl font-extrabold text-white text-center tracking-wide mb-2">
            Forgot Password
        </h2>

        <p class="text-center text-gray-300 mb-6 text-sm">
            Enter your email and we’ll send you a password reset link.
        </p>

        {{-- Success Status --}}
        @if (session('status'))
            <div class="mb-4 p-3 rounded-lg
                        bg-green-500/10 border border-green-400/40
                        text-green-300 text-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation Errors --}}
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

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <label class="block text-sm font-medium text-gray-200 mb-1">
                Email
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="w-full px-4 py-3 rounded-lg bg-white/10 text-white
                       placeholder-gray-300 focus:bg-white/20
                       outline-none border
                       {{ $errors->has('email') ? 'border-red-400' : 'border-white/10' }}"
                placeholder="Enter your email"
            />

            @error('email')
                <p class="text-xs text-red-300 mt-2">{{ $message }}</p>
            @enderror

            <!-- Button -->
            <button
                type="submit"
                class="w-full mt-6 py-3 rounded-lg font-bold tracking-wide
                       bg-[var(--hf-magenta)] hover:bg-[var(--hf-magenta-light)]
                       transition-all shadow-lg shadow-[rgba(214,0,123,0.4)]">
                Send Password Reset Link
            </button>
        </form>

        <div class="text-center mt-6 text-sm">
            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white underline">
                Back to Login
            </a>
        </div>

    </div>
</div>

@endsection
