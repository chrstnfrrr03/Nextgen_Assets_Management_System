<x-app-layout>

    <div class="max-w-2xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Add New Asset</h1>

        @if ($errors->any())
            <div class="p-4 text-red-700 bg-red-100 rounded-lg">
                @foreach ($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('assets.store') }}" class="space-y-4">
            @csrf

            <input name="part_no" placeholder="Part Number" class="w-full px-4 py-2 border rounded-lg" required>

            <input name="brand" placeholder="Brand" class="w-full px-4 py-2 border rounded-lg" required>

            <input name="part_name" placeholder="Name" class="w-full px-4 py-2 border rounded-lg" required>

            <input name="description" placeholder="Description" class="w-full px-4 py-2 border rounded-lg" required>

            <button class="w-full py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Save Asset
            </button>
        </form>

    </div>

</x-app-layout>