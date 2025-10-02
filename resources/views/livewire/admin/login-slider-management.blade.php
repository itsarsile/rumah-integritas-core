<div class="space-y-6">
    @if (session()->has('success'))
        <div class="alert alert-success shadow">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="card bg-white w-full border border-base-200 rounded-2xl">
        <div class="p-6 border-b border-base-200">
            <div class="card-title">
                Pengaturan Slider Login
            </div>
            <span class="text-sm font-light">Kelola konten carousel yang tampil di halaman login.</span>
        </div>          
        <div class="card-body space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control md:col-span-2 space-y-2">
                    <div class="form-control relative w-72">
                        <input 
                            type="text" 
                            placeholder="Cari judul atau deskripsi..." 
                            class="input input-bordered flex-1 border border-base-200 bg-white pr-6" 
                            wire:model.live="search"
                        >
                        <svg class="w-5 h-5 absolute right-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <select class="select select-bordered border border-base-200 bg-white w-56" wire:model.live="perPage">
                        <option value="5">5 per halaman</option>
                        <option value="10">10 per halaman</option>
                        <option value="15">15 per halaman</option>
                        <option value="25">25 per halaman</option>
                    </select>
                    <button wire:click="openCreateModal" class="btn btn-primary">Tambah Slide</button>
                </div>
            </div>

            <div class="card border border-base-200 rounded-2xl overflow-hidden">
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead class="bg-base-100">
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
                                            <a href="{{ $imageUrl }}" target="_blank">
                                                <div class="w-16 aspect-[4/5] overflow-hidden bg-base-200 rounded-xl relative">
                                                    <img src="{{ $imageUrl }}" alt="{{ $slide->title ?? 'Slide' }}" class="absolute inset-0 w-full h-full object-cover">
                                                </div>
                                            </a>
                                        </td>
                                        <td class="align-center">
                                            <div class="font-semibold text-base-content">{{ $slide->title ?: '—' }}</div>
                                            <div class="text-sm text-base-content/60">{{ $slide->subtitle ?: '—' }}</div>
                                        </td>
                                        <td class="align-center">
                                            <p class="text-sm text-base-content/80 line-clamp-3 whitespace-pre-line">{{ $slide->description ?: '—' }}</p>
                                            @if($slide->button_text && $slide->button_url)
                                                <div class="mt-2 text-sm">
                                                    <span class="px-2 py-1 rounded-full bg-primary text-white text-[10px]">{{ $slide->button_text }}</span>
                                                    <a href="{{ $slide->button_url }}" target="_blank" class="link link-primary text-[10px]">{{ $slide->button_url }}</a>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-center">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold">{{ $slide->display_order }}</span>
                                                {{-- <div class="join join-vertical">
                                                    <button class="btn btn-xs join-item" wire:click="moveSlideUp({{ $slide->id }})" title="Naikkan">
                                                        <x-feathericon-arrow-up class="w-4 h-4" />
                                                    </button>
                                                    <button class="btn btn-xs join-item" wire:click="moveSlideDown({{ $slide->id }})" title="Turunkan">
                                                        <x-feathericon-arrow-down class="w-4 h-4" />
                                                    </button>
                                                </div> --}}
                                            </div>
                                        </td>
                                        <td class="align-center">
                                            @if($slide->is_active)
                                                <span class="bg-green-100 border border-green-200 text-green-800 text-xs font-light mr-2 px-2.5 py-0.5 rounded-full">Aktif</span>
                                            @else
                                                <span class="bg-red-100 border border-red-200 text-red-800 text-xs font-light mr-2 px-2.5 py-0.5 rounded-full">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="align-center">
                                            <div class="flex">
                                                <button class="size-7 flex items-center justify-center rounded-lg hover:bg-neutral-200/70 {{ $slide->is_active ? ' text-red-800' : ' text-green-800' }} " wire:click="toggleStatus({{ $slide->id }})">
                                                    {{ $slide->is_active ? "off" : "on" }}
                                                </button>
                                                <button wire:click="openEditModal({{ $slide->id }})" class="size-7 flex items-center justify-center rounded-lg hover:bg-neutral-200/70">
                                                    <i class="fas fa-eye text-neutral-500"></i>
                                                </button>
                                                <button wire:click="confirmDelete({{ $slide->id }})" class="size-7 flex items-center justify-center rounded-lg hover:bg-neutral-200/70">
                                                    <i class="fas fa-trash text-red-500"></i>
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
        </div>
    </div>

    <div class="modal" x-data="{ open: @entangle('showFormModal') }" :class="{ 'modal-open': open }">
        <div class="modal-box max-w-3xl bg-base-100">
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
                        <h3 class="text-xl font-bold text-base-content">{{ $editingSlideId ? 'Edit Slide' : 'Tambah Slide' }}</h3>
                        <p class="text-sm text-base-content/70">{{ $editingSlideId ? 'Edit slide untuk halaman login' : 'Tambahkan slide baru untuk halaman login' }}</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Judul</span></label>
                    <input type="text" class="input input-bordered w-full border border-base-200 bg-white" placeholder="Judul slide" wire:model.live="title">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Subjudul</span></label>
                    <input type="text" class="input input-bordered w-full border border-base-200 bg-white" placeholder="Subjudul" wire:model.live="subtitle">
                </div>
                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text">Deskripsi</span></label>
                    <textarea class="textarea textarea-bordered w-full border border-base-200 bg-white" rows="3" placeholder="Deskripsi singkat" wire:model.live="description"></textarea>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Teks Tombol</span></label>
                    <input type="text" class="input input-bordered w-full border border-base-200 bg-white" placeholder="Contoh: Pelajari lebih lanjut" wire:model.live="button_text">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">URL Tombol</span></label>
                    <input type="url" class="input input-bordered w-full border border-base-200 bg-white" placeholder="https://contoh.go.id" wire:model.live="button_url">
                    @error('button_url')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Urutan Tampil</span></label>
                    <input type="number" min="0" class="input input-bordered w-full border border-base-200 bg-white" placeholder="Otomatis" wire:model.live="display_order">
                    @error('display_order')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control flex flex-col">
                    <label class="label"><span class="label-text">Status</span></label>
                    <label class="label cursor-pointer justify-start gap-3 mt-2">
                        <input type="checkbox" class="toggle toggle-primary" wire:model.live="is_active">
                        <span class="label-text">Tampilkan slide ini</span>
                    </label>
                </div>
                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text">Gambar</span></label>
                    <input type="file" class="file-input file-input-bordered w-full bg-white border border-base-200" accept="image/png,image/jpeg,image/webp" wire:model="image">
                    <span class="text-xs text-base-content/60 mt-1">Format JPG, PNG, atau WEBP maks. 4 MB. Rekomendasi ukuran 1080x1350 piksel (rasio 4:5).</span>
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
                                <div class="rounded-xl overflow-hidden border border-base-300 relative aspect-[4/5] w-full">
                                    <img src="{{ $image->temporaryUrl() }}" class="absolute inset-0 object-cover w-full h-full" alt="Preview sementara">
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
                                <div class="rounded-xl overflow-hidden border border-base-300 relative aspect-[4/5] w-full">
                                    <img src="{{ $existingUrl }}" class="absolute inset-0 object-cover w-full h-full" alt="Preview saat ini">
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
