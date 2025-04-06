<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search User') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto p-4" x-data="userSearch()">
            <!-- Search Bar using Flowbite Template -->
            <form class="max-w-5xl mx-auto">   
                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="search" id="default-search" x-model="query" @input="searchUser" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Find User..." required />
                    <button type="button" @click="searchUser" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                </div>
            </form>

            <!-- User Match Results -->
            <div x-show="users.length" class="mt-2 bg-white shadow rounded-lg p-2">
                <template x-for="user in users" :key="user.id">
                    <div class="flex items-center justify-between p-2 border-b last:border-none">
                        <div class="flex items-center space-x-2">
                            <img :src="user.avatar" class="w-10 h-10 rounded-full" alt="User Avatar">
                            <div>
                                <p class="font-semibold" x-text="user.name"></p>
                                <span class="text-sm text-gray-500" x-text="user.role"></span>
                            </div>
                        </div>
                        <div>
                            <button @click="viewProfile(user)" class="text-blue-500">
                                <span class="material-symbols-outlined">
                                search
                                </span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Profile Overview using Flowbite Account Overview -->
            <div x-show="selectedUser" class="mt-4 bg-white shadow rounded-lg p-6">
                <div class="relative w-full h-52 rounded-xl">
                    <!-- Banner Image -->
                    <img id="banner" 
                        src="banner.jpg" 
                        class="w-full h-full object-cover" 
                        alt="Banner Image">
                
                    <!-- Optional dark overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-10"></div>
                
                    <!-- Edit Button - Top Right -->
                    <div class="absolute top-4 right-4">
                        <button class="bg-blue-600 text-white text-sm px-4 py-2 rounded-md shadow hover:bg-blue-700">
                            Edit Profile
                        </button>
                    </div>
                </div>
                
                <!-- Profile + Info -->
                <div class="relative w-full px-4 sm:px-8 -mt-16 flex items-start space-x-4">
                    <!-- Profile Image -->
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-md">
                        <img id="profile-preview" 
                            src="profile.jpg" 
                            class="w-full h-full object-cover" 
                            alt="Profile Image">
                    </div>
                
                    <!-- Name and Role -->
                    <div class="flex flex-col pt-4">
                        <h1 class="text-2xl font-bold leading-tight" x-text="selectedUser.name"></h1>
                        <span 
                            class="inline-block bg-blue-500 text-white px-2 py-1 rounded-full text-xs mt-1 self-start"
                            x-text="selectedUser.role"
                        ></span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 text-sm text-gray-600">
                    <div>
                        <div class="p-4">
                            <p class="text-gray-500">Username</p>
                            <p class="font-semibold" x-text="selectedUser.name"></p>
                        </div>
                        <div class="p-4">
                            <p class="text-gray-500">Role</p>
                            <p class="font-semibold" x-text="selectedUser.role"></p>
                        </div>
                        <div class="p-4">
                            <p class="text-gray-500">About Me :</p>
                            <p class="font-semibold" x-text="selectedUser.about_me"></p>
                        </div>
                    </div>
                    <div>
                        <div class="p-4">
                            <p class="text-gray-500">Account Age</p>
                            <p class="font-semibold" x-text="selectedUser.created_at"></p>
                        </div>
                        <div class="p-4">
                            <p class="text-gray-500">Phone</p>
                            <p class="font-semibold" x-text="selectedUser.phone"></p>
                        </div>
                        <div class="p-4">
                            <p class="text-gray-500">Birth Date</p>
                            <p class="font-semibold" x-text="selectedUser.birth_date"></p>
                        </div>
                        <div class="p-4">
                            <p class="text-gray-500">Social Media</p>
                            <p class="font-semibold" x-text="selectedUser.social_media"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User Course Library And History --}}
            <div x-show="selectedUser" class="mt-4 bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900">
                {{ __('Reading History') }}
                </h2>
                <div>
                    <!-- Add content for reading history here -->
                    <p class="text-gray-500">List of courses read by the user will be displayed here.</p>
                </div>
            </div>
            <div x-show="selectedUser && ['trainer', 'admin', 'owner'].includes(selectedUser.role.toLowerCase())" class="mt-4 bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900">
                {{ __('Own Courses') }}
                </h2>
                <div>
                    <!-- Add content for own courses here -->
                    <p class="text-gray-500">List of courses owned by the user will be displayed here.</p>
                </div>
            </div>                
        </div>
    </div>

    <script>
        function userSearch() {
            return {
                query: '',
                users: [],
                selectedUser: null,
                searchUser() {
                    // Simulate API search
                    this.users = this.query ? [
                        {id: 1, name: 'John Doe', role: 'Admin', avatar: 'https://via.placeholder.com/80', created_at: '2023-01-01', phone: '1234567890', birth_date: '2000-01-01'}
                    ] : [];
                },
                viewProfile(user) {
                    this.selectedUser = user;
                }
            }
        }
    </script>
</x-app-layout>
