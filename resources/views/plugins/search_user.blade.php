<x-app-layout>
    @section('title', 'Search User - ' . config('app.name'))
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                        class="block w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white shadow-sm focus:ring-blue-500 focus:border-blue-500"
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
                            class="block w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white shadow-sm focus:ring-blue-500 focus:border-blue-500"
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
                                class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white shadow-sm focus:ring-blue-500 focus:border-blue-500"
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
                        class="flex items-center p-4 text-left border rounded-lg hover:bg-gray-50 transition-colors duration-150 ease-in-out"
                    >
                        <img :src="user.avatar" class="w-12 h-12 rounded-full border-1 border-gray-400 dark:border-gray-800 mr-4" alt="Avatar">
                        <div>
                            <div class="font-medium" x-text="user.name"></div>
                            <div class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-xs mt-1 first-letter:uppercase" x-text="user.role"></div>
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
