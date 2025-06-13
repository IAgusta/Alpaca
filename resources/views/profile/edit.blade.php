<x-app-layout>
    @section('title', 'Setting Profile - '. config('app.name') )
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Profile') }}
            <a href="{{ route('profile.index') }}">{{ __($user->name) }}</a>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl min-h-screen mx-auto sm:px-6 lg:px-8 space-y-6 ">
            <!-- Tabs Container -->
            <div class="lg:flex p-3">
                <!-- Mobile Dropdown Menu -->
                <div class="lg:hidden w-full">
                    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" 
                        class="w-full bg-white dark:bg-gray-800 dark:text-white justify-between text-gray-700 font-medium rounded-lg text-sm px-5 py-4 text-center inline-flex items-center shadow" 
                        type="button">
                        <span id="selectedTabText">Update Profile</span>
                        <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdown" class="z-10 hidden bg-white dark:bg-gray-800 dark:divide-slate-300 divide-y divide-gray-100 rounded-lg shadow w-full">
                        <ul class="text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                            <li>
                                <button data-tabs-target="#update-profile" class="w-full px-4 py-2 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-600 text-start">Update Profile</button>
                            </li>
                            <li>
                                <button data-tabs-target="#update-profile-pictures" class="w-full px-4 py-2 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-600 text-start">Change Pictures</button>
                            </li>
                            <li>
                                <button data-tabs-target="#update-link-account" class="w-full px-4 py-2 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-600 text-start">Social Media</button>
                            </li>
                            <li>
                                <button data-tabs-target="#update-password" class="w-full px-4 py-2 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-600 text-start">Change Password</button>
                            </li>
                            @unless(auth()->user()->role === 'owner')
                                <li>
                                    <button data-tabs-target="#delete-account" class="w-full px-4 py-2 hover:bg-gray-100 dark:text-red-600 dark:hover:bg-gray-600 text-start">Delete Account</button>
                                </li>
                            @endunless
                        </ul>
                    </div>
                </div>

                <!-- Desktop Vertical Tabs -->
                <div class="hidden lg:block w-52 shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-l-lg w-full shadow">
                        <ul class="flex flex-col w-full text-sm font-medium text-center" role="tablist">
                            <li class="me-2 lg:ml-2 lg:me-0 lg:my-2" role="presentation">
                                <button data-tabs-target="#update-profile" 
                                    class="w-full p-3 text-start rounded-l-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors dark:text-white"
                                    role="tab">Update Profile</button>
                            </li>
                            <li class="me-2 lg:ml-2 lg:me-0 lg:mb-2" role="presentation">
                                <button data-tabs-target="#update-profile-pictures"
                                    class="w-full p-3 text-start rounded-l-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors dark:text-white"
                                    role="tab">Change Pictures</button>
                            </li>
                            <li class="me-2 lg:ml-2 lg:me-0 lg:mb-2" role="presentation">
                                <button data-tabs-target="#update-link-account"
                                    class="w-full p-3 text-start rounded-l-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors dark:text-white"
                                    role="tab">Social Media Links</button>
                            </li>
                            <li class="me-2 lg:ml-2 lg:me-0 lg:mb-2" role="presentation">
                                <button data-tabs-target="#update-password"
                                    class="w-full p-3 text-start rounded-l-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors dark:text-white"
                                    role="tab">Change Password</button>
                            </li>
                            @unless(auth()->user()->role === 'owner')
                                <li class="me-2 mb-2 lg:ml-2 lg:me-0" role="presentation">
                                    <button data-tabs-target="#delete-account"
                                        class="w-full p-3 text-start rounded-l-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-red-500"
                                        role="tab">Delete Account</button>
                                </li>
                            @endunless
                        </ul>
                    </div>
                </div>

                <!-- Tab Content -->
                <div id="profile-tab-content" class="flex-grow">
                    <div id="tab-content" class="p-4 rounded-lg rounded-tl-none bg-white dark:bg-gray-800 shadow">
                        <div class="flex items-center justify-center text-gray-500 dark:text-gray-400">
                            Select a tab to load content
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('[data-tabs-target]');
            const tabContent = document.getElementById('tab-content');
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
            const loadTabContent = async (targetId) => {
                const section = targetId.substring(1);
                showLoadingState();

                try {
                    const response = await fetch(`/profile/section/${section}`);
                    if (!response.ok) throw new Error('Failed to load section');
                    const html = await response.text();
                    tabContent.innerHTML = html;

                    // Dispatch custom event for tab content loaded
                    document.dispatchEvent(new CustomEvent('tabContentLoaded', { 
                        detail: { section } 
                    }));

                    // Initialize components if needed
                    if (section === 'update-profile-pictures' && window.initCropperModal) {
                        window.initCropperModal();
                    } else if (section === 'update-link-account' && window.initSocialMediaForm) {
                        window.initSocialMediaForm();
                    }
                } catch (error) {
                    console.error('Error loading section:', error);
                    showError();
                }
            };

            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg ${
                    type === 'success' ? 'bg-green-500' : 'bg-red-500'
                } text-white`;
                notification.textContent = message;
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 3000);
            }

            function showLoadingState() {
                tabContent.innerHTML = `
                    <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow space-y-6 min-h-[250px] flex items-center justify-center text-sm text-gray-600 dark:text-gray-300">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="animate-spin h-10 w-10 text-gray-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Loading section...</span>
                        </div>
                    </div>
                `;
            }

            function showError() {
                tabContent.innerHTML = `
                    <div class="text-red-500 text-center p-4">
                        Failed to load content. Please try again.
                    </div>
                `;
            }

            const initializeTabs = (targetId = null) => {
                const savedTabId = targetId || localStorage.getItem(activeTabKey) || tabs[0]?.getAttribute('data-tabs-target');
                if (!savedTabId) return;

                tabs.forEach(tab => {
                    const isActive = tab.getAttribute('data-tabs-target') === savedTabId;
                    tab.setAttribute('aria-selected', isActive);
                    tab.classList.toggle('bg-gray-50', isActive);
                    tab.classList.toggle('dark:bg-gray-700', isActive);
                    tab.classList.toggle('text-purple-600', isActive);

                    if (isActive) {
                        selectedTabText.textContent = tab.textContent;
                    }
                });

                loadTabContent(savedTabId);
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

        // Add these utility functions at the top of your script
        function loadScript(src) {
            return new Promise((resolve, reject) => {
                if (document.querySelector(`script[src="${src}"]`)) {
                    resolve();
                    return;
                }
                const script = document.createElement('script');
                script.src = src;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        function loadStylesheet(href) {
            if (document.querySelector(`link[href="${href}"]`)) return;
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = href;
            document.head.appendChild(link);
        }
    </script>
</x-app-layout>