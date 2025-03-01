<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Content in {{ $module->title }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <form action="{{ route('admin.courses.modules.contents.update', ['course' => $course, 'module' => $module, 'moduleContent' => $moduleContent]) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label for="content" class="block text-gray-700">Content:</label>
                    <input type="text" name="content" value="{{ $moduleContent->content }}" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label for="content_type" class="block text-gray-700">Type:</label>
                    <select name="content_type" class="w-full border rounded px-3 py-2">
                        <option value="text" {{ $moduleContent->content_type == 'text' ? 'selected' : '' }}>Text</option>
                        <option value="image" {{ $moduleContent->content_type == 'image' ? 'selected' : '' }}>Image</option>
                        <option value="video" {{ $moduleContent->content_type == 'video' ? 'selected' : '' }}>Video</option>
                        <option value="code" {{ $moduleContent->content_type == 'code' ? 'selected' : '' }}>Code</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="position" class="block text-gray-700">Position:</label>
                    <input type="number" name="position" value="{{ $moduleContent->position }}" class="w-full border rounded px-3 py-2">
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Content</button>
            </form>
        </div>
    </div>
</x-app-layout>
