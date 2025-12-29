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
        // ✅ STEP 1: Validate input
        $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone1'         => 'required|string|max:20',
            'phone2'         => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'address'        => 'required|string',
            'landmark'       => 'nullable|string|max:255',
            'industry_id'    => 'required|integer',
            'category_id'    => 'required|integer',
            'city_id'        => 'required|integer',
            'image'          => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // ✅ STEP 2: Normalize data (VERY IMPORTANT)
        $name   = trim(strtolower($request->name));
        $phone1 = preg_replace('/\D/', '', $request->phone1);
        $email  = $request->email ? strtolower(trim($request->email)) : null;

        // ✅ STEP 3: GLOBAL DUPLICATE CHECK
        $exists = Customer::whereRaw('LOWER(name) = ?', [$name])
            ->where('phone1', $phone1)
            ->where(function ($q) use ($email) {
                if ($email !== null) {
                    $q->where('email', $email);
                }
            })
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'duplicate' => 'This customer already exists in the system.'
                ])
                ->withInput();
        }

        // ✅ STEP 4: Image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('customers', 'public');
        }

        // ✅ STEP 5: Create customer
        Customer::create([
            'salesman_id'    => Auth::id(),
            'name'           => $request->name,
            'contact_person' => $request->contact_person,
            'phone1'         => $phone1,
            'phone2'         => $request->phone2,
            'email'          => $email,
            'address'        => $request->address,
            'landmark'       => $request->landmark,
            'industry_id'    => $request->industry_id,
            'category_id'    => $request->category_id,
            'city_id'        => $request->city_id,
            'image'          => $imagePath,
        ]);

        return redirect()
            ->route('salesman.customers.index')
            ->with('success', 'Customer added successfully');
    }
}
