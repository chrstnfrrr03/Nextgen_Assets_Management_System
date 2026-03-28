<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ============================= --}}
    {{-- APP NAME FROM DATABASE --}}
    {{-- ============================= --}}
    @php
try {
    $appName = \Illuminate\Support\Facades\DB::table('settings')->value('app_name') ?? 'NextGen Assets';
} catch (\Exception $e) {
    $appName = 'NextGen Assets';
}
    @endphp

    <title>{{ $appName }}</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

{{-- DARK MODE SUPPORT --}}

<body x-data="{ darkMode: false }" :class="darkMode ? 'bg-gray-900 text-white' : 'bg-slate-100 text-gray-800'">

    <div class="flex min-h-screen">

        {{-- ============================= --}}
        {{-- SIDEBAR --}}
        {{-- ============================= --}}
        <aside class="w-64 p-6 text-gray-300 bg-slate-950">

            <h2 class="mb-10 text-xl font-bold text-white">
                {{ $appName }}
            </h2>

            <nav class="space-y-2 text-sm">

                <a href="/dashboard" class="block px-4 py-2 rounded hover:bg-slate-800">Dashboard</a>
                <a href="/products" class="block px-4 py-2 rounded hover:bg-slate-800">Products</a>
                <a href="/suppliers" class="block px-4 py-2 rounded hover:bg-slate-800">Suppliers</a>
                <a href="/categories" class="block px-4 py-2 rounded hover:bg-slate-800">Categories</a>
                <a href="/users" class="block px-4 py-2 rounded hover:bg-slate-800">Users</a>
                <a href="/settings" class="block px-4 py-2 rounded hover:bg-slate-800">Settings</a>

            </nav>

            <div class="mt-10 text-xs text-gray-500">
                © {{ date('Y') }} {{ $appName }}
            </div>

        </aside>

        {{-- ============================= --}}
        {{-- MAIN --}}
        {{-- ============================= --}}
        <div class="flex-1">

            {{-- ============================= --}}
            {{-- HEADER --}}
            {{-- ============================= --}}
            <div class="flex items-center justify-between p-4 bg-white border-b">

                {{-- LEFT --}}
                <div>
                    <h1 class="font-semibold">Dashboard</h1>
                    <p class="text-sm text-gray-500">
                        Welcome back, {{ Auth::user()->name ?? 'User' }}
                    </p>
                </div>

                {{-- RIGHT --}}
                <div class="flex items-center gap-4">

                    {{--  NOTIFICATIONS --}}
                <div x-data="{ open: false }" class="relative">
                
                    <!-- BUTTON -->
                    <button @click="open = !open" class="relative p-2 bg-gray-100 rounded-full hover:bg-gray-200">
                
                        🔔
                
                        <!-- RED DOT -->
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                
                    <!-- DROPDOWN -->
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 z-50 mt-2 bg-white border shadow-lg w-72 rounded-xl">
                
                        <div class="p-4 space-y-2 text-sm text-gray-700">
                
                            @if(!empty($notifications))
                                @foreach($notifications as $note)
                                    <div class="p-2 rounded bg-gray-50">
                                        🔔 {{ $note['message'] }}
                                    </div>
                                @endforeach
                            @else
                                <div>No notifications</div>
                            @endif
                
                        </div>
                
                    </div>
                
                </div>

                    {{--  DARK MODE --}}
                    <button @click="darkMode = !darkMode"
                        class="px-3 py-1 text-sm bg-gray-200 rounded hover:bg-gray-300">
                        Toggle Mode
                    </button>

                    {{-- LOGOUT --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-3 py-1 text-white bg-red-500 rounded hover:bg-red-600">
                            Logout
                        </button>
                    </form>

                </div>

            </div>

            {{-- ============================= --}}
            {{-- CONTENT --}}
            {{-- ============================= --}}
            <main class="p-6">
                {{ $slot }}
            </main>

        </div>

    </div>

    {{-- AlpineJS (REQUIRED FOR DARK MODE) --}}
    <script src="https://unpkg.com/alpinejs" defer></script>


    {{-- Auto_Refresh (Real-Time Feel)) --}}
     <script>
        setInterval(() => {
            window.location.reload();
        }, 15000);  //refresh every 15 seconds
        
     </script>

</body>

</html>