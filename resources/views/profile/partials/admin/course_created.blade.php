<div class="space-y-6">
    @php
        if (isset($user)) {
            $url = in_array($user->role, ['user', 'trainer'])
                ?  route('admin.courses.index', ['search' => $user->name])
                : route('course.feed', ['search' => $user->name]);
        } else {
            // fallback URL for guests
            $url = route('course.feed'); // or route('login') or any default
        }
    @endphp


    <div class="flex justify-between">
        <h3 class="text-2xl font-bold text-gray-900">Created Courses</h3>
        <a href="{{ $url }}">
            <span class="material-symbols-outlined">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                    <path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/>
                </svg>
            </span>
        </a>
    </div>

    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
        @forelse ($createdCourses as $course)
            <a href="{{ route('user.course.detail', ['name' => Str::slug($course->name), 'courseId' => $course->id]) }}"
                class="flex items-start gap-4 p-3 rounded-lg bg-white shadow hover:bg-slate-100 group transition duration-200">
                
                <!-- Cover Image -->
                <div class="w-16 h-20 flex-shrink-0 rounded bg-cover bg-center"
                    style="background-image: url('{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}');">
                </div>

                <!-- Details -->
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-gray-800 group-hover:text-blue-600 truncate">
                        {{ $course->name }}
                    </h4>
                    
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500 mt-1">
                            @if($course->modules->count() > 0)
                                {{ Str::limit($course->modules->first()->title, 30) }}
                            @else
                                No modules yet
                            @endif
                        </p>

                        <div class="text-xs text-gray-400 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($course->updated_at)->diffForHumans() }}
                        </div>
                    </div>

                    <p class="text-xs text-gray-400 mt-1">
                        Popularity: {{ $course->popularity }}
                    </p>
                </div>
            </a>
        @empty
            <div class="border rounded-lg p-4 bg-gray-50">
                <p class="text-gray-500 text-center">No courses created yet</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $createdCourses->links() }}
    </div>
</div>