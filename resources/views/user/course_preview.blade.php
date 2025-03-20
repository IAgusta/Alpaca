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

                <div class="w-full lg:w-max-full p-4 overflow-y-auto" id="main-content">
                    @if($course->modules)
                        @php $module = $course->modules->first(); @endphp
                        <h1 class="text-2xl font-bold mt-4 flex items-center">
                            {{ $module->title }}
                        </h1>
                        <h3 class="text-sm text-gray-500 font-thin">{{ __('Dibuat pada : ') }}{{ $module->created_at ? $module->created_at->translatedFormat('d F Y,') . ' ' . __('Jam : ') . $module->created_at->format('H:i') . __(' WIB') : '-' }}</h3>
                        <h3 class="text-sm text-gray-500 font-thin">{{ __('Diupdate pada : ') }}{{ $module->updated_at ? $module->updated_at->translatedFormat('d F Y,') . ' ' . __('Jam : ') . $module->updated_at->format('H:i') . __(' WIB') : '-' }}</h3>
                        @if($module->contents)
                            @foreach($module->contents->take(1) as $index => $content)
                                <div id="content-{{ $content->id }}">
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
                                        <div class="ql-editor p-0 border-0 shadow-none relative">
                                            @if($index === 1)
                                                <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gray-200 opacity-50 blur-md cursor-pointer"
                                                    onclick="history.back()"></div>
                                            @endif
                                            {!! $content->content !!}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/content.js'])
</x-app-layout>