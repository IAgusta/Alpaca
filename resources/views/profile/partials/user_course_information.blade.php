<section>
    @if(in_array($user->role, ['admin', 'trainer', 'owner']))
        <div class="space-y-6">
            <!-- Created Courses -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Created Courses</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Placeholder for created courses -->
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <p class="text-gray-500 text-center">No courses created yet</p>
                    </div>
                </div>
            </div>

            <!-- Teaching History -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Teaching History</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No teaching history available
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <!-- Enrolled Courses for regular users -->
        <div>
            <h3 class="text-lg font-semibold mb-4">Enrolled Courses</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Placeholder for enrolled courses -->
                <div class="border rounded-lg p-4 bg-gray-50">
                    <p class="text-gray-500 text-center">No enrolled courses</p>
                </div>
            </div>
        </div>
    @endif
</section>