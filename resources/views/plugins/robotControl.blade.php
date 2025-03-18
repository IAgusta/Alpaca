<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Plugins - Robot Controller') }}
            <a href="{{ route('documentation')}}" class="ml-3" id="documentation-link">
                <x-secondary-button> {{ __('Documentation') }}</x-secondary-button>
            </a>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden">
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

                    <!-- Motor Speed Slider -->
                    <div class="flex justify-center items-center flex-col gap-4 py-6">
                        <h3 class="text-lg font-semibold">{{ __('Motor Speed') }}</h3>
                        <div class="flex gap-4">
                            <input type="range" min="0" max="100" step="10" id="motorSlider" class="w-64" value="0" />
                            <p>Speed: <span id="motorSpeed">0</span></p>
                        </div>
                    </div>

                    <x-secondary-button id="forward-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5"/>
                        </svg>
                    </x-secondary-button>
                    <div class="flex justify-center gap-4">
                        <x-secondary-button id="left-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                            </svg>
                        </x-secondary-button>
                        <x-danger-button id="stop-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-sign-stop" viewBox="0 0 16 16">
                                <path d="M3.16 10.08c-.931 0-1.447-.493-1.494-1.132h.653c.065.346.396.583.891.583.524 0 .83-.246.83-.62 0-.303-.203-.467-.637-.572l-.656-.164c-.61-.147-.978-.51-.978-1.078 0-.706.597-1.184 1.444-1.184.853 0 1.386.475 1.436 1.087h-.645c-.064-.32-.352-.542-.797-.542-.472 0-.77.246-.77.6 0 .261.196.437.553.522l.654.161c.673.164 1.06.487 1.06 1.11 0 .736-.574 1.228-1.544 1.228Zm3.427-3.51V10h-.665V6.57H4.753V6h3.006v.568H6.587Z"/>
                                <path fill-rule="evenodd" d="M11.045 7.73v.544c0 1.131-.636 1.805-1.661 1.805-1.026 0-1.664-.674-1.664-1.805V7.73c0-1.136.638-1.807 1.664-1.807s1.66.674 1.66 1.807Zm-.674.547v-.553c0-.827-.422-1.234-.987-1.234-.572 0-.99.407-.99 1.234v.553c0 .83.418 1.237.99 1.237.565 0 .987-.408.987-1.237m1.15-2.276h1.535c.82 0 1.316.55 1.316 1.292 0 .747-.501 1.289-1.321 1.289h-.865V10h-.665zm1.436 2.036c.463 0 .735-.272.735-.744s-.272-.741-.735-.741h-.774v1.485z"/>
                                <path fill-rule="evenodd" d="M4.893 0a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146A.5.5 0 0 0 11.107 0zM1 5.1 5.1 1h5.8L15 5.1v5.8L10.9 15H5.1L1 10.9z"/>
                            </svg>
                        </x-danger-button>
                        <x-secondary-button id="right-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                            </svg>
                        </x-secondary-button>
                    </div>
                    <x-secondary-button id="backward-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1"/>
                        </svg>
                    </x-secondary-button>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/robot-control.js'])
</x-app-layout>