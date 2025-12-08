<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Show today's attendance
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('salesman_id', Auth::id())
                                ->where('date', $today)
                                ->first();

        return view('salesman.attendance.index', compact('attendance'));
    }

    // Clock in
    public function clockIn(Request $request)
    {
        $today = Carbon::today()->toDateString();

        // Prevent duplicate clock-in
        if (Attendance::where('salesman_id', Auth::id())->where('date', $today)->exists()) {
            return back()->with('error', 'You already clocked in today.');
        }

        Attendance::create([
            'salesman_id' => Auth::id(),
            'date' => $today,
            'clock_in' => Carbon::now()->format('H:i:s'),
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        return back()->with('success', 'Clock In Successful!');
    }

    // Clock out
    public function clockOut(Request $request)
    {
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('salesman_id', Auth::id())
                                ->where('date', $today)
                                ->first();

        if (!$attendance) {
            return back()->with('error', 'You need to Clock In first.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You already clocked out today.');
        }

        $attendance->clock_out = Carbon::now()->format('H:i:s');
        $attendance->save();

        return back()->with('success', 'Clock Out Successful!');
    }
}
