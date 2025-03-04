<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Module Content') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <a href="{{ route('admin.courses.modules.index', ['course' => $course->id]) }}">
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
                            <div class="flex gap-2">
                                <a href="{{ route('admin.courses.modules.contents.edit', ['course' => $course->id, 'module' => $module->id, 'moduleContent' => $content->id]) }}" class="text-blue-600">Edit</a>
                                <form action="{{ route('admin.courses.modules.contents.destroy', ['course' => $course->id, 'module' => $module->id, 'moduleContent' => $content->id]) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 delete-btn">Delete</button>
                                </form>
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
    @vite(['resources/js/content-manager.js'])
</x-app-layout>