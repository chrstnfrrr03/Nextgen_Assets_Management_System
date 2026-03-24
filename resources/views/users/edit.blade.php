<x-app-layout>

    <div class="max-w-xl p-6 mx-auto bg-white shadow rounded-2xl">

        <h2 class="mb-4 text-xl font-bold">Edit User</h2>

        <form method="POST" action="/users/{{ $user->id }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label>Name</label>
                <input type="text" name="name" value="{{ $user->name }}" class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="w-full px-4 py-2 border rounded-lg">
            </div>

            <button class="px-4 py-2 text-white bg-blue-600 rounded-lg">
                Update User
            </button>

        </form>

    </div>

</x-app-layout>