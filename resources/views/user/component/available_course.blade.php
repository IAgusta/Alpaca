<div class="flex flex-wrap gap-7 justify-start">
    @foreach ($availableCourses as $course)
        <div class="relative bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition flex flex-col" style="width: 208px; height: 350px;">
                {{-- Course Image --}}
                <a href="{{ route('user.course.detail', ['name' => Str::slug($course->name),'courseId' => $course->id]) }}" class="relative">
                    <img class="w-full h-35 object-cover rounded-t-lg" src="{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}" alt="Course Image" style="width: 206px; height: 154px; object-fit: cover;"/>
                    <!-- Lock/Unlock Icon -->
                    <div class="absolute top-2 left-2">
                        @if($course->is_locked)
                            <span class="material-symbols-outlined rounded-full p-1">
                                lock
                            </span>
                        @endif
                    </div>
                </a>
                <!-- Course Details -->
                <div class="flex flex-col h-full"> <!-- Added container for consistent layout -->
                    <a href="{{ route('user.course.detail', ['name' => Str::slug($course->name),'courseId' => $course->id]) }}" class="cursor-pointer flex-grow">
                        <div class="p-3 text-center h-full flex flex-col"> <!-- Added flex-col and h-full -->
                            <div class="relative flex flex-col items-center">
                                <!-- Course Title with Popover Button -->
                                <h5 class="flex justify-center text-sm font-medium text-gray-900 dark:text-white mx-auto text-center w-40">
                                    <p class="truncate">{{ $course->name }}</p>
                                    <button data-popover-target="popover-{{ $course->id }}" data-popover-placement="bottom" type="button" onclick="event.stopPropagation()">
                                        <svg class="w-3 h-3 text-gray-400 hover:text-gray-500" aria-hidden="true" fill="currentColor" 
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="sr-only">Show course description</span>
                                    </button>
                                </h5>
                            
                                <!-- Popover Content -->
                                <div data-popover id="popover-{{ $course->id }}" role="tooltip" 
                                    class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 
                                    bg-white border border-gray-200 rounded-lg shadow-md opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 
                                    dark:text-gray-400">
                                    <div class="p-3">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ ($course->name) }}</h3>
                                        <p>
                                            {{ $course->description ? $course->description : 'This Course doesn`t have any description.' }}
                                        </p>
                                    </div>
                                    <div data-popper-arrow></div>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 truncate">by {{ $course->authorUser->name ?? 'Unknown' }}</span>

                            <!-- Tags -->
                            <div class="mt-2 flex flex-wrap gap-1 justify-center">
                                @php
                                    $themes = explode(',', $course->theme ?? 'Umum');
                                    $limitedThemes = array_slice($themes, 0, 8);
                                @endphp
                                
                                @foreach($limitedThemes as $theme)
                                    @php
                                        $cleanTheme = trim($theme);
                                        $shortTheme = strlen($cleanTheme) > 10 ? substr($cleanTheme, 0, 10) . '…' : $cleanTheme;
                                    @endphp
                                    <span class="bg-slate-400 bg-opacity-20 text-[10px] px-2 py-[2px] rounded-md">
                                        {{ $shortTheme }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </a>
                    {{-- Stat Course --}}
                    @include('components.stat-available-courses')
                </div>
            </div>
    @endforeach
</div>