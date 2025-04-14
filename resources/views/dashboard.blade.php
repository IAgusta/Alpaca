<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="col-span-2">
                        {{-- Top 10 Favorit Courses --}}
                        @include('user.component.top_courses')

                        {{-- User Courses --}}
                        <div class="col-span-2 mt-7">
                            @if ($user->role != 'user')
                                @include('admin.admin')
                            @else
                                @include('user.component.dashboard_user_course')
                            @endif
                        </div>
                    </div>
                    {{-- Latest Courses Update --}}
                    <div>
                        @include('user.component.latest_course')
                    </div>
                </div>

                {{-- Plugins --}}
                @include('partials.plugins')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</x-app-layout>
