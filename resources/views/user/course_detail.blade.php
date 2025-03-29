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
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                        {{ $course->name }}
                    </div>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-7">
                <div class="flex gap-4">
                    <img src="{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}" alt="Course Image"
                        class="w-32 h-44 md:w-40 md:h-56 lg:w-52 lg:h-72 lg:mr-4 md:mr-2 object-cover rounded-lg">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold mb-6">{{ $course->name }}</h1>
                        <p class="text-gray-600 my-4">{{ $course->authorUser->name ?? 'Unknown' }}</p>
                        <div class="mt-4 flex gap-2">
                            @php
                                $isUserEnrolled = $userCourses->contains('course_id', $course->id);
                            @endphp
                            @if($isUserEnrolled)
                                <form method="POST" action="{{ route('user.course.delete', $course->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-4 py-2 rounded-lg flex items-center bg-orange-400 hover:bg-orange-500">
                                        <span class="material-symbols-outlined">bookmark_remove</span>
                                        <span class="ml-2">Remove</span>
                                    </button>
                                </form>
                            @else
                                @if($course->is_locked)
                                    <button data-modal-target="unlock-modal-{{ $course->id }}" data-modal-toggle="unlock-modal-{{ $course->id }}" class="px-4 py-2 rounded-lg flex items-center bg-orange-400 hover:bg-orange-500">
                                        <span class="material-symbols-outlined">lock</span>
                                        <span class="ml-2">Lock</span>
                                    </button>
                                @else
                                <form method="POST" action="{{ route('user.course.add', $course->id) }}">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    <button class="px-4 py-2 rounded-lg flex items-center bg-orange-400 hover:bg-orange-500">
                                        <span class="material-symbols-outlined">bookmark</span>
                                        <span class="ml-2">Save</span>
                                    </button>
                                </form>
                                @endif
                            @endif
                            <button class="px-4 py-2 rounded-lg flex items-center bg-slate-300 hover:bg-slate-500">
                                <span class="material-symbols-outlined">auto_stories</span>
                                <span class="ml-2 hidden lg:inline">Continue Reading</span>
                            </button>
                            <div class="hidden lg:flex gap-2">
                                <button class="px-4 py-2 rounded-lg flex items-center bg-slate-300 hover:bg-slate-500">
                                    <span class="material-symbols-outlined">report</span>
                                </button>
                                <button class="px-4 py-2 rounded-lg flex items-center bg-slate-300 hover:bg-slate-500">
                                    <span class="material-symbols-outlined">download</span>
                                </button>
                                <button class="px-4 py-2 rounded-lg flex items-center bg-slate-300 hover:bg-slate-500">
                                    <span class="material-symbols-outlined">share</span>
                                </button>
                            </div>
                        </div>

                        <!-- Unlock modal -->
                        @include('user.component.unlock_modal')

                        <div class="mt-4 flex gap-2">
                            @foreach(explode(',', $course->theme ?? 'Umum') as $theme)
                            <a href="#">
                                <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-lg dark:bg-blue-900 dark:text-blue-300">{{ $theme }}</span>
                            </a>
                            @endforeach
                        </div>
                        <div class="mt-4 flex items-center gap-4 text-gray-600">
                            <div class="flex items-center">
                                <span class="material-symbols-outlined">favorite</span>
                                <span class="ml-1">{{ $course->popularity }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="material-symbols-outlined">bookmark</span>
                                <span class="ml-1">{{ $savedCourses }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="material-symbols-outlined">chat</span>
                                <span class="ml-1">N/A</span>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="mt-4 text-gray-700">{{ $course->description ?? 'This course doesn`t have any description' }}</p>
                <div class="mt-6">
                    <div class="flex justify-between">
                        <h2 class="text-2xl font-bold">Bagian</h2>
                        <x-primary-button>Mark All As Read</x-primary-button>
                    </div>
                    <div class="mt-2 grid grid-cols-2 gap-4">
                        @foreach($course->modules as $index => $module)
                            <div class="border p-4 rounded-lg flex justify-between items-center">
                                <a href="{{ route('course.module.open', ['name' => Str::slug($course->name),'courseId' => $course->id,'moduleTitle' => Str::slug($module->title),'moduleId' => $module->id]) }}" class="flex-grow">
                                    <div class="flex items-start gap-3"> <!-- Added flex container -->
                                        <!-- Module number indicator -->
                                        <div class="flex items-center justify-center bg-gray-100 text-gray-800 rounded-full w-6 h-6 text-sm font-medium shrink-0">
                                            {{ $index + 1 }}
                                        </div>
                                        
                                        <div>
                                            <p class="font-bold">{{ $module->title }}</p>
                                            <p class="text-sm text-gray-500">Created at: {{ $module->created_at->format('M d, Y') }} | Updated at: {{ $module->updated_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </a>
                                <button>
                                    <span class="material-symbols-outlined">
                                        {{ !$module->is_completed ? 'visibility' : 'visibility_off' }}
                                    </span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
