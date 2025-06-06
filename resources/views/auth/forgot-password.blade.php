<x-guest-layout>
    @section('title', 'Forgot Your ' . config('app.name') . ' Account?')
    <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-10 mb-3">Lupa Kata Sandi?</h2>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-200">
        {{ __('Lupa kata sandi Anda? Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan tautan pengaturan ulang kata sandi melalui email yang memungkinkan Anda memilih kata sandi baru.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label class="mb-3" for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center mt-4 justify-between">
            <x-secondary-button onclick="window.history.back();">
                {{ __('Kembali') }}
            </x-secondary-button>

            <x-primary-button>
                {{ __('Kirim Email') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
