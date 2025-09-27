<div class="space-y-6">
    <div class="card bg-white w-full border border-base-200 rounded-2xl">
        <div class="p-6 border-b border-base-200">
            <h2 class="card-title">Tracking Permintaan</h2>

            <span class="text-sm font-light">Status dan riwayat permintaan Anda</span>
        </div>  
        <div class="card-body">
            <div class="flex flex-wrap items-center gap-3">
                <div class="dropdown ">
                    <div tabindex="0" role="button" class="btn btn-sm bg-base-100/40">
                        {{ $status === '' ? 'Semua Status' : ucfirst($status) }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
                    </div>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                        <li><a wire:click="$set('status','')">Semua Status</a></li>
                        <li><a wire:click="$set('status','pending')">Menunggu</a></li>
                        <li><a wire:click="$set('status','approved')">Disetujui</a></li>
                        <li><a wire:click="$set('status','rejected')">Ditolak</a></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn btn-sm bg-base-100/40">
                        {{ $type === '' ? 'Semua jenis' : ucfirst($type) }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
                    </div>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                        <li><a wire:click="$set('type','')">Semua jenis</a></li>
                        <li><a wire:click="$set('type','consumption')">Konsumsi</a></li>
                        <li><a wire:click="$set('type','maintenance')">Pemeliharaan</a></li>
                        <li><a wire:click="$set('type','agenda')">Agenda</a></li>
                    </ul>
                </div>

                <div class="ml-auto">
                    <label class="input input-bordered bg-base-100/40 input-sm flex items-center gap-2">
                        <input type="text" class="grow" placeholder="Search" wire:model.debounce.400ms="search" />
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" /></svg>
                    </label>
                </div>
            </div>

            <div class="mt-4 overflow-x-auto border border-base-200 rounded-2xl overflow-hidden">
                <table class="table">
                    <thead class="bg-base-100">
                        <tr>
                            <th>Aktivitas</th>
                            <th>ID</th>
                            <th>Jenis</th>
                            <th>Tanggal</th>
                            <th>Pemohon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $row)
                            @php
                                $status = $row->status;
                                $statusText = $status === 'pending' ? 'Menunggu' : ($status === 'rejected' ? 'Ditolak' : 'Disetujui');
                                $statusClass = match ($status) {
                                    'pending' => 'bg-yellow-100 border border-yellow-200 text-yellow-800',
                                    'rejected' => 'bg-red-100 border border-red-200 text-red-800',
                                    'accepted', 'approved' => 'bg-green-100 border border-green-200 text-green-800',
                                    default => 'bg-gray-100 border border-gray-200 text-gray-800',
                                };
                                $detailRoute = $row->source === 'consumption' ? route('dashboard.consumption.show', $row->source_id)
                                    : ($row->source === 'maintenance' ? route('dashboard.maintenance.show', $row->source_id)
                                    : route('dashboard.agenda.show', $row->source_id));
                            @endphp
                            <tr>
                                <td>{{ $row->activity }}</td>
                                <td>{{ $row->request_code }}</td>
                                <td>{{ $row->type }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($row->date)->translatedFormat('d F Y') }}</td>
                                <td>{{ $row->requester }}</td>
                                <td><span class="{{ $statusClass }} text-xs font-light mr-2 px-2.5 py-0.5 rounded-full">{{ $statusText }}</span></td>
                                <td><a href="{{ $detailRoute }}">
                                    <i class="fas fa-eye text-neutral-500"></i>    
                                </a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-gray-500">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-2">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
