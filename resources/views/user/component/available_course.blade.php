<h2 class="text-md font-semibold mt-4 mb-3">Available Courses</h2>
<div class="flex flex-wrap gap-4 justify-start">
    @foreach ($availableCourses as $course)
        @if ($course->id != 1) <!-- Exclude Course 1 from available courses -->
        <div class="relative bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition flex flex-col" style="width: 208px; height: 373px;">
                {{-- Course Image --}}
                <div class="relative">
                    <img class="w-full h-35 object-cover rounded-t-lg" src="{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}" alt="Course Image" style="width: 206px; height: 154px; object-fit: cover;"/>
                </div>
                {{-- Course Details --}}
                <div class="p-3 text-center flex-grow">
                    <div class="relative flex flex-col items-center">
                        <!-- Course Title with Popover Button -->
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mx-auto text-center w-40">
                            {{ ucwords(strtolower(Str::limit($course->name, 20, '...'))) }}
                            <button data-popover-target="popover-{{ $course->id }}" data-popover-placement="bottom" type="button">
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
                                    {{ $course->description ? $course->description : 'This Course doesn’t have any description.' }}
                                </p>
                            </div>
                            <div data-popper-arrow></div>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">by {{ Str::limit($course->authorUser->name, 32, '...') ?? 'Unknown' }}</span>
                    
                    {{-- Module Theme --}}
                    @php
                    $themeString = !empty(trim($course->theme)) ? $course->theme : 'umum';
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
                            
                            @if($totalThemes > 2)
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                    +{{ $totalThemes - 2 }} more
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="p-3 flex flex-col items-center">
                    <p class="text-xs mt-1">Total: <span class="font-bold">{{ $course->modules->count() }} Bagian</span></p>
                    {{-- Add & Preview Buttons --}}
                    <div class="flex mt-2 mb-2 justify-center space-x-2">
                        <a href="{{ route('user.course.preview', $course->id) }}">
                            <x-secondary-button>Preview</x-secondary-button>
                        </a>
                        @if($course->is_locked)
                            <x-primary-button data-modal-target="unlock-modal-{{ $course->id }}" data-modal-toggle="unlock-modal-{{ $course->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
                                  </svg>
                                Kunci
                            </x-primary-button>
                        @else
                            <form method="POST" action="{{ route('user.course.add', $course->id) }}">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                <x-primary-button type="submit">Tambah</x-primary-button>
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
                                Buka Course
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
                                    <div class="flex my-2">
                                        <x-text-input id="lock_password" class="block mt-1 w-full" type="password" name="lock_password" required />
                                        <x-input-error :messages="$errors->get('lock_password')" class="mt-2" />
                                        <x-primary-button class="ml-2" type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-unlock" viewBox="0 0 16 16">
                                                <path d="M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2M3 8a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1z"/>
                                            </svg>
                                            {{ __('Buka') }}
                                        </x-primary-button>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Tidak Punya Password? <a href="" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Minta Izin</a></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>