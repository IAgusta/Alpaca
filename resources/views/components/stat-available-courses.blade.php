{{-- Course Stats --}}
<div class="grid grid-cols-3 gap-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-b-lg border-t border-gray-200 dark:border-gray-600">
    {{-- Saved By --}}
    <div class="flex flex-col items-center">
        <span class="text-xs font-medium text-gray-500 dark:text-gray-300 mb-1">Saved</span>
        <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $course->saved_count ?? 0 }}</span>
    </div>
    
    {{-- Popularity --}}
    <div class="flex flex-col items-center">
        <span class="text-xs font-medium text-gray-500 dark:text-gray-300 mb-1">Popularity</span>
        <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $course->popularity ?? 0 }}</span>
    </div>
    
    {{-- Modules --}}
    <div class="flex flex-col items-center">
        <span class="text-xs font-medium text-gray-500 dark:text-gray-300 mb-1">Modules</span>
        <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $course->modules_count ?? $course->modules->count() }}</span>
    </div>
</div>