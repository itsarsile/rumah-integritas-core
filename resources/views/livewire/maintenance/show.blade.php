<div class="space-y-4">
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="card bg-white w-full border border-base-200 rounded-2xl">
        <div class="p-6 border-b border-base-200">
            <div class="card-title">
                Manajemen Pemeliharaan
            </div>
        </div>          
        <div class="card-body">
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Jenis Peralatan</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $maintenance->assetType->name }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Deskripsi Masalah</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $maintenance->description }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Tingkat Prioritas</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $maintenance->priority_text }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Lokasi / Departemen</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $maintenance->divisions->name }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Status Pengajuan</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : 
                    @php
                        $statusClass = match ($maintenance->status_text) {
                            'Menunggu' => 'bg-yellow-100 border border-yellow-200 text-yellow-800',
                            'Ditolak' => 'bg-red-100 border border-red-200 text-red-800',
                            'Disetujui' => 'bg-green-100 border border-green-200 text-green-800',
                            default => 'bg-gray-100 border border-gray-200 text-gray-800',
                        };
                    @endphp
                    <span class="{{ $statusClass }} text-xs font-light mr-2 px-2.5 py-0.5 rounded-full"> {{ $maintenance->status_text }}</span>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Bukti</span></label>
                </div>
                <div class="col-span-2 flex items-start gap-2">
                    : 
                    @if($maintenance->images && $maintenance->images->count())
                        <div class="flex flex-wrap gap-4 mt-2">
                            @foreach($maintenance->images as $img)
                                <img src="{{ Storage::disk('public')->url($img->image_path) }}" alt="Bukti"
                                        class="w-40 h-28 object-cover rounded-xl bg-base-100" />
                            @endforeach
                        </div>
                    @else
                        <span class="text-sm opacity-60">Tidak ada bukti</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
