@extends('layouts.app')

@section('title','All Customers')

@section('content')

<div class="p-6">

    <h1 class="text-3xl font-extrabold text-white tracking-wide mb-6">
        All Customers
    </h1>

    <div class="glass rounded-2xl border border-white/20 overflow-hidden shadow-xl">

        <table class="w-full">
            <thead class="bg-white/10 backdrop-blur-xl">
                <tr class="text-left text-white/70 text-sm uppercase tracking-wider">
                    <th class="p-3">ID</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Phone</th>
                    <th class="p-3">City</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($customers as $c)
                <tr class="border-t border-white/10 hover:bg-white/5 transition">
                    <td class="p-2 text-white/90">{{ $c->id }}</td>
                    <td class="p-2 text-white">{{ $c->name }}</td>
                    <td class="p-2 text-white/80">{{ $c->phone1 }}</td>
                    <td class="p-2 text-white/70">{{ $c->city->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>

@endsection
