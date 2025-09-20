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
        @foreach ($data as $audit_report)
            <tr>
                <td>{{ $audit_report->created_at }}</td>
                <td>Sa&P</td>
                <td class="flex items-center gap-2">
                    <div class="avatar">
                        @if ($audit_report->user_avatar)
                            <div class="w-6 h-6 rounded-full ring ring-primary ring-offset-base-100 ring-offset-1 overflow-hidden">
                                <img src="{{ $audit_report->user_avatar }}" alt="Avatar of {{ $audit_report->user_name }}" />
                            </div>
                        @else
                            <div class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px] font-semibold">
                                {{ strtoupper(substr($audit_report->user_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    {{ $audit_report->user_name }}
                </td>
                <!-- Concat the adescription if its too long -->

                <td class="truncate max-w-3xs">
                    <!-- {{ Str::limit($audit_report->report_description, 50) }} -->
                    {{ $audit_report->report_description }}
                </td>
                <!-- Render status badge with daisyui -->
                <td>
                    <span 
                        class="badge
                        @if ($audit_report->status == 'pending')
                            badge-warning 
                        @elseif ($audit_report->status == 'rejected')
                            badge-error
                        @elseif ($audit_report->status == 'accepted')
                            badge-success
                        @else
                        @endif
                        ">

                        @if ($audit_report->status == 'pending')
                            Menunggu
                        @elseif ($audit_report->status == 'rejected')
                            Ditolak
                        @elseif ($audit_report->status == 'accepted')
                            Diterima
                        @else
                            Tidak diketahui
                        @endif
                    </span>
                </td>
                <td><a href="/dashboard/audit/{{ $audit_report->id }}" class="btn btn-sm">View</a></td>
            </tr>

        @endforeach
    </tbody>
</table>
