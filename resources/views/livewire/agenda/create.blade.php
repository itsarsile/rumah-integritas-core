<div class="card bg-base-100 w-full border border-base-content/20 rounded-xl">
    <div class="card-body space-y-4">
        <div>
            <h1 class="card-title">Buat Agenda Baru</h1>
            <p class="text-sm text-base-content/70">Isi form di bawah untuk membuat agenda laporan</p>
        </div>
        <div class="divider my-2"></div>

        @if (session('success'))
            <div role="alert" class="alert alert-success">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-4">
            <!-- Judul -->
            <div class="form-control">
                <label class="label"><span class="label-text">Judul Agenda</span></label>
                <input type="text" wire:model="title" class="input input-bordered w-full" placeholder="Masukkan judul agenda">
                @error('title') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Tanggal -->
            <div class="form-control">
                <label class="label"><span class="label-text">Tanggal</span></label>
                <input type="date" wire:model="date" class="input input-bordered w-full">
                @error('date') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Lokasi -->
            <div class="form-control">
                <label class="label"><span class="label-text">Lokasi</span></label>
                <input type="text" wire:model="location" class="input input-bordered w-full" placeholder="Lokasi agenda">
                @error('location') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Reviewer -->
            <div class="form-control">
                <label class="label"><span class="label-text">Reviewer (Opsional)</span></label>
                <select wire:model="reviewed_by" class="select select-bordered w-full">
                    <option value="">Pilih Reviewer</option>
                    @foreach ($personInCharges as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('reviewed_by') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Tombol -->
            <div class="flex gap-2 justify-end pt-2">
                <button type="reset" class="btn btn-ghost">Reset</button>
                <button type="submit" class="btn btn-primary">Simpan Agenda</button>
            </div>
        </form>
    </div>
</div>
