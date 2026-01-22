<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /* ================= AUTH ================= */
    private function staffUser()
    {
        $user = auth()->user();
        if (!$user) abort(403);

        if (!in_array($user->role, [
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

    private function deviceHash(Request $request): string
    {
        $userAgent = $request->header('User-Agent') ?? 'unknown';
        $ip = $request->ip() ?? '0.0.0.0';

        return hash('sha256', $userAgent . '|' . $ip);
    }

    /* ================= QR VALIDATION ================= */
    private function validateOfficeQr(?string $qrToken): bool
    {
        if (!$qrToken) return true;
        $officeQr = config('office.qr_token');
        return hash_equals($officeQr, $qrToken);
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

        $hideLeaveButton = now()->hour >= 12;
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
            return back()->with('error', 'Attendance is disabled due to company holiday.');
        }

        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'qr_token' => 'nullable|string',
        ]);

        if (!$this->validateOfficeQr($request->qr_token)) {
            return back()->with('error', 'Invalid QR code.');
        }

        $existing = Attendance::where('salesman_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($existing && $existing->clock_in) {
            return back()->with('error', 'Already checked in today.');
        }

        $officeLat = config('office.lat');
        $officeLng = config('office.lng');
        $officeRadius = config('office.radius');

        $distance = $this->distanceInMeters(
            $request->lat,
            $request->lng,
            $officeLat,
            $officeLng
        );

        if ($distance > $officeRadius) {
            return back()->with('error', 'You are outside office radius.');
        }

        $clockIn = now();

        Attendance::create([
            'salesman_id'     => $user->id,
            'date'            => $today,
            'status'          => 'present',
            'clock_in'        => $clockIn,
            'short_leave'     => $clockIn->format('H:i') >= '12:00',

            'lat'             => $request->lat,
            'lng'             => $request->lng,
            'distance_meters' => $distance,

            'office_verified' => true,
            'qr_verified'     => (bool) $request->qr_token,
            'checkin_method'  => $request->qr_token ? 'qr' : 'gps',

            'device_hash'     => $this->deviceHash($request),
            'checkin_ip'      => $request->ip(),
        ]);

        return back()->with('success', '✅ Check-in successful.');
    }

    /* ================= CLOCK OUT ================= */
    public function clockOut(Request $request)
    {
        $user = $this->staffUser();
        $today = today()->toDateString();

        if (Holiday::isHoliday($today)) {
            return back()->with('error', 'Today is a company holiday.');
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

        $officeLat = config('office.lat');
        $officeLng = config('office.lng');
        $officeRadius = config('office.radius');

        $distance = $this->distanceInMeters(
            $request->lat,
            $request->lng,
            $officeLat,
            $officeLng
        );

        if ($distance > $officeRadius) {
            return back()->with('error', 'Clock-out allowed only inside office.');
        }

        $clockOut = now();

        $attendance->update([
            'clock_out' => $clockOut,
            'total_minutes' => $attendance->clock_in
                ? $attendance->clock_in->diffInMinutes($clockOut)
                : 0,
            'short_leave' =>
                $attendance->short_leave ||
                $clockOut->format('H:i') < '17:00',
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

            $dateKey = $date->format('Y-m-d');
            $attendance = $attendances->get($dateKey);
            $holiday = Holiday::isHoliday($dateKey);

            if ($holiday) {
                $status = 'holiday';
            } elseif ($date->isFuture()) {
                $status = 'future';
            } elseif ($attendance && $attendance->status === 'leave') {
                $status = 'leave';
            } elseif ($attendance && $attendance->clock_in) {
                $status = 'present';
            } else {
                $status = 'absent';
            }

            $calendar[] = [
                'date' => $dateKey,
                'status' => $status,
                'attendance' => $attendance,
                'holiday' => $holiday ? Holiday::title($dateKey) : null,
            ];

            $date->addDay();
        }

        return view(
            $this->viewPath('history'),
            compact('calendar', 'monthInput')
        );
    }

    /* ================= REQUEST LEAVE ================= */
    public function requestLeave(Request $request)
    {
        $user = $this->staffUser();
        $today = today()->toDateString();

        if (now()->hour >= 12) {
            return back()->with('error', 'Leave request is allowed only before 12:00 PM.');
        }

        if (Holiday::isHoliday($today)) {
            return back()->with('error', 'Cannot request leave on a holiday.');
        }

        $existing = Attendance::where('salesman_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($existing) {
            return back()->with('error', 'Attendance already exists for today.');
        }

        Attendance::create([
            'salesman_id' => $user->id,
            'date'        => $today,
            'status'      => 'leave',
            'leave_type'  => $request->leave_type ?? 'casual',
            'note'        => $request->note,
        ]);

        return back()->with('success', 'Leave requested successfully.');
    }
}
