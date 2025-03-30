<div class="mt-4 flex gap-2">
    @php
        $isUserEnrolled = $userCourses->contains('course_id', $course->id);
    @endphp
    @if($isUserEnrolled)
        <form method="POST" action="{{ route('user.course.delete', $course->id) }}">
            @csrf
            @method('DELETE')
            <button class="px-4 py-2 rounded-lg flex items-center bg-orange-400 hover:bg-orange-500">
                <span class="material-symbols-outlined">bookmark_remove</span>
                <span class="ml-2">Remove</span>
            </button>
        </form>
    @else
        @if($course->is_locked)
            <button data-modal-target="unlock-modal-{{ $course->id }}" data-modal-toggle="unlock-modal-{{ $course->id }}" class="px-4 py-2 rounded-lg flex items-center bg-orange-400 hover:bg-orange-500">
                <span class="material-symbols-outlined">lock</span>
                <span class="ml-2">Lock</span>
            </button>
        @else
        <form method="POST" action="{{ route('user.course.add', $course->id) }}">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <button class="px-4 py-2 rounded-lg flex items-center bg-orange-400 hover:bg-orange-500">
                <span class="material-symbols-outlined">bookmark</span>
                <span class="ml-2">Save</span>
            </button>
        </form>
        @endif
    @endif
    <button class="px-4 py-2 rounded-lg flex items-center bg-slate-300 hover:bg-slate-500">
        <span class="material-symbols-outlined">auto_stories</span>
        <span class="ml-2 hidden lg:inline">Continue Reading</span>
    </button>

    <!-- Button group for large screens, dropdown for smaller screens -->
    <div class="relative">
        <!-- Individual buttons - visible only on lg screens and above -->
        <div class="hidden lg:flex space-x-2">
            <button class="px-4 py-2 text-sm rounded-lg bg-slate-300 hover:bg-slate-500 flex items-center">
                <span class="material-symbols-outlined">report</span>
            </button>
            <button class="px-4 py-2 text-sm rounded-lg bg-slate-300 hover:bg-slate-500 flex items-center">
                <span class="material-symbols-outlined">download</span>
            </button>
            <button class="px-4 py-2 text-sm rounded-lg bg-slate-300 hover:bg-slate-500 flex items-center">
                <span class="material-symbols-outlined">share</span>
            </button>
        </div>

        <!-- Dropdown Button - visible on screens smaller than lg -->
        <div class="lg:hidden">
            <button id="dropdownButton" data-dropdown-toggle="actionDropdown" 
                class="px-4 py-2 text-sm rounded-lg flex items-center bg-slate-300 hover:bg-slate-500">
                <span class="material-symbols-outlined">more_vert</span>
            </button>

            <!-- Dropdown Menu -->
            <div id="actionDropdown" class="hidden absolute bg-white border shadow-md rounded-lg mt-1 w-36">
                <ul class="py-1 text-sm text-gray-700">
                    <li>
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-200 flex items-center">
                            <span class="material-symbols-outlined mr-2">report</span> Report
                        </button>
                    </li>
                    <li>
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-200 flex items-center">
                            <span class="material-symbols-outlined mr-2">download</span> Download
                        </button>
                    </li>
                    <li>
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-200 flex items-center">
                            <span class="material-symbols-outlined mr-2">share</span> Share
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>