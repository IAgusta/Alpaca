<section>
    <div class="max-w-3xl mx-auto rounded-2xl p-2 lg:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Role -->
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 52 52" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <g>
                                <path d="M38.3,27.2A11.4,11.4,0,1,0,49.7,38.6,11.46,11.46,0,0,0,38.3,27.2Zm2,12.4a2.39,2.39,0,0,1-.9-.2l-4.3,4.3a1.39,1.39,0,0,1-.9.4,1,1,0,0,1-.9-.4,1.39,1.39,0,0,1,0-1.9l4.3-4.3a2.92,2.92,0,0,1-.2-.9,3.47,3.47,0,0,1,3.4-3.8,2.39,2.39,0,0,1,.9.2c.2,0,.2.2.1.3l-2,1.9a.28.28,0,0,0,0,.5L41.1,37a.38.38,0,0,0,.6,0l1.9-1.9c.1-.1.4-.1.4.1a3.71,3.71,0,0,1,.2.9A3.57,3.57,0,0,1,40.3,39.6Z"></path>
                                <circle cx="21.7" cy="14.9" r="12.9"></circle>
                                <path d="M25.2,49.8c2.2,0,1-1.5,1-1.5h0a15.44,15.44,0,0,1-3.4-9.7,15,15,0,0,1,1.4-6.4.77.77,0,0,1,.2-.3c.7-1.4-.7-1.5-.7-1.5h0a12.1,12.1,0,0,0-1.9-.1A19.69,19.69,0,0,0,2.4,47.1c0,1,.3,2.8,3.4,2.8H24.9C25.1,49.8,25.1,49.8,25.2,49.8Z"></path>
                            </g>
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Role') }}</h3>
                    </div>

                    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'owner') && Auth::user()->id !== $user->id)
                        <div class="role-container mt-1">
                            <select 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-gray-900 dark:text-white"
                                data-user-id="{{ $user->id }}"
                                onchange="updateUserRole(this)">
                                @if(Auth::user()->role === 'owner')
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                @endif
                                <option value="trainer" {{ $user->role === 'trainer' ? 'selected' : '' }}>Trainer</option>
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                            <div id="roleUpdateMessage" class="mt-1 text-sm text-green-600 hidden">Role updated successfully!</div>
                            <div id="roleUpdateSpinner" class="mt-1 hidden">
                                <div class="animate-spin h-5 w-5 border-2 border-indigo-500 rounded-full border-t-transparent"></div>
                            </div>
                        </div>
                    @else
                        <p class="mt-1 text-base font-medium text-indigo-600 dark:text-indigo-300" id="roleDisplay">{{ Str::ucfirst($user->role) }}</p>
                    @endif
                </div>
                <!-- About Me -->
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 12c2.28 0 4.19-1.92 4.19-4.28C16.19 5.01 14.28 3 12 3S7.81 5.01 7.81 7.72C7.81 10.08 9.72 12 12 12zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('About me') }}</h3>
                    </div>
                    <p class="text-gray-700 dark:text-gray-400 text-base leading-relaxed">
                        {{ $user->details->about ?? __("This user hasn't written a bio yet.") }}
                    </p>
                </div>
            </div>
            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Status -->
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 {{ $user->isOnline() ? 'text-green-500' : 'text-red-400' }}" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="10"/>
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Status') }}</h3>
                    </div>
                    <p class="mt-1 text-base font-semibold flex items-center gap-2 
                        {{ $user->isOnline() ? 'text-green-600' : 'text-red-600' }}">
                        <span class="inline-block w-2 h-2 rounded-full {{ $user->isOnline() ? 'bg-green-500' : 'bg-red-500' }}"></span>
                        {{ $user->isOnline() ? __('Online') : __('Offline') }}
                        @unless($user->isOnline())
                            @if($user->last_seen)
                                <span class="text-xs text-gray-500 font-normal ml-2">
                                    {{ __('Last seen :') }} {{ \Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}
                                </span>
                            @endif
                        @endunless
                    </p>
                </div>
                <!-- Account Age -->
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Account Age') }}</h3>
                    </div>
                    <p class="text-gray-900 dark:text-white text-base">{{ $accountage }}</p>
                </div>
                <!-- Birth Date -->
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3M16 7V3M3 11h18M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"></path></svg>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Birth Date') }}</h3>
                    </div>
                    <p class="text-gray-900 dark:text-white text-base">
                        @if($user->details->birth_date)
                            {{ \Carbon\Carbon::parse($user->details->birth_date)->format('F j, Y') }}
                        @else
                            {{ __('Not provided') }}
                        @endif
                    </p>
                </div>
                <!-- Phone Number -->
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92V19a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07A19.5 19.5 0 0 1 3.07 8.63 19.86 19.86 0 0 1 0 3.18 2 2 0 0 1 2 1.82h2.09A2 2 0 0 1 6 3.22c.18.56.4 1.12.65 1.67a2 2 0 0 1-.45 2.11l-1.27 1.27a16 16 0 0 0 7.17 7.17l1.27-1.27a2 2 0 0 1 2.11-.45c.55.25 1.11.47 1.67.65a2 2 0 0 1 1.4 1.92z"/></svg>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Phone Number') }}</h3>
                    </div>
                    <p class="text-gray-900 dark:text-white text-base">{{ $user->details->phone ?? __('Not provided') }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'owner'))
        <script>
            function updateUserRole(selectElement) {
                const userId = selectElement.dataset.userId;
                const newRole = selectElement.value;
                const messageDiv = document.getElementById('roleUpdateMessage');
                const spinnerDiv = document.getElementById('roleUpdateSpinner');
                const roleContainer = selectElement.closest('.role-container');

                // Show spinner
                spinnerDiv.classList.remove('hidden');
                selectElement.disabled = true;

                fetch(`/admin/users/${userId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ role: newRole })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update message and show temporarily
                        messageDiv.classList.remove('hidden');
                        setTimeout(() => {
                            messageDiv.classList.add('hidden');
                        }, 3000);

                        // Update any role displays on the page
                        document.querySelectorAll('#roleDisplay').forEach(el => {
                            el.textContent = newRole.charAt(0).toUpperCase() + newRole.slice(1);
                        });
                    } else {
                        throw new Error(data.message || 'Failed to update role');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Reset select to previous value without alert
                    location.reload();
                })
                .finally(() => {
                    // Hide spinner and re-enable select
                    spinnerDiv.classList.add('hidden');
                    selectElement.disabled = false;
                });
            }
        </script>
    @endif
</section>
