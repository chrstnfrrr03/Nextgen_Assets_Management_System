<x-app-layout>

    <div class="space-y-8">

        <!-- HEADER -->
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Categories Management</h1>
            <p class="text-sm text-gray-500">Organize your products into categories</p>
        </div>

        <!-- SUCCESS -->
        @if(session('success'))
            <div class="p-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- ERRORS -->
        @if ($errors->any())
            <div class="p-4 text-red-700 bg-red-100 rounded-lg">
                @foreach ($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- SEARCH + ADD -->
        <div class="p-6 space-y-4 bg-white border shadow rounded-xl">

            <!-- SEARCH + FILTER -->
            <form method="GET" action="{{ route('categories') }}" class="flex flex-wrap gap-2">

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..."
                    class="px-4 py-2 border rounded-lg">

                <input type="text" name="name" value="{{ request('name') }}" placeholder="Filter name"
                    class="px-4 py-2 border rounded-lg">

                <input type="text" name="description" value="{{ request('description') }}"
                    placeholder="Filter description" class="px-4 py-2 border rounded-lg">

                <button class="px-4 py-2 text-white bg-blue-600 rounded-lg">
                    Apply
                </button>

                <a href="{{ route('categories') }}" class="px-4 py-2 text-white bg-gray-500 rounded-lg">
                    Reset
                </a>

            </form>

            <!-- ADD CATEGORY -->
            <form method="POST" action="{{ route('categories.store') }}" class="grid grid-cols-1 gap-3 md:grid-cols-3">
                @csrf

                <input name="name" placeholder="Category Name" class="px-3 py-2 border rounded-lg" required>

                <input name="description" placeholder="Description" class="px-3 py-2 border rounded-lg">

                <button class="text-white bg-green-600 rounded-lg hover:bg-green-700">
                    + Add Category
                </button>

            </form>

        </div>

        <!-- TABLE -->
        <div class="overflow-hidden bg-white border shadow rounded-2xl">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-50">
                <h3 class="font-semibold text-gray-700">All Categories</h3>

                <span class="text-sm text-gray-400">
                    {{ $categories->total() }} total
                </span>
            </div>

            <table class="w-full text-sm">
                <thead class="text-xs text-gray-600 uppercase bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Description</th>
                        <th class="px-6 py-4 text-left">Products</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4 font-medium">{{ $cat->name }}</td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $cat->description ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $cat->items_count ?? $cat->items->count() ?? 0 }}
                            </td>

                            <!-- ✅ CLEAN ACTIONS -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('categories.edit', $cat->id) }}"
                                        class="px-3 py-1 text-xs text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('categories.destroy', $cat->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button onclick="return confirm('Delete this category?')"
                                            class="px-3 py-1 text-xs text-white bg-red-500 rounded-lg">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-400">
                                No categories found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- PAGINATION -->
            <div class="p-4">
                {{ $categories->links() }}
            </div>

        </div>

    </div>

</x-app-layout>