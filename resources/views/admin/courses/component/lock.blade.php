<div class="relative p-4 w-full max-w-md max-h-full">
    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Kunci Kelas
            </h3>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal-lock-{{ $course->id }}">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
        <div class="relative p-4 md:p-5">
            <form action="{{ route('course.lockCourse', $course->id) }}" method="POST">
                @csrf
                <div>
                    <x-input-label for="lock_password" :value="__('Masukan Kode Password Kelasmu (optional)')" />
                    <x-text-input id="lock_password" class="block mt-1 w-full" type="text" name="lock_password" />
                    <x-input-error :messages="$errors->get('lock_password')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-primary-button>
                        {{ __('Kunci') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>