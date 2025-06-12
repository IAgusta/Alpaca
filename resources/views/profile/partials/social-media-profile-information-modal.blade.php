<section x-data x-init="() => {
    $nextTick(() => {
        if (window.initSocialMediaForm) window.initSocialMediaForm();
    });
}">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ __('Social Media Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your social media profile information to keep your connections informed.") }}
        </p>
    </header>

    <form id="social-media-form" method="post" action="{{ route('profile.update.link') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('PATCH')
        <input type="hidden" name="social_media" id="social-media-input" value="{{ json_encode($user->details->social_media ?? []) }}">

        <div class="flex flex-col gap-3 lg:items-start">
            @php
                use Illuminate\Support\Str;
                $platforms = [
                    'facebook' => [
                        'name' => 'Facebook',
                        'prefix' => 'https://facebook.com/',
                        'placeholder' => 'your.username or profile.php?id=...',
                        'allowRaw' => ['profile.php'],
                    ],
                    'instagram' => [
                        'name' => 'Instagram',
                        'prefix' => 'https://instagram.com/',
                        'placeholder' => 'your-username',
                    ],
                    'x' => [
                        'name' => 'X',
                        'prefix' => 'https://x.com/',
                        'placeholder' => 'your-username',
                    ],
                    'linkedin' => [
                        'name' => 'LinkedIn',
                        'prefix' => 'https://linkedin.com/in/',
                        'placeholder' => 'your-username',
                        'stripPrefix' => true,
                    ],
                    'youtube' => [
                        'name' => 'YouTube',
                        'prefix' => 'https://youtube.com/',
                        'placeholder' => '@your-channel',
                        'forceAt' => true,
                    ],
                    'github' => [
                        'name' => 'GitHub',
                        'prefix' => 'https://github.com/',
                        'placeholder' => 'your-username',
                    ],
                ];
            @endphp

            @foreach ($platforms as $platformKey => $platformInfo)
                @php
                    $currentLink = $user->details->social_media[$platformKey] ?? '';
                    $displayValue = $currentLink;
                    if (!empty($currentLink) && Str::startsWith($currentLink, $platformInfo['prefix'])) {
                        $displayValue = substr($currentLink, strlen($platformInfo['prefix']));
                    }
                    // For LinkedIn, strip /in/ prefix if present
                    if (($platformInfo['stripPrefix'] ?? false) && Str::startsWith($displayValue, 'in/')) {
                        $displayValue = substr($displayValue, 3);
                    }
                @endphp
                <div
                    x-data="{
                        showInput: {{ $currentLink ? 'true' : 'false' }},
                        rawInput: '{{ $displayValue }}',
                        isLoading: false,
                        getFullUrl(input) {
                            let val = input.trim();
                            let base = '{{ $platformInfo['prefix'] }}';

                            // Remove accidental full prefix for all
                            if (val.startsWith(base)) val = val.slice(base.length);

                            // LinkedIn: strip any pasted /in/ prefix
                            @if (!empty($platformInfo['stripPrefix']))
                                val = val.replace(/^(https:\/\/)?(www\.)?linkedin\.com\/in\//, '');
                                if (val.startsWith('in/')) val = val.slice(3);
                            @endif

                            // YouTube: force @ at start
                            @if (!empty($platformInfo['forceAt']))
                                if (!val.startsWith('@')) val = '@' + val.replace(/^@+/, '');
                            @endif

                            // Facebook: allow raw profile.php?id=...
                            @if (!empty($platformInfo['allowRaw']))
                                @foreach ($platformInfo['allowRaw'] as $raw)
                                    if (val.startsWith('{{ $raw }}')) return base + val;
                                @endforeach
                            @endif

                            // Remove leading @ for others
                            val = val.replace(/^@+/, '');

                            return base + val;
                        },
                        saveLink() {
                            this.isLoading = true;
                            const socialMediaInput = document.getElementById('social-media-input');
                            const socialLinks = JSON.parse(socialMediaInput.value || '{}');
                            socialLinks['{{ $platformKey }}'] = this.getFullUrl(this.rawInput);
                            socialMediaInput.value = JSON.stringify(socialLinks);
                            setTimeout(() => {
                                this.isLoading = false;
                                this.showInput = !!this.rawInput;
                            }, 800);
                        }
                    }"
                    @mouseenter="if(!rawInput) showInput = true"
                    @mouseleave="setTimeout(() => { if(!rawInput) showInput = false }, 200)"
                    class="group p-3 rounded-lg dark:hover:bg-gray-700 hover:bg-gray-50 transition-colors w-full"
                >
                    <div class="flex flex-col lg:flex-row items-start w-full lg:items-center gap-4 mb-2">
                        <div class="flex items-center gap-4 w-full lg:w-auto min-w-0">
                            <!-- Icon -->
                            <div class="relative w-8 h-8 flex items-center justify-center flex-shrink-0">
                                <img src="{{ asset('icons/' . $platformKey . '.svg') }}"
                                    alt="{{ $platformInfo['name'] }} icon"
                                    class="w-full h-full transition-transform group-hover:scale-110">

                                <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                                </div>
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-200 min-w-[80px] flex-shrink-0">{{ $platformInfo['name'] }}</span>
                            <div x-show="!rawInput && !showInput" class="flex-1 min-w-0">
                                <span class="text-sm text-gray-400 truncate block">Not connected</span>
                            </div>
                        </div>
                        <!-- Input field (inline on lg, below on mobile) -->
                        <div x-show="showInput || rawInput" x-transition class="w-full lg:flex-1 flex items-center pl-0 lg:pl-0 mt-2 lg:mt-0 min-w-0">
                            <span class="text-gray-500 dark:text-gray-400 text-sm px-3 py-1.5 border border-r-0 border-gray-300 dark:border-slate-500 rounded-l-md bg-gray-50 dark:bg-gray-700 flex-shrink-0 max-w-[50vw] lg:max-w-none overflow-hidden text-ellipsis">
                                {{ $platformInfo['prefix'] }}
                            </span>
                            <input
                                type="text"
                                :name="'social[' + '{{ $platformKey }}' + ']'"
                                x-model="rawInput"
                                :placeholder="'{{ $platformInfo['placeholder'] }}'"
                                @input="saveLink()"
                                @change="saveLink()"
                                class="border-gray-300 dark:bg-gray-600 dark:border-slate-500 dark:placeholder:text-gray-400 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-r-md shadow-sm flex-1 px-3 py-1.5 text-sm min-w-0 max-w-full"
                                style="overflow-x: auto;"
                                autocomplete="off"
                            >
                        </div>
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

        /* Prevent overflow on mobile */
        .group, .group * {
            box-sizing: border-box;
        }
        .group {
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden;
        }
        input[type="text"] {
            min-width: 0;
            max-width: 100%;
        }
        /* Responsive fix for input group */
        @media (max-width: 640px) {
            .group > div {
                flex-wrap: wrap;
            }
            .group input[type="text"] {
                width: 1px;
                min-width: 0;
                flex: 1 1 0%;
            }
        }
    </style>

    <!-- Social media form content -->
    @vite('resources/js/profile/added-social-link.js')
</section>