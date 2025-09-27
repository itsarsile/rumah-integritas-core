<div class="space-y-4">
    @if (session()->has('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 3000)" 
            class="toast toast-end"
        >
            <div class="alert alert-success flex items-center gap-2 transition duration-500">
                <i class="fas fa-check"></i>
                <span>{{ session('message') }}</span>
            </div>
        </div>    
    @endif

    <div class="card bg-white w-full border border-base-200 rounded-2xl">
        <div class="p-6 border-b border-base-200">
            <div class="card-title">
                Manajemen Akses Kontrol
            </div>
        </div>  
        <div class="card-body">
            <div class="flex flex-wrap items-center gap-3">
                <div class="space-y-2">
                    <label class="label"><span class="label-text font-medium">Pilih Role</span></label>
                    <select class="select select-bordered border border-base-200 bg-white" wire:model.live="selectedRoleId">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-primary shadow-none w-36" wire:click="save">Simpan</button>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach($modules as $module => $actions)
                    <div class="card bg-base-100 border border-base-200">
                        <div class="card-body">
                            <div class="font-semibold mb-2 capitalize">{{ $module }}</div>
                            <div class="space-y-2">
                                @foreach($actions as $action)
                                    @php $key = $action.' '.$module; @endphp
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" class="toggle toggle-primary" wire:model.live="grants.{{ $key }}">
                                        <span class="capitalize">{{ $action }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

