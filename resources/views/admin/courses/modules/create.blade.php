<div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Membuat Bagian Kelas
        </h3>
    </div>
    <form action="{{ route('admin.courses.modules.store', $course->id) }}" method="POST" class="mt-4">
        @csrf

        <div class="flex flex-col md:flex-row md:space-x-4">
            {{-- Module Title --}}
            <div class="mb-4 md:mb-0 md:w-1/2">
                <x-input-label for="title" :value="__('Nama Bagian')" />
                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autocomplete="title" />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            {{-- Module Description --}}
            <div class="mb-4 md:mb-0 md:w-1/2">
                <x-input-label for="description" :value="__('Deskripsi')" />
                <textarea id="description" name="description" rows="2"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 resize-none placeholder-gray-400"
                placeholder="Description is an optional, you can just ignore it if doesn't want to make the description"></textarea>
            </div>
        </div>

        <x-primary-button class="my-4" type="submit">
            {{ __('Buat') }}
        </x-primary-button>
    </form>
</div>