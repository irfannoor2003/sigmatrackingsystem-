<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\City;
use App\Models\Industry;
use App\Models\Category;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use App\Models\User;




class CustomerController extends Controller
{
   public function index(Request $request)
{
    $query = Customer::with(['city', 'industry', 'category', 'salesman']);

    if ($request->filled('city_id')) {
        $query->where('city_id', $request->city_id);
    }

    if ($request->filled('industry_id')) {
        $query->where('industry_id', $request->industry_id);
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // ⭐ Salesman Filter
    if ($request->filled('salesman_id')) {
        $query->where('salesman_id', $request->salesman_id);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('phone1', 'like', "%{$search}%")
              ->orWhere('phone2', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $customers = $query->orderBy('id', 'desc')
                       ->paginate(10)
                       ->appends($request->query());

    $cities     = City::orderBy('name')->get();
    $industries = Industry::orderBy('name')->get();
    $categories = Category::orderBy('name')->get();

    // ⭐ Users who are salesmen
    $salesmen   = User::where('role', 'salesman')
                       ->orderBy('name')
                       ->get();

    return view('admin.customers.index', compact(
        'customers',
        'cities',
        'industries',
        'categories',
        'salesmen'
    ));
}




    public function show($id)
    {
        $customer = Customer::with(['city', 'industry', 'category', 'salesman'])
                            ->findOrFail($id);

        $cities     = City::orderBy('name')->get();
        $industries = Industry::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.customers.show', compact(
            'customer',
            'cities',
            'industries',
            'categories'
        ));
    }
  public function exportAll()
{
    return Excel::download(new CustomersExport, 'all_customers.xlsx');
}

public function exportSingle($id)
{
    return Excel::download(new CustomersExport([$id]), 'customer_'.$id.'.xlsx');
}

public function exportBulk(Request $request)
{
    $ids = $request->selected_customers;

    if (!$ids || count($ids) === 0) {
        return back()->with('error', 'Please select at least one customer to export.');
    }

    return Excel::download(new CustomersExport($ids), 'selected_customers.xlsx');
}

}
