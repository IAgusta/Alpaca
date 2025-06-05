<div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
    <!-- Modal header -->
    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ __('Ubah Bagian : ') }} {{ $module->title }}
        </h3>
        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal-module-edit-{{ $module->id }}">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
        </button>
    </div>

    @if($errors->any())
        <x-input-error :messages="$errors->all()" class="mb-4" />
    @endif

    <div class="relative p-4 md:p-5">
        <form action="{{ route('admin.courses.modules.update', ['course' => $course->id, 'module' => $module->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Module Title -->
            <div>
                <x-input-label class="mb-3" for="title" :value="__('Nama Bagian')" />
                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $module->title)" required autofocus autocomplete="title" />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="mt-4">
                <textarea id="description" name="description" rows="5"
                class="mt-1 block w-full dark:text-white dark:bg-gray-600 dark:border-gray-800 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 resize-none placeholder-gray-400"
                placeholder="Description is an optional, you can just ignore it if doesn't want to make the description">{{ old('description', $module->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div class="mt-4">
                <x-primary-button>
                    {{ __('Ubah') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>