<nav x-data="{ open: false, pluginsOpen: false, loading: false }" class="bg-white border-b border-gray-100 fixed top-0 left-0 w-full z-10">
    <!-- Loading Bar -->
    <div x-show="loading" class="fixed top-0 left-0 w-full h-1 bg-blue-500 z-50" x-transition></div>

    <!-- Primary Navigation Menu -->
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}"
                        class="flex items-center space-x-2" @click="loading = true">
                         <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                         <span class="text-gray-800 font-semibold">Alpaca</span>
                     </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    <!-- Dashboard Link -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" @click="loading = true">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Courses Link -->
                    <x-nav-link :href="auth()->user()->role === 'admin' || auth()->user()->role === 'trainer' || auth()->user()->role === 'owner' ? route('admin.courses.index') : route('user.course')" 
                        :active="auth()->user()->role === 'admin' || auth()->user()->role === 'trainer' || auth()->user()->role === 'owner' ? request()->routeIs('admin.courses.index') : request()->routeIs('user.course')" @click="loading = true">
                        {{ __('Courses') }}
                    </x-nav-link>

                    <!-- Admin Management User (For Admin and Owner Only)-->
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
                    <x-nav-link :href="route('admin.manage-user')" :active="request()->routeIs('admin.manage-user')" @click="loading = true">
                        {{ __('Manage User') }}
                    </x-nav-link>
                    @endif

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
                                    :active="request()->routeIs('plugins.robotControl')" @click="loading = true">
                                    {{ __('Robot Control') }}
                                </x-dropdown-link>

                                <!-- Monitoring Link -->
                                <x-dropdown-link :href="route('plugins.monitoring')" 
                                    :active="request()->routeIs('plugins.monitoring')" @click="loading = true">
                                    {{ __('Search User') }}
                                </x-dropdown-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side of Navigation -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Profile button -->
                <img id="avatarButton" type="button" 
                data-dropdown-toggle="userDropdown"
                data-dropdown-placement="bottom-start"
                data-dropdown-trigger="click"
                data-dropdown-show-classes="ring-2 ring-gray-300 dark:ring-gray-500"
                data-dropdown-hide-classes=""
                class="w-10 h-10 rounded-full cursor-pointer transition-all duration-200" 
                src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('storage/profiles/default-profile.png') }}" 
                alt="User dropdown">

                <!-- Dropdown menu -->
                <div id="userDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                    <!-- User info section - won't close dropdown when clicked -->
                    <div class="px-4 py-3 text-sm text-gray-900 dark:text-white" onclick="event.stopPropagation()">
                        <div class="truncate hover:whitespace-normal" title="{{ Auth::user()->name }}">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="font-medium truncate hover:whitespace-normal" title="{{ Auth::user()->email }}">
                            {{ Auth::user()->email }}
                        </div>
                    </div>
                    
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                        <!-- Profile Link -->
                        <li>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                {{ __('Profile') }}
                            </a>
                        </li>
                        
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">User Course</a>
                        </li>
                    </ul>
                    
                    <div class="py-1">
                        <!-- Log Out -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" 
                            onclick="event.preventDefault(); this.closest('form').submit();" 
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                {{ __('Log Out') }}
                            </a>
                        </form>
                    </div>
                </div>
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
            <!-- Dashboard Link -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" @click="loading = true">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Courses Link -->
            <x-responsive-nav-link :href="auth()->user()->role === 'admin' || auth()->user()->role === 'trainer' || auth()->user()->role === 'owner' ? route('admin.courses.index') : route('user.course')" 
                :active="auth()->user()->role === 'admin' || auth()->user()->role === 'trainer' || auth()->user()->role === 'owner' ? request()->routeIs('admin.courses.index') : request()->routeIs('user.course')" @click="loading = true">
                {{ __('Courses') }}
            </x-responsive-nav-link>

            <!-- Admin Management User (For Admin and Owner Only)-->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
            <x-responsive-nav-link :href="route('admin.manage-user')" :active="request()->routeIs('admin.manage-user')" @click="loading = true">
                {{ __('Manage User') }}
            </x-responsive-nav-link>
            @endif

            <!-- Robot Control Link -->
            <x-responsive-nav-link :href="route('plugins.robotControl')" :active="request()->routeIs('plugins.robotControl')" @click="loading = true">
                {{ __('Robot Control') }}
            </x-responsive-nav-link>

            <!-- Monitoring Link -->
            <x-responsive-nav-link :href="route('plugins.monitoring')" :active="request()->routeIs('plugins.monitoring')" @click="loading = true">
                {{ __('Search User') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Login Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="mt-3 space-y-1">
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
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" @click="loading = true">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Log Out -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit(); loading = true;">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>