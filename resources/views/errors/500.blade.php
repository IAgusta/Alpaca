<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="mb-8">
                        <span class="text-6xl font-bold text-indigo-600">500</span>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Server Error</h1>
                    <p class="text-gray-600 dark:text-gray-300 text-lg mb-8">
                        {{ $exception->getMessage() ?: 'Something went wrong on our servers.' }}
                    </p>
                    
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <a href="#" onclick="window.location.reload();" 
                           class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Try Again
                        </a>
                        <a href="{{ route('contact') }}" 
                           class="text-sm font-semibold text-gray-900 dark:text-gray-300">
                            Report Problem <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
