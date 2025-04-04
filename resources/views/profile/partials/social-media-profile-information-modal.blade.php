<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Social Media Profile Information') }}
            @if (session('status') === 'social-media-updated')
            <x-input-success
                :messages="__('Profile Account Link Changed.')"/>
            @endif
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your other platform account links.") }}
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
                class="group flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    
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
                    <span class="font-medium text-gray-700 min-w-[80px]">{{ $platformName }}</span>
                    
                    <!-- Input field (shown on hover when empty or always when has link) -->
                    <div x-show="showInput || link" x-transition class="flex-1 flex items-center gap-2">
                        <input type="url" name="social[{{ $platformKey }}]" x-model="link" 
                               placeholder="https://{{ $platformKey }}.com/username"
                               class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-1.5 text-sm">
                        
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
                        <span class="material-symbols-outlined text-sm text-gray-400">link_off</span>
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