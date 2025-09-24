<div class="space-y-6">
    @if (session()->has('success'))
        <div class="alert alert-success shadow">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-base-content">Pengaturan Slider Login</h1>
            <p class="text-base-content/60 mt-1">Kelola konten carousel yang tampil di halaman login.</p>
        </div>
        <button wire:click="openCreateModal" class="btn btn-primary">Tambah Slide</button>
    </div>

    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text">Cari Slide</span>
                    </label>
                    <div class="input-group">
                        <input type="text" class="input input-bordered flex-1" placeholder="Cari judul atau deskripsi..." wire:model.live="search">
                        <button class="btn btn-square" wire:click="$refresh">
                            <x-feathericon-search class="w-5 h-5" />
                        </button>
                    </div>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Tampilkan</span>
                    </label>
                    <select class="select select-bordered" wire:model.live="perPage">
                        <option value="5">5 per halaman</option>
                        <option value="10">10 per halaman</option>
                        <option value="15">15 per halaman</option>
                        <option value="25">25 per halaman</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th class="cursor-pointer" wire:click="sortBy('title')">
                                <div class="flex items-center gap-2">
                                    Judul
                                    @if($sortField === 'title')
                                        @if($sortDirection === 'asc')
                                            <x-feathericon-arrow-up class="w-4 h-4" />
                                        @else
                                            <x-feathericon-arrow-down class="w-4 h-4" />
                                        @endif
                                    @endif
                                </div>
                            </th>
                            <th>Konten</th>
                            <th class="cursor-pointer" wire:click="sortBy('display_order')">
                                <div class="flex items-center gap-2">
                                    Urutan
                                    @if($sortField === 'display_order')
                                        @if($sortDirection === 'asc')
                                            <x-feathericon-arrow-up class="w-4 h-4" />
                                        @else
                                            <x-feathericon-arrow-down class="w-4 h-4" />
                                        @endif
                                    @endif
                                </div>
                            </th>
                            <th>Status</th>
                            <th class="w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($slides as $slide)
                            @php
                                $isExternal = Str::startsWith($slide->image_path, ['http://', 'https://']);
                                $imageUrl = $isExternal ? $slide->image_path : Storage::disk('public')->url($slide->image_path);
                            @endphp
                            <tr>
                                <td>
                                    <div class="mask mask-squircle h-20 w-32 overflow-hidden bg-base-200">
                                        <img src="{{ $imageUrl }}" alt="{{ $slide->title ?? 'Slide' }}" class="object-cover w-full h-full">
                                    </div>
                                </td>
                                <td class="align-top">
                                    <div class="font-semibold text-base-content">{{ $slide->title ?: '—' }}</div>
                                    <div class="text-sm text-base-content/60">{{ $slide->subtitle ?: '—' }}</div>
                                </td>
                                <td class="align-top">
                                    <p class="text-sm text-base-content/80 line-clamp-3 whitespace-pre-line">{{ $slide->description ?: '—' }}</p>
                                    @if($slide->button_text && $slide->button_url)
                                        <div class="mt-2 text-sm">
                                            <span class="badge badge-outline">{{ $slide->button_text }}</span>
                                            <a href="{{ $slide->button_url }}" target="_blank" class="link link-primary ml-2">{{ $slide->button_url }}</a>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold">{{ $slide->display_order }}</span>
                                        <div class="join join-vertical">
                                            <button class="btn btn-xs join-item" wire:click="moveSlideUp({{ $slide->id }})" title="Naikkan">
                                                <x-feathericon-arrow-up class="w-4 h-4" />
                                            </button>
                                            <button class="btn btn-xs join-item" wire:click="moveSlideDown({{ $slide->id }})" title="Turunkan">
                                                <x-feathericon-arrow-down class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-top">
                                    @if($slide->is_active)
                                        <div class="badge badge-success gap-2">
                                            <span class="w-2 h-2 rounded-full bg-success-content"></span>
                                            Aktif
                                        </div>
                                    @else
                                        <div class="badge badge-ghost gap-2">
                                            <span class="w-2 h-2 rounded-full bg-base-content/50"></span>
                                            Nonaktif
                                        </div>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <div class="flex flex-wrap gap-2">
                                        <button class="btn btn-ghost btn-xs" wire:click="toggleStatus({{ $slide->id }})">
                                            {{ $slide->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                        <button class="btn btn-outline btn-xs" wire:click="openEditModal({{ $slide->id }})">
                                            Edit
                                        </button>
                                        <button class="btn btn-error btn-xs" wire:click="confirmDelete({{ $slide->id }})">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10">
                                    <div class="flex flex-col items-center gap-2 text-base-content/60">
                                        <x-feathericon-image class="w-10 h-10" />
                                        <span>Tidak ada slide. Tambahkan slide pertama Anda.</span>
                                        <button class="btn btn-primary btn-sm mt-2" wire:click="openCreateModal">Tambah Slide</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($slides->hasPages())
                <div class="p-4">
                    {{ $slides->links() }}
                </div>
            @endif
        </div>
    </div>

    <div class="modal" x-data="{ open: @entangle('showFormModal') }" :class="{ 'modal-open': open }">
        <div class="modal-box max-w-3xl bg-base-100">
            <h3 class="font-bold text-lg mb-4">{{ $editingSlideId ? 'Edit Slide' : 'Tambah Slide' }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text">Judul</span></label>
                    <input type="text" class="input input-bordered" placeholder="Judul slide" wire:model.live="title">
                </div>
                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text">Subjudul</span></label>
                    <input type="text" class="input input-bordered" placeholder="Subjudul" wire:model.live="subtitle">
                </div>
                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text">Deskripsi</span></label>
                    <textarea class="textarea textarea-bordered" rows="3" placeholder="Deskripsi singkat" wire:model.live="description"></textarea>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Teks Tombol</span></label>
                    <input type="text" class="input input-bordered" placeholder="Contoh: Pelajari lebih lanjut" wire:model.live="button_text">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">URL Tombol</span></label>
                    <input type="url" class="input input-bordered" placeholder="https://contoh.go.id" wire:model.live="button_url">
                    @error('button_url')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Urutan Tampil</span></label>
                    <input type="number" min="0" class="input input-bordered" placeholder="Otomatis" wire:model.live="display_order">
                    @error('display_order')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Status</span></label>
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" class="toggle toggle-primary" wire:model.live="is_active">
                        <span class="label-text">Tampilkan slide ini</span>
                    </label>
                </div>
                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text">Gambar</span></label>
                    <input type="file" class="file-input file-input-bordered w-full" accept="image/png,image/jpeg,image/webp" wire:model="image">
                    <span class="text-xs text-base-content/60 mt-1">Format JPG, PNG, atau WEBP maks. 4 MB.</span>
                    @error('image')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <span class="label-text font-semibold">Preview</span>
                    <div class="mt-2 grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @if ($image)
                            <div>
                                <p class="text-sm mb-2 text-base-content/70">Gambar baru</p>
                                <div class="rounded-xl overflow-hidden border border-base-300">
                                    <img src="{{ $image->temporaryUrl() }}" class="object-cover w-full h-48" alt="Preview sementara">
                                </div>
                            </div>
                        @endif
                        @if ($existingImagePath && !$image)
                            @php
                                $existingIsExternal = Str::startsWith($existingImagePath, ['http://', 'https://']);
                                $existingUrl = $existingIsExternal ? $existingImagePath : Storage::disk('public')->url($existingImagePath);
                            @endphp
                            <div>
                                <p class="text-sm mb-2 text-base-content/70">Gambar saat ini</p>
                                <div class="rounded-xl overflow-hidden border border-base-300">
                                    <img src="{{ $existingUrl }}" class="object-cover w-full h-48" alt="Preview saat ini">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-action">
                <button class="btn btn-ghost" wire:click="$set('showFormModal', false)">Batal</button>
                <button class="btn btn-primary" wire:click="saveSlide">Simpan</button>
            </div>
        </div>
        <div class="modal-backdrop" wire:click="$set('showFormModal', false)"></div>
    </div>

    <div class="modal" x-data="{ open: @entangle('showDeleteModal') }" :class="{ 'modal-open': open }">
        <div class="modal-box">
            <h3 class="font-semibold text-lg">Hapus Slide</h3>
            <p class="py-4">Anda yakin ingin menghapus slide ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="modal-action">
                <button class="btn btn-ghost" wire:click="$set('showDeleteModal', false)">Batal</button>
                <button class="btn btn-error" wire:click="deleteSlide">Hapus</button>
            </div>
        </div>
        <div class="modal-backdrop" wire:click="$set('showDeleteModal', false)"></div>
    </div>
</div>
