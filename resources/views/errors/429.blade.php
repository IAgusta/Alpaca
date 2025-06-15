<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="mb-8">
                        <span class="text-6xl font-bold text-indigo-600">429</span>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Too Many Requests</h1>
                    <p class="text-red-500 text-lg mb-8">
                        Please slow down and try again in a few moments.
                    </p>
                    
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <a href="#" onclick="window.history.back();" 
                           class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Go Back
                        </a>
                        <a href="{{ route('home') }}" 
                           class="text-sm font-semibold text-gray-900 dark:text-gray-300">
                            Homepage <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
