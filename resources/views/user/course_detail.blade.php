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
                        @include('user.component.course_detail_component')

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
                        <x-primary-button id="toggleAllButton" onclick="toggleAllModules({{ $course->id }})">Mark All As Read</x-primary-button>
                    </div>
                    <div class="mt-2 lg:grid lg:grid-cols-2 gap-4">
                        @foreach($course->modules as $index => $module)
                            @php
                                // Check if the module is read
                                $progress = \App\Models\UserModel::where('user_id', auth()->id())
                                    ->where('module_id', $module->id)
                                    ->first();
                                $isRead = $progress && $progress->read;
                            @endphp
                        
                            <div class="p-4 rounded-lg flex justify-between items-center border-l-4 transition-colors duration-200
                                {{ $isRead ? 'bg-gray-200 border-gray-400 text-gray-600 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400' 
                                        : 'bg-white border-blue-500 text-black dark:bg-gray-900 dark:border-blue-500 dark:text-white' }}">
                                
                                <a href="{{ route('course.module.open', [
                                    'name' => Str::slug($course->name),
                                    'courseId' => $course->id,
                                    'moduleTitle' => Str::slug($module->title),
                                    'moduleId' => $module->id
                                ]) }}" class="flex-grow flex items-start gap-3">
                        
                                    <!-- Module title -->
                                    <div>
                                        <p class="font-bold">Ch. {{ $index + 1 }} {{ $module->title }}</p>
                                        <p class="text-sm transition-colors duration-200
                                            {{ $isRead ? 'text-gray-500 dark:text-gray-400' : 'text-gray-600 dark:text-gray-300' }}">
                                            Created at: {{ $module->created_at->format('M d, Y') }} | Updated at: {{ $module->updated_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </a>
                        
                                <!-- Toggle Button -->
                                <button onclick="toggleProgress({{ $module->id }}, this)">
                                    <span class="material-symbols-outlined">
                                        {{ $isRead ? 'visibility_off' : 'visibility' }}
                                    </span>
                                </button>
                            </div>
                        @endforeach                    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleProgress(moduleId, button) {
            fetch(`/module-progress/${moduleId}/toggle`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const container = button.closest("div");
                    const icon = button.querySelector("span");

                    // Toggle visibility icon
                    if (data.status === "read") {
                        icon.textContent = "visibility_off";
                        container.classList.remove("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
                        container.classList.add("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
                    } else {
                        icon.textContent = "visibility";
                        container.classList.remove("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
                        container.classList.add("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
                    }

                    // Update course progress dynamically
                    updateCourseProgressUI();
                }
            })
            .catch(error => console.error("Error:", error));
        }

        function updateCourseProgressUI() {
            fetch("/user/course-progress")
                .then(response => response.json())
                .then(data => {
                    const progressText = document.getElementById("course-status");
                    if (progressText) {
                        if (data.course_completed) {
                            progressText.textContent = "🎉 Course Completed!";
                            progressText.classList.add("text-green-500");
                        } else {
                            progressText.textContent = `${data.completed_modules} / ${data.total_modules} modules completed`;
                            progressText.classList.remove("text-green-500");
                        }
                    }
                });
        }

        function toggleAllModules(courseId) {
            fetch(`/courses/${courseId}/toggle-all`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/json",
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const button = document.getElementById("toggleAllButton");
                    const moduleContainers = document.querySelectorAll(".module-container");

                    moduleContainers.forEach(container => {
                        const icon = container.querySelector(".visibility-icon"); // Ensure the right icon is targeted
                        const buttonElement = container.querySelector(".toggle-button"); // The button inside the module div

                        if (data.newStatus === "read") {
                            // Change button text
                            button.textContent = "Mark All As Unread";
                            
                            // Change div styling dynamically
                            container.classList.remove("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
                            container.classList.add("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");

                            // Update icon
                            if (icon) icon.textContent = "visibility_off";

                            // Simulate single module button state change
                            if (buttonElement) {
                                buttonElement.setAttribute("onclick", `toggleProgress(${container.dataset.moduleId}, this)`);
                            }
                        } else {
                            button.textContent = "Mark All As Read";
                            
                            // Change div styling back to original
                            container.classList.remove("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
                            container.classList.add("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");

                            // Update icon
                            if (icon) icon.textContent = "visibility";

                            // Restore module button state
                            if (buttonElement) {
                                buttonElement.setAttribute("onclick", `toggleProgress(${container.dataset.moduleId}, this)`);
                            }
                        }
                    });

                    updateCourseProgressUI();
                }
            })
            .catch(error => console.error("Error:", error));
        }
    </script>
</x-app-layout>