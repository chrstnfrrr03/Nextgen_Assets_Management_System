<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex items-center justify-center min-h-screen bg-slate-100">

    <div class="w-full max-w-md p-6 bg-white border shadow rounded-2xl">
        @yield('content')
    </div>

</body>

</html>