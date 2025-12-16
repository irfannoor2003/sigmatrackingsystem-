@extends('layouts.app')

@section('title','Imported Old Customers')

@section('content')

<div class="p-6 text-white">

<!-- Header -->
<h1 class="text-3xl font-bold mb-6 tracking-wide flex items-center gap-2">
    <i data-lucide="database" class="w-7 h-7 text-[#ff2ba6]"></i>
    Imported Customers (Old)
</h1>

<!-- Filters -->
<form method="GET" class="flex flex-col md:flex-row gap-4 mb-6">

    <!-- Search -->
    <div class="relative w-full md:w-1/3">
        <i data-lucide="search" class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>
        <input type="text" name="search"
               value="{{ request('search') }}"
               placeholder="Search company / contact / phone / email"
               class="w-full pl-12 pr-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-[#ff2ba6]">
    </div>

    <!-- Salesman Filter -->
    <div class="relative w-full md:w-1/4">
        <i data-lucide="users"
           class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>

        <select name="salesman_id"
                class="w-full pl-12 pr-4 py-3 rounded-xl
                       bg-white/10 border border-white/20
                       text-white placeholder-white
                       focus:outline-none focus:ring-2 focus:ring-[#ff2ba6]">
            <option value="" class="text-black">All Salesmen</option>
            @foreach($salesmen as $salesman)
                <option value="{{ $salesman->id }}"
                    class="text-black"
                    {{ request('salesman_id') == $salesman->id ? 'selected' : '' }}>
                    {{ $salesman->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Filter Button -->
    <button type="submit"
        class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] font-semibold shadow hover:opacity-90 flex items-center gap-2">
        <i data-lucide="filter" class="w-5 h-5"></i>
        Filter
    </button>
</form>

<!-- Desktop Table -->
<div class="overflow-x-auto bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl hidden md:block">
    <table class="min-w-full text-sm">
        <thead class="bg-white/10 text-white/80">
    <tr>
        <th class="p-4 text-left w-16">
            <div class="flex items-center gap-2">
                <i data-lucide="hash" class="w-4 h-4 text-white/40"></i>
                Id
            </div>
        </th>

        <th class="p-4 text-left">
            <div class="flex items-center gap-2">
                <i data-lucide="building-2" class="w-4 h-4 text-[#ff2ba6]"></i>
                Company
            </div>
        </th>

        <th class="p-4 text-left">
            <div class="flex items-center gap-2">
                <i data-lucide="user-check" class="w-4 h-4 text-white/70"></i>
                Person
            </div>
        </th>

        <th class="p-4 text-left">
            <div class="flex items-center gap-2">
                <i data-lucide="map-pin" class="w-4 h-4 text-white/70"></i>
                Address
            </div>
        </th>

        <th class="p-4 text-left">
            <div class="flex items-center gap-2">
                <i data-lucide="mail" class="w-4 h-4 text-white/70"></i>
                Email
            </div>
        </th>

        <th class="p-4 text-left">
            <div class="flex items-center gap-2">
                <i data-lucide="phone" class="w-4 h-4 text-white/70"></i>
                Mobile Number
            </div>
        </th>
    </tr>
</thead>

        <tbody>
            @forelse($customers as $customer)
                <tr class="border-t border-white/10 hover:bg-white/5">
                    <td class="p-4 text-white/70 font-mono">#{{ $customer->id }}</td>
                    <td class="p-4 font-semibold">{{ $customer->company_name }}</td>
                    <td class="p-4">{{ $customer->contact_person ?? '-' }}</td>
                    <td class="p-4">{{ $customer->address ?? '-' }}</td>
                    <td class="p-4">{{ $customer->email ?? '-' }}</td>
                    <td class="p-4">{{ $customer->contact ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-white/60">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Cards -->
<div class="md:hidden p-1 space-y-4">
    @forelse($customers as $customer)
        <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">
            <h2 class="text-lg font-semibold text-white mb-2 flex items-center">
                <i data-lucide="building-2" class="w-5 h-5 mr-2 text-pink-400"></i>
                {{ $customer->company_name }}
            </h2>

             <p class="text-white/70 text-sm mb-1 flex items-center">
<i data-lucide="hash" class="w-4 h-4 mr-2 text-white/50"></i>
                <span class="font-medium" >Id:</span> {{ $customer->id }}
            </p>

            <p class="text-white/70 text-sm mb-1 flex items-center">
                <i data-lucide="user-round" class="w-4 h-4 mr-2 text-white/50"></i>
                <span class="font-medium mr-1">Contact Person:</span>
                {{ $customer->contact_person ?? '—' }}
            </p>

            <p class="text-white/80 text-sm mb-3 flex items-center">
                <i data-lucide="phone-call" class="w-4 h-4 mr-2 text-white/50"></i>
                <span class="font-medium mr-1">Mobile:</span>
                {{ $customer->contact ?? '—' }}
            </p>

            <p class="text-white/70 text-sm mb-3 flex items-start">
                <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-white/50 flex-shrink-0 mt-0.5"></i>
                <span class="font-medium mr-1">Address:</span>
                {{ $customer->address ?? '—' }}
            </p>

            <p class="text-white/70 text-sm flex items-center">
                <i data-lucide="user-check" class="w-4 h-4 mr-2 text-blue-400"></i>
                <span class="font-medium mr-1">Salesman:</span>
                {{ $customer->salesman->name ?? '—' }}
            </p>
        </div>
    @empty
        <p class="text-center text-white/50">No records found</p>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $customers->links() }}
</div>

</div>
@endsection
