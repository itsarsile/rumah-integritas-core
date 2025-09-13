<div class="card bg-base-100 w-full border border-base-content/20 rounded-xl">
    <div class="card-body space-y-4">
        <div>
            <h1 class="card-title">Manajemen Pemeliharaan</h1>
            <p class="text-sm text-base-content/70">Form permintaan pemeliharaan peralatan</p>
        </div>
        <div class="divider my-2"></div>

        @if (session('message'))
            <div role="alert" class="alert alert-success">
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-4">
            <!-- Jenis Peralatan -->
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Jenis Peralatan</span>
                </label>
                <select class="select select-bordered w-full" wire:model="assetId">
                    <option value="">Pilih Peralatan</option>
                    @foreach ($assets as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Deskripsi -->
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Deskripsi Masalah</span>
                </label>
                <textarea class="textarea textarea-bordered w-full" wire:model="description" rows="4"
                    placeholder="Jelaskan masalah peralatan"></textarea>
            </div>
            <!-- File -->
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Upload Bukti (Optional)</span>
                </label>
                <div class="border p-3 border-base-content/20 rounded-xl">
                    <input type="file" multiple accept="pdf,docx,doc,xlsx,xls,ppt,pptx" class="file-input file-input-ghost w-full" wire:model="files" multiple />   
                </div>
            </div>


            <div class="grid lg:grid-cols-2 gap-4 grid-cols-1">
                <!-- Tingkat Prioritas -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Tingkat Prioritas</span>
                    </label>
                    <select class="select select-bordered w-full" wire:model="priority">
                        <option value="">Pilih Prioritas</option>
                        <option value="low">Rendah</option>
                        <option value="medium">Normal</option>
                        <option value="high">Tinggi</option>
                    </select>
                </div>

                <!-- Divisi -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Divisi</span>
                    </label>
                    <select class="select select-bordered w-full" wire:model="divisionId">
                        <option value="">Pilih Divisi</option>
                        @foreach ($divisions as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <!-- Action Buttons -->
            <div class="flex gap-2 justify-end pt-2">
                <button type="reset" class="btn btn-ghost">Reset Form</button>
                <button type="submit" class="btn btn-primary">Ajukan Pemeliharaan</button>
            </div>
        </form>
    </div>
</div>