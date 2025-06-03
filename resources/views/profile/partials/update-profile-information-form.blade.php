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

    <div class="mt-6 space-y-6">
        <!-- Banner Section -->
        <div class="relative w-full h-52 rounded-xl overflow-hidden">
            <img 
                src="{{ $user->details->image ? asset('storage/' . (json_decode($user->details->image, true)['banner'] ?? 'profiles/patterns.png')) : asset('storage/profiles/patterns.png') }}" 
                class="w-full h-full object-cover" 
                alt="Banner Image">
            <div class="absolute inset-0 bg-black bg-opacity-10"></div>
        </div>

        <!-- Profile Info Section - like find user -->
        <div class="relative px-4 sm:px-8 pb-6 rounded-b-xl">
            <div class="flex flex-col sm:flex-row items-start sm:items-center sm:space-x-4 -mt-16">
                <!-- Profile Image -->
                <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-gray-500 dark:border-gray-900 shadow-lg shrink-0">
                    <img 
                        src="{{ $user->details->image ? asset('storage/' . (json_decode($user->details->image, true)['profile'] ?? '')) : asset('storage/profiles/default-profile.png') }}" 
                        class="w-full h-full object-cover" 
                        alt="Profile Image">
                </div>

                <!-- Name and Role + Socials -->
                <div class="pt-4 sm:pt-6 w-full">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full">
                        <!-- Name and Role -->
                        <div class="mb-2 sm:mb-0">
                            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                            @include('profile.partials.all.role-badge')
                        </div>

                        <!-- Social Media Links -->
                        <div class="flex space-x-3">
                            @foreach (['facebook', 'instagram', 'x', 'linkedin', 'youtube', 'github'] as $platform)
                                @php
                                    $socialMediaLinks = $user->details->social_media ?? [];
                                    $link = $socialMediaLinks[$platform] ?? null;
                                @endphp
                                @if ($link)
                                    <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="flex items-center">
                                        <img src="{{ asset('icons/' . $platform . '.svg') }}" alt="{{ $platform }} icon" class="w-5 h-5 mr-2">
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- User Details Form -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                              :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />

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

                @php
                    $cleanPhone = old('phone', $user->details->phone);
                    $cleanPhone = $cleanPhone && Str::startsWith($cleanPhone, '+62') ? Str::substr($cleanPhone, 3) : $cleanPhone;
                @endphp
                
                <div class="mt-4">
                    <x-input-label for="phone" :value="__('Phone Number')" />
                
                    <div x-data="{ phone: '{{ $cleanPhone }}' }" class="relative flex items-stretch mt-1">
                        <!-- Static +62 prefix -->
                        <span class="flex items-center px-3 text-sm bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg
                                    dark:bg-gray-700 dark:border-gray-600 text-gray-700 dark:text-white"
                            style="min-height: 42px;">
                            +62
                        </span>
                
                        <!-- Visible input -->
                        <input
                            id="phone"
                            name="visible_phone"
                            x-model="phone"
                            x-on:input="phone = phone.replace(/[^0-9]/g, '')"
                            type="text"
                            placeholder="8123XXXXXXX"
                            class="rounded-r-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm block w-full p-2.5 placeholder-gray-400
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-500"
                        />
                
                        <!-- Hidden full phone with +62 -->
                        <input type="hidden" name="phone" :value="phone.trim() !== '' ? '+62' + phone : ''">
                    </div>
                
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>
            </div>

            <div>
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

                <div class="mt-4">
                    <x-input-label for="about" :value="__('Bio')" />
                    <textarea id="about" name="about" rows="4"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 resize-none placeholder-gray-400"
                              placeholder="This user doesn't have any bio">{{ old('about', $user->details->about) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('about')" />
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
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        new Datepicker(document.getElementById('birth_date'), {
            format: 'yyyy-mm-dd',
            autohide: true,
        });
    });
    </script>
</section>