<div class="max-w-3xl mx-auto rounded-2xl p-2 lg:p-8">
    @if (!$robot || !$robot->isPublic)
        <div class="dark:bg-gray-900 rounded-xl shadow-lg p-6 max-w-md mx-auto">
            <div class="text-center">
                @if (!$robot)
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-4 text-gray-400" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="M700-480q-25 0-42.5-17.5T640-540q0-25 17.5-42.5T700-600q25 0 42.5 17.5T760-540q0 25-17.5 42.5T700-480Zm-334 0Zm-86 120v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80ZM160-240q-33 0-56.5-23.5T80-320v-320q0-34 24-57.5t58-23.5h77l81 81H160v320h366L55-791l57-57 736 736-57 57-185-185H160Zm720-80q0 26-14 46t-37 29l-29-29v-366H434l-80-80h446q33 0 56.5 23.5T880-640v320ZM617-457Z"/>
                    </svg>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mb-3">
                        🚫 The user is not using any controller yet.
                    </p>

                    <a href="{{ route('plugins.robotControl') }}" class="inline-block mt-2">
                        <button class="px-5 py-2 bg-blue-600 hover:bg-blue-500 transition-colors text-white rounded-lg shadow-md text-sm font-semibold">
                            Learn About Robot Controllers
                        </button>
                    </a>
                @else
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="currentColor" viewBox="0 -960 960 960">
                        <path d="M720-240q25 0 42.5-17.5T780-300q0-25-17.5-42.5T720-360q-25 0-42.5 17.5T660-300q0 25 17.5 42.5T720-240Zm0 120q30 0 56-14t43-39q-23-14-48-20.5t-51-6.5q-26 0-51 6.5T621-173q17 25 43 39t56 14ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM490-80H240q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v52q-18-6-37.5-9t-42.5-3v-40H240v400h212q8 24 16 41.5T490-80Zm230 40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM240-560v400-400Z"/>
                    </svg>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mb-3">
                        🔒 This user's robot configuration is private
                    </p>
                    <p class="text-xs text-gray-600 dark:text-gray-500">
                        The user has chosen to keep their robot configuration private.
                    </p>
                @endif
            </div>
        </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Left Column -->
        <div class="space-y-6">
            <!-- Microprocessor -->
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 -960 960 960" fill="#255290">
                        <path d="M360-360v-240h240v240H360Zm80-80h80v-80h-80v80Zm-80 320v-80h-80q-33 0-56.5-23.5T200-280v-80h-80v-80h80v-80h-80v-80h80v-80q0-33 23.5-56.5T280-760h80v-80h80v80h80v-80h80v80h80q33 0 56.5 23.5T760-680v80h80v80h-80v80h80v80h-80v80q0 33-23.5 56.5T680-200h-80v80h-80v-80h-80v80h-80Zm320-160v-400H280v400h400ZM480-480Z"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Microprocessor</h3>
                </div>
                <p class="text-base text-gray-900 dark:text-white">{{ $robot->controller }}</p>
            </div>

            <!-- Robot Image -->
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-6 h-6" fill="#634FA2">
                        <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Zm140-360q25 0 42.5-17.5T400-620q0-25-17.5-42.5T340-680q-25 0-42.5 17.5T280-620q0 25 17.5 42.5T340-560Z"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Robot Image</h3>
                </div>
                @if(isset($robot->robot_image))
                    <img src="{{ asset('storage/' . $robot->robot_image) }}" alt="Robot Image" class="w-full h-48 object-cover rounded-lg">
                @else
                    <div class="w-full h-48 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-400">
                        <svg class="w-10 h-10 text-gray-200 dark:text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                            <path d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z"/>
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Last Time Used -->
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-6 h-6" fill="#B89230" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 8v4l3 3"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Last Time Used</h3>
                </div>
                <p class="text-base text-gray-900 dark:text-white">{{ $robot->robot->updated_at->diffForHumans() }}</p>
            </div>

            <!-- Components -->
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 -960 960 960" fill="#75FB4C">
                        <path d="M240-500v-220h220v220H240Zm0 260v-220h220v220H240Zm260-260v-220h220v220H500Zm0 260v-220h220v220H500ZM320-580h60v-60h-60v60Zm260 0h60v-60h-60v60ZM320-320h60v-60h-60v60Zm260 0h60v-60h-60v60ZM380-580Zm200 0Zm0 200Zm-200 0ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Z"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Components Used</h3>
                </div>

                @php
                    $components = is_array($robot->components) ? $robot->components : json_decode($robot->components, true);
                @endphp

                @foreach($components as $type => $pins)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                        <h3 class="font-medium text-gray-900 dark:text-white mb-3">{{ $type }}</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @php
                                $pinArray = explode(',', $pins);
                                $pinConfig = [
                                    'Servo' => ['Data'],
                                    'Ultrasonic Sensor' => ['Trig', 'Echo'],
                                    'Motor Driver' => ['IN1', 'IN2', 'IN3', 'IN4', 'ENA', 'ENB'],
                                    'LCD' => ['SDA', 'SCL'],
                                    'Push Button' => ['Data'],
                                    'Infrared Sensor' => ['Data'],
                                    'DFPlayer' => ['RX', 'TX']
                                ];
                            @endphp
                            @foreach($pinConfig[$type] ?? [] as $index => $pinName)
                                @if(isset($pinArray[$index]))
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $pinName }}</label>
                                        <input type="text" value="{{ $pinArray[$index] }}" 
                                               class="bg-gray-100 dark:bg-gray-800 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:border-gray-600 dark:text-white" 
                                               readonly />
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>