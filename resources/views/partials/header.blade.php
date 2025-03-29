<nav x-data="{ open: false, loading: false }" class="bg-white border-b border-gray-100 fixed top-0 left-0 w-full z-10">
    <!-- Loading Bar -->
    <div x-show="loading" class="fixed top-0 left-0 w-full h-1 bg-blue-500 z-50" x-transition></div>

    <!-- Primary Navigation Menu -->
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left Section: Logo & Navigation -->
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2" @click="loading = true">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    <span class="text-gray-800 font-semibold">Alpaca</span>
                </a>
            </div>

        <!-- Right Section: Login | Dark Mode | Social Media -->
        <div class="hidden sm:flex items-center space-x-4">
            <!-- Login Button -->
            <a href="/dashboard" class="flex bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600 transition">
                @auth
                    {{ __('Dashboard') }}
                @else
                    {{ __('Login') }}
                @endauth
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="25" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                    <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                </svg>
            </a>
            
            <!-- Social Media Icons -->
            <div class="flex items-center gap-3">
                <a href="https://github.com/IAgusta/Alpaca" target="_blank">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg" width="30" height="30" alt="GitHub">
                </a>
                <a href="https://discord.gg/ExzAJfgE" target="_blank">
                    <img src="https://cdn.prod.website-files.com/6257adef93867e50d84d30e2/62fddf0fde45a8baedcc7ee5_847541504914fd33810e70a0ea73177e%20(2)-1.png" width="30" height="30" alt="Discord">
                </a>
                <a href="https://trakteer.id/eiko_hachiichi/tip" target="_blank">
                    <img src="https://trakteer.id/favicon/favicon-32x32.png?id=c697d5d9c36c92b18d6f" width="30" height="30" alt="Trakteer">
                </a>
            </div>
        </div>

            <!-- Mobile Menu Button -->
            <div class="sm:hidden">
                <button @click="open = !open" class="p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')" @click="loading = true">
                {{ __('About Us') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('news')" :active="request()->routeIs('news')" @click="loading = true">
                {{ __('Our News') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Login & Social Media -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
            <div class="flex items-center space-x-3 px-4">
                <!-- Profile Image -->
                <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('storage/profiles/default-profile.png') }}" 
                    alt="Profile Picture" 
                    class="w-10 h-10 rounded-full border border-gray-300 shadow-sm">
                
                <!-- User Info -->
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                </div>
                <!-- Profile Link -->
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" @click="loading = true">
                    {{ __('Back To Dashboard') }}
                </x-responsive-nav-link>

                <!-- Log Out -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit(); loading = true;">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            @else
                <div class="px-4" style="cursor: default;">
                    <div class="font-medium text-base text-gray-800">{{ "Belum Login" }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ "Silahkan login dengan menekan tombol login atau register" }}</div>
                </div>
                <!-- Login Link -->
                <x-responsive-nav-link :href="route('login')" @click="loading = true">
                    {{ __('Login') }}
                </x-responsive-nav-link>

                <!-- Register Link -->
                <x-responsive-nav-link :href="route('register')" @click="loading = true">
                    {{ __('Register') }}
                </x-responsive-nav-link>
            @endauth
        </div>
    </div>
</nav>