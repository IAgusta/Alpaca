<x-app-layout>
    @section('title', e($module->title) . ' | ' . e($course->display_name) . ' - ' . config('app.name'))
    <x-slot name="header">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <div class="flex items-center">
                        <a class="flex gap-2 justify-center items-center" href="{{ route('user.course.detail', ['slug' => Str::slug($course->slug),'courseId' => $course->id]) }}">
                            <img src="{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}" alt="thumbnail" class="w-8 h-8 rounded-sm object-cover">
                            <h2 class="cursor-pointer truncate font-semibold text-sm text-gray-800 dark:text-white leading-tight hover:text-blue-600">{{ Str::limit($course->display_name, 32, '...')  }}</h2>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <!-- Limited title for screens smaller than lg -->
                        <span class="block lg:hidden ms-1 text-xs font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                            {{ Str::limit($module->title, 18, '...') }}
                        </span>

                        <!-- Full title for lg and larger screens -->
                        <span class="hidden lg:block ms-1 text-xs font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                            {{ $module->title }}
                        </span>
                    </div>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 overflow-hidden">
            <div class="lg:grid lg:grid-cols-5 gap-4 h-full">
                <!-- Left Sidebar -->
                <div class="hidden lg:block col-span-1 rounded-lg p-4 h-full overflow-y-auto">
                    <div class="mt-4 flex flex-col space-y-4 text-gray-600 dark:text-gray-200 items-end">
                        <div class="flex items-center">
                            <span class="mr-1">N/A</span>
                            <span class="material-symbols-outlined text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
                                    <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">N/A</span>
                            <span class="material-symbols-outlined text-orange-300">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
                                    <path d="M160-80v-560q0-33 23.5-56.5T240-720h320q33 0 56.5 23.5T640-640v560L400-200 160-80Zm80-121 160-86 160 86v-439H240v439Zm480-39v-560H280v-80h440q33 0 56.5 23.5T800-800v560h-80ZM240-640h320-320Z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">N/A</span>
                            <span class="material-symbols-outlined text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
                                    <path d="M880-80 720-240H320q-33 0-56.5-23.5T240-320v-40h440q33 0 56.5-23.5T760-440v-280h40q33 0 56.5 23.5T880-640v560ZM160-473l47-47h393v-280H160v327ZM80-280v-520q0-33 23.5-56.5T160-880h440q33 0 56.5 23.5T680-800v280q0 33-23.5 56.5T600-440H240L80-280Zm80-240v-280 280Z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="col-span-3 p-2 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg h-full flex">
                    <div class="w-full max-w-6xl p-4 h-auto" id="main-content">
                        <div class="module-content" data-module-id="{{ $module->id }}">
                            <h1 class="text-2xl font-bold mt-4 flex items-center dark:text-white">
                                {{ $module->title }}
                                @if((int) Auth::id() === (int) $course->author)
                                    <a href="{{ route('admin.courses.modules.contents.index', ['course' => $course->id, 'module' => $module->id]) }}" class="ml-2 text-blue-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </a>
                                @endif
                            </h1>
                            <h3 class="text-sm text-gray-500 dark:text-gray-200 font-thin">{{ __('Dibuat pada : ') }}{{ $module->created_at ? $module->created_at->translatedFormat('d F Y,') . ' ' . __('Jam : ') . $module->created_at->format('H:i') . __(' WIB') : '-' }}</h3>
                            <h3 class="text-sm text-gray-500 dark:text-gray-200 font-thin">{{ __('Diupdate pada : ') }}{{ $module->updated_at ? $module->updated_at->translatedFormat('d F Y,') . ' ' . __('Jam : ') . $module->updated_at->format('H:i') . __(' WIB') : '-' }}</h3>
                            <!-- Divider Line Above Title -->
                            <div class="border-t border-gray-200 w-full my-4"></div>
                            @if($module->contents)
                                @foreach($module->contents as $content)
                                    <div id="content-{{ $content->id }}" class="mb-6">
                                        <h2 class="text-xl font-semibold mt-2 text-center dark:text-white">{{ $content->title }}</h2>
                                        @if($content->content_type === 'exercise')
                                            @php
                                                $exercise = json_decode($content->content, true);
                                            @endphp
                                            <div class="exercise-container max-w-lg mx-auto my-8 p-6 rounded-2xl shadow-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-slate-500 transition-all relative">
                                                <!-- Overlay (hidden by default) -->
                                                <div class="exercise-overlay absolute inset-0 bg-white/70 dark:bg-gray-900/70 z-10 hidden rounded-2xl flex items-center justify-center"></div>
                                                <!-- Floating result card (hidden by default) -->
                                                <div class="exercise-result-card absolute left-1/2 -translate-x-1/2 z-20 hidden min-w-[260px] px-6 py-4 rounded-xl shadow-lg flex flex-col items-center border-2 border-gray-200 bg-white dark:bg-gray-800">
                                                    <div class="result-icon mb-2"></div>
                                                    <div class="result-message font-semibold text-lg mb-1"></div>
                                                    <div class="result-explanation text-gray-700 dark:text-gray-200 text-base text-center"></div>
                                                </div>
                                                <form class="exercise-form space-y-5" data-explanation="{{ $exercise['explanation'] }}">
                                                    <p class="font-semibold text-lg text-gray-800 mb-4">{!! $exercise['question'] !!}</p>
                                                    <div class="space-y-3">
                                                        @foreach($exercise['answers'] as $index => $answer)
                                                            <label class="flex items-center gap-3 cursor-pointer px-3 py-2 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-500 transition group border border-transparent focus-within:border-blue-400">
                                                                <input 
                                                                    type="radio" 
                                                                    id="answer-{{ $content->id }}-{{ $index }}" 
                                                                    name="answer-{{ $content->id }}" 
                                                                    value="{{ $answer['correct'] }}"
                                                                    class="accent-blue-500 h-5 w-5 transition-all group-hover:scale-110"
                                                                >
                                                                <span class="text-gray-700 dark:text-white text-base">{{ $answer['text'] }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    <button 
                                                        type="button" 
                                                        class="submit-answer w-full mt-4 px-5 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-bold shadow hover:from-blue-700 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                                                    >
                                                        Submit
                                                    </button>
                                                </form>
                                                <div class="explanation hidden mt-6 px-4 py-3 rounded-lg bg-blue-50 border-t border-blue-200 flex items-start gap-2">
                                                    <span class="icon text-blue-500 mt-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" /></svg>
                                                    </span>
                                                    <span class="explanation-text text-blue-800 font-medium"></span>
                                                </div>
                                            </div>

                                        @else
                                            <div class="content-preview">
                                                <div class="px-4 py-2 bg-white rounded-b-lg dark:bg-gray-800">
                                                    <div id="wysiwyg-preview-{{ $content->id }}" class="block w-full px-0 text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400">
                                                        {!! $content->content !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="hidden lg:block max-h-screen col-span-1 bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 h-full overflow-y-auto">
                    <h2 class="text-xl dark:text-white font-semibold mb-4">{{ __('Kelas Terbaru dari Author yang sama') }}</h2>
                </div>
            </div>
            <!-- Navigation Buttons -->
            <div class="mt-6 mb-2 flex justify-between items-center px-4">
                {{-- Previous Button on the left --}}
                <div>
                    @if($previousModule)
                        <a href="{{ route('course.module.open', ['slug' => Str::slug($course->slug), 'courseId' => $course->id, 'moduleTitle' => Str::slug($previousModule->title), 'moduleId' => $previousModule->id]) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 rotate-180" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                            </svg>
                            <span>{{ __('Previous') }}</span>
                        </a>
                    @endif
                </div>

                {{-- Next or Return Button on the right --}}
                <div>
                    @if($nextModule)
                        <a href="{{ route('course.module.open', ['slug' => Str::slug($course->slug), 'courseId' => $course->id, 'moduleTitle' => Str::slug($nextModule->title), 'moduleId' => $nextModule->id]) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                            <span>{{ __('Next') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8.59 16.59 13.17 12 8.59 7.41 10 6l6 6-6 6z"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('user.course.detail', ['slug' => Str::slug($course->slug), 'courseId' => $course->id]) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-700 dark:hover:bg-gray-800">
                            <span>{{ __('Return to Menu') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/content/style.js', 'resources/js/content/exercise-form.js'])
</x-app-layout>