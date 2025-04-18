<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }} {{ $user->name }}
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

            <!-- Tabs Container -->
            <div class="lg:flex">
                <!-- Tab List -->
                <div class="flex overflow-x-auto lg:flex-col lg:w-48 lg:shrink-0">
                    <div class="bg-white rounded-l-lg w-full">
                        <ul class="flex flex-row lg:flex-col w-full text-sm font-medium text-center" 
                            id="profile-show-tab"
                            data-tabs-toggle="#profile-show-tab-content"
                            role="tablist">
                            <li class="me-2 lg:me-0 lg:mb-2" role="presentation">
                                <button class="w-full p-3 text-start rounded-l-lg hover:bg-gray-50 transition-colors"
                                    id="information-tab" 
                                    data-tabs-target="#information" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="information" 
                                    aria-selected="true">
                                    Information
                                </button>
                            </li>
                            <li class="me-2 lg:me-0 lg:mb-2" role="presentation">
                                <button class="w-full p-3 text-start rounded-l-lg hover:bg-gray-50 transition-colors" 
                                    id="courses-tab" 
                                    data-tabs-target="#courses" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="courses" 
                                    aria-selected="false">
                                    Courses
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tab Content -->
                <div id="profile-show-tab-content" class="flex-grow">
                    <div class="hidden p-4 rounded-lg bg-white dark:bg-gray-800" 
                    id="information" role="tabpanel" aria-labelledby="information-tab">
                        <!-- User Details Display -->
                        @include('profile.partials.user_information')
                    </div>

                    <div class="hidden p-4 rounded-lg bg-white dark:bg-gray-800" 
                    id="courses" role="tabpanel" aria-labelledby="courses-tab">
                        <!-- Courses Section -->
                        @include('profile.partials.user_course_information')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('[data-tabs-target]');
            const tabContent = document.querySelectorAll('[role="tabpanel"]');

            // Function to get URL parameters
            const getUrlParam = (param) => {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            };

            // Set default active tab (information)
            const setActiveTab = (targetId) => {
                tabs.forEach(tab => {
                    const isActive = tab.getAttribute('data-tabs-target') === targetId;
                    tab.setAttribute('aria-selected', isActive);
                    tab.classList.toggle('bg-gray-50', isActive);
                    tab.classList.toggle('text-purple-600', isActive);
                });

                tabContent.forEach(content => {
                    content.classList.toggle('hidden', content.id !== targetId.substring(1));
                });
            };

            // Check for tab parameter in URL, otherwise default to information
            const activeTab = getUrlParam('tab');
            setActiveTab(activeTab ? `#${activeTab}` : '#information');

            // Handle tab clicks
            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    setActiveTab(this.getAttribute('data-tabs-target'));
                });
            });
        });
    </script>
</x-app-layout>