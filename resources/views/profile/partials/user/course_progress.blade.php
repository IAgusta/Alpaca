<div x-data="{ showMore: false }">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Enrolled Courses</h3>
    @php
        $enrolledCourses = \App\Models\UserCourse::where('user_id', $user->id)
            ->with('course')
            ->take(15)
            ->get();
        $initialCourses = $enrolledCourses->take(5);
        $remainingCourses = $enrolledCourses->slice(5);
    @endphp

    @if($enrolledCourses->isEmpty())
        <div class=" p-4">
            <p class="text-gray-500 text-center">No enrolled courses</p>
        </div>
    @else
        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($initialCourses as $userCourse)
                <a href="{{ route('user.course.detail', ['slug' => Str::slug($userCourse->course->slug),'courseId' => $userCourse->course->id]) }}" class="block">
                    <div class="border p-3 rounded-lg shadow-md bg-white dark:bg-gray-800 dark:border-gray-600 flex flex-col group hover:bg-slate-500">
                        <!-- Course Image -->
                        <div class="relative h-20 w-full rounded-md bg-cover bg-center" 
                            style="background-image: url('{{ $userCourse->course->image ? asset('storage/'.$userCourse->course->image) : asset('storage/courses/default-course.png') }}');">
                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 rounded-md"></div>
                        </div>

                        <!-- Course Details -->
                        <div class="mt-2">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-amber-100 line-clamp-1">
                                {{ $userCourse->course->display_name }}
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-200 group-hover:text-gray-200">
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
                            <p class="text-xs text-gray-500 dark:text-gray-300 group-hover:text-gray-200 mt-1">Progress:</p>
                            <div class="w-full bg-gray-300 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $progressPercentage }}%;"></div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach

            <!-- Additional courses (hidden by default) -->
            <template x-if="showMore">
                @foreach($remainingCourses as $userCourse)
                    <a href="{{ route('user.course.detail', ['slug' => Str::slug($userCourse->course->slug),'courseId' => $userCourse->course->id]) }}" class="block">
                        <div class="border p-3 rounded-lg shadow-md bg-white flex flex-col group hover:bg-slate-500">
                            <!-- Course Image -->
                            <div class="relative h-20 w-full rounded-md bg-cover bg-center" 
                                style="background-image: url('{{ $userCourse->course->image ? asset('storage/'.$userCourse->course->image) : asset('storage/courses/default-course.png') }}');">
                                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 rounded-md"></div>
                            </div>

                            <!-- Course Details -->
                            <div class="mt-2">
                                <h4 class="text-sm font-semibold text-gray-900 group-hover:text-amber-100 line-clamp-1">
                                    {{ $userCourse->course->display_name }}
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
                                <div class="w-full bg-gray-300 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $progressPercentage }}%;"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </template>
        </div>

        @if($enrolledCourses->count() > 5)
            <div class="mt-4 text-center">
                <button 
                    @click="showMore = !showMore" 
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors"
                    x-text="showMore ? 'Show Less' : 'Show More'"
                ></button>
            </div>
        @endif
    @endif
</div>