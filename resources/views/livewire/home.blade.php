<div class="space-y-4 border rounded-md p-4 bg-white">
    {{-- The whole world belongs to you. --}}
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="card card-border bg-primary text-primary-content rounded-md">
            <div class="card-body">
                <h2 class="card-title font-normal">
                    <x-feathericon-file />
                    Total Permintaan
                </h2>
                <p class="text-3xl font-bold">12</p>

            </div>
        </div>
        <div class="card card-border bg-warning rounded-md">
            <div class="card-body text-warning-content">
                <h2 class="card-title font-normal">
                    <x-feathericon-alert-circle class="w-6 h-6" />
                    Menunggu Review
                </h2>
                <p class="text-3xl font-bold">5</p>

            </div>
        </div>
        <div class="card card-border bg-success rounded-md">
            <div class="card-body text-success-content">
                <h2 class="card-title font-normal">
                    <x-feathericon-check-circle />
                    Disetujui
                </h2>
                <p class="text-3xl font-bold">2</p>

            </div>
        </div>
    </div>

    <div class="card card-border rounded-md">
        <div class="card-body overflow-x-auto">
            <div class="card-title font-normal justify-between">
                Sistem Audit & Pengawasan
                <a class="btn" href="{{ route('dashboard.audit.master') }}">More</a>
            </div>
            <livewire:dashboard.table.audit-reports-table />
        </div>
    </div>
    <div class="card card-border rounded-md">
        <div class="card-body">
            <div class="card-title font-normal justify-between">
                Manajemen Konsumsi
                <a class="btn" href="{{ route('dashboard.consumption.master') }}">More</a>
            </div>
            <livewire:dashboard.table.consumption-reports-table />
        </div>
    </div>
    <div class="card card-border rounded-md">
        <div class="card-body">
            <div class="card-title font-normal justify-between">
                Manajemen Pemeliharaan
                <a class="btn" href="{{ route('dashboard.maintenance.master') }}">More</a>
            </div>
            <livewire:dashboard.table.maintenance-reports-table />
        </div>
    </div>
    <div class="card card-border rounded-md">
        <div class="card-body">
            <div class="card-title font-normal justify-between">
                Manajemen Agenda
                <a class="btn" href="{{ route('dashboard.agenda.master') }}">More</a>
            </div>
            <livewire:dashboard.table.agenda-reports-table />
        </div>
    </div>

</div>
