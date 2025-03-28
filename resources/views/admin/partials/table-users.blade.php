<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-4 py-3">User Detail</th>
            <th scope="col" class="px-6 py-3" id="sort-role">Role</th>
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
                    <div class="ps-3 gap-0">
                        <div class="text-base font-semibold">
                            <span class="block">{{ $user->name }}</span>
                            <span class="block font-normal text-gray-500">{{ $user->email }}</span>
                        </div>
                </th>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        @if ($user->role == 'user')
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 -2 16 16">
                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                            </svg>
                        @elseif ($user->role == 'trainer') 
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" fill="currentColor" class="bi bi-journal-bookmark-fill" viewBox="0 -1 16 16">
                                <path fill-rule="evenodd" d="M6 1h6v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8z"/>
                                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                            </svg> 
                        @elseif ($user->role == 'admin')
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" class="_LEQ7KnyAAfYD56ApfaU c5fvORiJNDhWS_5erJz4 b7Lf_ucBvHbZEidPjF8t" viewBox="0 -1 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.672 1.911a1 1 0 10-1.932.518l.259.966a1 1 0 001.932-.518l-.26-.966zM2.429 4.74a1 1 0 10-.517 1.932l.966.259a1 1 0 00.517-1.932l-.966-.26zm8.814-.569a1 1 0 00-1.415-1.414l-.707.707a1 1 0 101.415 1.415l.707-.708zm-7.071 7.072l.707-.707A1 1 0 003.465 9.12l-.708.707a1 1 0 001.415 1.415zm3.2-5.171a1 1 0 00-1.3 1.3l4 10a1 1 0 001.823.075l1.38-2.759 3.018 3.02a1 1 0 001.414-1.415l-3.019-3.02 2.76-1.379a1 1 0 00-.076-1.822l-10-4z"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.672 1.911a1 1 0 10-1.932.518l.259.966a1 1 0 001.932-.518l-.26-.966zM2.429 4.74a1 1 0 10-.517 1.932l.966.259a1 1 0 00.517-1.932l-.966-.26zm8.814-.569a1 1 0 00-1.415-1.414l-.707.707a1 1 0 101.415 1.415l.707-.708zm-7.071 7.072l.707-.707A1 1 0 003.465 9.12l-.708.707a1 1 0 001.415 1.415zm3.2-5.171a1 1 0 00-1.3 1.3l4 10a1 1 0 001.823.075l1.38-2.759 3.018 3.02a1 1 0 001.414-1.415l-3.019-3.02 2.76-1.379a1 1 0 00-.076-1.822l-10-4z"></path>
                        </svg>
                        @endif
                        {{ ucfirst($user->role) }}
                    </div>
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
                            <li>
                                <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                            </li>
                        </ul>    
                    </div>
                </td>
            </tr>
        @endforeach
        @for ($i = count($users); $i < 10; $i++)
            <tr class="border-b dark:border-gray-700">
                <td class="px-4 py-3" colspan="5">&nbsp;</td>
            </tr>
        @endfor
    </tbody>
</table>