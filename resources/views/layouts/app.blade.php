<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
        <link rel="stylesheet" href="{{ asset('resources/css/styles.css') }}">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/layout/main.js', 'resources/js/settings/main.js'])
    </head>
    <body class="font-sans antialiased pt-16 bg-white dark:bg-gray-900">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')
            @guest
                @include('partials.need-login')
            @endguest
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:text-white dark:bg-gray-800 shadow border-b dark:border-gray-600">
                    <div class="max-w-8xl dark:text-white mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Success and Error Messages -->
            <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md px-4">
                @if(session('success'))
                    <x-input-success :messages="[session('success')]" class="mb-4" />
                @endif
                @if(session('error'))
                    <x-input-error :messages="[session('error')]" class="mb-4" />
                @endif
                @if (session('status'))
                    <x-input-success :messages="[session('status')]" class="mb-4" />
                @endif
            </div>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

        </div>
        <!-- Footer -->
        @include('partials.footer')
        @include('layouts.footer')
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.0/flowbite.min.js"></script>

    </body>
</html>
