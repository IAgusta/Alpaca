<div class="p-6 mb-7" x-data="{ 
    activeMode: null,
    isLineActive: false,
    isWallActive: false,
    globalSpeed: 50,
    avoidDistance: 25,
    init() {
        @if(Auth::check() && Auth::user()->robot?->api_key)
            window.robotConnection = {
                mode: 'api',
                key: '{{ Auth::user()->robot?->api_key }}',
                active: true
            };
            // Pre-select manual mode for API users
            this.activeMode = 'manual';
        @endif

        // Initialize speed and distance
        updateAllSpeedSliders(this.globalSpeed);
        updateDistanceSlider(this.avoidDistance);
    },
    sendCommand(command) {
        // Handle both movement and parameter commands
        if (window.sendCommand) {
            window.sendCommand(command);
        } else {
            console.error('Command handler not initialized');
        }
    }
}" class="p-4 mb-7">
    <div class="relative">
        <h2 class="text-xl font-bold text-center dark:text-white">Select Mode</h2>
        <p class="text-center text-gray-600 mb-6 dark:text-gray-300">Choose the job you want your robot to do:</p>
        <!-- Grid Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Line Follower Card -->
            <div x-show="!activeMode || activeMode === 'line'"
                :class="{ 
                    'col-span-full': activeMode === 'line'
                }" 
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300">
                <div class="p-4 cursor-pointer flex items-center justify-between" @click="activeMode = activeMode === 'line' ? null : 'line'">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-lg bg-green-300">
                            <img src="/icons/car.svg" class="w-8 h-8 invert" alt="Line Follower">
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg dark:text-white">Line Follower</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-200">Follow the line path automatically</p>
                        </div>
                    </div>
                    <!-- Toggle Switch -->
                    <label class="relative inline-flex items-center cursor-pointer" @click.stop>
                        <input type="checkbox" x-model="isLineActive" class="sr-only peer" 
                               @click.stop 
                               @change.stop="sendCommand('line:' + (isLineActive ? 'on' : 'off'))">
                        <div class="w-14 h-8 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
                
                <!-- Expanded Content -->
                <div x-show="activeMode === 'line'" 
                     x-transition:enter="transition-all ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="p-4 border-t dark:border-gray-600">
                    <div class="space-y-4">
                        <div class="grid grid-cols-3 gap-4 dark:text-white">
                            <template x-for="sensor in ['Left', 'Center', 'Right']">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full transition-colors duration-300"
                                         :class="sensor === 'Center' ? 'bg-green-500' : 'bg-gray-300'"></div>
                                    <span class="text-sm mt-2" x-text="sensor"></span>
                                </div>
                            </template>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-200">Speed Control</span>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-200 w-16 text-right" x-text="globalSpeed + '%'"></span>
                            </div>
                            <div class="relative">
                                <div class="flex justify-between text-xs text-gray-400 px-1 mb-1">
                                    <span>0%</span>
                                    <span>25%</span>
                                    <span>50%</span>
                                    <span>75%</span>
                                    <span>100%</span>
                                </div>
                                <div class="absolute w-full h-2 flex justify-between items-center px-[2px] pointer-events-none">
                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                </div>
                                <input type="range" 
                                       x-model="globalSpeed" 
                                       @input="sendCommand('speed:' + globalSpeed)"
                                       class="w-full accent-green-600" 
                                       min="0" 
                                       max="100" 
                                       step="5">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wall Avoider Card -->
            <div x-show="!activeMode || activeMode === 'wall'"
                :class="{ 
                    'col-span-full': activeMode === 'wall'
                }" 
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300">
                <div class="p-4 cursor-pointer flex items-center justify-between" @click="activeMode = activeMode === 'wall' ? null : 'wall'">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-lg bg-blue-400">
                            <img src="/icons/sensor.svg" class="w-8 h-8 invert" alt="Wall Avoider">
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg dark:text-white">Wall Avoider</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-200">Navigate avoiding obstacles</p>
                        </div>
                    </div>
                    <!-- Toggle Switch -->
                    <label class="relative inline-flex items-center cursor-pointer" @click.stop>
                        <input type="checkbox" x-model="isWallActive" class="sr-only peer" 
                               @click.stop 
                               @change.stop="sendCommand('wall:' + (isWallActive ? 'on' : 'off'))">
                        <div class="w-14 h-8 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                
                <!-- Expanded Content -->
                <div x-show="activeMode === 'wall'" 
                     x-transition:enter="transition-all ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="p-4 border-t dark:border-gray-600">
                    <div class="space-y-6">
                        <!-- Radar Display -->
                        <div class="relative">
                            <div class="radar-display w-48 h-48 mx-auto rounded-full border-4 border-blue-200 relative">
                                <div class="radar-sweep absolute rounded-full inset-0 origin-center animate-spin duration-2000"></div>
                                <!-- Centered robot-object -->
                                <div class="robot-object absolute w-3 h-3 bg-blue-500 rounded-full"
                                     style="top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
                                <div class="hidden radar-object absolute w-3 h-3 bg-red-500 rounded-full"
                                     style="top: 30%; left: 70%;"></div>
                            </div>
                            <div class="mt-4 text-center text-sm text-gray-600 dark:text-gray-200">
                                Distance: <span class="font-medium">45cm</span>
                            </div>
                        </div>

                        <!-- Speed Control -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-200">Speed Control</span>
                                <span class="text-sm font-medium text-gray-600 w-16 text-right dark:text-gray-200" x-text="globalSpeed + '%'"></span>
                            </div>
                            <div class="relative">
                                <div class="flex justify-between text-xs text-gray-400 px-1 mb-1">
                                    <span>0%</span>
                                    <span>25%</span>
                                    <span>50%</span>
                                    <span>75%</span>
                                    <span>100%</span>
                                </div>
                                <div class="absolute w-full h-2 flex justify-between items-center px-[2px] pointer-events-none">
                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                </div>
                                <input type="range" 
                                       x-model="globalSpeed" 
                                       @input="sendCommand('speed:' + globalSpeed)"
                                       class="w-full accent-blue-600" 
                                       min="0" 
                                       max="100" 
                                       step="5">
                            </div>
                        </div>

                        <!-- Distance Control -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-200">Avoid Distance</span>
                                <span class="text-sm font-medium text-gray-600 w-16 text-right dark:text-gray-200" x-text="avoidDistance + 'cm'"></span>
                            </div>

                            <div class="relative">
                                <!-- Labels -->
                                <div class="relative h-4 mb-1">
                                    <template x-for="(val, i) in [10,15,20,25,30,35,40,45,50]">
                                        <span class="absolute text-xs text-gray-400"
                                            :style="'left: ' + (i * 12.5) + '%; transform: translateX(-50%)'">
                                            <span x-text="val + 'cm'"></span>
                                        </span>
                                    </template>
                                </div>

                                <!-- Dots -->
                                <div class="absolute w-full h-2 flex justify-between items-center px-[2px] pointer-events-none">
                                    <template x-for="(val, i) in 9">
                                        <div class="absolute w-1 h-1 bg-gray-300 rounded-full"
                                            :style="'left: ' + (i * 12.5) + '%; transform: translateX(-50%)'">
                                        </div>
                                    </template>
                                </div>

                                <!-- Range Input -->
                                <input type="range"
                                    x-model="avoidDistance"
                                    @click="event.stopPropagation()"
                                    @input="updateDistanceSlider($event.target.value); sendCommand('distance:' + avoidDistance)"
                                    class="w-full distance-slider"
                                    min="10"
                                    max="50"
                                    step="5">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manual Control Card -->
            <div x-show="!activeMode || activeMode === 'manual'"
                :class="{ 
                    'col-span-full': activeMode === 'manual'
                }" 
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300">
                <div class="p-4 cursor-pointer flex items-center justify-between" @click="activeMode = activeMode === 'manual' ? null : 'manual'">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-lg bg-purple-400">
                            <img src="/icons/control.svg" class="w-8 h-8 invert" alt="Manual Control">
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg dark:text-white">Manual Control</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-200">Direct control over movement</p>
                        </div>
                    </div>
                </div>
                
                <!-- Expanded Content -->
                <div x-show="activeMode === 'manual'" 
                     x-transition:enter="transition-all ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="p-4 border-t dark:border-gray-600">
                    <!-- Speed Control -->
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-200">Speed Control</span>
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-200 w-16 text-right" x-text="globalSpeed + '%'"></span>
                        </div>
                        <div class="relative">
                            <div class="flex justify-between text-xs text-gray-400 px-1 mb-1">
                                <span>0%</span>
                                <span>25%</span>
                                <span>50%</span>
                                <span>75%</span>
                                <span>100%</span>
                            </div>
                            <div class="absolute w-full h-2 flex justify-between items-center px-[2px] pointer-events-none">
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                            </div>
                            <input type="range" 
                                   x-model="globalSpeed" 
                                   @input="sendCommand('speed:' + globalSpeed)"
                                   class="w-full accent-purple-600" 
                                   min="0" 
                                   max="100" 
                                   step="5">
                        </div>
                    </div>

                    <!-- Direction Controls -->
                    <div class="grid grid-cols-3 gap-4 lg:gap-7 max-w-xs mx-auto p-7">
                        <!-- Left Column -->
                        <div class="flex justify-start items-center">
                            <button
                                x-data="{ isPressed: false }"
                                @mousedown="if (!isPressed) { isPressed = true; sendCommand('left'); }"
                                @mouseup="if (isPressed) { sendCommand('stop'); isPressed = false; }"
                                @mouseleave="if (isPressed) { sendCommand('stop'); isPressed = false; }"
                                @touchstart.prevent="if (!isPressed) { isPressed = true; sendCommand('left'); }"
                                @touchend="if (isPressed) { sendCommand('stop'); isPressed = false; }"
                                class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-md transition duration-200 hover:scale-105 active:scale-95 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
                                </svg>
                            </button>
                        </div>

                        <!-- Center Column -->
                        <div class="flex flex-col gap-4 justify-center items-center">
                            <!-- Forward -->
                            <button
                                x-data="{ isPressed: false }"
                                @mousedown="if (!isPressed) { isPressed = true; sendCommand('forward'); }"
                                @mouseup="if (isPressed) { sendCommand('stop'); isPressed = false; }"
                                @mouseleave="if (isPressed) { sendCommand('stop'); isPressed = false; }"
                                @touchstart.prevent="if (!isPressed) { isPressed = true; sendCommand('forward'); }"
                                @touchend="if (isPressed) { sendCommand('stop'); isPressed = false; }"
                                class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-full shadow-md transition duration-200 hover:scale-105 active:scale-95 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5l7 7m-7-7l-7 7" />
                                </svg>
                            </button>

                            <!-- Stop -->
                            <button @click="event.stopPropagation(); sendCommand('stop')"
                                class="bg-red-500 hover:bg-red-600 text-white p-4 rounded-full shadow-md transition duration-200 hover:scale-105 active:scale-95 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16L16 8M8 8l8 8" />
                                </svg>
                            </button>

                            <!-- Backward -->
                            <button
                                x-data="{ isPressed: false }"
                                @mousedown="if (!isPressed) { isPressed = true; sendCommand('backward'); }"
                                @mouseup="if (isPressed) { sendCommand('stop'); isPressed = false; }"
                                @mouseleave="if (isPressed) { sendCommand('stop'); isPressed = false; }"
                                @touchstart.prevent="if (!isPressed) { isPressed = true; sendCommand('backward'); }"
                                @touchend="if (isPressed) { sendCommand('stop'); isPressed = false; }"
                                class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-full shadow-md transition duration-200 hover:scale-105 active:scale-95 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l7-7m-7 7l-7-7" />
                                </svg>
                            </button>
                        </div>

                        <!-- Right Column -->
                        <div class="flex justify-end items-center">
                            <button
                                x-data="{ isPressed: false }"
                                @mousedown="if (!isPressed) { isPressed = true; sendCommand('right'); }"
                                @mouseup="if (isPressed) { sendCommand('stop'); isPressed = false; }"
                                @mouseleave="if (isPressed) { sendCommand('stop'); isPressed = false; }"
                                @touchstart.prevent="if (!isPressed) { isPressed = true; sendCommand('right'); }"
                                @touchend="if (isPressed) { sendCommand('stop'); isPressed = false; }"
                                class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-md transition duration-200 hover:scale-105 active:scale-95 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coming Soon Card -->
            <div x-show="!activeMode || activeMode === 'soon'"
                :class="{ 
                    'col-span-full': activeMode === 'soon'
                }" 
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300">
                <div class="p-4 flex cursor-pointer items-center justify-between" @click="activeMode = activeMode === 'soon' ? null : 'soon'">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-lg bg-gray-300">
                            <img src="/icons/question.svg" class="w-8 h-8 invert" alt="Coming Soon">
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg dark:text-white">Coming Soon</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-200">Exciting new features are on the way!</p>
                        </div>
                    </div>
                </div>
                
                <!-- Expanded Content -->
                <div x-show="activeMode === 'soon'" 
                     x-transition:enter="transition-all ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="p-4 border-t dark:border-gray-600">
                    <div class="p-6 text-center">
                        <svg class="w-24 h-24 mx-auto text-gray-400 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 100-16 8 8 0 000 16z"/>
                            <path d="M13 7h-2v6h2V7zm0 8h-2v2h2v-2z"/>
                        </svg>
                        <h3 class="mt-4 text-xl font-semibold text-gray-700 dark:text-gray-400">New Features Coming Soon!</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-200">We're working on exciting new features for you.</p>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            Have suggestions? Contact us at:
                            <a href="mailto:ikraamagusta91@gmail.com" 
                               class="text-blue-500 hover:text-blue-600 underline">
                                ikraamagusta91@gmail.com
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Minimized Mode Icons -->
        <div x-show="activeMode" 
             class="flex justify-center gap-4 mt-4 transition-all duration-300"
             x-transition:enter="transition-all ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0">
            <template x-for="mode in ['line', 'wall', 'manual', 'soon']" :key="mode">
                <button @click="activeMode = mode"
                        x-show="activeMode !== mode"
                        class="p-3 rounded-full transition-all duration-300 hover:scale-110"
                        :class="{
                            'bg-green-100': mode === 'line',
                            'bg-blue-100': mode === 'wall',
                            'bg-purple-100': mode === 'manual',
                            'bg-gray-100': mode === 'soon'
                        }">
                    <img :src="`/icons/${
                        mode === 'line' ? 'car' :
                        mode === 'wall' ? 'sensor' :
                        mode === 'manual' ? 'control' : 'question'
                    }.svg`" class="w-6 h-6" alt="">
                </button>
            </template>
        </div>
    </div>

    <style>
        .radar-sweep {
            background: conic-gradient(from 0deg, rgba(59, 130, 246, 0) 0deg, rgba(59, 130, 246, 0.1) 90deg, rgba(59, 130, 246, 0.1) 180deg, rgba(59, 130, 246, 0) 360deg);
            animation: sweep 2s linear infinite;
        }

        @keyframes sweep {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .dot {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Add smooth transition for minimized row */
        .flex-wrap {
            transition: all 0.3s ease-in-out;
        }
        
        /* Add hover effect for minimized icons */
        button:hover img {
            filter: drop-shadow(0 4px 3px rgb(0 0 0 / 0.07));
        }

        /* Add new styles for toggle switches */
        .peer:checked ~ .peer-checked\:bg-green-600 {
            background-color: #10B981;
        }

        .peer:checked ~ .peer-checked\:bg-blue-600 {
            background-color: #3B82F6;
        }

        /* Add smooth transitions for card scaling */
        .scale-90 {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        /* Improve hover effects */
        .scale-90:hover {
            opacity: 0.9;
            transform: scale(0.95);
        }

        /* Updated slider styles for better visibility */
        input[type="range"] {
            -webkit-appearance: none;
            @apply h-2 rounded-lg mt-1;
            background: linear-gradient(to right, currentColor 0%, currentColor var(--value-percent, 50%), #e5e7eb var(--value-percent, 50%), #e5e7eb 100%);
            transition: background 0.3s ease;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            @apply w-5 h-5 rounded-full bg-white shadow-md border-2 cursor-pointer relative;
            margin-top: -6px;
        }

        input[type="range"]::-webkit-slider-thumb::before {
            content: attr(value);
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            @apply text-xs font-medium;
        }

        input[type="range"].accent-purple-600 {
            color: #7c3aed;
        }

        input[type="range"].accent-blue-600 {
            color: #2563eb;
        }

        input[type="range"].accent-green-600 {
            color: #059669;
        }

        /* Add specific styles for distance slider */
        .distance-slider {
            -webkit-appearance: none;
            @apply h-2 rounded-lg mt-1;
            background: linear-gradient(to right, #3B82F6 0%, #3B82F6 var(--distance-percent, 28.5%), #e5e7eb var(--distance-percent, 28.5%), #e5e7eb 100%);
        }

        .distance-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            @apply w-5 h-5 rounded-full bg-white shadow-md border-2 border-blue-600 cursor-pointer relative;
            margin-top: -6px;
        }
    </style>

    <script>
        // Single script section at the end
        function updateAllSpeedSliders(value) {
            const percentage = value;
            document.querySelectorAll('input[type="range"]:not(.distance-slider)').forEach(input => {
                input.style.setProperty('--value-percent', `${percentage}%`);
                input.style.background = `linear-gradient(to right, ${input.classList.contains('accent-purple-600') ? '#7c3aed' : 
                                        input.classList.contains('accent-blue-600') ? '#2563eb' : 
                                        '#059669'} 0%, currentColor ${percentage}%, #e5e7eb ${percentage}%, #e5e7eb 100%)`;
            });
        }

        function updateDistanceSlider(value) {
            const min = 10;
            const max = 50;
            const percentage = ((value - min) / (max - min)) * 100;
            const sliders = document.querySelectorAll('.distance-slider');
            sliders.forEach(slider => {
                slider.style.setProperty('--distance-percent', `${percentage}%`);
                slider.style.background = `linear-gradient(to right, #3B82F6 0%, #3B82F6 ${percentage}%, #e5e7eb ${percentage}%, #e5e7eb 100%)`;
            });
        }

        // Initialize on both load and Alpine init
        function initializeSliders() {
            updateAllSpeedSliders(50);
            updateDistanceSlider(25);
        }

        document.addEventListener('input', (e) => {
            if (e.target.type === 'range') {
                if (e.target.classList.contains('distance-slider')) {
                    updateDistanceSlider(e.target.value);
                } else {
                    updateAllSpeedSliders(e.target.value);
                }
            }
        });

        window.addEventListener('load', initializeSliders);
        window.addEventListener('alpine:initialized', initializeSliders);
    </script>
</div>