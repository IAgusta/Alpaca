<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Courses') }} 
            @if (Auth::user()->role != 'user')
                <a href="{{ route('admin.courses.index') }}">
                    <x-secondary-button class="ml-4">Back</x-secondary-button>
                </a>
            @endif
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="mx-auto p-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Your Course Section --}}
                <h2 class="text-md font-semibold mb-3">Your Course</h2>
                <!-- Success Message -->
                @if(session('success'))
                    <x-input-success :messages="[session('success')]" class="mt-4" />
                @endif
                <div class="flex flex-wrap gap-4 justify-start">
                    @foreach ($userCourses as $userCourse)
                        <div class="relative w-52 h-auto max-h-410px bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition">
                            {{-- Course Image --}}
                            <div class="relative">
                                <img class="w-full h-35 object-cover rounded-t-lg" src="{{ $userCourse->course->image ? asset('storage/'.$userCourse->course->image) : asset('img/logo.png') }}" alt="Course Image"/>
                                @if ($userCourse->course_id != 1) <!-- Exclude dropdown for quickstart course -->
                                    <button data-dropdown-toggle="courseDropdown-{{ $userCourse->course->id }}" class="absolute top-2 right-0 bg-transparent p-2 rounded-full hover:bg-gray-200">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                                            <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z"/>
                                        </svg>
                                    </button>
                                    <!-- Dropdown Menu -->
                                    <div id="courseDropdown-{{ $userCourse->course->id }}" class="hidden absolute right-2 top-10 z-10 w-fit bg-white rounded-lg shadow-lg dark:bg-gray-700">
                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                                            <li>
                                                <form method="POST" action="{{ route('user.course.clearHistory', $userCourse->course->id) }}">
                                                    @csrf
                                                    <button type="submit" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Clear History</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('user.course.delete', $userCourse->course->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="block px-4 py-2 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            {{-- Course Details --}}
                            <div class="p-2 text-center">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{ $userCourse->course->name }}</h5>
                                <span class="text-xs text-gray-500 dark:text-gray-400">by {{ $userCourse->course->authorUser->name ?? 'Unknown' }}</span>
                                
                                {{-- Module Theme --}}
                                @php
                                $themeString = !empty(trim($userCourse->course->theme)) ? $userCourse->course->theme : 'umum';
                                $themes = explode(',', $themeString);
                                $totalThemes = count($themes);
                                $displayThemes = array_slice($themes, 0, 3);
                                
                                $colors = [
                                    'bg-blue-100 text-blue-800',
                                    'bg-gray-100 text-gray-800',
                                    'bg-red-100 text-red-800',
                                    'bg-green-100 text-green-800',
                                    'bg-yellow-100 text-yellow-800'
                                ];
                                @endphp
                                
                                @if(count($themes) > 0)
                                    <div class="flex flex-wrap gap-2 my-2 justify-center">
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
                                        
                                        @if($totalThemes > 3)
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                                +{{ $totalThemes - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                {{-- Progress Bar --}}
                                @php
                                $totalModules = $userCourse->course->modules->count();
                                $progressPercentage = $totalModules > 0 ? ($userCourse->completed_modules / $totalModules) * 100 : 0;
                                @endphp
                                <div class="w-full bg-gray-300 rounded-full h-2 mt-1">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $progressPercentage }}%;"></div>
                                </div>
                                <div class="mt-1 text-xs">{{ $userCourse->completed_modules }}/{{ $totalModules }} Complete</div>
                                {{-- Open Button --}}
                                <x-primary-button class="mt-2">
                                    <a href="{{ route('user.course.open', $userCourse->course->id) }}">Open</a>
                                </x-primary-button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Available Courses Section --}}
                <h2 class="text-md font-semibold mt-4 mb-3">Available Courses</h2>
                <!-- Error Message -->
                @if(session('error'))
                    <x-input-error :messages="[session('error')]" />
                @endif
                <div class="flex flex-wrap gap-4 justify-start">
                    @foreach ($availableCourses as $course)
                        @if ($course->id != 1) <!-- Exclude Course 1 from available courses -->
                            <div class="relative w-52 h-auto max-h-410px bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition">
                                {{-- Course Image --}}
                                <div class="relative">
                                    <img class="w-full h-35 object-cover rounded-t-lg" src="{{ $course->image ? asset('storage/'.$course->image) : asset('img/logo.png') }}" alt="Course Image"/>
                                </div>
                                {{-- Course Details --}}
                                <div class="p-3 text-center">
                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{ ucwords(strtolower(Str::limit($course->name, 20, '...'))) }}</h5>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">by {{ Str::limit($course->authorUser->name, 32, '...') ?? 'Unknown' }}</span>
                                    
                                    {{-- Module Theme --}}
                                    @php
                                    $themeString = !empty(trim($course->theme)) ? $course->theme : 'umum';
                                    $themes = explode(',', $themeString);
                                    $totalThemes = count($themes);
                                    $displayThemes = array_slice($themes, 0, 3);
                                    
                                    $colors = [
                                        'bg-blue-100 text-blue-800',
                                        'bg-gray-100 text-gray-800',
                                        'bg-red-100 text-red-800',
                                        'bg-green-100 text-green-800',
                                        'bg-yellow-100 text-yellow-800'
                                    ];
                                    @endphp
                                    
                                    @if(count($themes) > 0)
                                        <div class="flex flex-wrap gap-2 my-2 justify-center">
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
                                            
                                            @if($totalThemes > 3)
                                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                                    +{{ $totalThemes - 3 }} more
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <p class="text-xs mt-1">Total: <span class="font-bold">{{ $course->modules->count() }} Chapters</span></p>
                                    {{-- Add & Preview Buttons --}}
                                    <div class="flex mt-2 mb-2 justify-center space-x-2">
                                        <x-secondary-button href="{{ route('user.course.preview', $course->id) }}">Preview</x-secondary-button>
                                        @if($course->is_locked)
                                            <x-primary-button data-modal-target="unlock-modal-{{ $course->id }}" data-modal-toggle="unlock-modal-{{ $course->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
                                                  </svg>
                                                Lock
                                            </x-primary-button>
                                        @else
                                            <form method="POST" action="{{ route('user.course.add', $course->id) }}">
                                                @csrf
                                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                                <x-primary-button type="submit">Add</x-primary-button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Unlock modal -->
                            <div id="unlock-modal-{{ $course->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="relative p-4 w-full max-w-md max-h-full">
                                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                Unlock Course
                                            </h3>
                                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="unlock-modal-{{ $course->id }}">
                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                </svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>
                                        <div class="relative p-4 md:p-5">
                                            <form action="{{ route('user.course.add', $course->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                                <div>
                                                    <x-input-label for="lock_password" :value="__('Enter Your Course Password')" />
                                                    <x-text-input id="lock_password" class="block mt-1 w-full" type="password" name="lock_password" required />
                                                    <x-input-error :messages="$errors->get('lock_password')" class="mt-2" />
                                                </div>
                                                <div class="mt-4">
                                                    <x-primary-button type="submit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-unlock" viewBox="0 0 16 16">
                                                            <path d="M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2M3 8a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1z"/>
                                                          </svg>
                                                        {{ __('Unlock') }}
                                                    </x-primary-button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>