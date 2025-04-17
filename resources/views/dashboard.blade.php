<x-app-layout>
    <!-- Full-Width Top Courses -->
    <div class="w-full">
        @include('user.component.top_courses')
    </div>

    <div class="py-6">
        <!-- Constrained Content -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden">
                {{-- Latest Update Courses --}}
                <div class="my-7">
                    @include('user.component.latest_course')
                </div>

                <div class="lg:flex my-7 gap-7">
                    {{-- User Courses --}}
                    <div class="w-3/4">
                        @if ($user->role != 'user')
                            @include('admin.admin')
                        @else
                            @include('user.component.dashboard_user_course')
                        @endif
                    </div>

                    {{-- Shortcut/Admin Panel --}}
                    <div class="w-1/4">
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
