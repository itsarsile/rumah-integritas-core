@php
    // Expected: $user (App\Models\User or array-like with name, avatar_url, initials)
    $name = data_get($user, 'name', '-');
    $avatarUrl = data_get($user, 'avatar_url');
    $initials = data_get($user, 'initials', strtoupper(substr($name, 0, 1)));
@endphp

<div class="flex items-center gap-2">
    <div class="avatar">
        @if($avatarUrl)
            <div class="w-8 h-8 rounded-full ring ring-primary ring-offset-base-100 ring-offset-1 overflow-hidden">
                <img src="{{ $avatarUrl }}" alt="Avatar of {{ $name }}" />
            </div>
        @else
            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-semibold">
                {{ $initials }}
            </div>
        @endif
    </div>
    <span class="truncate max-w-[12rem]">{{ $name }}</span>
    
</div>

