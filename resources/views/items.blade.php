<x-app-layout>

    <div class="space-y-8">

        <!-- HEADER -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Asset Management</h1>
                <p class="text-sm text-gray-500">Manage and track all company assets</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('assets.create') }}"
                    class="px-5 py-2 text-sm font-medium text-white bg-green-600 shadow rounded-xl hover:bg-green-700">
                    + Add Asset
                </a>

                <a href="{{ route('assets.export') }}"
                    class="px-5 py-2 text-sm font-medium text-white bg-blue-600 shadow rounded-xl hover:bg-blue-700">
                    Export CSV
                </a>
            </div>
        </div>

        <!-- TABLE -->
        <div class="overflow-hidden bg-white border shadow-lg rounded-2xl">

            <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-50">
                <h3 class="font-semibold text-gray-700">All Assets</h3>
                <span class="text-sm text-gray-400">Total: {{ $items->total() }}</span>
            </div>

            <table class="w-full text-sm">
                <thead class="text-xs text-gray-500 uppercase bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left">Code</th>
                        <th class="px-6 py-4 text-left">Brand</th>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Assigned</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach($items as $item)
                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4 font-medium">{{ $item->part_no }}</td>
                            <td class="px-6 py-4">{{ $item->brand }}</td>
                            <td class="px-6 py-4">{{ $item->part_name }}</td>

                            <!-- ASSIGNED -->
                            <td class="px-6 py-4 text-xs text-gray-600">
                                @forelse($item->activeAssignments as $assign)
                                    <div>
                                        @if($assign->department)
                                            {{ $assign->department->name }}
                                        @endif

                                        @if($assign->user)
                                            - {{ $assign->user->name }}
                                        @endif

                                        ({{ $assign->quantity }})
                                        ({{ $assign->quantity }})
                                    </div>
                                @empty
                                    -
                                @endforelse
                            </td>

                            <!-- STATUS -->
                        <td class="px-6 py-4 text-xs">

                            @if($item->computed_status === 'out')
                                <span class="px-2 py-1 text-red-700 bg-red-100 rounded-lg">
                                    Out of Stock
                                </span>

                            @elseif($item->computed_status === 'partial')
                                <span class="px-2 py-1 text-yellow-700 bg-yellow-100 rounded-lg">
                                    {{ $item->totalAssigned() }} Assigned /
                                    {{ $item->availableQuantity() }} Available
                                </span>

                            @else
                                <span class="px-2 py-1 text-green-700 bg-green-100 rounded-lg">
                                    {{ $item->availableQuantity() }} Available
                                </span>

                            @endif

                        </td>

                            <!-- ACTIONS -->
                            <td class="px-6 py-4 space-y-2">

                                @if($item->availableQuantity() > 0)

                                    <form method="POST" action="{{ route('assets.assign', $item->id) }}">
                                        @csrf

                                        <!-- Department (REQUIRED) -->
                                        <select name="department_id" required>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                            @endforeach
                                        </select>

                                        <!-- User (OPTIONAL) -->
                                        <select name="user_id">
                                            <option value="">Optional User</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>

                                        <!-- Quantity -->
                                        <input type="number" name="quantity" min="1" max="{{ $item->availableQuantity() }}"
                                            placeholder="Qty max {{ $item->availableQuantity() }}" required>

                                        <button>Assign</button>
                                    </form>

                                @else
                                    <div class="text-xs text-gray-400">
                                        No stock
                                    </div>
                                @endif

                                <!-- RETURNS -->
                                @foreach($item->activeAssignments as $assign)
                                    <form method="POST" action="{{ route('assets.return', $assign->id) }}">
                                        @csrf
                                        <button
                                            class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                                            Return {{ $assign->quantity }}
                                        </button>
                                    </form>
                                @endforeach

                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>

            <!-- PAGINATION -->
            <div class="p-4">
                {{ $items->links() }}
            </div>

        </div>

        <!-- ACTIVITY LOG -->
        <div class="p-6 bg-white border shadow-lg rounded-2xl">

            <h3 class="mb-4 font-semibold text-gray-700">Activity Log</h3>

            @forelse($logs as $log)
                <div class="flex justify-between p-3 mb-2 text-sm rounded-lg bg-slate-50">
                    <span>
                        {{ ucfirst($log->action) }} -
                        {{ optional($log->item)->part_name ?? 'Asset' }}
                        by {{ optional($log->user)->name ?? 'System' }}
                    </span>
                    <span class="text-xs text-gray-400">
                        {{ $log->created_at?->diffForHumans() }}
                    </span>
                </div>
            @empty
                <div class="text-gray-400">No activity</div>
            @endforelse

        </div>

    </div>

</x-app-layout>