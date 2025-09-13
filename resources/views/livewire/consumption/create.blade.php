<div class="card bg-base-100 w-full border border-base-content/20 shadow-lg rounded-xl">
    <div class="card-body space-y-4">
        <!-- Title -->
        <div>
            <h2 class="card-title text-2xl font-semibold">Manajemen Konsumsi</h2>
            <p class="text-base text-base-content/70">Form permintaan konsumsi untuk kegiatan rapat</p>
        </div>

        <div class="divider"></div>

        <!-- Success message -->
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <!-- Form -->
        <form wire:submit="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kegiatan -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Nama Kegiatan</span>
                    </label>
                    <input type="text" wire:model="request_title" class="input input-bordered w-full" placeholder="Masukkan nama kegiatan" />
                </div>

                <!-- Tanggal Kegiatan -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Tanggal Kegiatan</span>
                    </label>
                    <input type="date" wire:model="event_request_date" class="input input-bordered w-full" />
                </div>

                <!-- Jumlah Peserta -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Jumlah Peserta</span>
                    </label>
                    <input type="number" wire:model="audience_count" class="input input-bordered w-full" min="1" placeholder="0" />
                </div>

                <!-- Email -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Email</span>
                    </label>
                    <input type="email" wire:model="email" class="input input-bordered w-full" placeholder="contoh@email.com" />
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
                </div>
            </div>

            <!-- Keterangan -->
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Keterangan</span>
                </label>
                <textarea wire:model="description" class="textarea textarea-bordered w-full" rows="3" placeholder="Tambahkan keterangan tambahan..."></textarea>
            </div>

            <!-- Submit -->
            <div class="form-control">
                <button type="submit" class="btn btn-primary w-full md:w-auto">Submit</button>
            </div>
        </form>
    </div>
</div>
