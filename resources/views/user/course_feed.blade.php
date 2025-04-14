<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelas') }} 
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="mx-auto p-7 bg-white overflow-hidden sm:rounded-lg">
                {{-- Available Courses Section --}}
                @include('user.component.available_course')
            </div>
        </div>
    </div>
</x-app-layout>