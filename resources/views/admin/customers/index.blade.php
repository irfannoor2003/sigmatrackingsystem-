@extends('layouts.app')

@section('title','All Customers')

@section('content')

<div class="p-4 px-0 sm:p-6">

    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-wide mb-6">
        All Customers
    </h1>

    <!-- Filters -->
    <form method="GET"
        class="glass mb-6 p-4 sm:p-6 rounded-2xl border border-white/20 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <select name="city_id"
            class="bg-white/10 text-white border border-white/20 p-3 rounded-xl">
            <option value="" class="text-black">All Cities</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}"
                    {{ request('city_id') == $city->id ? 'selected' : '' }}
                    class="text-black">
                    {{ $city->name }}
                </option>
            @endforeach
        </select>

        <select name="category_id"
            class="bg-white/10 text-white border border-white/20 p-3 rounded-xl">
            <option value="" class="text-black">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ request('category_id') == $cat->id ? 'selected' : '' }}
                    class="text-black">
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <select name="industry_id"
            class="bg-white/10 text-white border border-white/20 p-3 rounded-xl">
            <option value="" class="text-black">All Industries</option>
            @foreach($industries as $ind)
                <option value="{{ $ind->id }}"
                    {{ request('industry_id') == $ind->id ? 'selected' : '' }}
                    class="text-black">
                    {{ $ind->name }}
                </option>
            @endforeach
        </select>

        <input type="text"
            name="search"
            placeholder="Search name / phone"
            value="{{ request('search') }}"
            class="bg-white/10 text-white border border-white/20 p-3 rounded-xl">

        <div class="flex flex-wrap gap-3 sm:col-span-2 lg:col-span-4">
            <button type="submit"
                class="px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold shadow hover:opacity-90 w-full sm:w-auto">
                Filter
            </button>

            <a href="{{ route('admin.customers.index') }}"
                class="px-4 py-2 rounded-xl bg-white/20 border border-white/30 text-white font-semibold shadow hover:bg-white/30 w-full sm:w-auto">
                Reset
            </a>
        </div>

    </form>

    <!-- DESKTOP TABLE -->
    <div class="glass rounded-2xl border border-white/20 overflow-hidden shadow-xl hidden md:block">
        <table class="w-full min-w-[600px]">
            <thead class="bg-white/10 backdrop-blur-xl">
                <tr class="text-left text-white/70 text-xs sm:text-sm uppercase tracking-wider">
                    <th class="p-3">ID</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Phone</th>
                    <th class="p-3">City</th>
                    <th class="p-3">Industry</th>
                    <th class="p-3">Category</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $c)
                    <tr class="border-t border-white/10 hover:bg-white/10 transition">
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-white/60">No Customers Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- MOBILE WRAPPER LIKE YOUR VISITS PAGE -->
    <div class="md:hidden p-0 space-y-4 mt-4">

        <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">
            @forelse ($customers as $c)

                <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg mb-3">

                    <h2 class="text-lg font-semibold text-white">
                        {{ $c->name }}
                    </h2>

                    <p class="text-white/80 text-sm mb-1">
                        <span class="text-white/90 font-semibold">Phone:</span> {{ $c->phone1 ?? '—' }}
                    </p>

                    <p class="text-white/70 text-sm mb-1">
                        <span class="text-white/90 font-semibold">City:</span> {{ $c->city->name ?? '—' }}
                    </p>

                    <p class="text-white/70 text-sm mb-1">
                        <span class="text-white/90 font-semibold">Industry:</span> {{ $c->industry->name ?? '—' }}
                    </p>

                    <p class="text-white/70 text-sm mb-3">
                        <span class="text-white/90 font-semibold">Category:</span> {{ $c->category->name ?? '—' }}
                    </p>

                    <a href="{{ route('admin.customers.show', $c->id) }}"
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

    <div class="mt-5">
        {{ $customers->links() }}
    </div>

</div>

@endsection
