<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <div class="w-64 bg-gray-900 text-white p-6 space-y-6">
            <h2 class="text-xl font-bold">Inventory</h2>

            <nav class="space-y-2 text-sm">
                <p class="hover:text-blue-400 cursor-pointer">Dashboard</p>
                <p class="hover:text-blue-400 cursor-pointer">Items</p>
            </nav>
        </div>

        <!-- MAIN -->
        <div class="flex-1 p-8">

            <!-- TITLE -->
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                Motor Grader Inventory
            </h2>

            <!-- FORM CARD -->
        <div class="bg-gray-700 p-6 rounded-xl shadow-md mb-6 text-white">
</div> 

                <form method="POST" action="/items" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    @csrf

                    <input name="part_no" placeholder="Part No" required
                        class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

                    <input name="brand" placeholder="Brand" required
                        class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

                    <input name="part_name" placeholder="Part Name" required
                        class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

                    <input name="description" placeholder="Description" required
                        class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

                    <button
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow font-medium">
                        + Add
                    </button>
                </form>

            </div>

            <!-- TABLE CARD -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">

                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Part No</th>
                            <th class="px-6 py-3 text-left">Brand</th>
                            <th class="px-6 py-3 text-left">Part Name</th>
                            <th class="px-6 py-3 text-left">Description</th>
                            <th class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-4">{{ $item->part_no }}</td>
                                <td class="px-6 py-4">{{ $item->brand }}</td>
                                <td class="px-6 py-4">{{ $item->part_name }}</td>
                                <td class="px-6 py-4">{{ $item->description }}</td>

                                <td class="px-6 py-4 text-center">

                                    <!-- DELETE -->
                                    <form method="POST" action="/items/{{ $item->id }}" class="inline">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition text-sm">
                                            Delete
                                        </button>
                                    </form>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>

        </div>
    </div>
</x-app-layout>