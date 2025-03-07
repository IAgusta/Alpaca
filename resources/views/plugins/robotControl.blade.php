<x-plugins-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Plugins - Robot Controller') }}
            <a href="/documentation" class="ml-3" id="documentation-link">
                <x-secondary-button> {{ __('Documentation') }}</x-secondary-button>
            </a>           
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- IP Address Form -->
                <div class="p-6 text-gray-900 flex justify-center items-center">
                    <form id="ip-form" class="w-10 max-w-md">
                        <x-input-label for="ip-address" :value="__('IP Address :')" />
                        <div class="flex flex-row gap-2 mt-2">
                            <x-text-input id="ip-address" class="block w-full" type="text" name="ip-address" placeholder="Ex. 192.100.1.1" required autofocus />
                            <x-primary-button id="connect-button" type="button" onclick="connectToESP32()">
                                {{ __('Connect') }}
                            </x-primary-button>
                        </div>
                        <p id="connection-status" class="text-sm text-gray-600 mt-2 hidden"></p>
                    </form>
                </div>

                <!-- Divider -->
                <div class="border-b border-gray-300 my-4"></div>

                <!-- Robot Controller -->
                <div id="robot-controls" class="flex flex-col items-center gap-4 py-6 opacity-50 pointer-events-none">
                    <h3 class="text-lg font-semibold">{{ __('Robot Controller') }}</h3>

                    <!-- Forward Button -->
                    <div class="flex justify-center w-full">
                        <x-secondary-button id="forward-button">{{ __('Maju') }}</x-secondary-button>
                    </div>

                    <!-- Left, Stop, Right Buttons -->
                    <div class="flex justify-center w-full gap-4">
                        <x-secondary-button id="left-button">{{ __('Kiri') }}</x-secondary-button>
                        <x-danger-button id="stop-button">{{ __('Berhenti') }}</x-danger-button>
                        <x-secondary-button id="right-button">{{ __('Kanan') }}</x-secondary-button>
                    </div>

                    <!-- Reverse Button -->
                    <div class="flex justify-center w-full">
                        <x-secondary-button id="backward-button">{{ __('Mundur') }}</x-secondary-button>
                    </div>
                </div>

                <!-- Motor Speed Slider -->
                <div class="flex justify-center items-center flex-col gap-4 py-6">
                    <h3 class="text-lg font-semibold">{{ __('Motor Speed') }}</h3>
                    <input type="range" min="0" max="100" step="25" id="motorSlider" class="w-64" oninput="updateMotorSpeed(this.value)" value="0" />
                    <p>Motor Speed: <span id="motorSpeed">0</span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let esp32IP = null;

        // Function to connect to ESP32
        function connectToESP32() {
            const ipAddress = document.getElementById('ip-address').value;
            const connectButton = document.getElementById('connect-button');
            const connectionStatus = document.getElementById('connection-status');
            const robotControls = document.getElementById('robot-controls');

            if (!ipAddress) {
                connectionStatus.textContent = "Please enter a valid IP address.";
                connectionStatus.classList.remove('hidden', 'text-green-600');
                connectionStatus.classList.add('text-red-600');
                return;
            }

            // Simulate connecting to ESP32
            connectButton.disabled = true;
            connectButton.textContent = "Connecting...";

            setTimeout(() => {
                esp32IP = ipAddress;
                connectButton.textContent = "Connected";
                connectButton.classList.add('bg-green-600', 'hover:bg-green-700');
                connectionStatus.textContent = `Connected to ${ipAddress}`;
                connectionStatus.classList.remove('hidden', 'text-red-600');
                connectionStatus.classList.add('text-green-600');

                // Enable robot controls
                robotControls.classList.remove('opacity-50', 'pointer-events-none');
            }, 2000); // Simulate 2 seconds delay
        }

        // Function to send commands to ESP32
        function sendCommand(command) {
            if (!esp32IP) {
                alert("Please connect to the ESP32 first.");
                return;
            }

            // Send command to ESP32 via HTTP request
            fetch(`http://${esp32IP}/${command}`, { method: 'GET' })
                .then(response => {
                    if (response.ok) {
                        console.log(`Command sent: ${command}`);
                    } else {
                        console.error(`Failed to send command: ${command}`);
                    }
                })
                .catch(error => {
                    console.error(`Error sending command: ${error}`);
                });
        }

        // Function to update motor speed
        function updateMotorSpeed(value) {
            if (!esp32IP) {
                alert("Please connect to the ESP32 first.");
                return;
            }

            document.getElementById('motorSpeed').textContent = value;
            fetch(`http://${esp32IP}/speed?value=${value}`, { method: 'GET' })
                .then(response => {
                    if (response.ok) {
                        console.log(`Motor speed set to ${value}`);
                    } else {
                        console.error(`Failed to set motor speed: ${value}`);
                    }
                })
                .catch(error => {
                    console.error(`Error setting motor speed: ${error}`);
                });
        }

        // Attach event listeners to robot control buttons
        document.getElementById('forward-button').addEventListener('click', () => sendCommand('forward'));
        document.getElementById('left-button').addEventListener('click', () => sendCommand('left'));
        document.getElementById('stop-button').addEventListener('click', () => sendCommand('stop'));
        document.getElementById('right-button').addEventListener('click', () => sendCommand('right'));
        document.getElementById('backward-button').addEventListener('click', () => sendCommand('reverse'));
    </script>
</x-plugins-layout>