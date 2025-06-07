<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ __('Social Media Profile Information') }}
            @if (session('status') === 'social-media-updated')
            <x-input-success
                :messages="__('Profile Account Link Changed.')"/>
            @endif
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your social media profile information to keep your connections informed.") }}
        </p>
    </header>

    <form id="social-media-form" method="post" action="{{ route('profile.update.link') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('PATCH')
        <input type="hidden" name="social_media" id="social-media-input" value="{{ json_encode($user->details->social_media ?? []) }}">
    
        <div class="flex flex-col gap-4">
            <!-- Social Media Links Section --> 
            @foreach (['facebook', 'instagram', 'x' => 'Twitter', 'linkedin', 'youtube', 'github'] as $key => $platform)
                @php
                    $platformName = is_int($key) ? ucfirst($platform) : $platform;
                    $platformKey = is_int($key) ? $platform : $key;
                    $currentLink = $user->details->social_media[$platformKey] ?? ''; // Fetch the current link from the database
                @endphp
                
                <div x-data="{
                    showInput: {{ $currentLink ? 'true' : 'false' }},
                    link: '{{ $currentLink }}',
                    isLoading: false,
                    saveLink() {
                        this.isLoading = true;
                        const socialMediaInput = document.getElementById('social-media-input');
                        const socialLinks = JSON.parse(socialMediaInput.value || '{}');
                        if (this.link.trim()) {
                            socialLinks['{{ $platformKey }}'] = this.link.trim();
                            socialMediaInput.value = JSON.stringify(socialLinks);
                        }
                        setTimeout(() => {
                            this.isLoading = false;
                            this.showInput = false;
                        }, 800);
                    }
                }" 
                @mouseenter="if(!link) showInput = true" 
                @mouseleave="setTimeout(() => { if(!link) showInput = false }, 200)"
                class="group flex items-center gap-4 p-3 rounded-lg dark:hover:bg-gray-700 hover:bg-gray-50 transition-colors">
                    
                    <!-- Platform icon -->
                    <div class="relative w-8 h-8 flex items-center justify-center">
                        <img src="{{ asset('icons/' . $platformKey . '.svg') }}" 
                             alt="{{ $platformName }} icon" 
                             class="w-full h-full transition-transform group-hover:scale-110">
                        
                        <!-- Loading indicator (hidden by default) -->
                        <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center">
                            <div class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>
                    
                    <!-- Platform name (always visible) -->
                    <span class="font-medium text-gray-700 dark:text-gray-200 min-w-[80px]">{{ $platformName }}</span>
                    
                    <!-- Input field (shown on hover when empty or always when has link) -->
                    <div x-show="showInput || link" x-transition class="flex-1 flex items-center gap-2">
                        <div class="flex-1 flex items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-sm px-3 py-1.5 border border-r-0 border-gray-300 dark:border-slate-500 rounded-l-md bg-gray-50 dark:bg-gray-700">
                                https://{{ $platformKey }}.com/
                            </span>
                            <input type="text" name="social[{{ $platformKey }}]" 
                                   x-model="link"
                                   placeholder="your-username"
                                   @input="link = $event.target.value.startsWith('https://{{ $platformKey }}.com/') ? $event.target.value : 'https://{{ $platformKey }}.com/' + $event.target.value"
                                   class="border-gray-300 dark:bg-gray-600 dark:border-slate-500 dark:placeholder:text-gray-400 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-r-md shadow-sm flex-1 px-3 py-1.5 text-sm">
                        </div>
                        
                        <!-- Save indicator (shown when input is focused or has value) -->
                        <button type="button" @click="saveLink(); console.log('Temporary storage:', JSON.parse(document.getElementById('social-media-input').value));" 
                                x-show="link" 
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Save
                        </button>
                    </div>
                    
                    <!-- Default state (shown when no link exists) -->
                    <div x-show="!link && !showInput" class="flex-1">
                        <span class="text-sm text-gray-400">Not connected</span>
                        <span class="material-symbols-outlined text-sm text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" height="12px" viewBox="0 -960 960 960" width="12px" class="dark:text-white" fillcolor="currentColor">
                                <path d="m770-302-60-62q40-11 65-42.5t25-73.5q0-50-35-85t-85-35H520v-80h160q83 0 141.5 58.5T880-480q0 57-29.5 105T770-302ZM634-440l-80-80h86v80h-6ZM792-56 56-792l56-56 736 736-56 56ZM440-280H280q-83 0-141.5-58.5T80-480q0-69 42-123t108-71l74 74h-24q-50 0-85 35t-35 85q0 50 35 85t85 35h160v80ZM320-440v-80h65l79 80H320Z"/>
                            </svg>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    
        <div class="flex items-center justify-end gap-4 mt-6 pt-4 border-t border-gray-200">
            <x-primary-button>{{ __('Save All') }}</x-primary-button>
        </div>
    </form>

    <style>
                /* Smooth transitions */
        [x-cloak] { display: none !important; }

        /* Loading spinner animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Hover effects */
        .hover\:bg-gray-50:hover {
            background-color: #f9fafb;
        }

        .transition-colors {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .transition-transform {
            transition-property: transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>

    <!-- Social media form content -->
    @vite('resources/js/profile/added-social-link.js')
</section>