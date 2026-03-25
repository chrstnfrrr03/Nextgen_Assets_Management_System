<x-app-layout>

    <div class="space-y-8">

        <h1 class="text-2xl font-bold">Categories</h1>

        <!-- ADD -->
        <form method="POST" action="{{ route('categories.store') }}" class="flex gap-2">
            @csrf
            <input name="name" placeholder="Category name" class="px-3 py-2 border">
            <input name="description" placeholder="Description" class="px-3 py-2 border">
            <button class="px-4 py-2 text-white bg-blue-600 rounded">Add</button>
        </form>

        <!-- LIST -->
        <table class="w-full">
            @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->description }}</td>
                    <td>
                        <form method="POST" action="/categories/{{ $cat->id }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

    </div>

</x-app-layout>