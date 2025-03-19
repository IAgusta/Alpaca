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

            <!-- Right Section: Login & Social Media -->
            <div class="hidden sm:flex items-center space-x-4">
                <!-- Login Button -->
                <a href="/dashboard" class="flex bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600 transition">
                    @auth
                        {{ __('Back To Dashboard') }}
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
                    <a href="https://trakteer.id/eiko_hachiichi/tip" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-cup-straw" viewBox="0 0 16 16">
                            <path d="M13.902.334a.5.5 0 0 1-.28.65l-2.254.902-.4 1.927c.376.095.715.215.972.367.228.135.56.396.56.82q0 .069-.011.132l-.962 9.068a1.28 1.28 0 0 1-.524.93c-.488.34-1.494.87-3.01.87s-2.522-.53-3.01-.87a1.28 1.28 0 0 1-.524-.93L3.51 5.132A1 1 0 0 1 3.5 5c0-.424.332-.685.56-.82.262-.154.607-.276.99-.372C5.824 3.614 6.867 3.5 8 3.5c.712 0 1.389.045 1.985.127l.464-2.215a.5.5 0 0 1 .303-.356l2.5-1a.5.5 0 0 1 .65.278M9.768 4.607A14 14 0 0 0 8 4.5c-1.076 0-2.033.11-2.707.278A3.3 3.3 0 0 0 4.645 5c.146.073.362.15.648.222C5.967 5.39 6.924 5.5 8 5.5c.571 0 1.109-.03 1.588-.085zm.292 1.756C9.445 6.45 8.742 6.5 8 6.5c-1.133 0-2.176-.114-2.95-.308a6 6 0 0 1-.435-.127l.838 8.03c.013.121.06.186.102.215.357.249 1.168.69 2.438.69s2.081-.441 2.438-.69c.042-.029.09-.094.102-.215l.852-8.03a6 6 0 0 1-.435.127 9 9 0 0 1-.89.17zM4.467 4.884s.003.002.005.006zm7.066 0-.005.006zM11.354 5a3 3 0 0 0-.604-.21l-.099.445.055-.013c.286-.072.502-.149.648-.222"/>
                        </svg>
                    </a>
                    <a href="https://github.com/IAgusta/Alpaca" target="_blank">
                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg" width="30" height="30" alt="GitHub">
                    </a>
                    <a href="https://discord.gg/ExzAJfgE" target="_blank">
                        <img src="https://img.icons8.com/?size=100&id=30888&format=png&color=000000" width="30" height="30" alt="Discord">
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
