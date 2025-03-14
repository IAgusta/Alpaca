<x-guest-layout>
    <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900 mt-10 mb-3">Masuk ke Akun Anda</h2>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label class="mb-3" for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <div class="text-sm/6 font-medium text-gray-900 flex justify-between mb-3">
            <x-input-label for="password" :value="__('Password')" />
            @if (Route::has('password.request'))
            <a class="underline text-sm text-indigo-600 hover:text-gray-900" href="{{ route('password.request') }}">
                {{ __('Lupa Password?') }}
            </a>
            @endif
            </div>
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col space-y-4 mt-4">
            <div class="text-center text-gray-600 justify-between flex items-center">
                <div>
                    <span class="text-sm text-gray-600">{{ 'Tidak Punya Akun?' }}</span>
                    <a href="{{ route('register') }}" class="underline text-sm text-indigo-600 hover:text-gray-900">
                        {{ 'Register' }}
                    </a>
                </div>
            </div>
        
            <div class="flex items-center justify-end">
                <x-primary-button>
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
