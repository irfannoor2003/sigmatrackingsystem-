@extends('layouts.app')

@section('title','My Attendance')

@section('content')
<div class="max-w-xl mx-auto mt-10 px-0">

<div class="glass border border-white/20 p-6 rounded-3xl shadow-2xl">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-white tracking-wide">
            ⏱ My Attendance
        </h2>
        <span class="text-sm text-white/70">
            {{ now()->format('d M Y') }}
        </span>
    </div>

    {{-- Status --}}
    <div class="mb-6 p-4 rounded-2xl bg-white/10 border border-white/20">

        @if($attendance && $attendance->status === 'leave')
            <span class="px-3 py-1 rounded-full bg-red-500/20 text-red-300 text-sm font-semibold">
                🚫 On Leave
            </span>

            @if($attendance->note)
                <p class="mt-2 text-sm text-white/80">
                    📝 Reason: {{ $attendance->note }}
                </p>
            @endif

        @elseif(!$attendance || !$attendance->clock_in)
            <span class="px-3 py-1 rounded-full bg-yellow-500/20 text-yellow-300 text-sm font-semibold">
                ⚠ Not Clocked In
            </span>

        @elseif($attendance && !$attendance->clock_out)
            <span class="px-3 py-1 rounded-full bg-green-500/20 text-green-300 text-sm font-semibold">
                🟢 Working
            </span>

        @else
            <span class="px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-sm font-semibold">
                ✅ Completed
            </span>
        @endif

        <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
            <div class="bg-black/20 p-3 rounded-xl">
                <p class="text-white/60">Clock In</p>
                <p class="text-white font-semibold">
                    {{ $attendance->clock_in ?? '--:--' }}
                </p>
            </div>

            <div class="bg-black/20 p-3 rounded-xl">
                <p class="text-white/60">Clock Out</p>
                <p class="text-white font-semibold">
                    {{ $attendance->clock_out ?? '--:--' }}
                </p>
            </div>
        </div>

        @if($attendance && $attendance->clock_in && $attendance->status !== 'leave')
            <div class="mt-3 text-sm text-white/70">
                ⏳ Working Time:
                <strong>{{ $attendance->working_duration }}</strong>
            </div>
        @endif
    </div>

@php
    $role = auth()->user()->role;

    $clockInRoute = $role === 'salesman'
        ? route('salesman.attendance.clockin')
        : route('staff.attendance.clockin');

    $clockOutRoute = $role === 'salesman'
        ? route('salesman.attendance.clockout')
        : route('staff.attendance.clockout');

    $historyRoute = $role === 'salesman'
        ? route('salesman.attendance.history')
        : route('staff.attendance.history');
@endphp

{{-- Actions --}}
<div class="flex gap-4 mb-4">

    {{-- Clock In --}}
    <form id="clockinForm" method="POST" action="{{ $clockInRoute }}" class="flex-1">
        @csrf
        <input type="hidden" name="lat" id="clockin_lat">
        <input type="hidden" name="lng" id="clockin_lng">

        <button type="button"
            onclick="clockIn()"
            @if($attendance && ($attendance->clock_in || $attendance->status === 'leave'))
                disabled
            @endif
            class="w-full py-3 rounded-xl font-semibold text-white
            bg-gradient-to-r from-green-500 to-emerald-600
            hover:opacity-90 transition shadow-lg
            disabled:opacity-40 disabled:cursor-not-allowed">
            ⏰ Clock In
        </button>
    </form>

    {{-- Clock Out --}}
    <form id="clockoutForm" method="POST" action="{{ $clockOutRoute }}" class="flex-1">
        @csrf
        <input type="hidden" name="lat" id="clockout_lat">
        <input type="hidden" name="lng" id="clockout_lng">

        <button type="button"
            onclick="clockOut()"
            @if(!$attendance || !$attendance->clock_in || $attendance->clock_out || $attendance->status === 'leave')
                disabled
            @endif
            class="w-full py-3 rounded-xl font-semibold text-white
            bg-gradient-to-r from-red-500 to-pink-600
            hover:opacity-90 transition shadow-lg
            disabled:opacity-40 disabled:cursor-not-allowed">
            🚪 Clock Out
        </button>
    </form>
</div>

{{-- Request Leave Button --}}
@if(
    !$attendance ||
    (
        !$attendance->clock_in &&
        $attendance->status !== 'leave'
    )
)
<button
    onclick="document.getElementById('leaveModal').classList.remove('hidden')"
    class="w-full mb-4 py-3 rounded-xl font-semibold text-white
    bg-gradient-to-r from-orange-500 to-red-600 hover:opacity-90">
    🤒 Request Leave
</button>
@endif


{{-- Monthly History --}}
<div class="mt-4 text-center">
    <a href="{{ $historyRoute }}"
       class="text-indigo-300 hover:underline text-sm font-semibold">
        📅 View Monthly Attendance History
    </a>
</div>

</div>
</div>

{{-- Leave Modal --}}
<div id="leaveModal"
     class="hidden fixed inset-0 z-[9999] flex items-center justify-center
            bg-black/70 backdrop-blur-xl p-4">

    <div class="relative w-full max-w-md
                bg-white/10 backdrop-blur-2xl
                border border-white/20
                rounded-3xl shadow-2xl p-6 md:p-8 animate-fadeIn">

        {{-- Title --}}
        <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
            {{-- Lucide Icon: alert-circle --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-alert-circle mr-3 text-red-400">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" x2="12" y1="8" y2="12"/>
                <line x1="12" x2="12.01" y1="16" y2="16"/>
            </svg>
            Leave Reason
        </h3>

        {{-- Form --}}
        <form method="POST" action="{{ route('attendance.leave') }}">
            @csrf

            {{-- Reason --}}
            <div class="mb-6">
                <label class="block text-white/70 mb-2 font-medium">
                    Reason for Leave
                </label>

                <textarea name="reason" required rows="4"
                    class="w-full px-4 py-3 rounded-2xl
                           bg-black/40 border border-white/10
                           text-white placeholder-white/40
                           focus:ring-2 focus:ring-red-400/50 focus:border-transparent
                           resize-none"
                    placeholder="Explain your reason (illness, emergency, etc)"></textarea>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3">
                <button type="button"
                    onclick="document.getElementById('leaveModal').classList.add('hidden')"
                    class="px-5 py-2.5 rounded-xl
                           bg-white/10 hover:bg-white/20
                           text-white font-medium transition">
                    Cancel
                </button>

                <button type="submit"
                    class="px-5 py-2.5 rounded-xl
                           bg-red-600/80 hover:bg-red-600
                           text-white font-semibold shadow-lg transition">
                    Submit Leave
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function geoSubmit(formId, latId, lngId) {
    if (!navigator.geolocation) {
        alert("Geolocation is not supported by your browser.");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            document.getElementById(latId).value = pos.coords.latitude;
            document.getElementById(lngId).value = pos.coords.longitude;
            document.getElementById(formId).submit();
        },
        (err) => alert('Unable to get GPS: ' + err.message),
        { enableHighAccuracy: true, timeout: 10000 }
    );
}

function clockIn()  { geoSubmit('clockinForm','clockin_lat','clockin_lng'); }
function clockOut() { geoSubmit('clockoutForm','clockout_lat','clockout_lng'); }
</script>
@endsection
