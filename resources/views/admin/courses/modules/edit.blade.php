<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Module: ') . $module->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('admin.courses.modules.index', $course->id) }}">
                    <x-secondary-button>
                        {{ __('Back to Modules') }}
                    </x-secondary-button>
                </a>

                <form action="{{ route('admin.courses.modules.update', ['course' => $course->id, 'module' => $module->id]) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Module Title</label>
                        <input type="text" name="title" value="{{ $module->title }}" required class="w-full border rounded p-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea name="description" class="w-full border rounded p-2">{{ $module->description }}</textarea>
                    </div>

                    <x-primary-button type="submit">
                        {{ __('Update Module') }}
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
