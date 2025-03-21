<h3 class="text-lg font-medium text-gray-900 mb-4">Popular Courses</h3>
<div class="relative">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach($topCourses as $course)
            <div class="swiper-slide">
                <div class="relative w-full h-[250px] rounded-lg overflow-hidden text-white flex items-center" style="background-image: url('{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}'); background-size: cover; background-position: center;">
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-50"></div>

                    <div class="relative flex gap-6 z-10 w-full p-6">
                        <!-- Course Image -->
                        <div class="w-1/3 flex-shrink-0">
                            <img src="{{ $course->image ? asset('storage/'.$course->image) : asset('storage/courses/default-course.png') }}" 
                                    alt="Course Image" 
                                    class="rounded-lg shadow-lg w-full h-auto object-cover">
                        </div>

                        <!-- Course Details -->
                        <div class="w-2/3 flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-bold">{{ $course->name }}</h3>

                                <!-- Tags -->
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach(explode(',', $course->theme) as $theme)
                                        <span class="px-2 py-1 text-sm bg-blue-500 text-white rounded-lg">{{ $theme }}</span>
                                    @endforeach
                                </div>

                                <!-- Description with Truncation -->
                                <p class="mt-4 text-sm line-clamp-5">
                                    {{ Str::limit($course->description ?? 'No description available', 400, '...') }}
                                </p>
                            </div>

                            <!-- Author & Date -->
                            <div class="text-xs mt-4">
                                <p>Author: <span>{{ $course->authorUser->name }}</span></p>
                                <p>Updated: <span>{{ \Carbon\Carbon::parse($course->updated_at)->translatedFormat('d F Y') }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>


    <!-- Navigation (Bottom Right) -->
    <div class="absolute right-4 bottom-4 flex items-center gap-4 z-10">
        <button id="prevSlide" class="text-white hover:text-gray-300">&lt;</button>
        <span id="slide-number" class="text-sm text-white">No. 1</span>
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