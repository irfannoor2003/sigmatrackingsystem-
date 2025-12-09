<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Current Year
        $year = Carbon::now()->year;

        // Monthly Visits Count
        $monthlyVisits = Visit::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('count', 'month');

        // Monthly New Customers Count
        $monthlyCustomers = Customer::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('count', 'month');

        // Fill missing months with 0
        $visits = array_fill(1, 12, 0);
        foreach ($monthlyVisits as $month => $count) {
            $visits[$month] = $count;
        }

        $customers = array_fill(1, 12, 0);
        foreach ($monthlyCustomers as $month => $count) {
            $customers[$month] = $count;
        }

        return view('reports.index', [
            'visits' => array_values($visits),
            'customers' => array_values($customers),
        ]);
    }
    // Salesman report
public function salesmanReport()
{
    $salesmanId = auth()->id();

    $visits = Visit::with('customer')
        ->where('salesman_id', $salesmanId)
        ->orderBy('started_at', 'desc')
        ->get();

    // Calculate duration for each visit
    $visits->each(function($visit) {
        $visit->duration = $visit->completed_at
            ? $visit->completed_at->diffInMinutes($visit->started_at)
            : null;
    });

    return view('salesman.reports.index', compact('visits'));
}
// Admin report
public function adminReport(Request $request)
{
    $query = Visit::with(['customer','salesman']);

    if ($request->salesman_id) {
        $query->where('salesman_id', $request->salesman_id);
    }

    if ($request->from_date) {
        $query->whereDate('started_at', '>=', $request->from_date);
    }

    if ($request->to_date) {
        $query->whereDate('started_at', '<=', $request->to_date);
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    $visits = $query->orderBy('started_at','desc')->get();

    // Calculate duration
    $visits->each(function($visit) {
        $visit->duration = $visit->completed_at
            ? $visit->completed_at->diffInMinutes($visit->started_at)
            : null;
    });

    $salesmen = \App\Models\User::where('role','salesman')->get();

    return view('admin.reports.index', compact('visits','salesmen'));
}
public function show($id)
{
    $visit = Visit::with(['salesman', 'customer'])->findOrFail($id);
    return view('admin.reports.show', compact('visit'));
}

}

