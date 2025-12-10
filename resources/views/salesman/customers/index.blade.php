@extends('layouts.app')
@section('title', 'My Customers')

@section('content')

<div class="p-6">

    <h1 class="text-3xl font-bold text-white mb-6 tracking-wide">My Customers</h1>

    {{-- Add Customer Button --}}
    <div class="mb-6 flex justify-between">
        <a href="{{ route('salesman.customers.create') }}"
            class="px-5 py-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500
                   text-white font-semibold shadow hover:opacity-90 transition">
            + Add Customer
        </a>
    </div>


    <!-- ================= DESKTOP TABLE VIEW ================= -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20
                rounded-2xl shadow-xl overflow-hidden hidden md:block">

        <table class="w-full table-auto text-white">
            <thead>
                <tr class="bg-white/10 text-white/80">
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Contact</th>
                    <th class="p-3 text-left">Phone</th>
                    <th class="p-3 text-left">Address</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($customers as $c)
                    <tr class="border-t border-white/10 hover:bg-white/5 transition">

                        <td class="p-3">{{ $c->name }}</td>

                        <td class="p-3 text-white/80">
                            {{ $c->contact_person ?? '-' }}
                        </td>

                        <td class="p-3 text-white/80">
                            {{ $c->phone1 ?? '-' }}
                        </td>

                        <td class="p-3 text-white/60">
                            {{ $c->address ?? '-' }}
                        </td>

                        <td class="p-3">
                            <a href="{{ route('salesman.customers.show', $c->id) }}"
                                class="px-3 py-2 rounded-lg bg-blue-500/30 border border-blue-400/40
                                       text-blue-100 text-sm text-center hover:bg-blue-500/40 transition">
                                View
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="p-6 text-center text-white/70 bg-white/5">
                            No customers found
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>



    <!-- ================= MOBILE VIEW WITH SAME WRAPPER AS VISITS ================= -->
    <div class="md:hidden p-4 space-y-4">

        <!-- SAME WRAPPER DIV USED IN MY VISITS -->
        <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">

            @forelse ($customers as $customer)

                <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg mb-3">

                    <h2 class="text-lg font-semibold text-white">
                        {{ $customer->name }}
                    </h2>

                    <p class="text-white/70 text-sm mb-2">
                        Contact Person: {{ $customer->contact_person ?? '—' }}
                    </p>

                    <p class="text-white/80 text-sm mb-2">
                        Phone: {{ $customer->phone1 ?? '—' }}
                    </p>

                    <p class="text-white/70 text-sm mb-3">
                        Address: {{ $customer->address ?? '—' }}
                    </p>

                    <a href="{{ route('salesman.customers.show', $customer->id) }}"
                        class="px-3 py-2 rounded-lg bg-blue-500/30 border border-blue-400/40
                               text-blue-100 text-sm text-center hover:bg-blue-500/40 transition block">
                        View
                    </a>

                </div>

            @empty
                <p class="text-center text-white/50">No customers found</p>
            @endforelse

        </div>

    </div>

</div>

@endsection
