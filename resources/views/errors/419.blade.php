<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="mb-8">
                        <span class="text-6xl font-bold text-indigo-600">419</span>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Page Expired</h1>
                    <p class="text-gray-600 dark:text-gray-300 text-lg mb-8">
                        Your session has expired. Please refresh and try again.
                    </p>
                    
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <a href="#" onclick="window.location.reload();" 
                           class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Refresh Page
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="text-sm font-semibold text-gray-900 dark:text-gray-300">
                            Back to Dashboard <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
