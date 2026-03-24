<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>NextGen Assets Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-slate-100">

    <!-- =========================
         LOGIN CARD
    ========================== -->
    <div class="w-full max-w-md p-8 bg-white shadow-xl rounded-2xl">

        <!-- TITLE -->
        <h2 class="mb-6 text-2xl font-bold text-center text-slate-800">
            NextGen Assets Login
        </h2>

        <!-- =========================
             SUCCESS / SESSION MESSAGE
        ========================== -->
        @if (session('status'))
            <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded">
                {{ session('status') }}
            </div>
        @endif

        <!-- =========================
             LOGIN FORM
        ========================== -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <div class="mb-4">
                <label class="block mb-1 text-sm text-gray-600">
                    Email
                </label>

                <input type="email" name="email" required value="{{ old('email') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                <!-- ERROR -->
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div class="mb-4">
                <label class="block mb-1 text-sm text-gray-600">
                    Password
                </label>

                <input type="password" name="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                <!-- ERROR -->
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- REMEMBER -->
            <div class="flex items-center mb-4">
                <input type="checkbox" name="remember" class="mr-2">
                <span class="text-sm text-gray-600">Remember me</span>
            </div>

            <!-- BUTTON -->
            <button class="w-full py-2 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Login
            </button>

        </form>

        <!-- =========================
             LINKS SECTION
        ========================== -->
        <div class="mt-4 text-center space-y-2">

            <!-- REGISTER -->
            <a href="{{ route('register') }}" class="block text-sm text-blue-600 hover:underline">
                Create an account
            </a>

            <!-- FORGOT PASSWORD -->
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="block text-sm text-gray-500 hover:underline">
                    Forgot your password?
                </a>
            @endif

        </div>

    </div>

</body>

</html>