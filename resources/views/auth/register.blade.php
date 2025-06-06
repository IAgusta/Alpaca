<x-guest-layout>
    @section('title', 'Register to ' . config('app.name'))
    <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-10 mb-3">Buat Akun Barumu</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- First & Last Name -->
        <div class="grid grid-cols-2 gap-4">
            <!-- First Name -->
            <div>
                <x-input-label class="mb-3" for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div>
                <x-input-label class="mb-3" for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label class="mb-3" for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4 relative"
            x-data="{
                username: '{{ old('username') }}',
                startedTyping: false,
                rules: {
                    length: false,
                    pattern: false
                },
                validateUsername() {
                    this.rules.length = this.username.length >= 6 && this.username.length <= 16;
                    this.rules.pattern = /^[a-z0-9._]+$/.test(this.username);
                }
            }"
            x-init="validateUsername()">
            <x-input-label class="mb-3" for="username" :value="__('Username')" />
        
            <!-- Username input -->
            <input
                x-model="username"
                x-on:input="
                    startedTyping = true;
                    if (username.length > 16) {
                        username = username.slice(0, 16);
                    }
                    validateUsername();
                "
                x-on:focus="document.getElementById('popover-username').classList.remove('invisible', 'opacity-0')"
                x-on:blur="document.getElementById('popover-username').classList.add('invisible', 'opacity-0')"
                id="username"
                name="username"
                type="text"
                class="text-sm rounded-lg block w-full p-2.5 bg-gray-50 text-gray-900
                    dark:bg-gray-700 dark:text-white dark:placeholder-gray-400
                    focus:ring-2 focus:outline-none transition duration-200
                    border
                    focus:ring-blue-500 focus:border-blue-500
                    dark:focus:ring-blue-500 dark:focus:border-blue-500"
                :class="startedTyping && (!rules.length || !rules.pattern)
                    ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500'
                    : 'border-gray-300'"
                required
            />
        
            <!-- Guidelines popover -->
            <div
                id="popover-username"
                class="absolute z-10 invisible opacity-0 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm w-72 p-3 mt-2 text-sm text-gray-500
                    dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400"
            >
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Username Guidelines</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li :class="rules.length ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400'">
                        Between 6 to 16 characters
                    </li>
                    <li :class="rules.pattern ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400'">
                        Only lowercase letters, numbers, dot (.), underscore (_)
                    </li>
                </ul>
                <div data-popper-arrow></div>
            </div>
        
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Birth Date -->
        <div class="mt-4">
            <x-input-label class="mb-3" for="birth_date" :value="__('Birth Date (Optional)')" />
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                    </svg>
                </div>
                <input 
                    id="birth_date" 
                    type="text" 
                    name="birth_date" 
                    value="{{ old('birth_date') }}"
                    data-datepicker
                    data-datepicker-buttons
                    data-datepicker-autoselect-today
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    placeholder="Select date"
                >
            </div>
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
        </div>

        <!-- Phone (Optional) -->
        <div class="mt-4" x-data="{ phone: '{{ old('phone') }}'.replace('+62', '') }">
            <x-input-label class="mb-3" for="phone" :value="__('Phone (Optional)')" />

            <div class="relative flex items-stretch">
                <!-- Static +62 prefix -->
                <span class="flex items-center px-3 text-sm bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg dark:bg-gray-700 dark:border-gray-600
                    text-gray-700 dark:text-white"
                    style="min-height: 42px;"
                >
                    +62
                </span>
            
                <!-- Input -->
                <input
                    id="phone"
                    name="visible_phone"
                    x-model="phone"
                    x-on:input="phone = phone.replace(/[^0-9]/g, '')"
                    class="rounded-r-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm block w-full p-2.5 placeholder-gray-400
                        dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-500"
                    type="text"
                    placeholder="8123XXXXXXX"
                />
            
                <!-- Hidden actual phone -->
                <input type="hidden" name="phone" :value="phone.trim() !== '' ? '+62' + phone : ''">
            </div>

            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 relative">
            <x-input-label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" :value="__('Password')" />

            <input
                data-popover-target="popover-password"
                data-popover-placement="bottom"
                type="password"
                id="password"
                name="password"
                required
                autocomplete="new-password"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

            <!-- Password Strength Popover -->
            <div data-popover id="popover-password" role="tooltip"
                class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-xs opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                <div class="p-3 space-y-2">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Must have at least 8 characters</h3>
                    <div class="grid grid-cols-4 gap-2" id="strength-bars">
                        <div class="h-1 bg-gray-200 dark:bg-gray-600"></div>
                        <div class="h-1 bg-gray-200 dark:bg-gray-600"></div>
                        <div class="h-1 bg-gray-200 dark:bg-gray-600"></div>
                        <div class="h-1 bg-gray-200 dark:bg-gray-600"></div>
                    </div>
                    <p>It’s better to have:</p>
                    <ul>
                        <li class="flex items-center mb-1" id="case-check">
                            <svg class="w-3.5 h-3.5 me-2 text-gray-300 dark:text-gray-400" fill="none" viewBox="0 0 16 12"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/></svg>
                            Upper & lower case letters
                        </li>
                        <li class="flex items-center mb-1" id="symbol-check">
                            <svg class="w-3.5 h-3.5 me-2 text-gray-300 dark:text-gray-400" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                            A symbol or Number (0-9,#$&)
                        </li>
                        <li class="flex items-center" id="length-check">
                            <svg class="w-3.5 h-3.5 me-2 text-gray-300 dark:text-gray-400" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                            A longer password (min. 8 chars.)
                        </li>
                    </ul>
                </div>
                <div data-popper-arrow></div>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" :value="__('Confirm Password')" />

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-indigo-600 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Sudah Punya Akun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    <!-- Required Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/datepicker.min.js"></script>
    <script>
        // Initialize datepicker with proper options
        document.addEventListener('DOMContentLoaded', function() {
            new Datepicker(document.getElementById('birth_date'), {
                // Optional configuration
                format: 'yyyy-mm-dd', // Match your DB date format
                autohide: true, // Auto-close after selection
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const password = document.getElementById('password');
            const strengthBars = document.querySelectorAll('#strength-bars div');
            const checks = {
                case: document.getElementById('case-check'),
                symbol: document.getElementById('symbol-check'),
                length: document.getElementById('length-check')
            };
        
            function updateCheck(element, passed) {
                const svg = element.querySelector('svg');
                if (passed) {
                    svg.classList.remove('text-gray-300', 'dark:text-gray-400');
                    svg.classList.add('text-green-400', 'dark:text-green-500');
                } else {
                    svg.classList.remove('text-green-400', 'dark:text-green-500');
                    svg.classList.add('text-gray-300', 'dark:text-gray-400');
                }
            }
        
            password.addEventListener('input', () => {
                const val = password.value;
                let score = 0;
        
                const hasUpper = /[A-Z]/.test(val);
                const hasLower = /[a-z]/.test(val);
                const hasSymbol = /[\W_]/.test(val);
                const hasNumber = /\d/.test(val);
                const isLong = val.length >= 8;
                const isMin = val.length >= 1;
        
                if (hasUpper && hasLower) score++;
                if (hasSymbol || hasNumber) score++;
                if (isLong) score++;
                if (isMin) score++;
        
                strengthBars.forEach((bar, i) => {
                    bar.className = `h-1 ${i < score ? 'bg-orange-300 dark:bg-orange-400' : 'bg-gray-200 dark:bg-gray-600'}`;
                });
        
                updateCheck(checks.case, hasUpper && hasLower);
                updateCheck(checks.symbol, hasSymbol || hasNumber);
                updateCheck(checks.length, isLong);
            });
        });
    </script>     
</x-guest-layout>
