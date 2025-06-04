<div x-data="{ 
    activeMode: null,
    isWifiConnected: false,
    isApiConnected: false,
    connect(type) {
        connectToESP32(type);
    },
    showLoginModal() {
        document.getElementById('accessModal').classList.remove('hidden');
        document.getElementById('modalBackdrop').classList.remove('hidden');
    }
}">
    <h2 class="text-xl font-bold text-center">Connect to Your ESP32</h2>
    <p class="text-center text-gray-600 mb-3">Choose a connection method below:</p>
    <div id="connect-options" class="grid-cols-none grid lg:grid-cols-2 gap-4 p-4 mb-7">
        <!-- Wi-Fi Card -->
        <div id="wifi-card" 
             class="bg-white rounded-xl shadow-lg transition-all duration-300"
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
                        <h3 class="font-semibold text-lg">Connect Using Wi-Fi</h3>
                        <p class="text-sm text-gray-500">Connect to your ESP32's IP address</p>
                    </div>
                </div>
            </div>

            <!-- Expandable Section -->
            <div x-show="activeMode === 'wifi'"
                 x-transition:enter="transition-all ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="p-4 border-t">
                <div class="flex gap-3 items-center">
                    <input type="text" id="wifi-ip" placeholder="Enter ESP32 IP" 
                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:border-blue-500" 
                        onclick="event.stopPropagation();">
                    <button onclick="event.stopPropagation(); connectToESP32('wifi')" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Connect
                    </button>
                </div>
            </div>

            <!-- Overlay with Spinner and Success -->
            <div id="wifi-overlay" class="overlay absolute inset-0 bg-white/80 flex flex-col items-center justify-center z-10 hidden">
                <!-- Spinner -->
                <div id="wifi-spinner" class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-opacity-50 mb-4"></div>
        
                <!-- Success Animation -->
                <div id="wifi-success" class="hidden text-green-600 flex flex-col items-center">
                    <svg class="w-16 h-16 text-green-500 animate-bounce" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="mt-2 text-lg font-semibold">Connection Successful</span>
                </div>
        
                <!-- Disconnect Button -->
                <button id="wifi-disconnect" onclick="disconnect('wifi'); event.stopPropagation()" class="mt-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded hidden">
                Disconnect
                </button>
            </div>
        </div>
    
        <!-- API Card -->
        <div id="api-card" 
             class="bg-white rounded-xl shadow-lg transition-all duration-300"
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
                        <h3 class="font-semibold text-lg">Connect Using API</h3>
                        <p class="text-sm text-gray-500">
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
                 class="p-4 border-t">
                <div class="space-y-3">
                    {{-- Display, Copy, Regenerate, and Connect API key (one line flex) --}}
                    <div class="w-full flex flex-wrap items-center gap-2">
                        <div class="relative flex-1 min-w-0">
                            <label for="api-key" class="sr-only">API Key</label>
                            <input id="api-key" type="text" 
                                class="bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg 
                                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 
                                    dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 
                                    dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="Your API key here"
                                readonly
                                disabled
                                onclick="event.stopPropagation();"
                            >
                            <button onclick="event.stopPropagation(); copyApiKey()" 
                                    aria-label="Copy API Key"
                                    class="absolute end-2 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 
                                        hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg p-2 inline-flex 
                                        items-center justify-center">
                                <!-- Default copy icon -->
                                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                    <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
                                </svg>
                                <!-- Success icon - toggle visibility as needed -->
                                <svg class="w-3.5 h-3.5 text-blue-700 dark:text-blue-500 hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12" id="success-icon">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                                </svg>
                            </button>
                            <!-- Tooltip -->
                            <div id="tooltip-copy-api-key" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                <span id="default-tooltip-message">Copy to clipboard</span>
                                <span id="success-tooltip-message" class="hidden">Copied!</span>
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </div>
                        <button id="regenerate-api" onclick="event.stopPropagation(); regenerateApiKey()"
                                class="text-black px-3 py-2 rounded-lg dark:text-white hover:bg-gray-300 transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-6 h-6">
                                <path d="M204-318q-22-38-33-78t-11-82q0-134 93-228t227-94h7l-64-64 56-56 160 160-160 160-56-56 64-64h-7q-100 0-170 70.5T240-478q0 26 6 51t18 49l-60 60ZM481-40 321-200l160-160 56 56-64 64h7q100 0 170-70.5T720-482q0-26-6-51t-18-49l60-60q22 38 33 78t11 82q0 134-93 228t-227 94h-7l64 64-56 56Z"/>
                            </svg>
                        </button>
                        <button onclick="event.stopPropagation(); connectWithApiKey()" 
                                class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                            Connect
                        </button>
                    </div>
                    <span id="next-reset" class="text-sm text-gray-500"></span>
                </div>
            </div>
            @endauth

            <!-- Overlay with Spinner and Success -->
            <div id="api-overlay" class="overlay absolute inset-0 bg-white/80 flex flex-col items-center justify-center z-10 hidden">
                <div id="api-spinner" class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-opacity-50 mb-4"></div>
                <div id="api-success" class="hidden text-green-600 flex flex-col items-center">
                    <svg class="w-16 h-16 text-green-500 animate-bounce" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="mt-2 text-lg font-semibold">Connection Successful</span>
                </div>
                <button id="api-disconnect" onclick="disconnect('api'); event.stopPropagation()" 
                        class="mt-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded hidden">
                    Disconnect
                </button>
            </div>
        </div>
    </div>
</div>

@include('partials.need-login')

<style>
    .connection-card.disabled {
        pointer-events: none;
        opacity: 0.5;
    }
    
    .overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.75);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
    }

    .overlay.hidden {
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
</style>