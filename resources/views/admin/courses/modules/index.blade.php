<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Module Management for ') . $course->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Back and Create New Module Buttons -->
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

                <!-- Success Message -->
                @if(session('success'))
                <x-input-success :messages="[session('success')]" />
                @endif

                <!-- Table -->
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center">Module Title</th>
                                <th scope="col" class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($modules as $module)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                                        {{ $module->title }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <!-- Edit Module Button -->
                                            <a href="{{ route('admin.courses.modules.edit', ['course' => $course->id, 'module' => $module->id]) }}">
                                                <x-primary-button>
                                                    {{ __('Edit') }}
                                                </x-primary-button>
                                            </a>

                                            <!-- Manage Contents Button -->
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

                                            <!-- Delete Module Modal -->
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
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center">
                                        {{ __('No modules found. Click "Create New Module" to add one!') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>