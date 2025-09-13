<div>
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-error mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="card bg-white">
        <div class="card-body">
            <h2 class="card-title">Manajemen Konsumsi</h2>
            <div class="divider"></div>
            <table class="table">
                <tr>
                    <td class="w-1/3">Nama Pemohon</td>
                    <td class="w-1/2">{{ $consumption->creator->name }}</td>
                </tr>
                <tr>
                    <td class="w-1/2">Email Pemohon</td>
                    <td class="w-1/2">{{ $consumption->email }}</td>
                </tr>
                <tr>
                    <td class="w-1/2">Judul Kegiatan Rapat</td>
                    <td class="w-1/2">{{ $consumption->request_title }}</td>
                </tr>
                <tr>
                    <td>Waktu Pelaksanaan</td>
                    <td>{{ $consumption->event_request_date->locale('id')->translatedFormat('j F Y') }}</td>
                </tr>
                <tr>
                    <td>Status Pengajuan</td>
                    <td>
                        @php
                            $status = $consumption->status;
                            $statusText =
                                $status == 'pending'
                                    ? 'Menunggu'
                                    : ($status == 'rejected'
                                        ? 'Ditolak'
                                        : ($status == 'accepted'
                                            ? 'Diterima'
                                            : 'Tidak diketahui'));
                            $statusClass = $status == 'pending' ? 'badge badge-warning' : 'badge badge-success';
                        @endphp
                        <span class="{{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                </tr>
                <tr>
                    <td>Jenis Konsumsi</td>
                    <td>{{ $consumption->consumptionType->name }}</td>
                </tr>
                <tr>
                    <td>Sub bagian/bagian/tim</td>
                    <td>{{ $consumption->divisions->pluck('name')->implode(', ') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>