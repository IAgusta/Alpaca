<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    
                    <h1 class="text-3xl font-bold text-red-500 mb-4">Limited Access</h1>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        @if(isset($exception) && $exception->getMessage() !== 'Unauthorized access')
                            {{ $exception->getMessage() }}
                        @else
                            {{ $message ?? 'You do not have permission to access this page.' }}
                        @endif
                    </p>
                    
                    @if(isset($course))
                        <div class="mb-6">
                            <x-primary-button 
                                onclick="window.location.href='{{ route('user.course.detail', ['name' => Str::slug($course->name), 'courseId' => $course->id]) }}'">
                                Go to Course Details
                            </x-primary-button>
                        </div>
                    @endif

                    <x-secondary-button 
                        onclick="window.location.href='{{ route('user.course') }}'">
                        Back to Courses
                    </x-secondary-button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>