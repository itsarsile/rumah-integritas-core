<div>
    {{-- User Management Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-base-content">User Management</h1>
            <p class="text-base-content/60 mt-1">Manage and monitor your application users</p>
        </div>
        <button wire:click="openCreateModal" class="btn btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New User
        </button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat bg-base-100 shadow rounded-box">
            <div class="stat-figure text-primary">
                <x-feathericon-users class="w-8 h-8 text-primary"/>
            </div>
            <div class="stat-title">Total Users</div>
            <div class="stat-value text-primary">{{ $totalUsers }}</div>
        </div>

        <div class="stat bg-base-100 shadow rounded-box">
            <div class="stat-figure text-success">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-title">Active Users</div>
            <div class="stat-value text-success">{{ $activeUsers }}</div>
        </div>

        <div class="stat bg-base-100 shadow rounded-box">
            <div class="stat-figure text-warning">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="stat-title">Pending</div>
            <div class="stat-value text-warning">{{ $pendingUsers }}</div>
            <div class="stat-desc">Awaiting verification</div>
        </div>

        <div class="stat bg-base-100 shadow rounded-box">
            <div class="stat-figure text-error">
                <x-feathericon-x-circle class="w-8 h-8 text-error"/>
            </div>
            <div class="stat-title">Inactive</div>
            <div class="stat-value text-error">{{ $inactiveUsers }}</div>
        </div>
    </div>

    {{-- Filters and Search --}}
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <div class="flex flex-col lg:flex-row gap-4">
                {{-- Search Input --}}
                <div class="form-control flex-1">
                    <div class="input-group">
                        <input 
                            type="text" 
                            placeholder="Search users..." 
                            class="input input-bordered flex-1" 
                            wire:model.live="search"
                        >
                        <button class="btn btn-square">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="form-control">
                    <select class="select select-bordered" wire:model.live="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="pending">Pending</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>

                {{-- Role Filter --}}
                <div class="form-control">
                    <select class="select select-bordered" wire:model.live="roleFilter">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Per Page --}}
                <div class="form-control">
                    <select class="select select-bordered" wire:model.live="perPage">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('name')" class="cursor-pointer hover:bg-base-200">
                                <div class="flex items-center gap-2">
                                    Name
                                    @if($sortField === 'name')
                                        @if($sortDirection === 'asc')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('email')" class="cursor-pointer hover:bg-base-200">
                                <div class="flex items-center gap-2">
                                    Email
                                    @if($sortField === 'email')
                                        @if($sortDirection === 'asc')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </th>
                            <th>Role</th>
                            <th wire:click="sortBy('status')" class="cursor-pointer hover:bg-base-200">
                                <div class="flex items-center gap-2">
                                    Status
                                    @if($sortField === 'status')
                                        @if($sortDirection === 'asc')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('created_at')" class="cursor-pointer hover:bg-base-200">
                                <div class="flex items-center gap-2">
                                    Joined
                                    @if($sortField === 'created_at')
                                        @if($sortDirection === 'asc')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </th>
                            <th>Last Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr wire:key="{{ $user->id }}">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar avatar-placeholder">
                                            <div class="bg-neutral text-neutral-content w-16 rounded-full">
                                                @if($user->avatar)
                                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" />
                                                @else
                                                    <div class="bg-neutral text-neutral-content flex items-center justify-center">
                                                        {{ substr($user->name, 0, 2) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ $user->name }}</div>
                                            @if($user->phone)
                                                <div class="text-sm opacity-50">{{ $user->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-mono text-sm">{{ $user->email }}</div>
                                    @if($user->email_verified_at)
                                        <div class="badge badge-success badge-xs">Verified</div>
                                    @else
                                        <div class="badge badge-warning badge-xs">Unverified</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="badge badge-outline">{{ $user->roles->isEmpty() ? 'None' : ucfirst($user->roles->pluck('name')->implode(', ')) }}</div>
                                </td>
                                <td>
                                    @switch($user->status)
                                        @case('active')
                                            <div class="badge badge-success">Active</div>
                                            @break
                                        @case('inactive')
                                            <div class="badge badge-error">Inactive</div>
                                            @break
                                        @case('pending')
                                            <div class="badge badge-warning">Pending</div>
                                            @break
                                        @case('suspended')
                                            <div class="badge badge-error">Suspended</div>
                                            @break
                                        @default
                                            <div class="badge badge-ghost">Unknown</div>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="text-sm">{{ $user->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs opacity-50">{{ $user->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    @if($user->last_login_at)
                                        <div class="text-sm">{{ $user->last_login_at->format('M d, Y') }}</div>
                                        <div class="text-xs opacity-50">{{ $user->last_login_at->diffForHumans() }}</div>
                                    @else
                                        <span class="text-sm opacity-50">Never</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown dropdown-end">
                                        <div tabindex="0" role="button" class="btn btn-ghost btn-xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                            </svg>
                                        </div>
                                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                            <li><a wire:click="viewUser({{ $user->id }})">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View Details
                                            </a></li>
                                            <li><a wire:click="openMenuConfigModal({{ $user->id }})">
                                                <x-feathericon-menu class="w-4 h-4" />
                                                Configure Menu
                                            </a></li>
                                            <li><a wire:click="editUser({{ $user->id }})">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit User
                                            </a></li>
                                            @if($user->status === 'active')
                                                <li><a wire:click="suspendUser({{ $user->id }})" class="text-warning">
                                                    <x-feathericon-x-circle class="w-4 h-4"/>
                                                    Suspend
                                                </a></li>
                                            @else
                                                <li><a wire:click="activateUser({{ $user->id }})" class="text-success">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Activate
                                                </a></li>
                                            @endif
                                            <div class="divider my-1"></div>
                                            <li><a wire:click="deleteUser({{ $user->id }})" class="text-error">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8">
                                    <div class="flex flex-col items-center gap-4">
                                        <svg class="w-16 h-16 text-base-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        <div class="text-center">
                                            <h3 class="text-lg font-semibold text-base-content">No users found</h3>
                                            <p class="text-base-content/60">Try adjusting your search or filter criteria</p>
                                        </div>
                                        @if($search || $statusFilter || $roleFilter)
                                            <button wire:click="clearFilters" class="btn btn-outline btn-sm">
                                                Clear Filters
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="flex justify-center mt-6">
            {{ $users->links() }}
        </div>
    @endif

    {{-- Bulk Actions --}}
    @if(count($selectedUsers) > 0)
        <div class="fixed bottom-4 right-4">
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <div class="flex items-center gap-4">
                        <span class="text-sm">{{ count($selectedUsers) }} user(s) selected</span>
                        <div class="flex gap-2">
                            <button wire:click="bulkActivate" class="btn btn-success btn-sm">
                                Activate
                            </button>
                            <button wire:click="bulkSuspend" class="btn btn-warning btn-sm">
                                Suspend
                            </button>
                            <button wire:click="bulkDelete" class="btn btn-error btn-sm">
                                Delete
                            </button>
                            <button wire:click="clearSelection" class="btn btn-ghost btn-sm">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

        {{-- Create User Modal --}}
    <div class="modal" x-data="{ open: @entangle('showCreateModal') }"
        :class="{ 'modal-open': open }" x-cloak>
        <div class="modal-box max-w-2xl bg-base-100 shadow-2xl border border-base-300 rounded-3xl">
            <!-- Header with gradient background -->
            <div
                class="flex items-center justify-between p-6 bg-gradient-to-r from-warning/10 to-info/10 -m-6 mb-6 rounded-t-3xl border-b border-base-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-warning/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-base-content">Create User</h3>
                        <p class="text-sm text-base-content/70">Create new user</p>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-circle btn-ghost hover:bg-base-200"
                    wire:click="closeCreateModal">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="createUser" class="space-y-6">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content flex items-center gap-2">
                            Name
                        </span>
                    </legend>
                    <input type="text" placeholder="Enter a descriptive role name..."
                        class="input input-bordered input-lg bg-base-100 border-2 focus:border-primary focus:outline-none transition-all duration-200 hover:border-primary/50 w-full"
                        wire:model="name">
                    @error('name')
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                        </label>
                    @enderror
                </fieldset>

                <fieldset class+="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content flex items-center gap-2">
                            Email
                        </span>
                    </legend>
                    <input type="email" placeholder="Enter user email address..."
                        class="input input-bordered input-lg bg-base-100 border-2 focus:border-primary focus:outline-none transition-all duration-200 hover:border-primary/50 w-full"
                        wire:model="email">
                    @error('email')
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                        </label>
                    @enderror
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content flex items-center gap-2">
                            Role
                        </span>
                    </legend>
                    <select
                        class="select select-bordered select-lg bg-base-100 border-2 focus:border-primary focus:outline-none transition-all duration-200 hover:border-primary/50 w-full"
                        wire:model="selectedRoleId">
                        <option value="" disabled>Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedRoleId')
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                        </label>
                    @enderror
                </fieldset>

                <!-- Password Fields -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content flex items-center gap-2">
                            Password
                        </span>
                    </legend>
                    <input type="password" placeholder="Enter a secure password..."
                        class="input input-bordered input-lg bg-base-100 border-2 focus:border-primary focus:outline-none transition-all duration-200 hover:border-primary/50 w-full"
                        wire:model="password">
                    @error('password')
                        <label class="label">
                            <span class="label-text alt text-error flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                        </label>
                    @enderror
                </fieldset>

                <!-- Password confirmation -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content flex items-center gap-2">
                            Confirm Password
                        </span>
                    </legend>
                    <input type="password" placeholder="Confirm the password..."
                        class="input input-bordered input-lg bg-base-100 border-2 focus:border-primary focus:outline-none transition-all duration-200 hover:border-primary/50 w-full"
                        wire:model="password_confirmation">
                    @error('passwordConfirmation')
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                        </label>
                    @enderror
                </fieldset>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-base-200">
                    <button type="submit"
                        class="btn btn-primary btn-lg flex-1 gap-2 shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create User
                    </button>
                    <button type="button"
                        class="btn btn-outline btn-lg px-8 hover:bg-base-200 transition-all duration-200"
                        wire:click="closeCreateModal">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Enhanced backdrop -->
        <div class="modal-backdrop bg-black/50 backdrop-blur-sm"></div>
    </div>

    {{-- Edit User Modal --}}
    <div class="modal" id="editUserModal" x-data="{ open: @entangle('showEditModal') }" :class="{ 'modal-open': open }"
    x-cloak>
        <div class="modal-box max-w-2xl bg-base-100 shadow-2xl border border-base-300 rounded-3xl">
            <!-- Header -->
            <div
                class="flex items-center justify-between p-6 bg-gradient-to-r from-warning/10 to-info/10 -m-6 mb-6 rounded-t-3xl border-b border-base-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-warning/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-base-content">Edit User</h3>
                        <p class="text-sm text-base-content/70">Update user details and settings</p>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-circle btn-ghost hover:bg-base-200"
                    wire:click="closeEditModal">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="updateUser" class="space-y-6">
                <!-- Name -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content">Full Name</span>
                    </legend>
                    <input type="text" placeholder="Enter full name..."
                        class="w-full input input-bordered input-lg bg-base-100 border-2 focus:border-warning hover:border-warning/50"
                        wire:model="name">
                    @error('name')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </fieldset>

                <!-- Email -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content">Email</span>
                    </legend>
                    <input type="email" placeholder="Enter email address..."
                        class="w-full input input-bordered input-lg bg-base-100 border-2 focus:border-info hover:border-info/50"
                        wire:model="email">
                    @error('email')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </fieldset>

                <!-- Role -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content">Role</span>
                    </legend>
                    <select
                        class="w-full select select-bordered select-lg bg-base-100 border-2 focus:border-accent hover:border-accent/50"
                        wire:model="selectedRoleId">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </fieldset>

                <!-- Status -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content">Status</span>
                    </legend>
                    <select
                        class="w-full select select-bordered select-lg bg-base-100 border-2 focus:border-secondary hover:border-secondary/50"
                        wire:model="status">
                        <option value="active">✅ Active</option>
                        <option value="inactive">🚫 Inactive</option>
                        <option value="suspended">⚠️ Suspended</option>
                    </select>
                    @error('status')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </fieldset>

                <!-- Password -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content">New Password (optional)</span>
                    </legend>
                    <input type="password" placeholder="Enter new password..."
                        class="w-full input input-bordered input-lg bg-base-100 border-2 focus:border-primary hover:border-primary/50"
                        wire:model="password">
                    @error('password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </fieldset>

                <!-- Confirm Password -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content">Confirm Password</span>
                    </legend>
                    <input type="password" placeholder="Confirm new password..."
                        class="w-full input input-bordered input-lg bg-base-100 border-2 focus:border-primary hover:border-primary/50"
                        wire:model="password_confirmation">
                </fieldset>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-base-200">
                    <button type="submit"
                        class="btn btn-warning btn-lg flex-1 gap-2 shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Update User
                    </button>
                    <button type="button"
                        class="btn btn-outline btn-lg px-8 hover:bg-base-200 transition-all duration-200"
                        wire:click="closeEditModal">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Backdrop -->
        <div class="modal-backdrop bg-black/50 backdrop-blur-sm"></div>
    </div>

    {{-- Menu Configuration Modal --}}
    <div class="modal" x-data="{ open: @entangle('showMenuConfigModal') }" :class="{ 'modal-open': open }" x-cloak>
        <div class="modal-box max-w-3xl bg-base-100 shadow-2xl border border-base-300 rounded-3xl">
            <div class="flex items-center justify-between p-6 bg-gradient-to-r from-primary/10 to-secondary/10 -m-6 mb-6 rounded-t-3xl border-b border-base-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/15 flex items-center justify-center">
                        <x-feathericon-menu class="w-5 h-5 text-primary" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-base-content">Configure User Menu</h3>
                        <p class="text-sm text-base-content/70">Pilih menu yang boleh diakses oleh pengguna ini</p>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-circle btn-ghost hover:bg-base-200" wire:click="closeMenuConfigModal">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <p class="text-sm text-base-content/70">Pengaturan ini akan menambah akses menu di luar yang diberikan oleh peran.</p>
                <div class="max-h-80 overflow-y-auto pr-2">
                    <x-menu-selector :menus="$menuTree" field="userMenuSelections" />
                </div>
            </div>

            <div class="modal-action mt-6">
                <button type="button" class="btn btn-ghost" wire:click="closeMenuConfigModal">Batal</button>
                <button type="button" class="btn btn-primary" wire:click="saveUserMenus">Simpan</button>
            </div>
        </div>

        <label class="modal-backdrop bg-black/50 backdrop-blur-sm" wire:click="closeMenuConfigModal">Close</label>
    </div>


    {{-- Delete Confirmation Modal --}}
    <div class="modal" id="deleteRoleModal" x-data="{ open: @entangle('showDeleteModal') }"
        :class="{ 'modal-open': open }" x-cloak>
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirm Deletion</h3>
            <p class="py-4">Are you sure you want to delete this role? This action cannot be undone.</p>
            <div class="modal-action">
                <button class="btn btn-error" wire:click="deleteRole">Delete</button>
                <button class="btn btn-ghost" wire:click="cancelDelete">Cancel</button>
            </div>
        </div>
    </div>
</div>
