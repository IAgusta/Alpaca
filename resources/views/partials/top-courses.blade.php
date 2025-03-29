<!-- Courses Section -->
<div class="py-16 sm:py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:mx-0">
            <h2 class="text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl">Our Top Courses</h2>
            <p class="mt-2 text-lg/8 text-gray-600">Discover our best courses, packed with interactive content, expert guidance, and exciting challenges!</p>
        </div>
        <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
            @foreach($favoriteCourses as $course)
            <article class="flex max-w-xl flex-col items-start justify-between h-full">
                <div class="flex items-center gap-x-4 text-xs">
                    <time datetime="{{ $course->updated_at->format('Y-m-d') }}" class="text-gray-500">{{ $course->updated_at->format('M d, Y') }}</time>
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
                <div class="group relative flex-grow">
                    <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                        <a href="{{ route('user.course.detail', ['name' => Str::slug($course->name),'courseId' => $course->id]) }}">
                            <span class="absolute inset-0"></span>
                            <div class="flex gap-4 items-center">
                                <span class="text-lg font-semibold">{{ $course->name }}</span>
                                <div class="flex items-center text-sm gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                                    </svg>
                                    <span>{{ $course->popularity }}</span>
                                </div>
                            </div>
                        </a>
                    </h3>
                    <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">{{ $course->description ?? 'This Courses doenst have description yet' }}</p>
                </div>
                <div class="relative mt-8 flex items-center gap-x-4">
                    <img src="{{ $course->authorUser->image ? asset('storage/' .$course->authorUser->image): asset('storage/profiles/default-profile.png') }}" alt="author-image" class="size-10 rounded-full bg-gray-50">
                    <div class="text-sm/6">
                        <p class="font-semibold text-gray-900">
                            <span class="absolute inset-0"></span>
                            {{ $course->authorUser->name }}
                        </p>
                        <p class="text-gray-600">{{ ucfirst($course->authorUser->role) }}</p>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</div>