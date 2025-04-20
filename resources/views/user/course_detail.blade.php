<x-app-layout>
    @section('title', e($course->name) . ' - '. config('app.name'))

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-7">
                <div class="flex gap-4">
                    <img src="{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}" alt="Course Image"
                        class="w-32 h-44 md:w-40 md:h-56 lg:w-52 lg:h-72 lg:mr-4 md:mr-2 object-cover rounded-lg">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold mb-6">{{ $course->name }}</h1>
                        <p class="text-gray-600 my-4">{{ $course->authorUser->name ?? 'Unknown' }}</p>
                        @include('user.component.course_detail_component')

                        <!-- Unlock modal -->
                        @include('user.component.unlock_modal')

                        <div class="mt-4 flex gap-2">
                            @foreach(explode(',', $course->theme ?? 'Umum') as $theme)
                            <a href="{{ route('course.feed', ['search' => trim($theme)]) }}">
                                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-lg dark:bg-blue-900 dark:text-blue-300">{{ $theme }}</span>
                            </a>
                            @endforeach
                        </div>
                        <div class="mt-4 flex items-center gap-4 text-gray-600">
                            <div class="flex items-center">
                                <span class="material-symbols-outlined">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                        <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>
                                    </svg>
                                </span>
                                <span class="ml-1">{{ $course->popularity }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="material-symbols-outlined">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                        <path d="M160-80v-560q0-33 23.5-56.5T240-720h320q33 0 56.5 23.5T640-640v560L400-200 160-80Zm80-121 160-86 160 86v-439H240v439Zm480-39v-560H280v-80h440q33 0 56.5 23.5T800-800v560h-80ZM240-640h320-320Z"/>
                                    </svg>
                                </span>
                                <span class="ml-1">{{ $savedCourses }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="material-symbols-outlined">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                        <path d="M880-80 720-240H320q-33 0-56.5-23.5T240-320v-40h440q33 0 56.5-23.5T760-440v-280h40q33 0 56.5 23.5T880-640v560ZM160-473l47-47h393v-280H160v327ZM80-280v-520q0-33 23.5-56.5T160-880h440q33 0 56.5 23.5T680-800v280q0 33-23.5 56.5T600-440H240L80-280Zm80-240v-280 280Z"/>
                                    </svg>
                                </span>
                                <span class="ml-1">N/A</span>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="mt-4 text-gray-700">{{ $course->description ?? 'This course doesn`t have any description' }}</p>
                <div class="mt-6">
                    <div class="mt-2 lg:grid lg:grid-cols-3 gap-4">
                        {{-- Courses more Details --}}
                        <div class="lg:col-span-1">
                            <!-- Author / Themes -->
                            <div class="flex gap-4">
                                <div>
                                    <h3 class="font-bold text-sm uppercase">Author</h3>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <a href="/{{ $course->authorUser->username }}">
                                            <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-lg dark:bg-blue-900 dark:text-blue-300 truncate max-w-[120px]"
                                                title="{{ $course->authorUser->name }}">
                                                {{ Str::limit($course->authorUser->name, 12, "") ?? 'Unknown' }}
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm uppercase">Themes</h3>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        @foreach(explode(',', $course->theme ?? 'Umum') as $theme)
                                        <a href="{{ route('course.feed', ['search' => trim($theme)]) }}">
                                            <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-lg dark:bg-blue-900 dark:text-blue-300 truncate max-w-[120px]">{{ $theme }}</span>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            {{-- Author Social Media Links --}}
                            <div class="mt-4">
                                <h3 class="font-bold text-sm uppercase mt-3">Author Social Media</h3>
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2 mt-1 mb-7">
                                    @foreach (['facebook', 'instagram', 'x', 'linkedin', 'youtube', 'github'] as $platform)
                                        @php
                                            $socialMediaLinks = $course->authorUser->details->social_media ?? [];
                                            $link = $socialMediaLinks[$platform] ?? null;
                                            
                                            // Extract username from URL if link exists
                                            $username = null;
                                            if ($link) {
                                                $parsedUrl = parse_url($link);
                                                if (isset($parsedUrl['path'])) {
                                                    $username = ltrim($parsedUrl['path'], '/');
                                                    // Remove any trailing slashes
                                                    $username = rtrim($username, '/');
                                                }
                                            }
                                        @endphp
                                        @if ($link)
                                            <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1">
                                                <img src="{{ asset('icons/' . $platform . '.svg') }}" alt="{{ $platform }} icon" class="w-5 h-5">
                                                @if ($username)
                                                    <span class="text-sm">{{ $username }}</span>
                                                @endif
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        {{-- Courses Modules --}}
                        <div class="lg:col-span-2">
                            <div class="flex justify-between">
                                <h2 class="text-2xl font-bold">Bagian</h2>
                                @if($course->modules->count() > 0)
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
                            <div class="mt-2 gap-4">
                                @forelse($course->modules as $index => $module)
                                    @php
                                        $progress = \App\Models\UserModel::where('user_id', auth()->id())
                                            ->where('module_id', $module->id)
                                            ->first();
                                        $isRead = $progress && $progress->read;
                                    @endphp
                                    
                                    <div class="module-container mb-3 p-4 rounded-lg flex justify-between items-center border-l-4 transition-colors duration-200
                                        {{ $isRead ? 'bg-gray-200 border-gray-400 text-gray-600 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400' 
                                                : 'bg-white border-blue-500 text-black dark:bg-gray-900 dark:border-blue-500 dark:text-white' }}"
                                        data-module-id="{{ $module->id }}">
                                        
                                        <a href="{{ route('course.module.open', [
                                            'name' => Str::slug($course->name),
                                            'courseId' => $course->id,
                                            'moduleTitle' => Str::slug($module->title),
                                            'moduleId' => $module->id
                                        ]) }}" class="flex-grow flex items-start gap-3">
                                            <div>
                                                <p class="font-bold">Ch. {{ $index + 1 }} {{ $module->title }}</p>
                                                <p class="text-sm transition-colors duration-200
                                                    {{ $isRead ? 'text-gray-500 dark:text-gray-400' : 'text-gray-600 dark:text-gray-300' }}">
                                                    Created at: {{ $module->created_at?->format('M d, Y') ?? 'N/A' }} | 
                                                    Updated at: {{ $module->updated_at?->format('M d, Y') ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </a>
                                        
                                        <button class="toggle-button" data-module-id="{{ $module->id }}">
                                            <span class="material-symbols-outlined visibility-icon">
                                                @if ($isRead)
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#B7B7B7">
                                                        <path d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"/>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#00000">
                                                        <path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"/>
                                                    </svg>
                                                @endif
                                            </span>
                                        </button>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-gray-500">
                                        <p>No modules available yet.</p>
                                    </div>
                                @endforelse                  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/course/show.js'])
</x-app-layout>