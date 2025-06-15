<x-app-layout>
    @section('title', 'Profile - ' . config('app.name'))
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl min-h-screen mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Banner Section -->
            <div class="relative w-full h-52 rounded-xl overflow-hidden mt-6">
                <img 
                    src="{{ $user->details->image ? asset('storage/' . (json_decode($user->details->image, true)['banner'] ?? 'profiles/patterns.png')) : asset('storage/profiles/patterns.png') }}" 
                    class="w-full h-full object-cover" 
                    alt="Banner Image">
                <div class="absolute inset-0 bg-black bg-opacity-10"></div>
                <!-- Edit Button - Top Right -->
                <div class="absolute top-4 right-4">
                    <a href="{{ route('profile.edit') }}" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-md shadow hover:bg-blue-700">
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Profile Info Section (Overlay) -->
            <div class="relative px-4 sm:px-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-end -mt-20 sm:-mt-16">
                    <!-- Avatar - overlaps banner -->
                    <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-white dark:border-gray-900 shadow-lg bg-white">
                        <img 
                            src="{{ $user->details->image ? asset('storage/' . (json_decode($user->details->image, true)['profile'] ?? '')) : asset('storage/profiles/default-profile.png') }}" 
                            class="w-full h-full object-cover" 
                            alt="Profile Image">
                    </div>

                    <!-- Name/Role/Socials -->
                    <div class="mt-2 sm:mt-0 sm:ml-6 flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $user->name }}</h1>
                                    <span class="text-gray-500 dark:text-gray-300 text-xl font-mono ml-3">{{ '@' . $user->username }}</span>
                                </div>
                                @include('profile.partials.all.role-badge')
                            </div>
                            <!-- Social Media Links with Usernames -->
                            <div class="flex flex-wrap gap-3">
                                @foreach (['facebook', 'instagram', 'x', 'linkedin', 'youtube', 'github'] as $platform)
                                    @php
                                        $socialMediaLinks = $user->details->social_media ?? [];
                                        $link = $socialMediaLinks[$platform] ?? null;
                                    @endphp
                                    @if ($link)
                                        <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="p-2 flex items-center rounded-full hover:bg-gray-50 dark:hover:bg-gray-500 dark:bg-gray-300">
                                            <img src="{{ asset('icons/' . $platform . '.svg') }}" alt="{{ $platform }} icon" class="w-5 h-5">
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blended Profile Details Section -->
            <div class="mt-8 rounded-xl bg-white dark:bg-gray-800 shadow p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <!-- About Me -->
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 12c2.28 0 4.19-1.92 4.19-4.28C16.19 5.01 14.28 3 12 3S7.81 5.01 7.81 7.72C7.81 10.08 9.72 12 12 12zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('About me') }}</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-base leading-relaxed">
                                {{ $user->details->about ?? __("This user hasn't written a bio yet.") }}
                            </p>
                        </div>
                        <!-- Account Age -->
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3"></path><circle cx="12" cy="12" r="10"></circle></svg>
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Account Age') }}</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">{{ $accountage }}</p>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <!-- Email -->
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Email') }}</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <p class="text-xs mt-2 text-yellow-600 dark:text-yellow-400">
                                    {{ __('Your email address is unverified.') }}
                                </p>
                            @endif
                        </div>
                        <!-- Birth Date -->
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3M16 7V3M3 11h18M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"></path></svg>
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Birth Date') }}</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">
                                @if($user->details->birth_date)
                                    {{ \Carbon\Carbon::parse($user->details->birth_date)->format('F j, Y') }}
                                @else
                                    {{ __('Not provided') }}
                                @endif
                            </p>
                        </div>
                        <!-- Phone -->
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92V19a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07A19.5 19.5 0 0 1 3.07 8.63 19.86 19.86 0 0 1 0 3.18 2 2 0 0 1 2 1.82h2.09A2 2 0 0 1 6 3.22c.18.56.4 1.12.65 1.67a2 2 0 0 1-.45 2.11l-1.27 1.27a16 16 0 0 0 7.17 7.17l1.27-1.27a2 2 0 0 1 2.11-.45c.55.25 1.11.47 1.67.65a2 2 0 0 1 1.4 1.92z"/></svg>
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Phone Number') }}</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">{{ $user->details->phone ?? __('Not provided') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
