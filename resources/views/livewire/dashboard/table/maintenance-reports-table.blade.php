<table class="w-full text-sm text-neutral-700">
    <thead class="bg-base-200/70 text-xs tracking-wide text-neutral-500">
        <tr>
            <th class="py-3 px-4 text-left font-light rounded-tl-xl rounded-bl-xl">Waktu</th>
            <th class="py-3 px-4 text-left font-light">Aktivitas</th>
            <th class="py-3 px-4 text-left font-light">Pengaju</th>
            <th class="py-3 px-4 text-left font-light">Deskripsi</th>
            <th class="py-3 px-4 text-left font-light">Status</th>
            <th class="py-3 px-4 text-left font-light rounded-tr-xl rounded-br-xl">Actions</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-base-200">
        @foreach ($data as $item)
            @php
                $time = \Carbon\Carbon::parse($item->created_at)->format('h:i A');
                $statusLabel = match ($item->status) {
                    'pending' => 'Menunggu',
                    'rejected' => 'Ditolak',
                    'accepted' => 'Disetujui',
                    default => 'Tidak diketahui',
                };
                $statusClass = match ($item->status) {
                    'pending' => 'bg-yellow-100 border border-yellow-200 text-yellow-800',
                    'rejected' => 'bg-red-100 border border-red-200 text-red-800',
                    'accepted' => 'bg-green-100 border border-green-200 text-green-800',
                    default => 'bg-gray-100 border border-gray-200 text-gray-800',
                };
            @endphp
            <tr class="hover:bg-base-100/40 border-b border-base-200 bg-white">
                <td class="p-2 align-middle whitespace-nowrap text-neutral-600">{{ $time }}</td>
                <td class="p-2 align-middle">
                    <div class="flex items-center gap-2">
                        <x-feathericon-tool class="w-4 h-4 text-primary" />
                        <span class="text-neutral-700">Pemeliharaan</span>
                    </div>
                </td>
                <td class="p-2 align-middle">
                    <div class="flex items-center gap-3 border border-base-200 rounded-full p-1 w-fit pr-3">
                        @if ($item->user_avatar)
                        @php
                        $src = $item->user_avatar;
                        if (!\Illuminate\Support\Str::startsWith($src, ['http://', 'https://'])) {
                            $src = \Illuminate\Support\Facades\Storage::url($src);
                        }
                        @endphp
                            <div class="avatar">
                                <div class="size-6 rounded-full border border-base-200 overflow-hidden">
                                    <img src="{{ $src }}" alt="Avatar of {{ $item->user_name }}" />
                                </div>
                            </div>
                            @else
                                <div class="size-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-semibold">
                                    {{ strtoupper(substr($item->user_name, 0, 1)) }}
                                </div>
                            @endif
                        <span class="font-light">{{ $item->user_name }}</span>
                    </div>
                </td>
                <td class="p-2 align-middle text-neutral-500 max-w-sm">
                    {{ \Illuminate\Support\Str::limit($item->description, 80) }}
                </td>
                <td class="p-2 align-middle">
                    <span class="{{ $statusClass }} text-xs font-light mr-2 px-2.5 py-0.5 rounded-full">{{ $statusLabel }}</span>
                </td>
                <td class="p-2 align-middle">
                    <a href="{{ route('dashboard.maintenance.show', $item->id) }}" class="text-neutral-500 flex items-center gap-1">
                        <i class="fas fa-eye text-neutral-500"></i> <span class="text-xs">Detail</span>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
