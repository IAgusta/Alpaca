<x-app-layout>  
    @section('title', 'Controller - '. config('app.name'))
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Controller') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                @auth
                    <div x-data="{ 
                        activeTab: {{ auth()->user()->robot ? 2 : 1 }},
                        init() {
                            this.checkConfiguration();
                            window.navigateToTab = (tab) => {
                                this.activeTab = tab;
                            };
                        },
                        async checkConfiguration() {
                            try {
                                const response = await fetch('/api/robot/configuration');
                                if (response.ok) {
                                    const { data } = await response.json();
                                    if (data) {
                                        this.activeTab = 2;
                                    }
                                }
                            } catch (error) {
                                console.error('Error checking configuration:', error);
                            }
                        }
                    }" class="p-3 w-full">
                        <div class="flex justify-center my-4">
                            <ol class="flex items-center gap-8 max-w-2xl mx-auto">
                                <li @click="activeTab = 1" :class="{ 'cursor-pointer': true }">
                                    <span class="flex items-center justify-center w-12 h-12 rounded-full transition-colors duration-300"
                                          :class="{ 'bg-blue-600 text-white': activeTab === 1, 'bg-gray-200 text-gray-600': activeTab !== 1 }">
                                        <svg class="w-4 h-4 lg:w-5 lg:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </span>
                                </li>
                                <li @click="activeTab = 2" :class="{ 'cursor-pointer': true }">
                                    <span class="flex items-center justify-center w-12 h-12 rounded-full transition-colors duration-300"
                                          :class="{ 'bg-blue-600 text-white': activeTab === 2, 'bg-gray-200 text-gray-600': activeTab !== 2 }">
                                        <svg class="w-4 h-4 lg:w-5 lg:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                        </svg>
                                    </span>
                                </li>
                                <li @click="activeTab = 3" :class="{ 'cursor-pointer': true }">
                                    <span class="flex items-center justify-center w-12 h-12 rounded-full transition-colors duration-300"
                                          :class="{ 'bg-blue-600 text-white': activeTab === 3, 'bg-gray-200 text-gray-600': activeTab !== 3 }">
                                        <svg class="w-4 h-4 lg:w-5 lg:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9" />
                                        </svg>
                                    </span>
                                </li>
                            </ol>
                        </div>

                        <!-- Tab Content -->
                        <div class="mt-4">
                            <div x-show="activeTab === 1">
                                @include('plugins.partials.type')
                            </div>
                            <div x-show="activeTab === 2">
                                @include('plugins.partials.connection')
                            </div>
                            <div x-show="activeTab === 3">
                                @include('plugins.partials.control')
                            </div>
                        </div>
                    </div>
                @else
                    <div class="space-y-6">
                        @include('plugins.partials.connection')
                        @include('plugins.partials.control')
                    </div>
                @endauth
            </div>
        </div>
    </div>
    @vite(['resources/js/robot/main.js'])
</x-app-layout>