<x-app-layout>
    @section('title', 'Course List - '. config('app.name'))
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Available Courses') }} 
            </h2>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="mx-auto p-7 overflow-hidden sm:rounded-lg">
                <div class="flex items-center justify-end mb-7">
                    <!-- Combined Sort + Search -->
                    <div class="flex lg:mr-3 items-center border border-gray-300 rounded-lg bg-gray-300 dark:bg-gray-600">
                        
                        <!-- Sort Dropdown -->
                        <div class="relative">
                            <button id="sortDropdownButton" data-dropdown-toggle="sortDropdown" type="button"
                                class="text-gray-500 rounded-l-lg dark:text-white dark:hover:bg-gray-700 hover:bg-gray-100 px-3 py-2 text-sm inline-flex items-center">
                                <span class="mr-2">Sort by: {{ ucfirst($sort) }}</span>
                                <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            @include('components.sort-dropdown')
                        </div>
                
                        <!-- Search Input -->
                        <form action="{{ route('course.feed') }}" method="GET" class="w-64">
                            <input type="hidden" name="sort" value="{{ $sort }}">
                            <input type="hidden" name="direction" value="{{ $direction }}">
                            <input type="text" 
                                   name="search" 
                                   value="{{ $search ?? '' }}"
                                   placeholder="Search courses..."
                                   class="w-full rounded-r-lg dark:text-white dark:bg-gray-600 border-none focus:ring-0 focus:outline-none text-sm px-3 py-2">
                        </form>
                    </div>
                </div>
                {{-- Available course for feed --}}
                <div id="course-container" class="min-h-[200px] lg:min-h-screen">
                    <div class="flex items-center justify-center w-full h-full">
                        <div class="flex items-center gap-2 text-gray-500">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Loading courses...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    const container = document.getElementById('course-container');
    const searchForm = document.querySelector('form');
    const sortLinks = document.querySelectorAll('[data-sort]');

    async function loadCourses(url = null) {
        try {
            if (!url) url = '{{ route('course.feed') }}' + window.location.search;
            
            // Show loading spinner
            container.innerHTML = `
                <div class="flex items-center justify-center w-full h-full">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="animate-spin h-10 w-10 text-gray-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="dark:text-gray-300">Loading courses...</span>
                    </div>
                </div>
            `;
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!response.ok) throw new Error('Failed to load courses');
            container.innerHTML = await response.text();

            // Re-initialize Flowbite components
            if (window.initFlowbite) window.initFlowbite();

            // Update URL if it's different
            if (url !== window.location.href) {
                history.pushState({}, '', url);
            }

            // Attach pagination listeners
            attachPaginationListeners();
        } catch (error) {
            console.error('Error:', error);
            container.innerHTML = `
                <div class="text-red-500 text-center w-full">
                    Failed to load courses. Please try again.
                </div>
            `;
        }
    }

    function attachPaginationListeners() {
        container.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', async (e) => {
                e.preventDefault();
                await loadCourses(link.href);
                window.scrollTo(0, 0);
            });
        });
    }

    // Handle search form submission
    searchForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(searchForm);
        const url = new URL(window.location.href);
        formData.forEach((value, key) => url.searchParams.set(key, value));
        await loadCourses(url.toString());
    });

    // Handle sort clicks
    sortLinks.forEach(link => {
        link.addEventListener('click', async (e) => {
            e.preventDefault();
            await loadCourses(link.href);
        });
    });

    // Initial load
    loadCourses();

    // Handle browser back/forward buttons
    window.addEventListener('popstate', () => {
        loadCourses(window.location.href);
    });
});
</script>