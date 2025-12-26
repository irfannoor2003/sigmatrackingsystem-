<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/* EXPORTS */
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceReportController extends Controller
{
    /**
     * ADMIN: Attendance Index (Month + Staff filter + Insights)
     */
    public function index(Request $request)
    {
        $monthInput = $request->month ?? now()->format('Y-m');
        $date  = Carbon::createFromFormat('Y-m', $monthInput);
        $month = $date->month;
        $year  = $date->year;

        $staffId = $request->staff;

        $roles = ['salesman', 'it', 'account', 'store', 'office_boy'];


        /** --------------------------------
         * Staff list (for dropdown)
         * -------------------------------- */
        $allStaff = User::whereIn('role', $roles)
            ->orderBy('name')
            ->get();

        /** --------------------------------
         * Attendance Stats (for table + insights)
         * -------------------------------- */
        $attendanceStats = Attendance::select(
                'salesman_id',
                DB::raw("SUM(status = 'present') as presents"),
                DB::raw("SUM(status = 'leave') as leaves"),
                DB::raw("SUM(total_minutes) as minutes")
            )
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->groupBy('salesman_id')
            ->get();

        /** --------------------------------
         * Insight Cards Logic
         * -------------------------------- */
        $bestAttendance = $attendanceStats->sortByDesc('presents')->first();
        $mostLeaves     = $attendanceStats->sortByDesc('leaves')->first();
        $hardestWorker  = $attendanceStats->sortByDesc('minutes')->first();

        $totalStaff    = $attendanceStats->count();
        $totalPresents = $attendanceStats->sum('presents');
        $daysInMonth   = $date->daysInMonth;

        $attendanceRate = $totalStaff > 0
            ? round(($totalPresents / ($totalStaff * $daysInMonth)) * 100)
            : 0;

        /** --------------------------------
         * No attendance case
         * -------------------------------- */
        if ($attendanceStats->isEmpty()) {
            return view('admin.attendance.index', compact(
                'allStaff',
                'monthInput',
                'staffId',
                'attendanceRate'
            ))->with([
                'staff' => collect(),
                'bestAttendance' => null,
                'mostLeaves' => null,
                'hardestWorker' => null,
            ]);
        }

        /** --------------------------------
         * Build Staff Table Data
         * -------------------------------- */
        $staffQuery = User::whereIn('role', $roles)
            ->whereIn('id', $attendanceStats->pluck('salesman_id'));

        if ($staffId) {
            $staffQuery->where('id', $staffId);
        }

        $staff = $staffQuery->get()->map(function ($user) use ($attendanceStats) {
            $stats = $attendanceStats->firstWhere('salesman_id', $user->id);

            $user->monthAttendance = $stats->presents ?? 0;
            $user->monthLeaves     = $stats->leaves ?? 0;

            return $user;
        });

        return view('admin.attendance.index', compact(
            'staff',
            'allStaff',
            'monthInput',
            'staffId',
            'bestAttendance',
            'mostLeaves',
            'hardestWorker',
            'attendanceRate'
        ));
    }

    /**
     * ADMIN: Single Staff Monthly Attendance
     */
    public function staffReport($id, Request $request)
{
    $monthInput = $request->month ?? now()->format('Y-m');
    $date = Carbon::createFromFormat('Y-m', $monthInput);

    $user = User::findOrFail($id);

    // Existing attendance records (indexed by date)
    $attendanceRecords = Attendance::where('salesman_id', $id)
        ->whereMonth('date', $date->month)
        ->whereYear('date', $date->year)
        ->get()
        ->keyBy(fn ($att) => Carbon::parse($att->date)->toDateString());

    $holidays = config('pakistan_holidays');

    $daysInMonth = $date->daysInMonth;
    $calendar = collect();

    for ($day = 1; $day <= $daysInMonth; $day++) {

        $currentDate = Carbon::create(
            $date->year,
            $date->month,
            $day
        );

        $dateString = $currentDate->toDateString();
        $md = $currentDate->format('m-d');

        // Default day structure
       $today = now()->toDateString();

$dayData = [
    'date' => $dateString,
    'day'  => $currentDate->format('l'),
    'status' => $dateString > $today ? 'future' : 'absent',
    'label'  => $dateString > $today ? 'Upcoming' : 'Absent',
    'attendance' => null,
];


        // 🎉 Pakistan Holiday
        if (isset($holidays[$md])) {
            $dayData['status'] = 'off';
            $dayData['label']  = $holidays[$md];
        }

        // 🌙 Sunday OFF
        elseif ($currentDate->isSunday()) {
            $dayData['status'] = 'off';
            $dayData['label']  = 'Sunday';
        }

        // ✅ Attendance Exists
        elseif ($attendanceRecords->has($dateString)) {
            $attendance = $attendanceRecords[$dateString];

            $dayData['attendance'] = $attendance;
            $dayData['status'] = $attendance->status;
            $dayData['label']  = ucfirst($attendance->status);
        }

        $calendar->push($dayData);
    }

    return view('admin.attendance.staff', compact(
        'user',
        'calendar',
        'monthInput'
    ));
}


    /**
     * ADMIN: Mark Leave
     */
    public function markLeave(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string|max:1000',
        ]);

        Attendance::updateOrCreate(
            [
                'salesman_id' => $id,
                'date'        => Carbon::parse($request->date)->toDateString(),
            ],
            [
                'status'        => 'leave',
                'clock_in'      => null,
                'clock_out'     => null,
                'total_minutes' => 0,
                'note'          => $request->note,
            ]
        );

        return back()->with('success', 'Leave marked successfully.');
    }

    /**
     * ADMIN: Update Attendance
     */
    public function updateAttendance(Request $request, $attendanceId)
    {
        $request->validate([
            'clock_in'  => 'nullable|date',
            'clock_out' => 'nullable|date|after_or_equal:clock_in',
            'note'      => 'nullable|string|max:500',
        ]);

        $attendance = Attendance::findOrFail($attendanceId);

        $attendance->update([
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'status' => 'present',
            'note' => $request->note,
            'total_minutes' => $request->clock_in && $request->clock_out
                ? Carbon::parse($request->clock_in)
                    ->diffInMinutes(Carbon::parse($request->clock_out))
                : 0,
        ]);

        return back()->with('success', 'Attendance updated successfully.');
    }

    /**
     * EXPORT: Excel
     */
    public function exportExcel(Request $request)
    {
        $monthInput = $request->month;
        $staffId = $request->staff;

        if ($monthInput) {
            $date = Carbon::createFromFormat('Y-m', $monthInput);
            $month = $date->month;
            $year = $date->year;
        }

        return Excel::download(
            new AttendanceExport($staffId ?? null, $month ?? null, $year ?? null),
            'attendance.xlsx'
        );
    }

    /**
     * EXPORT: PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Attendance::with('salesman');

        if ($request->staff) {
            $query->where('salesman_id', $request->staff);
        }

        if ($request->month) {
            $date = Carbon::createFromFormat('Y-m', $request->month);
            $query->whereMonth('date', $date->month)
                  ->whereYear('date', $date->year);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        return Pdf::loadView('admin.attendance.pdf', compact('attendances'))
            ->download('attendance.pdf');
    }
}
