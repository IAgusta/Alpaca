<div>
    <h2 class="text-xl font-bold text-center">Connect to Your ESP32</h2>
    <p class="text-center text-gray-600 mb-3">Choose a connection method below:</p>
    <div id="connect-options" class="grid-cols-none grid lg:grid-cols-2 gap-4 p-4 mb-7">
        <!-- Wi-Fi Card -->
        <div id="wifi-card" class="bg-white rounded-xl shadow-lg transition-all duration-300" 
             :class="{ 'col-span-full': activeMode === 'wifi' }">
            <div class="p-4 cursor-pointer flex items-center justify-between" onclick="expandCard('wifi')">
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
            <div class="details hidden p-4 border-t">
                <div class="flex gap-3 items-center">
                    <input type="text" id="wifi-ip" placeholder="Enter ESP32 IP" 
                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:border-blue-500" 
                        onclick="event.stopPropagation();">
                    <button onclick="event.stopPropagation(); connectToESP32('wifi')" 
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
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
        <div id="api-card" class="bg-white rounded-xl shadow-lg transition-all duration-300" 
             :class="{ 'col-span-full': activeMode === 'api' }"
             onclick="{{ auth()->check() ? 'expandCard(\'api\')' : '' }}"
             style="{{ !auth()->check() ? 'opacity: 0.7; cursor: not-allowed;' : '' }}">
            <div class="p-4 flex items-center justify-between">
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
            <div class="details hidden p-4 border-t">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <input type="text" id="api-key" readonly
                            class="flex-1 rounded-lg border border-gray-300 px-3 py-2 bg-gray-50" 
                            onclick="event.stopPropagation();">
                        <button onclick="event.stopPropagation(); copyApiKey()" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Copy
                        </button>
                    </div>
                    <div class="flex items-center gap-2 justify-between">
                        <button id="regenerate-api" onclick="event.stopPropagation(); regenerateApiKey()"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            Regenerate Key
                        </button>
                        <span id="next-reset" class="text-sm text-gray-500"></span>
                    </div>
                    <button onclick="event.stopPropagation(); connectWithApiKey()" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Connect Using API Key
                    </button>
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