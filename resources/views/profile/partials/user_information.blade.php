<section>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('Username') }}</h3>
                <p class="mt-1 text-sm text-gray-900">{{ $user->username }}</p>
            </div>
    
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('Role') }}</h3>
                <p class="mt-1 text-sm text-gray-900">{{ Str::ucfirst($user->role) }}</p>
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
</section>

