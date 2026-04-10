@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Assets</h1>
            <p class="text-sm text-slate-500">Manage asset records and lifecycle</p>
        </div>

        <div class="flex gap-2">
            @if(auth()->user()->isAdmin() || auth()->user()->isAssetOfficer())
                <a href="{{ route('items.create') }}"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    + Add Asset
                </a>

                <a href="{{ route('assignments.create') }}"
                    class="px-4 py-2 text-sm font-semibold text-white rounded-lg bg-slate-900 hover:bg-slate-800">
                    Assign
                </a>
            @endif
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('items.index') }}" class="p-4 mb-5 bg-white shadow-sm rounded-xl">

        <div class="grid grid-cols-1 gap-3 md:grid-cols-4">

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search assets..."
                class="px-3 py-2 text-sm border rounded-lg border-slate-300 focus:ring-2 focus:ring-blue-500">

            <select name="status" onchange="this.form.submit()"
                class="px-3 py-2 text-sm border rounded-lg border-slate-300">
                <option value="">All Status</option>
                <option value="available" @selected(request('status') == 'available')>Available</option>
                <option value="assigned" @selected(request('status') == 'assigned')>Assigned</option>
                <option value="maintenance" @selected(request('status') == 'maintenance')>Maintenance</option>
                <option value="retired" @selected(request('status') == 'retired')>Retired</option>
            </select>

            <select name="category_id" onchange="this.form.submit()"
                class="px-3 py-2 text-sm border rounded-lg border-slate-300">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="department_id" onchange="this.form.submit()"
                class="px-3 py-2 text-sm border rounded-lg border-slate-300">
                <option value="">All Departments</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2 mt-3">
            <button class="px-4 py-2 text-sm text-white rounded-lg bg-slate-900 hover:bg-slate-800">
                Search
            </button>

            <a href="{{ route('items.index') }}"
                class="px-4 py-2 text-sm rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">
                Reset
            </a>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="overflow-hidden bg-white shadow-sm rounded-xl">
        <table class="w-full text-sm">
            <thead class="text-xs uppercase bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left">Asset</th>
                    <th class="px-4 py-3 text-left">Tag</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Department</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Assigned</th>
                    <th class="px-4 py-3 text-left">Manage</th>
                </tr>
            </thead>

            <tbody>
                @forelse($items as $item)
                    <tr class="transition border-t hover:bg-slate-50">

                        <td class="px-4 py-3 font-medium">
                            <a href="{{ route('items.show', $item) }}" class="hover:underline">
                                {{ $item->name }}
                            </a>
                        </td>

                        <td class="px-4 py-3 text-slate-500">
                            {{ $item->asset_tag ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->category?->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->department?->name ?? '-' }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 py-3">
                            @php
                                $map = [
                                    'available' => 'bg-green-100 text-green-700',
                                    'assigned' => 'bg-yellow-100 text-yellow-700',
                                    'maintenance' => 'bg-red-100 text-red-700',
                                    'retired' => 'bg-gray-200 text-gray-700'
                                ];
                            @endphp

                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $map[$item->status] ?? '' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        {{-- ASSIGNED USER --}}
                        <td class="px-4 py-3 text-slate-600">
                            {{ $item->activeAssignment?->user?->name ?? 'Unassigned' }}
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">

                                <a href="{{ route('items.show', $item) }}" class="text-xs text-blue-600 hover:underline">
                                    View
                                </a>

                                @if(auth()->user()->isAdmin() || auth()->user()->isAssetOfficer())
                                    <a href="{{ route('items.edit', $item) }}" class="text-xs text-slate-600 hover:underline">
                                        Edit
                                    </a>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <form method="POST" action="{{ route('items.destroy', $item) }}"
                                        onsubmit="return confirm('Delete asset?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="text-xs text-red-600 hover:underline">
                                            Delete
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-slate-500">
                            No assets found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $items->withQueryString()->links() }}
    </div>

@endsection