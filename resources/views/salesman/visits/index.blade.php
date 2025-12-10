@extends('layouts.app')
@section('title', 'My Visits')

@section('content')

<div class="p-6">

    <h1 class="text-3xl font-bold text-white mb-6 tracking-wide">My Visits</h1>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-500/20 border border-green-400/40
                    text-green-100 rounded-xl backdrop-blur-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Container --}}
    <div class="bg-white/10 backdrop-blur-xl border border-white/20
                rounded-2xl shadow-xl overflow-hidden">

        <table class="w-full table-auto text-white hidden md:table">
            <thead>
                <tr class="bg-white/10 text-white/80">
                    <th class="p-3 text-left">Customer</th>
                    <th class="p-3 text-left">Purpose</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Notes</th>
                    <th class="p-3 text-left">Duration</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($visits as $v)
                    <tr class="border-t border-white/10 hover:bg-white/5 transition">

                        <td class="p-3">{{ $v->customer->name }}</td>

                        <td class="p-3 text-white/80">
                            {{ $v->purpose }}
                        </td>

                        <td class="p-3">
                            <span class="px-3 py-1 rounded-lg text-xs
                                @if($v->status == 'started')
                                    bg-yellow-500/20 border border-yellow-400/40 text-yellow-200
                                @else
                                    bg-green-500/20 border border-green-400/40 text-green-200
                                @endif
                            ">
                                {{ ucfirst($v->status) }}
                            </span>
                        </td>

                        <td class="p-3 text-white/60">
                            {{ $v->notes ?? '-' }}
                        </td>

                        <td class="p-3 text-white/80">
                            @if($v->status == 'completed' && $v->completed_at)
                                {{ \Carbon\Carbon::parse($v->started_at)->diffForHumans($v->completed_at, true) }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="p-3">
                            @if($v->status == 'started')

                                <form action="{{ route('salesman.visits.complete', $v->id) }}"
                                      method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <textarea
                                        name="notes"
                                        class="w-full bg-white/10 text-white placeholder-white/50
                                               p-2 rounded-lg outline-none mb-2 focus:bg-white/20"
                                        placeholder="Add notes" required></textarea>

                                    <input type="file"
                                           name="images[]"
                                           multiple
                                           class="w-full text-white mb-2 bg-white/10 p-2 rounded-lg" required>

                                    <button
                                        class="w-full py-2 rounded-xl text-white font-semibold
                                               bg-gradient-to-r from-green-500 to-emerald-500
                                               shadow hover:opacity-90 transition">
                                        Complete
                                    </button>
                                </form>

                            @else
                                <div class="flex flex-col gap-2">
                                    <span class="text-green-300 font-semibold">Completed</span>

                                    <a href="{{ route('salesman.visits.show', $v->id) }}"
                                       class="px-3 py-2 rounded-lg bg-blue-500/30 border border-blue-400/40
                                              text-blue-100 text-sm text-center hover:bg-blue-500/40 transition">
                                        View
                                    </a>
                                </div>
                            @endif

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            class="p-6 text-center text-white/70 bg-white/5">
                            No record found
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>


        <!-- ================= MOBILE CARD VIEW ================= -->
        <div class="md:hidden p-4 space-y-4">

            @forelse ($visits as $v)
                <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">

                    <h2 class="text-lg font-semibold text-white">
                        {{ $v->customer->name }}
                    </h2>

                    <p class="text-white/70 text-sm mb-2">
                        Purpose: {{ $v->purpose }}
                    </p>

                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-white/60 text-sm">Status:</span>

                        <span class="px-3 py-1 rounded-lg text-xs
                            @if($v->status == 'started')
                                bg-yellow-500/20 border border-yellow-400/40 text-yellow-200
                            @else
                                bg-green-500/20 border border-green-400/40 text-green-200
                            @endif
                        ">
                            {{ ucfirst($v->status) }}
                        </span>
                    </div>

                    <p class="text-white/60 text-sm mb-2">
                        Notes: {{ $v->notes ?? '-' }}
                    </p>

                    <p class="text-white/80 text-sm mb-3">
                        Duration:
                        @if($v->status == 'completed' && $v->completed_at)
                            {{ \Carbon\Carbon::parse($v->started_at)->diffForHumans($v->completed_at, true) }}
                        @else
                            -
                        @endif
                    </p>


                    {{-- If Visit Started → Show Form --}}
                    @if($v->status == 'started')

                        <form action="{{ route('salesman.visits.complete', $v->id) }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf

                            <textarea
                                name="notes"
                                class="w-full bg-white/10 text-white placeholder-white/50
                                       p-2 rounded-lg outline-none mb-2 focus:bg-white/20"
                                placeholder="Add notes" required></textarea>

                            <input type="file"
                                   name="images[]"
                                   multiple
                                   class="w-full text-white mb-2 bg-white/10 p-2 rounded-lg" required>

                            <button
                                class="w-full py-2 rounded-xl text-white font-semibold
                                       bg-gradient-to-r from-green-500 to-emerald-500
                                       shadow hover:opacity-90 transition">
                                Complete
                            </button>
                        </form>

                    @else
                        <div class="flex flex-col gap-2">
                            <span class="text-green-300 font-semibold">Completed</span>

                            <a href="{{ route('salesman.visits.show', $v->id) }}"
                               class="px-3 py-2 rounded-lg bg-blue-500/30 border border-blue-400/40
                                      text-blue-100 text-sm text-center hover:bg-blue-500/40 transition">
                                View
                            </a>
                        </div>
                    @endif

                </div>
            @empty
                <p class="text-center text-white/50">No records found</p>
            @endforelse

        </div>

    </div>

</div>

@endsection
