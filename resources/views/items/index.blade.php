@extends('layouts.app')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Assets</h1>
                <p class="text-sm text-slate-500">Manage asset records and lifecycle</p>
            </div>

            <div class="flex gap-3">
                @if(auth()->user()->isAdmin() || auth()->user()->isAssetOfficer())
                    <a href="{{ route('items.create') }}"
                        class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 shadow rounded-xl hover:bg-blue-700">
                        + Add Asset
                    </a>

                    <a href="{{ route('assignments.create') }}"
                        class="px-4 py-2 text-sm font-semibold text-white rounded-xl bg-slate-900 hover:bg-slate-800">
                        Assign
                    </a>
                @endif
            </div>
        </div>

        {{-- FILTER CARD --}}
        <form method="GET" action="{{ route('items.index') }}"
            class="p-5 bg-white border shadow-sm rounded-2xl border-slate-200">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search assets..."
                    class="px-4 py-2 text-sm border rounded-xl border-slate-200 focus:ring-2 focus:ring-blue-500">

                <select name="status" onchange="this.form.submit()"
                    class="px-4 py-2 text-sm border rounded-xl border-slate-200">
                    <option value="">All Status</option>
                    <option value="available" @selected(request('status') == 'available')>Available</option>
                    <option value="assigned" @selected(request('status') == 'assigned')>Assigned</option>
                    <option value="maintenance" @selected(request('status') == 'maintenance')>Maintenance</option>
                    <option value="retired" @selected(request('status') == 'retired')>Retired</option>
                </select>

                <select name="category_id" onchange="this.form.submit()"
                    class="px-4 py-2 text-sm border rounded-xl border-slate-200">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="department_id" onchange="this.form.submit()"
                    class="px-4 py-2 text-sm border rounded-xl border-slate-200">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2 mt-4">
                <button class="px-5 py-2 text-sm text-white rounded-xl bg-slate-900 hover:bg-slate-800">
                    Search
                </button>

                <a href="{{ route('items.index') }}"
                    class="px-5 py-2 text-sm rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200">
                    Reset
                </a>
            </div>
        </form>

        {{-- TABLE CARD --}}
        <div class="overflow-hidden bg-white border shadow-sm border-slate-200 rounded-2xl">

            <table class="w-full text-sm">

                <thead class="text-xs uppercase bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-3 text-left">Asset</th>
                        <th class="px-5 py-3 text-left">Tag</th>
                        <th class="px-5 py-3 text-left">Category</th>
                        <th class="px-5 py-3 text-left">Department</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Assigned</th>
                        <th class="px-5 py-3 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($items as $item)
                        <tr class="transition border-t hover:bg-slate-50">

                            <td class="px-5 py-4 font-semibold text-slate-900">
                                {{ $item->name }}
                            </td>

                            <td class="px-5 py-4 text-slate-500">
                                {{ $item->asset_tag ?? '-' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $item->category?->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $item->department?->name ?? '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-5 py-4">
                                @if($item->status === 'available')
                                    <span class="badge badge-success">Available</span>
                                @elseif($item->status === 'assigned')
                                    <span class="badge badge-warning">Assigned</span>
                                @elseif($item->status === 'maintenance')
                                    <span class="badge badge-danger">Maintenance</span>
                                @else
                                    <span class="text-gray-700 bg-gray-200 badge">Retired</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $item->activeAssignment?->user?->name ?? 'Unassigned' }}
                            </td>

                            {{-- ACTIONS --}}
                            <td class="px-5 py-4">
                                <div class="flex gap-2">

                                    <a href="{{ route('items.show', $item) }}"
                                        class="px-3 py-1 text-xs text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                        View
                                    </a>

                                    @if(auth()->user()->isAdmin() || auth()->user()->isAssetOfficer())
                                        <a href="{{ route('items.edit', $item) }}"
                                            class="px-3 py-1 text-xs text-white rounded-lg bg-slate-700 hover:bg-slate-800">
                                            Edit
                                        </a>
                                    @endif

                                    @if(auth()->user()->isAdmin())
                                        <form method="POST" action="{{ route('items.destroy', $item) }}"
                                            onsubmit="return confirm('Delete asset?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="px-3 py-1 text-xs text-white bg-red-600 rounded-lg hover:bg-red-700">
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
        <div>
            {{ $items->withQueryString()->links() }}
        </div>

    </div>

@endsection