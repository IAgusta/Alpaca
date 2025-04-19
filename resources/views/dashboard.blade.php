<x-app-layout>
    @section('title', 'Dashboard - ' . config('app.name'))
    <!-- Full-Width Top Courses -->
    <div class="w-full">
        @include('user.component.top_courses')
    </div>

    <div class="py-6">
        <!-- Constrained Content -->
        <div class="max-w-8xl mx-auto px-3 lg:px-8">
            <div class="bg-white overflow-hidden">
                {{-- Latest Update Courses --}}
                <div class="my-7 lg:px-8">
                    @include('user.component.latest_course')
                </div>

                <div class="lg:flex my-7 gap-7 lg:px-8">
                    {{-- User Courses --}}
                    <div class="lg:w-3/4 my-7">
                        @if ($user->role != 'user')
                            @include('admin.admin')
                        @else
                            @include('user.component.dashboard_user_course')
                        @endif
                    </div>

                    {{-- Shortcut/Admin Panel --}}
                    <div class="lg:w-1/4 my-7">
                        {{-- Plugins --}}
                        @include('partials.plugins')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</x-app-layout>
