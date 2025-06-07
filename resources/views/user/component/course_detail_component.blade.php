<div class="mt-4 flex gap-2">
    @php
        $isUserEnrolled = $userCourses->contains('course_id', $course->id);
        
        // Get all modules for this course ordered by position
        $modules = $course->modules->sortBy('position');
        
        // Get user's read modules
        $readModules = \App\Models\UserModel::where('user_id', auth()->id())
            ->where('read', true)
            ->whereIn('module_id', $modules->pluck('id'))
            ->pluck('module_id');
            
        // Determine the next module to read
        $nextModule = null;
        $buttonText = 'Start Reading';
        
        if ($readModules->count() > 0) {
            if ($readModules->count() == $modules->count()) {
                // All modules read - point to last module by position
                $nextModule = $modules->last();
                $buttonText = 'Review Last Module';
            } else {
                // Find the next unread module by position
                $nextModule = $modules->first(function($module) use ($readModules) {
                    return !$readModules->contains($module->id);
                });
                $buttonText = 'Continue Reading';
            }
        } else {
            // No modules read - start with first module by position
            $nextModule = $modules->first();
        }
    @endphp
    @if($isUserEnrolled)
        <form method="POST" action="{{ route('user.course.delete', $course->id) }}">
            @csrf
            @method('DELETE')
            <button class="px-4 py-2 rounded-lg flex items-center bg-orange-400 hover:bg-orange-500">
                <span class="material-symbols-outlined">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path d="M840-680H600v-80h240v80ZM200-120v-640q0-33 23.5-56.5T280-840h240v80H280v518l200-86 200 86v-278h80v400L480-240 200-120Zm80-640h240-240Z"/>
                    </svg>
                </span>
                <span class="ml-2">Remove</span>
            </button>
        </form>
    @else
        @if($course->is_locked)
            <button data-modal-target="unlock-modal-{{ $course->id }}" data-modal-toggle="unlock-modal-{{ $course->id }}" class="px-4 py-2 rounded-lg flex items-center bg-orange-400 hover:bg-orange-500">
                <span class="material-symbols-outlined">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm0-80h480v-400H240v400Zm240-120q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80ZM240-160v-400 400Z"/>
                    </svg>
                </span>
                <span class="ml-2">Lock</span>
            </button>
        @else
        <form method="POST" action="{{ route('user.course.add', $course->id) }}">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <button class="px-4 py-2 rounded-lg flex items-center bg-orange-400 hover:bg-orange-500">
                <span class="material-symbols-outlined">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path d="M200-120v-640q0-33 23.5-56.5T280-840h400q33 0 56.5 23.5T760-760v640L480-240 200-120Zm80-122 200-86 200 86v-518H280v518Zm0-518h400-400Z"/>
                    </svg>
                </span>
                <span class="ml-2">Save</span>
            </button>
        </form>
        @endif
    @endif

    @if($nextModule)
    <a href="{{ route('course.module.open', [
        'slug' => Str::slug($course->display_name),
        'courseId' => $course->id,
        'moduleTitle' => Str::slug($nextModule->title),
        'moduleId' => $nextModule->id
    ]) }}" class="px-4 py-2 rounded-lg flex items-center bg-slate-300 hover:bg-slate-500">
        <span class="material-symbols-outlined">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                <path d="M480-160q-48-38-104-59t-116-21q-42 0-82.5 11T100-198q-21 11-40.5-1T40-234v-482q0-11 5.5-21T62-752q46-24 96-36t102-12q58 0 113.5 15T480-740v484q51-32 107-48t113-16q36 0 70.5 6t69.5 18v-480q15 5 29.5 10.5T898-752q11 5 16.5 15t5.5 21v482q0 23-19.5 35t-40.5 1q-37-20-77.5-31T700-240q-60 0-116 21t-104 59Zm80-200v-380l200-200v400L560-360Zm-160 65v-396q-33-14-68.5-21.5T260-720q-37 0-72 7t-68 21v397q35-13 69.5-19t70.5-6q36 0 70.5 6t69.5 19Zm0 0v-396 396Z"/>
            </svg>
        </span>
        <span class="ml-2 hidden lg:inline">{{ $buttonText }}</span>
    </a>
    @else
    <button class="px-4 py-2 rounded-lg flex items-center bg-slate-300 hover:bg-slate-500" disabled>
        <span class="material-symbols-outlined">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                <path d="M480-160q-48-38-104-59t-116-21q-42 0-82.5 11T100-198q-21 11-40.5-1T40-234v-482q0-11 5.5-21T62-752q46-24 96-36t102-12q58 0 113.5 15T480-740v484q51-32 107-48t113-16q36 0 70.5 6t69.5 18v-480q15 5 29.5 10.5T898-752q11 5 16.5 15t5.5 21v482q0 23-19.5 35t-40.5 1q-37-20-77.5-31T700-240q-60 0-116 21t-104 59Zm80-200v-380l200-200v400L560-360Zm-160 65v-396q-33-14-68.5-21.5T260-720q-37 0-72 7t-68 21v397q35-13 69.5-19t70.5-6q36 0 70.5 6t69.5 19Zm0 0v-396 396Z"/>
            </svg>
        </span>
        <span class="ml-2 hidden lg:inline">No Modules Available</span>
    </button>
    @endif

    <!-- Button group for large screens, dropdown for smaller screens -->
    <div class="relative">
        <!-- Individual buttons - visible only on lg screens and above -->
        <div class="hidden lg:flex space-x-2">
            <button class="px-4 py-2 text-sm rounded-lg bg-slate-300 hover:bg-slate-500 flex items-center">
                <span class="material-symbols-outlined">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path d="M200-120v-680h360l16 80h224v400H520l-16-80H280v280h-80Zm300-440Zm86 160h134v-240H510l-16-80H280v240h290l16 80Z"/>
                    </svg>
                </span>
            </button>
            <button class="px-4 py-2 text-sm rounded-lg bg-slate-300 hover:bg-slate-500 flex items-center">
                <span class="material-symbols-outlined">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/>
                    </svg>
                </span>
            </button>
            <button data-modal-target="shareModal" data-modal-toggle="shareModal" class="px-4 py-2 text-sm rounded-lg bg-slate-300 hover:bg-slate-500 flex items-center">
                <span class="material-symbols-outlined">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path d="M680-80q-50 0-85-35t-35-85q0-6 3-28L282-392q-16 15-37 23.5t-45 8.5q-50 0-85-35t-35-85q0-50 35-85t85-35q24 0 45 8.5t37 23.5l281-164q-2-7-2.5-13.5T560-760q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35q-24 0-45-8.5T598-672L317-508q2 7 2.5 13.5t.5 14.5q0 8-.5 14.5T317-452l281 164q16-15 37-23.5t45-8.5q50 0 85 35t35 85q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T720-200q0-17-11.5-28.5T680-240q-17 0-28.5 11.5T640-200q0 17 11.5 28.5T680-160ZM200-440q17 0 28.5-11.5T240-480q0-17-11.5-28.5T200-520q-17 0-28.5 11.5T160-480q0 17 11.5 28.5T200-440Zm480-280q17 0 28.5-11.5T720-760q0-17-11.5-28.5T680-800q-17 0-28.5 11.5T640-760q0 17 11.5 28.5T680-720Zm0 520ZM200-480Zm480-280Z"/>
                    </svg>
                </span>
            </button>
        </div>

        <!-- Dropdown Button - visible on screens smaller than lg -->
        <div class="lg:hidden">
            <button id="dropdownButton" data-dropdown-toggle="actionDropdown" 
                class="px-4 py-2 text-sm rounded-lg flex items-center bg-slate-300 hover:bg-slate-500">
                <span class="material-symbols-outlined">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                        <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/>
                    </svg>
                </span>
            </button>

            <!-- Dropdown Menu -->
            <div id="actionDropdown" class="hidden absolute bg-white border shadow-md rounded-lg mt-1 w-36">
                <ul class="py-1 text-sm text-gray-700">
                    <li>
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-200 flex items-center">
                            <span class="material-symbols-outlined mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                    <path d="M200-120v-680h360l16 80h224v400H520l-16-80H280v280h-80Zm300-440Zm86 160h134v-240H510l-16-80H280v240h290l16 80Z"/>
                                </svg>    
                            </span> Report
                        </button>
                    </li>
                    <li>
                        <button class="w-full px-4 py-2 text-left hover:bg-gray-200 flex items-center">
                            <span class="material-symbols-outlined mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                    <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/>
                                </svg>
                            </span> Download
                        </button>
                    </li>
                    <li>
                        <button data-modal-target="shareModal" data-modal-toggle="shareModal" class="w-full px-4 py-2 text-left hover:bg-gray-200 flex items-center">
                            <span class="material-symbols-outlined mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                    <path d="M680-80q-50 0-85-35t-35-85q0-6 3-28L282-392q-16 15-37 23.5t-45 8.5q-50 0-85-35t-35-85q0-50 35-85t85-35q24 0 45 8.5t37 23.5l281-164q-2-7-2.5-13.5T560-760q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35q-24 0-45-8.5T598-672L317-508q2 7 2.5 13.5t.5 14.5q0 8-.5 14.5T317-452l281 164q16-15 37-23.5t45-8.5q50 0 85 35t35 85q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T720-200q0-17-11.5-28.5T680-240q-17 0-28.5 11.5T640-200q0 17 11.5 28.5T680-160ZM200-440q17 0 28.5-11.5T240-480q0-17-11.5-28.5T200-520q-17 0-28.5 11.5T160-480q0 17 11.5 28.5T200-440Zm480-280q17 0 28.5-11.5T720-760q0-17-11.5-28.5T680-800q-17 0-28.5 11.5T640-760q0 17 11.5 28.5T680-720Zm0 520ZM200-480Zm480-280Z"/>
                                </svg>
                            </span> Share
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    function copyUrl() {
        const urlInput = document.getElementById('courseUrl');
        urlInput.select();
        urlInput.setSelectionRange(0, 99999); // for mobile devices

        try {
            // Use modern clipboard API if available
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(urlInput.value);
            } else {
                // Fallback for older browsers
                document.execCommand('copy');
            }
        } catch (err) {
            console.error('Failed to copy: ', err);
            return;
        }

        // Show success icon, hide copy icon
        const copyIcon = document.getElementById('copy-icon');
        const successIcon = document.getElementById('success-icon');
        const copyButton = document.getElementById('copyButton');

        copyIcon.classList.add('hidden');
        successIcon.classList.remove('hidden');

        // Optional: change button color to blue to indicate success
        copyButton.classList.remove('text-gray-500', 'dark:text-gray-400');
        copyButton.classList.add('text-blue-600');

        // After 2 seconds, revert to original state
        setTimeout(() => {
            successIcon.classList.add('hidden');
            copyIcon.classList.remove('hidden');

            copyButton.classList.remove('text-blue-600');
            copyButton.classList.add('text-gray-500', 'dark:text-gray-400');
        }, 2000);
    }
</script>