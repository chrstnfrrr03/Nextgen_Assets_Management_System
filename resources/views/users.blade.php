<x-app-layout>

    <div class="space-y-8">

        <!-- HEADER -->
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Users Management
            </h1>
            <p class="text-sm text-gray-500">
                Manage system users and access
            </p>
        </div>

        <!-- SEARCH -->
        <div class="p-4 bg-white border shadow rounded-xl">
            <form method="GET" action="/users" class="flex gap-2">
                <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}"
                    class="w-full px-4 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500">

                <button class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Search
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="overflow-hidden bg-white shadow rounded-2xl">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-50">
                <h3 class="font-semibold text-gray-700">All Users</h3>

                <span class="text-sm text-gray-400">
                    {{ $users->total() }} total
                </span>
            </div>

            <table class="w-full text-sm">
                <thead class="text-xs text-gray-600 uppercase bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Joined</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($users as $user)
                        <tr class="transition hover:bg-slate-50">

                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $user->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $user->email }}
                            </td>

                            <td class="px-6 py-4 text-gray-400">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                            <!-- ACTIONS -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">

                                    <!-- DELETE -->
                                    <form method="POST" action="/users/{{ $user->id }}">
                                        @csrf
                                        @method('DELETE')

                                        <button onclick="return confirm('Delete this user?')"
                                            class="px-3 py-1 text-xs font-semibold text-white bg-red-500 rounded-lg hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-400">
                                No users found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

            <!-- PAGINATION -->
            <div class="p-4">
                {{ $users->links() }}
            </div>

        </div>

    </div>

</x-app-layout>