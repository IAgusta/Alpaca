<x-app-layout>  
    @section('title', 'Controller - '. config('app.name'))
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Controller') }}
            <a href="{{ route('documentation')}}" class="ml-3" id="documentation-link">
                <x-secondary-button> {{ __('Documentation') }}</x-secondary-button>
            </a>
        </h2>
    </x-slot>

    <style>
        .connection-card {
            border: 1px solid #ccc;
            padding: 1.25rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
      
        .connection-card.expanded {
            background-color: #f9fafb;
        }

        .connection-card.disabled {
            pointer-events: none;
            opacity: 0.5;
        }
      
        .icon-text {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
      
        .icon {
            width: 4rem;
            height: 4rem;
        }
      
        .title {
            font-weight: bold;
            font-size: 1.25rem;
        }
      
        .subtitle {
            color: #555;
            font-size: 0.9rem;
        }
      
        .details {
            margin-top: 1rem;
        }
      
        .input {
            border: 1px solid #ccc;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            width: 100%;
        }
      
        .btn {
            background: #1e40af;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
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
      
        .spinner {
            width: 2rem;
            height: 2rem;
            border: 3px solid #ccc;
            border-top: 3px solid #1e40af;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .animate-success {
            animation: pop 0.6s ease-out forwards;
        }

        @keyframes pop {
            0% { transform: scale(0.7); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }
      
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden">
                <h2 class="text-xl font-bold text-center">Connect to Your ESP32</h2>
                <p class="text-center text-gray-600 mb-3">Choose a connection method below:</p>
                <div id="connect-options" class="grid-cols-none grid lg:grid-cols-2 gap-4 p-4 mb-7">
                    <!-- Wi-Fi Card -->
                    <div id="wifi-card" class="connection-card group relative" onclick="expandCard('wifi')">
                        <div class="icon-text">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 -960 960 960">
                                <path d="M480-120q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM254-346l-84-86q59-59 138.5-93.5T480-560q92 0 171.5 35T790-430l-84 84q-44-44-102-69t-124-25q-66 0-124 25t-102 69ZM84-516 0-600q92-94 215-147t265-53q142 0 265 53t215 147l-84 84q-77-77-178.5-120.5T480-680q-116 0-217.5 43.5T84-516Z"/>
                            </svg>
                            <div>
                                <h2 class="title">Connect Using Wi-Fi</h2>
                                <p class="subtitle">Connect to your ESP32's IP address</p>
                            </div>
                        </div>
                  
                        <!-- Expandable Section -->
                        <div class="details hidden flex gap-3 items-center">
                            <input type="text" id="wifi-ip" placeholder="Enter ESP32 IP" 
                                   class="input rounded-lg mt-2 py-2 px-3 h-[40px]" 
                                   onclick="event.stopPropagation();">
                            <button onclick="event.stopPropagation(); connectToESP32('wifi')" 
                                    class="btn">Connect</button>
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
                    @auth
                    <div id="api-card" class="connection-card group relative" onclick="expandCard('api')">
                        <div class="icon-text">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24">
                                <path d="M22 9V7h-2V5a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2h2v-2h-2v-2h2v-2h-2V9h2zm-4 10H4V5h14v14zM6 13h5v4H6zm6-6h4v3h-4zM6 7h5v5H6zm10 7h2v2h-2z"/>
                            </svg>
                            <div>
                                <h2 class="title">Connect Using API</h2>
                                <p class="subtitle">Your personal API key for robot control</p>
                            </div>
                        </div>
                  
                        <!-- Expandable Section -->
                        <div class="details hidden">
                            <div class="flex flex-col gap-3">
                                <div class="flex items-center gap-2">
                                    <input type="text" id="api-key" readonly
                                           class="input rounded-lg py-2 px-3 h-[40px] bg-gray-50" 
                                           onclick="event.stopPropagation();">
                                    <button onclick="event.stopPropagation(); copyApiKey()" 
                                            class="btn">Copy</button>
                                </div>
                                <div class="flex items-center gap-2 justify-between">
                                    <button id="regenerate-api" onclick="event.stopPropagation(); regenerateApiKey()"
                                            class="btn bg-green-600">Regenerate Key</button>
                                    <span id="next-reset" class="text-sm text-gray-500"></span>
                                </div>
                                <button onclick="event.stopPropagation(); connectWithApiKey()" 
                                        class="btn w-full">Connect Using API Key</button>
                            </div>
                        </div>
                  
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
                    @endauth
                </div>
                  
                @include('plugins.partials.control')
            </div>
        </div>
    </div>
    @vite(['resources/js/robot-control.js'])
    <script>
        let currentConnection = null;
        let ws = null;

        window.addEventListener("DOMContentLoaded", () => {
            ['wifi', 'api'].forEach(type => {
                document.getElementById(`${type}-overlay`).classList.add('hidden');
                document.getElementById(`${type}-spinner`).classList.add('hidden');
                document.getElementById(`${type}-success`).classList.add('hidden');
                document.getElementById(`${type}-disconnect`).classList.add('hidden');
            });

            if (document.getElementById('api-card')) {
                loadApiKey();
            }
        });

        function initWebSocket(ip) {
            ws = new WebSocket(`ws://${ip}:81`);
            
            ws.onopen = function() {
                console.log('Connected to ESP32 WebSocket');
            };
            
            ws.onmessage = function(evt) {
                const data = JSON.parse(evt.data);
                handleRobotResponse(data);
            };
            
            ws.onclose = function() {
                console.log('WebSocket connection closed');
                setTimeout(function() {
                    initWebSocket(ip);
                }, 2000);
            };
        }

        function handleRobotResponse(data) {
            if (data.type === 'sensor') {
                updateSensorDisplay(data);
            } else if (data.type === 'status') {
                updateRobotStatus(data);
            }
        }
        
        function expandCard(type) {
            const card = document.getElementById(`${type}-card`);
            const details = card.querySelector('.details');
            
            if (card.classList.contains('disabled')) return;
            
            // Collapse other cards
            ['wifi', 'api'].forEach((c) => {
                const otherCard = document.getElementById(`${c}-card`);
                const otherDetails = otherCard.querySelector('.details');
                if (c !== type) {
                    otherCard.classList.remove('expanded');
                    otherDetails.classList.add('hidden');
                }
            });
            
            // Toggle selected card
            const expanded = card.classList.toggle('expanded');
            details.classList.toggle('hidden', !expanded);
        }

        async function connectToESP32(method) {
            const overlay = document.getElementById(`${method}-overlay`);
            const spinner = document.getElementById(`${method}-spinner`);
            const success = document.getElementById(`${method}-success`);
            const disconnectBtn = document.getElementById(`${method}-disconnect`);
            const card = document.getElementById(`${method}-card`);

            overlay.classList.remove('hidden');
            spinner.classList.remove('hidden');
            success.classList.add('hidden');
            disconnectBtn.classList.add('hidden');

            try {
                let response;
                if (method === 'wifi') {
                    const ip = document.getElementById('wifi-ip').value.trim();
                    if (!ip.match(/^(\d{1,3}\.){3}\d{1,3}$/)) throw new Error("Invalid IP format");
                    response = await fetch(`http://${ip}/connect`);
                } else if (method === 'api') {
                    const apiKey = document.getElementById('api-key').value.trim();
                    if (!apiKey) throw new Error("API key is required");
                    response = await fetch('/api/robot/connect', {
                        headers: {
                            'Authorization': `Bearer ${apiKey}`,
                            'Accept': 'application/json'
                        }
                    });
                }

                if (!response.ok) throw new Error("Connection failed");

                spinner.classList.add('hidden');
                success.classList.remove('hidden');
                disconnectBtn.classList.remove('hidden');

                currentConnection = method;
                
                // Disable other card
                const other = method === 'wifi' ? 'api' : 'wifi';
                document.getElementById(`${other}-card`).classList.add('disabled');

            } catch (err) {
                alert("Connection failed: " + err.message);
                overlay.classList.add('hidden');
            }
        }

        function disconnect(method) {
            const overlay = document.getElementById(`${method}-overlay`);
            const spinner = document.getElementById(`${method}-spinner`);
            const success = document.getElementById(`${method}-success`);
            const disconnectBtn = document.getElementById(`${method}-disconnect`);
            const input = document.getElementById(`${method}-${method === 'wifi' ? 'ip' : 'key'}`);

            overlay.classList.add('hidden');
            spinner.classList.add('hidden');
            success.classList.add('hidden');
            disconnectBtn.classList.add('hidden');

            if (input) {
                input.style.display = 'block';
                input.value = '';
            }

            const other = method === 'wifi' ? 'api' : 'wifi';
            document.getElementById(`${other}-card`).classList.remove('disabled');

            currentConnection = null;
        }

        async function loadApiKey() {
            try {
                const response = await fetch('/api/robot/key');
                const data = await response.json();
                
                document.getElementById('api-key').value = data.api_key || 'No API key generated';
                
                const regenerateBtn = document.getElementById('regenerate-api');
                const nextResetSpan = document.getElementById('next-reset');
                
                if (!data.can_reset) {
                    regenerateBtn.disabled = true;
                    regenerateBtn.classList.add('opacity-50');
                    nextResetSpan.textContent = `Next reset available: ${new Date(data.next_reset).toLocaleDateString()}`;
                } else {
                    regenerateBtn.disabled = false;
                    regenerateBtn.classList.remove('opacity-50');
                    nextResetSpan.textContent = '';
                }
            } catch (error) {
                console.error('Failed to load API key:', error);
            }
        }

        async function regenerateApiKey() {
            try {
                const response = await fetch('/api/robot/generate-key', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                if (data.error) {
                    alert(data.error);
                    return;
                }
                
                await loadApiKey();
            } catch (error) {
                alert('Failed to regenerate API key');
            }
        }

        function copyApiKey() {
            const apiKeyInput = document.getElementById('api-key');
            apiKeyInput.select();
            document.execCommand('copy');
            alert('API key copied to clipboard!');
        }

        async function connectWithApiKey() {
            const apiKey = document.getElementById('api-key').value;
            if (!apiKey || apiKey === 'No API key generated') {
                alert('Please generate an API key first');
                return;
            }
            
            connectToESP32('api');
        }
    </script>
</x-app-layout>