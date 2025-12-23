@extends('layouts.app')

@section('title','Attendance Reports')

@section('content')
@php
    $displayMonth = \Carbon\Carbon::createFromFormat(
        'Y-m',
        $monthInput ?? now()->format('Y-m')
    )->format('F Y');
@endphp

<div class="max-w-6xl mx-auto mt-12 px-4">

    {{-- Header --}}
    <div class="relative glass p-8 rounded-2xl border border-white/20 shadow-2xl mb-8 overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-pink-400/20 blur-3xl rounded-full"></div>
        <div class="relative flex items-center gap-4">
            <div class="bg-white/10 p-3 rounded-2xl">
                <i data-lucide="clipboard-list" class="w-8 h-8 text-[#ff2ba6]"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-white tracking-tight">
                    Attendance Reports
                </h2>
                <p class="text-sm text-white/40">
                    Monthly attendance & leave overview
                </p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.attendance.index') }}"
          class="glass p-6 md:p-8 rounded-2xl border border-white/20 shadow-2xl mb-8">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">

            {{-- Month --}}
            <div>
                <label class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 block">
                    Month
                </label>
                <input type="month"
                       name="month"
                       value="{{ $monthInput }}"
                       class="w-full px-4 py-3 rounded-2xl bg-black/40 border border-white/10 text-white
                              focus:ring-2 focus:ring-pink-400/50">
            </div>

            {{-- Staff --}}
            <div>
                <label class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 block">
                    Staff
                </label>
                <select name="staff"
                        class="w-full px-4 py-3 rounded-2xl bg-black/40 border border-white/10 text-white">
                    <option value="">All Staff</option>
                    @foreach ($allStaff as $user)
                        <option value="{{ $user->id }}"
                            {{ ($staffId ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ ucfirst($user->role) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <button type="submit"
                        class="px-6 py-3 rounded-2xl font-bold text-white
                               bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a]
                               shadow-lg shadow-pink-900/20">
                    Apply
                </button>

                <a href="{{ route('admin.attendance.index') }}"
                   class="px-6 py-3 rounded-2xl bg-white/5 hover:bg-white/10
                          text-white border border-white/10">
                    Reset
                </a>
            </div>

        </div>
    </form>
{{-- Attendance Insight Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

    {{-- Star Performer --}}
    <div class="glass p-6 rounded-2xl border border-emerald-400/30 hover:scale-[1.02] transition">
        <p class="text-xs uppercase text-emerald-300 tracking-wider">
            Star Performer
        </p>
        <h3 class="text-xl font-bold text-white mt-2">
            {{ $bestAttendance?->salesman?->name ?? '--' }}
        </h3>
        <p class="text-sm text-emerald-200 mt-1">
            ✅ {{ $bestAttendance->presents ?? 0 }} days present
        </p>
    </div>

    {{-- Most Leaves --}}
    <div class="glass p-6 rounded-2xl border border-rose-400/30 hover:scale-[1.02] transition">
        <p class="text-xs uppercase text-rose-300 tracking-wider">
            Most Leaves
        </p>
        <h3 class="text-xl font-bold text-white mt-2">
            {{ $mostLeaves?->salesman?->name ?? '--' }}
        </h3>
        <p class="text-sm text-rose-200 mt-1">
            🚫 {{ $mostLeaves->leaves ?? 0 }} leaves
        </p>
    </div>

    {{-- Hardest Worker --}}
    <div class="glass p-6 rounded-2xl border border-sky-400/30 hover:scale-[1.02] transition">
        <p class="text-xs uppercase text-sky-300 tracking-wider">
            Hardest Worker
        </p>
        <h3 class="text-xl font-bold text-white mt-2">
            {{ $hardestWorker?->salesman?->name ?? '--' }}
        </h3>
        <p class="text-sm text-sky-200 mt-1">
            ⏱ {{ number_format(($hardestWorker->minutes ?? 0) / 60, 1) }} hrs
        </p>
    </div>

    {{-- Attendance Health --}}
    <div class="glass p-6 rounded-2xl border border-purple-400/30 hover:scale-[1.02] transition">
        <p class="text-xs uppercase text-purple-300 tracking-wider">
            Attendance Health
        </p>
        <h3 class="text-3xl font-extrabold text-white mt-2">
            {{ $attendanceRate }}%
        </h3>
        <p class="text-sm text-purple-200 mt-1">
            📊 Overall monthly attendance
        </p>
    </div>

</div>

    {{-- Table (Visible on md and up) --}}
    <div class="hidden md:block glass rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
        <div class="p-6 bg-white/5 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
    <h3 class="text-xl font-bold text-white flex items-center gap-3">
        <i data-lucide="users" class="w-6 h-6 text-[#ff2ba6]"></i>
        Staff Attendance
        <span class="text-xs text-[#ff2ba6]/70 font-semibold ml-2">
            ({{ $displayMonth }})
        </span>
    </h3>

    {{-- Export Buttons --}}
    <div class="flex gap-3">
        {{-- Excel --}}
        <a href="{{ route('admin.attendance.export.excel', [
                'month' => $monthInput,
                'staff' => $staffId
            ]) }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl
                  bg-emerald-500/20 text-emerald-400
                  border border-emerald-400/30 hover:bg-emerald-500/30
                  text-sm font-semibold">
            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
           Export Excel
        </a>

        {{-- PDF --}}
        {{-- <a href="{{ route('admin.attendance.export.pdf', [
                'month' => $monthInput,
                'staff' => $staffId
            ]) }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl
                  bg-rose-500/20 text-rose-400
                  border border-rose-400/30 hover:bg-rose-500/30
                  text-sm font-semibold">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            PDF
        </a> --}}
    </div>
</div>


        <div class="overflow-x-auto">
            <table class="min-w-full text-white text-sm divide-y divide-white/20">
                <thead class="bg-white/5 text-white/60 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-center">Present</th>
                        <th class="px-6 py-3 text-center">Leaves</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/20">
                    @forelse($staff as $index => $user)
                        <tr class="hover:bg-[#ff2ba620] transition-colors">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-white/60">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-white/60">{{ ucfirst($user->role) }}</td>
                            <td class="px-6 py-4 text-[#ff2ba6] font-semibold text-center">
                                {{ $user->monthAttendance }}
                            </td>
                            <td class="px-6 py-4 text-[#ff2ba6] font-semibold text-center">
                                {{ $user->monthLeaves }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.attendance.staff', ['id' => $user->id, 'month' => $monthInput]) }}"
                                   class="inline-flex items-center px-4 py-1 rounded-xl
                                          bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a]
                                          text-white text-sm font-semibold">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-white/40">
                                <i data-lucide="folder-x" class="w-12 h-12 mx-auto mb-3"></i>
                                <p class="text-lg font-semibold">No attendance records found</p>
                                <p class="text-sm">There is no data available for this month</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Card Layout (Visible only on mobile) --}}
    <div class="md:hidden mt-6 space-y-4">
        @forelse($staff as $user)
            <div class="glass p-5 rounded-2xl border border-white/10 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-[#ff2ba6]/5 blur-2xl rounded-full"></div>

                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#ff2ba6]/20 to-purple-600/20 flex items-center justify-center border border-white/10 text-[#ff2ba6]">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold">{{ $user->name }}</h4>
                            <p class="text-white/40 text-xs">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] bg-white/10 text-white/60 px-2 py-1 rounded-md uppercase tracking-tighter">
                        {{ $user->role }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-black/20 rounded-2xl p-3 border border-white/5 text-center">
                        <p class="text-[10px] text-white/40 uppercase mb-1">Present</p>
                        <p class="text-emerald-400 font-bold text-lg">{{ $user->monthAttendance }}</p>
                    </div>
                    <div class="bg-black/20 rounded-2xl p-3 border border-white/5 text-center">
                        <p class="text-[10px] text-white/40 uppercase mb-1">Leaves</p>
                        <p class="text-[#ff2ba6] font-bold text-lg">{{ $user->monthLeaves }}</p>
                    </div>
                </div>

                <a href="{{ route('admin.attendance.staff', ['id' => $user->id, 'month' => $monthInput]) }}"
                   class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl bg-gradient-to-r from-[#ff2ba6] to-[#d41a8a] text-white font-bold text-sm shadow-lg shadow-pink-900/20">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                    Detailed Report
                </a>
            </div>
        @empty
            <div class="glass p-12 rounded-2xl text-center text-white/30 border border-white/10">
                <i data-lucide="users-2" class="w-10 h-10 mx-auto mb-2 opacity-20"></i>
                <p>No staff records found</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    .glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
    table th, table td {
        vertical-align: middle;
    }
</style>
@endsection
