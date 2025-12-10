<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sigma Tracking System')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        :root {
            --hf-magenta: #d6007b;
            --hf-magenta-light: #ff2ba6;
            --hf-purple: #6A00FF;
            --hf-dark: #000000;
        }

        body {
            background: linear-gradient(135deg, #000000, #2b0034, #4a008b, #6A00FF);
            background-size: 300% 300%;
            animation: gradientFlow 12s ease infinite;
            color: white !important;
        }

        @keyframes gradientFlow {
            0% {
                background-position: 0% 0%;
            }

            50% {
                background-position: 100% 100%;
            }

            100% {
                background-position: 0% 0%;
            }
        }

        .glass {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .sidebar-active {
            background: rgba(255, 0, 170, 0.2);
            border-left: 4px solid var(--hf-magenta-light);
            box-shadow: 0 0 12px rgba(255, 0, 170, 0.4);
            color: white !important;
        }

        .hf-heading {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 1px;
        }
    </style>

    @stack('head')
</head>

<body class="antialiased">

    <div class="min-h-screen flex">

        <aside class="w-72 hidden md:flex flex-col glass shadow-2xl border-r border-white/10">

            <div class="p-6 border-b border-white/10">
                <a href="{{ route('dashboard') ?? url('/') }}" class="hf-heading text-white">
                    SES<span class="text-[var(--hf-magenta-light)]">.</span>
                </a>
                <p class="text-xs text-gray-300 mt-1">Salesman Tracking System</p>
            </div>

            <nav class="p-4 flex-1 text-gray-200 ">

                @auth

                    <div class="mb-6 p-4 rounded-xl glass border border-white/10 ">
                        <div class="text-sm text-gray-300">Logged in as:</div>
                        <div class="font-semibold text-white">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-400">{{ auth()->user()->email }}</div>
                    </div>

                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-4 py-2 rounded-lg hover:bg-white/10 mt-1
                        {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                            Dashboard
                        </a>

                        <a href="{{ route('admin.reports.index') }}"
                            class="block px-4 py-2 rounded-lg hover:bg-white/10 mt-1
                        {{ request()->routeIs('admin.reports.*') ? 'sidebar-active' : '' }}">
                            Reports
                        </a>

                        <a href="{{ route('admin.salesmen.index') }}"
                            class="block px-4 py-2 rounded-lg hover:bg-white/10 mt-1">
                            Salesmen
                        </a>

                        <a href="{{ url('/admin/customers') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 mt-1">
                            All Customers
                        </a>
                    @endif

                    @if (auth()->user()->role === 'salesman')
                        <a href="{{ route('salesman.dashboard') }}"
                            class="block px-4 py-2 rounded-lg hover:bg-white/10 mt-1
                        {{ request()->routeIs('salesman.dashboard') ? 'sidebar-active' : '' }}">
                            Dashboard
                        </a>

                        <a href="{{ route('salesman.customers.index') }}"
                            class="block px-4 py-2 rounded-lg hover:bg-white/10 mt-1">
                            My Customers
                        </a>

                        <a href="{{ route('salesman.customers.create') }}"
                            class="block px-4 py-2 rounded-lg hover:bg-white/10 mt-1">
                            Add Customer
                        </a>

                        <a href="{{ route('salesman.visits.create') }}"
                            class="block px-4 py-2 rounded-lg hover:bg-white/10 mt-1">
                            Start Visit
                        </a>

                        <a href="{{ route('salesman.visits.index') }}"
                            class="block px-4 py-2 rounded-lg hover:bg-white/10 mt-1">
                            My Visits
                        </a>

                        {{-- <a href="{{ route('salesman.attendance.index') }}"
                        class="block px-4 py-2 rounded-lg hover:bg-white/10 mt-1">
                        Attendance
                    </a> --}}
                    @endif

                    <div class="border-t border-white/10 mt-6 pt-4">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10">
                            Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="px-4 mt-3">
                            @csrf
                            <button type="submit" class="text-sm text-red-400 hover:text-red-300">
                                Logout
                            </button>
                        </form>
                    </div>

                @endauth
            </nav>
        </aside>

        <div x-data="{ open: false }" class="flex-1">

            <header class="glass border-b border-white/10 shadow-xl relative z-20">
                <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">

                    <div class="flex items-center gap-3">
                        <button @click="open = !open" class="md:hidden p-2 rounded hover:bg-white/10">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <a href="{{ route('dashboard') }}"
                            class="text-xl md:text-2xl font-extrabold text-white tracking-wide">
                            Sigma Engineering Services<span class="text-[var(--hf-magenta-light)]">.</span>
                        </a>
                    </div>

                    @auth
                        <div class="hidden md:block text-sm text-gray-300">
                            {{ auth()->user()->name }} — {{ auth()->user()->role }}
                        </div>
                    @endauth
                </div>

                <div x-show="open" x-cloak x-transition
                    class="md:hidden glass border-t border-white/10 absolute top-16 left-0 w-full z-30 bg-black/80">
                    <div class="p-4">

                        @auth
                            <a href="{{ route('profile.edit') }}"
                                class="block p-3 mb-4 rounded-xl glass border border-white/10 hover:bg-white/10 transition">
                                <div class="text-sm text-gray-300">Logged in as:</div>
                                <div class="font-semibold text-white">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-400">{{ auth()->user()->email }}</div>
                            </a>

                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'sidebar-active-mobile' : '' }}">Dashboard</a>
                                <a href="{{ route('admin.salesmen.index') }}"
                                    class="block py-2 rounded hover:bg-white/10">
                                    Salesmen
                                </a>


                                <a href="{{ route('admin.reports.index') }}"
                                    class="block py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.reports.*') ? 'sidebar-active-mobile' : '' }}">Reports</a>
                                <a href="{{ route('admin.customers.index') }}"
                                    class="block py-2 rounded hover:bg-white/10 {{ request()->routeIs('admin.reports.*') ? 'sidebar-active-mobile' : '' }}">All
                                    Customers</a>
                            @endif

                            @if (auth()->user()->role === 'salesman')
                                <a href="{{ route('salesman.dashboard') }}"
                                    class="block py-2 rounded hover:bg-white/10 {{ request()->routeIs('salesman.dashboard') ? 'sidebar-active-mobile' : '' }}">Dashboard</a>
                                <a href="{{ route('salesman.customers.index') }}"
                                    class="block py-2 rounded hover:bg-white/10">
                                    My Customers</a>
                                <a href="{{ route('salesman.customers.create') }}"
                                    class="block py-2 rounded hover:bg-white/10">Add Customer</a>
                                <a href="{{ route('salesman.visits.create') }}"
                                    class="block py-2 rounded hover:bg-white/10">Start Visit</a>
                                <a href="{{ route('salesman.visits.index') }}"
                                    class="block py-2 rounded hover:bg-white/10">
                                    My Visits
                                </a>
                                {{-- <a href="{{ route('salesman.attendance.index') }}" class="block py-2">Attendance</a> --}}
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-white/10">
                                @csrf
                                <button class="text-red-400 hover:text-red-300">Logout</button>
                            </form>
                        @endauth
                    </div>
                </div>
            </header>

            <main class="p-6 max-w-7xl mx-auto">

                @if (session('success'))
                    <div class="mb-4 p-3 glass border border-green-400/40 text-green-300 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-3 glass border border-red-400/40 text-red-300 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </main>
        </div>

    </div>

    @stack('scripts')
    @yield('scripts')

    <style>
        .sidebar-active-mobile {
            background: rgba(255, 0, 170, 0.2);
            /* border-left: 4px solid var(--hf-magenta-light); <-- Removed for mobile */
            box-shadow: 0 0 12px rgba(255, 0, 170, 0.4);
            color: white !important;
        }
    </style>

</body>

</html>
