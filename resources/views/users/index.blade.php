@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-4 mb-8 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div
                class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium bg-white border rounded-full shadow-sm border-slate-200 text-slate-500">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                System administration
            </div>

            <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                User Administration
            </h1>

            <p class="mt-2 text-sm text-slate-500 sm:text-base">
                Provision accounts, assign roles, and switch into user accounts for support and verification.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                + Add User
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4">
        <div class="p-5 bg-white border shadow-sm rounded-2xl border-slate-200">
            <p class="text-sm text-slate-500">Total Users</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">{{ $users->total() }}</h2>
        </div>

        <div class="p-5 bg-white border shadow-sm rounded-2xl border-slate-200">
            <p class="text-sm text-slate-500">System Administrators</p>
            <h2 class="mt-2 text-3xl font-bold text-red-600">{{ $users->where('role', 'admin')->count() }}</h2>
        </div>

        <div class="p-5 bg-white border shadow-sm rounded-2xl border-slate-200">
            <p class="text-sm text-slate-500">Asset Officers</p>
            <h2 class="mt-2 text-3xl font-bold text-blue-600">{{ $users->where('role', 'asset_officer')->count() }}</h2>
        </div>

        <div class="p-5 bg-white border shadow-sm rounded-2xl border-slate-200">
            <p class="text-sm text-slate-500">Managers</p>
            <h2 class="mt-2 text-3xl font-bold text-amber-500">{{ $users->where('role', 'manager')->count() }}</h2>
        </div>
    </div>

    <div class="p-4 mb-6 bg-white border shadow-sm rounded-2xl border-slate-200">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-col gap-3 md:flex-row">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or role..."
                class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100">

            <div class="flex gap-3">
                <button type="submit"
                    class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Search
                </button>

                <a href="{{ route('users.index') }}"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">Accounts</h2>
            <p class="mt-1 text-sm text-slate-500">
                Use “Login As” to switch into another account. You cannot impersonate your own account.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-left">Name</th>
                        <th class="px-4 py-3 font-semibold text-left">Email</th>
                        <th class="px-4 py-3 font-semibold text-left">Role</th>
                        <th class="px-4 py-3 font-semibold text-left">Assignment History</th>
                        <th class="px-4 py-3 font-semibold text-left">Active Assignments</th>
                        <th class="px-4 py-3 font-semibold text-left">Activity Logs</th>
                        <th class="px-4 py-3 font-semibold text-left">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 text-sm font-bold text-white rounded-xl bg-slate-900">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $user->name }}</div>

                                        @if(auth()->id() === $user->id)
                                            <div
                                                class="mt-1 inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-[11px] font-semibold text-blue-700">
                                                Current Account
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-slate-700">{{ $user->email }}</td>

                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    {{ $user->role === 'admin' ? 'System Administrator' : ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-slate-700">{{ $user->assignments_count }}</td>
                            <td class="px-4 py-4 text-slate-700">{{ $user->active_assignments_count }}</td>
                            <td class="px-4 py-4 text-slate-700">{{ $user->asset_logs_count }}</td>

                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('users.show', $user) }}"
                                        class="rounded-lg bg-slate-700 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-800">
                                        View
                                    </a>

                                    <a href="{{ route('users.edit', $user) }}"
                                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">
                                        Edit
                                    </a>

                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('users.impersonate', $user) }}">
                                            @csrf
                                            <button type="submit"
                                                class="rounded-lg bg-purple-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-purple-700">
                                                Login As
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" disabled
                                            class="cursor-not-allowed rounded-lg bg-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-500">
                                            Current User
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-slate-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endsection