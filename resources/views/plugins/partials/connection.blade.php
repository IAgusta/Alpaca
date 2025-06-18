<div class="p-6 mb-7" x-data="{ 
    activeMode: null,
    isWifiConnected: false,
    isApiConnected: false,
    apiKeyVisible: false,
    justCopied: false,
    init() {
        window.addEventListener('api-copied', (e) => this.justCopied = e.detail);
    }
}">
    <h2 class="text-xl font-bold text-center dark:text-white">Connect to Your ESP32</h2>
    <p class="text-center text-gray-600 dark:text-gray-300 mb-6">Choose a connection method below:</p>
    <div id="connect-options" class="grid-cols-none grid lg:grid-cols-2 gap-4 mb-3">
        <!-- Wi-Fi Card -->
        <div id="wifi-card" 
             class="bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300 relative"
             :class="{ 'col-span-full': activeMode === 'wifi' }"
             x-show="!activeMode || activeMode === 'wifi'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100">
            <div class="p-4 cursor-pointer flex items-center justify-between" 
                 @click="activeMode = activeMode === 'wifi' ? null : 'wifi'">
                <div class="flex items-center space-x-4">
                    <div class="p-3 rounded-lg bg-green-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 -960 960 960">
                            <path d="M480-120q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM254-346l-84-86q59-59 138.5-93.5T480-560q92 0 171.5 35T790-430l-84 84q-44-44-102-69t-124-25q-66 0-124 25t-102 69ZM84-516 0-600q92-94 215-147t265-53q142 0 265 53t215 147l-84 84q-77-77-178.5-120.5T480-680q-116 0-217.5 43.5T84-516Z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg dark:text-white">Connect Using Wi-Fi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-200">Connect to your ESP32's IP address</p>
                    </div>
                </div>
            </div>

            <!-- Expandable Section -->
            <div x-show="activeMode === 'wifi'"
                 x-transition:enter="transition-all ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="p-4 border-t dark:border-gray-600">
                <div class="flex gap-3 items-center">
                    <input type="text" id="wifi-ip" placeholder="Enter ESP32 IP" 
                        class="bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg 
                                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 
                                    dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 
                                    dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                        onclick="event.stopPropagation();">
                    <button onclick="event.stopPropagation(); connectToESP32('wifi')" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Connect
                    </button>
                </div>
            </div>

            <!-- Modified Overlay -->
            <div id="wifi-overlay" class="absolute inset-0 bg-white/95 dark:bg-gray-800/95 flex flex-col items-center justify-center rounded-xl hidden">
                <div id="wifi-spinner" class="animate-spin rounded-full h-12 w-12 border-t-4 border-blue-500 border-opacity-50 mb-4"></div>
        
                <div id="wifi-success" class="hidden text-green-600 flex flex-col items-center">
                    <svg class="w-12 h-12 text-green-500 animate-bounce" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="mt-2 text-sm font-semibold">Connection Successful</span>
                </div>
        
                <button id="wifi-disconnect" onclick="disconnect('wifi'); event.stopPropagation()" 
                        class="mt-4 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm hidden">
                    Disconnect
                </button>
            </div>
        </div>
    
        <!-- API Card -->
        <div id="api-card" 
             class="bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300"
             :class="{ 'col-span-full': activeMode === 'api' }"
             x-show="!activeMode || activeMode === 'api'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             @click="{{ auth()->check() ? 'activeMode = activeMode === \'api\' ? null : \'api\'' : 'showLoginModal()' }}"
             :class="{ 'opacity-70': {{ !auth()->check() ? 'true' : 'false' }} }">
            <div class="p-4 cursor-pointer flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 rounded-lg bg-blue-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24">
                            <path d="M22 9V7h-2V5a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2h2v-2h-2v-2h2v-2h-2V9h2zm-4 10H4V5h14v14zM6 13h5v4H6zm6-6h4v3h-4zM6 7h5v5H6zm10 7h2v2h-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg dark:text-white">Connect Using API</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-200">
                            {{ auth()->check() ? 'Your personal API key for robot control' : 'Login required for API access' }}
                        </p>
                    </div>
                </div>
            </div>

            @auth
            <!-- Expandable Section -->
            <div x-show="activeMode === 'api'"
                 x-transition:enter="transition-all ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="p-4 border-t dark:border-gray-600">
                <div class="space-y-3">
                    {{-- Display, Copy, Regenerate, and Connect API key (one line flex) --}}
                    <div class="w-full flex flex-wrap items-center gap-2">
                        <div class="relative flex-1 min-w-0">
                            <input id="api-key" type="text" 
                                class="bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg 
                                       focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400"
                                :class="{ 'blur-sm select-none': !apiKeyVisible }"
                                readonly
                                @click.stop="apiKeyVisible = !apiKeyVisible"
                                value="{{ auth()->user()->robot?->api_key ?? 'No API key generated' }}"
                            >
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 flex items-center">
                                <!-- Eye Icon -->
                                <button type="button" 
                                        @click.stop="apiKeyVisible = !apiKeyVisible"
                                        class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400">
                                </button>
                                <!-- Copy Button -->
                                <button type="button"
                                        @click.stop="copyToClipboard()"
                                        class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400">
                                    <!-- Copy Icon -->
                                    <svg x-show="!justCopied" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
                                        <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h167q11-35 43-57.5t70-22.5q40 0 71.5 22.5T594-840h166q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560h-80v120H280v-120h-80v560Zm280-560q17 0 28.5-11.5T520-800q0-17-11.5-28.5T480-840q-17 0-28.5 11.5T440-800q0 17 11.5 28.5T480-760Z"/>
                                    </svg>
                                    <!-- Success/Checkmark Icon -->
                                    <svg x-show="justCopied" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor" class="text-green-600">
                                        <path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Regenerate Button -->
                        <button type="button"
                                id="regenerate-api"
                                onclick="regenerateApiKey(); event.stopPropagation();"
                                class="px-3 py-3 rounded-lg bg-gray-100 hover:bg-gray-200 
                                       dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white transition-colors 
                                       flex items-center gap-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span class="hidden lg:block">Regenerate</span>
                        </button>
                    </div>
                    {{-- can reset --}}
                    <div id="next-reset" class="text-sm text-gray-500"></div>
                </div>
            </div>
            @endauth
        </div>
    </div>
    @auth()
    <div class="flex justify-end gap-4 items-center my-3 space-x-4">
        <span class="text-sm text-gray-500 dark:text-gray-300">Press Skip Button if You want to connect via API Key:</span>
        <div class="flex text-center py-4">
            <button id="skip-button" 
                    @click="window.navigateToTab(3)"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                Skip
            </button>
        </div>
    </div>
    @endauth
</div>

<style>
    .connection-card.disabled {
        pointer-events: none;
        opacity: 0.5;
    }
    
    /* Remove or update the old overlay styles */
    #wifi-overlay {
        backdrop-filter: blur(2px);
    }

    #wifi-overlay.hidden {
        display: none !important;
    }
    
    .animate-success {
        animation: pop 0.6s ease-out forwards;
    }

    @keyframes pop {
        0% { transform: scale(0.7); opacity: 0; }
        50% { transform: scale(1.2); opacity: 1; }
        100% { transform: scale(1); }
    }
    .blur-sm {
        filter: blur(4px);
        user-select: none;
    }
</style>