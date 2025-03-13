<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Course') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($errors->any())
                    <x-input-error :messages="$errors->all()" class="mb-4" />
                @endif

                <a href="{{ route('admin.courses.index') }}">
                    <x-secondary-button class="mb-4">
                        {{ __('Back to Courses') }}
                    </x-secondary-button>
                </a>

                <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Course Name -->
                    <div>
                        <x-input-label class="mb-3" for="name" :value="__('Course Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $course->name)" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 rounded">{{ old('description', $course->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Course Theme Selection -->
                    <div class="my-4">
                        <x-input-label for="theme" :value="__('Course Theme')" />

                        <!-- Selected Themes + New Button -->
                        <div id="selected-themes" class="flex flex-wrap items-center gap-2">
                            @if(old('theme', $course->theme))
                                @php
                                    $themes = explode(',', old('theme', $course->theme));
                                @endphp
                                @foreach($themes as $theme)
                                    @if(trim($theme))
                                        <div class="theme-badge inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm mr-2">
                                            {{ trim($theme) }}
                                            <button type="button" onclick="removeTheme(this)" class="ml-1 text-blue-600 hover:text-blue-800">×</button>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            <span id="new-theme-trigger" class="text-blue-600 cursor-pointer hover:text-blue-800" onclick="showThemeInput()">
                                New+
                            </span>
                        </div>

                        <!-- New Theme Input -->
                        <div id="new-theme-container" class="hidden items-center gap-2 mt-2">
                            <input type="text" id="new-theme-input" 
                                   class="px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300 w-40" 
                                   placeholder="Enter theme">
                            <x-secondary-button type="button" onclick="addTheme()">
                                {{ __('Add') }}
                            </x-secondary-button>
                        </div>

                        <!-- Hidden Input Field -->
                        <input type="hidden" id="theme-input" name="theme" value="{{ old('theme', $course->theme) }}">

                        <x-input-error :messages="$errors->get('theme')" class="mt-2" />
                    </div>

                    <!-- Current Course Image -->
                    @if ($course->image)
                        <div class="mt-4">
                            <x-input-label :value="__('Current Image')" />
                            <img src="{{ asset('storage/' . $course->image) }}" class="mt-2 w-48 rounded">
                        </div>
                    @endif

                    <!-- Courses Image -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                               id="file_input" 
                               type="file" 
                               name="image">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">SVG, PNG, JPG, or GIF (Max 2MB).</p>
                        @error('image')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <x-primary-button>
                            {{ __('Update Course') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @vite(['resources/js/course-theme.js'])
</x-app-layout>