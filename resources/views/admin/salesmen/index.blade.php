@extends('layouts.app')

@section('title','Salesmen')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-white tracking-wide">Salesmen</h2>

    <a href="{{ route('admin.salesmen.create') }}"
       class="px-5 py-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500
              text-white font-semibold shadow hover:opacity-90 transition">
        Add Salesman
    </a>
</div>

<div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg">


    {{-- ▌▌ DESKTOP LAYOUT (TABLE) --}}
    <div class="hidden md:block">
        <table class="w-full">
            <thead>
                <tr class="text-left text-sm text-white/70 border-b border-white/20">
                    <th class="p-3">Name</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Customers (Total)</th>
                    <th class="p-3">Customers (This Month)</th>
                    <th class="p-3">Visits (This Month)</th>
                    <th class="p-3">Created</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-white/10">

                @php
                    use App\Models\Customer;
                    use App\Models\Visit;
                    use Carbon\Carbon;

                    $start = Carbon::now()->startOfMonth();
                    $end   = Carbon::now()->endOfMonth();
                @endphp

                @forelse($salesmen as $s)

                    @php
                        $totalCustomers = Customer::where('salesman_id', $s->id)->count();
                        $customersMonthly = Customer::where('salesman_id', $s->id)
                            ->whereBetween('created_at', [$start, $end])
                            ->count();

                        $visitsMonthly = Visit::where('salesman_id', $s->id)
                            ->where('status', 'completed')
                            ->whereBetween('created_at', [$start, $end])
                            ->count();
                    @endphp

                    <tr class="hover:bg-white/5 transition">
                        <td class="p-2 text-white">{{ $s->name }}</td>
                        <td class="p-2 text-white/90">{{ $s->email }}</td>
                        <td class="p-2 text-white/80">{{ $totalCustomers }}</td>
                        <td class="p-2 text-indigo-300 font-semibold">{{ $customersMonthly }}</td>
                        <td class="p-2 text-purple-300 font-semibold">{{ $visitsMonthly }}</td>
                        <td class="p-2 text-white/60 text-sm">{{ $s->created_at->format('Y-m-d') }}</td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6"
                            class="p-6 text-center text-white/70 bg-white/5">
                            No Salesman Found
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>



    {{-- ▌▌ MOBILE LAYOUT (CARDS) --}}
    <div class="md:hidden space-y-4">

        @forelse($salesmen as $s)

            @php
                $totalCustomers = $s->customers()->count();
                $customersMonthly = $s->customers()
                    ->whereBetween('created_at', [$start, $end])
                    ->count();

                $visitsMonthly = $s->visits()
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
            @endphp

            <div class="p-4 bg-white/10 rounded-xl border border-white/10 shadow">
                <div class="text-lg font-semibold text-white">{{ $s->name }}</div>
                <div class="text-white/70 text-sm">{{ $s->email }}</div>

                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">

                    <div class="bg-white/5 p-3 rounded-lg">
                        <div class="text-white/60">Total Customers</div>
                        <div class="text-white font-semibold text-lg">{{ $totalCustomers }}</div>
                    </div>

                    <div class="bg-white/5 p-3 rounded-lg">
                        <div class="text-white/60">This Month</div>
                        <div class="text-indigo-300 font-semibold text-lg">{{ $customersMonthly }}</div>
                    </div>

                    <div class="bg-white/5 p-3 rounded-lg col-span-2">
                        <div class="text-white/60">Visits (This Month)</div>
                        <div class="text-purple-300 font-semibold text-lg">{{ $visitsMonthly }}</div>
                    </div>

                </div>

                <div class="text-xs text-white/50 mt-3">
                    Joined: {{ $s->created_at->format('Y-m-d') }}
                </div>
            </div>

        @empty

            <div class="p-6 text-center text-white/70 bg-white/5 rounded-xl">
                No Salesman Found
            </div>

        @endforelse

    </div>




    <div class="mt-5">
        {{ $salesmen->links() }}
    </div>

</div>

@endsection
