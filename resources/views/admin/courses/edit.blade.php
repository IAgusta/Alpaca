<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Course') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($errors->any())
                    <x-input-error :messages="$errors->all()" class="mb-4" />
                @endif

                <a href="{{ route('admin.courses.index') }}">
                    <x-secondary-button class="mb-4">
                        {{ __('Back to Courses') }}
                    </x-secondary-button>
                </a>

                <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Course Name -->
                    <div>
                        <x-input-label class="mb-3" for="name" :value="__('Course Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $course->name)" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 rounded">{{ old('description', $course->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Current Course Image -->
                    @if ($course->image)
                        <div class="mt-4">
                            <x-input-label :value="__('Current Image')" />
                            <img src="{{ asset('storage/' . $course->image) }}" class="mt-2 w-48 rounded">
                        </div>
                    @endif

                    <!-- New Course Image -->
                    <div class="mt-4">
                        <x-input-label for="image" :value="__('Change Course Image (Optional)')" />
                        <input id="image" type="file" name="image" class="block mt-1 w-full border-gray-300 rounded">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <x-primary-button>
                            {{ __('Update Course') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
