<div class="flex justify-between">
    <h3 class="text-2xl font-bold text-gray-900">Latest Update Courses</h3>
    <a href="{{ route('user.course') }}">
        <span class="material-symbols-outlined">
            arrow_forward
        </span>
    </a>
</div>

<div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Loop through the latest courses -->
    @foreach ($latestCourses as $index => $course)
        <a href="{{ route('user.course.detail', ['name' => Str::slug($course->name), 'courseId' => $course->id]) }}"
            class="flex items-start gap-4 p-3 rounded-lg bg-white shadow hover:bg-slate-100 group transition duration-200
                   {{ $index >= 5 ? 'hidden lg:flex' : '' }}">
            
            <!-- Cover Image -->
            <div class="w-16 h-20 flex-shrink-0 rounded bg-cover bg-center"
                style="background-image: url('{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}');">
            </div>

            <!-- Details -->
            <div class="flex-1">
                <!-- Course Name -->
                <h4 class="text-sm font-bold text-gray-800 group-hover:text-blue-600 truncate">
                    {{ $course->name }}
                </h4>
                
                <!-- Module Progress / Info -->
                <p class="text-xs text-gray-500 mt-1">
                    {{ $course->modules_count ?? 'No' }} modules
                </p>

                <!-- Author -->
                <p class="text-xs text-gray-400">
                    {{ $course->authorUser->name }}
                </p>
            </div>

            <!-- Timestamp -->
            <div class="text-xs text-gray-400 whitespace-nowrap">
                {{ \Carbon\Carbon::parse($course->updated_at)->diffForHumans() }}
            </div>
        </a>
    @endforeach
</div>
