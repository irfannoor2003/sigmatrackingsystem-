<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Dashboard charts
    public function index()
    {
        $year = Carbon::now()->year;

        $monthlyVisits = Visit::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('count', 'month');

        $monthlyCustomers = Customer::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('count', 'month');

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

    // Salesman personal report
    public function salesmanReport()
    {
        $salesmanId = auth()->id();

        $visits = Visit::with('customer')
            ->where('salesman_id', $salesmanId)
            ->orderBy('started_at', 'desc')
            ->paginate(10);   // PAGINATED

        // Add duration field
        $visits->each(function ($visit) {
            $visit->duration = $visit->completed_at
                ? $visit->completed_at->diffInMinutes($visit->started_at)
                : null;
        });

        return view('salesman.reports.index', compact('visits'));
    }

    // Admin report (for your view)
    public function adminReport(Request $request)
    {
        $query = Visit::with(['customer', 'salesman']);

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

        // PAGINATION FIXED HERE
        $visits = $query->orderBy('started_at', 'desc')
                        ->paginate(10)   // you can change the number
                        ->appends($request->query()); // keeps filters

        // Calculate duration
        $visits->each(function ($visit) {
            $visit->duration = $visit->completed_at
                ? $visit->completed_at->diffInMinutes($visit->started_at)
                : null;
        });

        $salesmen = User::where('role', 'salesman')->get();

        return view('admin.reports.index', compact('visits', 'salesmen'));
    }

    // Admin single visit details
    public function show($id)
    {
        $visit = Visit::with(['salesman', 'customer'])->findOrFail($id);

        return view('admin.reports.show', compact('visit'));
    }
}
