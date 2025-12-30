@extends('layouts.app')
@section('title', 'My Visits')

@section('content')

    <div class="p-0 sm:p-6 ">

   <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">

    <h1 class="text-3xl font-bold text-white tracking-wide flex items-center">
        {{-- Lucide Icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
            viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-route mr-3 text-[#ff2ba6]">
            <circle cx="6" cy="19" r="3" />
            <path d="M9 19h8.5a3.5 3.5 0 0 0 0-7H14a2 2 0 0 1-2-2V3" />
            <circle cx="18" cy="5" r="3" />
        </svg>
        My Visits
    </h1>

    {{-- PRINT BUTTON --}}
    <button
        onclick="window.print()"
        class="px-5 py-2 rounded-xl
               bg-gradient-to-r from-pink-500 to-purple-500
               text-white font-semibold
               flex items-center justify-center
               hover:opacity-90 transition
               print:hidden">

        {{-- Printer Icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
            viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round"
            class="mr-2">
            <polyline points="6 9 6 2 18 2 18 9"/>
            <path d="M6 18H4a2 2 0 0 1-2-2v-5
                     a2 2 0 0 1 2-2h16
                     a2 2 0 0 1 2 2v5
                     a2 2 0 0 1-2 2h-2"/>
            <rect x="6" y="14" width="12" height="8"/>
        </svg>

        Print
    </button>

</div>

        {{-- Success Message --}}
        @if (session('success'))
            <div
                class="mb-4 p-4 bg-green-500/20 border border-green-400/40
                    text-green-100 rounded-xl backdrop-blur-lg flex items-center">
                {{-- Lucide Icon: check --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-check mr-2">
                    <path d="M20 6 9 17l-5-5" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Table Container --}}
        <div
            class="bg-white/10 backdrop-blur-xl border border-white/20
                  rounded-2xl shadow-xl overflow-hidden">

            <table class="w-full table-auto text-white hidden md:table">
                <thead>
                    <tr class="bg-white/10 text-white/80">
                        <th class="p-3 text-left">
                            {{-- Icon for ID/Index --}}
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-hash mr-1">
                                    <line x1="4" x2="20" y1="9" y2="9" />
                                    <line x1="4" x2="20" y1="15" y2="15" />
                                    <line x1="10" x2="8" y1="3" y2="21" />
                                    <line x1="16" x2="14" y1="3" y2="21" />
                                </svg>
                                Id
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: building --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-building mr-1">
                                    <rect width="16" height="20" x="4" y="2" rx="2" ry="2" />
                                    <path d="M9 22v-4h6v4" />
                                </svg>
                                Customer
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: list-checks (Purpose) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-list-checks mr-1">
                                    <path d="m3 16 2 2 4-4" />
                                    <path d="m3 12 2 2 4-4" />
                                    <path d="m3 8 2 2 4-4" />
                                    <path d="M11 6h9" />
                                    <path d="M11 10h9" />
                                    <path d="M11 14h9" />
                                </svg>
                                Purpose
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: activity (Status) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-activity mr-1">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                </svg>
                                Status
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: sticky-note (Notes) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-sticky-note mr-1">
                                    <path d="M15.5 8H20v14H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8.5L20 8.5z" />
                                    <path d="M15 2v4a2 2 0 0 0 2 2h4" />
                                </svg>
                                Notes
                            </div>
                        </th>
                        <th class="p-3 text-left">
    <div class="flex items-center">
        {{-- Lucide Icon: map-pin --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-map-pin mr-1">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
            <circle cx="12" cy="10" r="3" />
        </svg>
        KM
    </div>
</th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: clock (Duration) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                Duration
                            </div>
                        </th>
                        <th class="p-3 text-left">
                            <div class="flex items-center">
                                {{-- Lucide Icon: zap (Action) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap mr-1">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                                </svg>
                                Action
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($visits as $v)
                        <tr class="border-t border-white/10 hover:bg-white/5 transition">

                            <td class="p-3">{{ $v->id }}</td>
                            <td class="p-3">{{ $v->customer->name }}</td>

                            <td class="p-3 text-white/80">
                                {{ $v->purpose }}
                            </td>

                            <td class="p-3">
                                <span
                                    class="px-3 py-1 rounded-lg text-xs
                                @if ($v->status == 'started') bg-yellow-500/20 border border-yellow-400/40 text-yellow-200
                                @else
                                    bg-green-500/20 border border-green-400/40 text-green-200 @endif
                            ">
                                    {{ ucfirst($v->status) }}
                                </span>
                            </td>

                            <td class="p-3 text-white/60 max-w-xs">
                                <div class="break-words whitespace-pre-line max-h-24 overflow-y-auto pr-1">
                                    {{ $v->notes ?? '-' }}
                                </div>
                            </td>

<td class="p-3 text-white/80">
    @if ($v->distance_km)
        {{ $v->distance_km }} km
    @else
        -
    @endif
</td>

                            <td class="p-3 text-white/80">
                                @if ($v->status == 'completed' && $v->completed_at)
                                    {{ \Carbon\Carbon::parse($v->started_at)->diffForHumans($v->completed_at, true) }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="p-3">
                                @if ($v->status == 'started')
                                    <form action="{{ route('salesman.visits.complete', $v->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <textarea name="notes"
                                            class="w-full bg-white/10 text-white placeholder-white/50
                                                p-2 rounded-lg outline-none mb-2 focus:bg-white/20"
                                            placeholder="Add notes" required></textarea>
                                        <input type="number" step="0.1" min="0" name="distance_km"
    placeholder="Enter distance in KM"
    class="w-full bg-white/10 text-white placeholder-white/50
           p-2 rounded-lg mb-2 focus:bg-white/20" required>

                                        {{-- File upload for images only --}}
                                        <input type="file" name="images[]" multiple accept="image/*"
                                            {{-- Suggested addition to hint for images only --}}
                                            class="w-full text-white mb-2 bg-white/10 p-2 rounded-lg" required>

                                        <button
                                            class="w-full py-2 rounded-xl text-white font-semibold flex items-center justify-center
                                                bg-gradient-to-r from-green-500 to-emerald-500
                                                shadow hover:opacity-90 transition">
                                            {{-- Lucide Icon: check-circle --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-check-circle mr-1">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                <path d="m9 11 2 2 4-4" />
                                            </svg>
                                            Complete
                                        </button>
                                    </form>
                                @else
                                    <div class="flex flex-col gap-2">
                                        <span class="text-green-300 font-semibold flex items-center">
                                            {{-- Lucide Icon: check-check --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-check-check mr-1">
                                                <path d="M18 6 7 17l-5-5" />
                                                <path d="m19 12-5 5-2-2" />
                                            </svg>
                                            Completed
                                        </span>

                                        <a href="{{ route('salesman.visits.show', $v->id) }}"
                                            class="px-3 py-2 rounded-lg bg-blue-500/30 border border-blue-400/40
                                               text-blue-100 text-sm text-center hover:bg-blue-500/40 transition flex items-center justify-center">
                                            {{-- Lucide Icon: eye --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-eye mr-1">
                                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                @endif

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-white/70 bg-white/5">
                                No record found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>


            {{-- MOBILE VIEW --}}
            <div class="md:hidden p-4 space-y-4">

                @forelse ($visits as $v)
                    <div class="bg-white/10 border border-white/10 rounded-xl p-4 shadow-lg">

                        <h2 class="text-lg font-semibold text-white flex items-center">
                            {{-- Lucide Icon: building --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-building mr-2 text-indigo-300">
                                <rect width="16" height="20" x="4" y="2" rx="2" ry="2" />
                                <path d="M9 22v-4h6v4" />
                            </svg>
                            {{ $v->customer->name }}
                        </h2>

                        <p class="text-white/70 text-sm mb-2 flex items-center ml-px">
                            {{-- Lucide Icon: list-checks (Purpose) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-list-checks mr-2 text-white/80">
                                <path d="m3 16 2 2 4-4" />
                                <path d="m3 12 2 2 4-4" />
                                <path d="m3 8 2 2 4-4" />
                                <path d="M11 6h9" />
                                <path d="M11 10h9" />
                                <path d="M11 14h9" />
                            </svg>
                            Purpose: {{ $v->purpose }}
                        </p>

                        <div class="flex items-center gap-2 mb-2 ml-px">
                            {{-- Lucide Icon: activity (Status) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-activity mr-0.5 text-white/80">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                            </svg>
                            <span class="text-white/60 text-sm">Status:</span>

                            <span
                                class="px-3 py-1 rounded-lg text-xs
                            @if ($v->status == 'started') bg-yellow-500/20 border border-yellow-400/40 text-yellow-200
                            @else
                                bg-green-500/20 border border-green-400/40 text-green-200 @endif
                        ">
                                {{ ucfirst($v->status) }}
                            </span>
                        </div>

                        <div class="text-white/60 text-sm mb-2 ml-px">
                            <div class="flex items-center mb-1">
                                {{-- Lucide Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-sticky-note mr-2 text-white/80">
                                    <path d="M15.5 8H20v14H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8.5L20 8.5z" />
                                    <path d="M15 2v4a2 2 0 0 0 2 2h4" />
                                </svg>
                                Notes
                            </div>

                            <div class="text-white/70 text-sm break-words whitespace-pre-line
    line-clamp-3 transition-all duration-300"
    id="notes-{{ $v->id }}">
    {{ $v->notes ?? '-' }}
</div>

@if ($v->notes && strlen($v->notes) > 120)
    <button
        type="button"
        onclick="toggleNotes({{ $v->id }}, this)"
        class="mt-1 text-xs text-pink-400 hover:text-pink-300 transition">
        Read more
    </button>
@endif




                        </div>


                        <p class="text-white/80 text-sm mb-3 flex items-center ml-px">
                            {{-- Lucide Icon: clock (Duration) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-clock mr-2 text-white/80">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            Duration:
                            @if ($v->status == 'completed' && $v->completed_at)
                                {{ \Carbon\Carbon::parse($v->started_at)->diffForHumans($v->completed_at, true) }}
                            @else
                                -
                            @endif
                        </p>
@if ($v->distance_km)
<p class="text-white/80 text-sm mb-2 flex items-center ml-px">
    {{-- Lucide Icon: map-pin --}}
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
        viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="lucide lucide-map-pin mr-2 text-white/80">
        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
        <circle cx="12" cy="10" r="3" />
    </svg>
    Distance: <span class="ml-1 font-semibold">{{ $v->distance_km }} km</span>
</p>
@endif


                        {{-- If Visit Started → Show Form --}}
           {{-- ACTION AREA --}}
<div class="mt-4">

    {{-- IF VISIT IS STARTED --}}
    @if ($v->status == 'started')

        <form action="{{ route('salesman.visits.complete', $v->id) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <textarea name="notes"
                class="w-full bg-white/10 text-white placeholder-white/50
                       p-2 rounded-lg outline-none mb-2 focus:bg-white/20"
                placeholder="Add notes" required></textarea>

            {{-- KM Field --}}
            <input type="number" step="0.1" min="0"
                name="distance_km"
                placeholder="Enter distance in KM"
                class="w-full bg-white/10 text-white placeholder-white/50
                       p-2 rounded-lg mb-2 focus:bg-white/20"
                required>

            {{-- Images --}}
            <input type="file"
                name="images[]"
                multiple
                accept="image/*"
                class="w-full text-white mb-3 bg-white/10 p-2 rounded-lg text-sm"
                required>

            <button
                class="w-full py-2 rounded-xl text-white font-semibold
                       flex items-center justify-center
                       bg-gradient-to-r from-green-500 to-emerald-500
                       shadow hover:opacity-90 transition">
                ✔ Complete Visit
            </button>
        </form>

    {{-- IF VISIT IS COMPLETED --}}
    @else

        <a href="{{ route('salesman.visits.show', $v->id) }}"
           class="w-full mt-2 px-4 py-2 rounded-xl
                  bg-blue-500/30 border border-blue-400/40
                  text-blue-100 text-sm font-semibold
                  flex items-center justify-center
                  hover:bg-blue-500/40 transition">

            {{-- Eye Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg"
                width="16" height="16"
                viewBox="0 0 24 24"
                fill="none" stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="mr-2">
                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>

            View Visit Details
        </a>

    @endif

</div>



                    </div>
                @empty
                    <p class="text-center text-white/50 p-6">No records found</p>
                @endforelse

            </div>

        </div>

    </div>

@endsection

<script>
function toggleNotes(id, btn) {
    const el = document.getElementById(`notes-${id}`);
    el.classList.toggle('line-clamp-3');

    btn.innerText = el.classList.contains('line-clamp-3')
        ? 'Read more'
        : 'Read less';
}
</script>

<style>
@media print {

    body {
        background: white !important;
        color: black !important;
    }

    /* Hide buttons, forms, inputs */
    button,
    form,
    input,
    textarea,
    .print\:hidden {
        display: none !important;
    }

    /* Force table visible */
    table {
        display: table !important;
        width: 100%;
        color: black !important;
    }

    th, td {
        color: black !important;
        border: 1px solid #ddd !important;
        padding: 8px !important;
    }

    /* Remove glass effect */
    .bg-white\/10,
    .backdrop-blur-xl {
        background: white !important;
        border: none !important;
    }

    /* Hide mobile cards in print */
    .md\:hidden {
        display: none !important;
    }
}
</style>
