<x-app-layout>
    <x-slot name="header">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight hover:text-blue-600">{{ __('Kelas') }}</h2>
                    </a>
                </li>
                <li>
                  <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a data-modal-target="crud-modal-module-{{ $course->id }}" data-modal-toggle="crud-modal-module-{{ $course->id }}" class="ms-1 text-sm font-medium text-gray-700 md:ms-2 dark:text-gray-400 dark:hover:text-white"><h3 class="font-semibold text-xl text-gray-800 leading-tight hover:text-blue-600 cursor-pointer">{{ $course->name }}</h3></a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                      <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                      </svg>
                      <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ $module->title }}</span>
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
            <form method="POST" action="{{ route('admin.courses.modules.contents.store', ['course' => $course->id, 'module' => $module->id]) }}" enctype="multipart/form-data">
                @csrf
                <!-- title field -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Judul Konten</label>
                    <input type="text" name="title" id="title" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
            
                <!-- Existing content_type dropdown -->
                <div class="mb-4">
                    <label class="flex text-sm font-medium text-gray-700">Tipe Isi Konten :
                        <p data-popover-target="popover" data-popover-placement="bottom">
                            <svg class="w-3 h-3 text-gray-400 hover:text-gray-500" aria-hidden="true" fill="currentColor" 
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Show information description</span>
                        </p>
                    </label>
                    <select id="content_type" name="content_type" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="content">Isi</option>
                        <option value="exercise">Latihan</option>
                    </select>
                </div>
            
                <!-- Existing content-editor and exercise-form sections -->
                <div id="content-editor" class="mb-2">
                    <label class="flex text-sm font-medium text-gray-700">Isi Konten Bagian :
                    </label>
                    <div id="editor" class="w-full border border-gray-300 shadow-sm"></div>
                    <input type="hidden" name="content" id="content-hidden">
                </div>
            
                <div id="exercise-form" class="hidden">
                    <label class="flex text-sm font-medium text-gray-700">Pertanyaan
                    </label>
                    <div id="question-editor" class="w-full border border-gray-300 shadow-sm mb-2"></div>
                    <input type="hidden" name="question" id="question-hidden">
            
                    <label class="block text-sm font-medium text-gray-700">Pilihan Jawaban</label>
                    <div id="answers-container"></div>
                    <button type="button" id="add-answer" class="bg-blue-500 text-white px-2 py-1 rounded mt-2">+ Tambah Jawaban</button>
            
                    <label class="block text-sm font-medium text-gray-700 mt-4">Penjelasan (Jika Salah)</label>
                    <textarea name="explanation" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
            
                <!-- Popover Content -->
                <div data-popover id="popover" role="tooltip" 
                class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 
                bg-white border border-gray-200 rounded-lg shadow-md opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 
                dark:text-gray-400">
                <div class="p-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Information</h3>
                    <p>
                        Untuk posisi Gambar dan Video akan selalu di tengah, meskipun pada text editor gambar dan video tidak dapat diubah tata letaknya.
                        Saat telah diinput dan dilihat pada preview maupun user, tidak akan berpengaruh karna posisi letak telah diatur ditengah. Dan untuk
                        video ukuran yang diatur adalah HD atau 720x460px
                    </p>
                </div>
                <div data-popper-arrow></div>
                </div>
            
                <x-primary-button type="submit">Save</x-primary-button>
            </form>

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
                            <div class="flex-1">
                                <!-- Title -->
                                <div class="font-semibold mb-2 text-center">{{ $content->title }}</div>
            
                                <!-- Content Preview -->
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
    @vite(['resources/js/content-manager.js'])
    @vite(['resources/js/content.js'])
</x-app-layout>