<x-app-layout>
    @section('title', 'Search User - ' . config('app.name'))
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Search User') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto p-4" x-data="userSearch()" x-init="init()">
            <div class="flex flex-wrap gap-4 max-w-6xl mx-auto mb-8 items-center">
                <!-- Role Filter Dropdown -->
                <div class="relative w-48">
                    <label for="role-filter" class="sr-only">Filter by Role</label>
                    <select 
                        id="role-filter"
                        x-model="roleFilter" 
                        @change="handleFilters"
                        class="block w-full p-3 text-sm text-gray-900 dark:text-white border border-gray-300 rounded-lg bg-white dark:bg-gray-800 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">All Roles</option>
                        <option value="user">User</option>
                        <option value="trainer">Trainer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            
                <!-- Sort + Search Container -->
                <div class="flex flex-1 gap-4 min-w-[300px] items-center">
                    <!-- Sort Dropdown -->
                    <div class="relative w-48">
                        <label for="name-sort" class="sr-only">Sort Names</label>
                        <select 
                            id="name-sort"
                            x-model="nameSort" 
                            @change="handleFilters"
                            class="block w-full p-3 text-sm text-gray-900 dark:text-white border border-gray-300 rounded-lg bg-white dark:bg-gray-800 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="asc">Name (A-Z)</option>
                            <option value="desc">Name (Z-A)</option>
                        </select>
                    </div>
            
                    <!-- Search Input -->
                    <div class="flex-1 min-w-[200px]">
                        <label for="user-search" class="sr-only">Search Users</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input 
                                type="search" 
                                id="user-search" 
                                x-model="query" 
                                @input.debounce.300ms="searchUser" 
                                placeholder="Search users by name..." 
                                class="block w-full p-3 pl-10 text-sm text-gray-900 dark:text-white border border-gray-300 rounded-lg bg-white dark:bg-gray-800 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- All Users / Search Results Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <template x-for="user in displayedUsers.data" :key="user.id">
                    <button 
                        @click="goToProfile(user.username)"
                        class="flex items-center p-4 text-left border rounded-lg dark:bg-gray-800 hover:bg-gray-100 transition-colors duration-150 ease-in-out"
                    >
                        <img :src="user.avatar" class="w-12 h-12 rounded-full border-1 border-gray-400 dark:border-gray-800 mr-4" alt="Avatar">
                        <div>
                            <div class="font-medium dark:text-white" x-text="user.name"></div>
                            <div>
                                <template x-if="user.role === 'owner'">
                                    <span class="inline-block bg-black text-white px-3 py-1 rounded-full text-xs mt-1 first-letter:uppercase">
                                        <svg class="inline w-4 h-4 mr-1 -mt-1" fill="currentColor" viewBox="0 -960 960 960" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M200-160v-80h560v80H200Zm0-140-51-321q-2 0-4.5.5t-4.5.5q-25 0-42.5-17.5T80-680q0-25 17.5-42.5T140-740q25 0 42.5 17.5T200-680q0 7-1.5 13t-3.5 11l125 56 125-171q-11-8-18-21t-7-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820q0 15-7 28t-18 21l125 171 125-56q-2-5-3.5-11t-1.5-13q0-25 17.5-42.5T820-740q25 0 42.5 17.5T880-680q0 25-17.5 42.5T820-620q-2 0-4.5-.5t-4.5-.5l-51 321H200Zm68-80h424l26-167-105 46-133-183-133 183-105-46 26 167Zm212 0Z"/>
                                        </svg>
                                        Owner
                                    </span>
                                </template>
                                <template x-if="user.role === 'admin'">
                                    <span class="inline-block bg-red-500 text-white px-3 py-1 rounded-full text-xs mt-1 first-letter:uppercase">
                                        <svg class="inline w-4 h-4 mr-1 -mt-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                        </svg>
                                        Admin
                                    </span>
                                </template>
                                <template x-if="user.role === 'trainer'">
                                    <span class="inline-block bg-green-500 text-white px-3 py-1 rounded-full text-xs mt-1 first-letter:uppercase">
                                        <svg class="inline w-4 h-4 mr-1 -mt-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                        </svg>
                                        Trainer
                                    </span>
                                </template>
                                <template x-if="user.role === 'user'">
                                    <span class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-xs mt-1 first-letter:uppercase">
                                        <svg class="inline w-4 h-4 mr-1 -mt-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        User
                                    </span>
                                </template>
                            </div>
                        </div>
                    </button>
                </template>
            </div>

            <!-- No Results Message -->
            <div x-show="query && displayedUsers.data && displayedUsers.data.length === 0" class="mt-4 text-center text-gray-500">
                No users found matching your search.
            </div>

            <!-- Pagination -->
            <div x-show="!query && displayedUsers.last_page > 1" class="mt-6 flex justify-center gap-2">
                <template x-for="page in displayedUsers.last_page" :key="page">
                    <button 
                        @click="changePage(page)"
                        :class="{'bg-blue-500 text-white': currentPage === page, 'bg-gray-200 hover:bg-gray-300': currentPage !== page}"
                        class="px-4 py-2 rounded-md transition-colors duration-150"
                        x-text="page"
                    ></button>
                </template>
            </div>
        </div>
    </div>

    <script>
        function userSearch() {
                return {
                    query: '',
                    roleFilter: '',
                    nameSort: 'asc',
                    displayedUsers: { data: [] },
                    currentPage: 1,
                    searchUser() {
                        if (this.query.length < 2) {
                            this.loadAllUsers();
                            return;
                        }
                        
                        fetch(`/api/search-users?query=${encodeURIComponent(this.query)}&role=${this.roleFilter}&sort=${this.nameSort}`)
                            .then(response => response.json())
                            .then(data => {
                                this.displayedUsers = { data: data };
                            })
                            .catch(error => {
                                console.error('Error searching users:', error);
                                this.displayedUsers = { data: [] };
                            });
                    },
                    loadAllUsers(page = 1) {
                        fetch(`/api/all-users?page=${page}&role=${this.roleFilter}&sort=${this.nameSort}`)
                            .then(response => response.json())
                            .then(data => {
                                this.displayedUsers = data;
                                this.currentPage = page;
                            })
                            .catch(error => {
                                console.error('Error loading users:', error);
                                this.displayedUsers = { data: [] };
                            });
                    },
                    handleFilters() {
                        if (this.query.length >= 2) {
                            this.searchUser();
                        } else {
                            this.loadAllUsers(this.currentPage);
                        }
                    },
                changePage(page) {
                    this.loadAllUsers(page);
                },
                goToProfile(username) {
                    window.location.href = `/${username}`;
                },
                init() {
                    this.loadAllUsers();
                }
            }
        }
    </script>
</x-app-layout>
