<x-app-layout>  
    @section('title', 'Controller - '. config('app.name'))
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Controller') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                
                @auth
                    @include('plugins.partials.type')    
                @endauth
                
                @include('plugins.partials.connection')
                  
                @include('plugins.partials.control')
            </div>
        </div>
    </div>
    @vite(['resources/js/robot/main.js'])
</x-app-layout>