@extends('layouts.app')

@section('title','Attendance History')

@section('content')
<div class="max-w-5xl mx-auto mt-10 px-4">

<div class="glass p-6 rounded-3xl border border-white/20 shadow-2xl">

    <h2 class="text-2xl font-bold text-white mb-6">
        📅 Monthly Attendance
    </h2>

    <form method="GET" class="mb-6">
        <input type="month" name="month" value="{{ $month }}"
               class="px-4 py-2 rounded-xl bg-black/30 border border-white/20 text-white">
        <button class="ml-2 px-4 py-2 rounded-xl bg-pink-500/80 text-white font-semibold">
            Filter
        </button>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-white/10">
    <table class="min-w-full text-sm text-white">
        <thead class="bg-white/10 text-white/70">
            <tr>
                <th class="px-4 py-3 text-left font-semibold">Date</th>
                <th class="px-4 py-3 text-left font-semibold">Status</th>
                <th class="px-4 py-3 text-left font-semibold">Clock In</th>
                <th class="px-4 py-3 text-left font-semibold">Clock Out</th>
                <th class="px-4 py-3 text-left font-semibold">Work Time</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-white/10">
            @forelse($attendances as $attendance)
                <tr class="hover:bg-white/5 transition">
                    <td class="px-4 py-3 whitespace-nowrap">
                        {{ $attendance->date->format('d M Y') }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-lg text-xs font-semibold
                            @if($attendance->status === 'present') bg-green-500/20 text-green-300
                            @elseif($attendance->status === 'leave') bg-red-500/20 text-red-300
                            @else bg-yellow-500/20 text-yellow-300
                            @endif">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap">
                        {{ $attendance->clock_in ?? '--:--' }}
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap">
                        {{ $attendance->clock_out ?? '--:--' }}
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap">
                        {{ $attendance->working_duration }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-white/60">
                        No attendance data found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


</div>
</div>
@endsection
