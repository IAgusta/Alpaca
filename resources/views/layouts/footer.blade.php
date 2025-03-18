<footer class="relative bottom-0 left-0 z-0 w-full p-4 bg-white border-t border-gray-200 shadow-sm md:flex md:flex-col md:items-center justify-center md:p-6 dark:bg-gray-800 dark:border-gray-600">
    <ul class="flex flex-wrap items-center mb-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mb-0 justify-center">
        <li>
            <a href="{{ route('dashboard') }}" class="hover:underline me-4 md:me-6">Dashboard</a>
        </li>
        <li>
            <a href="{{ route('user.course') }}" class="hover:underline me-4 md:me-6">Course</a>
        </li>
        <li>
            <a href="{{ route('plugins.robotControl') }}" class="hover:underline me-4 md:me-6">Robot Control</a>
        </li>
        <li>
            <a href="{{ route('plugins.monitoring') }}" class="hover:underline me-4 md:me-6">Monitoring</a>
        </li>
        <li>
            <a href="{{ route('profile.edit') }}" class="hover:underline">Profile</a>
        </li>
    </ul>
    <span class="text-sm mt-4 text-gray-500 sm:text-center dark:text-gray-400">© 2025 <a href="{{ route('home') }}" class="hover:underline">Alpaca™</a>. All Rights Reserved.
    </span>
</footer>