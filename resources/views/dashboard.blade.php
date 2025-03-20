<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Top Courses Swiper -->
                    @include('user.component.top_courses')

                    <!-- Last Update Courses -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Last Update Courses</h3>

                        <div class="mt-4 grid grid-cols-2 gap-4">
                            @foreach ($latestCourses as $course) <!-- Show only 5 items -->
                                <a href="{{ route('user.course.preview', $course->id) }}" class="block">
                                    <div class="border p-3 rounded-lg shadow-md bg-white flex flex-col group hover:bg-slate-500">
                                        <!-- Course Image (Smaller) -->
                                        <div class="relative h-20 w-full rounded-md bg-cover bg-center" 
                                            style="background-image: url('{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}');">
                                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 rounded-md"></div>
                                        </div>
                            
                                        <!-- Course Details -->
                                        <div class="mt-2">
                                            <h4 class="text-sm font-semibold text-gray-900 group-hover:text-amber-100 line-clamp-1">
                                                {{ $course->name }}
                                            </h4>
                                            <p class="text-xs text-gray-500 group-hover:text-gray-200 line-clamp-1">
                                                {{ $course->authorUser->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 group-hover:text-gray-200">
                                                {{ \Carbon\Carbon::parse($course->updated_at)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            <!-- "More" Button -->
                            <a href="{{ route('user.course') }}" class="hover:bg-slate-500 hover:text-white flex items-center justify-center border p-3 rounded-lg shadow-md bg-gray-100 text-blue-400 font-medium text-sm">
                                More
                            </a>
                        </div>
                    </div>


                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-gray-900">Your Courses Progress</h3>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="border p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900">User Courses</h4>
                                <p class="text-sm text-gray-500">Total</p>
                                <p class="text-sm text-gray-500">Completed :</p>
                                <p class="text-sm text-gray-500">Chapter :</p>
                            </div>
                            <div class="border p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900">User Courses</h4>
                                <p class="text-sm text-gray-500">Total</p>
                                <p class="text-sm text-gray-500">Completed :</p>
                                <p class="text-sm text-gray-500">Chapter :</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Plugins</h3>
                        <div class="mt-4 space-y-4">
                            <div class="border p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900">
                                    <span class="material-symbols-outlined">
                                        stadia_controller
                                        </span>
                                    Robot Control</h4>
                            </div>
                            <div class="border p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900">
                                    <span class="material-symbols-outlined">
                                        person_search
                                        </span>
                                    Find Users</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</x-app-layout>
