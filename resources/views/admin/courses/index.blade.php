<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelas') }}
            <a class="ml-4" href="{{ route('user.course') }}">
                <x-secondary-button class="gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                    </svg> {{ __('Preview') }}
                </x-secondary-button>
            </a>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Course Cards -->
                <div class="flex flex-wrap gap-4 justify-start">
                    <!-- Add New Course Card -->
                    <div data-modal-target="crud-modal-create" data-modal-toggle="crud-modal-create"
                    class="cursor-pointer relative w-52 h-auto max-h-410px bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition flex flex-col items-center justify-center p-4">
                        <div class="flex flex-col items-center justify-center h-40">
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

                    @foreach($courses as $course)
                    <div class="relative w-52 h-auto max-h-410px bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition">
                            <!-- Course Image -->
                            <div class="relative">
                                <button data-dropdown-toggle="courseDropdown-{{ $course->id }}" class="absolute top-2 right-0 bg-transparent p-2 rounded-full hover:bg-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                    </svg>
                                </button>

                                <img class="w-full h-35 object-cover rounded-t-lg" src="{{ $course->image ? asset('storage/'.$course->image) : asset('img/logo.png') }}" alt="Course Image"/>

                                <!-- Dropdown Menu -->
                                <div id="courseDropdown-{{ $course->id }}" class="hidden absolute right-2 top-10 z-10 w-44 bg-white rounded-lg shadow-lg dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                                        <li>
                                            <div data-modal-target="crud-modal-update-{{ $course->id }}" data-modal-toggle="crud-modal-update-{{ $course->id }}" class="cursor-pointer block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Info</div>
                                        </li>
                                        @if($course->is_locked)
                                        <li>
                                            <div data-modal-target="crud-modal-unlock-{{ $course->id }}" data-modal-toggle="crud-modal-unlock-{{ $course->id }}" class="cursor-pointer block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Buka Kunci</div>
                                        </li>
                                        @else
                                        <li>
                                            <div data-modal-target="crud-modal-lock-{{ $course->id }}" data-modal-toggle="crud-modal-lock-{{ $course->id }}" class="cursor-pointer block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Kunci</div>
                                        </li>
                                        @endif
                                    </ul>
                                </div>

                                <!-- Edit modal -->
                                <div id="crud-modal-update-{{ $course->id }}" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative p-4 w-full max-w-md max-h-full">
                                        @include('admin.courses.edit', ['course' => $course])
                                    </div>
                                </div>

                                <!-- Lock modal -->
                                <div id="crud-modal-lock-{{ $course->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    @include('admin.courses.component.lock')
                                </div>

                                <!-- Unlock modal -->
                                <div id="crud-modal-unlock-{{ $course->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    @include('admin.courses.component.unlock')
                                </div>
                            </div>

                            <!-- Course Details -->
                            <div class="p-2 text-center">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white mx-auto text-center w-40">
                                    {{ ucwords(strtolower(Str::limit($course->name, 20, '...'))) }}
                                </h5>
                                <span class="text-xs text-gray-500 dark:text-gray-400">by {{ Str::limit($course->authorUser->name, 32, '...') ?? 'Unknown' }}</span>

                               <!-- Module Theme -->
                               @php
                               // Handle null/empty theme and format display
                               $themeString = !empty(trim($course->theme)) ? $course->theme : 'umum';
                               $themes = explode(',', $themeString);
                               $totalThemes = count($themes);
                               $displayThemes = array_slice($themes, 0, 2);
                               
                               $colors = [
                                   'bg-blue-100 text-blue-800',
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
                                       
                                       @if($totalThemes > 2)
                                           <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                               +{{ $totalThemes - 2 }} more
                                           </span>
                                       @endif
                                   </div>
                               @endif

                                <!-- Module Count -->
                                <div class="mt-2 text-sm font-semibold">Bagian : {{ $course->modules->count() }}</div>

                                <div class="flex mt-2 mb-2 justify-center space-x-2">
                                    <!-- Open Button -->
                                    <div data-modal-target="crud-modal-module-{{ $course->id }}" data-modal-toggle="crud-modal-module-{{ $course->id }}">
                                        <x-primary-button class="mt-2 px-4 py-2 text-sm">Lihat</x-primary-button>
                                    </div>

                                    <!-- Main modal for modules -->
                                    <div id="crud-modal-module-{{ $course->id }}" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-7xl max-h-full md:max-w-6xl">
                                            @include('admin.courses.modules.index', ['course' => $course, 'modules' => $course->modules])
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Course Button -->
                                    <x-danger-button class="mt-2 px-4 py-2 text-sm" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-course-deletion-{{ $course->id }}')">
                                        {{ __('Hapus') }}
                                    </x-danger-button>

                                    <!-- Delete Course Modal -->
                                    <x-modal name="confirm-course-deletion-{{ $course->id }}" focusable>
                                        <form method="post" action="{{ route('admin.courses.destroy', $course->id) }}" class="p-6">
                                            @csrf
                                            @method('delete')

                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ __('Apakah anda yakin menghapus kursus ini?') }}
                                            </h2>

                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ __('Ketika anda menghapus kursus ini, semua data yang tersimpan akan dihapus. Tolong konfirmasi dengan memasukkan nama kursus.') }}
                                            </p>
                                            <div class="mt-6">
                                                <x-input-label for="course_name_{{ $course->id }}" value="{{ __('Course Name') }}" class="sr-only" />

                                                <x-text-input
                                                    id="course_name_{{ $course->id }}"
                                                    name="course_name"
                                                    type="text"
                                                    class="mt-1 block w-3/4"
                                                    placeholder="{{ __('Masukan Nama Kursus yang akan dihapus') }}"
                                                />

                                                <!-- Error message for course name validation -->
                                                <x-input-error :messages="$errors->courseDeletion->get('course_name')" class="mt-2" />
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <!-- Cancel button to close the modal -->
                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                    {{ __('Batal') }}
                                                </x-secondary-button>

                                                <!-- Delete button to submit the form -->
                                                <x-danger-button class="ms-3">
                                                    {{ __('Hapus') }}
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
    @vite(['resources/js/course-theme.js'])
</x-app-layout>
