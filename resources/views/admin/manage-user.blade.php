<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="relative overflow-x-auto">
                        <div class="flex items-center">
                            <h1 class="font-semibold text-lg text-gray-800 dark:text-white mb-6 mr-2">{{ __('Table Users :') }}</h1>
                            @if(session('success'))
                                <x-input-success :messages="[session('success')]" />
                            @endif
                            @if(session('error'))
                                <x-input-error :messages="[session('error')]" />
                            @endif
                        </div>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center">Name</th>
                                    <th scope="col" class="px-6 py-3 text-center">Email</th>
                                    <th scope="col" class="px-6 py-3 text-center" id="sort-role">
                                        <div class="flex items-center justify-center">
                                            Role
                                            <a href="#" class="ml-1" id="role-sort-icon">
                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="relative">
                                                <button id="dropdownDefaultButton-{{ $user->id }}" data-dropdown-toggle="dropdown-{{ $user->id }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                                                    {{ ucfirst($user->role) }}
                                                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                                    </svg>
                                                </button>

                                                <div id="dropdown-{{ $user->id }}" class="absolute z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton-{{ $user->id }}">
                                                        @if(Auth::user()->role === 'owner')
                                                            <li>
                                                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" 
                                                                   data-role="admin" 
                                                                   data-user-id="{{ $user->id }}"
                                                                   x-on:click.prevent="changeUserRole('{{ $user->id }}', 'admin')">
                                                                   Admin
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" 
                                                               data-role="teach" 
                                                               data-user-id="{{ $user->id }}"
                                                               x-on:click.prevent="changeUserRole('{{ $user->id }}', 'teach')">
                                                               Teacher
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" 
                                                               data-role="user" 
                                                               data-user-id="{{ $user->id }}"
                                                               x-on:click.prevent="changeUserRole('{{ $user->id }}', 'user')">
                                                               User
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form method="POST" action="{{ route('admin.toggleActive', $user->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="{{ $user->active ? 'bg-green-500' : 'bg-red-500' }} text-white px-3 py-1 rounded">
                                                    {{ $user->active ? 'Active' : 'Inactive' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <x-danger-button
                                                x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion-{{ $user->id }}')"
                                            >
                                                {{ __('Delete') }}
                                            </x-danger-button>

                                            <x-modal name="confirm-user-deletion-{{ $user->id }}" focusable>
                                                <form method="post" action="{{ route('admin.deleteUser', $user->id) }}" class="p-6">
                                                    @csrf
                                                    @method('DELETE')

                                                    <h2 class="text-lg font-medium text-gray-900">
                                                        {{ __('Are you sure you want to delete this user?') }}
                                                    </h2>

                                                    <p class="mt-1 text-sm text-gray-600">
                                                        {{ __('Once deleted, this user cannot be recovered.') }}
                                                    </p>

                                                    <div class="mt-6 flex justify-end">
                                                        <x-secondary-button x-on:click="$dispatch('close')">
                                                            {{ __('Cancel') }}
                                                        </x-secondary-button>
                                                        <x-danger-button class="ms-3">
                                                            {{ __('Delete User') }}
                                                        </x-danger-button>
                                                    </div>
                                                </form>
                                            </x-modal>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/data-user.js'])
</x-app-layout>