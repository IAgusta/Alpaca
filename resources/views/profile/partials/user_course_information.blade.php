<section>
    @if(in_array($user->role, ['admin', 'trainer', 'owner']))
        <div class="space-y-6">
            <!-- Created Courses -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Created Courses</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Placeholder for created courses -->
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <p class="text-gray-500 text-center">No courses created yet</p>
                    </div>
                </div>
            </div>

            <!-- Teaching History -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Teaching History</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No teaching history available
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <!-- Enrolled Courses for regular users -->
        <div x-data="{ showMore: false }">
            <h3 class="text-lg font-semibold mb-4">Enrolled Courses</h3>
            @php
                $enrolledCourses = \App\Models\UserCourse::where('user_id', $user->id)
                    ->with('course')
                    ->take(15)
                    ->get();
                $initialCourses = $enrolledCourses->take(5);
                $remainingCourses = $enrolledCourses->slice(5);
            @endphp

            @if($enrolledCourses->isEmpty())
                <div class="border rounded-lg p-4 bg-gray-50">
                    <p class="text-gray-500 text-center">No enrolled courses</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($initialCourses as $userCourse)
                        <a href="{{ route('user.course.detail', ['name' => Str::slug($userCourse->course->name),'courseId' => $userCourse->course->id]) }}" class="block">
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
                            <a href="{{ route('user.course.detail', ['name' => Str::slug($userCourse->course->name),'courseId' => $userCourse->course->id]) }}" class="block">
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
    @endif
</section>