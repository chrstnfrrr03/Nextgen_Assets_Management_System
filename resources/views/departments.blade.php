<x-app-layout>

    <div class="space-y-6">

        <h1 class="text-2xl font-bold">Departments</h1>

        <!-- ADD -->
        <form method="POST" action="{{ route('departments.store') }}" class="flex gap-2">
            @csrf

            <input name="name" placeholder="Department Name" class="p-2 border rounded" required>
            <input name="description" placeholder="Description" class="p-2 border rounded">

            <button class="px-4 text-white bg-green-600 rounded">
                + Add
            </button>
        </form>

        <!-- LIST -->
        <div class="p-4 bg-white rounded shadow">

            @foreach($departments as $dept)
                <div class="flex justify-between py-2 border-b">

                    <div>
                        <strong>{{ $dept->name }}</strong>
                        <p class="text-sm text-gray-500">{{ $dept->description }}</p>
                    </div>

                    <form method="POST" action="{{ route('departments.destroy', $dept->id) }}">
                        @csrf
                        @method('DELETE')

                        <button class="text-red-500">Delete</button>
                    </form>

                </div>
            @endforeach

        </div>

    </div>

</x-app-layout>