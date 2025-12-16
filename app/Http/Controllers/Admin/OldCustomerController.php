<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OldCustomer;
use App\Models\User;
use Illuminate\Http\Request;

class OldCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = OldCustomer::with('salesman');

        // Filter by salesman
        if ($request->filled('salesman_id')) {
            $query->where('salesman_id', $request->salesman_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $salesmen = User::where('role', 'salesman')
            ->orderBy('name')
            ->get();

        return view('admin.old-customers.index', compact('customers', 'salesmen'));
    }
}
