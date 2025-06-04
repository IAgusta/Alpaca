<x-app-layout>
    @section('title', e($course->name) . ' - '. config('app.name'))

    <div class="py-6">
        <!-- Background Image -->
        <div class="absolute inset-0 w-full h-[250px] lg:h-[275px] z-0 overflow-hidden">
            <!-- Gradient Overlay - make sure it covers the entire container -->
            <div class="absolute inset-0 bg-gradient-to-b from-transparent/0 via-transparent/0 to-white/100 dark:to-black/10 z-10"></div>
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
                        <div class="flex gap-4">
                            <img src="{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}" 
                                alt="Course Image"
                                class="w-32 h-44 md:w-40 md:h-56 lg:w-52 lg:h-72 lg:mr-4 md:mr-2 object-cover rounded-lg shadow-lg" 
                                loading="lazy">
                            <div class="flex-1">
                                <h1 class="text-4xl font-extrabold lg:mb-3 mb-2 text-black">{{ $course->name }}</h1>
                                {{-- Need to enable cURL SSL --}}
                                {{-- @php
                                    $tr = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
                                    $translatedName = $tr->translate($course->name);
                                @endphp --}}

                                <h2 class="text-2xl font-bold mb-6 lg:mb-11 text-black">{{-- $translatedName --}} {{ $course->name }}</h2>
                                <p class="text-gray-800 my-4">{{ $course->authorUser->name ?? 'Unknown' }}</p>
                                @include('user.component.course_detail_component')

                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach(explode(',', $course->theme ?? 'Umum') as $theme)
                                    <a href="{{ route('course.feed', ['search' => trim($theme)]) }}">
                                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-lg dark:bg-blue-900 dark:text-blue-300">{{ $theme }}</span>
                                    </a>
                                    @endforeach
                                </div>
                                <div class="mt-4 flex items-center gap-4 text-gray-800">
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
                <div class="p-7">
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
                            @include('user.component.module_content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/course/show.js'])
</x-app-layout>