<div class="card bg-white w-full border border-base-200 rounded-2xl">
    <div class="p-6 border-b border-base-200">
        <div class="card-title">
            Manajemen Role
        </div>
        <span class="text-sm font-light">Daftar semua role</span>
    </div>      

    <div class="p-4 border-b border-base-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border border-base-200 rounded-2xl p-4 space-y-4">
                <div class="stat-title">Total Role</div>
                <div class="flex items-center justify-between text-primary">
                    <p class="text-4xl font-medium">{{ $totalRoles }}</p>               
                    <x-feathericon-shield class="w-8 h-8 text-primary"/>
                </div>
                <div class="stat-desc">Active roles in the system</div>
            </div>
    
            <div class="border border-base-200 rounded-2xl p-4 space-y-4">
                <div class="stat-title">Total Permissions</div>
                <div class="flex items-center justify-between text-success">
                    <p class="text-4xl font-medium">{{ $totalPermissions }}</p>               
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>            
                </div>
                <div class="stat-desc">Available permissions</div>
            </div>
        </div>
    </div>

    {{-- Filters and Search --}}
    <div class="card bg-white">
        <div class="card-body">
            <div class="flex flex-col lg:flex-row gap-4 lg:justify-between">
                {{-- Search Input --}}
                <div class="form-control relative w-72">
                    <input 
                        type="text" 
                        placeholder="Search users..." 
                        class="input input-bordered flex-1 border border-base-200 bg-white pr-6" 
                        wire:model.live="search"
                    >
                    <svg class="w-5 h-5 absolute right-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Guard Filter (if multiple guards are used) --}}
                    <div class="form-control">
                        <select class="select select-bordered border border-base-200 bg-white" wire:model.live="guardFilter">
                            <option value="">All Guards</option>
                            <option value="web">Web</option>
                            <option value="api">API</option>
                        </select>
                    </div>
    
                    {{-- Per Page --}}
                    <div class="form-control">
                        <select class="select select-bordered border border-base-200 bg-white" wire:model.live="perPage">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>

                    <button wire:click="openCreateModal" class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Role
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Roles Table --}}
    <div class="px-4 pb-4">
        <div class="card border border-base-200 rounded-2xl overflow-hidden">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead class="bg-base-100">
                            <tr>
                                <th wire:click="sortBy('name')" class="cursor-pointer hover:bg-base-200">
                                    <div class="flex items-center gap-2">
                                        Name
                                        @if($sortField === 'name')
                                            @if($sortDirection === 'asc')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th>Guard</th>
                                <th>Permissions</th>
                                <th wire:click="sortBy('created_at')" class="cursor-pointer hover:bg-base-200">
                                    <div class="flex items-center gap-2">
                                        Created
                                        @if($sortField === 'created_at')
                                            @if($sortDirection === 'asc')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr wire:key="{{ $role->id }}">
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div>
                                                <div class="px-2 py-1 text-xs border border-base-300 rounded-full w-fit">{{ $role->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $role->guard_name }}
                                    </td>
                                    <td class="align-top">
                                        <div class="flex flex-wrap gap-1 max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg">
                                            @forelse($role->permissions as $permission)
                                                <div class="badge badge-primary badge-sm whitespace-nowrap">{{ $permission->name }}</div>
                                            @empty
                                                <span class="text-sm opacity-50">No permissions</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-sm">{{ $role->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs opacity-50">{{ $role->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td>
                                        <div class="dropdown dropdown-end">
                                            <div tabindex="0" class="btn btn-ghost btn-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <ul tabindex="0"
                                                class="menu dropdown-content z-50 p-2 shadow bg-base-100 rounded-box w-52">
                                                {{-- <li><a wire:click="viewRole({{ $role->id }})">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                        View Details
                                                    </a></li> --}}
                                                <li><a wire:click="openMenuConfigModal({{ $role->id }})">
                                                        <x-feathericon-menu class="w-4 h-4" />
                                                        Configure Menu
                                                    </a></li>
                                                <li><a wire:click="editRole({{ $role->id }})">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                        Edit Role
                                                    </a></li>
                                                <div class="divider my-1"></div>
                                                <li><a wire:click="deleteRole({{ $role->id }})" class="text-error">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                        Delete
                                                    </a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8">
                                        <div class="flex flex-col items-center gap-4">
                                            <svg class="w-16 h-16 text-base-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                                </path>
                                            </svg>
                                            <div class="text-center">
                                                <h3 class="text-lg font-semibold text-base-content">No roles found</h3>
                                                <p class="text-base-content/60">Try adjusting your search or filter criteria</p>
                                            </div>
                                            @if($search || $guardFilter)
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
    </div>

    {{-- Pagination --}}
    @if($roles->hasPages())
        <div class="flex justify-center mt-6">
            {{ $roles->links() }}
        </div>
    @endif

    {{-- Bulk Actions --}}
    @if(count($selectedRoles) > 0)
        <div class="fixed bottom-4 right-4">
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <div class="flex items-center gap-4">
                        <span class="text-sm">{{ count($selectedRoles) }} role(s) selected</span>
                        <div class="flex gap-2">
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

    {{-- Create Role Modal --}}
    <div class="modal" id="createRoleModal" x-data="{ open: @entangle('showCreateModal') }"
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
                        <h3 class="text-xl font-bold text-base-content">Create Role</h3>
                        <p class="text-sm text-base-content/70">Create new role permissions and settings</p>
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

            <form wire:submit.prevent="createRole" class="space-y-2">
                <!-- Role Name Section -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content flex items-center gap-2">
                            Role Name
                        </span>
                    </legend>
                    <input type="text" placeholder="Enter a descriptive role name..."
                        class="input input-bordered bg-white border-1 focus:border-primary focus:outline-none transition-all duration-200 hover:border-primary/50 w-full"
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

                <!-- Guard Name Section -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content flex items-center gap-2">
                            Guard Name
                        </span>
                        <span class="label-text-alt text-base-content/60">Security context</span>
                    </legend>
                    <select
                        class="select select-bordered bg-white border-1 focus:border-secondary focus:outline-none transition-all duration-200 hover:border-secondary/50 w-full"
                        wire:model="guard_name">
                        <option value="web" class="flex items-center">
                            🌐 Web Guard
                        </option>
                        <option value="api" class="flex items-center">
                            🔌 API Guard
                        </option>
                    </select>
                    @error('guard_name')
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

                <!-- Permissions Section -->
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text font-semibold text-base-content flex items-center gap-2">
                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                </path>
                            </svg>
                            Permissions
                        </span>
                        <span class="label-text-alt text-base-content/60">Select role capabilities</span>
                    </label>

                    <!-- Enhanced permissions container -->
                    <div
                        class="bg-base-50 border-2 border-base-200 rounded-2xl p-4 max-h-80 overflow-y-auto custom-scrollbar">
                        <div class="space-y-2">
                            @foreach(\Spatie\Permission\Models\Permission::all() as $permission)
                                <h1>
                                    @if($loop->first || $permission->group !== $previousGroup)
                                        @if(str_contains($permission->name, 'report'))
                                            <div class="text-sm font-semibold text-base-content/70 mt-4 mb-2">
                                                Report Module
                                            </div>
                                        @else
                                            <div class="text-sm font-semibold text-base-content/70 mt-4 mb-2">
                                                {{ ucfirst(str_replace('_', ' ', $permission->group ?? 'General')) }}
                                            </div>
                                        @endif
                                        @php
                                            $previousGroup = $permission->group;
                                        @endphp
                                    @endif
                                </h1>
                                <label
                                    class="flex items-center justify-between p-3 rounded-xl hover:bg-base-100 transition-all duration-200 cursor-pointer group border border-transparent hover:border-base-300">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center group-hover:bg-accent/20 transition-colors duration-200">
                                            @if(str_contains($permission->name, 'create'))
                                                <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            @elseif(str_contains($permission->name, 'update'))
                                                <svg class="w-4 h-4 text-warning" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            @elseif(str_contains($permission->name, 'delete'))
                                                <svg class="w-4 h-4 text-error" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            @elseif(str_contains($permission->name, 'view'))
                                                <svg class="w-4 h-4 text-info" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-medium text-base-content">{{ $permission->name }}</span>
                                            <p class="text-xs text-base-content/60 mt-0.5">
                                                @if(str_contains($permission->name, 'create'))
                                                    Create new resources and content
                                                @elseif(str_contains($permission->name, 'update'))
                                                    Modify existing data and settings
                                                @elseif(str_contains($permission->name, 'delete'))
                                                    Remove data permanently
                                                @elseif(str_contains($permission->name, 'view'))
                                                    Read and access information
                                                @else
                                                    Manage {{ $permission->name }} operations
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <input type="checkbox" class="checkbox checkbox-warning checkbox-lg"
                                        wire:model="permissions" value="{{ $permission->id }}">
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('permissions.*')
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
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-base-200">
                    <button type="submit"
                        class="btn btn-primary flex-1 gap-2 shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Role
                    </button>
                    <button type="button"
                        class="btn btn-outline px-8 hover:bg-base-200 transition-all duration-200"
                        wire:click="closeCreateModal">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Enhanced backdrop -->
        <div class="modal-backdrop bg-black/50 backdrop-blur-sm"></div>
    </div>

    {{-- Edit Role Modal --}}
    <div class="modal" id="editRoleModal" x-data="{ open: @entangle('showEditModal') }" :class="{ 'modal-open': open }"
        x-cloak>
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
                        <h3 class="text-xl font-bold text-base-content">Edit Role</h3>
                        <p class="text-sm text-base-content/70">Modify permissions and settings</p>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-circle btn-ghost hover:bg-base-200"
                    wire:click="closeEditModal">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="updateRole" class="space-y-2">
                <!-- Role Name Section -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content flex items-center gap-2">
                            Role Name
                        </span>
                    </legend>
                    <input type="text" placeholder="Enter role name..."
                        class="w-full input input-bordered bg-white border-1 focus:border-warning focus:outline-none transition-all duration-200 hover:border-warning/50"
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

                <!-- Guard Name Section -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        <span class="label-text font-semibold text-base-content flex items-center gap-2">
                            <svg class="w-4 h-4 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            Guard Name
                        </span>
                        <span class="label-text-alt text-base-content/60">Security context</span>
                    </legend>
                    <select
                        class="w-full select select-bordered bg-white border-1 focus:border-info focus:outline-none transition-all duration-200 hover:border-info/50"
                        wire:model="guard_name">
                        <option value="web" class="flex items-center">
                            🌐 Web Guard
                        </option>
                        <option value="api" class="flex items-center">
                            🔌 API Guard
                        </option>
                    </select>
                    @error('guard_name')
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

                <!-- Permissions Section -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-base-content flex items-center gap-2">
                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                </path>
                            </svg>
                            Permissions
                        </span>
                        <span class="label-text-alt text-base-content/60">Modify role capabilities</span>
                    </label>

                    <!-- Enhanced permissions container -->
                    <div
                        class="bg-base-50 border-2 border-base-200 rounded-2xl p-4 max-h-80 overflow-y-auto custom-scrollbar">
                        <div class="space-y-2">
                            @foreach(\Spatie\Permission\Models\Permission::all() as $permission)
                                <label
                                    class="flex items-center justify-between p-3 rounded-xl hover:bg-base-100 transition-all duration-200 cursor-pointer group border border-transparent hover:border-base-300">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center group-hover:bg-accent/20 transition-colors duration-200">
                                            @if(str_contains($permission->name, 'create'))
                                                <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            @elseif(str_contains($permission->name, 'update'))
                                                <svg class="w-4 h-4 text-warning" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            @elseif(str_contains($permission->name, 'delete'))
                                                <svg class="w-4 h-4 text-error" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            @elseif(str_contains($permission->name, 'view'))
                                                <svg class="w-4 h-4 text-info" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-medium text-base-content">{{ $permission->name }}</span>
                                            <p class="text-xs text-base-content/60 mt-0.5">
                                                @if(str_contains($permission->name, 'create'))
                                                    Create new resources and content
                                                @elseif(str_contains($permission->name, 'update'))
                                                    Modify existing data and settings
                                                @elseif(str_contains($permission->name, 'delete'))
                                                    Remove data permanently
                                                @elseif(str_contains($permission->name, 'view'))
                                                    Read and access information
                                                @else
                                                    Manage {{ $permission->name }} operations
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <input type="checkbox" class="checkbox checkbox-warning checkbox-lg"
                                        wire:model="permissions" value="{{ $permission->id }}">
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('permissions.*')
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
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-base-200">
                    <button type="submit"
                        class="btn btn-warning flex-1 gap-2 shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Update Role
                    </button>
                    <button type="button"
                        class="btn btn-outline px-8 hover:bg-base-200 transition-all duration-200"
                        wire:click="closeEditModal">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Enhanced backdrop -->
        <div class="modal-backdrop bg-black/50 backdrop-blur-sm"></div>
    </div>

    {{-- Menu Configuration Modal --}}
    <div class="modal" x-data="{ open: @entangle('showMenuConfigModal') }" :class="{ 'modal-open': open }" x-cloak>
        <div class="modal-box max-w-3xl bg-base-100 shadow-2xl border border-base-300 rounded-3xl">
            <div class="flex items-center justify-between p-6 bg-gradient-to-r from-primary/10 to-info/10 -m-6 mb-6 rounded-t-3xl border-b border-base-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/15 flex items-center justify-center">
                        <x-feathericon-menu class="w-5 h-5 text-primary" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-base-content">Configure Role Menu</h3>
                        <p class="text-sm text-base-content/70">Kelola menu yang tersedia untuk peran ini</p>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-circle btn-ghost hover:bg-base-200" wire:click="closeMenuConfigModal">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <p class="text-sm text-base-content/70">Menu yang dipilih akan tersedia untuk semua pengguna dengan peran ini.</p>
                <div class="max-h-80 overflow-y-auto pr-2">
                    <x-menu-selector :menus="$menuTree" field="roleMenuSelections" />
                </div>
            </div>

            <div class="modal-action mt-6">
                <button type="button" class="btn btn-ghost" wire:click="closeMenuConfigModal">Batal</button>
                <button type="button" class="btn btn-primary" wire:click="saveRoleMenus">Simpan</button>
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
