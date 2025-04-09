<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl min-h-screen mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Banner Section -->
            <div class="relative w-full h-52 rounded-xl overflow-hidden mt-6">
                <img 
                    src="{{ $user->details->image ? asset('storage/' . (json_decode($user->details->image, true)['banner'] ?? '')) : asset('storage/profiles/patterns.png') }}" 
                    class="w-full h-full object-cover" 
                    alt="Banner Image">
                <div class="absolute inset-0 bg-black bg-opacity-10"></div>
            </div>

            <!-- Profile Info Section -->
            <div class="relative px-4 sm:px-8 pb-6 rounded-b-xl">
                <div class="flex flex-col sm:flex-row items-start sm:items-center sm:space-x-4 -mt-16">
                    <!-- Profile Image -->
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-blue-500 shadow-lg shrink-0">
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
                                <span class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-xs mt-1">
                                    {{ Str::ucfirst($user->role ?? 'User') }}
                                </span>
                            </div>

                            <!-- Social Media Links with Usernames -->
                            <div class="flex flex-wrap gap-3">
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

            <!-- User Details Display -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 bg-white p-6 rounded-lg shadow">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Username') }}</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->username }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Role') }}</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ Str::ucfirst($user->role) }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('About me') }}</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $user->details->about ?? __("This user hasn't written a bio yet.") }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Email') }}</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <p class="text-sm mt-2 text-yellow-600">
                                {{ __('Your email address is unverified.') }}
                            </p>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Account Age') }}</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $accountage }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Birth Date') }}</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($user->details->birth_date)
                                {{ \Carbon\Carbon::parse($user->details->birth_date)->format('F j, Y') }}
                            @else
                                {{ __('Not provided') }}
                            @endif
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Phone Number') }}</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->details->phone ?? __('Not provided') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>