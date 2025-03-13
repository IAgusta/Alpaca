<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Courses') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="mx-auto p-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Your Course Section --}}
                <h2 class="text-md font-semibold mb-3">Your Course</h2>
                <div class="flex justify-start gap-4">
                    <div class="relative w-48 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition">
                        {{-- Course Image --}}
                        <div class="relative">
                            <div class="relative">
                                <!-- Dropdown Button (Top-Right Corner) -->
                                <button data-dropdown-toggle="courseDropdown" class="absolute top-2 right-0 bg-transparent p-2 rounded-full hover:bg-gray-200">
                                    <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                                        <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z"/>
                                    </svg>
                                </button>

                                <!-- Course Image -->
                                <img class="w-full h-32 object-cover rounded-t-lg" src="img/logo.png" alt="Course Image"/>                        
                            </div>
                            <!-- Dropdown Menu (Appears Below Button) -->
                            <div id="courseDropdown" class="hidden absolute right-2 top-10 z-10 w-44 bg-white rounded-lg shadow-lg dark:bg-gray-700">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Export</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </div>           

                        {{-- Course Details --}}
                        <div class="p-2 text-center">
                            <h5 class="text-sm font-medium text-gray-900 dark:text-white">Robotic Course</h5>
                            <span class="text-xs text-gray-500 dark:text-gray-400">by Teacher X</span>

                            {{-- Progress Bar --}}
                            <div class="w-full bg-gray-300 rounded-full h-2 mt-1">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 50%;"></div>
                            </div>
                            <div class="mt-1 text-xs">7/14 Chapters</div>

                            {{-- Open Button --}}
                            <x-primary-button class="mt-2">
                                <a href="#" >Open</a>
                            </x-primary-button>
                        </div>
                    </div>
                </div>

                {{-- Available Courses Section --}}
                <h2 class="text-md font-semibold mt-4 mb-3">Available Courses</h2>
                <div class="flex justify-start gap-4">
                    @foreach (range(1, 7) as $course)
                        <div class="relative w-48 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition">
                            {{-- Course Image --}}
                            <div class="relative">
                                <img class="w-full h-32 object-cover rounded-t-lg" src="img/logo.png" alt="Course Image"/>
                            </div>
                
                            {{-- Course Details --}}
                            <div class="p-3 text-center">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white">Course {{ $course }}</h5>
                                <span class="text-xs text-gray-500 dark:text-gray-400">by Teacher X</span>
                                <p class="text-xs mt-1">Total: <span class="font-bold">16 Chapters</span></p>
                
                                {{-- Add & Preview Buttons --}}
                                <div class="flex mt-2 mb-2 justify-center space-x-2">
                                    <x-primary-button href="#">Add</x-primary-button>
                                    <x-secondary-button href="#">Preview</x-secondary-button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
