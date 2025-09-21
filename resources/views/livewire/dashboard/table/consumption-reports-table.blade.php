<table class="table">
    <!-- head -->
    <thead class="bg-base-200">
        <tr>
            <th>Waktu</th>
            <th>Aktivitas</th>
            <th>Pengaju</th>
            <th>Deskripsi</th>
            <th>Status</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->created_at }}</td>
                <td>Konsumsi</td>
                <td class="flex items-center gap-2">
                    <div class="avatar">
                        @if ($item->user_avatar)
                            @php
                                $src = $item->user_avatar;
                                if (!\Illuminate\Support\Str::startsWith($src, ['http://', 'https://'])) {
                                    $src = \Illuminate\Support\Facades\Storage::url($src);
                                }
                            @endphp
                            <div class="w-6 h-6 rounded-full ring ring-primary ring-offset-base-100 ring-offset-1 overflow-hidden">
                                <img src="{{ $src }}" alt="Avatar of {{ $item->user_name }}" />
                            </div>
                        @else
                            <div class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px] font-semibold">
                                {{ strtoupper(substr($item->user_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    {{ $item->user_name }}
                </td>
                <td class="truncate max-w-3xs">
                    {{ $item->description }}
                </td>
                <td>
                    <span 
                        class="badge
                        @if ($item->status == 'pending')
                            badge-warning 
                        @elseif ($item->status == 'rejected')
                            badge-error
                        @elseif ($item->status == 'accepted')
                            badge-success
                        @else
                        @endif
                        ">

                        @if ($item->status == 'pending')
                            Menunggu
                        @elseif ($item->status == 'rejected')
                            Ditolak
                        @elseif ($item->status == 'accepted')
                            Diterima
                        @else
                            Tidak diketahui
                        @endif
                    </span>
                </td>
                <td><a href="/dashboard/audit/{{ $item->id }}" class="btn btn-sm">View</a></td>
            </tr>

        @endforeach
    </tbody>
</table>
