<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="mb-8">
                        <span class="text-6xl font-bold text-indigo-600">403</span>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Limited Access</h1>
                    <p class="text-red-500 text-lg mb-8">
                        @if(isset($exception) && $exception->getMessage() !== 'Unauthorized access')
                            {{ $exception->getMessage() }}
                        @else
                            {{ $message ?? 'You do not have permission to access this page.' }}
                        @endif
                    </p>
                    
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        @if(isset($course))
                            <a href="{{ route('user.course.detail', ['name' => Str::slug($course->name), 'courseId' => $course->id]) }}" 
                               class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Go to Course Details
                            </a>
                        @endif

                        <a href="{{ route('user.course') }}" 
                           class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Courses
                        </a>

                        <a href="{{ route('home') }}" 
                           class="text-sm font-semibold text-gray-900 dark:text-gray-300">
                            Back to Homepage<span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>