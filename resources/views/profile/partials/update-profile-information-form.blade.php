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
                <div class="flex flex-col items-center">
                    <!-- Banner and Profile Image Wrapper -->
                    <div class="relative w-full max-w-3xl h-48">
                        <!-- Banner Image -->
                        <img id="banner" 
                        src="{{ $user->details->image ? asset('storage/' . (json_decode($user->details->image, true)['banner'] ?? '')) : '' }}" 
                        onerror="this.src='{{ asset('storage/profiles/patterns.png') }}'" 
                             class="w-full h-full rounded-lg border border-gray-300 object-cover" 
                             alt="Banner Image">

                        <!-- Dark Overlay (30-40% opacity) -->
                        <div class="absolute inset-0 bg-black bg-opacity-5 rounded-lg"></div>
                        
                        <!-- Profile Image - Fully Centered -->
                        <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <img id="profile-preview" 
                            src="{{ $user->details->image ? asset('storage/' . (json_decode($user->details->image, true)['profile'] ?? '')) : '' }}" 
                            onerror="this.src='{{ asset('storage/profiles/default-profile.png') }}'" 
                                 class="w-32 h-32 rounded-full object-cover shadow-lg" 
                                 alt="Profile Image">
                        </div>
                    </div>
                    <!-- Spacer to push content down, accounting for profile overlap -->
                    <div class="h-2"></div>
                </div>                

                <!-- Bio (About) -->
                <div class="mt-4 h-full w-full">
                    <x-input-label for="about" :value="__('Bio')" />
                    <textarea id="about" name="about" rows="5"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 resize-none placeholder-gray-400"
                        placeholder="This user doesn't have any bio">{{ old('about', $user->details->about) }}</textarea>
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
    
                <!-- Birth Date -->
                <div class="mt-4">
                    <x-input-label for="birth_date" :value="__('Birth Date (Year/Month/Day)')" />
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                        </div>
                        <input id="birth_date" type="text" name="birth_date" 
                            value="{{ old('birth_date', $user->details->birth_date) }}"
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
                                  :value="old('phone', $user->details->phone)" placeholder="Optional" />
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>

                <div class="flex mt-6 gap-2">
                    <span>Social Media :</span>
                    <div class="flex gap-2">
                        @foreach (['facebook', 'instagram', 'x', 'linkedin', 'youtube'] as $platform)
                        <div class="flex items-center">
                            <img src="{{ asset('icons/' . $platform . '.svg') }}" alt="{{ $platform }} icon" class="w-5 h-5 me-2">
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="social_media" id="social-media-input">
                </div>
            </div>
        </div>
    
        <!-- Save Button -->
        <div class="flex justify-center mt-6">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>

    <!-- Required Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/datepicker.min.js"></script>
    @vite('resources/js/profile/added-social-link.js')

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        new Datepicker(document.getElementById('birth_date'), {
            // Optional configuration
            format: 'yyyy-mm-dd', // Match your DB date format
            autohide: true, // Auto-close after selection
        });
    });
    </script>
</section>