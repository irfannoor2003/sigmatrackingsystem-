@extends('layouts.app')

@section('title','Salesman Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

  <div class="bg-white p-6 rounded-xl shadow">
    <h3 class="text-sm text-gray-500">My Customers</h3>
    <p class="text-2xl font-bold mt-2">{{ \App\Models\Customer::where('salesman_id', auth()->id())->count() }}</p>
    <a href="{{ route('salesman.customers.index') }}" class="text-xs text-indigo-600 mt-3 inline-block">View</a>
  </div>

  <div class="bg-white p-6 rounded-xl shadow">
    <h3 class="text-sm text-gray-500">My Visits (this month)</h3>
    <p class="text-2xl font-bold mt-2">{{ \App\Models\Visit::where('salesman_id', auth()->id())->whereMonth('created_at', now()->month)->count() }}</p>
    <a href="{{ route('salesman.visits.index') }}" class="text-xs text-indigo-600 mt-3 inline-block">View</a>
  </div>

</div>

<div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
  <a href="{{ route('salesman.customers.create') }}" class="block bg-indigo-600 text-white p-6 rounded-xl shadow hover:shadow-lg">
    <h4 class="font-semibold">Add Customer</h4>
    <p class="text-sm mt-2">Quick add a customer to your list.</p>
  </a>

  <a href="{{ route('salesman.visits.create') }}" class="block bg-green-600 text-white p-6 rounded-xl shadow hover:shadow-lg">
    <h4 class="font-semibold">Start Visit</h4>
    <p class="text-sm mt-2">Open a visit (office-only start).</p>
  </a>

  <a href="{{ route('salesman.attendance.index') }}" class="block bg-yellow-500 text-white p-6 rounded-xl shadow hover:shadow-lg">
    <h4 class="font-semibold">Attendance</h4>
    <p class="text-sm mt-2">Clock in / Clock out.</p>
  </a>
</div>
@endsection
