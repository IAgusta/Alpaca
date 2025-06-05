<!-- Slider Section -->
<div class="relative">
    <!-- Swiper Container -->
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach($topCourses as $course)
                <div class="swiper-slide relative overflow-hidden">
                    <a href="{{ route('user.course.detail', ['name' => Str::slug($course->name),'courseId' => $course->id]) }}">
                        <!-- Background Image -->
                        <div class="absolute inset-0 w-full h-[400px] z-0">
                            <div class="w-full h-full"
                                style="background-image: url('{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}');
                                        background-size: cover;
                                        background-position: center;
                                        filter: blur(3px);">
                            </div>
                        </div>

                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent dark:via-black/30 dark:to-black/70 to-white/100 z-10"></div>

                        <!-- Foreground Content -->
                        <div class="relative z-20 w-full h-full px-3 lg:px-12 py-7 mt-7 max-w-8xl mx-auto flex gap-6 items-start">
                            <!-- Course Image -->
                            <div class="w-[175px] h-[240px] rounded-md overflow-hidden shadow-lg shrink-0">
                                <img src="{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}" 
                                     alt="Course Image" 
                                     class="w-full h-full object-cover">
                            </div>

                            <!-- Course Content -->
                            <div class="flex-1 dark:text-white text-black flex flex-col h-full">
                                <!-- Title -->
                                <h2 class="font-bold text-xl line-clamp-5 sm:line-clamp-2 lg:text-2xl overflow-hidden">
                                    {{ $course->name }}
                                </h2>

                                <!-- Tags -->
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach(explode(',', $course->theme ?? 'Umum') as $theme)
                                        <span class="dark:bg-white/20 bg-slate-200 text-xs px-2 py-1 rounded-md">
                                            {{ trim($theme) }}
                                        </span>
                                    @endforeach
                                </div>

                                <!-- Description -->
                                <div class="mt-2 max-h-[100px] overflow-y-auto pr-2 text-sm text-black dark:text-white/90 custom-scrollbar">
                                    <p>{{ $course->description ?? 'No description available.' }}</p>
                                </div>

                                <!-- Author -->
                                <div class="text-sm text-black dark:text-white/70 italic mt-2">
                                    <p>{{ $course->authorUser->name }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Static Title -->
    <div class="absolute lg:px-8 w-full top-3 z-10">
        <div class="max-w-8xl mx-auto px-3">
            <h1 class="text-xl lg:text-2xl font-bold text-black dark:text-white">Popular New Titles</h1>
        </div>
    </div>


    <!-- Navigation Outside of Swiper -->
    <div class="absolute lg:px-8 w-full bottom-4 z-10">
        <div class="max-w-8xl mx-auto px-6 flex justify-end items-center gap-4">
            <span id="slide-number" class="text-lg text-black dark:text-white">No. 1</span>
            <button id="prevSlide" class="dark:text-white text-black hover:text-gray-300 text-2xl font-bold">&lt;</button>
            <button id="nextSlide" class="dark:text-white text-black hover:text-gray-300 text-2xl font-bold">&gt;</button>
        </div>
    </div>
</div>

<!-- Swiper Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get total number of slides
        const totalSlides = document.querySelectorAll('.swiper-slide').length;
        // Generate random initial slide
        const randomInitialSlide = Math.floor(Math.random() * totalSlides);

        const swiper = new Swiper(".mySwiper", {
            loop: true,
            initialSlide: randomInitialSlide,
            autoplay: {
                delay: 15000, // 15 seconds
                disableOnInteraction: false, // Continue autoplay after user interaction
            },
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
