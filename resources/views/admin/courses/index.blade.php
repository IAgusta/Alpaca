<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-7">
                <!-- Course Cards -->
                <div class="flex flex-wrap gap-7 justify-start">
                    <!-- Add New Course Card -->
                    <div data-modal-target="crud-modal-create" data-modal-toggle="crud-modal-create"
                    class="cursor-pointer bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition flex flex-col items-center justify-center p-4" style="width: 208px; height: 350px;">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <p class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tambah Kelas Baru</p>
                        </div>
                    </div>

                    <!-- Main modal -->
                    <div id="crud-modal-create" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            @include('admin.courses.create')
                        </div>
                    </div>

                    @include('admin.courses.component.available_course')
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/course-theme.js'])
</x-app-layout>
