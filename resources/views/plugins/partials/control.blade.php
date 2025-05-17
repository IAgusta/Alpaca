<div x-data="{ expanded: null }" class="p-4">
    <!-- Card Template -->
    <div class="relative space-y-4">
        <template x-for="(card, index) in ['line', 'wall', 'manual', 'soon']" :key="card">
            <div :class="{
                    'w-full': expanded === card,
                    'w-[48%] inline-block': expanded !== card,
                    'hidden': expanded !== null && expanded !== card,
                    'mb-4': true
                }"
                class="border border-gray-400 p-5 rounded-lg shadow-lg transition-all duration-300 ease-in-out"
                :style="expanded === card ? 'transform-origin: top' : ''">

                <!-- Header Section -->
                <div class="flex flex-row items-center cursor-pointer" 
                     @click="expanded = expanded === card ? null : card">
                    
                    <!-- Local SVG Icons -->
                    <img :src="'/icons/' + {
                        'line': 'car.svg',
                        'wall': 'sensor.svg',
                        'manual': 'control.svg',
                        'soon': 'question.svg'
                    }[card]" 
                    class="w-12 h-12" fill="#000000"
                    :class="expanded === card ? 'opacity-80' : 'opacity-100'"
                    alt="">

                    <div class="ml-4">
                        <h2 class="text-black font-bold" :class="expanded === card ? 'text-xl' : 'text-lg'">
                            <span x-text="{
                                'line': 'Line Follower',
                                'wall': 'Wall Avoider',
                                'manual': 'Manual Control',
                                'soon': 'Coming Soon'
                            }[card]"></span>
                        </h2>
                        <p class="text-gray-600 text-sm" x-show="expanded === null" x-text="{
                            'line': 'Make the Robot Follow the Line',
                            'wall': 'Make the Robot Avoid Walls',
                            'manual': 'Control the Robot Manually',
                            'soon': 'New Features Coming Soon'
                        }[card]"></p>
                    </div>
                </div>

                <!-- Expandable Content -->
                <div x-show="expanded === card" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="mt-6">
                    
                    <!-- Line Follower Expanded -->
                    <template x-if="card === 'line'">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-lg font-semibold">Line Following Mode</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <template x-for="sensor in ['Left', 'Center', 'Right']" :key="sensor">
                                    <div class="text-center">
                                        <span class="block mb-2" x-text="sensor + ' Sensor'"></span>
                                        <div class="w-8 h-8 rounded-full bg-gray-200 mx-auto"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Wall Avoider Expanded -->
                    <template x-if="card === 'wall'">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-lg font-semibold">Wall Avoidance Mode</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            <div class="relative w-48 h-48 mx-auto bg-gray-100 rounded-full">
                                <div class="absolute inset-2 border-4 border-blue-400 rounded-full"></div>
                                <div class="absolute w-4 h-4 bg-green-500 rounded-full" style="top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
                            </div>
                        </div>
                    </template>

                    <!-- Manual Control Expanded -->
                    <template x-if="card === 'manual'">
                        <div class="grid grid-cols-3 gap-4 w-48 mx-auto">
                            <div></div>
                            <button class="p-4 bg-gray-200 rounded-lg hover:bg-gray-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l-7 7h4v7h6v-7h4l-7-7z"/>
                                </svg>
                            </button>
                            <div></div>
                            <button class="p-4 bg-gray-200 rounded-lg hover:bg-gray-300">
                                <svg class="w-8 h-8 rotate-[-90deg]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l-7 7h4v7h6v-7h4l-7-7z"/>
                                </svg>
                            </button>
                            <div></div>
                            <button class="p-4 bg-gray-200 rounded-lg hover:bg-gray-300">
                                <svg class="w-8 h-8 rotate-[90deg]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l-7 7h4v7h6v-7h4l-7-7z"/>
                                </svg>
                            </button>
                            <div></div>
                            <button class="p-4 bg-gray-200 rounded-lg hover:bg-gray-300 col-start-2">
                                <svg class="w-8 h-8 rotate-180" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l-7 7h4v7h6v-7h4l-7-7z"/>
                                </svg>
                            </button>
                        </div>
                    </template>

                    <!-- Coming Soon Expanded -->
                    <template x-if="card === 'soon'">
                        <div class="text-center text-gray-500 text-lg mt-4">
                            New Function Will Be Added Soon.
                        </div>
                    </template>

                </div>
            </div>
        </template>
    </div>
</div>
