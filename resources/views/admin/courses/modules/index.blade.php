<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <!-- Modal header -->
    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Course Module : {{ $course->name }}
        </h3>
        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal-module-{{ $course->id }}">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
        </button>
    </div>

    <!-- Table -->
    <div class="relative my-2 overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">Title</th>
                    <th scope="col" class="px-6 py-3 text-center">Description</th>
                    <th scope="col" class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($modules as $module)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                            {{ $module->title }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                            {{ $module->description }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <!-- Edit Module Button -->
                                <a data-modal-target="crud-modal-module-edit-{{ $module->id }}" data-modal-toggle="crud-modal-module-edit-{{ $module->id }}">
                                    <x-primary-button>
                                        {{ __('Edit') }}
                                    </x-primary-button>
                                </a>

                                <!-- Main modal -->
                                <div id="crud-modal-module-edit-{{ $module->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                                    <div class="relative p-4 w-full max-w-md max-h-full">
                                        @include('admin.courses.modules.edit', ['course' => $course, 'module' => $module])
                                    </div>
                                </div>

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
                            {{ __('No modules found. "Create New Module Chapter" on bellow table to add one!') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="relative my-4">
        @include('admin.courses.modules.create')
    </div>
</div>