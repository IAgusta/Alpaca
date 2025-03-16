<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Bagian</label>
                <input type="text" name="title" required class="w-full border rounded p-2">
            </div>

            {{-- Module Description --}}
            <div class="mb-4 md:mb-0 md:w-1/2">
                <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                <textarea name="description" class="w-full border rounded p-2"></textarea>
            </div>
        </div>

        <x-primary-button class="my-4" type="submit">
            {{ __('Buat') }}
        </x-primary-button>
    </form>
</div>