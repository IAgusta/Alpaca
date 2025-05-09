<x-app-layout>
    @section('title', 'Controller - '. config('app.name'))
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                  
                    <!-- Bluetooth Card -->
                    <div id="bluetooth-card" class="connection-card group relative" onclick="expandCard('bluetooth')">
                        <div class="icon-text">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 -960 960 960">
                                <path d="M440-80v-304L256-200l-56-56 224-224-224-224 56-56 184 184v-304h40l228 228-172 172 172 172L480-80h-40Zm80-496 76-76-76-74v150Zm0 342 76-74-76-76v150Z"/>
                            </svg>
                            <div>
                                <h2 class="title">Connect Using Bluetooth</h2>
                                <p class="subtitle">Pair with ESP32 via Bluetooth</p>
                            </div>
                        </div>
                  
                        <!-- Expandable Section -->
                        <div class="details hidden">
                            <button onclick="event.stopPropagation(); connectToESP32('bluetooth')" class="btn">Scan & Connect</button>
                        </div>
                  
                        <!-- Overlay with Spinner and Success -->
                        <div id="bluetooth-overlay" class="overlay absolute inset-0 bg-white/80 flex flex-col items-center justify-center z-10 hidden">
                            <!-- Spinner -->
                            <div id="bluetooth-spinner" class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-opacity-50 mb-4"></div>
                    
                            <!-- Success Animation -->
                            <div id="bluetooth-success" class="hidden text-green-600 flex flex-col items-center">
                                <svg class="w-16 h-16 text-green-500 animate-bounce" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="mt-2 text-lg font-semibold">Connection Successful</span>
                            </div>
                    
                            <!-- Disconnect Button -->
                            <button id="bluetooth-disconnect" onclick="disconnect('bluetooth'); event.stopPropagation()" class="mt-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded hidden">
                            Disconnect
                            </button>
                        </div>
                    </div>
                </div>
                  
        
                <div class="grid grid-cols-2 gap-4 p-4">
                    <!-- Line Follower -->
                    <div class="border border-gray-400 p-5 rounded-lg shadow-lg flex flex-col items-center">
                        <div class="flex flex-row items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32" viewBox="0 -960 960 960">
                                <path d="M440-80v-120H160v-80h640v80H520v120h-80Zm-80-420q17 0 28.5-11.5T400-540q0-17-11.5-28.5T360-580q-17 0-28.5 11.5T320-540q0 17 11.5 28.5T360-500Zm240 0q17 0 28.5-11.5T640-540q0-17-11.5-28.5T600-580q-17 0-28.5 11.5T560-540q0 17 11.5 28.5T600-500ZM200-616l66-192q5-14 16.5-23t25.5-9h344q14 0 25.5 9t16.5 23l66 192v264q0 14-9 23t-23 9h-16q-14 0-23-9t-9-23v-48H280v48q0 14-9 23t-23 9h-16q-14 0-23-9t-9-23v-264Zm106-64h348l-28-80H334l-28 80Zm-26 80v120-120Zm0 120h400v-120H280v120Z"/>
                            </svg>
                            <div class="text-center mt-2">
                                <h2 class="text-black font-bold text-lg">Line Follower</h2>
                                <p class="text-gray-600">Make the Robot Follow the Line</p>
                            </div>
                        </div>
                    </div>
            
                    <!-- Wall Avoider -->
                    <div class="border border-gray-400 p-5 rounded-lg shadow-lg flex flex-col items-center">
                        <div class="flex flex-row items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32" viewBox="0 -960 960 960">
                                <path d="M160-240v-200 200ZM80-440l84-240q6-18 21.5-29t34.5-11h183q-3 20-3 40t3 40H234l-42 120h259q17 24 38 44.5t47 35.5H160v200h560v-163q21-3 41-9t39-15v307q0 17-11.5 28.5T760-80h-40q-17 0-28.5-11.5T680-120v-40H200v40q0 17-11.5 28.5T160-80h-40q-17 0-28.5-11.5T80-120v-320Zm540 160q25 0 42.5-17.5T680-340q0-25-17.5-42.5T620-400q-25 0-42.5 17.5T560-340q0 25 17.5 42.5T620-280Zm-360 0q25 0 42.5-17.5T320-340q0-25-17.5-42.5T260-400q-25 0-42.5 17.5T200-340q0 25 17.5 42.5T260-280Zm420-200q-83 0-141.5-58.5T480-680q0-82 58-141t142-59q83 0 141.5 58.5T880-680q0 83-58.5 141.5T680-480Zm-20-160h40v-160h-40v160Zm20 80q8 0 14-6t6-14q0-8-6-14t-14-6q-8 0-14 6t-6 14q0 8 6 14t14 6Z"/>
                            </svg>
                            <div class="text-center mt-2">
                                <h2 class="text-black font-bold text-lg">Wall Avoider</h2>
                                <p class="text-gray-600">Make the Robot Avoid Walls</p>
                            </div>
                        </div>
                    </div>
            
                    <!-- Manual Control -->
                    <div class="border border-gray-400 p-5 rounded-lg shadow-lg flex flex-col items-center">
                        <div class="flex flex-row items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32" viewBox="0 -960 960 960">
                                <path d="m272-440 208 120 208-120-168-97v137h-80v-137l-168 97Zm168-189v-17q-44-13-72-49.5T340-780q0-58 41-99t99-41q58 0 99 41t41 99q0 48-28 84.5T520-646v17l280 161q19 11 29.5 29.5T840-398v76q0 22-10.5 40.5T800-252L520-91q-19 11-40 11t-40-11L160-252q-19-11-29.5-29.5T120-322v-76q0-22 10.5-40.5T160-468l280-161Zm0 378L200-389v67l280 162 280-162v-67L520-251q-19 11-40 11t-40-11Zm40-469q25 0 42.5-17.5T540-780q0-25-17.5-42.5T480-840q-25 0-42.5 17.5T420-780q0 25 17.5 42.5T480-720Zm0 560Z"/>
                            </svg>
                            <div class="text-center mt-2">
                                <h2 class="text-black font-bold text-lg">Manual Control</h2>
                                <p class="text-gray-600">Control the Robot Manually by Yourself</p>
                            </div>
                        </div>
                    </div>
            
                    <!-- Coming Soon -->
                    <div class="border border-gray-400 p-5 rounded-lg shadow-lg flex flex-col items-center">
                        <div class="flex flex-row items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32" viewBox="0 -960 960 960">
                                <path d="M424-320q0-81 14.5-116.5T500-514q41-36 62.5-62.5T584-637q0-41-27.5-68T480-732q-51 0-77.5 31T365-638l-103-44q21-64 77-111t141-47q105 0 161.5 58.5T698-641q0 50-21.5 85.5T609-475q-49 47-59.5 71.5T539-320H424Zm56 240q-33 0-56.5-23.5T400-160q0-33 23.5-56.5T480-240q33 0 56.5 23.5T560-160q0 33-23.5 56.5T480-80Z"/>
                            </svg>
                            <div class="text-center mt-2">
                                <h2 class="text-black font-bold text-lg">Coming Soon</h2>
                                <p class="text-gray-600">More features are on the way!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/robot-control.js'])
    <script>
        let currentConnection = null;
        
        window.addEventListener("DOMContentLoaded", () => {
          ['wifi', 'bluetooth'].forEach(type => {
            document.getElementById(`${type}-overlay`).classList.add('hidden');
            document.getElementById(`${type}-spinner`).classList.add('hidden');
            document.getElementById(`${type}-success`).classList.add('hidden');
            document.getElementById(`${type}-disconnect`).classList.add('hidden');
          });
        });
        
        function expandCard(type) {
          const card = document.getElementById(`${type}-card`);
          const details = card.querySelector('.details');
        
          if (card.classList.contains('disabled')) return;
        
          // Collapse other cards
          ['wifi', 'bluetooth'].forEach((c) => {
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
          const input = document.getElementById(`${method}-ip`);
          const card = document.getElementById(`${method}-card`);
        
          // Reset state
          overlay.classList.remove('hidden');
          spinner.classList.remove('hidden');
          success.classList.add('hidden');
          disconnectBtn.classList.add('hidden');
        
          try {
            let url = '';
            if (method === 'wifi') {
              const ip = input.value.trim();
              if (!ip.match(/^(\d{1,3}\.){3}\d{1,3}$/)) throw new Error("Invalid IP format");
              url = `https://${ip}/connect`;
            } else {
              url = `/bluetooth-connect`; // Placeholder endpoint
            }
        
            const res = await fetch(url, { method: 'GET', mode: 'cors' });
            const data = await res.text();
        
            if (data.includes("success")) {
              spinner.classList.add('hidden');
              success.classList.remove('hidden');
              disconnectBtn.classList.remove('hidden');
              if (input) input.style.display = 'none';
              currentConnection = method;
        
              // Disable other card
              const other = method === 'wifi' ? 'bluetooth' : 'wifi';
              const otherCard = document.getElementById(`${other}-card`);
              otherCard.classList.add('disabled', 'opacity-50', 'pointer-events-none');
            } else {
              throw new Error("ESP32 did not respond with success.");
            }
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
          const input = document.getElementById(`${method}-ip`);
        
          // Reset states
          overlay.classList.add('hidden');
          spinner.classList.add('hidden');
          success.classList.add('hidden');
          disconnectBtn.classList.add('hidden');
        
          if (input) {
            input.style.display = 'block';
            input.value = '';
          }
        
          const other = method === 'wifi' ? 'bluetooth' : 'wifi';
          const otherCard = document.getElementById(`${other}-card`);
          otherCard.classList.remove('disabled', 'opacity-50', 'pointer-events-none');
        
          currentConnection = null;
        }
    </script>
</x-app-layout>