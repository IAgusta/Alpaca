<footer class="relative bottom-0 left-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow-sm md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2025 <a href="{{ route('home') }}" class="hover:underline">Alpaca™</a>. All Rights Reserved.
    </span>
    <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
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
            <a href="{{ route('plugins.monitoring') }}" class="hover:underline">Monitoring</a>
        </li>
    </ul>
</footer>