<x-app-layout>
    @section('title', e($course->name) . ' - '. config('app.name'))

    <div class="py-6">
        <!-- Background Image -->
        <div class="absolute inset-0 w-full h-[250px] lg:h-[275px] z-0 overflow-hidden">
            <!-- Gradient Overlay - make sure it covers the entire container -->
            <div class="absolute inset-0 bg-gradient-to-b from-transparent/0 via-transparent/0 dark:via-black/20 to-white/100 dark:to-black/60 z-10"></div>
            <div class="w-full h-full"
                style="background-image: url('{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}');
                        background-size: cover;
                        background-position: top center;
                        filter: blur(3px);">
            </div>
        </div>

        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden">
                <!-- Top section with background image -->
                <div class="relative">
                    <!-- Content -->
                    <div class="relative z-20 p-7">
                        <div class="flex flex-col lg:flex-row gap-4 items-start lg:h-[288px] lg:min-h-[180px]">
                            <!-- Image: responsive sizing -->
                            <div class="flex-shrink-0 w-full lg:w-auto h-auto lg:h-full">
                                <img src="{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}"
                                    alt="Course Image"
                                    class="w-full lg:max-w-[208px] max-w-[160px] max-h-[238px] mx-auto lg:mx-0 lg:h-full md:w-40 md:h-full lg:w-52 object-cover rounded-lg shadow-lg"
                                    loading="lazy"
                                    style="max-height: 288px; min-height: 100px;"
                                >
                            </div>
                            <!-- Mobile: Theme tags and buttons below image -->
                            <div class="block lg:hidden w-full">
                                <div class="mt-3 flex flex-wrap gap-2 justify-center">
                                    @foreach(explode(',', $course->theme ?? 'Umum') as $theme)
                                    <a href="{{ route('course.feed', ['search' => trim($theme)]) }}">
                                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-lg dark:bg-blue-900 dark:text-blue-300">{{ $theme }}</span>
                                    </a>
                                    @endforeach
                                </div>
                                <div class="mt-4 flex items-center gap-4 justify-center text-gray-800 dark:text-gray-300">
                                    @include('user.component.partials.stat_detail')
                                </div>
                            </div>
                            <!-- Right content: always fixed height on lg, normal flow on mobile -->
                            <div class="flex-1 flex flex-col justify-between h-auto lg:h-full min-h-0 lg:min-h-[180px] lg:max-h-[288px]">
                                <div>
                                    <h1 class="text-4xl font-extrabold lg:mb-3 mb-2 text-black dark:text-white">{{ $course->name }}</h1>
                                    {{-- Need to enable cURL SSL --}}
                                    {{-- @php
                                        $tr = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
                                        $translatedName = $tr->translate($course->name);
                                    @endphp --}}

                                    <h2 class="text-2xl font-bold mb-6 lg:mb-11 text-black dark:text-white">{{-- $translatedName --}} {{ $course->name }}</h2>
                                    <p class="text-gray-800 dark:text-gray-300 my-4">{{ $course->authorUser->name ?? 'Unknown' }}</p>
                                    @include('user.component.course_detail_component')
                                </div>
                                <!-- lg: Theme tags and buttons in right panel, hidden on mobile -->
                                <div class="hidden lg:block">
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach(explode(',', $course->theme ?? 'Umum') as $theme)
                                        <a href="{{ route('course.feed', ['search' => trim($theme)]) }}">
                                            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-lg dark:bg-blue-900 dark:text-blue-300">{{ $theme }}</span>
                                        </a>
                                        @endforeach
                                    </div>
                                    <div class="mt-4 flex items-center gap-4 text-gray-800 dark:text-white">
                                        @include('user.component.partials.stat_detail')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unlock modal -->
                    @include('user.component.unlock_modal')

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
                                    <div class="w-full max-w-full relative">
                                    <label for="courseUrl" class="sr-only">Course URL</label>
                                    <input type="text" id="courseUrl" value="{{ url()->current() }}" readonly
                                            class="col-span-6 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-14 dark:bg-gray-700
                                                    dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500
                                                    dark:focus:border-blue-500"
                                            onclick="event.stopPropagation();">
                                        <button id="copyButton" onclick="copyUrl()" aria-label="Copy Course URL"
                                                class="absolute end-2 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400
                                                    hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg p-2 inline-flex items-center justify-center transition-colors">
                                        <!-- Default copy icon -->
                                        <svg id="copy-icon" class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                            <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
                                        </svg>

                                        <!-- Success check icon, hidden initially -->
                                        <svg id="success-icon" class="w-4 h-4 text-blue-600 hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                                        </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rest of the content -->
                <div class="p-3">
                    <p class="mt-4 text-gray-700 dark:text-gray-400">{{ $course->description ?? 'This course doesn`t have any description' }}</p>
                    <div class="mt-6">
                        <div class="mt-2 lg:grid lg:grid-cols-3 gap-4">
                            {{-- Courses more Details --}}
                            <div class="lg:col-span-1">
                                <!-- Author / Themes -->
                                <div class="flex gap-4 mb-7">
                                    <div>
                                        <h3 class="font-bold text-sm uppercase dark:text-white">Author</h3>
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
                                        <h3 class="font-bold text-sm uppercase dark:text-white">Themes</h3>
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
                                    <h3 class="font-bold text-sm uppercase mt-3 dark:text-white">Author Social Media</h3>
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
                                                    <img src="{{ asset('icons/' . $platform . '.svg') }}" alt="{{ $platform }} icon" class="w-5 h-5 dark:bg-gray-300 rounded-full p-1 bg-white">
                                                    {{-- Display username if available --}}
                                                    @if ($username)
                                                        <span class="text-sm dark:text-white">{{ $username }}</span>
                                                    @endif
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            {{-- Courses Modules --}}
                            @include('user.component.module_content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/course/show.js'])
</x-app-layout>