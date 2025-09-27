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

    <div class="card bg-white w-full border border-base-200 rounded-2xl">
        <div class="p-6 border-b border-base-200">
            <div class="card-title">
                Manajemen Agenda
            </div>
        </div>  
        <div class="card-body">
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Nama Kegiatan</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $agenda->title }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Tanggal Kegiatan</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p>{{ \Carbon\Carbon::parse($agenda->date)->locale('id')->translatedFormat('j F Y') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Lokasi</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $agenda->location }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Penanggung Jawab</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $agenda->personInCharge->name }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Status Pengajuan</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : 
                    @php
                        $statusLabel = match ($agenda->status) {
                            'pending' => 'Menunggu',
                            'rejected' => 'Ditolak',
                            'accepted' => 'Disetujui',
                            default => 'Tidak diketahui',
                        };
                        $statusClass = match ($agenda->status) {
                            'pending' => 'bg-yellow-100 border border-yellow-200 text-yellow-800',
                            'rejected' => 'bg-red-100 border border-red-200 text-red-800',
                            'accepted' => 'bg-green-100 border border-green-200 text-green-800',
                            default => 'bg-gray-100 border border-gray-200 text-gray-800',
                        };
                    @endphp
                    <span class="{{ $statusClass }} text-xs font-light mr-2 px-2.5 py-0.5 rounded-full">{{ $statusLabel }}</span>
                </div>
            </div>
            {{-- <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Email Pemohon</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $consumption->email }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Judul Kegiatan Rapat</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $consumption->request_title }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Waktu Pelaksanaan</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $consumption->event_request_date->locale('id')->translatedFormat('j F Y') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Status Pengajuan</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : 
                    @php
                        $statusLabel = match ($consumption->status) {
                            'pending' => 'Menunggu',
                            'rejected' => 'Ditolak',
                            'accepted' => 'Disetujui',
                            default => 'Tidak diketahui',
                        };
                        $statusClass = match ($consumption->status) {
                            'pending' => 'bg-yellow-100 border border-yellow-200 text-yellow-800',
                            'rejected' => 'bg-red-100 border border-red-200 text-red-800',
                            'accepted' => 'bg-green-100 border border-green-200 text-green-800',
                            default => 'bg-gray-100 border border-gray-200 text-gray-800',
                        };
                    @endphp
                    <span class="{{ $statusClass }} text-xs font-light mr-2 px-2.5 py-0.5 rounded-full">{{ $statusLabel }}</span>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Jenis Konsumsi</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $consumption->consumptionType->name }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Sub bagian/divisi/tim</span></label>
                </div>
                <div class="col-span-2 flex items-start gap-2">
                    : <p>{{ $consumption->divisions->pluck('name')->implode(', ') }}</p>
                </div>
            </div> --}}
        </div>
    </div>
</div>
