<div id="social-input-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Your Social Links
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="social-input-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                @php
                    $socialMedia = json_decode($user->details->social_media ?? '{}', true);
                @endphp
            
                @foreach (['facebook', 'instagram', 'x', 'linkedin', 'youtube'] as $platform)
                    <div class="social-link-container" data-platform="{{ $platform }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="{{ asset('icons/' . $platform . '.svg') }}" alt="{{ $platform }} icon" class="w-5 h-5 me-2">
                                <span class="font-medium capitalize">{{ $platform }}</span>
                            </div>
                            <button type="button" class="add-link-btn {{ isset($socialMedia[$platform]) ? 'hidden' : '' }}">
                                <span class="material-symbols-outlined">add_link</span>
                            </button>
                        </div>
                        <div class="link-input-container {{ isset($socialMedia[$platform]) ? 'hidden' : '' }} mt-2">
                            <div class="flex">
                                <input type="url" class="social-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="https://{{ $platform }}.com/username">
                                <button type="button" class="save-link-btn text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-r-lg text-sm px-3 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Save
                                </button>
                            </div>
                        </div>
                        <div class="link-display-container {{ isset($socialMedia[$platform]) ? '' : 'hidden' }} mt-2">
                            <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-600 p-2 rounded">
                                <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $socialMedia[$platform] ?? '' }}</span>
                                <button type="button" class="remove-link-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    <span class="material-symbols-outlined">link_off</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button id="save-social-links" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
            </div>
        </div>
    </div>
</div>