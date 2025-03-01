<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Module Content') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <!-- Content Form -->
            <form method="POST" action="{{ route('admin.courses.modules.contents.store', ['course' => $course->id, 'module' => $module->id]) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Content Type</label>
                    <select id="content_type" name="content_type" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Tutor</option>
                        <option value="Content">Isi</option>
                        <option value="Exercise">Latihan</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Content</label>
                    <div id="editor" class="w-full border border-gray-300 rounded-md shadow-sm"></div>
                    <input type="hidden" name="content" id="content-hidden">
                </div>

                <x-primary-button id="preview-btn">Preview</x-primary-button>
                <x-primary-button type="submit">Save</x-primary-button>
            </form>

            <!-- Preview Section -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold">Preview</h3>
                <div id="preview-area" class="p-4 bg-gray-100 rounded shadow-md"></div>
            </div>

            <!-- Existing Content -->
            <h3 class="mt-6 text-lg font-semibold">Existing Content</h3>
            <ul class="mt-2 space-y-2">
                @foreach ($module->contents as $content)
                    <li class="p-4 bg-gray-100 rounded shadow-md flex justify-between items-center content-item">
                        <span>{!! $content->content !!}</span>
                        <div>
                            <a href="{{ route('admin.modules.content.edit', [$module->id, $content->id]) }}" class="text-blue-600">Edit</a>
                            <button class="text-red-600 delete-btn" data-id="{{ $content->id }}">Delete</button>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    @vite(['resources/js/content-manager.js'])
</x-app-layout>
