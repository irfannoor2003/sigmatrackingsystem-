<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Visit;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Total Salesmen
        $totalSalesmen = User::where('role', 'salesman')->count();

        // Staff Working Today (clocked in but not clocked out)
        $workingToday = Attendance::whereDate('date', $today)
            ->where('status', 'present')
            ->whereNull('clock_out')
            ->count();

        // Attendance Activities
        $attendanceActivities = Attendance::with('salesman')
            ->latest()
            ->limit(6)
            ->get();

        // Visit Activities
        $visitActivities = Visit::with('salesman', 'customer')
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalSalesmen',
            'workingToday',
            'attendanceActivities',
            'visitActivities'
        ));
    }
}
