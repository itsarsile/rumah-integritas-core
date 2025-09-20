<div class="flex items-center gap-4 p-4">
    <div class="avatar placeholder">
        @if($user?->avatar_url)
            <div class="w-12 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2 overflow-hidden">
                <img src="{{ $user->avatar_url }}" alt="avatar" />
            </div>
        @else
            <div class="bg-primary/10 text-primary w-12 rounded-full flex items-center justify-center font-semibold">
                {{ $user?->initials }}
            </div>
        @endif
    </div>
    <div class="flex flex-col">
        <span class="font-semibold">{{ $user?->name }}</span>
        <span class="text-gray-600">{{ $user?->hasRole('admin') ? 'Administrator' : ($user?->hasRole('user') ? 'Pengaju' : '')}}</span>
    </div>
</div>

