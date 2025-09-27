@php
    // Expected: $user (App\Models\User or array-like with name, avatar_url, initials)
    $name = data_get($user, 'name', '-');
    $avatarUrl = data_get($user, 'avatar_url');
    $initials = data_get($user, 'initials', strtoupper(substr($name, 0, 1)));
@endphp

<div class="flex items-center gap-3 border border-base-200 rounded-full p-1 w-fit pr-3">
    @if($avatarUrl)
        <div class="avatar">
            <div class="size-6 rounded-full border border-base-200 overflow-hidden">
                <img src="{{ $avatarUrl }}" alt="Avatar of {{ $name }}" />
            </div>
        </div>
        @else
            <div class="size-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-semibold">
                {{ $initials }}
            </div>
        @endif
    <span class="truncate max-w-[12rem] font-light">{{ $name }}</span>
</div>

