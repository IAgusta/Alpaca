<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-4 py-3">User Detail</th>
            <th scope="col" class="px-4 py-3">Phone</th>
            <th scope="col" class="px-6 py-3" id="sort-role">
                <div class="flex">
                    Role
                    <a class="ml-1" id="role-sort-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z"/>
                        </svg>
                    </a>
                </div>
            </th>
            <th scope="col" class="px-4 py-3">Status</th>
            <th scope="col" class="px-4 py-3">Last Online</th>
            <th scope="col" class="px-4 py-3">
                <span class="sr-only">Actions</span>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr class="border-b dark:border-gray-700">
                <th scope="row" class="flex px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <img class="w-10 h-10 rounded-full" src="{{ $user->image ? asset('storage/' .$user->image) : asset('storage/profiles/default-profile.png') }}" alt="Profile Image">
                    <div class="ps-3">
                        <div class="text-base font-semibold">{{ $user->name }}</div>
                        <div class="font-normal text-gray-500">{{ $user->email }}</div>
                    </div>  
                </th>
                <td class="px-4 py-3">
                    {{ $user->phone ?? ('No Data') }}
                </td>
                <td class="px-4 py-3">
                    {{ ucfirst($user->role) }}
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3"> 
                        @if ($user->active)
                            <div class="flex items-center">
                                <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> Online
                            </div>
                        @else
                            <div class="flex items-center">
                                <div class="h-2.5 w-2.5 rounded-full bg-gray-700 me-2"></div> Offline
                            </div>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div>
                        {{ \Carbon\Carbon::parse($user->last_seen)->format('Y-m-d') }}<br>
                        {{ \Carbon\Carbon::parse($user->last_seen)->format('H:i:s T') }}
                    </div>
                </td>
                <td class="px-4 py-3 flex items-center justify-end">
                    <button id="editUserButton{{ $user->id }}" data-dropdown-toggle="editUserDropdown{{ $user->id }}" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </button>
                    <div id="editUserDropdown{{ $user->id }}" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="editUserButton{{ $user->id }}">
                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="edit-user-dropdown-button">
                            <li>
                                <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Show</a>
                            </li>
                            <li>
                                <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                            </li>
                        </ul>
                        <div class="py-1">
                            <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>