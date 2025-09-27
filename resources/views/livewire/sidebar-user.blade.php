<div class="flex items-center gap-4 px-6 py-4 justify-between">
    <div class="flex items-center gap-4">
        @if($user?->avatar_url)
            <div class="avatar placeholder">
                <div class="w-10 bg-base-200 rounded-full overflow-hidden">
                    <img src="{{ $user->avatar_url ?? 'https://imebehavioralhealth.com/wp-content/uploads/2021/10/user-icon-placeholder-1.png' }}" alt="avatar" />
                </div>
            </div>
            @else
                <div class="bg-primary/10 text-primary w-10 h-10 rounded-full flex items-center justify-center font-semibold">
                    {{ $user?->initials }}
                </div>
            @endif
        <div class="flex flex-col">
            <span class="font-semibold text-sm">{{ $user?->name }}</span>
            <span class="text-neutral-500 text-xs">{{ $user?->hasRole('admin') ? 'Administrator' : ($user?->hasRole('user') ? 'Pengaju' : '')}}</span>
        </div>
    </div>
    <form action="{{ route('logout') }}" method="POST" >
        @csrf
        <button type="submit" class="flex items-center gap-2 cursor-pointer">
            <i class="fa fa-sign-out text-lg font-light text-error"></i>
        </button>
    </form>
</div>

