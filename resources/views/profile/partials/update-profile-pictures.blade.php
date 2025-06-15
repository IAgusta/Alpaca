<section class="p-3 lg:p-8" x-data x-init="() => { 
    $nextTick(() => {
        if (window.initCropperModal) window.initCropperModal();
    });
}">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ __('Profile Pictures') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile avatar and banner. Max Size for Images are 5MB, if your file larger than 5MB Please compress it before uploading.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update.images') }}" enctype="multipart/form-data" class="mt-6 space-y-6" id="profile-images-form">
        @csrf

        <div class="flex flex-col gap-3">
            <!-- Banner Section -->
            <div class="relative bg-white dark:bg-gray-800 rounded-lg">
                <h3 class="font-medium mb-3 dark:text-white">Banner Previews</h3>
                <x-input-error class="mt-2" :messages="$errors->get('banner_image')" />
                <div class="relative w-full h-48 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                    <img id="banner-preview" 
                        src="{{ $user->details->image ? asset('storage/' . (json_decode($user->details->image, true)['banner'] ?? 'profiles/patterns.png')) : asset('storage/profiles/patterns.png') }}"
                        class="w-full h-full object-cover" 
                        alt="Banner Image">
                    
                    <label for="banner_image" class="absolute top-4 right-4 bg-white dark:bg-gray-700 p-2 rounded-lg shadow-md cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-5 h-5 text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-gray-100" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                        <span class="text-sm dark:text-white">Change Banner</span>
                    </label>
                    <input type="file" id="banner_image" name="banner_image" class="hidden" accept="image/*">
                </div>
            </div>

            <!-- Avatar Preview Section -->
            <div class="relative bg-white dark:bg-gray-800 rounded-lg">
                <h3 class="font-medium mb-3 dark:text-white">Avatar Previews</h3>
                <label for="profile_image" class="absolute top-4 right-4 bg-white dark:bg-gray-700 p-2 rounded-lg shadow-md cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-5 h-5 text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-gray-100" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                    <span class="text-sm dark:text-white">Change Avatar</span>
                </label>
                <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />
                <input type="file" id="profile_image" name="profile_image" class="hidden" accept="image/*">
                
                <div class="flex flex-wrap gap-6 items-end pt-2">
                    <div class="text-center">
                        <img id="profile-preview-large" 
                             src="{{ $user->details->image ? asset('storage/' . json_decode($user->details->image, true)['profile'] ?? 'profiles/default-profile.png') : asset('storage/profiles/default-profile.png') }}" 
                             class="w-32 h-32 rounded-full border-2 border-gray-500 dark:border-gray-800 object-cover shadow-md"
                             alt="Large Preview">
                        <p class="text-sm mb-2 dark:text-gray-200">184px</p>
                    </div>
                    <div class="text-center">
                        <img id="profile-preview-medium" 
                            src="{{ $user->details->image ? asset('storage/' . json_decode($user->details->image, true)['profile'] ?? 'profiles/default-profile.png') : asset('storage/profiles/default-profile.png') }}"
                             class="w-16 h-16 rounded-full border-1 border-gray-400 dark:border-gray-800 object-cover"
                             alt="Medium Preview">
                        <p class="text-sm mb-2 dark:text-gray-200">64px</p>
                    </div>
                    <div class="text-center">
                        <img id="profile-preview-small" 
                            src="{{ $user->details->image ? asset('storage/' . json_decode($user->details->image, true)['profile'] ?? 'profiles/default-profile.png') : asset('storage/profiles/default-profile.png') }}"
                             class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-800 object-cover"
                             alt="Small Preview">
                        <p class="text-sm mb-2 dark:text-gray-200">32px</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-center mt-6">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>

    <!-- Cropping Modal -->
    <div id="cropper-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900">Crop Image</h2>
                <button id="cropper-close" class="text-gray-600 hover:text-gray-800 text-2xl">&times;</button>
            </div>
            <div class="w-full h-64 flex items-center justify-center">
                <img id="cropper-image" class="w-auto h-full object-contain">
            </div>
            <div class="flex justify-end mt-4">
                <button id="cropper-crop" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Crop
                </button>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.0.7/compressor.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
    @vite('resources/js/profile/profile-images.js')

    <script>
    // Prevent form submit if cropper modal is open
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('profile-images-form');
        const modal = document.getElementById('cropper-modal');
        if (form && modal) {
            form.addEventListener('submit', function(e) {
                if (!modal.classList.contains('hidden')) {
                    e.preventDefault();
                    alert('Please finish cropping your image or close the cropper modal before saving.');
                }
            });
        }
    });
    </script>
</section>