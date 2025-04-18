<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Available Courses') }} 
            </h2>
            <div class="flex items-center gap-4">
                <!-- Sort Dropdown -->
                <div class="relative">
                    <button id="sortDropdownButton" data-dropdown-toggle="sortDropdown" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg text-sm px-3 py-2 text-center inline-flex items-center border">
                        <span class="mr-2">Sort by: {{ ucfirst($sort) }}</span>
                        <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    @include('components.sort-dropdown')
                </div>
                
                <!-- Search Input -->
                <div class="w-64">
                    <form action="{{ route('course.feed') }}" method="GET">
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
            <div class="mx-auto p-7 bg-white overflow-hidden sm:rounded-lg">
                @include('user.component.available_course')
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $availableCourses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>