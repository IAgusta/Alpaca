<x-landing-layout> 
    <div class="min-h-screen flex items-center justify-center bg-slate-300">
        <main class="grid place-items-center bg-transparent px-6 py-24 sm:py-32 lg:px-8">
            <div class="text-center">
                <p class="text-base font-semibold text-indigo-600">403</p>
                <h1 class="mt-4 text-5xl font-semibold tracking-tight text-balance text-gray-900 sm:text-7xl">Unauthorized</h1>
                <p class="mt-6 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8">
                    Sorry, we couldn’t Provide You Looking that Page.</p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="#" onclick="window.history.back();" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Go back</a>
                    <a href="{{ route('contact') }}" class="text-sm font-semibold text-gray-900">Contact support <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
        </main>
    </div>
</x-landing-layout>