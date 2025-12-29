@extends('layouts.app')

@section('title','My Attendance')

@section('content')
<div class="max-w-xl mx-auto mt-10 px-4">

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
<div id="leaveModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-96">
        <h3 class="text-xl font-bold mb-4">Leave Reason</h3>

        <form method="POST" action="{{ route('attendance.leave') }}">
            @csrf

            <textarea name="reason" required
                class="w-full border rounded-lg p-3"
                placeholder="Explain your reason (illness, emergency, etc)"></textarea>

            <div class="mt-4 flex justify-end gap-3">
                <button type="button"
                    onclick="document.getElementById('leaveModal').classList.add('hidden')"
                    class="px-4 py-2 border rounded">
                    Cancel
                </button>

                <button class="px-4 py-2 bg-red-600 text-white rounded">
                    Submit
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
