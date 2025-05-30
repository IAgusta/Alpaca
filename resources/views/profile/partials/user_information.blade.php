<section>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('Username') }}</h3>
                <p class="mt-1 text-sm text-gray-900">{{ $user->username }}</p>
            </div>
    
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('Role') }}</h3>
                @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'owner') && Auth::user()->id !== $user->id)
                    <div class="role-container">
                        <select 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
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
                    <p class="mt-1 text-sm text-gray-900" id="roleDisplay">{{ Str::ucfirst($user->role) }}</p>
                @endif
            </div>
    
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('About me') }}</h3>
                <p class="mt-1 text-sm text-gray-900">
                    {{ $user->details->about ?? __("This user hasn't written a bio yet.") }}
                </p>
            </div>
        </div>
    
        <div class="space-y-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('Status') }}</h3>
                <p class="mt-1 text-sm font-semibold flex items-center gap-2 
                    {{ $user->isOnline() ? 'text-green-600' : 'text-red-600' }}">
                    {{ $user->isOnline() ? __('Online') : __('Offline') }}
            
                    @unless($user->isOnline())
                        @if($user->last_seen)
                            <span class="text-xs text-gray-500 font-normal">
                            {{ __('Last seen :') }} {{ \Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}
                            </span>
                        @endif
                    @endunless
                </p>
            </div>
    
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('Account Age') }}</h3>
                <p class="mt-1 text-sm text-gray-900">{{ $accountage }}</p>
            </div>
    
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('Birth Date') }}</h3>
                <p class="mt-1 text-sm text-gray-900">
                    @if($user->details->birth_date)
                        {{ \Carbon\Carbon::parse($user->details->birth_date)->format('F j, Y') }}
                    @else
                        {{ __('Not provided') }}
                    @endif
                </p>
            </div>
    
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('Phone Number') }}</h3>
                <p class="mt-1 text-sm text-gray-900">{{ $user->details->phone ?? __('Not provided') }}</p>
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

