<div class="space-y-4">
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card bg-base-100">
        <div class="card-body">
            <div class="flex flex-wrap items-center gap-3">
                <div>
                    <label class="label"><span class="label-text">Pilih Role</span></label>
                    <select class="select select-bordered" wire:model.live="selectedRoleId">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-primary" wire:click="save">Simpan</button>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach($modules as $module => $actions)
                    <div class="card bg-base-200">
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

