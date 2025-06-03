@if ($user->role === 'owner')
    <span class="inline-block bg-black text-white px-3 py-1 rounded-full text-xs mt-1">
        <svg class="inline w-4 h-4 mr-1 -mt-1" fill="currentColor" viewBox="0 -960 960 960" xmlns="http://www.w3.org/2000/svg">
            <path d="M200-160v-80h560v80H200Zm0-140-51-321q-2 0-4.5.5t-4.5.5q-25 0-42.5-17.5T80-680q0-25 17.5-42.5T140-740q25 0 42.5 17.5T200-680q0 7-1.5 13t-3.5 11l125 56 125-171q-11-8-18-21t-7-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820q0 15-7 28t-18 21l125 171 125-56q-2-5-3.5-11t-1.5-13q0-25 17.5-42.5T820-740q25 0 42.5 17.5T880-680q0 25-17.5 42.5T820-620q-2 0-4.5-.5t-4.5-.5l-51 321H200Zm68-80h424l26-167-105 46-133-183-133 183-105-46 26 167Zm212 0Z"/>
        </svg>
        {{ Str::ucfirst($user->role) }}
    </span>
@elseif ($user->role === 'admin')
    <span class="inline-block bg-red-500 text-white px-3 py-1 rounded-full text-xs mt-1">
        <svg class="inline w-4 h-4 mr-1 -mt-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
        </svg>
        {{ Str::ucfirst($user->role) }}
    </span>
@elseif ($user->role === 'trainer')
    <span class="inline-block bg-green-500 text-white px-3 py-1 rounded-full text-xs mt-1">
        <svg class="inline w-4 h-4 mr-1 -mt-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
        </svg>
        {{ Str::ucfirst($user->role) }}
    </span>
@else
    <span class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-xs mt-1">
        <svg class="inline w-4 h-4 mr-1 -mt-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
        </svg>
        {{ Str::ucfirst($user->role ?? 'User') }}
    </span>
@endif