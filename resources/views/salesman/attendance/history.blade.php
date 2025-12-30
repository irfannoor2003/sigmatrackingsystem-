@extends('layouts.app')

@section('title','Attendance History')

@section('content')

<div class="max-w-5xl mx-auto mt-8 px-4 text-white">

    <div class="glass p-5 sm:p-6 rounded-3xl border border-white/20 shadow-2xl">

        {{-- HEADER --}}
        <h2 class="text-2xl sm:text-3xl font-bold mb-6 flex items-center gap-2">
            <i data-lucide="calendar-days" class="w-7 h-7 text-[#ff2ba6]"></i>
            Monthly Attendance
        </h2>

        {{-- FILTER --}}
        <form method="GET"
              class="flex flex-col sm:flex-row gap-3 mb-6 items-start sm:items-end">

            <div class="relative">
                <i data-lucide="calendar" class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>
                <input type="month" name="month" value="{{ $month }}"
                       class="pl-12 pr-4 py-3 rounded-xl bg-black/40
                              border border-white/20 text-white w-full sm:w-auto d-block">
            </div>

            <button
                class="px-5 py-3 rounded-xl bg-gradient-to-r
                       from-pink-500/80 to-pink-500/80
                       font-semibold flex items-center gap-2 w-full sm:w-auto">
                <i data-lucide="filter"></i>
                Filter
            </button>
        </form>

        {{-- ================= DESKTOP TABLE ================= --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-white/20 text-white/70">
                    <tr>
                        <th class="py-3 text-left">Date</th>
                        <th>Status</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Work Time</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($attendances as $attendance)
                        <tr class="border-b border-white/10 hover:bg-white/5">
                            <td class="py-3">
                                {{ $attendance->date->format('d M Y') }}
                            </td>

                            <td>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($attendance->display_status === 'Present') bg-green-500/20 text-green-300
                                    @elseif($attendance->display_status === 'Absent') bg-red-500/20 text-red-300
                                    @elseif($attendance->display_status === 'Leave') bg-yellow-500/20 text-yellow-300
                                    @else bg-white/20 text-white
                                    @endif">
                                    {{ $attendance->display_status }}
                                </span>
                            </td>

                            <td>{{ $attendance->clock_in ?? '--' }}</td>
                            <td>{{ $attendance->clock_out ?? '--' }}</td>
                            <td class="font-semibold">
                                {{ $attendance->working_duration }}
                            </td>
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

        {{-- ================= MOBILE CARDS ================= --}}
        <div class="md:hidden space-y-4">

            @forelse($attendances as $attendance)
                <div class="bg-white/10 border border-white/10 rounded-2xl p-4">

                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold">
                            {{ $attendance->date->format('d M Y') }}
                        </span>

                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($attendance->display_status === 'Present') bg-green-500/20 text-green-300
                            @elseif($attendance->display_status === 'Absent') bg-red-500/20 text-red-300
                            @elseif($attendance->display_status === 'Leave') bg-yellow-500/20 text-yellow-300
                            @else bg-white/20 text-white
                            @endif">
                            {{ $attendance->display_status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm text-white/80">
                        <div class="flex items-center gap-2">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            In: {{ $attendance->clock_in ?? '--' }}
                        </div>

                        <div class="flex items-center gap-2">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Out: {{ $attendance->clock_out ?? '--' }}
                        </div>

                        <div class="col-span-2 flex items-center gap-2 font-semibold">
                            <i data-lucide="timer" class="w-4 h-4"></i>
                            Work Time: {{ $attendance->working_duration }}
                        </div>
                    </div>

                </div>
            @empty
                <div class="text-center py-6 text-white/60">
                    No attendance data.
                </div>
            @endforelse

        </div>

    </div>

</div>

@endsection
