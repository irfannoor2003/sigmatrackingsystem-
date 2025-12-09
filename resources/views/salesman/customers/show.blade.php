@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto mt-10">

    <!-- Main Card -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20
                rounded-3xl shadow-2xl p-8">

        <!-- Heading -->
        <h2 class="text-3xl font-bold text-white mb-6 tracking-wide">
            Customer Details
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            <!-- Image (Fixed size, perfect fit) -->
            <div class="flex justify-center">
                <div class="w-64 h-64 rounded-3xl overflow-hidden shadow-xl bg-white/5 border border-white/10">
                    @if($customer->image)
                        <img src="{{ asset('storage/' . $customer->image) }}"
                             class="w-full h-full object-cover">
                    @else
                        <img src="https://via.placeholder.com/300"
                             class="w-full h-full object-cover">
                    @endif
                </div>
            </div>

            <!-- Info Section -->
            <div>

                <h3 class="text-2xl font-semibold text-white">
                    {{ $customer->name }}
                </h3>

                <p class="text-indigo-200 font-medium mb-4 capitalize">
                    {{ $customer->category->name ?? 'No Category' }}
                </p>

                <!-- Details Table -->
                <div class="space-y-3">

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Contact Person</span>
                        <span class="text-white">{{ $customer->contact_person ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Phone 1</span>
                        <span class="text-white">{{ $customer->phone1 }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Phone 2</span>
                        <span class="text-white">{{ $customer->phone2 ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Email</span>
                        <span class="text-white">{{ $customer->email ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">City</span>
                        <span class="text-white capitalize">{{ $customer->city?->name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Industry</span>
                        <span class="text-white">{{ $customer->industry?->name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Address</span>
                        <span class="text-white">{{ $customer->address ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between bg-white/10 py-3 px-4 rounded-xl">
                        <span class="text-white/70">Added On</span>
                        <span class="text-white">{{ $customer->created_at->format('d M, Y') }}</span>
                    </div>

                </div>
            </div>

        </div>

        <!-- Back Button -->
        <div class="mt-8 text-right">
            <a href="{{ route('salesman.customers.index') }}"
               class="px-6 py-3 bg-purple-600/80 hover:bg-purple-600
                      text-white font-semibold rounded-xl shadow-lg
                      transition-all duration-200">
                Back to Customers
            </a>
        </div>

    </div>

</div>

@endsection
