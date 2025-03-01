<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Content to {{ $module->title }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <form action="{{ route('admin.courses.modules.contents.store', ['course' => $course, 'module' => $module]) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="content" class="block text-gray-700">Content:</label>
                    <input type="text" name="content" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label for="content_type" class="block text-gray-700">Type:</label>
                    <select name="content_type" class="w-full border rounded px-3 py-2">
                        <option value="text">Text</option>
                        <option value="image">Image</option>
                        <option value="video">Video</option>
                        <option value="code">Code</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="position" class="block text-gray-700">Position:</label>
                    <input type="number" name="position" class="w-full border rounded px-3 py-2">
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Content</button>
            </form>
        </div>
    </div>
</x-app-layout>
