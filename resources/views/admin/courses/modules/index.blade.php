<div class="bg-slate-100 overflow-hidden shadow-sm sm:rounded-lg p-6">
    <!-- Modal header -->
    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white"> {{ __('Kelas :') }} {{ $course->name }}</h3>
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
                    <th scope="col" class="px-6 py-3 text-center">Bagian</th>
                    <th scope="col" class="px-6 py-3 text-center">Aktivitas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($modules as $module)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center relative">
                            {{ $module->title }}
                        
                            <!-- Tooltip Button with Info Icon -->
                            <button data-tooltip-target="tooltip-right-{{ $module->id }}" data-tooltip-placement="right" type="button" class="ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle text-gray-400 hover:text-gray-500" viewBox="0 0 16 16"> 
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                                </svg>
                            </button>
                        </td>
                        
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <!-- Edit Module Button -->
                                <a data-modal-target="crud-modal-module-edit-{{ $module->id }}" data-modal-toggle="crud-modal-module-edit-{{ $module->id }}">
                                    <x-primary-button>
                                        {{ __('Ubah') }}
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
                                        {{ __('Atur') }}
                                    </x-primary-button>
                                </a>

                                <!-- Delete Module Button -->
                                <x-danger-button
                                    x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-module-deletion-{{ $module->id }}')"
                                >
                                    {{ __('Hapus') }}
                                </x-danger-button>

                                <!-- Delete Module Modal -->
                                <x-modal name="confirm-module-deletion-{{ $module->id }}" focusable>
                                    <form method="post" action="{{ route('admin.courses.modules.destroy', ['course' => $course->id, 'module' => $module->id]) }}" class="p-6">
                                        @csrf
                                        @method('delete')

                                        <h2 class="text-lg font-medium text-gray-900">
                                            {{ __('Apakah anda yakin menghapus bagian dari kelas ini?') }}
                                        </h2>

                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ __('Ketika dihapus, bagian dan isinya akan dihapus secara permanen.') }}
                                        </p>

                                        <div class="mt-6 flex justify-end">
                                            <!-- Cancel button to close the modal -->
                                            <x-secondary-button x-on:click="$dispatch('close')">
                                                {{ __('Batal') }}
                                            </x-secondary-button>

                                            <!-- Delete button to submit the form -->
                                            <x-danger-button class="ms-3">
                                                {{ __('Hapus') }}
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
                            {{ __('Tidak ada bagian yang telah dibuat. "Buat Bagian Baru" dengan mengisi isian dibawah!') }}
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

<!-- Tooltip Content with Text Wrapping -->
@foreach($modules as $module)
    <div id="tooltip-right-{{ $module->id }}" role="tooltip" 
        class="absolute z-10 invisible px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700 w-48 whitespace-normal break-words">
        <p class="leading-tight">
            {{ $module->description ?? 'Bagian ini tidak memiliki deskripsi apapun' }}
        </p>
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
@endforeach