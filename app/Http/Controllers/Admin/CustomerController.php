<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\City;
use App\Models\Industry;
use App\Models\Category;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
   public function index(Request $request)
{
    $query = Customer::with(['city', 'industry', 'category']);

    if ($request->filled('city_id')) {
        $query->where('city_id', $request->city_id);
    }

    if ($request->filled('industry_id')) {
        $query->where('industry_id', $request->industry_id);
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
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

    $customers   = $query->orderBy('id','desc')->get();
    $cities      = City::orderBy('name')->get();
    $industries  = Industry::orderBy('name')->get();
    $categories  = Category::orderBy('name')->get();

    return view('admin.customers.index', compact(
        'customers',
        'cities',
        'industries',
        'categories'
    ));
}


   public function show($id)
{
    $customer = Customer::with(['city', 'industry', 'category', 'salesman'])
                        ->findOrFail($id);

    // Load dropdown data if needed in view
    $cities      = City::orderBy('name')->get();
    $industries  = Industry::orderBy('name')->get();
    $categories  = Category::orderBy('name')->get();

    return view('admin.customers.show', compact(
        'customer',
        'cities',
        'industries',
        'categories'
    ));
}

}
