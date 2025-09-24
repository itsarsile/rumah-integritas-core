<div class="space-y-4 border rounded-md p-4 bg-white">
    {{-- The whole world belongs to you. --}}
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="card card-border text-primary-content rounded-md">
            <div class="card-body">
                <h2 class="card-title font-normal">
                    <x-feathericon-file />
                    Total Permintaan
                </h2>
                <p class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</p>

            </div>
        </div>
        <div class="card card-border  rounded-md">
            <div class="card-body text-warning-content">
                <h2 class="card-title font-normal">
                    <x-feathericon-alert-circle class="w-6 h-6" />
                    Menunggu Review
                </h2>
                <p class="text-3xl font-bold">{{ $stats['pending'] ?? 0 }}</p>

            </div>
        </div>
        <div class="card card-border  rounded-md">
            <div class="card-body text-success-content">
                <h2 class="card-title font-normal">
                    <x-feathericon-check-circle />
                    Disetujui
                </h2>
                <p class="text-3xl font-bold">{{ $stats['approved'] ?? 0 }}</p>

            </div>
        </div>
    </div>

    <section class="border border-dashed border-primary/40 rounded-xl bg-white shadow-sm">
        <header class="flex items-center justify-between px-6 py-4">
            <h3 class="text-lg font-semibold">Sistem Audit & Pengawasan</h3>
            <a class="btn btn-primary btn-sm rounded-full px-6" href="{{ route('dashboard.audit.master') }}">More</a>
        </header>
        <div class="px-6 pb-6 overflow-x-auto">
            <livewire:dashboard.table.audit-reports-table />
        </div>
    </section>
    <section class="border border-dashed border-primary/40 rounded-xl bg-white shadow-sm">
        <header class="flex items-center justify-between px-6 py-4">
            <h3 class="text-lg font-semibold">Manajemen Konsumsi</h3>
            <a class="btn btn-primary btn-sm rounded-full px-6" href="{{ route('dashboard.consumption.master') }}">More</a>
        </header>
        <div class="px-6 pb-6 overflow-x-auto">
            <livewire:dashboard.table.consumption-reports-table />
        </div>
    </section>
    <section class="border border-dashed border-primary/40 rounded-xl bg-white shadow-sm">
        <header class="flex items-center justify-between px-6 py-4">
            <h3 class="text-lg font-semibold">Manajemen Pemeliharaan</h3>
            <a class="btn btn-primary btn-sm rounded-full px-6" href="{{ route('dashboard.maintenance.master') }}">More</a>
        </header>
        <div class="px-6 pb-6 overflow-x-auto">
            <livewire:dashboard.table.maintenance-reports-table />
        </div>
    </section>
    <section class="border border-dashed border-primary/40 rounded-xl bg-white shadow-sm">
        <header class="flex items-center justify-between px-6 py-4">
            <h3 class="text-lg font-semibold">Manajemen Agenda</h3>
            <a class="btn btn-primary btn-sm rounded-full px-6" href="{{ route('dashboard.agenda.master') }}">More</a>
        </header>
        <div class="px-6 pb-6 overflow-x-auto">
            <livewire:dashboard.table.agenda-reports-table />
        </div>
    </section>

</div>
