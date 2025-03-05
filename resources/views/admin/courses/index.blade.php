<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('admin.courses.create') }}">
                    <x-primary-button type="button">
                        {{ __('Create New Course') }}
                    </x-primary-button>
                </a>

                @if(session('success'))
                <x-input-success :messages="[session('success')]" />
                @endif

                <table class="w-full border-collapse border border-gray-200 mt-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">Image</th>
                            <th class="border p-2">Name</th>
                            <th class="border p-2">Author</th>
                            <th class="border p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                            <tr>
                                <td class="border p-2 text-center">
                                    @if($course->image)
                                        <img src="{{ asset('storage/'.$course->image) }}" alt="Course Image" class="h-16 w-16 rounded mx-auto">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td class="border p-2">{{ $course->name }}</td>
                                <td class="border p-2">{{ $course->authorUser->name ?? 'Unknown' }}</td>
                                <td class="border p-2 space-x-2">
                                    <div class="flex justify-center gap-6">
                                        <!-- Edit Course Button -->
                                        <a href="{{ route('admin.courses.edit', $course->id) }}">
                                            <x-primary-button>
                                                {{ __('Edit') }}
                                            </x-primary-button>
                                        </a>

                                        <!-- Manage Modules Button -->
                                        <a href="{{ route('admin.courses.modules.index', $course->id) }}">
                                            <x-primary-button>
                                                {{ __('Manage') }}
                                            </x-primary-button>
                                        </a>

                                        <!-- Delete Course Button -->
                                        <x-danger-button
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'confirm-course-deletion-{{ $course->id }}')"
                                        >{{ __('Delete') }}</x-danger-button>

                                        <!-- Delete Course Modal -->
                                        <x-modal name="confirm-course-deletion-{{ $course->id }}" focusable>
                                            <form method="post" action="{{ route('admin.courses.destroy', $course->id) }}" class="p-6">
                                                @csrf
                                                @method('delete')
                                        
                                                <h2 class="text-lg font-medium text-gray-900">
                                                    {{ __('Are you sure you want to delete this course?') }}
                                                </h2>
                                        
                                                <p class="mt-1 text-sm text-gray-600">
                                                    {{ __('Once the course is deleted, all of its data will be permanently removed. Please type the course name to confirm.') }}
                                                </p>
                                        
                                                <div class="mt-6">
                                                    <x-input-label for="course_name_{{ $course->id }}" value="{{ __('Course Name') }}" class="sr-only" />
                                        
                                                    <x-text-input
                                                        id="course_name_{{ $course->id }}"
                                                        name="course_name"
                                                        type="text"
                                                        class="mt-1 block w-3/4"
                                                        placeholder="{{ __('Enter the course name') }}"
                                                    />
                                        
                                                    <!-- Error message for course name validation -->
                                                    <x-input-error :messages="$errors->courseDeletion->get('course_name')" class="mt-2" />
                                                </div>
                                        
                                                <div class="mt-6 flex justify-end">
                                                    <!-- Cancel button to close the modal -->
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('Cancel') }}
                                                    </x-secondary-button>
                                        
                                                    <!-- Delete button to submit the form -->
                                                    <x-danger-button class="ms-3">
                                                        {{ __('Delete Course') }}
                                                    </x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>