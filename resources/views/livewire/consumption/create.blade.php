<div class="card bg-base-100 w-full border border-base-content/20 shadow-lg rounded-xl">
    <div class="card-body space-y-4">
        <!-- Title -->
        <div>
            <h2 class="card-title text-2xl font-semibold">Manajemen Konsumsi</h2>
            <p class="text-base text-base-content/70">Form permintaan konsumsi untuk kegiatan rapat</p>
        </div>

        <div class="divider"></div>

        <!-- Flash messages -->
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error">
                Terdapat kesalahan pada input. Periksa kembali form di bawah.
            </div>
        @endif

        <!-- Form -->
        <form wire:submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kegiatan -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Nama Kegiatan</span>
                    </label>
                    <input type="text" wire:model="request_title" class="input input-bordered w-full" placeholder="Masukkan nama kegiatan" />
                    @error('request_title')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tanggal Kegiatan -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Tanggal Kegiatan</span>
                    </label>
                    <input type="date" wire:model="event_request_date" class="input input-bordered w-full" />
                    @error('event_request_date')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Jumlah Peserta -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Jumlah Peserta</span>
                    </label>
                    <input type="number" wire:model="audience_count" class="input input-bordered w-full" min="1" placeholder="0" />
                    @error('audience_count')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Email</span>
                    </label>
                    <input type="email" wire:model="email" class="input input-bordered w-full" placeholder="contoh@email.com" />
                    @error('email')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Jenis Konsumsi -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Jenis Konsumsi</span>
                    </label>
                    <select wire:model="consumption_type_id" class="select select-bordered w-full">
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
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Divisi</span>
                    </label>
                    <select wire:model="division_id" class="select select-bordered w-full">
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
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Keterangan</span>
                </label>
                <textarea wire:model="description" class="textarea textarea-bordered w-full" rows="3" placeholder="Tambahkan keterangan tambahan..."></textarea>
                @error('description')
                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit -->
            <div class="form-control">
                <button type="submit" class="btn btn-primary w-full md:w-auto">Submit</button>
            </div>
        </form>
    </div>
</div>
