@php
use App\Models\UserContentProgress;
@endphp

<div id="drawer-disable-body-scrolling"
    class="fixed top-0 left-0 z-50 h-screen p-4 transition-transform -translate-x-full 
           bg-white w-72 dark:bg-gray-800 shadow-lg" 
    tabindex="-1" 
    aria-labelledby="drawer-disable-body-scrolling-label">

    <div class="py-4">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">{{ $course->name }}</h2>
            <button type="button" 
                    data-drawer-hide="drawer-disable-body-scrolling" 
                    aria-controls="drawer-disable-body-scrolling"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close menu</span>
            </button>
        </div>

        <!-- Course Modules Content -->
        <div class="mt-4">
            @if($course->modules)
                @foreach($course->modules as $module)
                    <div class="mt-4">
                        <div class="flex items-center">
                            <h3 class="text-md font-semibold">{{ $module->title }}</h3>
                            <!-- Tooltip Button with Info Icon - position this immediately after the title -->
                            <button data-tooltip-target="tooltip-right-{{ $module->id }}" 
                                    data-tooltip-placement="right"
                                    type="button" 
                                    class="ml-2 p-1 cursor-pointer text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle text-gray-400 hover:text-gray-500" viewBox="0 0 16 16"> 
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Tooltip Content with Text Wrapping -->
                        <div id="tooltip-right-{{ $module->id }}" role="tooltip" 
                            class="absolute z-10 invisible px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700 w-48 whitespace-normal break-words">
                            <p class="leading-tight">
                                {{ $module->description ?? 'Bagian ini tidak memiliki deskripsi apapun' }}
                            </p>
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                        
                        <ul id="drawer-content-list-{{ $module->id }}" class="pl-4 mt-2">
                            @if($module->contents)
                                @php
                                    $hasReadContent = false;
                                @endphp
                        
                                @foreach($module->contents as $content)
                                    @php
                                        $isRead = UserContentProgress::isContentRead(Auth::id(), $content->id);
                                        if ($isRead) {
                                            $hasReadContent = true;
                                        }
                                    @endphp
                        
                                    @if($isRead)
                                        <li>
                                            <a href="#content-{{ $content->id }}" class="text-blue-500 hover:underline">
                                                {{ $content->title }} <!-- Use the content title -->
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                        
                                @if(!$hasReadContent)
                                    <li class="text-gray-500">No content read yet.</li>
                                @endif
                            @endif
                        </ul>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>