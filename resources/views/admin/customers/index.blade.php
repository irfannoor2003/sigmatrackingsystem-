@extends('layouts.app')

@section('title', 'All Customers')

@section('content')

    <div class="p-4 px-0 sm:p-6">

        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-wide mb-6">
            <i data-lucide="building-2" class="w-7 h-7 inline mr-2 text-[var(--hf-magenta-light)]"></i>
            All Customers
        </h1>

        <form method="GET"
            class="glass mb-6 p-4 sm:p-6 rounded-2xl border border-white/20 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="relative flex items-center">
                <i data-lucide="map-pin" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                <select name="city_id" class="bg-white/10 text-white border border-white/20 p-3 pl-10 rounded-xl w-full">
                    <option value="" class="text-black">All Cities</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}
                            class="text-black">
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="relative flex items-center">
                <i data-lucide="users" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                <select name="salesman_id"
                    class="bg-white/10 text-white border border-white/20 p-3 pl-10 rounded-xl w-full">
                    <option value="" class="text-black">All Salesmen</option>
                    @foreach($salesmen as $s)
                        <option value="{{ $s->id }}"
                            {{ request('salesman_id') == $s->id ? 'selected' : '' }}
                            class="text-black">
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="relative flex items-center">
                <i data-lucide="tag" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                <select name="category_id" class="bg-white/10 text-white border border-white/20 p-3 pl-10 rounded-xl w-full">
                    <option value="" class="text-black">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}
                            class="text-black">
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="relative flex items-center">
                <i data-lucide="factory" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                <select name="industry_id" class="bg-white/10 text-white border border-white/20 p-3 pl-10 rounded-xl w-full">
                    <option value="" class="text-black">All Industries</option>
                    @foreach ($industries as $ind)
                        <option value="{{ $ind->id }}" {{ request('industry_id') == $ind->id ? 'selected' : '' }}
                            class="text-black">
                            {{ $ind->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="relative flex items-center lg:col-span-2">
                <i data-lucide="search" class="absolute left-3 w-5 h-5 text-white/50 pointer-events-none"></i>
                <input type="text" name="search" placeholder="Search name / phone" value="{{ request('search') }}"
                    class="bg-white/10 text-white border border-white/20 p-3 pl-10 rounded-xl w-full">
            </div>


            <div class="flex flex-wrap gap-3 sm:col-span-2 lg:col-span-2 justify-end">
                <button type="submit"
                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6] text-white font-semibold shadow hover:opacity-90 w-full sm:w-auto">
                    <i data-lucide="filter" class="w-5 h-5"></i>
                    Filter
                </button>

                <a href="{{ route('admin.customers.index') }}"
                    class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white font-semibold shadow hover:bg-white/30 w-full sm:w-auto">
                    <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                    Reset
                </a>
            </div>

        </form>

        <form action="{{ route('admin.customers.export.bulk') }}" method="POST">
            @csrf

            <div class="glass rounded-2xl border border-white/20 overflow-hidden shadow-xl hidden md:block">
                <table class="w-full min-w-[600px]">
                    <thead class="bg-white/10 backdrop-blur-xl">
    <tr class="text-left text-white/70 text-xs sm:text-sm uppercase tracking-wider">

        {{-- Select All --}}
        <th class="p-3">
            <input type="checkbox" id="select-all" class="cursor-pointer">
        </th>

        <th class="p-3">
            <div class="flex items-center gap-2">
                <i data-lucide="hash" class="w-4 h-4 text-white/50"></i>
                Id
            </div>
        </th>

        <th class="p-3">
            <div class="flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4 text-white/50"></i>
                Name
            </div>
        </th>

        <th class="p-3">
            <div class="flex items-center gap-2">
                <i data-lucide="phone" class="w-4 h-4 text-white/50"></i>
                Phone
            </div>
        </th>

        <th class="p-3">
            <div class="flex items-center gap-2">
                <i data-lucide="map-pin" class="w-4 h-4 text-white/50"></i>
                City
            </div>
        </th>

        <th class="p-3">
            <div class="flex items-center gap-2">
                <i data-lucide="factory" class="w-4 h-4 text-white/50"></i>
                Industry
            </div>
        </th>

        <th class="p-3">
            <div class="flex items-center gap-2">
                <i data-lucide="layers" class="w-4 h-4 text-white/50"></i>
                Category
            </div>
        </th>

        <th class="p-3">
            <div class="flex items-center gap-2">
                <i data-lucide="settings" class="w-4 h-4 text-white/50"></i>
                Action
            </div>
        </th>

    </tr>
</thead>


                    <tbody>
                        @forelse ($customers as $c)
                            <tr class="border-t border-white/10 hover:bg-white/10 transition">

                                <td class="p-3">
                                    <input type="checkbox" name="selected_customers[]" value="{{ $c->id }}"
                                        class="cursor-pointer customer-checkbox">
                                </td>

                                <td class="p-2 text-white/90">{{ $c->id }}</td>

                                <td class="p-2">
                                    <a href="{{ route('admin.customers.show', $c->id) }}"
                                        class="text-indigo-300 hover:text-indigo-400 underline font-semibold">
                                        {{ $c->name }}
                                    </a>
                                </td>

                                <td class="p-2 text-white/80">{{ $c->phone1 }}</td>
                                <td class="p-2 text-white/70">{{ $c->city->name ?? '-' }}</td>
                                <td class="p-2 text-white/70">{{ $c->industry->name ?? '-' }}</td>
                                <td class="p-2 text-white/70">{{ $c->category->name ?? '-' }}</td>

                                <td class="p-2">
                                    <a href="{{ route('admin.customers.export.single', $c->id) }}"
                                        class="flex items-center gap-1 justify-center text-sm px-3 py-1 rounded-lg bg-green-500/20 border border-green-400/40
                                            text-green-200 hover:bg-green-500/30 transition">
                                        <i data-lucide="file-text" class="w-4 h-4"></i>
                                        Export
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-white/60">No Customers Found</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 mt-4">

                <button type="submit" id="export-selected-btn" disabled
                    class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-green-500/50 to-emerald-500/50
                        text-white font-semibold shadow disabled:opacity-50 hover:opacity-90 w-full sm:w-auto hidden sm:flex transition">
                    <i data-lucide="download" class="w-5 h-5"></i>
                    Export Selected
                </button>

                <a href="{{ route('admin.customers.export.all') }}"
                    class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-[#ff2ba6] to-[#ff2ba6]
                        text-white font-semibold shadow hover:opacity-90 w-full sm:w-auto text-center">
                    <i data-lucide="archive" class="w-5 h-5"></i>
                    Export All
                </a>

            </div>

        </form>

        <div class="md:hidden p-0 space-y-4 mt-4">

            @forelse ($customers as $c)
                <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">

                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-semibold text-white">
                            <i data-lucide="user" class="w-4 h-4 inline mr-1 text-white/70"></i>{{ $c->name }}
                        </h2>
                        <input type="checkbox" name="selected_customers[]" value="{{ $c->id }}"
                            class="cursor-pointer customer-checkbox">
                    </div>


                    <p class="text-white/80 text-sm mb-1">
                        <span class="text-white/90 font-semibold">
                            <i data-lucide="phone" class="w-4 h-4 inline mr-1 text-white/70"></i>Phone:
                        </span> {{ $c->phone1 ?? '—' }}
                    </p>

                    <p class="text-white/70 text-sm mb-1">
                        <span class="text-white/90 font-semibold">
                            <i data-lucide="map-pin" class="w-4 h-4 inline mr-1 text-white/70"></i>City:
                        </span> {{ $c->city->name ?? '—' }}
                    </p>

                    <p class="text-white/70 text-sm mb-1">
                        <span class="text-white/90 font-semibold">
                            <i data-lucide="factory" class="w-4 h-4 inline mr-1 text-white/70"></i>Industry:
                        </span> {{ $c->industry->name ?? '—' }}
                    </p>

                    <p class="text-white/70 text-sm mb-3">
                        <span class="text-white/90 font-semibold">
                            <i data-lucide="tag" class="w-4 h-4 inline mr-1 text-white/70"></i>Category:
                        </span> {{ $c->category->name ?? '—' }}
                    </p>

                    <a href="{{ route('admin.customers.show', $c->id) }}"
                        class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-blue-500/30 border border-blue-400/40
                        text-blue-100 text-sm text-center hover:bg-blue-500/40 transition block mb-2">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        View Details
                    </a>

                    <a href="{{ route('admin.customers.export.single', $c->id) }}"
                        class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-green-500/20 border border-green-400/40
                        text-green-200 text-sm text-center hover:bg-green-500/30 transition block">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        Export
                    </a>

                </div>
            @empty
                <p class="text-center text-white/50">No customers found</p>
            @endforelse

            <button type="submit"
                class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-green-500/50 to-emerald-500/50
                    text-white font-semibold shadow disabled:opacity-50 hover:opacity-90 w-full text-center">
                <i data-lucide="download" class="w-5 h-5"></i>
                Export Selected
            </button>


        </div>

        <div class="mt-5">
            {{ $customers->links() }}
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectAllCheckbox = document.getElementById('select-all');
            const customerCheckboxes = document.querySelectorAll("input[name='selected_customers[]']");
            const exportSelectedBtn = document.getElementById('export-selected-btn');

            function updateExportButtonState() {
                const checkedCount = document.querySelectorAll(".customer-checkbox:checked").length;
                if (exportSelectedBtn) {
                    exportSelectedBtn.disabled = checkedCount === 0;
                    exportSelectedBtn.classList.toggle('from-green-500', checkedCount > 0);
                    exportSelectedBtn.classList.toggle('to-emerald-500', checkedCount > 0);
                    exportSelectedBtn.classList.toggle('from-green-500/50', checkedCount === 0);
                    exportSelectedBtn.classList.toggle('to-emerald-500/50', checkedCount === 0);
                }
            }

            // 1. Select All Toggle
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    customerCheckboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateExportButtonState();
                });
            }

            // 2. Individual Checkbox Change (for Export Button state)
            customerCheckboxes.forEach(cb => {
                cb.classList.add('customer-checkbox'); // Ensure all individual checkboxes have the class
                cb.addEventListener('change', updateExportButtonState);
            });

            // Initial state check
            updateExportButtonState();
        });
    </script>

@endsection
