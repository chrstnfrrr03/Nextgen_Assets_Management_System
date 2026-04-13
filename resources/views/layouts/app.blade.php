<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appSettings['system_name'] ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-800">
    @php
        $headerNotifications = \App\Models\SystemNotification::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->take(6)
            ->get();

        $headerUnreadCount = \App\Models\SystemNotification::query()
            ->where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        $authUser = auth()->user();
    @endphp

    <div x-data="{ sidebarOpen: true }" class="flex min-h-screen bg-slate-100">
        @include('layouts.navigation')

        <div class="flex flex-col flex-1 min-w-0">
            <header class="sticky top-0 z-30 border-b border-slate-150 bg-white/80 backdrop-blur-xl">
                <div class="flex items-center justify-between gap-4 px-4 py-3 sm:px-6 xl:px-8">
                    <div class="flex items-center min-w-0 gap-3">
                        <button
                            type="button"
                            @click="sidebarOpen = !sidebarOpen"
                            class="inline-flex items-center justify-center w-10 h-10 transition bg-white border shadow-sm rounded-xl border-slate-200 text-slate-600 hover:bg-slate-50"
                        >
                            ☰
                        </button>

                        <form method="GET" action="{{ route('items.index') }}" class="hidden md:block">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search assets..."
                                class="w-[320px] rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100 xl:w-[420px]"
                            >
                        </form>
                    </div>

                    <div class="flex items-center gap-3">
                        @if($authUser && $authUser->isAdmin())
                            <a
                                href="{{ route('settings.index') }}"
                                class="inline-flex items-center justify-center w-10 h-10 transition bg-white border shadow-sm rounded-xl border-slate-200 text-slate-600 hover:bg-slate-50"
                                title="Settings"
                            >
                                ⚙️
                            </a>
                        @endif

                        <div x-data="{ open: false }" class="relative">
                            <button
                                type="button"
                                @click="open = !open"
                                class="relative inline-flex items-center justify-center w-10 h-10 transition bg-white border shadow-sm rounded-xl border-slate-200 text-slate-600 hover:bg-slate-50"
                                title="Notifications"
                            >
                                🔔
                                @if($headerUnreadCount > 0)
                                    <span class="absolute -right-1 -top-1 inline-flex min-w-[20px] items-center justify-center rounded-full bg-red-500 px-1 text-[11px] font-semibold text-white">
                                        {{ $headerUnreadCount > 99 ? '99+' : $headerUnreadCount }}
                                    </span>
                                @endif
                            </button>

                            <div
                                x-show="open"
                                x-transition
                                @click.away="open = false"
                                class="absolute right-0 mt-2 w-[360px] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
                                style="display: none;"
                            >
                                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900">Notifications</h3>
                                        <p class="text-xs text-slate-500">{{ $headerUnreadCount }} unread</p>
                                    </div>

                                    <a
                                        href="{{ route('notifications.index') }}"
                                        class="text-xs font-semibold text-blue-600 transition hover:text-blue-700"
                                    >
                                        View all
                                    </a>
                                </div>

                                <div class="overflow-y-auto max-h-96">
                                    @forelse($headerNotifications as $notification)
                                        <a
                                            href="{{ route('notifications.open', $notification) }}"
                                            class="block px-4 py-3 transition border-b border-slate-100 last:border-b-0 hover:bg-slate-50"
                                        >
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-semibold text-slate-900">
                                                        {{ $notification->title }}
                                                    </p>
                                                    <p class="mt-1 text-xs line-clamp-2 text-slate-500">
                                                        {{ $notification->message }}
                                                    </p>
                                                </div>

                                                @if(is_null($notification->read_at))
                                                    <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-blue-500"></span>
                                                @endif
                                            </div>

                                            <p class="mt-2 text-[11px] text-slate-400">
                                                {{ $notification->created_at?->diffForHumans() }}
                                            </p>
                                        </a>
                                    @empty
                                        <div class="px-4 py-6 text-sm text-slate-500">
                                            No notifications found.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="relative">
                            <button
                                type="button"
                                @click="open = !open"
                                class="flex items-center gap-3 px-3 py-2 transition bg-white border shadow-sm rounded-xl border-slate-200 hover:bg-slate-50"
                            >
                                <div class="hidden text-right sm:block">
                                    <div class="text-sm font-semibold text-slate-900">{{ $authUser->name }}</div>
                                    <div class="text-xs text-slate-500">
                                        {{ $authUser->isSystemAdmin() ? 'System Administrator' : ucfirst(str_replace('_', ' ', $authUser->role)) }}
                                    </div>
                                </div>

                                @if($authUser && $authUser->profile_photo_url)
                                    <img
                                        src="{{ $authUser->profile_photo_url }}"
                                        alt="{{ $authUser->name }}"
                                        class="object-cover border h-9 w-9 rounded-xl border-slate-200"
                                    >
                                @else
                                    <div class="flex items-center justify-center text-sm font-bold text-white h-9 w-9 rounded-xl bg-slate-900">
                                        {{ strtoupper(substr($authUser->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </button>

                            <div
                                x-show="open"
                                x-transition
                                @click.away="open = false"
                                class="absolute right-0 mt-2 overflow-hidden bg-white border shadow-2xl w-72 rounded-2xl border-slate-200"
                                style="display: none;"
                            >
                                <div class="px-4 py-4 border-b border-slate-200">
                                    <div class="text-sm font-semibold text-slate-900">{{ $authUser->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $authUser->email }}</div>
                                    <div class="mt-2 inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                        {{ $authUser->isSystemAdmin() ? 'System Administrator' : ucfirst(str_replace('_', ' ', $authUser->role)) }}
                                    </div>
                                </div>

                                <div class="py-2">
                                    <a
                                        href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-sm transition text-slate-700 hover:bg-slate-50"
                                    >
                                        Profile
                                    </a>

                                    @if($authUser->isAdmin())
                                        <a
                                            href="{{ route('users.index') }}"
                                            class="block px-4 py-2 text-sm transition text-slate-700 hover:bg-slate-50"
                                        >
                                            Switch Account
                                        </a>
                                    @endif

                                    @if(session()->has('impersonator_id'))
                                        <form method="POST" action="{{ route('impersonation.stop') }}">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="block w-full px-4 py-2 text-sm font-semibold text-left transition text-amber-700 hover:bg-amber-50"
                                            >
                                                Return to Administrator
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="block w-full px-4 py-2 text-sm font-semibold text-left text-red-600 transition hover:bg-red-50"
                                        >
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            @if(session()->has('impersonator_id'))
                <div class="flex items-center justify-between gap-4 px-6 py-3 text-sm border-b border-amber-200 bg-amber-100 text-amber-900">
                    <div>
                        You are currently viewing the system as <span class="font-semibold">{{ $authUser->name }}</span>.
                    </div>

                    <form method="POST" action="{{ route('impersonation.stop') }}">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-lg bg-amber-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-black"
                        >
                            Return to Administrator
                        </button>
                    </form>
                </div>
            @endif

            <main class="flex-1 px-6 py-6 bg-slate-50 xl:px-10 xl:py-8">
                @if(session('success'))
                    <div class="px-4 py-3 mb-4 text-green-700 border border-green-200 rounded-2xl bg-green-50">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="px-4 py-3 mb-4 text-red-700 border border-red-200 rounded-2xl bg-red-50">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="px-4 py-3 mb-4 text-red-700 border border-red-200 rounded-2xl bg-red-50">
                        <p class="mb-2 font-semibold">Please fix the following errors:</p>
                        <ul class="text-sm list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>