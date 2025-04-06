<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('profile.index') }}">{{ __('Profile') }}</a>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl min-h-screen mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Tabs -->
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" 
                id="profile-tab"
                data-tabs-toggle="#profile-tab-content"
                data-tabs-active-classes="text-purple-600 hover:text-purple-600 dark:text-purple-500 dark:hover:text-purple-500 border-purple-600 dark:border-purple-500"
                data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300"
                role="tablist">
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg"
                        id="update-profile-tab" 
                        data-tabs-target="#update-profile" 
                        type="button" role="tab" aria-controls="update-profile" 
                        aria-selected="true">Update Profile</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" 
                        id="update-profile-pictures-tab" 
                        data-tabs-target="#update-profile-pictures" type="button" 
                        role="tab" aria-controls="update-profile-pictures" 
                        aria-selected="false">Change Pictures</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" 
                        id="update-link-account-tab" data-tabs-target="#update-link-account" 
                        type="button" role="tab" aria-controls="update-link-account" 
                        aria-selected="false">Social Media</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" 
                        id="update-password-tab" data-tabs-target="#update-password" 
                        type="button" role="tab" aria-controls="update-password" 
                        aria-selected="false">Change Password</button>
                    </li>
                    @unless(auth()->user()->role === 'owner')
                        <li role="presentation">
                            <button class="inline-block p-4 border-b-2 rounded-t-lg" 
                            id="delete-account-tab" data-tabs-target="#delete-account" type="button" 
                            role="tab" aria-controls="delete-account" 
                            aria-selected="false">Delete Account</button>
                        </li>
                    @endunless
                </ul>
            </div>

            <!-- Tab Content -->
            <div id="profile-tab-content">
                <div class="hidden p-4 rounded-lg bg-white shadow dark:bg-gray-800" 
                id="update-profile" role="tabpanel" aria-labelledby="update-profile-tab">
                    @include('profile.partials.update-profile-information-form')
                </div>
                <div class="hidden p-4 rounded-lg bg-white shadow dark:bg-gray-800" 
                id="update-profile-pictures" role="tabpanel" aria-labelledby="update-profile-pictures-tab">
                    @include('profile.partials.update-profile-pictures')
                </div>
                <div class="hidden p-4 rounded-lg bg-white shadow dark:bg-gray-800" 
                id="update-link-account" role="tabpanel" aria-labelledby="update-link-account">
                    @include('profile.partials.social-media-profile-information-modal')
                </div>
                <div class="hidden p-4 rounded-lg bg-white shadow dark:bg-gray-800" 
                id="update-password" role="tabpanel" aria-labelledby="update-password-tab">
                    @include('profile.partials.update-password-form')
                </div>
                @unless(auth()->user()->role === 'owner')
                    <div class="hidden p-4 rounded-lg bg-white shadow dark:bg-gray-800" 
                    id="delete-account" role="tabpanel" aria-labelledby="delete-account-tab">
                        @include('profile.partials.delete-user-form')
                    </div>
                @endunless
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('[data-tabs-target]');
            const tabContent = document.querySelectorAll('[role="tabpanel"]');
            const activeTabKey = 'activeProfileTab';

            // Retrieve the saved active tab from localStorage or query parameter
            const urlParams = new URLSearchParams(window.location.search);
            const queryTabId = urlParams.get('tab');
            const savedTabId = queryTabId || localStorage.getItem(activeTabKey);

            if (savedTabId) {
                // Activate the saved tab
                tabs.forEach(tab => {
                    tab.setAttribute('aria-selected', tab.id === savedTabId ? 'true' : 'false');
                    tab.classList.toggle('border-purple-600', tab.id === savedTabId);
                });
                tabContent.forEach(content => {
                    content.classList.toggle('hidden', content.id !== document.getElementById(savedTabId).getAttribute('data-tabs-target').substring(1));
                });
            } else {
                // Default to the first tab if no saved tab exists
                tabs[0].setAttribute('aria-selected', 'true');
                tabs[0].classList.add('border-purple-600');
                tabContent[0].classList.remove('hidden');
            }

            // Add click event listeners to tabs
            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    // Save the active tab to localStorage
                    localStorage.setItem(activeTabKey, this.id);

                    // Update the UI
                    tabs.forEach(t => {
                        t.setAttribute('aria-selected', 'false');
                        t.classList.remove('border-purple-600');
                    });
                    this.setAttribute('aria-selected', 'true');
                    this.classList.add('border-purple-600');

                    tabContent.forEach(content => {
                        content.classList.add('hidden');
                    });
                    document.querySelector(this.getAttribute('data-tabs-target')).classList.remove('hidden');
                });
            });
        });
    </script>
</x-app-layout>