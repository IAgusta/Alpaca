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

                    <x-nav-link :href="route('plugins.search-users')" :active="request()->routeIs('plugins.search-users')" @click="loading = true">
                        {{ __('Find User') }}
                    </x-nav-link>

                    <!-- Plugins Dropdown -->
                    <div class="relative">
                        <button @click="pluginsOpen = !pluginsOpen" 
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            {{ __('Tools') }}
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
                src="{{ Auth::user()->details->image ? asset('storage/' . (json_decode(Auth::user()->details->image, true)['profile'] ?? '')) : '' }}" 
                onerror="this.src='{{ asset('storage/profiles/default-profile.png') }}'" 
                alt="User dropdown">

                <!-- Dropdown menu -->
                <div id="userDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                    <!-- Banner and Profile Image Wrapper -->
                    <a href="{{ route('profile.index') }}" class="flex flex-col items-center relative">
                        <div class="relative w-full h-16">
                            <!-- Banner Image -->
                            <img id="banner" 
                            src="{{ Auth::user()->details->image ? asset('storage/' . (json_decode(Auth::user()->details->image, true)['banner'] ?? '')) : '' }}" 
                            onerror="this.src='{{ asset('storage/profiles/patterns.png') }}'" 
                                    class="w-full h-full object-cover" 
                                    alt="Banner Image">

                            <!-- Dark Overlay (30-40% opacity) -->
                            <div class="absolute inset-0 bg-black bg-opacity-5"></div>
                            
                            <!-- Profile Image - Fully Centered -->
                            <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <img id="profile-preview" 
                                src="{{ Auth::user()->details->image ? asset('storage/' . (json_decode(Auth::user()->details->image, true)['profile'] ?? '')) : '' }}" 
                                onerror="this.src='{{ asset('storage/profiles/default-profile.png') }}'" 
                                        class="w-8 h-8 border border-blue-500 rounded-full object-cover shadow-lg" 
                                        alt="Profile Image">
                            </div>
                        </div>
                    </a>
                    <!-- User info section - won't close dropdown when clicked -->
                    <div class="px-4 py-3 text-sm text-gray-900 dark:text-white flex flex-col items-center text-center" onclick="event.stopPropagation()">
                        <!-- User Name -->
                        <div class="truncate hover:whitespace-normal text-wrap" title="{{ Auth::user()->name }}">
                            {{ Auth::user()->name }}
                        </div>
                        <!-- User Role - Styled Differently -->
                        <div class="truncate hover:whitespace-normal bg-blue-500 text-white px-2 py-1 rounded-full text-xs mt-1" title="{{ Auth::user()->role }}">
                            {{ Str::ucfirst(Auth::user()->role)  }}
                        </div>
                    </div>
                    
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                        <!-- Profile Link -->
                        <li>
                            <a href="{{ route('profile.index') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                {{ __('Profile') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.course') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Library</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.courses.index') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">My Course</a>
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

            <!-- Find User Link -->
            <x-responsive-nav-link :href="route('plugins.search-users')" :active="request()->routeIs('plugins.search-users')" @click="loading = true">
                {{ __('Find User') }}
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
                <x-responsive-nav-link :href="route('profile.index')" :active="request()->routeIs('profile.index')" @click="loading = true">
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