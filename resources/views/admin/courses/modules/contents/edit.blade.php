<x-app-layout>
    @section('title', 'Edit Content | ' . $module->title . ' | ' . $course->name)
    <x-slot name="header">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.courses.modules.contents.index', ['course' => $course, 'module' => $module]) }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight hover:text-blue-600">{{ $module->title }}</h2>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ __('Edit') }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-7 max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden sm:rounded-lg p-6">
            <!-- Back Button -->
            <a href="#" onclick="window.history.back();" class="mb-4 inline-block">
                <x-secondary-button>{{ __('Back') }}</x-secondary-button>
            </a>

            <!-- Edit Form -->
            <form method="POST" action="{{ route('admin.courses.modules.contents.update', ['course' => $course, 'module' => $module, 'moduleContent' => $content->id]) }}">
                @csrf
                @method('PATCH')

                <!-- Title field -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Judul Konten</label>
                    <input type="text" name="title" id="title" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ $content->title }}" required>
                </div>

                <!-- Content Type (Display Only) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Content Type</label>
                    <input type="text" class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100" value="{{ ucfirst($content->content_type) }}" readonly>
                </div>

                <!-- Single WYSIWYG Editor Container -->
                <div class="mb-2">
                    <label class="flex text-sm font-medium text-gray-700" id="editor-label">
                        {{ $content->content_type === 'exercise' ? 'Pertanyaan:' : 'Isi Konten:' }}
                    </label>
                    <div class="w-full border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                        @include('admin.courses.modules.contents.partials.text-editor')
                        <div class="px-4 py-2 bg-white rounded-b-lg dark:bg-gray-800">
                            <div id="wysiwyg-container" class="block w-full px-0 text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400"></div>
                        </div>
                    </div>
                </div>

                <!-- Hidden input for content -->
                <input type="hidden" name="content" id="content-hidden" value="{{ $content->content_type === 'exercise' ? (json_decode($content->content, true)['question'] ?? '') : $content->content }}">

                <!-- Exercise Additional Fields -->
                @if ($content->content_type === 'exercise')
                    @php
                        $exercise = json_decode($content->content, true);
                    @endphp
                    
                    <div id="exercise-fields" class="mt-4">
                        <!-- Answer Choices -->
                        <div id="answers-container" class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Jawaban</label>
                            @foreach ($exercise['answers'] ?? [] as $index => $answer)
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="text" name="answers[{{ $index }}][text]" 
                                        class="w-full border-gray-300 rounded-md shadow-sm" 
                                        value="{{ $answer['text'] ?? '' }}">
                                    <input type="checkbox" name="answers[{{ $index }}][correct]" 
                                        value="1" {{ isset($answer['correct']) && $answer['correct'] ? 'checked' : '' }}>
                                    <span class="text-sm">Correct</span>
                                    <button type="button" class="text-red-600 remove-answer">×</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-answer" class="bg-blue-500 text-white px-2 py-1 rounded mb-4">+ Tambah Jawaban</button>

                        <!-- Explanation -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Penjelasan (Jika Salah)</label>
                            <textarea name="explanation" class="w-full border-gray-300 rounded-md shadow-sm">{{ $exercise['explanation'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endif

                <!-- Save Button -->
                <x-primary-button type="submit">{{ __('Save') }}</x-primary-button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/content/editor.js'])
</x-app-layout>