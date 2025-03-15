<x-app-layout>
    <x-slot name="header">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('user.course') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight hover:text-blue-600">{{ __('Courses') }}</h2>
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
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 h-screen overflow-hidden"> <!-- Set height to full screen and hide overflow -->
            <div class="mx-auto p-2 bg-white overflow-hidden shadow-sm sm:rounded-lg h-full flex"> <!-- Set height to full and use flexbox -->
                <!-- Sidebar Navigation -->
                <aside class="w-1/5 p-4 border-r overflow-y-auto hidden lg:block" id="sidebar"> <!-- Enable vertical scrolling and hide on mobile -->
                    @if($course->modules)
                        @foreach($course->modules as $module)
                            <div class="mt-4">
                                <h3 class="text-md font-semibold">{{ $module->title }}</h3>
                                <ul class="pl-4 mt-2">
                                    @if($module->contents)
                                        @foreach($module->contents as $content)
                                            <li>
                                                <a href="#content-{{ $content->id }}" class="text-blue-500 hover:underline">{{ Str::limit(strip_tags($content->content), 15) }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endforeach
                    @endif
                </aside>

                <!-- Vertical Bar for Mobile -->
                <div class="lg:hidden p-4 cursor-pointer" id="sidebar-toggle">
                    <div class="w-1 h-full bg-gray-800"></div>
                </div>
                
                <!-- Main Content -->
                <div class="w-full lg:w-4/5 p-4 overflow-y-auto" id="main-content"> <!-- Enable vertical scrolling -->
                    @if($course->modules)
                        @foreach($course->modules as $module)
                            <h1 class="text-2xl font-bold mt-6">{{ $module->title }}</h1>
                            @if($module->contents)
                                @foreach($module->contents as $content)
                                    <div id="content-{{ $content->id }}"> <!-- Remove ql-editor class -->
                                        <div class="ql-editor p-0 border-0 shadow-none"> <!-- Add ql-editor class here -->
                                            {!! $content->content !!} <!-- Quill-formatted content -->
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("aside a").forEach(anchor => {
                anchor.addEventListener("click", function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute("href")).scrollIntoView({
                        behavior: "smooth"
                    });
                });
            });

            // Fix curly quotes in links within Quill editor content
            document.querySelectorAll(".ql-editor a").forEach(link => {
                link.href = link.href.replace(/“|”/g, '"');
                link.target = link.target.replace(/“|”/g, '"');
            });

            // Handle clicks on links within Quill editor content
            document.querySelectorAll(".ql-editor a").forEach(link => {
                link.addEventListener("click", function (e) {
                    e.stopPropagation();
                    e.preventDefault(); // Prevent Laravel from interpreting it as a relative path
                    window.open(this.href, '_blank');
                });
            });

            // Toggle sidebar visibility on mobile
            document.getElementById("sidebar-toggle").addEventListener("click", function () {
                var sidebar = document.getElementById("sidebar");
                var mainContent = document.getElementById("main-content");
                sidebar.classList.toggle("hidden");
                if (!sidebar.classList.contains("hidden")) {
                    mainContent.classList.add("lg:ml-1/5"); // Adjust main content position
                } else {
                    mainContent.classList.remove("lg:ml-1/5");
                }
            });
        });
    </script>
</x-app-layout>