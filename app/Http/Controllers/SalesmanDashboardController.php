<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesmanDashboardController extends Controller
{
    public function index()
    {
        return view('salesman.dashboard');
    }
}
