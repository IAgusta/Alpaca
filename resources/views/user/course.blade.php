<x-app-layout>
    @section('title', 'Courses - ' . config('app.name'))
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Courses') }} 
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="mx-auto p-7 overflow-hidden sm:rounded-lg">
                {{-- Your Course Section --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold dark:text-white">Your Courses</h3>
                        @if($userCourses->count() >= 6)
                            <a href="{{ route('user.course.library') }}" class="text-blue-600 hover:text-blue-800">See More</a>
                        @endif
                    </div>
                    @include('user.component.user_course')
                </div>

                {{-- Available Courses Section --}}
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold dark:text-white">Other Courses</h3>
                        @if($availableCourses->count() >= 12)
                            <a href="{{ route('course.feed') }}" class="text-blue-600 hover:text-blue-800">See More</a>
                        @endif
                    </div>
                    @include('user.component.available_course')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>