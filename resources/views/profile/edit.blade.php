<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('profile.index') }}">{{ __('Profile') }}</a>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl min-h-screen mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Tabs Container -->
            <div class="lg:flex p-3">
                <!-- Mobile Dropdown Menu -->
                <div class="lg:hidden w-full">
                    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" 
                        class="w-full bg-white justify-between text-gray-700 font-medium rounded-lg text-sm px-5 py-4 text-center inline-flex items-center shadow" 
                        type="button">
                        <span id="selectedTabText">Update Profile</span>
                        <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-full">
                        <ul class="text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                            <li>
                                <button data-tabs-target="#update-profile" class="w-full px-4 py-2 hover:bg-gray-100 text-start">Update Profile</button>
                            </li>
                            <li>
                                <button data-tabs-target="#update-profile-pictures" class="w-full px-4 py-2 hover:bg-gray-100 text-start">Change Pictures</button>
                            </li>
                            <li>
                                <button data-tabs-target="#update-link-account" class="w-full px-4 py-2 hover:bg-gray-100 text-start">Social Media</button>
                            </li>
                            <li>
                                <button data-tabs-target="#update-password" class="w-full px-4 py-2 hover:bg-gray-100 text-start">Change Password</button>
                            </li>
                            @unless(auth()->user()->role === 'owner')
                                <li>
                                    <button data-tabs-target="#delete-account" class="w-full px-4 py-2 hover:bg-gray-100 text-start">Delete Account</button>
                                </li>
                            @endunless
                        </ul>
                    </div>
                </div>

                <!-- Desktop Vertical Tabs -->
                <div class="hidden lg:block w-52 shrink-0">
                    <div class="bg-white rounded-l-lg">
                        <ul class="flex flex-col w-full text-sm font-medium text-center" role="tablist">
                            <li class="me-2 lg:me-0 lg:mb-2" role="presentation">
                                <button data-tabs-target="#update-profile" 
                                    class="w-full p-3 text-start rounded-l-lg hover:bg-gray-50 transition-colors"
                                    role="tab">Update Profile</button>
                            </li>
                            <li class="me-2 lg:me-0 lg:mb-2" role="presentation">
                                <button data-tabs-target="#update-profile-pictures"
                                    class="w-full p-3 text-start rounded-l-lg hover:bg-gray-50 transition-colors"
                                    role="tab">Change Pictures</button>
                            </li>
                            <li class="me-2 lg:me-0 lg:mb-2" role="presentation">
                                <button data-tabs-target="#update-link-account"
                                    class="w-full p-3 text-start rounded-l-lg hover:bg-gray-50 transition-colors"
                                    role="tab">Social Media Links</button>
                            </li>
                            <li class="me-2 lg:me-0 lg:mb-2" role="presentation">
                                <button data-tabs-target="#update-password"
                                    class="w-full p-3 text-start rounded-l-lg hover:bg-gray-50 transition-colors"
                                    role="tab">Change Password</button>
                            </li>
                            @unless(auth()->user()->role === 'owner')
                                <li class="me-2 lg:me-0 lg:mb-2" role="presentation">
                                    <button data-tabs-target="#delete-account"
                                        class="w-full p-3 text-start rounded-l-lg hover:bg-gray-50 transition-colors"
                                        role="tab">Delete Account</button>
                                </li>
                            @endunless
                        </ul>
                    </div>
                </div>

                <!-- Tab Content -->
                <div id="profile-tab-content" class="flex-grow">
                    <div class="hidden p-4 rounded-lg rounded-tl-none bg-white dark:bg-gray-800" 
                    id="update-profile" role="tabpanel" aria-labelledby="update-profile-tab">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                    <div class="hidden p-4 rounded-lg rounded-tl-none bg-white dark:bg-gray-800" 
                    id="update-profile-pictures" role="tabpanel" aria-labelledby="update-profile-pictures-tab">
                        @include('profile.partials.update-profile-pictures')
                    </div>
                    <div class="hidden p-4 rounded-lg rounded-tl-none bg-white dark:bg-gray-800" 
                    id="update-link-account" role="tabpanel" aria-labelledby="update-link-account">
                        @include('profile.partials.social-media-profile-information-modal')
                    </div>
                    <div class="hidden p-4 rounded-lg rounded-tl-none bg-white dark:bg-gray-800" 
                    id="update-password" role="tabpanel" aria-labelledby="update-password-tab">
                        @include('profile.partials.update-password-form')
                    </div>
                    @unless(auth()->user()->role === 'owner')
                        <div class="hidden p-4 rounded-lg rounded-tl-none bg-white dark:bg-gray-800" 
                        id="delete-account" role="tabpanel" aria-labelledby="delete-account-tab">
                            @include('profile.partials.delete-user-form')
                        </div>
                    @endunless
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('[data-tabs-target]');
            const tabContent = document.querySelectorAll('[role="tabpanel"]');
            const activeTabKey = 'activeProfileTab';
            const dropdown = document.getElementById('dropdown');
            const dropdownButton = document.getElementById('dropdownDefaultButton');
            const selectedTabText = document.getElementById('selectedTabText');

            // Toggle dropdown
            dropdownButton?.addEventListener('click', () => {
                dropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!dropdownButton?.contains(e.target) && !dropdown?.contains(e.target)) {
                    dropdown?.classList.add('hidden');
                }
            });

            // Initialize tabs
            const initializeTabs = (targetId = null) => {
                const savedTabId = targetId || localStorage.getItem(activeTabKey) || tabs[0]?.getAttribute('data-tabs-target');
                if (!savedTabId) return;

                tabs.forEach(tab => {
                    const isActive = tab.getAttribute('data-tabs-target') === savedTabId;
                    tab.setAttribute('aria-selected', isActive);
                    tab.classList.toggle('bg-gray-50', isActive);
                    tab.classList.toggle('text-purple-600', isActive);
                    
                    if (isActive) {
                        selectedTabText.textContent = tab.textContent;
                    }
                });

                tabContent.forEach(content => {
                    content.classList.toggle('hidden', content.id !== savedTabId.substring(1));
                });

                dropdown?.classList.add('hidden');
            };

            // Set initial active tab
            initializeTabs();

            // Handle tab clicks
            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-tabs-target');
                    localStorage.setItem(activeTabKey, targetId);
                    initializeTabs(targetId);
                });
            });
        });
    </script>
</x-app-layout>