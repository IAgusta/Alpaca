<form method="POST" action="{{ route('admin.courses.modules.contents.store', ['course' => $course->id, 'module' => $module->id]) }}" enctype="multipart/form-data">
    @csrf
    <!-- title field -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Judul Konten</label>
        <input type="text" name="title" id="title" class="w-full border-gray-300 rounded-md shadow-sm" required>
    </div>

    <!-- Existing content_type dropdown -->
    <div class="mb-4">
        <label class="flex text-sm font-medium text-gray-700">Tipe Isi Konten :
            <p data-popover-target="popover" data-popover-placement="bottom">
                <svg class="w-3 h-3 text-gray-400 hover:text-gray-500" aria-hidden="true" fill="currentColor" 
                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Show information description</span>
            </p>
        </label>
        <select id="content_type" name="content_type" class="w-full border-gray-300 rounded-md shadow-sm">
            <option value="content">Isi</option>
            <option value="exercise">Latihan</option>
        </select>
    </div>

    <!-- Existing content-editor and exercise-form sections -->
    <div id="content-editor" class="mb-2">
        <label class="flex text-sm font-medium text-gray-700">Isi Konten Bagian :
        </label>
        <div id="editor" class="w-full border border-gray-300 shadow-sm"></div>
        <input type="hidden" name="content" id="content-hidden">
    </div>

    <div id="exercise-form" class="hidden">
        <label class="flex text-sm font-medium text-gray-700">Pertanyaan
        </label>
        <div id="question-editor" class="w-full border border-gray-300 shadow-sm mb-2"></div>
        <input type="hidden" name="question" id="question-hidden">

        <label class="block text-sm font-medium text-gray-700">Pilihan Jawaban</label>
        <div id="answers-container"></div>
        <button type="button" id="add-answer" class="bg-blue-500 text-white px-2 py-1 rounded mt-2">+ Tambah Jawaban</button>

        <label class="block text-sm font-medium text-gray-700 mt-4">Penjelasan (Jika Salah)</label>
        <textarea name="explanation" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
    </div>

    <!-- Popover Content -->
    <div data-popover id="popover" role="tooltip" 
    class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 
    bg-white border border-gray-200 rounded-lg shadow-md opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 
    dark:text-gray-400">
    <div class="p-3">
        <h3 class="font-semibold text-gray-900 dark:text-white">Information</h3>
        <p>
            Untuk posisi Gambar dan Video akan selalu di tengah, meskipun pada text editor gambar dan video tidak dapat diubah tata letaknya.
            Saat telah diinput dan dilihat pada preview maupun user, tidak akan berpengaruh karna posisi letak telah diatur ditengah. Dan untuk
            video ukuran yang diatur adalah HD atau 720x460px
        </p>
    </div>
    <div data-popper-arrow></div>
    </div>

    <x-primary-button type="submit">Save</x-primary-button>
</form>