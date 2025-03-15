<x-app-layout>
    <x-slot name="header">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight hover:text-blue-600">{{ __('Courses') }}</h2>
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

    <div class="py-6 max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <a href="{{ route('admin.courses.index')}}">
                <x-secondary-button class="mb-4">
                    {{ __('Back') }}
                </x-secondary-button>
            </a>
            
            <!-- Content Form -->
            <form method="POST" action="{{ route('admin.courses.modules.contents.store', ['course' => $course->id, 'module' => $module->id]) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Content Type</label>
                    <select id="content_type" name="content_type" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="content">Isi</option>
                        <option value="exercise">Latihan</option>
                    </select>
                </div>

                <!-- Isi (Content) Editor -->
                <div id="content-editor" class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Content</label>
                    <div id="editor" class="w-full border border-gray-300 rounded-md shadow-sm"></div>
                    <input type="hidden" name="content" id="content-hidden">
                </div>

                <!-- Latihan (Exercise) Form -->
                <div id="exercise-form" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Question</label>
                    <!-- Quill Editor for Question -->
                    <div id="question-editor" class="w-full border border-gray-300 rounded-md shadow-sm mb-2"></div>
                    <input type="hidden" name="question" id="question-hidden">

                    <label class="block text-sm font-medium text-gray-700">Answer Choices</label>
                    <div id="answers-container"></div>
                    <button type="button" id="add-answer" class="bg-blue-500 text-white px-2 py-1 rounded mt-2">+ Add Answer</button>

                    <label class="block text-sm font-medium text-gray-700 mt-4">Explanation (if wrong)</label>
                    <textarea name="explanation" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>

                <x-primary-button type="submit">Save</x-primary-button>
            </form>

            <!-- Preview Section -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold">Preview</h3>
                <!-- Exiting Content -->
                <ul class="mt-2 space-y-2 sortable-list" data-url="{{ route('admin.courses.modules.contents.reorder', ['course' => $course->id, 'module' => $module->id]) }}">
                    @foreach ($module->contents->sortBy('position') as $content)
                        <li class="p-4 bg-gray-100 rounded shadow-md flex justify-between items-center content-item" data-id="{{ $content->id }}">
                            <div class="flex items-center gap-2">
                                <span class="cursor-grab drag-handle">☰</span>
                                <!-- Content Preview -->
                                @if ($content->content_type === "content")
                                    <div class="ql-editor">{!! $content->content !!}</div>
                                @else
                                    @php
                                        $exercise = json_decode($content->content, true);
                                    @endphp
                                    <div>
                                        <strong>Question:</strong>
                                        <div class="ql-editor">{!! $exercise['question'] ?? 'No question provided' !!}</div>
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
                            <!-- Action Buttons -->
                            <div>
                                <a href="{{ route('admin.courses.modules.contents.edit', ['course' => $course->id, 'module' => $module->id, 'moduleContent' => $content->id]) }}">
                                    <x-secondary-button>
                                        {{ __('Edit') }}
                                    </x-secondary-button>
                                </a>

                                <!-- Delete Content Button -->
                                <x-danger-button
                                    x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-content-deletion-{{ $content->id }}')"
                                >
                                    {{ __('Delete') }}
                                </x-danger-button>

                                <x-modal name="confirm-content-deletion-{{ $content->id }}" focusable>
                                    <form method="post" action="{{ route('admin.courses.modules.contents.destroy', ['course' => $course->id, 'module' => $module->id, 'moduleContent' => $content->id]) }}" class="p-6">
                                        @csrf
                                        @method('delete')

                                        <h2 class="text-lg font-medium text-gray-900">
                                            {{ __('Are you sure you want to delete this content?') }}
                                        </h2>

                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ __('Once deleted, this content cannot be recovered.') }}
                                        </p>

                                        <div class="mt-6 flex justify-end">
                                            <!-- Cancel button to close the modal -->
                                            <x-secondary-button x-on:click="$dispatch('close')">
                                                {{ __('Cancel') }}
                                            </x-secondary-button>

                                            <!-- Delete button to submit the form -->
                                            <x-danger-button class="ms-3">
                                                {{ __('Delete Content') }}
                                            </x-danger-button>
                                        </div>
                                    </form>
                                </x-modal>
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
</x-app-layout>