@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-4 mb-8 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Inventory Management</h1>
            <p class="mt-2 text-sm text-slate-500 sm:text-base">
                Manage stock movement, monitor quantity levels, and respond to low-stock items quickly.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <div class="px-4 py-3 bg-white border shadow-sm rounded-2xl border-slate-200">
                <p class="text-xs font-medium tracking-wide uppercase text-slate-400">Items Listed</p>
                <p class="mt-1 text-lg font-semibold text-slate-900">{{ $items->total() }}</p>
            </div>
        </div>
    </div>

    <div class="p-4 mb-6 bg-white border shadow-sm rounded-2xl border-slate-200">
        <form method="GET" action="{{ route('inventory.index') }}" class="flex flex-col gap-3 md:flex-row">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search asset name, asset tag, serial number, category, supplier, or department..."
                    class="w-full py-3 pr-4 text-sm border rounded-2xl border-slate-200 bg-slate-50 pl-11 text-slate-700 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100"
                >
            </div>

            <div class="flex gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-white transition rounded-2xl bg-slate-900 hover:bg-slate-800"
                >
                    Search
                </button>

                <a
                    href="{{ route('inventory.index') }}"
                    class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold transition bg-white border rounded-2xl border-slate-200 text-slate-700 hover:bg-slate-50"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden card card-hover">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">Inventory Table</h2>
            <p class="mt-1 text-sm text-slate-500">
                Use stock actions carefully. All stock movements are logged automatically by the system.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-left">Asset</th>
                        <th class="px-6 py-4 font-semibold text-left">Category</th>
                        <th class="px-6 py-4 font-semibold text-left">Department</th>
                        <th class="px-6 py-4 font-semibold text-left">Quantity</th>
                        <th class="px-6 py-4 font-semibold text-left">Level</th>
                        <th class="px-6 py-4 font-semibold text-left">Stock In</th>
                        <th class="px-6 py-4 font-semibold text-left">Stock Out</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $item)
                        <tr class="align-top transition hover:bg-slate-50/70">
                            <td class="px-6 py-4">
                                <div class="min-w-[220px]">
                                    <p class="font-semibold text-slate-900">{{ $item->name }}</p>

                                    <div class="mt-1 space-y-1 text-xs text-slate-500">
                                        @if($item->asset_tag)
                                            <p>Tag: {{ $item->asset_tag }}</p>
                                        @endif

                                        @if($item->serial_number)
                                            <p>Serial: {{ $item->serial_number }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-slate-700">
                                {{ $item->category?->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-slate-700">
                                {{ $item->department?->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-sm font-semibold rounded-lg bg-slate-100">
                                    {{ $item->quantity }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if($item->quantity <= 0)
                                    <span class="badge badge-danger">Out</span>
                                @elseif($item->quantity <= 3)
                                    <span class="badge badge-danger">Low</span>
                                @elseif($item->quantity <= 10)
                                    <span class="badge badge-warning">Medium</span>
                                @else
                                    <span class="badge badge-success">Healthy</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('inventory.stock-in', $item) }}" class="flex min-w-[170px] items-center gap-2">
                                    @csrf

                                    <input
                                        type="number"
                                        name="quantity"
                                        min="1"
                                        inputmode="numeric"
                                        class="w-24 px-3 py-2 text-sm bg-white border rounded-xl border-slate-200 text-slate-700 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100"
                                        placeholder="Qty"
                                        required
                                    >

                                    <button
                                        type="submit"
                                        class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white transition rounded-xl bg-emerald-600 hover:bg-emerald-700"
                                    >
                                        In
                                    </button>
                                </form>
                            </td>

                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('inventory.stock-out', $item) }}" class="flex min-w-[170px] items-center gap-2">
                                    @csrf

                                    <input
                                        type="number"
                                        name="quantity"
                                        min="1"
                                        inputmode="numeric"
                                        class="w-24 px-3 py-2 text-sm bg-white border rounded-xl border-slate-200 text-slate-700 focus:border-rose-500 focus:outline-none focus:ring-4 focus:ring-rose-100"
                                        placeholder="Qty"
                                        required
                                    >

                                    <button
                                        type="submit"
                                        class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white transition rounded-xl bg-rose-600 hover:bg-rose-700"
                                    >
                                        Out
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="max-w-md mx-auto">
                                    <p class="text-base font-semibold text-slate-900">No inventory records found</p>
                                    <p class="mt-2 text-sm text-slate-500">
                                        Try a different search term or reset the filter to view all inventory items.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
@endsection