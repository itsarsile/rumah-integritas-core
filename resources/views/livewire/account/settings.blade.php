<div class="space-y-6">
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile info -->
        <div class="card bg-base-100 lg:col-span-2">
            <div class="card-body">
                <h2 class="card-title">Profil</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div>
                        <label class="label"><span class="label-text">Nama</span></label>
                        <input type="text" wire:model.defer="name" class="input input-bordered w-full" />
                        @error('name')<span class="text-error text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="label"><span class="label-text">Email</span></label>
                        <input type="email" class="input input-bordered w-full" value="{{ $email }}" disabled />
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary" wire:click="updateProfile">Simpan Perubahan</button>
                </div>
            </div>
        </div>

        <!-- Avatar -->
        <div class="card bg-base-100">
            <div class="card-body">
                <h3 class="card-title">Avatar</h3>
                <div class="flex items-center gap-4">
                    <div class="avatar">
                        <div class="w-16 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                            <img src="{{ auth()->user()->avatar_url ?? 'https://img.daisyui.com/images/profile/demo/spiderperson@192.webp' }}" alt="avatar">
                        </div>
                    </div>
                    <div class="flex-1">
                        <input type="file" class="file-input file-input-bordered w-full" wire:model="avatar" accept="image/*">
                        @error('avatar')<span class="text-error text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="flex gap-2 mt-3">
                    <button class="btn btn-primary btn-sm" wire:click="updateAvatar" wire:loading.attr="disabled">Upload</button>
                    <button class="btn btn-ghost btn-sm" wire:click="removeAvatar">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-base-100">
        <div class="card-body">
            <h2 class="card-title">Ubah Password</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="label"><span class="label-text">Password Saat Ini</span></label>
                    <input type="password" wire:model.defer="current_password" class="input input-bordered w-full" />
                    @error('current_password')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="label"><span class="label-text">Password Baru</span></label>
                    <input type="password" wire:model.defer="new_password" class="input input-bordered w-full" />
                    @error('new_password')<span class="text-error text-sm">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="label"><span class="label-text">Konfirmasi Password</span></label>
                    <input type="password" wire:model.defer="new_password_confirmation" class="input input-bordered w-full" />
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary" wire:click="updatePassword">Update Password</button>
            </div>
        </div>
    </div>
</div>

