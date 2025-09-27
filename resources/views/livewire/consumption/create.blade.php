<div class="card bg-white w-full border border-base-200 rounded-2xl">
    <div class="p-6 border-b border-base-200">
        <div class="card-title">
            Manajemen Konsumsi
        </div>
        <span class="text-sm font-light">Form permintaan konsumsi untuk kegiatan rapat</span>
    </div>
    <div class="card-body space-y-4">
        <!-- Success message -->
        @if (session('message'))
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

         @if (session('error'))
            <div class="toast toast-end">
                <div class="alert alert-error flex items-center gap-2">
                    <i class="fas fa-times"></i><span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="toast toast-end">
                <div class="alert alert-error flex items-center gap-2">
                    <i class="fas fa-times"></i><span>Terdapat kesalahan pada input. Periksa kembali form di bawah.</span>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form wire:submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kegiatan -->
                <div class="form-control space-y-2">
                    <label class="label">
                        <span class="label-text font-medium">Nama Kegiatan</span>
                    </label>
                    <input type="text" wire:model="request_title" class="input input-bordered w-full border border-base-200 bg-base-100/40" placeholder="Masukkan nama kegiatan" />
                    @error('request_title')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tanggal Kegiatan -->
                <div class="form-control space-y-2">
                    <label class="label">
                        <span class="label-text font-medium">Tanggal Kegiatan</span>
                    </label>
                    <input type="date" wire:model="event_request_date" class="input input-bordered w-full border border-base-200 bg-base-100/40" />
                    @error('event_request_date')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Jumlah Peserta -->
                <div class="form-control space-y-2">
                    <label class="label">
                        <span class="label-text font-medium">Jumlah Peserta</span>
                    </label>
                    <input type="number" wire:model="audience_count" class="input input-bordered w-full border border-base-200 bg-base-100/40" min="1" placeholder="0" />
                    @error('audience_count')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-control space-y-2">
                    <label class="label">
                        <span class="label-text font-medium">Email</span>
                    </label>
                    <input type="email" wire:model="email" class="input input-bordered w-full border border-base-200 bg-base-100/40" placeholder="contoh@email.com" />
                    @error('email')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Jenis Konsumsi -->
                <div class="form-control space-y-2">
                    <label class="label">
                        <span class="label-text font-medium">Jenis Konsumsi</span>
                    </label>
                    <select wire:model="consumption_type_id" class="select select-bordered border border-base-200 bg-base-100/40 w-full">
                        <option value="0">Pilih Jenis Konsumsi</option>
                        @foreach ($consumptionTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('consumption_type_id')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Divisi -->
                <div class="form-control space-y-2">
                    <label class="label">
                        <span class="label-text font-medium">Divisi</span>
                    </label>
                    <select wire:model="division_id" class="select select-bordered w-full border border-base-200 bg-base-100/40">
                        <option value="0">Pilih Sub/Bagian/Tim</option>
                        @foreach ($divisions as $divison)
                            <option value="{{ $divison->id }}">{{ $divison->name }}</option>
                        @endforeach
                    </select>
                    @error('division_id')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Keterangan -->
            <div class="form-control space-y-2">
                <label class="label">
                    <span class="label-text font-medium">Keterangan</span>
                </label>
                <textarea wire:model="description" class="textarea textarea-bordered w-full border border-base-200 bg-base-100/40" rows="3" placeholder="Tambahkan keterangan tambahan..."></textarea>
                @error('description')
                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit -->
            <div class="form-control flex justify-end">
                <button type="submit" class="btn btn-primary w-full max-w-36">Submit</button>
            </div>
        </form>
    </div>
</div>
