<x-modal name="confirm-content-deletion-{{ $content->id }}" focusable>
    <form method="post" action="{{ route('admin.courses.modules.contents.destroy', ['course' => $course->id, 'module' => $module->id, 'moduleContent' => $content->id]) }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Apakah anda yakin menghapus bagian dari konten kelas ini?') }}
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