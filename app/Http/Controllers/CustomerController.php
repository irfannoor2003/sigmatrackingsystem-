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
            'name'           => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone1'         => 'required|string|max:20',
            'phone2'         => 'nullable|string|max:20',
            'email'          => 'required|email',
            'address'        => 'required|string',
            'landmark'       => 'nullable|string|max:255',
            'industry_id'    => 'required|integer',
            'category_id'    => 'required|integer',
            'city_id'        => 'required|integer',
            'image'          => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('customers', 'public');
        }

        // Create customer
        Customer::create([
            'salesman_id'    => Auth::id(),
            'name'           => $request->name,
            'contact_person' => $request->contact_person,
            'phone1'         => $request->phone1,
            'phone2'         => $request->phone2,
            'email'          => $request->email,
            'address'        => $request->address,
            'landmark'       => $request->landmark,
            'industry_id'    => $request->industry_id,
            'category_id'    => $request->category_id,
            'city_id'        => $request->city_id,
            'image'          => $imagePath,
        ]);

        return redirect()->route('salesman.customers.index')
                         ->with('success', 'Customer added successfully');
    }
}
