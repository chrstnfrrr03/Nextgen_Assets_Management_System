<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NextGen Assets</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="text-gray-800 bg-slate-100">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="flex flex-col w-64 p-6 text-gray-300 shadow-2xl bg-slate-950">

            <!-- LOGO -->
            <div class="mb-10">
                <h2 class="text-xl font-bold tracking-wide text-white">
                    NextGen Assets
                </h2>
                <p class="text-xs text-gray-500">Asset Management</p>
            </div>

            <!-- MENU -->
            <nav class="space-y-2 text-sm">

                <!-- MAIN -->
                <p class="mb-2 text-xs text-gray-500 uppercase">Main</p>

                <a href="/dashboard"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                    {{ request()->is('dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                    Dashboard
                </a>

                <a href="/items"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                    {{ request()->is('items') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                    Assets
                </a>

                <!-- MANAGEMENT -->
                <p class="mt-6 mb-2 text-xs text-gray-500 uppercase">Management</p>
            <!-- #region-->
                {{-- Suppliers --}}
                <a href="/suppliers"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                                    {{ request()->is('suppliers') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                    Suppliers
                </a>

                {{-- SAFE LINKS --}}
                <a href="/users"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                    {{ request()->is('users') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                    Users
                </a>

                <a href="/settings"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                    {{ request()->is('settings') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                    Settings
                </a>

                <!-- SYSTEM -->
                <p class="mt-6 mb-2 text-xs text-gray-500 uppercase">System</p>

                <a href="/reports"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                    {{ request()->is('reports') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' }}">
                    Reports
                </a>

            </nav>

            <!-- FOOTER -->
            <div class="mt-auto text-xs text-gray-500">
                © {{ date('Y') }} NextGen Assets
            </div>

        </aside>

        <!-- MAIN -->
        <div class="flex flex-col flex-1">

            <!-- TOP BAR -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b shadow-sm">

                <!-- PAGE TITLE -->
                <div>
                    <h1 class="text-lg font-semibold text-slate-800">
                        @if(request()->is('items')) Assets Management
                        @elseif(request()->is('users')) Users Management
                        @elseif(request()->is('settings')) Settings
                        @elseif(request()->is('reports')) Reports
                        @else Dashboard Overview
                        @endif
                    </h1>

                    <p class="text-xs text-gray-500">
                        Welcome back, {{ Auth::user()->name ?? 'User' }}
                    </p>
                </div>

                <!-- RIGHT SIDE -->
                <div class="flex items-center gap-4">

                    <div class="flex items-center gap-2 px-3 py-1 rounded-lg bg-slate-100">
                        <span class="text-sm font-medium text-gray-700">
                            {{ Auth::user()->email ?? '' }}
                        </span>
                    </div>

                    <!-- SAFE LOGOUT -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-3 py-1 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600">
                            Logout
                        </button>
                    </form>

                </div>

            </header>

            <!-- CONTENT -->
            <main class="p-8">
                <div class="mx-auto max-w-7xl">

                    <!-- SUCCESS MESSAGE -->
                    @if(session('success'))
                        <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 border border-green-200 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{ $slot }}

                </div>
            </main>

        </div>

    </div>

</body>

</html>