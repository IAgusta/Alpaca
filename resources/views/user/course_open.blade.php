<x-app-layout>
    @section('title', e($module->title) . ' | ' . e($course->name) . ' - ' . config('app.name'))
    <x-slot name="header">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('user.course') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight hover:text-blue-600">{{ __('Kelas') }}</h2>
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('user.course.detail', ['name' => Str::slug($course->name),'courseId' => $course->id]) }}">
                            <h2 class="cursor-pointer truncate font-semibold text-xl text-gray-800 leading-tight hover:text-blue-600">{{ Str::limit($course->name, 18, '...')  }}</h2>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <!-- Limited title for screens smaller than lg -->
                        <span class="block lg:hidden ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                            {{ Str::limit($module->title, 18, '...') }}
                        </span>

                        <!-- Full title for lg and larger screens -->
                        <span class="hidden lg:block ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
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
                    <div class="mt-4 flex flex-col space-y-4 text-gray-600 items-end">
                        <div class="flex items-center">
                            <span class="mr-1">N/A</span>
                            <span class="material-symbols-outlined">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                    <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">N/A</span>
                            <span class="material-symbols-outlined">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                    <path d="M160-80v-560q0-33 23.5-56.5T240-720h320q33 0 56.5 23.5T640-640v560L400-200 160-80Zm80-121 160-86 160 86v-439H240v439Zm480-39v-560H280v-80h440q33 0 56.5 23.5T800-800v560h-80ZM240-640h320-320Z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">N/A</span>
                            <span class="material-symbols-outlined">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                                    <path d="M880-80 720-240H320q-33 0-56.5-23.5T240-320v-40h440q33 0 56.5-23.5T760-440v-280h40q33 0 56.5 23.5T880-640v560ZM160-473l47-47h393v-280H160v327ZM80-280v-520q0-33 23.5-56.5T160-880h440q33 0 56.5 23.5T680-800v280q0 33-23.5 56.5T600-440H240L80-280Zm80-240v-280 280Z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="col-span-3 p-2 bg-white shadow-sm sm:rounded-lg h-full flex">
                    <div class="w-full max-w-6xl p-4 h-auto" id="main-content">
                        <div class="module-content" data-module-id="{{ $module->id }}">
                            <h1 class="text-2xl font-bold mt-4 flex items-center">
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
                            <h3 class="text-sm text-gray-500 font-thin">{{ __('Dibuat pada : ') }}{{ $module->created_at ? $module->created_at->translatedFormat('d F Y,') . ' ' . __('Jam : ') . $module->created_at->format('H:i') . __(' WIB') : '-' }}</h3>
                            <h3 class="text-sm text-gray-500 font-thin">{{ __('Diupdate pada : ') }}{{ $module->updated_at ? $module->updated_at->translatedFormat('d F Y,') . ' ' . __('Jam : ') . $module->updated_at->format('H:i') . __(' WIB') : '-' }}</h3>
                            <!-- Divider Line Above Title -->
                            <div class="border-t border-gray-200 w-full my-4"></div>
                            @if($module->contents)
                                @foreach($module->contents as $content)
                                    <div id="content-{{ $content->id }}" class="mb-6">
                                        <h2 class="text-xl font-semibold mt-2 text-center">{{ $content->title }}</h2>
                                        @if($content->content_type === 'exercise')
                                            @php
                                                $exercise = json_decode($content->content, true);
                                            @endphp
                                            <div class="exercise-container mx-auto my-4 p-4 border border-gray-300 rounded-lg bg-gray-50">
                                                <form class="exercise-form" data-explanation="{{ $exercise['explanation'] }}">
                                                    <p class="font-semibold">{!! $exercise['question'] !!}</p>
                                                    @foreach($exercise['answers'] as $index => $answer)
                                                        <div class="my-2">
                                                            <input type="radio" id="answer-{{ $content->id }}-{{ $index }}" name="answer-{{ $content->id }}" value="{{ $answer['correct'] }}">
                                                            <label for="answer-{{ $content->id }}-{{ $index }}">{{ $answer['text'] }}</label>
                                                        </div>
                                                    @endforeach
                                                    <button type="button" class="submit-answer mt-2 px-4 py-2 bg-blue-500 text-white rounded">Submit</button>
                                                </form>
                                                <div class="explanation hidden mt-4 p-2 border-t border-gray-200">
                                                    <span class="icon"></span>
                                                    <span class="explanation-text"></span>
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
                <div class="hidden lg:block col-span-1 bg-white shadow-sm rounded-lg p-4 h-full overflow-y-auto">
                    <h2 class="text-xl font-semibold mb-4">{{ __('Kelas Terbaru dari Author yang sama') }}</h2>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/content/style.js'])
</x-app-layout>