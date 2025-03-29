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
                    <div class="col-span-2">
                        {{-- Top 10 Favorit Courses --}}
                        @include('user.component.top_courses')

                        {{-- User Courses --}}
                        <div class="col-span-2 mt-8">
                            @include('user.component.dashboard_user_course')
                        </div>
                    </div>
                    {{-- Latest Courses Update --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Last Update Courses</h3>

                        <div class="mt-2 grid grid-cols-2 gap-4">
                            @foreach ($latestCourses as $course) <!-- Show only 5 items -->
                                <a href="{{route('user.course.detail', ['name' => Str::slug($course->name),'courseId' => $course->id])}}" class="block">
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

                {{-- Plugins --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Plugins</h3>
                        <div class="mt-2 flex flex-wrap gap-4">
                            <!-- Robot Control -->
                            <a href="{{ route('plugins.robotControl') }}" class="group">
                                <div class="relative w-24 h-24 border rounded-lg shadow-md overflow-hidden bg-gray-200 hover:bg-slate-500 transition duration-300 flex items-center justify-center">
                                    <!-- Icon (Visible by default, disappears on hover) -->
                                    <span class="material-symbols-outlined text-5xl text-gray-900 transition-all duration-300 group-hover:opacity-0">
                                        stadia_controller
                                    </span>
                                    <!-- Text (Hidden by default, appears centered on hover) -->
                                    <p class="absolute opacity-0 text-lg font-semibold text-white transition-all duration-300 group-hover:opacity-100 group-hover:flex group-hover:items-center group-hover:justify-center w-full h-full text-center whitespace-nowrap">
                                        Controler
                                    </p>
                                </div>
                            </a>
                
                            <!-- Find Users -->
                            <a href="{{ route('plugins.monitoring') }}" class="group">
                                <div class="relative w-24 h-24 border rounded-lg shadow-md overflow-hidden bg-gray-200 hover:bg-slate-500 transition duration-300 flex items-center justify-center">
                                    <!-- Icon (Visible by default, disappears on hover) -->
                                    <span class="material-symbols-outlined text-5xl text-gray-900 transition-all duration-300 group-hover:opacity-0">
                                        person_search
                                    </span>
                                    <!-- Text (Hidden by default, appears centered on hover) -->
                                    <p class="absolute opacity-0 text-lg font-semibold text-white transition-all duration-300 group-hover:opacity-100 group-hover:flex group-hover:items-center group-hover:justify-center w-full h-full text-center whitespace-nowrap">
                                        Find Users
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</x-app-layout>
