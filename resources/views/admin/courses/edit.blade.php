<div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
    <!-- Modal header -->
    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Info Kelas
        </h3>
        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal-update-{{ $course->id }}">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
        </button>
    </div>

    @if($errors->any())
        <x-input-error :messages="$errors->all()" class="mb-4" />
    @endif

    <div class="relative p-4 md:p-5">
        <form id="course-edit-form-{{ $course->id }}" action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Course Name -->
            <div>
                <x-input-label class="mb-3" for="main-name-{{ $course->id }}" :value="__('Nama Kelas')" />
                <x-text-input id="main-name-{{ $course->id }}" class="block mt-1 w-full" type="text" name="main_name" :value="old('main_name', explode(' [', preg_replace('/ \((test|manga)\)/', '', $course->name))[0])" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Alternative Title -->
            <div class="mt-4">
                <x-input-label for="alt-title-{{ $course->id }}" :value="__('Nama Alternative')" />
                <x-text-input id="alt-title-{{ $course->id }}" class="block mt-1 w-full" type="text" name="alt_title"
                    :value="old('alt_title', (preg_match('/\[(.*?)\]$/', $course->name, $m) ? $m[1] : ''))" autocomplete="off" />
            </div>

            <!-- Checkboxes for Testing and Manga -->
            <div class="mt-4 flex gap-4 dark:text-white items-center">
                <label class="inline-flex items-center">
                    <input type="checkbox" id="is-testing-{{ $course->id }}" class="form-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                        @if(strpos($course->name, '(test)') !== false) checked @endif />
                    <span class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Testing</span>
                </label>
                <span id="show-others-{{ $course->id }}" class="text-blue-600 cursor-pointer hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400 text-sm font-medium">
                    Other +
                </span>
                <div id="other-options-{{ $course->id }}" class="hidden flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="is-manga-{{ $course->id }}" class="form-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            @if(strpos($course->name, '(manga)') !== false) checked @endif />
                        <span class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Manga</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="is-novel-{{ $course->id }}" class="form-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            @if(strpos($course->name, '(novel)') !== false) checked @endif />
                        <span class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Novel</span>
                    </label>
                </div>
            </div>

            <!-- Hidden actual name input -->
            <input type="hidden" id="name-actual-{{ $course->id }}" name="name" value="{{ old('name', $course->name) }}" />

            <!-- Description -->
            <div class="mt-4">
                <x-input-label for="description-{{ $course->id }}" :value="__('Deskripsi')" />
                <textarea id="description-{{ $course->id }}" name="description" rows="5"
                class="mt-1 block w-full border-gray-300 dark:bg-gray-600 dark:text-white dark:border-gray-800 rounded-md shadow-sm focus:ring focus:ring-indigo-200 resize-none placeholder-gray-400"
                placeholder="Description is an optional, you can just ignore it if doesn't want to make the description">{{ old('description', $course->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            @if ($course->is_locked)
                <div class="mt-4">
                    <x-input-label :value="__('Password Kelas')" />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                        {{ $plainPassword ?? 'Password not available' }}
                    </p>
                </div>
            @endif
        

            <!-- Course Theme Selection -->
            <div class="my-4">
                <x-input-label for="theme-{{ $course->id }}" :value="__('Tema Kelas')" />

                <!-- Selected Themes + New Button -->
                <div id="selected-themes-{{ $course->id }}" class="flex flex-wrap items-center gap-2">
                    @if(old('theme', $course->theme))
                        @php
                            $themes = explode(',', old('theme', $course->theme));
                        @endphp
                        @foreach($themes as $theme)
                            @if(trim($theme))
                                <div class="theme-badge inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm mr-2">
                                    {{ trim($theme) }}
                                    <button type="button" onclick="removeTheme(this, '{{ $course->id }}')" class="ml-1 text-blue-600 hover:text-blue-800">×</button>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    <span id="new-theme-trigger-{{ $course->id }}" class="text-blue-600 cursor-pointer hover:text-blue-800" onclick="showThemeInput('{{ $course->id }}')">
                        Baru +
                    </span>
                </div>

                <!-- New Theme Input -->
                <div id="new-theme-container-{{ $course->id }}" class="hidden items-center gap-2 mt-2">
                    <input type="text" id="new-theme-input-{{ $course->id }}" 
                            class="px-2 py-1 border dark:bg-gray-600 dark:border-800 dark:text-white border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300 w-40" 
                            placeholder="Enter theme">
                    <x-secondary-button type="button" onclick="addTheme('{{ $course->id }}')">
                        {{ __('Tambah') }}
                    </x-secondary-button>
                </div>

                <!-- Hidden Input Field -->
                <input type="hidden" id="theme-input-{{ $course->id }}" name="theme" value="{{ old('theme', $course->theme) }}">

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
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input-{{ $course->id }}">Upload file</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                        id="file_input-{{ $course->id }}"
                        type="file" 
                        name="image">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">JPEG, PNG, GIF, BMP, SVG, TIFF, or WEBP (Max 2MB).</p>
                @error('image')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="mt-4">
                <x-primary-button>
                    {{ __('Perbarui') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>