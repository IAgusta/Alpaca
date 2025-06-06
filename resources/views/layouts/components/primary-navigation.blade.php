    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Hamburger Menu -->
                <div class="lg:hidden flex items-center">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-white hover:text-gray-500 hover:bg-gray-100 dark:bg-gray-700 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}"
                        class="flex items-center space-x-2" @click="loading = true">
                         <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                         <span class="text-gray-800 dark:text-white font-semibold">Alpaca</span>
                     </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden lg:flex space-x-8 sm:-my-px sm:ms-10 items-center">
                    <!-- Dashboard Link -->
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" @click="loading = true" class="dark:text-gray-300 dark:hover:text-white">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Courses Link -->
                    <x-nav-link :href="auth()->check() && in_array(auth()->user()->role, ['admin', 'trainer', 'owner']) 
                        ? route('admin.courses.index') 
                        : route('user.course')" 
                        :active="request()->routeIs('admin.courses.index') || request()->routeIs('user.course')" 
                        @click="loading = true"
                        class="dark:text-gray-300 dark:hover:text-white">
                        {{ __('Courses') }}
                    </x-nav-link>

                    <x-nav-link :href="route('plugins.search-users')" :active="request()->routeIs('plugins.search-users')" @click="loading = true" class="dark:text-gray-300 dark:hover:text-white">
                        {{ __('Find User') }}
                    </x-nav-link>

                    <!-- Plugins Dropdown -->
                    <div class="relative">
                        <button @click="pluginsOpen = !pluginsOpen" 
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white focus:outline-none transition ease-in-out duration-150">
                            {{ __('Tools') }}
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Plugins Dropdown Content -->
                        <div x-show="pluginsOpen" @click.away="pluginsOpen = false" 
                            class="absolute left-0 top-full z-20 w-48 rounded-md shadow-lg dark:text-white bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <x-dropdown-link class="dark:text-white dark:hover:bg-gray-800" :href="route('plugins.robotControl')" 
                                    :active="request()->routeIs('plugins.robotControl')" @click="loading = true">
                                    {{ __('Robot Control') }}
                                </x-dropdown-link>
                            </div>
                        </div>
                    </div>

                    <x-nav-link :href="route('forum')" :active="request()->routeIs('forum')" @click="loading = true" class="dark:text-gray-300 dark:hover:text-white">
                        {{ __('Forums') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="flex items-center">
                @auth
                    <!-- Profile Dropdown (Visible on all screen sizes) -->
                    <div class="flex items-center">
                        <!-- Profile button -->
                        <img id="avatarButton" type="button" 
                        data-dropdown-toggle="userDropdown"
                        data-dropdown-placement="bottom-start"
                        data-dropdown-trigger="click"
                        data-dropdown-show-classes="ring-2 ring-gray-300 dark:ring-gray-500"
                        data-dropdown-hide-classes=""
                        class="w-10 h-10 rounded-full cursor-pointer transition-all duration-200  border-1 border-gray-400 dark:border-gray-800" 
                        src="{{ Auth::user()->details->image ? asset('storage/' . (json_decode(Auth::user()->details->image, true)['profile'] ?? '')) : '' }}" 
                        onerror="this.src='{{ asset('storage/profiles/default-profile.png') }}'" 
                        alt="User dropdown">

                        <!-- Dropdown menu -->
                        <div id="userDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                            <!-- Banner and Profile Image Wrapper -->
                            <a href="{{ route('profile.index') }}" class="flex flex-col items-center relative">
                                <div class="relative w-full h-16">
                                    <!-- Banner Image -->
                                    <img id="banner" 
                                    src="{{ Auth::user()->details->image ? asset('storage/' . (json_decode(Auth::user()->details->image, true)['banner'] ?? '')) : '' }}" 
                                    onerror="this.src='{{ asset('storage/profiles/patterns.png') }}'" 
                                            class="w-full h-full object-cover rounded-t-lg" 
                                            alt="Banner Image">

                                    <!-- Dark Overlay (30-40% opacity) -->
                                    <div class="absolute inset-0 bg-black bg-opacity-5"></div>
                                    
                                    <!-- Profile Image - Fully Centered -->
                                    <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                        <img id="profile-preview" 
                                        src="{{ Auth::user()->details->image ? asset('storage/' . (json_decode(Auth::user()->details->image, true)['profile'] ?? '')) : '' }}" 
                                        onerror="this.src='{{ asset('storage/profiles/default-profile.png') }}'" 
                                                class="w-8 h-8 border border-1 border-gray-400 dark:border-gray-800 rounded-full object-cover shadow-lg" 
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
                                    @if (auth()->user()->role != 'user')
                                        <a href="{{ route('course.feed') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Library</a>
                                    @else
                                        <a href="{{ route('user.course') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Library</a>
                                    @endif
                                </li>
                                <li>
                                    <a href="{{ route('admin.courses.index') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">My Course</a>
                                </li>
                            </ul>
                            
                            <div class="py-1">
                                <div>
                                    <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">{{ __('Settings') }}</a>
                                </div>

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
                @else
                    <!-- Guest Profile Dropdown -->
                    <div class="flex items-center">
                        <img id="avatarButton" type="button" 
                            data-dropdown-toggle="userDropdown"
                            data-dropdown-placement="bottom-start"
                            class="w-10 h-10 rounded-full cursor-pointer transition-all duration-200 border-1 border-gray-400 dark:border-gray-800" 
                            src="{{ asset('storage/profiles/guest-avatar.png') }}" 
                            alt="Guest User">

                        <!-- Guest Dropdown menu -->
                        <div id="userDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                            <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                <div>Guest</div>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">Login</a>
                                <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">Register</a>
                            </div>
                            <div class="py-1">
                                <div>
                                    <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">{{ __('Settings') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>