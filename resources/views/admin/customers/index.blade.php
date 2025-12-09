@extends('layouts.app')

@section('title','All Customers')

@section('content')

<div class="p-6">

    <h1 class="text-3xl font-extrabold text-white tracking-wide mb-6">
        All Customers
    </h1>

    <!-- Filters -->
    <form method="GET"
        class="glass mb-6 p-6 rounded-2xl border border-white/20 grid grid-cols-1 md:grid-cols-4 gap-4">

        <!-- City Filter -->
        <select name="city_id"
            class="bg-white/10 text-white border border-white/20 p-3 rounded-xl outline-none">
            <option value="" class="text-black">All Cities</option>

            @foreach($cities as $city)
                <option value="{{ $city->id }}"
                    {{ request('city_id') == $city->id ? 'selected' : '' }}
                    class="text-black">
                    {{ $city->name }}
                </option>
            @endforeach
        </select>

        <!-- Category Filter -->
        <select name="category_id"
            class="bg-white/10 text-white border border-white/20 p-3 rounded-xl outline-none">
            <option value="" class="text-black">All Categories</option>

            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ request('category_id') == $cat->id ? 'selected' : '' }}
                    class="text-black">
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <!-- Industry Filter -->
        <select name="industry_id"
            class="bg-white/10 text-white border border-white/20 p-3 rounded-xl outline-none">
            <option value="" class="text-black">All Industries</option>

            @foreach($industries as $ind)
                <option value="{{ $ind->id }}"
                    {{ request('industry_id') == $ind->id ? 'selected' : '' }}
                    class="text-black">
                    {{ $ind->name }}
                </option>
            @endforeach
        </select>

        <!-- Search -->
        <input type="text"
            name="search"
            placeholder="Search name / phone"
            value="{{ request('search') }}"
            class="bg-white/10 text-white border border-white/20 p-3 rounded-xl outline-none">

        <!-- Buttons -->
        <div class="flex gap-3 md:col-span-4">
            <button type="submit"
                class="px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold shadow hover:opacity-90">
                Filter
            </button>

            <a href="{{ route('admin.customers.index') }}"
                class="px-4 py-2 rounded-xl bg-white/20 border border-white/30 text-white font-semibold shadow hover:bg-white/30">
                Reset
            </a>
        </div>

    </form>

    <!-- Table -->
    <div class="glass rounded-2xl border border-white/20 overflow-hidden shadow-xl">

        <table class="w-full">
            <thead class="bg-white/10 backdrop-blur-xl">
                <tr class="text-left text-white/70 text-sm uppercase tracking-wider">
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
                    <tr class="border-t border-white/10 hover:bg-white/5 transition">
                        <td class="p-2 text-white/90">{{ $c->id }}</td>

                        <td class="p-2">
                            <a href="{{ route('admin.customers.show', $c->id) }}"
                                class="text-indigo-300 hover:text-indigo-400 underline font-semibold transition">
                                {{ $c->name }}
                            </a>
                        </td>

                        <td class="p-2 text-white/80">{{ $c->phone1 }}</td>

                        <td class="p-2 text-white/70">
                            {{ $c->city->name ?? '-' }}
                        </td>

                        <td class="p-2 text-white/70">
                            {{ $c->industry->name ?? '-' }}
                        </td>

                        <td class="p-2 text-white/70">
                            {{ $c->category->name ?? '-' }}
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-white/60 text-lg">
                            No Customers Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

@endsection
