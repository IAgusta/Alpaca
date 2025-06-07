@php
    use App\Models\UserCourse;
@endphp

<div class="lg:col-span-2 relative">
    @php
        $hasAccess = in_array(Auth::user()->role, ['admin', 'owner']) || 
                     $course->author === Auth::id() || 
                     (UserCourse::where('user_id', Auth::id())
                        ->where('course_id', $course->id)
                        ->exists());
    @endphp

    @if (!$hasAccess && $course->is_locked)
    <div class="absolute -inset-3 sm:-inset-4 md:-inset-6 rounded-2xl bg-gray-100/70 dark:bg-gray-900/70 backdrop-blur-md z-20 flex items-center justify-center">
        <div class="text-center text-black dark:text-white p-8">
            <h3 class="text-2xl font-bold mb-4">Access Restricted</h3>
            <p class="mb-6">Please save this course to access its content</p>
        </div>
    </div>
    @endif

    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold dark:text-white">Bagian</h2>
        <div class="flex items-center gap-2">
            @if($course->modules->count() > 0)
                <div class="flex gap-1">
                    {{-- Ascending --}}
                    <button
                        aria-label="Sort Ascending"
                        onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'asc']) }}'"
                        class="p-2 {{ request('sort', 'desc') === 'asc' ? 'text-gray-400 dark:text-gray-500 cursor-not-allowed' : 'text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }} rounded"
                        {{ request('sort', 'desc') === 'asc' ? 'disabled' : '' }}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 15l7-7 7 7"/>
                        </svg>
                    </button>
                    {{-- Descending --}}
                    <button
                        aria-label="Sort Descending"
                        onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => 'desc']) }}'"
                        class="p-2 {{ request('sort', 'desc') === 'desc' ? 'text-gray-400 dark:text-gray-500 cursor-not-allowed' : 'text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }} rounded"
                        {{ request('sort', 'desc') === 'desc' ? 'disabled' : '' }}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
                @php
                    $userId = Auth::id();
                    $totalModules = $course->modules->count();
                    $completedModules = \App\Models\UserModel::where('user_id', $userId)
                        ->whereHas('module', function ($query) use ($course) {
                            $query->where('course_id', $course->id);
                        })
                        ->where('read', true)
                        ->count();
                @endphp
                
                <x-primary-button id="toggleAllButton" data-course-id="{{ $course->id }}">
                    {{ $completedModules === $totalModules ? 'Mark All As Unread' : 'Mark All As Read' }}
                </x-primary-button>
            @endif
        </div>
    </div>
    <div class="mt-2 gap-4">
        @php
            $sortDirection = request('sort', 'desc');
            $modules = $course->modules->sortBy('position', SORT_REGULAR, $sortDirection === 'desc');
        @endphp
        
        @forelse($modules as $module)
            @php
                $progress = \App\Models\UserModel::where('user_id', auth()->id())
                    ->where('module_id', $module->id)
                    ->first();
                $isRead = $progress && $progress->read;
            @endphp
            
            <div class="module-container shadow mb-3 p-4 rounded-lg flex justify-between items-center border-l-4 transition-colors duration-200
                {{ $isRead ? 'bg-gray-200 border-gray-400 text-gray-600 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400' 
                        : 'bg-white border-blue-500 text-black dark:bg-gray-700 dark:border-blue-500 dark:text-white' }}"
                data-module-id="{{ $module->id }}"
                data-read="{{ $isRead ? 'true' : 'false' }}">
                
                <a href="{{ route('course.module.open', [
                    'slug' => Str::slug($course->slug),
                    'courseId' => $course->id,
                    'moduleTitle' => Str::slug($module->title),
                    'moduleId' => $module->id
                ]) }}" class="flex-grow flex items-start gap-3">
                    <div>
                        <p class="font-bold">Ch. {{ $module->position }} {{ $module->title }}</p>
                        <p class="text-sm transition-colors duration-200
                            {{ $isRead ? 'text-gray-500 dark:text-gray-400' : 'text-gray-600 dark:text-gray-300' }}">
                            Created at: {{ $module->created_at?->format('M d, Y') ?? 'N/A' }} | 
                            Updated at: {{ $module->updated_at?->format('M d, Y') ?? 'N/A' }}
                        </p>
                    </div>
                </a>
                
                <button class="toggle-button p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full" data-module-id="{{ $module->id }}">
                    @if ($isRead)
                        <svg xmlns="http://www.w3.org/2000/svg" class="visibility-icon w-6 h-6" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
                            <path d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="visibility-icon w-6 h-6" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
                            <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/>
                        </svg>
                    @endif
                </button>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <p>No modules available yet.</p>
            </div>
        @endforelse                  
    </div>
</div>