<x-plugins-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Plugins - Robot Controller') }}
            <a href="/documentation-esp32" class="ml-3" id="documentation-link">
                <x-secondary-button> {{ __('Documentation') }}</x-secondary-button>
            </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- IP Address Form -->
                <div class="p-6 text-gray-900 flex justify-center items-center">
                    <form id="ip-form" class="w-8xl max-w-md">
                        <x-input-label for="ip-address" :value="__('IP Address :')" />
                        <div class="flex flex-row gap-2 mt-2">
                            <x-text-input id="ip-address" class="block w-full" type="text" name="ip-address" placeholder="Masukan IP Address ESP32/8266" required autofocus />
                            <x-primary-button id="connect-button" type="button">
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
                    <x-secondary-button id="forward-button">{{ __('Maju') }}</x-secondary-button>
                    <div class="flex justify-center gap-4">
                        <x-secondary-button id="left-button">{{ __('Kiri') }}</x-secondary-button>
                        <x-danger-button id="stop-button">{{ __('Berhenti') }}</x-danger-button>
                        <x-secondary-button id="right-button">{{ __('Kanan') }}</x-secondary-button>
                    </div>
                    <x-secondary-button id="backward-button">{{ __('Mundur') }}</x-secondary-button>
                </div>

                <!-- Motor Speed Slider -->
                <div class="flex justify-center items-center flex-col gap-4 py-6">
                    <h3 class="text-lg font-semibold">{{ __('Motor Speed') }}</h3>
                    <input type="range" min="0" max="100" step="1" id="motorSlider" class="w-64" value="0" />
                    <p>Motor Speed: <span id="motorSpeed">0</span></p>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/robot-control.js'])
</x-plugins-layout>