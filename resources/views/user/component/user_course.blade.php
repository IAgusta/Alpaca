<h2 class="text-md font-semibold mb-3">Your Course</h2>
<div class="flex flex-wrap gap-7 justify-start">
    @foreach ($userCourses as $userCourse)
        <div class="relative bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition flex flex-col" style="width: 208px; height: 350px;">
            {{-- Course Image --}}
            <a href="{{ route('user.course.detail', ['courseId' => $userCourse->course->id]) }}" class="relative">
                <img class="w-full h-35 object-cover rounded-t-lg" src="{{ $userCourse->course->image ? asset('storage/'.$userCourse->course->image) : asset('storage/courses/default-course.png') }}" alt="Course Image" style="width: 206px; height: 154px; object-fit: cover;"/>
                @if ($userCourse->course_id != 1) <!-- Exclude dropdown for quickstart course -->
                    <button data-dropdown-toggle="courseDropdown-{{ $userCourse->course->id }}" class="absolute top-2 right-0 bg-transparent p-2 rounded-full hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                        </svg>
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="courseDropdown-{{ $userCourse->course->id }}" class="hidden absolute right-2 top-10 z-10 w-fit bg-white rounded-lg shadow-lg dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                            <li>
                                <form method="POST" action="{{ route('user.course.clearHistory', $userCourse->course->id) }}">
                                    @csrf
                                    <button type="submit" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Hapus Riwayat</button>
                                </form>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('user.course.delete', $userCourse->course->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="block w-full px-4 py-2 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Lupakan Kelas</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            </a>
            {{-- Course Details --}}
            <a href="{{ route('user.course.detail', ['courseId' => $userCourse->course->id]) }}">
                <div class="p-3 text-center flex-grow">
                    <div class="relative flex flex-col items-center">
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mx-auto text-center w-40">
                            {{ ucwords(strtolower(Str::limit($userCourse->course->name, 20, '...'))) }}
                            <button data-popover-target="popover-{{ $userCourse->course->id }}" data-popover-placement="bottom" type="button">
                                <svg class="w-3 h-3 text-gray-400 hover:text-gray-500" aria-hidden="true" fill="currentColor" 
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Show course description</span>
                            </button>
                        </h5>
                        
                        <div data-popover id="popover-{{ $userCourse->course->id }}" role="tooltip" 
                            class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 
                            bg-white border border-gray-200 rounded-lg shadow-md opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 
                            dark:text-gray-400">
                            <div class="p-3">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $userCourse->course->name }}</h3>
                                <p>
                                    {{ $userCourse->course->description ? $userCourse->course->description : 'This Course doesn’t have any description.' }}
                                </p>
                            </div>
                            <div data-popper-arrow></div>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">by {{ $userCourse->course->authorUser->name ?? 'Unknown' }}</span>
                    
                    {{-- Module Theme --}}
                    @php
                    $themeString = !empty(trim($userCourse->course->theme)) ? $userCourse->course->theme : 'umum';
                    $themes = explode(',', $themeString);
                    $totalThemes = count($themes);
                    $displayThemes = array_slice($themes, 0, 2);
                    
                    $colors = [
                        'bg-blue-100 text-blue-800',
                        'bg-red-100 text-red-800',
                        'bg-green-100 text-green-800',
                        'bg-yellow-100 text-yellow-800'
                    ];
                    @endphp
                    
                    @if(count($themes) > 0)
                        <div class="flex flex-wrap gap-2 mt-2 justify-center">
                            @foreach($displayThemes as $theme)
                                @php
                                    $formattedTheme = ucwords(trim($theme));
                                    if(strtolower($formattedTheme) === 'umum') {
                                        $formattedTheme = 'Umum';
                                    }
                                @endphp
                                
                                <span class="{{ $colors[$loop->index % count($colors)] }} text-xs font-medium px-2.5 py-0.5 rounded-sm space-x-2">
                                    {{ $formattedTheme }}
                                </span>
                            @endforeach
                            
                            @if($totalThemes > 2)
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                    +{{ $totalThemes - 2 }} more
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="p-2 flex flex-col items-center">
                    {{-- Progress Bar --}}
                    @php
                    $totalModules = $userCourse->total_modules;
                    $completedModules = $userCourse->completed_modules;
                    $progressPercentage = $totalModules > 0 ? ($completedModules / $totalModules) * 100 : 0;
                    @endphp
                    <div class="w-full bg-gray-300 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $progressPercentage }}%;"></div>
                    </div>
                    <div class="my-1 text-xs">{{ $completedModules }}/{{ $totalModules }} Complete</div>
                </div>
            </a>
        </div>
    @endforeach
</div>