<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
            @if (session('status') === 'profile-updated')
            <x-input-success
                :messages="__('Profile Information Changed.')"/>
            @endif
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')
    
        <div class="flex gap-6">
            <!-- Profile Image Section -->
            <div class="flex-1 flex flex-col items-center">
                <div class="relative w-38 h-38">
                    <!-- Profile Image -->
                    <img id="profile-preview" src="{{ $user->image ? asset('storage/' .$user->image) : asset('storage/profiles/default-profile.png') }}" 
                         class="w-32 h-32 rounded-full border border-gray-300 object-cover" 
                         alt="Profile Image">
                
                    <!-- Upload Button (Positioned Top-Right) -->
                    <label for="image" class="absolute top-1 right-1 bg-white p-2 rounded-full shadow-md cursor-pointer hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-5 h-5 text-gray-600 hover:text-gray-800" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                    </label>
                    
                    <!-- Hidden File Input -->
                    <input type="file" id="image" name="image" class="hidden" accept="image/*">
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('image')" />

                <!-- Bio (About) -->
                <div class="mt-4 h-full w-full">
                    <x-input-label for="about" :value="__('Bio')" />
                    <textarea id="about" name="about" rows="5"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 resize-none placeholder-gray-400"
                        placeholder="This user doesn't have any bio">{{ old('about', $user->about) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('about')" />
                </div>
            </div>
    
            <div class="flex-1">
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                  :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
    
                <!-- Birth Date -->
                <div class="mt-4">
                    <x-input-label for="birth_date" :value="__('Birth Date')" />
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                        </div>
                        <input id="birth_date" type="text" name="birth_date" 
                               value="{{ old('birth_date', $user->birth_date) }}"
                               data-datepicker data-datepicker-buttons data-datepicker-autoselect-today
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                               placeholder="Select birth date">
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
                </div>
                
                <!-- Phone Number -->
                <div class="mt-4">
                    <x-input-label for="phone" :value="__('Phone Number')" />
                    <x-text-input id="phone" name="phone" type="number" class="mt-1 block w-full no-spinner"  
                                  :value="old('phone', $user->phone)" placeholder="Optional" />
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>
    
                <!-- Email -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                  :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
    
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>
    
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    
        <!-- Save Button -->
        <div class="flex justify-center mt-6">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>

    <!-- Cropping Modal -->
    <div id="cropper-modal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900">Crop Image</h2>
                <button id="cropper-close" class="text-gray-600 hover:text-gray-800">&times;</button>
            </div>
            <div class="w-full h-64">
                <img id="cropper-image" class="w-full h-full object-cover">
            </div>
            <div class="flex justify-end mt-4">
                <button id="cropper-crop" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Crop</button>
            </div>
        </div>
    </div>

    <!-- Required Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.0.7/compressor.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />

    <script>
        // Initialize datepicker with proper options
        document.addEventListener('DOMContentLoaded', function() {
            new Datepicker(document.getElementById('birth_date'), {
                // Optional configuration
                format: 'yyyy-mm-dd', // Match your DB date format
                autohide: true, // Auto-close after selection
            });
        });

        let cropper;
        const imageInput = document.getElementById('image');
        const cropperModal = document.getElementById('cropper-modal');
        const cropperImage = document.getElementById('cropper-image');
        const cropperClose = document.getElementById('cropper-close');
        const cropperCrop = document.getElementById('cropper-crop');
        const profilePreview = document.getElementById('profile-preview');

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) { // Check if file is larger than 2MB
                    new Compressor(file, {
                        quality: 0.6, // Adjust quality as needed
                        maxWidth: 1920, // Adjust max width as needed
                        maxHeight: 1920, // Adjust max height as needed
                        success(result) {
                            handleFile(result);
                        },
                        error(err) {
                            console.error(err.message);
                        },
                    });
                } else {
                    handleFile(file);
                }
            }
        });

        function handleFile(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result;
                cropperModal.classList.remove('hidden');
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                });
            };
            reader.readAsDataURL(file);
        }

        cropperClose.addEventListener('click', function() {
            cropper.destroy();
            cropperModal.classList.add('hidden');
        });

        cropperCrop.addEventListener('click', function() {
            const canvas = cropper.getCroppedCanvas();
            canvas.toBlob(function(blob) {
                if (blob.size > 2 * 1024 * 1024) { // Re-compress if still larger than 2MB
                    new Compressor(blob, {
                        quality: 0.6, // Adjust quality as needed
                        success(result) {
                            updateImageInput(result);
                        },
                        error(err) {
                            console.error(err.message);
                        },
                    });
                } else {
                    updateImageInput(blob);
                }
            });
        });

        function updateImageInput(blob) {
            const url = URL.createObjectURL(blob);
            profilePreview.src = url;
            const fileInput = new File([blob], 'cropped.jpg', { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(fileInput);
            imageInput.files = dataTransfer.files;
            cropper.destroy();
            cropperModal.classList.add('hidden');
        }
    </script>
</section>