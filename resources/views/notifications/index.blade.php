@extends('layouts.app')

@section('content')
    <meta http-equiv="refresh" content="30">

    <div class="flex flex-col gap-4 mb-8 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Notification Center</h1>
            <p class="mt-1 text-slate-500">Persistent operational alerts and system events.</p>
        </div>

        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <button
                class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                Mark All Read
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4">
        <div class="p-5 bg-white border shadow-sm rounded-2xl border-slate-200">
            <p class="text-sm text-slate-500">Unread</p>
            <h2 class="mt-2 text-3xl font-bold text-red-600">{{ $unreadCount }}</h2>
        </div>

        <div class="p-5 bg-white border shadow-sm rounded-2xl border-slate-200">
            <p class="text-sm text-slate-500">Critical</p>
            <h2 class="mt-2 text-3xl font-bold text-rose-600">{{ $criticalCount }}</h2>
        </div>

        <div class="p-5 bg-white border shadow-sm rounded-2xl border-slate-200">
            <p class="text-sm text-slate-500">Warnings</p>
            <h2 class="mt-2 text-3xl font-bold text-amber-500">{{ $warningCount }}</h2>
        </div>

        <div class="p-5 bg-white border shadow-sm rounded-2xl border-slate-200">
            <p class="text-sm text-slate-500">Info / Success</p>
            <h2 class="mt-2 text-3xl font-bold text-blue-600">{{ $infoCount + $successCount }}</h2>
        </div>
    </div>

    <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">Notifications</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-left">Status</th>
                        <th class="px-4 py-3 font-semibold text-left">Type</th>
                        <th class="px-4 py-3 font-semibold text-left">Title</th>
                        <th class="px-4 py-3 font-semibold text-left">Message</th>
                        <th class="px-4 py-3 font-semibold text-left">Created</th>
                        <th class="px-4 py-3 font-semibold text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($notifications as $notification)
                        @php
                            $typeClasses = match ($notification->type) {
                                'critical' => 'bg-red-100 text-red-700',
                                'warning' => 'bg-amber-100 text-amber-700',
                                'success' => 'bg-emerald-100 text-emerald-700',
                                default => 'bg-blue-100 text-blue-700',
                            };
                        @endphp

                        <tr class="{{ is_null($notification->read_at) ? 'bg-blue-50/40' : '' }}">
                            <td class="px-4 py-3">
                                @if(is_null($notification->read_at))
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                        Unread
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">
                                        Read
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $typeClasses }}">
                                    {{ ucfirst($notification->type) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 font-medium text-slate-900">{{ $notification->title }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $notification->message }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $notification->created_at?->format('d M Y H:i') }}</td>

                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('notifications.open', $notification) }}"
                                        class="rounded-lg bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-700">
                                        Open
                                    </a>

                                    @if(is_null($notification->read_at))
                                        <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                            @csrf
                                            <button
                                                class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">
                                                Mark Read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                                No notifications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
@endsection