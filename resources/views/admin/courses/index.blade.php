<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelas') }}
            </h2>
            <div class="flex items-center gap-4">
                <!-- Sort Dropdown -->
                <div class="relative">
                    <button id="sortDropdownButton" data-dropdown-toggle="sortDropdown" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg text-sm px-3 py-2 text-center inline-flex items-center border" type="button">
                        <span class="mr-2">Sort by: {{ ucfirst($sort) }}</span>
                        <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="sortDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                        <ul class="py-2 text-sm text-gray-700" aria-labelledby="sortDropdownButton">
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => $sort === 'name' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="block px-4 py-2 hover:bg-gray-100">
                                    Name {{ $sort === 'name' ? ($direction === 'asc' ? '↑' : '↓') : '' }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => $sort === 'created_at' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="block px-4 py-2 hover:bg-gray-100">
                                    Created Date {{ $sort === 'created_at' ? ($direction === 'asc' ? '↑' : '↓') : '' }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'updated_at', 'direction' => $sort === 'updated_at' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="block px-4 py-2 hover:bg-gray-100">
                                    Updated Date {{ $sort === 'updated_at' ? ($direction === 'asc' ? '↑' : '↓') : '' }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Search Input -->
                <div class="w-64">
                    <form action="{{ route('admin.courses.index') }}" method="GET">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <input type="hidden" name="direction" value="{{ $direction }}">
                        <input type="text" 
                               name="search" 
                               value="{{ $search ?? '' }}"
                               placeholder="Search courses..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </form>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-7">
                <!-- Course Cards -->
                <div class="flex flex-wrap gap-7 justify-start">
                    <!-- Add New Course Card - Fixed Position -->
                    <div data-modal-target="crud-modal-create" data-modal-toggle="crud-modal-create"
                    class="cursor-pointer bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition flex flex-col items-center justify-center p-4" style="width: 208px; height: 350px;">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <p class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tambah Kelas Baru</p>
                        </div>
                    </div>

                    @include('admin.courses.component.available_course')
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $courses->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Create Course Modal -->
    <div id="crud-modal-create" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            @include('admin.courses.create')
        </div>
    </div>

    @vite(['resources/js/course-theme.js'])
</x-app-layout>
