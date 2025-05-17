<div class="flex justify-between">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
    Your Progress</h3>
    <a href="{{ route('user.course.library') }}">
        <span class="material-symbols-outlined">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="dark:fill-white">
                <path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/>
            </svg>
        </span>
    </a>
</div>
<div class="mt-4 grid grid-cols-2 lg:grid-cols-4 gap-4">
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
                        @php
                            $totalModules = $userCourse->course->modules->count();
                            $progressPercentage = $totalModules > 0 ? ($userCourse->completed_modules / $totalModules) * 100 : 0;
                        @endphp
                        @if($progressPercentage == 100)
                            Completed {{ $userCourse->completed_at?->diffForHumans() ?? 'N/A' }}
                        @else
                            Last opened {{ $userCourse->last_opened?->diffForHumans() ?? 'Never' }}
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 group-hover:text-gray-200 mt-1">Progress:</p>
                    
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-300 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $progressPercentage }}%;"></div>
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>