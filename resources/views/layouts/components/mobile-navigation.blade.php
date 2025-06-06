    <!-- Side Drawer Navigation -->
    <div :class="{'translate-x-0': open, '-translate-x-full': !open}" 
        class="fixed top-16 left-0 h-full w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out lg:hidden flex flex-col">
        <div class="pt-2 pb-3 space-y-1 flex-1">
            <!-- Dashboard Link -->
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" 
                class="block px-4 py-2 text-base font-medium dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-900" @click="loading = true">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <!-- Courses Link -->
            <x-responsive-nav-link :href="auth()->check() && in_array(auth()->user()->role, ['admin', 'trainer', 'owner'])
                ? route('admin.courses.index')
                : route('user.course')"
                :active="request()->routeIs('admin.courses.index') || request()->routeIs('user.course')"
                class="block px-4 py-2 text-base font-medium dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-900" @click="loading = true">
                {{ __('Courses') }}
            </x-responsive-nav-link>
            <!-- Find User Link -->
            <x-responsive-nav-link :href="route('plugins.search-users')" :active="request()->routeIs('plugins.search-users')"
                class="block px-4 py-2 text-base font-medium dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-900" @click="loading = true">
                {{ __('Find User') }}
            </x-responsive-nav-link>
            <!-- Tools Dropdown -->
            <div class="px-4">
                <button @click="pluginsOpen = !pluginsOpen" 
                    class="w-full flex items-center justify-between py-2 text-base font-medium text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                    {{ __('Tools') }}
                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        :class="{'rotate-180': pluginsOpen}">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="pluginsOpen" class="pl-4">
                    <x-responsive-nav-link :href="route('plugins.robotControl')" :active="request()->routeIs('plugins.robotControl')"
                        class="block py-2 text-sm dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-900" @click="loading = true">
                        {{ __('Robot Control') }}
                    </x-responsive-nav-link>
                </div>
            </div>
            <!-- Forum Discussion -->
            <x-responsive-nav-link :href="route('forum')" :active="request()->routeIs('forum')"
                class="block px-4 py-2 text-base font-medium dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-900" @click="loading = true">
                {{ __('Forums') }}
            </x-responsive-nav-link>
        </div>

        <!-- Support Us Button at Bottom -->
        <div class="px-4 pb-4 mt-auto">
            <div id="supportSidebarDiv" class="hidden mt-3 flex justify-center items-center flex-col">
                <a href="https://trakteer.id/eiko_hachiichi" target="_blank" class="inline-block transition-transform transform hover:scale-110">
                    <img src="img/camellya.png" alt="Support via Trakteer" class="w-16 h-16 rounded-full border-4 border-pink-400 shadow-md">
                    <p class="mt-2 text-pink-600 font-semibold text-center text-xs">Mie Ayam ♥</p>
                </a>
            </div>
            <button onclick="toggleSupportSidebar()" class="w-full bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transition-all duration-300 ease-in-out transform hover:scale-105 flex items-center justify-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-bounce" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 015.656 5.656L10 18.343l-6.828-6.829a4 4 0 010-5.656z" />
                </svg>
                <span>Support Us</span>
            </button>

            <script>
                function toggleSupportSidebar() {
                    const supportDiv = document.getElementById('supportSidebarDiv');
                    supportDiv.classList.toggle('hidden');
                }
            </script>
        </div>

        <!-- Sidebar Footer Section -->
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-2 space-y-2">
            <!-- Dropdowns -->
            <div x-data="{ openProduct: false, openInfo: false, openContact: false }">
                <!-- Product Dropdown -->
                <button @click="openProduct = !openProduct" class="w-full flex items-center justify-between py-2 text-sm font-semibold text-gray-700 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none">
                    Product
                    <svg :class="{'rotate-180': openProduct}" class="ml-1 h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openProduct" class="pl-4 pb-2 space-y-1">
                    <a href="{{ route('course.feed') }}" class="block text-gray-500 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm py-1">Kelas Online</a>
                    <a href="{{ route('plugins.search-users') }}" class="block text-gray-500 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm py-1">Cari Pengguna</a>
                    <a href="{{ route('plugins.robotControl') }}" class="block text-gray-500 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm py-1">Kontrol Robot</a>
                    <a href="{{ route('prices') }}" class="block text-gray-500 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm py-1">Fitur dan Harga</a>
                </div>
                <!-- Informasi Dropdown -->
                <button @click="openInfo = !openInfo" class="w-full flex items-center justify-between py-2 text-sm font-semibold text-gray-700 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none">
                    Informasi
                    <svg :class="{'rotate-180': openInfo}" class="ml-1 h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openInfo" class="pl-4 pb-2 space-y-1">
                    <a href="{{ route('about') }}" class="block text-gray-500 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm py-1">Tentang</a>
                    <a href="{{ route('terms') }}" class="block text-gray-500 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm py-1">Syarat dan Ketentuan</a>
                    <a href="{{ route('faq') }}" class="block text-gray-500 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm py-1">Bantuan</a>
                    <a href="{{ route('privacy-policy') }}" class="block text-gray-500 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm py-1">Kebijakan Privasi</a>
                </div>
                <!-- Hubungi Kami Dropdown -->
                <button @click="openContact = !openContact" class="w-full flex items-center justify-between py-2 text-sm font-semibold text-gray-700 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none">
                    Hubungi Kami
                    <svg :class="{'rotate-180': openContact}" class="ml-1 h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openContact" class="pl-4 pb-2 space-y-1">
                    <a href="{{ route('contact') }}" class="block text-gray-500 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm py-1">Kontak</a>
                    <a href="{{ route('news') }}" class="block text-gray-500 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm py-1">Berita</a>
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLScIvdilxzsOfFCzkolZAj9-eZoQypunWG6aIzc6Sg7x-MPxOw/viewform?usp=dialog" target="_blank" class="block text-gray-500 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm py-1">Kritik dan Saran</a>
                </div>
            </div>
            <!-- Social Media -->
            <div class="mt-2">
                <h2 class="mb-2 text-xs font-semibold text-gray-900 uppercase dark:text-white">Social Media</h2>
                <div class="flex space-x-3">
                    <a href="https://github.com/IAgusta/Alpaca" target="_blank" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 .333A9.911 9.911 0 0 0 6.866 19.65c.5.092.678-.215.678-.477 0-.237-.01-1.017-.014-1.845-2.757.6-3.338-1.169-3.338-1.169a2.627 2.627 0 0 0-1.1-1.451c-.9-.615.07-.6.07-.6a2.084 2.084 0 0 1 1.518 1.021 2.11 2.11 0 0 0 2.884.823c.044-.503.268-.973.63-1.325-2.2-.25-4.516-1.1-4.516-4.9A3.832 3.832 0 0 1 4.7 7.068a3.56 3.56 0 0 1 .095-2.623s.832-.266 2.726 1.016a9.409 9.409 0 0 1 4.962 0c1.89-1.282 2.717-1.016 2.717-1.016.366.83.402 1.768.1 2.623a3.827 3.827 0 0 1 1.02 2.659c0 3.807-2.319 4.644-4.525 4.889a2.366 2.366 0 0 1 .673 1.834c0 1.326-.012 2.394-.012 2.72 0 .263.18.572.681.475A9.911 9.911 0 0 0 10 .333Z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="https://discord.gg/m6RMwKkjj2" target="_blank" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 21 16">
                            <path d="M16.942 1.556a16.3 16.3 0 0 0-4.126-1.3 12.04 12.04 0 0 0-.529 1.1 15.175 15.175 0 0 0-4.573 0 11.585 11.585 0 0 0-.535-1.1 16.274 16.274 0 0 0-4.129 1.3A17.392 17.392 0 0 0 .182 13.218a15.785 15.785 0 0 0 4.963 2.521c.41-.564.773-1.16 1.084-1.785a10.63 10.63 0 0 1-1.706-.83c.143-.106.283-.217.418-.33a11.664 11.664 0 0 0 10.118 0c.137.113.277.224.418.33-.544.328-1.116.606-1.71.832a12.52 12.52 0 0 0 1.084 1.785 16.46 16.46 0 0 0 5.064-2.595 17.286 17.286 0 0 0-2.973-11.59ZM6.678 10.813a1.941 1.941 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.919 1.919 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Zm6.644 0a1.94 1.94 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.918 1.918 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Z"/>
                        </svg>
                    </a>
                    <a href="https://youtube.com/@ikraamagusta?si=aaz--YvkvX0got9O" target="_blank" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z"/>
                        </svg>
                    </a>
                    <a href="https://instagram.com/i_agusta" target="_blank" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.036.78-.166 1.203-.276 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                        </svg>
                    </a>
                    <a href="https://web.facebook.com/profile.php?id=100012710521025" target="_blank" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>