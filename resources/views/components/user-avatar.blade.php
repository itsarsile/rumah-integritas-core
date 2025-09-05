<div class="flex items-center space-x-3">
    <!-- Avatar -->
    <div class="flex-shrink-0">
        @if($user->avatar)
            <img class="h-8 w-8 rounded-full" src="{{ $user->avatar }}" alt="{{ $user->name }} avatar">
        @else
            <div
                class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-sm font-medium">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
    </div>
    <!-- Name -->
    <div class="text-sm font-medium text-gray-900">
        {{ $user->name }}
    </div>
</div>