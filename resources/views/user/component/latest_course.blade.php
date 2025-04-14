<h3 class="text-lg font-medium text-gray-900">Latest Update Courses</h3>

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
                        {{ \Carbon\Carbon::parse($course->updated_at)->diffForHumans() }}
                    </p>
                </div>
            </div>
        </a>
    @endforeach
</div>
<!-- "More" Button -->
<a href="{{ route('user.course') }}" class="mt-3 hover:bg-slate-500 hover:text-white flex items-center justify-center border p-3 rounded-lg shadow-md bg-gray-100 text-blue-400 font-medium text-sm">
    More
</a>