<table class="w-full text-sm text-neutral-700">
    <thead class="bg-base-200/70 text-xs font-medium uppercase tracking-wide text-neutral-500">
        <tr>
            <th class="py-3 px-4 text-left">Waktu</th>
            <th class="py-3 px-4 text-left">Aktivitas</th>
            <th class="py-3 px-4 text-left">Pengaju</th>
            <th class="py-3 px-4 text-left">Deskripsi</th>
            <th class="py-3 px-4 text-left">Status</th>
            <th class="py-3 px-4 text-left">Actions</th>
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
                    'pending' => 'text-warning font-semibold',
                    'rejected' => 'text-error font-semibold',
                    'accepted' => 'text-success font-semibold',
                    default => 'text-neutral-500',
                };
            @endphp
            <tr class="hover:bg-base-100/40">
                <td class="py-4 px-4 align-middle whitespace-nowrap text-neutral-600">{{ $time }}</td>
                <td class="py-4 px-4 align-middle">
                    <div class="flex items-center gap-2">
                        <x-feathericon-tool class="w-4 h-4 text-primary" />
                        <span class="font-medium text-neutral-700">Pemeliharaan</span>
                    </div>
                </td>
                <td class="py-4 px-4 align-middle">
                    <div class="flex items-center gap-3">
                        <div class="avatar">
                            @if ($item->user_avatar)
                                @php
                                    $src = $item->user_avatar;
                                    if (!\Illuminate\Support\Str::startsWith($src, ['http://', 'https://'])) {
                                        $src = \Illuminate\Support\Facades\Storage::url($src);
                                    }
                                @endphp
                                <div class="w-8 h-8 rounded-full border border-base-200 overflow-hidden">
                                    <img src="{{ $src }}" alt="Avatar of {{ $item->user_name }}" />
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-semibold">
                                    {{ strtoupper(substr($item->user_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <span class="font-medium">{{ $item->user_name }}</span>
                    </div>
                </td>
                <td class="py-4 px-4 align-middle text-neutral-500 max-w-sm">
                    {{ \Illuminate\Support\Str::limit($item->description, 80) }}
                </td>
                <td class="py-4 px-4 align-middle">
                    <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
                <td class="py-4 px-4 align-middle">
                    <a href="{{ route('dashboard.maintenance.show', $item->id) }}" class="text-primary font-semibold">View</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
