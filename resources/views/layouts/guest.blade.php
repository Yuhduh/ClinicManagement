<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-100 px-4 py-10">
            <div class="absolute -left-20 -top-20 h-72 w-72 rounded-full bg-blue-200/40 blur-3xl"></div>
            <div class="absolute -right-24 -bottom-24 h-80 w-80 rounded-full bg-slate-300/40 blur-3xl"></div>
            <div class="relative z-10 w-full max-w-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
