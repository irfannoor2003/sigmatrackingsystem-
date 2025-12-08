<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('salesman_id', Auth::id())->get();
        return view('salesman.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('salesman.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Customer::create([
            'salesman_id' => Auth::id(),
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'email' => $request->email,
            'city' => $request->city,
            'address' => $request->address,
        ]);

        return redirect()->route('salesman.customers.index')
                         ->with('success', 'Customer added successfully');
    }
}
