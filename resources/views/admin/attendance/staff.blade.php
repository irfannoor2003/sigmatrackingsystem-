@extends('layouts.app')

@section('title','Staff Attendance')

@section('content')
@php
    $displayMonth = \Carbon\Carbon::createFromFormat(
        'Y-m',
        $monthInput ?? now()->format('Y-m')
    )->format('F Y');
@endphp

<div class="max-w-6xl mx-auto mt-10 px-4 pb-20">

    {{-- Header --}}
    <div class="relative overflow-hidden glass p-8 rounded-[2rem] border border-white/20 shadow-2xl mb-8">
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-pink-400 mb-2 block">
                    Personnel Records
                </span>

                <h2 class="text-3xl font-extrabold text-white flex items-center gap-3">
                    <span class="bg-white/10 p-2 rounded-2xl">🧑‍💼</span>
                    {{ $user->name }}
                </h2>

                <p class="text-white/50 text-sm mt-2">
                    {{ $user->email }}
                </p>

                <p class="mt-2 text-sm text-pink-300 font-semibold">
                    Role: {{ ucfirst($user->role) }} | Month: {{ $displayMonth }}
                </p>
            </div>

            <a href="{{ route('admin.attendance.index') }}"
               class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10
                      text-white text-sm border border-white/10">
                Back to List
            </a>
        </div>
    </div>

    {{-- MONTH FILTER --}}
    <form method="GET" class="glass p-6 rounded-[2rem] border border-white/20 shadow-xl mb-8 flex gap-4 items-end">
        <div>
            <label class="block text-xs text-white/50 mb-1 uppercase tracking-wider">
                Filter Month
            </label>
            <input type="month"
                   name="month"
                   value="{{ $monthInput }}"
                   class="px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">
        </div>

        <button class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a]
                       text-white font-bold">
            Apply
        </button>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- MARK LEAVE --}}
        <div class="lg:col-span-1">
            <div class="glass p-8 rounded-[2rem] border border-white/20 shadow-2xl sticky top-10">
                <h3 class="text-xl font-bold text-white mb-6">
                    🛑 Mark Leave
                </h3>

                <form method="POST"
                      action="{{ route('admin.attendance.leave', $user->id) }}"
                      class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs text-white/50 mb-1">Leave Date</label>
                        <input type="date" name="date" required
                               class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">
                    </div>

                    <div>
                        <label class="block text-xs text-white/50 mb-1">Leave Reason</label>
                        <textarea name="note" rows="4" required
                                  class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white"
                                  placeholder="Sick leave, personal work, emergency..."></textarea>
                    </div>

                    <button class="w-full py-4 rounded-xl
                                   bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a]
                                   text-white font-bold">
                        Confirm Leave
                    </button>

                    <p class="text-[11px] text-white/40 text-center">
                        ⚠ This will override existing clock-in/out
                    </p>
                </form>
            </div>
        </div>

        {{-- ATTENDANCE TABLE --}}
        <div class="lg:col-span-2">
            <div class="glass rounded-[2rem] border border-white/20 shadow-2xl overflow-hidden">

                <div class="p-8 border-b border-white/10 bg-white/5 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-white">
                        📅 Attendance History
                    </h3>
                    <span class="text-xs bg-pink-400/20 text-pink-200 px-3 py-1 rounded-full">
                        {{ $attendances->count() }} Records
                    </span>
                </div>

                <div class="overflow-x-auto px-4 pb-6">
                    <table class="w-full text-sm text-white border-separate border-spacing-y-3">

                        <thead class="text-white/40 uppercase text-[11px] tracking-widest font-bold">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3">Clock In / Out</th>
                                <th class="px-4 py-3 text-right">Work Time</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($attendances as $attendance)
                            <tr class="bg-white/5 hover:bg-white/10 transition">

                                {{-- DATE --}}
                                <td class="px-4 py-4 rounded-l-2xl font-medium">
                                    {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                    <div class="text-[10px] text-white/30">
                                        {{ \Carbon\Carbon::parse($attendance->date)->format('l') }}
                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td class="px-4 py-4 text-center">
                                    @if($attendance->status === 'leave')
                                        <span class="px-3 py-1.5 rounded-lg text-[10px]
                                                     bg-red-500/10 text-red-400
                                                     border border-red-500/20 font-bold">
                                            Leave
                                        </span>

                                        @if($attendance->note)
                                            <div class="mt-1 text-[10px] text-white/40 italic">
                                                "{{ $attendance->note }}"
                                            </div>
                                        @endif

                                    @elseif($attendance->clock_in && !$attendance->clock_out)
                                        <span class="px-3 py-1.5 rounded-lg text-[10px]
                                                     bg-pink-400/10 text-pink-300
                                                     border border-pink-400/20 font-bold animate-pulse">
                                            Working
                                        </span>
                                    @else
                                        <span class="px-3 py-1.5 rounded-lg text-[10px]
                                                     bg-pink-500/10 text-pink-400
                                                     border border-pink-500/20 font-bold">
                                            Completed
                                        </span>
                                    @endif
                                </td>

                                {{-- CLOCK --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2 font-mono">
                                        <span>
                                            {{ $attendance->clock_in
                                                ? \Carbon\Carbon::parse($attendance->clock_in)->format('h:i A')
                                                : '--:--' }}
                                        </span>
                                        <span class="text-white/30">→</span>
                                        <span class="text-white/60">
                                            {{ $attendance->clock_out
                                                ? \Carbon\Carbon::parse($attendance->clock_out)->format('h:i A')
                                                : '--:--' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- DURATION --}}
                                <td class="px-4 py-4 rounded-r-2xl text-right font-bold text-pink-300">
                                    {{ $attendance->working_duration ?? '00:00' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center text-white/40">
                                    No attendance records found for this month.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
