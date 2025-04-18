<div id="sortDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
    <ul class="py-2 text-sm text-gray-700" aria-labelledby="sortDropdownButton">
        <li>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => $sort === 'name' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
               class="block px-4 py-2 hover:bg-gray-100">
                Name {{ $sort === 'name' ? ($direction === 'asc' ? '↑' : '↓') : '' }}
            </a>
        </li>
        
        @if(Route::currentRouteName() === 'course.feed')
            <li>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'popularity', 'direction' => $sort === 'popularity' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                   class="block px-4 py-2 hover:bg-gray-100">
                    Popularity {{ $sort === 'popularity' ? ($direction === 'asc' ? '↑' : '↓') : '' }}
                </a>
            </li>
            <li>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => $sort === 'created_at' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                   class="block px-4 py-2 hover:bg-gray-100">
                    Created Date {{ $sort === 'created_at' ? ($direction === 'asc' ? '↑' : '↓') : '' }}
                </a>
            </li>
            <li>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'updated_at', 'direction' => $sort === 'updated_at' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                   class="block px-4 py-2 hover:bg-gray-100">
                    Updated Date {{ $sort === 'updated_at' ? ($direction === 'asc' ? '↑' : '↓') : '' }}
                </a>
            </li>
        @endif

        @if(Route::currentRouteName() === 'user.course.library')
            <li>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'completion', 'direction' => $sort === 'completion' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                   class="block px-4 py-2 hover:bg-gray-100">
                    Completion {{ $sort === 'completion' ? ($direction === 'asc' ? '↑' : '↓') : '' }}
                </a>
            </li>
        @endif
    </ul>
</div>
