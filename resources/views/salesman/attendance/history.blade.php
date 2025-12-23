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
        <button class="ml-2 px-4 py-2 rounded-xl bg-indigo-500/80 text-white font-semibold">
            Filter
        </button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-white">
            <thead class="text-white/70 border-b border-white/20">
                <tr>
                    <th class="py-3">Date</th>
                    <th>Status</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Work Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr class="border-b border-white/10">
                        <td class="py-3">
                            {{ $attendance->date->format('d M Y') }}
                        </td>
                        <td>
                            {{ $attendance->display_status }}
                        </td>
                        <td>{{ $attendance->clock_in ?? '--' }}</td>
                        <td>{{ $attendance->clock_out ?? '--' }}</td>
                        <td>{{ $attendance->working_duration }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-white/60">
                            No attendance data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</div>
@endsection
