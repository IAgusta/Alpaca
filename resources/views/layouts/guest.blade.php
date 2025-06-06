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
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/layout/main.js', 'resources/js/animations/starfield.js', 'resources/js/settings/main.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased overflow-hidden">
        <div class="flex h-screen w-full">
            <!-- Animation Section -->
            <div class="hidden lg:block lg:w-1/2 relative bg-gray-900">
                <!-- Background Image with Overlay -->
                <div class="absolute inset-0 bg-cover bg-center z-0" style="background-image: url('{{ asset('img/background.png') }}');">
                    <div class="absolute inset-0 bg-gray-900/50"></div>
                </div>
                <!-- Starfield with reduced opacity to blend -->
                <canvas id="starfield" class="absolute inset-0 w-full h-full z-10 mix-blend-screen opacity-70"></canvas>
            </div>
            
            <!-- Content Section -->
            <div class="w-full lg:w-1/2 flex flex-col h-screen bg-white/90 dark:bg-gray-900">
                <!-- Main Content Area -->
                <div class="flex-1 overflow-auto px-6 py-12 lg:px-8">
                    <div class="sm:mx-auto sm:w-full sm:max-w-sm mb-3">
                        <a href="/" class="justify-center flex">
                            <img src="{{ asset('img/logo.png') }}" class="w-20 h-20" />
                        </a>
                    </div>

                    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                        {{ $slot }}
                    </div>
                </div>

                @include('layouts.footer')
            </div>
        </div>
    </body>
</html>
