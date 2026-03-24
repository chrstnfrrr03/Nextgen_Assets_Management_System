<x-app-layout>

    <div class="p-6">

        <h1 class="mb-4 text-2xl font-bold">Suppliers</h1>

        <!-- ADD FORM -->
        <form method="POST" action="{{ route('suppliers.store') }}" class="mb-6">
            @csrf

            <input type="text" name="name" placeholder="Supplier Name" class="border p-2 mr-2">
            <input type="text" name="email" placeholder="Email" class="border p-2 mr-2">

            <button class="px-4 py-2 text-white bg-blue-600">Add</button>
        </form>

        <!-- TABLE -->
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>
                            <form method="POST" action="{{ route('suppliers.destroy', $supplier->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</x-app-layout>