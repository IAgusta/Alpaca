<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search User') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto p-4" x-data="userSearch()" x-init="init()">
            <!-- Search Bar -->
            <form class="max-w-5xl mx-auto mb-8">   
                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input 
                        type="search" 
                        id="default-search" 
                        x-model="query" 
                        @input.debounce.300ms="searchUser" 
                        class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Search users by name..." 
                    />
                </div>
            </form>

            <!-- All Users / Search Results Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="user in displayedUsers.data" :key="user.id">
                    <button 
                        @click="goToProfile(user.username)"
                        class="flex items-center p-4 text-left border rounded-lg hover:bg-gray-50 transition-colors duration-150 ease-in-out"
                    >
                        <img :src="user.avatar" class="w-12 h-12 rounded-full mr-4" alt="Avatar">
                        <div>
                            <div class="font-medium" x-text="user.name"></div>
                            <div class="text-sm text-gray-500" x-text="user.role"></div>
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
                displayedUsers: { data: [] },
                currentPage: 1,
                searchUser() {
                    if (this.query.length < 2) {
                        this.loadAllUsers();
                        return;
                    }
                    
                    fetch(`/api/search-users?query=${encodeURIComponent(this.query)}`)
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
                    fetch(`/api/all-users?page=${page}`)
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
