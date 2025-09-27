<div class="space-y-6">
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="card bg-white w-full border border-base-200 rounded-2xl">
        <div class="p-6 border-b border-base-200">
            <div class="card-title">
                Manajemen Persetujuan
            </div>
            <span class="text-sm font-light">Review dan setujui permintaan yang masuk</span>
        </div>  
        <div class="card-body">
            @php
                $sections = [
                    ['key' => 'audit', 'title' => 'Audit', 'rows' => $pendingAudit, 'cols' => ['report_code' => 'Kode', 'report_title' => 'Judul']],
                    ['key' => 'maintenance', 'title' => 'Pemeliharaan', 'rows' => $pendingMaintenance, 'cols' => ['request_code' => 'Kode', 'description' => 'Deskripsi']],
                    ['key' => 'agenda', 'title' => 'Agenda', 'rows' => $pendingAgenda, 'cols' => ['request_code' => 'Kode', 'title' => 'Judul']],
                    ['key' => 'consumption', 'title' => 'Konsumsi', 'rows' => $pendingConsumption, 'cols' => ['request_code' => 'Kode', 'request_title' => 'Judul']],
                ];
            @endphp

            <div class="tabs tabs-boxed">
                <a class="tab {{ $tab==='all' ? 'tab-active font-semibold underline' : '' }}" wire:click="setTab('all')">Semua</a>
                <a class="tab {{ $tab==='audit' ? 'tab-active font-semibold underline' : '' }}" wire:click="setTab('audit')"><span class="size-5 absolute top-0 right-0 rounded-full bg-primary text-primary-content text-[10px] flex items-center justify-center">{{ count($pendingAudit) }}</span>Audit</a>
                <a class="tab {{ $tab==='maintenance' ? 'tab-active font-semibold underline' : '' }}" wire:click="setTab('maintenance')"><span class="size-5 absolute top-0 right-0 rounded-full bg-primary text-primary-content text-[10px] flex items-center justify-center">{{ count($pendingMaintenance) }}</span>Pemeliharaan</a>
                <a class="tab {{ $tab==='agenda' ? 'tab-active font-semibold underline' : '' }}" wire:click="setTab('agenda')"><span class="size-5 absolute top-0 right-0 rounded-full bg-primary text-primary-content text-[10px] flex items-center justify-center">{{ count($pendingAgenda) }}</span>Agenda</a>
                <a class="tab {{ $tab==='consumption' ? 'tab-active font-semibold underline' : '' }}" wire:click="setTab('consumption')"><span class="size-5 absolute top-0 right-0 rounded-full bg-primary text-primary-content text-[10px] flex items-center justify-center">{{ count($pendingConsumption) }}</span> Konsumsi</a>
            </div>


            @foreach ($sections as $sec)
                @if ($tab==='all' || $tab===$sec['key'])
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold">{{ $sec['title'] }}</h3>
                            <span class="text-sm text-gray-500">{{ $sec['rows']->count() }} menunggu</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table w-full p-0">
                                <thead>
                                    <tr>
                                        @foreach ($sec['cols'] as $label)
                                            <th class="font-light">{{ $label }}</th>
                                        @endforeach
                                        <th class="font-light">Status</th>
                                        <th class="font-light">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse ($sec['rows'] as $row)
                                    <tr>
                                        @foreach (array_keys($sec['cols']) as $key)
                                            <td>{{ data_get($row, $key) }}</td>
                                        @endforeach
                                        <td>
                                            <span class="bg-yellow-100 border border-yellow-200 text-yellow-800 text-xs font-light mr-2 px-2.5 py-0.5 rounded-full">Menunggu</span>
                                        </td>
                                        <td class="flex gap-2">
                                            <button class="flex justify-center px-2 gap-1 items-center w-fit h-7 rounded-md border border-base-200 text-xs font-medium text-gray-600 hover:bg-base-100 cursor-pointer" wire:click="approve('{{ $sec['key'] }}', {{ $row->id }})"><i class="fa fa-circle-check text-green-700"></i> <p>Setujui</p></button>
                                            <button class="flex justify-center px-2 gap-1 items-center w-fit h-7 rounded-md border border-base-200 text-xs font-medium text-gray-600 hover:bg-base-100 cursor-pointer" wire:click="reject('{{ $sec['key'] }}', {{ $row->id }})"><i class="fa fa-circle-xmark text-red-700"></i> Tolak</button>
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
