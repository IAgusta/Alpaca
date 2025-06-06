<x-guest-layout>
    @section('title', 'Verification to ' . config('app.name'))
    <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-10 mb-3">Verifikasi Akun Anda</h2>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-200">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    @if (session('status') == 'verified')
        <div id="verification-modal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75">
            <div class="bg-white p-6 rounded shadow-lg text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-check-lg mx-auto mb-4" viewBox="0 0 16 16">
                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                </svg>
                <p class="text-lg font-medium text-gray-900">{{ __('Your Email Verification is Complete') }}</p>
                <p class="text-sm text-gray-600 mt-2">{{ __('Thank You For Sign Up') }}</p>
            </div>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = "{{ route('login') }}";
            }, 3000);
        </script>
    @else
        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-primary-button>
                        {{ __('Resend Verification Email') }}
                    </x-primary-button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    @endif
</x-guest-layout>
