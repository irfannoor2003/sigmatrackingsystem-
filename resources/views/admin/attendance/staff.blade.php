@extends('layouts.app')

@section('title','Staff Attendance')

@section('content')
@php
    use Carbon\Carbon;

    $displayMonth = Carbon::createFromFormat(
        'Y-m',
        $monthInput ?? now()->format('Y-m')
    )->format('F Y');

    $weeks = collect($calendar)->chunk(7);
@endphp

<style>
/* Month picker icon white */
input[type="month"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
    opacity: 0.9;
    cursor: pointer;
}
</style>

<div class="max-w-6xl mx-auto mt-10 px-4 pb-20">

    {{-- HEADER --}}
    <div class="glass p-6 sm:p-8 rounded-3xl border border-white/20 shadow-2xl mb-6">
        <span class="text-xs font-bold uppercase tracking-widest text-pink-400">
            Personnel Records
        </span>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-2">
            🧑‍💼 {{ $user->name }}
        </h2>

        <p class="text-white/50 text-sm">{{ $user->email }}</p>

        <p class="mt-2 text-sm text-pink-300 font-semibold">
            Role: {{ ucfirst($user->role) }} | Month: {{ $displayMonth }}
        </p>

        <a href="{{ route('admin.attendance.index') }}"
           class="inline-block mt-4 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white border border-white/10">
            ← Back
        </a>
    </div>

    {{-- MONTH FILTER --}}
    <form method="GET"
          class="glass p-5 rounded-3xl border border-white/20 shadow-xl mb-6 flex flex-col sm:flex-row gap-4 items-end">
        <div>
            <label class="text-xs text-white/50 uppercase">Filter Month</label>
            <input type="month" name="month" value="{{ $monthInput }}"
                   class="px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">
        </div>

        <button
            class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a] text-white font-bold">
            Apply
        </button>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- MARK LEAVE --}}
        <div>
            <div class="glass p-6 rounded-3xl border border-white/20 shadow-2xl lg:sticky lg:top-10">
                <h3 class="text-lg font-bold text-white mb-4">🛑 Mark Leave</h3>

                <form method="POST" action="{{ route('admin.attendance.leave', $user->id) }}" class="space-y-4">
                    @csrf

                    <input type="date" name="date" required
                           class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white">

                    <textarea name="note" rows="3" required
                              class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-white"
                              placeholder="Leave reason"></textarea>

                    <button
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a] text-white font-bold">
                        Confirm Leave
                    </button>
                </form>
            </div>
        </div>

        {{-- ATTENDANCE --}}
        <div class="lg:col-span-2 space-y-6">

            @foreach($weeks as $week)
                <div class="glass p-4 sm:p-6 rounded-3xl border border-white/20 shadow-xl">

                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3">
                        @foreach($week as $day)

                            @php
                                $status = $day['status'];
                            @endphp

                            <div class="rounded-2xl p-3 text-center text-xs
                                @if($status === 'present') bg-green-500/20 text-green-300
                                @elseif($status === 'leave') bg-red-500/20 text-red-300
                                @elseif($status === 'off') bg-gray-500/20 text-gray-300
                                @elseif($status === 'future') bg-blue-500/10 text-blue-300
                                @else bg-yellow-500/20 text-yellow-300
                                @endif">

                                <div class="opacity-70">
                                    {{ Carbon::parse($day['date'])->format('D') }}
                                </div>

                                <div class="text-lg font-bold">
                                    {{ Carbon::parse($day['date'])->format('d') }}
                                </div>

                                <div class="mt-1">
                                    {{ $day['label'] }}
                                </div>

                                @if($day['attendance'])
                                    <div class="mt-1 text-[10px] font-mono opacity-80">
                                        {{ $day['attendance']->clock_in
                                            ? Carbon::parse($day['attendance']->clock_in)->format('h:i A')
                                            : '--:--' }}
                                        -
                                        {{ $day['attendance']->clock_out
                                            ? Carbon::parse($day['attendance']->clock_out)->format('h:i A')
                                            : '--:--' }}
                                    </div>
                                @endif
                            </div>

                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
@endsection
