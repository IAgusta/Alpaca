<div class="flex justify-between">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Latest Update Titles</h3>
    <a href="{{ route('course.feed', ['sort' => 'updated_at']) }}">
        <span class="material-symbols-outlined">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="dark:fill-white">
                <path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/>
            </svg>
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
                <!-- Course Name with Own Tag -->
                <div class="flex items-center gap-2">
                    <h4 class="text-sm font-bold text-gray-800 group-hover:text-blue-600 truncate">
                        {{ $course->name }}
                    </h4>
                    @if($userSavedCourses->contains($course->id))
                        <span class="px-2 py-1 text-[10px] bg-blue-100 text-blue-800 rounded-full">Own</span>
                    @endif
                </div>
                
                <div class="flex justify-between items-center mt-1">
                    <!-- Module Progress / Info -->
                    <p class="text-xs text-gray-500 mt-1">
                        @if($course->modules->count() > 0)
                            {{ Str::limit($course->modules->first()->title, 30) }}
                        @else
                            No modules yet
                        @endif
                    </p>

                    <!-- Timestamp -->
                    <div class="text-xs text-gray-400 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($course->updated_at)->diffForHumans() }}
                    </div>
                </div>

                <!-- Author -->
                <p class="text-xs text-gray-400 mt-1">
                    {{ $course->authorUser->name }}
                </p>
            </div>
        </a>
    @endforeach
</div>
