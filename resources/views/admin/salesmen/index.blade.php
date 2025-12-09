@extends('layouts.app')

@section('title','Salesmen')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-white tracking-wide">Salesmen</h2>

    <a href="{{ route('admin.salesmen.create') ?? '#' }}"
       class="px-5 py-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500
              text-white font-semibold shadow hover:opacity-90 transition">
        Add Salesman
    </a>
</div>

<div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg">

    <table class="w-full">
        <thead>
            <tr class="text-left text-sm text-white/70 border-b border-white/20">
                <th class="p-3">Name</th>
                <th class="p-3">Email</th>
                <th class="p-3">Created</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-white/10">
            @php
                $salesmen = \App\Models\User::where('role','salesman')->paginate(20);
            @endphp

            @forelse($salesmen as $s)
                <tr class="hover:bg-white/5 transition">
                    <td class="p-2 text-white">{{ $s->name }}</td>
                    <td class="p-2 text-white/90">{{ $s->email }}</td>
                    <td class="p-2 text-white/60 text-sm">{{ $s->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3"
                        class="p-6 text-center text-white/70 bg-white/5">
                        No Salesman Found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-5">
        {{ $salesmen->links() }}
    </div>

</div>

@endsection
