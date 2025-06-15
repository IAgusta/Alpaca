<x-app-layout>
    @section('title', 'Content | ' . $course->displayname . ' - ' .config('app.name') )
    <x-slot name="header">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <div class="flex items-center">
                        <a class="flex gap-2 justify-center items-center" data-modal-target="crud-modal-module-{{ $course->id }}" data-modal-toggle="crud-modal-module-{{ $course->id }}">
                            <img src="{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}" alt="thumbnail" class="w-8 h-8 rounded-sm object-cover">
                            <h2 class="cursor-pointer truncate font-semibold text-sm text-gray-800 dark:text-white leading-tight hover:text-blue-600">{{ Str::limit($course->display_name, 32, '...')  }}</h2>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <!-- Limited title for screens smaller than lg -->
                        <span class="block lg:hidden ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                            {{ Str::limit($module->title, 18, '...') }}
                        </span>

                        <!-- Full title for lg and larger screens -->
                        <span class="hidden lg:block ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                            {{ $module->title }}
                        </span>
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
        <div class="overflow-hidden sm:rounded-lg p-6">
            <a href="#" onclick="window.history.back();">
                <x-secondary-button class="mb-4">
                    {{ __('Kembali') }}
                </x-secondary-button>
            </a>
            
            <!-- Content Form -->
            @include('admin.courses.modules.contents.partials.input_form')

            <!-- Preview Section -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-center dark:text-white">Preview Isi Konten</h3>
                <ul class="mt-2 space-y-2 sortable-list" data-url="{{ route('admin.courses.modules.contents.reorder', ['course' => $course->id, 'module' => $module->id]) }}">
                    @foreach ($module->contents->sortBy('position') as $index => $content)
                        <li class="p-4 border bg-gray-100 dark:bg-gray-800 dark:border-gray-800 rounded shadow-md flex items-start content-item" data-id="{{ $content->id }}" data-position="{{ $index + 1 }}">
                            <!-- Reposition Buttons (Left Side) -->
                            <div class="flex flex-col items-center gap-2 mr-4">
                                <span class="cursor-grab drag-handle dark:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                                    </svg>
                                </span>
                            </div>
            
                            <!-- Content Section -->
                            <div class="flex-1" x-data="{ showContent: false }">
                                <!-- Title & Button in One Line -->
                                <div class="flex items-center justify-center relative">
                                    <div class="font-semibold text-center dark:text-gray-300">{{ $content->title }}</div>
                                    <button 
                                        @click="showContent = !showContent" 
                                        class="text-blue-500 hover:text-blue-600 p-1 rounded-full"
                                        aria-label="Toggle content visibility">
                                        <span class="material-symbols-outlined" x-show="!showContent">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#0000F5">
                                                <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/>
                                            </svg>
                                        </span>
                                        <span class="material-symbols-outlined" x-show="showContent" x-cloak>
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#0000F5">
                                                <path d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"/>
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                <!-- Content Toggle -->
                                <div x-show="showContent" x-cloak class="mt-2">
                                    @if ($content->content_type === "content")
                                        <div class="w-full">
                                            <div class="px-4 py-2">
                                                <div id="preview-container-{{ $content->id }}" class="block w-full px-0 text-sm text-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400">
                                                    {!! $content->content !!}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div>
                                            @php
                                                $exercise = json_decode($content->content, true);
                                            @endphp
                                            <div class="exercise-container max-w-lg mx-auto my-8 p-6 rounded-2xl shadow-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-slate-500 transition-all relative">
                                                <!-- Overlay (hidden by default) -->
                                                <div class="exercise-overlay absolute inset-0 bg-white/70 dark:bg-gray-900/70 z-10 hidden rounded-2xl flex items-center justify-center"></div>
                                                <!-- Floating result card (hidden by default) -->
                                                <div class="exercise-result-card absolute left-1/2 -translate-x-1/2 z-20 hidden min-w-[260px] px-6 py-4 rounded-xl shadow-lg flex flex-col items-center border-2 border-gray-200 bg-white dark:bg-gray-800">
                                                    <div class="result-icon mb-2"></div>
                                                    <div class="result-message font-semibold text-lg mb-1"></div>
                                                    <div class="result-explanation text-gray-700 dark:text-gray-200 text-base text-center"></div>
                                                </div>
                                                <form class="exercise-form space-y-5" data-explanation="{{ $exercise['explanation'] ?? '' }}">
                                                    <p class="font-semibold text-lg text-gray-800 mb-4">{!! $exercise['question'] ?? 'No question provided' !!}</p>
                                                    <div class="space-y-3">
                                                        @foreach($exercise['answers'] ?? [] as $index => $answer)
                                                            <label class="flex items-center gap-3 cursor-pointer px-3 py-2 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-500 transition group border border-transparent focus-within:border-blue-400">
                                                                <input 
                                                                    type="radio" 
                                                                    id="answer-{{ $content->id }}-{{ $index }}" 
                                                                    name="answer-{{ $content->id }}" 
                                                                    value="{{ $answer['correct'] ?? '' }}"
                                                                    class="accent-blue-500 h-5 w-5 transition-all group-hover:scale-110"
                                                                >
                                                                <span class="text-gray-700 dark:text-white text-base">{{ $answer['text'] ?? 'No answer text provided' }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    <button 
                                                        type="button" 
                                                        class="submit-answer w-full mt-4 px-5 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-bold shadow hover:from-blue-700 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                                                    >
                                                        Submit
                                                    </button>
                                                </form>
                                                <div class="explanation hidden mt-6 px-4 py-3 rounded-lg bg-blue-50 border-t border-blue-200 flex items-start gap-2">
                                                    <span class="icon text-blue-500 mt-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" /></svg>
                                                    </span>
                                                    <span class="explanation-text text-blue-800 font-medium"></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
            
                            <!-- Dropdown Button (Top-Right) -->
                            <div class="relative self-start">
                                <button data-dropdown-toggle="contentDropdown-{{ $content->id }}" class="bg-transparent p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-three-dots-vertical dark:text-white" viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a.5.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
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
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    @vite(['resources/js/content/editor.js','resources/js/content/content-manager.js', 'resources/js/content/exercise-form.js'])
</x-app-layout>