<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FitArbie') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-black text-white">
    <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0">
        <div class="mb-8">
            <a href="/">
                <x-application-logo class="w-24 h-24 fill-current text-yellow-500" />
            </a>
            <h1 class="text-center text-2xl font-black tracking-tighter mt-2">FIT ARBIE</h1>
        </div>

        <div
            class="w-full sm:max-w-md px-8 py-10 bg-slate-900 shadow-2xl overflow-hidden rounded-3xl border border-slate-800">
            {{ $slot }}
        </div>

        <p class="mt-8 text-slate-500 text-xs uppercase tracking-widest font-bold">Web Master Edition</p>
    </div>
</body>

</html>
