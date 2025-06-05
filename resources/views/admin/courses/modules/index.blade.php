<div class="bg-gray-100 dark:bg-gray-600 overflow-hidden shadow-sm rounded-lg p-6">
    <!-- Modal header -->
    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white"> {{ __('Kelas :') }} {{ $course->name }}</h3>
        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" 
            data-modal-hide="crud-modal-module-{{ $course->id }}"
            onclick="document.body.style.overflow = 'auto';">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
        </button>
    </div>

    <!-- Table -->
    <div class="relative my-2">
        <ul class="space-y-2 sortable-list" data-url="{{ route('admin.courses.modules.reorder', ['course' => $course->id]) }}">
            @forelse($modules->sortBy('position') as $module)
                <li class="bg-white dark:bg-gray-700 border dark:border-gray-800 rounded-lg shadow-sm p-4 module-item" data-id="{{ $module->id }}" data-position="{{ $module->position }}">
                    <div class="flex items-start gap-4">
                        <!-- Drag Handle -->
                        <div class="cursor-grab drag-handle mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                            </svg>
                        </div>

                        <!-- Module Title -->
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 dark:text-white">{{ $module->title }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-200">{{ $module->description ?? 'No description available' }}</p>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <!-- Desktop buttons (visible on lg and above) -->
                            <div class="hidden lg:flex justify-center gap-2">
                                <a href="{{ route('admin.courses.modules.contents.index', ['course' => $course->id, 'module' => $module->id]) }}">
                                    <x-primary-button>
                                        {{ __('Open') }}
                                    </x-primary-button>
                                </a>
                            
                                <a data-modal-target="crud-modal-module-edit-{{ $module->id }}" data-modal-toggle="crud-modal-module-edit-{{ $module->id }}">
                                    <x-primary-button>
                                        {{ __('Edit') }}
                                    </x-primary-button>
                                </a>
                                                
                                <x-danger-button
                                    x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-module-deletion-{{ $module->id }}')"
                                >
                                    {{ __('Delete') }}
                                </x-danger-button>
                            </div>
                        
                            <!-- Mobile dropdown (visible below lg) -->
                            <div class="lg:hidden" x-data="{ open: false }">
                                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-800 hover:bg-gray-100">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
    
                                <!-- Dropdown menu -->
                                <div x-show="open" 
                                     @click.away="open = false"
                                     class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <a data-modal-target="crud-modal-module-edit-{{ $module->id }}" 
                                           data-modal-toggle="crud-modal-module-edit-{{ $module->id }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                                            {{ __('Ubah') }}
                                        </a>
                        
                                        <a href="{{ route('admin.courses.modules.contents.index', ['course' => $course->id, 'module' => $module->id]) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            {{ __('Atur') }}
                                        </a>
                        
                                        <button @click="$dispatch('open-modal', 'confirm-module-deletion-{{ $module->id }}')"
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                            {{ __('Hapus') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Edit modal -->
                            <div id="crud-modal-module-edit-{{ $module->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                                <div class="relative p-4 w-full max-w-md max-h-full">
                                    @include('admin.courses.modules.edit', ['course' => $course, 'module' => $module])
                                </div>
                            </div>

                            <!-- Delete Module Modal -->
                            <x-modal name="confirm-module-deletion-{{ $module->id }}" focusable>
                                <form method="post" action="{{ route('admin.courses.modules.destroy', ['course' => $course->id, 'module' => $module->id]) }}" class="p-6">
                                    @csrf
                                    @method('delete')

                                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ __('Apakah anda yakin menghapus bagian dari kelas ini?') }}
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
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
                    </div>
                </li>
            @empty
                <li class="text-center py-4 text-gray-500">
                    {{ __('Tidak ada bagian yang telah dibuat. "Buat Bagian Baru" dengan mengisi isian dibawah!') }}
                </li>
            @endforelse
        </ul>
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

<!-- Add these scripts at the bottom of the file -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@vite(['resources/js/modules/module-manager.js'])