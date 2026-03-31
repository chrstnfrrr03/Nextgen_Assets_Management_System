<x-app-layout>

    <div class="space-y-8">

        <!-- HEADER -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Asset Management</h1>
                <p class="text-sm text-gray-500">Manage and track all company assets</p>
            </div>

            <!-- EXPORT BUTTON -->
            <a href="{{ route('assets.export') }}"
               class="px-5 py-2 text-sm font-medium text-white bg-blue-600 shadow rounded-xl hover:bg-blue-700">
                Export CSV
            </a>
        </div>

        <!-- TABLE CARD -->
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
                        <tr class="transition hover:bg-slate-50">

                            <td class="px-6 py-4 font-medium">{{ $item->part_no }}</td>
                            <td class="px-6 py-4">{{ $item->brand }}</td>
                            <td class="px-6 py-4">{{ $item->part_name }}</td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $item->user->name ?? '-' }}
                            </td>

                            <!-- STATUS BADGE -->
                            <td class="px-6 py-4">
                                @php $status = $item->status ?? 'available'; @endphp

                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $status == 'available' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $status == 'assigned' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $status == 'maintenance' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <!-- ACTIONS -->
                            <td class="px-6 py-4">

                                <form method="POST" action="{{ route('assets.update', $item->id) }}"
                                    class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')

                                    <!-- USER -->
                                    <select name="assigned_to"
                                        class="px-2 py-1 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Assign</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $item->assigned_to == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- STATUS -->
                                    <select name="status"
                                        class="px-2 py-1 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="available" {{ $item->status == 'available' ? 'selected' : '' }}>
                                            Available</option>
                                        <option value="assigned" {{ $item->status == 'assigned' ? 'selected' : '' }}>
                                            Assigned</option>
                                        <option value="maintenance" {{ $item->status == 'maintenance' ? 'selected' : '' }}>
                                            Maintenance</option>
                                    </select>

                                    <!-- SAVE BUTTON -->
                                    <button class="px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Save
                                    </button>
                                </form>

                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>

            <!--  PAGINATION (ADDED) -->
            <div class="p-4">
                {{ $items->links() }}
            </div>

        </div>

        <!-- ACTIVITY LOG -->
        <div class="p-6 bg-white border shadow-lg rounded-2xl">

            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-700">Activity Log</h3>
                <span class="text-xs text-gray-400">Latest actions</span>
            </div>

            <div class="space-y-3 text-sm">

                @forelse($logs as $log)
                    <div class="flex items-center justify-between p-3 transition rounded-lg bg-slate-50 hover:bg-slate-100">

                        <div class="flex items-center gap-2">

                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $log->action == 'created' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $log->action == 'updated' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $log->action == 'deleted' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($log->action) }}
                            </span>

                            <span class="text-gray-700">
                                {{ optional($log->item)->part_name ?? 'Asset' }}
                            </span>

                            <span class="text-gray-500">
                                by {{ optional($log->user)->name ?? 'System' }}
                            </span>

                        </div>

                        <span class="text-xs text-gray-400">
                            {{ $log->created_at?->diffForHumans() }}
                        </span>

                    </div>

                @empty
                    <div class="py-6 text-center text-gray-400">
                        No activity yet
                    </div>
                @endforelse

            </div>

        </div>

    </div>

</x-app-layout>