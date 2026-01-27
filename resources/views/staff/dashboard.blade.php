@extends('layouts.app')

@section('title','Staff Dashboard')

@section('content')

<div class="p-0 md:p-6">

    <!-- Page Title -->
    <h1 class="text-3xl font-bold text-white tracking-wide mb-8">
        Staff Dashboard
    </h1>

    <!-- Welcome Card -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg flex items-center gap-4">
        <i data-lucide="briefcase" class="w-8 h-8 text-pink-500"></i>
        <div>
            <h2 class="text-xl font-semibold text-magenta-300">
                Welcome, {{ auth()->user()->name }} 👋
            </h2>
            <p class="mt-2 text-white/80">
                Mark your attendance and view your work history.
            </p>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">

        <!-- My Attendance -->
        <a href="{{ route('staff.attendance.index') }}"
           class="block bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-lg p-6 hover:bg-white/20 transition border-l-4"
           style="border-left-color:#ff2ba6;">
            <div class="flex items-center gap-3">
                <i data-lucide="clock" class="w-7 h-7 text-pink-500"></i>
                <h3 class="text-xl font-semibold text-magenta-300">
                    My Attendance
                </h3>
            </div>
            <p class="text-white/70 mt-2">
                Clock in / out for today.
            </p>
        </a>

        <!-- Request Leave -->
        <a href="{{ route('staff.attendance.index') }}"
           class="block bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-lg p-6 hover:bg-white/20 transition border-l-4"
           style="border-left-color:#ff2ba6;">
            <div class="flex items-center gap-3">
                <i data-lucide="calendar-plus" class="w-7 h-7 text-pink-500"></i>
                <h3 class="text-xl font-semibold text-magenta-300">
                    Request Leave
                </h3>
            </div>
            <p class="text-white/70 mt-2">
                Submit a leave request.
            </p>
        </a>

        <!-- View Attendance History -->
        <a href="{{ route('staff.attendance.history') }}"
           class="block bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-lg p-6 hover:bg-white/20 transition border-l-4"
           style="border-left-color:#ff2ba6;">
            <div class="flex items-center gap-3">
                <i data-lucide="clipboard-list" class="w-7 h-7 text-pink-500"></i>
                <h3 class="text-xl font-semibold text-magenta-300">
                    Attendance History
                </h3>
            </div>
            <p class="text-white/70 mt-2">
                View your previous records.
            </p>
        </a>

    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons();
    });
</script>

@endsection
