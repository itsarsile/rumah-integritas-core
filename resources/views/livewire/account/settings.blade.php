<div class="space-y-6">
    @if (session()->has('success'))
        <div 
                x-data="{ show: true }" 
                x-show="show" 
                x-init="setTimeout(() => show = false, 3000)" 
                class="toast toast-end z-50"
            >
                <div class="alert alert-success flex items-center gap-2 transition duration-500">
                    <i class="fas fa-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
    @endif

    <!-- Avatar -->
    <div class="card bg-white w-full border border-base-200 rounded-2xl">
        <div class="p-6 border-b border-base-200">
            <div class="card-title">
                Setting Profile
            </div>
        </div>         
        <div class="card-body">
            <div class="pb-6 border-b border-base-200 space-y-4">
                <h3 class="card-title">Avatar</h3>
                <div class="flex items-center gap-4">
                    <div class="avatar">
                        <div class="w-12 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                            <img src="{{ auth()->user()->avatar_url ?? 'https://imebehavioralhealth.com/wp-content/uploads/2021/10/user-icon-placeholder-1.png' }}" alt="avatar">
                        </div>
                    </div>
                    <div class="flex-1">
                        <input type="file" class="file-input file-input-sm file-input-bordered bg-white border border-base-200 max-w-sm w-full" wire:model="avatar" accept="image/*">
                        @error('avatar')<span class="text-error text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="flex gap-2 mt-3">
                    <button class="btn btn-primary btn-sm" wire:click="updateAvatar" wire:loading.attr="disabled" wire:target="avatar,updateAvatar">Upload</button>
                    <button class="btn btn-ghost btn-sm" wire:click="removeAvatar">Hapus</button>
                </div>
            </div>

            <div class="pb-6 border-b border-base-200 space-y-4">
                <h2 class="card-title mt-2">Profil</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div class="space-y-2">
                        <label class="label"><span class="label-text">Nama</span></label>
                        <input type="text" wire:model.defer="name" class="input input-bordered bg-white border border-base-200 w-full" />
                        @error('name')<span class="text-error text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="space-y-2">
                        <label class="label"><span class="label-text">Email</span></label>
                        <input type="email" class="input input-bordered w-full" value="{{ $email }}" disabled />
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary btn-sm" wire:click="updateProfile">Simpan Perubahan</button>
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="card-title mt-2">Ubah Password</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label class="label"><span class="label-text">Password Saat Ini</span></label>
                        <input placeholder="Password saat ini" type="password" wire:model.defer="current_password" class="input input-bordered bg-white border border-base-200 w-full" />
                        @error('current_password')<span class="text-error text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="space-y-2">
                        <label class="label"><span class="label-text">Password Baru</span></label>
                        <input placeholder="Password baru" type="password" wire:model.defer="new_password" class="input input-bordered w-full bg-white border border-base-200" />
                        @error('new_password')<span class="text-error text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="space-y-2">
                        <label class="label"><span class="label-text">Konfirmasi Password</span></label>
                        <input placeholder="Konfirmasi password" type="password" wire:model.defer="new_password_confirmation" class="input input-bordered bg-white border border-base-200 w-full" />
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary btn-sm" wire:click="updatePassword">Update Password</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile info -->
    <div class="card bg-base-100 lg:col-span-2">

    </div>

    <div class="card bg-base-100">
        
    </div>
</div>

