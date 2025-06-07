<x-app-layout>
    @section('title', 'Library - ' . config('app.name'))
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Your Courses') }} 
            </h2>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="mx-auto p-7 overflow-hidden sm:rounded-lg">
                <div class="flex items-center justify-end mb-7">
                    <!-- Combined Sort + Search Box -->
                    <div class="flex items-center bg-white border border-gray-300 rounded-lg overflow-hidden">
                        <!-- Sort Dropdown -->
                        <div class="relative">
                            <button id="sortDropdownButton" data-dropdown-toggle="sortDropdown" type="button"
                                class="text-gray-500 hover:bg-gray-100 px-3 py-2 text-sm inline-flex items-center">
                                <span class="mr-2">Sort by: {{ ucfirst(str_replace('_', ' ', $sort)) }}</span>
                                <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            @include('components.sort-dropdown')
                        </div>
                
                        <!-- Search Input -->
                        <form action="{{ route('user.course.library') }}" method="GET" class="w-64">
                            <input type="hidden" name="sort" value="{{ $sort }}">
                            <input type="hidden" name="direction" value="{{ $direction }}">
                            <input type="text" 
                                   name="search" 
                                   value="{{ $search ?? '' }}"
                                   placeholder="Search courses..."
                                   class="w-full border-none text-sm px-3 py-2 focus:outline-none focus:ring-0">
                        </form>
                    </div>
                </div>
                {{-- User Already Saved Course --}}
                @include('user.component.user_course')
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $userCourses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>