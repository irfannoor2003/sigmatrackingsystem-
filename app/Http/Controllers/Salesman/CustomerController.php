<?php

namespace App\Http\Controllers\Salesman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    // List customers assigned to this salesman
    public function index()
    {
        $salesmanId = Auth::id();
        $customers = Customer::where('salesman_id', $salesmanId)->get();

        return view('salesman.customers.index', compact('customers'));
    }

    // Show form to add a new customer
    public function create()
    {
        return view('salesman.customers.create');
    }

    // Store a new customer
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone1' => 'required|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'city_id' => 'nullable|integer',
        ]);

        Customer::create([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'email' => $request->email,
            'city_id' => $request->city_id,
            'salesman_id' => Auth::id(),
        ]);

        return redirect()->route('salesman.customers.index')->with('success', 'Customer added successfully.');
    }
}
