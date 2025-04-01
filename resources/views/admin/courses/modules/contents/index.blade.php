<x-app-layout>
    <x-slot name="header">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight hover:text-blue-600">{{ __('Kelas') }}</h2>
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a data-modal-target="crud-modal-module-{{ $course->id }}" data-modal-toggle="crud-modal-module-{{ $course->id }}">
                            <h2 class="truncate cursor-pointer font-semibold text-xl text-gray-800 leading-tight hover:text-blue-600">{{ Str::limit($course->name, 18, '...')  }}</h2>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ Str::limit($module->title, 18, '...')  }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <!-- Main modal for modules -->
        <div id="crud-modal-module-{{ $course->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-7xl max-h-full md:max-w-6xl">
                @include('admin.courses.modules.index', ['course' => $course, 'modules' => $course->modules->where('id', '!=', $module->id)])
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden sm:rounded-lg p-6">
            <a href="#" onclick="window.history.back();">
                <x-secondary-button class="mb-4">
                    {{ __('Kembali') }}
                </x-secondary-button>
            </a>
            
            <!-- Content Form -->
            @include('admin.courses.modules.contents.partials.input_form')

            <!-- Preview Section -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-center">Preview Isi Konten</h3>
                <ul class="mt-2 space-y-2 sortable-list" data-url="{{ route('admin.courses.modules.contents.reorder', ['course' => $course->id, 'module' => $module->id]) }}">
                    @foreach ($module->contents->sortBy('position') as $index => $content)
                        <li class="p-4 bg-gray-100 rounded shadow-md flex items-start content-item" data-id="{{ $content->id }}" data-position="{{ $index + 1 }}">
                            <!-- Reposition Buttons (Left Side) -->
                            <div class="flex flex-col items-center gap-2 mr-4">
                                <button id="button-up" class="{{ $index === 0 ? 'hidden' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-compact-up" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M7.776 5.553a.5.5 0 0 1 .448 0l6 3a.5.5 0 1 1-.448.894L8 6.56 2.224 9.447a.5.5 0 1 1-.448-.894z"/>
                                    </svg>
                                </button>
                                <span class="cursor-grab drag-handle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                                    </svg>
                                </span>
                                <button id="button-down" class="{{ $index === $module->contents->count() - 1 ? 'hidden' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-compact-down" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1.553 6.776a.5.5 0 0 1 .67-.223L8 9.44l5.776-2.888a.5.5 0 1 1 .448.894l-6 3a.5.5 0 0 1-.448 0l-6-3a.5.5 0 0 1-.223-.67"/>
                                    </svg>
                                </button>
                            </div>
            
                            <!-- Content Section -->
                            <div class="flex-1" x-data="{ showContent: false }">
                                <!-- Title & Button in One Line -->
                                <div class="flex items-center justify-center relative">
                                    <div class="font-semibold text-center">{{ $content->title }}</div>
                                    <button 
                                        @click="showContent = !showContent" 
                                        class="text-blue-500 hover:bg-blue-50 p-1 rounded-full"
                                        aria-label="Toggle content visibility">
                                        <span class="material-symbols-outlined" x-show="!showContent">
                                            visibility
                                        </span>
                                        <span class="material-symbols-outlined" x-show="showContent" x-cloak>
                                            visibility_off
                                        </span>
                                    </button>
                                </div>

                                <!-- Content Toggle -->
                                <div x-show="showContent" x-cloak class="mt-2">
                                    @if ($content->content_type === "content")
                                        <div class="ql-editor p-0 border-0 shadow-none">{!! $content->content !!}</div>
                                    @else
                                        @php
                                            $exercise = json_decode($content->content, true);
                                        @endphp
                                        <div>
                                            <strong>Question:</strong>
                                            <div class="ql-editor p-0 border-0 shadow-none">{!! $exercise['question'] ?? 'No question provided' !!}</div>
                                            <ul>
                                                @foreach ($exercise['answers'] ?? [] as $answer)
                                                    <li>
                                                        {{ $answer['text'] ?? 'No answer text provided' }}
                                                        @if (isset($answer['correct']) && $answer['correct'])
                                                            <span class="text-green-600 font-bold">(✔ Correct)</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="text-sm text-gray-600">
                                                <strong>Explanation:</strong> {{ $exercise['explanation'] ?? 'No explanation provided' }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
            
                            <!-- Dropdown Button (Top-Right) -->
                            <div class="relative self-start">
                                <button data-dropdown-toggle="contentDropdown-{{ $content->id }}" class="bg-transparent p-2 rounded-full hover:bg-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                    </svg>
                                </button>
                                <div id="contentDropdown-{{ $content->id }}" class="hidden absolute right-2 top-10 z-10 w-44 bg-white rounded-lg shadow-lg dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                                        <li>
                                            <a href="{{ route('admin.courses.modules.contents.edit', ['course' => $course->id, 'module' => $module->id, 'moduleContent' => $content->id]) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                        </li>
                                        <li>
                                            <a href="#" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-content-deletion-{{ $content->id }}')" class="block w-full px-4 py-2 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Delete</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Delete Module Modal -->
                            @include('admin.courses.modules.contents.partials.delete_modal')
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
    @vite(['resources/js/content-manager.js', 'resources/js/content.js'])
</x-app-layout>