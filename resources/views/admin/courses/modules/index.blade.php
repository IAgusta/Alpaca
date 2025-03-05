<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Module Management for ') . $course->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between mb-4">
                    <a href="{{ route('admin.courses.index') }}">
                        <x-secondary-button>
                            {{ __('Back') }}
                        </x-secondary-button>
                    </a>

                    <a href="{{ route('admin.courses.modules.create', $course->id) }}">
                        <x-primary-button>
                            {{ __('Create New Module') }}
                        </x-primary-button>
                    </a>
                </div>

                @if(session('success'))
                <x-input-success :messages="[session('success')]" />
                @endif

                <table class="w-full border-collapse border border-gray-200 mt-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">Module Title</th>
                            <th class="border p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modules as $module)
                            <tr>
                                <td class="border p-2">{{ $module->title }}</td>
                                <td class="border p-2">
                                    <div class="flex justify-center gap-6">
                                        <a href="{{ route('admin.courses.modules.edit', ['course' => $course->id, 'module' => $module->id]) }}">
                                            <x-primary-button>
                                                {{ __('Edit') }}
                                            </x-primary-button>
                                        </a>

                                        <a href="{{ route('admin.courses.modules.contents.index', ['course' => $course->id, 'module' => $module->id]) }}">
                                            <x-primary-button>
                                                {{ __('Manage') }}
                                            </x-primary-button>
                                        </a>
                                    
                                        <!-- Delete Module Button -->
                                        <x-danger-button
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'confirm-module-deletion-{{ $module->id }}')"
                                        >
                                            {{ __('Delete') }}
                                        </x-danger-button>

                                        <x-modal name="confirm-module-deletion-{{ $module->id }}" focusable>
                                            <form method="post" action="{{ route('admin.courses.modules.destroy', ['course' => $course->id, 'module' => $module->id]) }}" class="p-6">
                                                @csrf
                                                @method('delete')

                                                <h2 class="text-lg font-medium text-gray-900">
                                                    {{ __('Are you sure you want to delete this module?') }}
                                                </h2>

                                                <p class="mt-1 text-sm text-gray-600">
                                                    {{ __('Once deleted, this module and its contents will be permanently removed.') }}
                                                </p>

                                                <div class="mt-6 flex justify-end">
                                                    <!-- Cancel button to close the modal -->
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('Cancel') }}
                                                    </x-secondary-button>

                                                    <!-- Delete button to submit the form -->
                                                    <x-danger-button class="ms-3">
                                                        {{ __('Delete Module') }}
                                                    </x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </td>
                                </div>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="border p-2 text-center">
                                    {{ __('No modules found. Click "Create New Module" to add one!') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
