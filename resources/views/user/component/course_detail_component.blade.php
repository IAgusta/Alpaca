<div class="mt-4 flex gap-2">
    @php
        $isUserEnrolled = $userCourses->contains('course_id', $course->id);
        
        // Get all modules for this course
        $modules = $course->modules;
        
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
                // All modules read - point to last module
                $nextModule = $modules->last();
                $buttonText = 'Review Last Module';
            } else {
                // Find the next unread module
                foreach ($modules as $module) {
                    if (!$readModules->contains($module->id)) {
                        $nextModule = $module;
                        $buttonText = 'Continue Reading';
                        break;
                    }
                }
            }
        } else {
            // No modules read - start with first module
            $nextModule = $modules->first();
        }
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

    @if($nextModule)
    <a href="{{ route('course.module.open', [
        'name' => Str::slug($course->name),
        'courseId' => $course->id,
        'moduleTitle' => Str::slug($nextModule->title),
        'moduleId' => $nextModule->id
    ]) }}" class="px-4 py-2 rounded-lg flex items-center bg-slate-300 hover:bg-slate-500">
        <span class="material-symbols-outlined">auto_stories</span>
        <span class="ml-2 hidden lg:inline">{{ $buttonText }}</span>
    </a>
    @else
    <button class="px-4 py-2 rounded-lg flex items-center bg-slate-300 hover:bg-slate-500" disabled>
        <span class="material-symbols-outlined">auto_stories</span>
        <span class="ml-2 hidden lg:inline">No Modules Available</span>
    </button>
    @endif

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
            <button data-modal-target="shareModal" data-modal-toggle="shareModal" class="px-4 py-2 text-sm rounded-lg bg-slate-300 hover:bg-slate-500 flex items-center">
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
                        <button data-modal-target="shareModal" data-modal-toggle="shareModal" class="w-full px-4 py-2 text-left hover:bg-gray-200 flex items-center">
                            <span class="material-symbols-outlined mr-2">share</span> Share
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div id="shareModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Share Course
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="shareModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-2">
                    <input type="text" id="courseUrl" value="{{ url()->current() }}" class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    <button onclick="copyUrl()" class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                        Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyUrl() {
    const urlInput = document.getElementById('courseUrl');
    urlInput.select();
    document.execCommand('copy');
    
    // Show feedback (optional)
    const copyButton = urlInput.nextElementSibling;
    const originalText = copyButton.textContent;
    copyButton.textContent = 'Copied!';
    setTimeout(() => {
        copyButton.textContent = originalText;
    }, 2000);
}
</script>