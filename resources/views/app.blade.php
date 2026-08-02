<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ShopBoard') }}</title>
    <link rel="stylesheet" href="{{ mix('resources/css/app.css') }}">
    @inertiaHead
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen antialiased">
    @inertia
    <script src="{{ mix('resources/js/app.js') }}" defer></script>
</body>
</html>
