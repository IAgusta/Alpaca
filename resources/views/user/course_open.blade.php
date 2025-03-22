<x-app-layout>
    <x-slot name="header">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('user.course') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight hover:text-blue-600">{{ __('Kelas') }}</h2>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ $course->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 h-screen overflow-hidden">
            <div class="mx-auto p-2 bg-white overflow-hidden shadow-sm sm:rounded-lg h-full flex">
                <!-- Toggle Button for All Screens -->
                <button class="p-2 cursor-pointer text-gray-500 hover:text-gray-700" 
                        data-drawer-target="drawer-disable-body-scrolling"
                        data-drawer-show="drawer-disable-body-scrolling"
                        data-drawer-backdrop="true"
                        data-drawer-body-scrolling="false"
                        type="button">
                    <span class="material-symbols-outlined">
                        keyboard_double_arrow_right
                    </span>
                </button>

                <!-- Drawer Navigation -->
                @include('user.component.drawer_navigation')
                
                <!-- Main Content -->
                <div class="w-full p-4 overflow-y-auto" id="main-content">
                    @if($course->modules)
                        @foreach($course->modules as $index => $module)
                            <div class="module-content {{ $index === 0 ? '' : 'hidden' }}" data-module-index="{{ $index }}" data-module-id="{{ $module->id }}">
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
                                @if($module->contents)
                                    @foreach($module->contents as $content)
                                        <div id="content-{{ $content->id }}">
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
                                                <div class="ql-editor p-0 border-0 shadow-none"> <!-- Add ql-editor class here -->
                                                    {!! $content->content !!} <!-- Quill-formatted content -->
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                        <!-- Navigation Buttons -->
                        <div class="flex justify-center gap-4 mt-4">
                            <button id="prev-module" class="px-4 py-2 bg-gray-500 text-white rounded hidden">Kembali</button>
                            <button id="next-module" class="px-4 py-2 bg-blue-500 text-white rounded">Lanjut</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/content.js', 'resources/js/content-feature.js'])
</x-app-layout>