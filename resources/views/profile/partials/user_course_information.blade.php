<section>
    @if(in_array($user->role, ['admin', 'trainer', 'owner']))
        {{-- Courses for trainer users --}}
        @include('profile.partials.admin.course_created')
    @else
        <!-- Enrolled Courses for regular users -->
        @include('profile.partials.user.course_progress', ['user' => $user])
    @endif
</section>