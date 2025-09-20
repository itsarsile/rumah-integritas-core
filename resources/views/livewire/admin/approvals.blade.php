<div class="space-y-6">
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="card bg-white">
        <div class="card-body">
            <h2 class="card-title">Persetujuan Laporan</h2>
            <div class="tabs tabs-boxed mt-4">
                <a class="tab {{ $tab==='all' ? 'tab-active' : '' }}" wire:click="setTab('all')">Semua</a>
                <a class="tab {{ $tab==='audit' ? 'tab-active' : '' }}" wire:click="setTab('audit')">Audit</a>
                <a class="tab {{ $tab==='maintenance' ? 'tab-active' : '' }}" wire:click="setTab('maintenance')">Pemeliharaan</a>
                <a class="tab {{ $tab==='agenda' ? 'tab-active' : '' }}" wire:click="setTab('agenda')">Agenda</a>
                <a class="tab {{ $tab==='consumption' ? 'tab-active' : '' }}" wire:click="setTab('consumption')">Konsumsi</a>
            </div>

            @php
                $sections = [
                    ['key' => 'audit', 'title' => 'Audit', 'rows' => $pendingAudit, 'cols' => ['report_code' => 'Kode', 'report_title' => 'Judul']],
                    ['key' => 'maintenance', 'title' => 'Pemeliharaan', 'rows' => $pendingMaintenance, 'cols' => ['request_code' => 'Kode', 'description' => 'Deskripsi']],
                    ['key' => 'agenda', 'title' => 'Agenda', 'rows' => $pendingAgenda, 'cols' => ['request_code' => 'Kode', 'title' => 'Judul']],
                    ['key' => 'consumption', 'title' => 'Konsumsi', 'rows' => $pendingConsumption, 'cols' => ['request_code' => 'Kode', 'request_title' => 'Judul']],
                ];
            @endphp

            @foreach ($sections as $sec)
                @if ($tab==='all' || $tab===$sec['key'])
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-lg">{{ $sec['title'] }}</h3>
                            <span class="text-sm text-gray-500">{{ $sec['rows']->count() }} menunggu</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra w-full">
                                <thead>
                                    <tr>
                                        @foreach ($sec['cols'] as $label)
                                            <th>{{ $label }}</th>
                                        @endforeach
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse ($sec['rows'] as $row)
                                    <tr>
                                        @foreach (array_keys($sec['cols']) as $key)
                                            <td>{{ data_get($row, $key) }}</td>
                                        @endforeach
                                        <td>
                                            <span class="badge badge-warning">Menunggu</span>
                                        </td>
                                        <td class="space-x-2">
                                            <button class="btn btn-xs btn-success" wire:click="approve('{{ $sec['key'] }}', {{ $row->id }})">Setujui</button>
                                            <button class="btn btn-xs btn-error" wire:click="reject('{{ $sec['key'] }}', {{ $row->id }})">Tolak</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($sec['cols']) + 2 }}" class="text-center text-gray-500">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
