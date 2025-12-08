@extends('layouts.app')

@section('title','Attendance')

@section('content')
<div class="max-w-xl mx-auto bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-xl">
  <h2 class="text-2xl font-bold text-white mb-4 tracking-wide">Attendance - {{ date('Y-m-d') }}</h2>

  <div class="mb-6 text-white/80 text-sm">
    @if($attendance)
      Clock In: <strong>{{ $attendance->clock_in ?? '-' }}</strong> • Clock Out: <strong>{{ $attendance->clock_out ?? '-' }}</strong>
    @else
      You have not clocked in today.
    @endif
  </div>

  <div class="flex gap-4">
    <form id="clockinForm" method="POST" action="{{ route('salesman.attendance.clockin') }}" class="flex-1">
      @csrf
      <input type="hidden" name="lat" id="clockin_lat">
      <input type="hidden" name="lng" id="clockin_lng">
      @if(!$attendance)
        <button type="button" onclick="clockIn()"
            class="w-full py-3 rounded-xl font-semibold bg-gradient-to-r from-green-500 to-green-600 hover:opacity-90 transition shadow-lg text-white">
            Clock In
        </button>
      @endif
    </form>

    @if($attendance && !$attendance->clock_out)
      <form id="clockoutForm" method="POST" action="{{ route('salesman.attendance.clockout') }}" class="flex-1">
        @csrf
        <input type="hidden" name="lat" id="clockout_lat">
        <input type="hidden" name="lng" id="clockout_lng">
        <button type="button" onclick="clockOut()"
            class="w-full py-3 rounded-xl font-semibold bg-gradient-to-r from-red-500 to-red-600 hover:opacity-90 transition shadow-lg text-white">
            Clock Out
        </button>
      </form>
    @endif
  </div>
</div>
@endsection

@section('scripts')
<script>
function geoSuccess(formId, latId, lngId) {
  if(!navigator.geolocation){
    alert("Geolocation is not supported by your browser.");
    return;
  }
  navigator.geolocation.getCurrentPosition(function(pos) {
    document.getElementById(latId).value = pos.coords.latitude;
    document.getElementById(lngId).value = pos.coords.longitude;
    document.getElementById(formId).submit();
  }, function(err) {
    alert('Unable to get GPS: ' + err.message);
  }, { enableHighAccuracy:true, timeout:10000 });
}

function clockIn(){ geoSuccess('clockinForm','clockin_lat','clockin_lng'); }
function clockOut(){ geoSuccess('clockoutForm','clockout_lat','clockout_lng'); }
</script>
@endsection
