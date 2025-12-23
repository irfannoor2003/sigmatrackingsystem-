<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Get logged-in staff user (salesman / it / accounts)
     */
    private function staffUser()
    {
        $user = auth()->user();

        if (!in_array($user->role, ['salesman', 'it', 'accounts'])) {
            abort(403, 'Unauthorized action.');
        }

        return $user;
    }

    /**
     * Resolve correct view path based on role
     */
    private function viewPath(string $view)
    {
        $role = auth()->user()->role;

        // Salesman keeps old views (NO BREAKING CHANGE)
        if ($role === 'salesman') {
            return "salesman.attendance.$view";
        }

        // IT & Accounts use staff views
        return "staff.attendance.$view";
    }

    /**
     * Show today's attendance
     */
    public function index()
    {
        $user = $this->staffUser();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('salesman_id', $user->id)
            ->where('date', $today)
            ->first();

        return view($this->viewPath('index'), compact('attendance'));
    }

    /**
     * Clock In
     */
    public function clockIn(Request $request)
    {
        $user = $this->staffUser();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('salesman_id', $user->id)
            ->where('date', $today)
            ->first();

        // 🚫 Admin marked leave
        if ($attendance && $attendance->status === 'leave') {
            return back()->with('error', 'You are marked on leave today.');
        }

        // ⛔ Already clocked in
        if ($attendance && $attendance->clock_in) {
            return back()->with('error', 'You have already clocked in today.');
        }

        Attendance::updateOrCreate(
            [
                'salesman_id' => $user->id,
                'date' => $today,
            ],
            [
                'status'   => 'present',
                'clock_in' => now(),
                'lat'      => $request->lat,
                'lng'      => $request->lng,
            ]
        );

        return back()->with('success', 'Clock-in successful.');
    }

    /**
     * Clock Out
     */
    public function clockOut(Request $request)
    {
        $user = $this->staffUser();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('salesman_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance || !$attendance->clock_in) {
            return back()->with('error', 'You must clock in first.');
        }

        if ($attendance->status === 'leave') {
            return back()->with('error', 'You are on leave today.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You have already clocked out.');
        }

        $clockOut = now();

        $attendance->update([
            'clock_out'     => $clockOut,
            'total_minutes' => Carbon::parse($attendance->clock_in)
                ->diffInMinutes($clockOut),
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        return back()->with('success', 'Clock-out successful.');
    }

    /**
     * Monthly Attendance History
     */
    public function history(Request $request)
    {
        $user = $this->staffUser();
        $month = $request->get('month', now()->format('Y-m'));

        $attendances = Attendance::where('salesman_id', $user->id)
            ->whereMonth('date', Carbon::parse($month)->month)
            ->whereYear('date', Carbon::parse($month)->year)
            ->orderBy('date', 'desc')
            ->get();

        return view(
            $this->viewPath('history'),
            compact('attendances', 'month')
        );
    }
}
