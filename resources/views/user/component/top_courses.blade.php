<h3 class="text-lg font-medium text-gray-900 mb-2">Popular Courses</h3>
<div class="relative">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach($topCourses as $course)
            <div class="swiper-slide">
                <a href="{{ route('user.course.detail', ['name' => Str::slug($course->name),'courseId' => $course->id]) }}">
                    <div class="relative w-full h-[250px] rounded-lg overflow-hidden text-white flex items-center"
                        style="background-image: url('{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}'); background-size: cover; background-position: center;">
                    
                        <!-- Dark Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm"></div>
                    
                        <!-- Foreground Content -->
                        <div class="relative z-10 flex gap-6 items-stretch w-full max-w-6xl mx-auto px-6 py-4 h-full">
                            <!-- Course Cover Image -->
                            <div class="w-[175px] h-full rounded-md overflow-hidden shadow-lg shrink-0">
                                <img src="{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}" 
                                     alt="Course Image" 
                                     class="w-full h-full object-cover">
                            </div>
                    
                            <div class="flex-1 flex flex-col justify-between overflow-hidden">
                                <!-- Top (Title + Theme) -->
                                <div>
                                    <!-- Big Course Title -->
                                    <h3 class="text-2xl lg:text-3xl font-extrabold leading-snug truncate">
                                        {{ $course->name }}
                                    </h3>
                            
                                    <!-- Tags -->
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach(explode(',', $course->theme ?? 'Umum') as $theme)
                                            <span class="bg-white bg-opacity-20 text-[10px] px-2 py-[2px] rounded-md">
                                                {{ trim($theme) }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <div class="mt-2 max-h-[100px] overflow-y-auto pr-2 text-sm text-white/90 custom-scrollbar">
                                        <p>
                                            {{ $course->description ?? 'No description available.' }}
                                        </p>
                                    </div>
                                </div>
                            
                                <!-- Bottom (Author) -->
                                <div class="text-[11px] text-white/70 italic">
                                    <p>{{ $course->authorUser->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>


    <!-- Navigation (Bottom Right) -->
    <div class="absolute right-4 bottom-4 flex items-center gap-4 z-10">
        <span id="slide-number" class="text-sm text-white">No. 1</span>
        <button id="prevSlide" class="text-white hover:text-gray-300">&lt;</button>
        <button id="nextSlide" class="text-white hover:text-gray-300">&gt;</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const swiper = new Swiper(".mySwiper", {
            loop: true,
            navigation: {
                nextEl: "#nextSlide",
                prevEl: "#prevSlide",
            },
            on: {
                slideChange: function () {
                    document.getElementById("slide-number").textContent = `No. ${this.realIndex + 1}`;
                }
            }
        });
    });
</script>