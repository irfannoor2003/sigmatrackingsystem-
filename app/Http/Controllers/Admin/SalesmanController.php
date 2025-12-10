<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class SalesmanController extends Controller
{
   public function index()
{
    $salesmen = User::where('role', 'salesman')->paginate(10);
    return view('admin.salesmen.index', compact('salesmen'));
}


    public function create()
    {
        return view('admin.salesmen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'salesman',
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.salesmen.index')->with('success', 'Salesman added successfully');
    }
}
