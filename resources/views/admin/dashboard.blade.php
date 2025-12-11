@extends('layouts.app')

@section('title','Admin Dashboard')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Salesmen -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
        <i data-lucide="users" class="w-8 h-8 text-blue-400 absolute top-4 right-4"></i>

        <h3 class="text-sm font-medium text-white/80">Manage Salesmen</h3>
        <p class="text-4xl font-extrabold text-white mt-2">
            {{ \App\Models\User::where('role','salesman')->count() }}
        </p>
        <a href="{{ route('admin.salesmen.index') }}"
           class="text-xs text-white/80 underline mt-3 inline-block hover:text-white">
           Open Section
        </a>
    </div>

    <!-- Customers -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
        <i data-lucide="contact" class="w-8 h-8 text-green-400 absolute top-4 right-4"></i>

        <h3 class="text-sm font-medium text-white/80">All Customers</h3>
        <p class="text-4xl font-extrabold text-white mt-2">
            {{ \App\Models\Customer::count() }}
        </p>
        <a href="{{ url('/admin/customers') }}"
           class="text-xs text-white/80 underline mt-3 inline-block hover:text-white">
           View Customers
        </a>
    </div>

    <!-- Reports -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
        <i data-lucide="bar-chart-3" class="w-8 h-8 text-purple-400 absolute top-4 right-4"></i>

        <h3 class="text-sm font-medium text-white/80">Visit Reports</h3>
        <p class="text-4xl font-extrabold text-white mt-2">
            {{ \App\Models\Visit::whereMonth('created_at', now()->month)->count() }}
        </p>
        <a href="{{ route('admin.reports.index') }}"
           class="text-xs text-white/80 underline mt-3 inline-block hover:text-white">
           View Reports
        </a>
    </div>

    <!-- Visits This Month -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
        <i data-lucide="calendar" class="w-8 h-8 text-yellow-400 absolute top-4 right-4"></i>

        <h3 class="text-sm font-medium text-white/80">Visits This Month</h3>
        <p class="text-4xl font-extrabold text-white mt-2">
            {{ \App\Models\Visit::whereMonth('created_at', now()->month)->count() }}
        </p>
    </div>

    <!-- Today Visits -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg relative">
        <i data-lucide="sun" class="w-8 h-8 text-orange-400 absolute top-4 right-4"></i>

        <h3 class="text-sm font-medium text-white/80">Today's Visits</h3>
        <p class="text-4xl font-extrabold text-white mt-2">
            {{ \App\Models\Visit::whereDate('created_at', now()->toDateString())->count() }}
        </p>
    </div>

</div>

<!-- Recent Activities -->
<div class="mt-8 bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-lg border-l-8"
     style="border-left-color: var(--hf-magenta-light);">

    <h3 class="text-lg font-semibold text-white tracking-wide flex items-center gap-2">
        <i data-lucide="activity" class="w-6 h-6 text-pink-400"></i>
        Recent Activities
    </h3>

    <ul class="mt-4 space-y-4">
        @foreach(\App\Models\Visit::with('salesman','customer')->latest()->limit(6)->get() as $v)
            <li class="flex items-start gap-3">
                <div class="w-2 h-2 rounded-full bg-[#ff2ba6] mt-2"></div>
                <div>
                    <div class="text-sm text-white">
                        {{ $v->salesman->name ?? '—' }} started visit at
                        <strong>{{ $v->customer->name ?? '—' }}</strong>
                    </div>
                    <div class="text-xs text-white/70">
                        {{ $v->created_at->diffForHumans() }}
                    </div>
                </div>
            </li>
        @endforeach
    </ul>

</div>

@endsection
