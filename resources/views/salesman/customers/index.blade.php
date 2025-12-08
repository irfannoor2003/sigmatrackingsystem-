@extends('layouts.app')

@section('title', 'My Customers')

@section('content')

<div class="p-6">

    <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-white tracking-wide">My Customers</h2>

    <a href="{{ route('salesman.customers.create') }}"
       class="px-5 py-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500
              text-white font-semibold shadow hover:opacity-90 transition">
        + Add Customer
    </a>

</div>






    <!-- Table Container -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-lg overflow-hidden">

        <table class="w-full text-left text-white/90">
            <thead class="bg-white/10 border-b border-white/20">
                <tr>
                    <th class="px-4 py-3 text-sm font-semibold text-white/80">Name</th>
                    <th class="px-4 py-3 text-sm font-semibold text-white/80">Contact</th>
                    <th class="px-4 py-3 text-sm font-semibold text-white/80">Phone</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($customers as $customer)
                <tr class="border-b border-white/10 hover:bg-white/10 transition">
                    <td class="px-4 py-3">{{ $customer->name }}</td>
                    <td class="px-4 py-3">{{ $customer->contact_person }}</td>
                    <td class="px-4 py-3">{{ $customer->phone1 }}</td>
                </tr>
                @endforeach

                @if($customers->count() == 0)
                <tr>
                    <td colspan="3" class="px-4 py-6 text-center text-white/60">
                        No customers found.
                    </td>
                </tr>
                @endif

            </tbody>
        </table>

    </div>

</div>

@endsection
