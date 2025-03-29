<h3 class="text-lg font-medium text-gray-900">
    Your Courses Progress</h3>
<div class="mt-2 grid grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach ($userCourses as $userCourse)
        <a href="{{ route('user.course.detail', ['name' => Str::slug($userCourse->course->name),'courseId' => $userCourse->course->id])  }}" class="block">
            <div class="border p-3 rounded-lg shadow-md bg-white flex flex-col group hover:bg-slate-500">
                <!-- Course Image -->
                <div class="relative h-20 w-full rounded-md bg-cover bg-center" 
                    style="background-image: url('{{ $userCourse->course->image ? asset('storage/'.$userCourse->course->image) : asset('storage/courses/default-course.png') }}');">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 rounded-md"></div>
                </div>

                <!-- Course Details -->
                <div class="mt-2">
                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-amber-100 line-clamp-1">
                        {{ $userCourse->course->name }}
                    </h4>
                    <p class="text-xs text-gray-500 group-hover:text-gray-200">
                        Progress: 
                    </p>
                    
                    <!-- Progress Bar -->
                    @php
                        $totalModules = $userCourse->course->modules->count();
                        $progressPercentage = $totalModules > 0 ? ($userCourse->completed_modules / $totalModules) * 100 : 0;
                    @endphp
                    <div class="w-full bg-gray-300 rounded-full h-2 mt-1">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $progressPercentage }}%;"></div>
                    </div>
                </div>
            </div>
        </a>
    @endforeach

    <!-- "More" Button -->
    <a href="{{ route('user.course') }}" class="hover:bg-slate-500 hover:text-white flex items-center justify-center border p-3 rounded-lg shadow-md bg-gray-100 text-blue-400 font-medium text-sm">
        More
    </a>
</div>