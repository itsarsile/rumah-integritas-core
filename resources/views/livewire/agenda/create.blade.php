<div class="card bg-white w-full border border-base-200 rounded-2xl">
    <div class="p-6 border-b border-base-200">
        <div class="card-title">
            Manajemen Agenda
        </div>
        <span class="text-sm font-light">Form penjadwalan kegiatan</span>
    </div>    
    <div class="card-body space-y-4">

        @if (session('success'))
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

        <form wire:submit.prevent="submit" class="space-y-4">
            <!-- Judul -->
            <div class="form-control space-y-2">
                <label class="label"><span class="label-text font-medium">Judul Agenda</span></label>
                <input type="text" wire:model="title" class="input input-bordered w-full border border-base-200 bg-base-100/40" placeholder="Masukkan judul agenda">
                @error('title') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid lg:grid-cols-2 gap-4 grid-cols-1">
                <!-- Tanggal -->
                <div class="form-control space-y-2">
                    <label class="label"><span class="label-text font-medium">Tanggal</span></label>
                    <input type="date" wire:model="date" class="input input-bordered w-full border border-base-200 bg-base-100/40">
                    @error('date') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Lokasi -->
                <div class="form-control space-y-2">
                    <label class="label"><span class="label-text font-medium">Lokasi</span></label>
                    <input type="text" wire:model="location" class="input input-bordered w-full border border-base-200 bg-base-100/40" placeholder="Lokasi agenda">
                    @error('location') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Reviewer -->
            <div class="form-control space-y-2">
                <label class="label"><span class="label-text font-medium">Penanggung Jawab</span></label>
                <select wire:model="person_in_charge_id" class="select select-bordered w-full border border-base-200 bg-base-100/40">
                    <option value="">Pilih Penanggung Jawab</option>
                    @foreach ($personInCharges as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('person_in_charge_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Tombol -->
            <div class="flex gap-2 justify-end pt-2">
                <button type="reset" class="btn btn-outline border border-base-300 text-neutral-500 w-full max-w-36 hover:bg-base-100">Reset Form</button>
                <button type="submit" class="btn btn-primary">Simpan Agenda</button>
            </div>
        </form>
    </div>
</div>
