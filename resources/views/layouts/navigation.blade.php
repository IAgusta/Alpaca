<nav x-data="{ open: false, pluginsOpen: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' || auth()->user()->role === 'teach' ? route('admin.dashboard') : route('user.dashboard')) : route('home') }}" 
                       class="flex items-center space-x-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        <span class="text-gray-800 font-semibold">Alpaca</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    @auth
                        <!-- Dashboard Link -->
                        <x-nav-link :href="auth()->user()->role === 'admin' || auth()->user()->role === 'teach' ? route('admin.dashboard') : route('user.dashboard')" 
                            :active="auth()->user()->role === 'admin' || auth()->user()->role === 'teach' ? request()->routeIs('admin.dashboard') : request()->routeIs('user.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <!-- Courses Link -->
                        <x-nav-link :href="auth()->user()->role === 'admin' || auth()->user()->role === 'teach' ? route('admin.courses.index') : route('user.course')" 
                            :active="auth()->user()->role === 'admin' || auth()->user()->role === 'teach' ? request()->routeIs('admin.courses.index') : request()->routeIs('user.course')">
                            {{ __('Courses') }}
                        </x-nav-link>

                        <!-- Plugins Dropdown -->
                        <div class="relative">
                            <button @click="pluginsOpen = !pluginsOpen" 
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                {{ __('Plugins') }}
                                <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Plugins Dropdown Content -->
                            <div x-show="pluginsOpen" @click.away="pluginsOpen = false" 
                                class="absolute left-0 top-full z-20 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <!-- Robot Control Link -->
                                    <x-dropdown-link :href="route('plugins.robotControl')" 
                                        :active="request()->routeIs('plugins.robotControl')">
                                        {{ __('Robot Control') }}
                                    </x-dropdown-link>

                                    <!-- Monitoring Link -->
                                    <x-dropdown-link :href="route('plugins.monitoring')" 
                                        :active="request()->routeIs('plugins.monitoring')">
                                        {{ __('Monitoring') }}
                                    </x-dropdown-link>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Robot Control Link -->
                        <x-nav-link :href="route('plugins.robotControl')" :active="request()->routeIs('plugins.robotControl')">
                            {{ __('Robot Control') }}
                        </x-nav-link>

                        <!-- Monitoring Link -->
                        <x-nav-link :href="route('plugins.monitoring')" :active="request()->routeIs('plugins.monitoring')">
                            {{ __('Monitoring') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Right Side of Navigation -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Profile Link -->
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Log Out -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Belum Login Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>Belum Login</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Login Link -->
                            <x-dropdown-link :href="route('login')">
                                {{ __('Login') }}
                            </x-dropdown-link>

                            <!-- Register Link -->
                            <x-dropdown-link :href="route('register')">
                                {{ __('Register') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <!-- Hamburger Menu for Mobile -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-0.5">
            @auth
                <!-- Dashboard Link -->
                <x-responsive-nav-link :href="auth()->user()->role === 'admin' || auth()->user()->role === 'teach' ? route('admin.dashboard') : route('user.dashboard')" 
                    :active="auth()->user()->role === 'admin' || auth()->user()->role === 'teach' ? request()->routeIs('admin.dashboard') : request()->routeIs('user.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                <!-- Courses Link -->
                <x-responsive-nav-link :href="auth()->user()->role === 'admin' || auth()->user()->role === 'teach' ? route('admin.courses.index') : route('user.course')" 
                    :active="auth()->user()->role === 'admin' || auth()->user()->role === 'teach' ? request()->routeIs('admin.courses.index') : request()->routeIs('user.course')">
                    {{ __('Courses') }}
                </x-responsive-nav-link>

                <!-- Robot Control Link -->
                <x-responsive-nav-link :href="route('plugins.robotControl')" :active="request()->routeIs('plugins.robotControl')">
                    {{ __('Robot Control') }}
                </x-responsive-nav-link>

                <!-- Monitoring Link -->
                <x-responsive-nav-link :href="route('plugins.monitoring')" :active="request()->routeIs('plugins.monitoring')">
                    {{ __('Monitoring') }}
                </x-responsive-nav-link>
            @else
                <!-- Robot Control Link -->
                <x-responsive-nav-link :href="route('plugins.robotControl')" :active="request()->routeIs('plugins.robotControl')">
                    {{ __('Robot Control') }}
                </x-responsive-nav-link>

                <!-- Monitoring Link -->
                <x-responsive-nav-link :href="route('plugins.monitoring')" :active="request()->routeIs('plugins.monitoring')">
                    {{ __('Monitoring') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Login Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="mt-3 space-y-1">
                @auth
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <!-- Profile Link -->
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Log Out -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    <div class="px-4" style="cursor: default;">
                        <div class="font-medium text-base text-gray-800">{{ "Belum Login" }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ "Silahkan login dengan menekan tombol login atau register" }}</div>
                    </div>
                    <!-- Login Link -->
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Login') }}
                    </x-responsive-nav-link>

                    <!-- Register Link -->
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                @endauth
            </div>
        </div>
    </div>
</nav>