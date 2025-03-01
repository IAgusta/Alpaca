<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Module for ') . $course->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('admin.courses.modules.index', $course->id) }}">
                    <x-secondary-button>
                        {{ __('Back to Modules') }}
                    </x-secondary-button>
                </a>

                <form action="{{ route('admin.courses.modules.store', $course->id) }}" method="POST" class="mt-4">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Module Title</label>
                        <input type="text" name="title" required class="w-full border rounded p-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea name="description" class="w-full border rounded p-2"></textarea>
                    </div>

                    <x-primary-button type="submit">
                        {{ __('Create Module') }}
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
