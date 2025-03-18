<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden">
                <div class="grid gap-8 mb-6 lg:mb-16 md:grid-cols-2">
                    <div>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 mb-2">{{ __('Profile Information') }}
                                <button data-popover-target="popover-description" data-popover-placement="bottom-end" type="button">
                                    <svg class="w-3 h-3 text-gray-400 hover:text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" 
                                        clip-rule="evenodd"></path></svg>
                                        <span class="sr-only">Show information</span>
                                    </button>
                                </p>
                            </h2>
                            <div data-popover id="popover-description" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-xs opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                <div class="p-3 space-y-2">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Profile Information</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ __('Click, Image or Account Name to go into Profile, ') }}
                                        {{ __('Or you can') }} <a class="hover:text-blue-600 hover:underline" href="{{ route('profile.edit') }}">{{ __('Click Here') }}</a>
                                    </p>
                                </div>
                                <div data-popper-arrow></div>
                            </div>
                        </header>
                        
                        <div class="items-center bg-gray-50 rounded-lg shadow sm:flex dark:bg-gray-800 dark:border-gray-700">
                            <a href="{{ route('profile.edit') }}">
                                <img class="w-full rounded-lg sm:rounded-none sm:rounded-l-lg" src="{{ $user->image ? asset('storage/' .$user->image) : asset('storage/profiles/default-profile.png') }}" alt="Profile Image" style="width: 205px; height: 205px; object-fit: cover;">
                            </a>
                            <div class="p-5">
                                <h3 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    <a href="{{ route('profile.edit') }}">{{ ($user->name) }}</a>
                                </h3>
                                <span class="text-gray-500 dark:text-gray-400">{{ ($user->email) }}</span>
                                <div class="flex items-center gap-3 text-gray-500 dark:text-gray-400 mt-3 mb-4"> 
                                    @if ($user->active)
                                        <div class="flex items-center">
                                            <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> {{ __('Online') }}
                                        </div>
                                    @else
                                        <div class="flex items-center">
                                            <div class="h-2.5 w-2.5 rounded-full bg-gray-700 me-2"></div> {{ __('Offline') }}
                                        </div>
                                    @endif
                                </div>
                                <p class="mt-3 mb-4 font-light text-gray-500 dark:text-gray-400">{{ ($user->about ?? "This user doesn't have any bio") }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
