<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelas') }} 
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="mx-auto p-7 bg-white overflow-hidden sm:rounded-lg">
                {{-- Your Course Section --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Your Courses</h3>
                        @if($userCourses->count() >= 5)
                            <a href="{{ route('user.course.library') }}" class="text-blue-600 hover:text-blue-800">See More</a>
                        @endif
                    </div>
                    @include('user.component.user_course')
                </div>

                {{-- Available Courses Section --}}
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Available Courses</h3>
                        @if($availableCourses->count() >= 11)
                            <a href="{{ route('course.feed') }}" class="text-blue-600 hover:text-blue-800">See More</a>
                        @endif
                    </div>
                    @include('user.component.available_course')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>