<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                <x-input-success :messages="[session('success')]" />
                @endif

                <a href="{{ route('admin.courses.create') }}">
                    <x-primary-button type="button">
                        {{ __('Create New Course') }}
                    </x-primary-button>
                </a>

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
                                                {{ __('Manage Modules') }}
                                            </x-primary-button>
                                        </a>

                                        <!-- Delete Course Form -->
                                        <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button>
                                                {{ __('Delete') }}
                                            </x-danger-button>
                                        </form>
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
