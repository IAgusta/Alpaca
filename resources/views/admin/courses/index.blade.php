<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course Management') }}
            <a class="ml-4" href="/course"><x-secondary-button>Preview</x-secondary-button></a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Success Message -->
                @if(session('success'))
                <x-input-success :messages="[session('success')]" class="mt-4" />
                @endif

                <!-- Course Cards -->
                <div class="flex flex-wrap gap-4 justify-start">
                    <!-- Add New Course Card -->
                    <a href="{{ route('admin.courses.create') }}" 
                    class="relative w-52 h-auto bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition flex flex-col items-center justify-center p-4">
                    
                    <div class="flex flex-col items-center justify-center h-40">
                        <svg class="w-16 h-16 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Add New Course</p>
                    </div>
                    </a>
                    @foreach($courses as $course)
                    <div class="relative w-52 h-auto bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition">
                            <!-- Course Image -->
                            <div class="relative">
                                <button data-dropdown-toggle="courseDropdown-{{ $course->id }}" class="absolute top-2 right-0 bg-transparent p-2 rounded-full hover:bg-gray-200">
                                    <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                                        <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z"/>
                                    </svg>
                                </button>

                                <img class="w-full h-35 object-cover rounded-t-lg" src="{{ $course->image ? asset('storage/'.$course->image) : asset('img/logo.png') }}" alt="Course Image"/>

                                <!-- Dropdown Menu -->
                                <div id="courseDropdown-{{ $course->id }}" class="hidden absolute right-2 top-10 z-10 w-44 bg-white rounded-lg shadow-lg dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                                        <li>
                                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Course Details -->
                            <div class="p-2 text-center">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white mx-auto text-center w-40">
                                    {{ Str::limit($course->name, 20, '...') }}
                                </h5>
                                <span class="text-xs text-gray-500 dark:text-gray-400">by {{ $course->authorUser->name ?? 'Unknown' }}</span>

                               <!-- Module Theme -->
                               @php
                               // Handle null/empty theme and format display
                               $themeString = !empty(trim($course->theme)) ? $course->theme : 'umum';
                               $themes = explode(',', $themeString);
                               $totalThemes = count($themes);
                               $displayThemes = array_slice($themes, 0, 3);
                               
                               $colors = [
                                   'bg-blue-100 text-blue-800',
                                   'bg-gray-100 text-gray-800',
                                   'bg-red-100 text-red-800',
                                   'bg-green-100 text-green-800',
                                   'bg-yellow-100 text-yellow-800'
                               ];
                               @endphp
                               
                               @if(count($themes) > 0)
                                   <div class="flex flex-wrap gap-2 mt-2 justify-center">
                                       @foreach($displayThemes as $theme)
                                           @php
                                               // Format theme name
                                               $formattedTheme = ucwords(trim($theme));
                                               // Special case for 'umum'
                                               if(strtolower($formattedTheme) === 'umum') {
                                                   $formattedTheme = 'Umum';
                                               }
                                           @endphp
                                           
                                           <span class="{{ $colors[$loop->index % count($colors)] }} text-xs font-medium px-2.5 py-0.5 rounded-sm space-x-2">
                                               {{ $formattedTheme }}
                                           </span>
                                       @endforeach
                                       
                                       @if($totalThemes > 3)
                                           <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                               +{{ $totalThemes - 3 }} more
                                           </span>
                                       @endif
                                   </div>
                               @endif

                                <!-- Module Count -->
                                <div class="mt-2 text-sm font-semibold">Modules: {{ $course->modules->count() }}</div>

                                <div class="flex mt-2 mb-2 justify-center space-x-2">
                                    <!-- Open Button -->
                                    <a href="{{ route('admin.courses.modules.index', $course->id) }}">
                                        <x-primary-button class="mt-2 px-4 py-2 text-sm">Open</x-primary-button>
                                    </a>

                                    <!-- Delete Course Button -->
                                    <x-danger-button class="mt-2 px-4 py-2 text-sm" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-course-deletion-{{ $course->id }}')">
                                        {{ __('Delete') }}
                                    </x-danger-button>

                                    <!-- Delete Course Modal -->
                                    <x-modal name="confirm-course-deletion-{{ $course->id }}" focusable>
                                        <form method="post" action="{{ route('admin.courses.destroy', $course->id) }}" class="p-6">
                                            @csrf
                                            @method('delete')

                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ __('Are you sure you want to delete this course?') }}
                                            </h2>

                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ __('Once the course is deleted, all of its data will be permanently removed. Please type the course name to confirm.') }}
                                            </p>
                                            <div class="mt-6">
                                                <x-input-label for="course_name_{{ $course->id }}" value="{{ __('Course Name') }}" class="sr-only" />

                                                <x-text-input
                                                    id="course_name_{{ $course->id }}"
                                                    name="course_name"
                                                    type="text"
                                                    class="mt-1 block w-3/4"
                                                    placeholder="{{ __('Enter the course name') }}"
                                                />

                                                <!-- Error message for course name validation -->
                                                <x-input-error :messages="$errors->courseDeletion->get('course_name')" class="mt-2" />
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <!-- Cancel button to close the modal -->
                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                    {{ __('Cancel') }}
                                                </x-secondary-button>

                                                <!-- Delete button to submit the form -->
                                                <x-danger-button class="ms-3">
                                                    {{ __('Delete Course') }}
                                                </x-danger-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
