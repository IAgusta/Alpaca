{{-- Wrapper --}}
<div class="relative group">

    {{-- Default: Course Stats --}}
    <div class="grid grid-cols-3 gap-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-b-lg border-t border-gray-200 dark:border-gray-600
                group-hover:opacity-0 transition-opacity duration-300">
        {{-- Saved By --}}
        <div class="flex flex-col items-center">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-300 mb-1">Saved</span>
            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $course->user_progress_count ?? 0 }}</span>
        </div>
        
        {{-- Popularity --}}
        <div class="flex flex-col items-center">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-300 mb-1">Popularity</span>
            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $course->popularity ?? 0 }}</span>
        </div>
        
        {{-- Modules --}}
        <div class="flex flex-col items-center">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-300 mb-1">Bagian</span>
            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $course->modules_count ?? $course->modules->count() }}</span>
        </div>
    </div>

    {{-- Hover: Last Module Update --}}
    <div class="absolute inset-0 grid grid-cols-1 place-content-center bg-gray-50 dark:bg-gray-700 rounded-b-lg border-t border-gray-200 dark:border-gray-600
                opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <div class="flex flex-col items-center">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-300 mb-1">Bagian Terupdate</span>
            <span class="text-sm font-bold text-gray-800 dark:text-white truncate max-w-xs text-center">
                {{ Str::limit($course->modules->sortByDesc('created_at')->first()?->title ?? 'Belum ada bagian', 20) }}
            </span>
        </div>
    </div>
</div>