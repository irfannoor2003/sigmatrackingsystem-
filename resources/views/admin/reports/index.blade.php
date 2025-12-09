@extends('layouts.app')

@section('title','All Salesmen Visits Report')

@section('content')

<div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-2xl shadow-lg">

    <h1 class="text-2xl font-bold text-white mb-6 tracking-wide">
        Salesmen Visits Report
    </h1>

    <!-- Filters -->
    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">

        <!-- Salesman -->
        <select name="salesman_id"
                class="bg-white/10 text-white border border-white/20 p-3 rounded-xl outline-none">
            <option value="" class="text-black">All Salesmen</option>
            @foreach($salesmen as $s)
                <option value="{{ $s->id }}"
                        {{ request('salesman_id') == $s->id ? 'selected' : '' }}
                        class="text-black">
                    {{ $s->name }}
                </option>
            @endforeach
        </select>

        <!-- Date From -->
        <input type="date"
               name="from_date"
               value="{{ request('from_date') }}"
               class="bg-white/10 text-white border border-white/20 p-3 rounded-xl outline-none">

        <!-- Date To -->
        <input type="date"
               name="to_date"
               value="{{ request('to_date') }}"
               class="bg-white/10 text-white border border-white/20 p-3 rounded-xl outline-none">

        <!-- Status -->
        <select name="status"
                class="bg-white/10 text-white border border-white/20 p-3 rounded-xl outline-none">
            <option value="" class="text-black">All Status</option>
            <option value="started" {{ request('status')=='started'?'selected':'' }} class="text-black">
                Started
            </option>
            <option value="completed" {{ request('status')=='completed'?'selected':'' }} class="text-black">
                Completed
            </option>
        </select>

        <!-- Buttons -->
        <div class="flex gap-3">
            <button type="submit"
                class="px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold shadow hover:opacity-90">
                Filter
            </button>

            <button type="button"
                onclick="window.print()"
                class="px-4 py-2 rounded-xl bg-white/20 border border-white/30 text-white font-semibold shadow hover:bg-white/30">
                Print
            </button>
        </div>

    </form>

    <!-- Table -->
    <div class="overflow-x-auto mt-4">
        <table class="w-full border border-white/20 rounded-xl overflow-hidden">
            <thead class="bg-white/10 backdrop-blur-xl border-b border-white/20">
                <tr>
                    <th class="p-3 text-left text-white text-sm">Salesman</th>
                    <th class="p-3 text-left text-white text-sm">Customer</th>
                    <th class="p-3 text-left text-white text-sm">Purpose</th>
                    <th class="p-3 text-left text-white text-sm">Status</th>
                    <th class="p-3 text-left text-white text-sm">Notes</th>
                    <th class="p-3 text-left text-white text-sm">Date</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-white/10">

                @forelse($visits as $v)
                    <tr class="hover:bg-white/5 transition">
                        <td class="p-2 text-white/90">{{ $v->salesman->name }}</td>
                        <td class="p-2 text-white/90">{{ $v->customer->name }}</td>

                        <td class="p-2 text-white/90">
                            <a href="{{ route('admin.reports.show', $v->id) }}"
                               class="text-indigo-300 hover:underline">
                                {{ $v->purpose }}
                            </a>
                        </td>

                        <td class="p-2 text-white/90">{{ ucfirst($v->status) }}</td>
                        <td class="p-2 text-white/90">{{ $v->notes }}</td>

                        <td class="p-2 text-white/90">{{ $v->started_at->format('Y-m-d H:i') }}</td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6"
                            class="p-6 text-center text-white/70 bg-white/5">
                            No report found
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>
    </div>

</div>

@endsection
