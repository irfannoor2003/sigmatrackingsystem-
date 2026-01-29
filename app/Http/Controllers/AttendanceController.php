<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\AttendanceVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Mail\AttendanceClockInVerificationMail;

class AttendanceController extends Controller
{
    /* ================= AUTH ================= */
    private function staffUser()
    {
        $user = auth()->user();
        if (! $user) abort(403);

        if (! in_array($user->role, [
            'salesman','it','account','store','office_boy'
        ])) abort(403);

        return $user;
    }

    private function viewPath(string $view)
    {
        return auth()->user()->role === 'salesman'
            ? "salesman.attendance.$view"
            : "staff.attendance.$view";
    }

    /* ================= HELPERS ================= */
    private function distanceInMeters($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLng / 2) ** 2;

        return round($earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a))));
    }

    private function validateOfficeQr(?string $qrToken): bool
    {
        if (! $qrToken) return false;
        return hash_equals(config('office.qr_token'), $qrToken);
    }

    /* ================= INDEX ================= */
    public function index()
    {
        $user = $this->staffUser();
        $today = today()->toDateString();

        $attendance = Attendance::where('salesman_id', $user->id)
            ->where('date', $today)
            ->first();

        $todayHoliday = Holiday::isHoliday($today)
            ? Holiday::title($today)
            : null;

        // ✅ ALWAYS DEFINED
        $hideLeaveButton   = now()->hour >= 12;
        $hideClockInButton = now()->hour >= 15;

        return view(
            $this->viewPath('index'),
            compact(
                'attendance',
                'todayHoliday',
                'hideLeaveButton',
                'hideClockInButton'
            )
        );
    }

    /* ================= CHECK-IN VIEW ================= */
    public function checkInView()
    {
        return view($this->viewPath('checkin'));
    }

    /* ================= CLOCK IN ================= */
    public function clockIn(Request $request)
    {
        $user = $this->staffUser();
        $today = today()->toDateString();

        if (Holiday::isHoliday($today)) {
            return back()->with('error', 'Attendance disabled due to holiday.');
        }

        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'qr_token' => 'nullable|string',
        ]);

        if (! $this->validateOfficeQr($request->qr_token)) {
            return back()->with('error', 'Invalid QR code.');
        }

        if (Attendance::where('salesman_id', $user->id)->where('date', $today)->exists()) {
            return back()->with('error', 'Already checked in today.');
        }

        $distance = $this->distanceInMeters(
            $request->lat,
            $request->lng,
            config('office.lat'),
            config('office.lng')
        );

        if ($distance > config('office.radius')) {
            return back()->with('error', 'Outside office radius.');
        }

        // 🔥 Clear old unused verifications
        AttendanceVerification::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->delete();

        $token = Str::uuid()->toString();

        AttendanceVerification::create([
            'user_id'    => $user->id,
            'token'      => $token,
            'expires_at' => now()->addMinute(),
            'payload'    => json_encode([
                'lat'      => $request->lat,
                'lng'      => $request->lng,
                'distance' => $distance,
                'qr'       => (bool) $request->qr_token,
                'ip'       => $request->ip(),
            ])
        ]);

        $link = URL::temporarySignedRoute(
            'attendance.verify',
            now()->addMinute(),
            ['token' => $token]
        );

        Mail::to($user->email)
            ->send(new AttendanceClockInVerificationMail($link));

        return back()->with(
            'success',
            '📧 Verification email sent. Confirm within 1 minute.'
        );
    }

    /* ================= VERIFY CLOCK IN ================= */
    public function verifyClockIn(Request $request, $token)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired verification link.');
        }

        $verification = AttendanceVerification::where('token', $token)
            ->whereNull('verified_at')
            ->firstOrFail();

        if (now()->greaterThan($verification->expires_at)) {
            abort(403, 'Verification link expired.');
        }

        $data = json_decode($verification->payload, true);
        if (! is_array($data)) {
            abort(403, 'Invalid verification payload.');
        }

        $user = User::findOrFail($verification->user_id);
        Auth::login($user);

        $today = today()->toDateString();

        if (Attendance::where('salesman_id', $user->id)->where('date', $today)->exists()) {
            abort(403, 'Attendance already marked.');
        }

        $clockIn = now();

        Attendance::create([
            'salesman_id'     => $user->id,
            'date'            => $today,
            'status'          => 'present',
            'clock_in'        => $clockIn,
            'short_leave'     => $clockIn->format('H:i') >= '12:00',
            'lat'             => $data['lat'],
            'lng'             => $data['lng'],
            'distance_meters' => $data['distance'],
            'office_verified' => true,
            'qr_verified'     => $data['qr'],
            'checkin_method'  => $data['qr'] ? 'qr' : 'gps',
            'checkin_ip'      => $data['ip'],
        ]);

        $verification->update(['verified_at' => now()]);

        $route = $user->role === 'salesman'
            ? 'salesman.attendance.index'
            : 'staff.attendance.index';

        return redirect()->route($route)
            ->with('success', '✅ Clock-in verified successfully.');
    }

    /* ================= CLOCK OUT ================= */
    public function clockOut(Request $request)
    {
        $user = $this->staffUser();
        $today = today()->toDateString();

        if (Holiday::isHoliday($today)) {
            return back()->with('error', 'Holiday today.');
        }

        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ]);

        $attendance = Attendance::where('salesman_id', $user->id)
            ->where('date', $today)
            ->firstOrFail();

        if ($attendance->clock_out) {
            return back()->with('error', 'Already clocked out.');
        }

        $distance = $this->distanceInMeters(
            $request->lat,
            $request->lng,
            config('office.lat'),
            config('office.lng')
        );

        if ($distance > config('office.radius')) {
            return back()->with('error', 'Clock-out allowed only inside office.');
        }

        $clockOut = now();

        $attendance->update([
            'clock_out' => $clockOut,
            'total_minutes' => $attendance->clock_in
                ? $attendance->clock_in->diffInMinutes($clockOut)
                : 0,
            'short_leave' => $attendance->short_leave || $clockOut->format('H:i') < '17:00',
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        return back()->with('success', 'Clock-out successful.');
    }

    /* ================= HISTORY ================= */
    public function history(Request $request)
    {
        $user = $this->staffUser();
        $monthInput = $request->month ?? now()->format('Y-m');

        $start = Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();
        $end   = Carbon::createFromFormat('Y-m', $monthInput)->endOfMonth();

        $attendances = Attendance::where('salesman_id', $user->id)
            ->whereBetween('date', [$start, $end])
            ->get()
            ->keyBy(fn ($a) => Carbon::parse($a->date)->format('Y-m-d'));

        $calendar = [];
        $date = $start->copy();

        while ($date <= $end) {
            $key = $date->format('Y-m-d');
            $attendance = $attendances->get($key);

            $calendar[] = [
                'date' => $key,
                'status' =>
                    Holiday::isHoliday($key) ? 'holiday' :
                    ($attendance?->status ?? ($date->isFuture() ? 'future' : 'absent')),
                'attendance' => $attendance,
                'holiday' => Holiday::title($key),
            ];

            $date->addDay();
        }

        return view($this->viewPath('history'), compact('calendar', 'monthInput'));
    }

    /* ================= REQUEST LEAVE ================= */
    public function requestLeave(Request $request)
    {
        $user = $this->staffUser();
        $today = today()->toDateString();

        if (now()->hour >= 12) {
            return back()->with('error', 'Leave allowed before 12 PM only.');
        }

        if (Holiday::isHoliday($today)) {
            return back()->with('error', 'Holiday today.');
        }

        if (Attendance::where('salesman_id', $user->id)->where('date', $today)->exists()) {
            return back()->with('error', 'Attendance already exists.');
        }

        Attendance::create([
            'salesman_id' => $user->id,
            'date' => $today,
            'status' => 'leave',
            'leave_type' => $request->leave_type ?? 'casual',
            'note' => $request->note,
        ]);

        return back()->with('success', 'Leave requested successfully.');
    }
}
