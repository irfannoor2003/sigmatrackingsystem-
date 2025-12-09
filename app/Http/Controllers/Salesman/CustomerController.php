<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\City;
use App\Models\Industry;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    // List customers of logged-in salesman
    public function index()
    {
        $salesmanId = Auth::id();

        $customers = Customer::with(['city', 'industry', 'category'])
            ->where('salesman_id', $salesmanId)
            ->orderBy('id', 'desc')
            ->get();

        return view('salesman.customers.index', compact('customers'));
    }

    // Show create form
    public function create()
    {
        $cities = City::orderBy('name')->get();
        $industries = Industry::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('salesman.customers.create', compact('cities', 'industries', 'categories'));
    }

    // Store customer
    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'contact_person'  => 'required|string|max:255',
            'phone1'          => 'required|string|max:20',
            'phone2'          => 'nullable|string|max:20',
            'email'           => 'required|email|max:255',
            'address'         => 'required|string|max:255',

            // Required dropdowns
            'city_id'         => 'required|exists:cities,id',
            'industry_id'     => 'required|exists:industries,id',
            'category_id'     => 'required|exists:categories,id',

            'image'           => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('customers', 'public');
        }

        // Create Customer
        Customer::create([
            'salesman_id'     => Auth::id(),

            'name'            => $request->name,
            'contact_person'  => $request->contact_person,
            'phone1'          => $request->phone1,
            'phone2'          => $request->phone2,
            'email'           => $request->email,
            'address'         => $request->address,

            'city_id'         => $request->city_id,
            'industry_id'     => $request->industry_id,
            'category_id'     => $request->category_id,

            'image'           => $imagePath,


        ]);

        return redirect()->route('salesman.customers.index')
            ->with('success', 'Customer added successfully.');
    }

    // Show customer only if it belongs to logged-in salesman
    public function show($id)
    {
        $salesmanId = Auth::id();

        $customer = Customer::with(['city', 'industry', 'category'])
            ->where('id', $id)
            ->where('salesman_id', $salesmanId)
            ->firstOrFail();

        return view('salesman.customers.show', compact('customer'));
    }
}
