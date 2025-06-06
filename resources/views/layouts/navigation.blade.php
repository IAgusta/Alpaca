<nav x-data="{ open: false, pluginsOpen: false, loading: false }" class="bg-white dark:bg-gray-800 dark:border-gray-900 border-b border-gray-100 fixed top-0 left-0 w-full z-30">
    <!-- Loading Bar -->
    <div x-show="loading" class="fixed top-0 left-0 w-full h-1 bg-blue-500 z-50" x-transition></div>

    <!-- Primary Navigation Menu -->
    @include('layouts.components.primary-navigation')

    <!-- Mobile Navigation Menu -->
    @include('layouts.components.mobile-navigation')
</nav>