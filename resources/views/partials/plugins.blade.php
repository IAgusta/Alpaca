<div class="gap-6">
    <div>
        <h3 class="text-2xl font-bold text-gray-900 text-center">Shortcut</h3>
        <div class="mt-4 flex flex-wrap justify-center gap-7">
            <!-- Robot Control -->
            <a href="{{ route('plugins.robotControl') }}" class="group relative w-28 h-28 rounded-xl shadow-md overflow-hidden bg-gradient-to-br from-blue-100 to-blue-300 hover:from-blue-300 hover:to-blue-500 transition duration-300 transform hover:scale-105">
                <!-- SVG Icon in center -->
                <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 group-hover:opacity-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-800" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="M189-160q-60 0-102.5-43T42-307q0-9 1-18t3-18l84-336q14-54 57-87.5t98-33.5h390q55 0 98 33.5t57 87.5l84 336q2 9 3.5 18.5T919-306q0 61-43.5 103.5T771-160q-42 0-78-22t-54-60l-28-58q-5-10-15-15t-21-5H385q-11 0-21 5t-15 15l-28 58q-18 38-54 60t-78 22Zm3-80q19 0 34.5-10t23.5-27l28-57q15-31 44-48.5t63-17.5h190q34 0 63 18t45 48l28 57q8 17 23.5 27t34.5 10q28 0 48-18.5t21-46.5q0 1-2-19l-84-335q-7-27-28-44t-49-17H285q-28 0-49.5 17T208-659l-84 335q-2 6-2 18 0 28 20.5 47t49.5 19Zm348-280q17 0 28.5-11.5T580-560q0-17-11.5-28.5T540-600q-17 0-28.5 11.5T500-560q0 17 11.5 28.5T540-520Zm80-80q17 0 28.5-11.5T660-640q0-17-11.5-28.5T620-680q-17 0-28.5 11.5T580-640q0 17 11.5 28.5T620-600Zm0 160q17 0 28.5-11.5T660-480q0-17-11.5-28.5T620-520q-17 0-28.5 11.5T580-480q0 17 11.5 28.5T620-440Zm80-80q17 0 28.5-11.5T740-560q0-17-11.5-28.5T700-600q-17 0-28.5 11.5T660-560q0 17 11.5 28.5T700-520Zm-360 60q13 0 21.5-8.5T370-490v-40h40q13 0 21.5-8.5T440-560q0-13-8.5-21.5T410-590h-40v-40q0-13-8.5-21.5T340-660q-13 0-21.5 8.5T310-630v40h-40q-13 0-21.5 8.5T240-560q0 13 8.5 21.5T270-530h40v40q0 13 8.5 21.5T340-460Zm140-20Z"/>
                    </svg>
                </div>
            
                <!-- Hover Text Overlay -->
                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white text-sm font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    Controller
                </div>
            </a>

            <!-- Find Users -->
            <a href="{{ route('plugins.search-users') }}" class="group relative w-28 h-28 rounded-xl shadow-md overflow-hidden bg-gradient-to-br from-blue-100 to-blue-300 hover:from-blue-300 hover:to-blue-500 transition duration-300 transform hover:scale-105">
                <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 group-hover:opacity-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-800" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="M440-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T520-640q0-33-23.5-56.5T440-720q-33 0-56.5 23.5T360-640q0 33 23.5 56.5T440-560ZM884-20 756-148q-21 12-45 20t-51 8q-75 0-127.5-52.5T480-300q0-75 52.5-127.5T660-480q75 0 127.5 52.5T840-300q0 27-8 51t-20 45L940-76l-56 56ZM660-200q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-540 40v-111q0-34 17-63t47-44q51-26 115-44t142-18q-12 18-20.5 38.5T407-359q-60 5-107 20.5T221-306q-10 5-15.5 14.5T200-271v31h207q5 22 13.5 42t20.5 38H120Zm320-480Zm-33 400Z"/>
                    </svg>
                </div>

                <!-- Text (Hidden by default, appears centered on hover) -->
                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white text-sm font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    Find Users
                </div>
            </a>

            @guest
                {{-- Guest Course Feed --}}
                <a href="{{ route('course.feed') }}" class="group relative w-28 h-28 rounded-xl shadow-md overflow-hidden bg-gradient-to-br from-blue-100 to-blue-300 hover:from-blue-300 hover:to-blue-500 transition duration-300 transform hover:scale-105">
                    <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 group-hover:opacity-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-800" viewBox="0 -960 960 960" fill="currentColor">
                            <path d="M320-320h480v-480h-80v280l-100-60-100 60v-280H320v480Zm0 80q-33 0-56.5-23.5T240-320v-480q0-33 23.5-56.5T320-880h480q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H320ZM160-80q-33 0-56.5-23.5T80-160v-560h80v560h560v80H160Zm360-720h200-200Zm-200 0h480-480Z"/>
                        </svg>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white text-sm font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        Course Feed
                    </div>
                </a>
            @else
                @if (Auth::user()->role != 'user')
                    <!-- Course Management -->
                    <a href="{{ route('admin.courses.index') }}" class="group relative w-28 h-28 rounded-xl shadow-md overflow-hidden bg-gradient-to-br from-blue-100 to-blue-300 hover:from-blue-300 hover:to-blue-500 transition duration-300 transform hover:scale-105">
                        <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 group-hover:opacity-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-800" viewBox="0 -960 960 960" fill="currentColor">
                                <path d="M160-400v-80h280v80H160Zm0-160v-80h440v80H160Zm0-160v-80h440v80H160Zm360 560v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-380L643-160H520Zm300-263-37-37 37 37ZM580-220h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19Z"/>
                            </svg>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white text-sm font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Course
                        </div>
                    </a>
                @else
                    {{-- User Course --}}
                    <a href="{{ route('user.course') }}" class="group relative w-28 h-28 rounded-xl shadow-md overflow-hidden bg-gradient-to-br from-blue-100 to-blue-300 hover:from-blue-300 hover:to-blue-500 transition duration-300 transform hover:scale-105">
                        <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 group-hover:opacity-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-800" viewBox="0 -960 960 960" fill="currentColor">
                                <path d="M320-320h480v-480h-80v280l-100-60-100 60v-280H320v480Zm0 80q-33 0-56.5-23.5T240-320v-480q0-33 23.5-56.5T320-880h480q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H320ZM160-80q-33 0-56.5-23.5T80-160v-560h80v560h560v80H160Zm360-720h200-200Zm-200 0h480-480Z"/>
                            </svg>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white text-sm font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Course
                        </div>
                    </a>
                @endif
            @endguest
        </div>
    </div>
</div>   