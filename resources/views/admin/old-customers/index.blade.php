@extends('layouts.app')

@section('title','Imported Old Customers')

@section('content')

<div class="p-6 text-white">

<h1 class="text-3xl font-bold mb-6 tracking-wide flex items-center gap-2">
    <i data-lucide="database" class="w-7 h-7 text-[#ff2ba6]"></i>
    Imported Customers (Old )
</h1>

<form method="GET" class="flex flex-col md:flex-row gap-4 mb-6">

    <div class="relative w-full md:w-1/3">
        <i data-lucide="search" class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>
        <input type="text" name="search"
               value="{{ request('search') }}"
               placeholder="Search company / contact / phone / email"
               class="w-full pl-12 pr-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-[#ff2ba6]">
    </div>

    <div class="relative w-full md:w-1/4">
        <i data-lucide="users"
           class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>

        <select name="salesman_id"
                class="w-full pl-12 pr-4 py-3 rounded-xl
                       bg-white/10 border border-white/20
                       text-white focus:outline-none focus:ring-2 focus:ring-[#ff2ba6]">
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

    <button type="submit"
        class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] font-semibold shadow hover:opacity-90 flex items-center gap-2">
        <i data-lucide="filter" class="w-5 h-5"></i>
        Filter
    </button>
</form>

<div class="overflow-x-auto bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl hidden md:block">
    <table class="min-w-full text-sm">
        <thead class="bg-white/10 text-white/80">
            <tr>
                <th class="p-4 text-left w-16">Id</th>
                <th class="p-4 text-left">Company </th>
                <th class="p-4 text-left">Person</th>
                <th class="p-4 text-left">Address</th>
                <th class="p-4 text-left">Email</th>
                <th class="p-4 text-left">Mobile Number</th>

            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr class="border-t border-white/10 hover:bg-white/5">
                    <td class="p-4 text-white/70 font-mono">#{{ $customer->id }}</td>

                    <td class="p-4 font-semibold max-w-sm">
                        <div class="whitespace-nowrap overflow-x-auto scrollbar-thin scrollbar-thumb-white/20 flex items-center gap-1">
                            <i data-lucide="building-2" class="w-4 h-4 text-white/70"></i>
                            {{ $customer->company_name }}
                        </div>
                    </td>

                    <td class="p-4 max-w-xs">
                        <div class="whitespace-nowrap overflow-x-auto scrollbar-thin scrollbar-thumb-white/20 flex items-center gap-1">
                            <i data-lucide="user-check" class="w-4 h-4 text-white/70"></i>
                            {{ $customer->contact_person ?? '-' }}
                        </div>
                    </td>

                    <td class="p-4 max-w-xs">
                        <div class="whitespace-nowrap overflow-x-auto scrollbar-thin scrollbar-thumb-white/20 flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-4 h-4 text-white/70"></i>
                            {{ $customer->address ?? '-' }}
                        </div>
                    </td>

                    <td class="p-4 max-w-xs">
                        <div class="whitespace-nowrap overflow-x-auto scrollbar-thin scrollbar-thumb-white/20 flex items-center gap-1">
                            <i data-lucide="mail" class="w-4 h-4 text-white/70"></i>
                            {{ $customer->email ?? '-' }}
                        </div>
                    </td>

                    <td class="p-4 max-w-xs">
                        <div class="whitespace-nowrap overflow-x-auto scrollbar-thin scrollbar-thumb-white/20 flex items-center gap-1">
                            <i data-lucide="phone" class="w-4 h-4 text-white/70"></i>
                            {{ $customer->contact ?? '-' }}
                        </div>
                    </td>


                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-6 text-center text-white/60">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="md:hidden p-1 space-y-4">
    @forelse($customers as $customer)
        <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">

            <h2 class="text-lg font-semibold text-white mb-2 flex items-center">
                <i data-lucide="building-2" class="w-5 h-5 mr-2 text-pink-400"></i>
                {{ $customer->company_name }}
            </h2>

            <p class="text-white/60 text-sm mb-1 flex items-center gap-1">
                <i data-lucide="hash" class="w-4 h-4 text-white/40 shrink-0"></i>
                <strong>ID:</strong> {{ $customer->id }}
            </p>

            <p class="text-white/70 text-sm mb-1 flex items-start gap-1">
                <i data-lucide="user-check" class="w-4 h-4 mt-0.5 text-white/50 shrink-0"></i>
                <strong>Contact Person:</strong>
                <span class="break-words">
                    {{ $customer->contact_person ?? '—' }}
                </span>
            </p>

            <p class="text-white/80 text-sm mb-2 flex items-center gap-1">
                <i data-lucide="phone" class="w-4 h-4 text-white/50 shrink-0"></i>
                <strong>Mobile:</strong>
                {{ $customer->contact ?? '—' }}
            </p>

            <p class="text-white/70 text-sm mb-3 flex items-start gap-1">
                <i data-lucide="mail" class="w-4 h-4 mt-0.5 text-white/50 shrink-0"></i>
                <strong>Email:</strong>
                <span class="break-words">
                    {{ $customer->email ?? '—' }}
                </span>
            </p>

            <p class="text-white/70 text-sm mb-3 flex items-start gap-1">
                <i data-lucide="map-pin" class="w-4 h-4 mt-0.5 text-white/50 shrink-0"></i>
                <strong>Address:</strong>
                <span class="break-words">
                    {{ $customer->address ?? '—' }}
                </span>
            </p>

            <p class="text-white/70 text-sm mt-3 flex items-start gap-1">
                <i data-lucide="briefcase" class="w-4 h-4 mt-0.5 text-[#ff2ba6] shrink-0"></i>
                <strong>Salesman:</strong>
                <span class="break-words">
                    {{ $customer->salesman->name ?? 'N/A' }}
                </span>
            </p>
        </div>
    @empty
        <p class="text-center text-white/50">No records found</p>
    @endforelse
</div>

<div class="mt-6">
    {{ $customers->links() }}
</div>

</div>
@endsection
