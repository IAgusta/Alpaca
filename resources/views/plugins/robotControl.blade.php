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

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden">
                <div class="grid grid-cols-2 gap-4 p-4 mb-7">
                    <!-- Connect Using Wi-Fi -->
                    <div class="border border-gray-400 p-5 rounded-lg shadow-lg flex flex-col items-center">
                        <div class="flex flex-row items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32" viewBox="0 -960 960 960">
                                <path d="M480-120q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM254-346l-84-86q59-59 138.5-93.5T480-560q92 0 171.5 35T790-430l-84 84q-44-44-102-69t-124-25q-66 0-124 25t-102 69ZM84-516 0-600q92-94 215-147t265-53q142 0 265 53t215 147l-84 84q-77-77-178.5-120.5T480-680q-116 0-217.5 43.5T84-516Z"/>
                            </svg>
                            <div class="text-center mt-2">
                                <h2 class="text-black font-bold text-lg">Connect Using Wi-Fi</h2>
                                <p class="text-gray-600">Connect your device to the robot's Wi-Fi network.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Connect Using Bluetooth -->
                    <div class="border border-gray-400 p-5 rounded-lg shadow-lg flex flex-col items-center">
                        <div class="flex flex-row items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32" viewBox="0 -960 960 960">
                                <path d="M440-80v-304L256-200l-56-56 224-224-224-224 56-56 184 184v-304h40l228 228-172 172 172 172L480-80h-40Zm80-496 76-76-76-74v150Zm0 342 76-74-76-76v150Z"/>
                            </svg>
                            <div class="text-center mt-2">
                                <h2 class="text-black font-bold text-lg">Connect Using Bluetooth</h2>
                                <p class="text-gray-600">Connect your device via Bluetooth to control the robot.</p>
                            </div>
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
</x-app-layout>