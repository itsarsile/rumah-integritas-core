<div class="space-y-4">
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="card bg-base-100">
        <div class="card-body">
            <div class="mb-2">
                <h2 class="card-title">Manajemen Pemeliharaan</h2>
            </div>
            <div class="divider my-2"></div>

            <div class="overflow-x-auto">
                <table class="table">
                    <tbody>
                        <tr>
                            <th class="w-64 align-top bg-base-200">Jenis Peralatan</th>
                            <td>{{ $maintenance->assetType->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="align-top bg-base-200">Deskripsi Masalah</th>
                            <td class="whitespace-pre-line">{{ $maintenance->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="align-top bg-base-200">Tingkat Prioritas</th>
                            <td>
                                <span class="badge {{ $maintenance->priority_badge_class }}">
                                    {{ $maintenance->priority_text }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="align-top bg-base-200">Lokasi / Departemen</th>
                            <td>{{ $maintenance->divisions->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="align-top bg-base-200">Status Pengajuan</th>
                            <td>
                                <span class="badge {{ $maintenance->status_badge_class }}">
                                    {{ $maintenance->status_text }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="align-top bg-base-200">Bukti</th>
                            <td>
                                @if($maintenance->images && $maintenance->images->count())
                                    <div class="flex flex-wrap gap-4">
                                        @foreach($maintenance->images as $img)
                                            <img src="{{ Storage::disk('public')->url($img->image_path) }}" alt="Bukti"
                                                 class="w-40 h-28 object-cover rounded-xl shadow" />
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm opacity-60">Tidak ada bukti</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
