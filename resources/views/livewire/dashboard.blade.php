<div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="card card-border bg-primary rounded-md">
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

    <div>
        <div class="card card-border rounded-md">
            <div class="card-body">
                <div class="card-title font-normal justify-between">
                    Sistem Audit & Pengawasan
                    <a class="btn" href="{{ route('dashboard.audit.master') }}">More</a>
                </div>
                <div>
                    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
                    </div>
                </div>

                <div class="divider"></div>
                <div class="card-title font-normal justify-between">
                    Manajemen Konsumsi
                    <a class="btn" href="{{ route('dashboard.audit.master') }}">More</a>
                </div>
                <div>
                    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
                        <livewire:dashboard.table.audit-reports-table />
                    </div>
                </div>
                <div class="divider"></div>
                <div class="card-title font-normal justify-between">
                    Manajemen Pemiliharaan
                    <button class="btn">More</button>
                </div>
                <div>
                    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
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
                                <!-- row 1 -->
                                <tr>
                                    <td>Cy Ganderton</td>
                                    <td>Quality Control Specialist</td>
                                    <td>Blue</td>
                                </tr>
                                <!-- row 2 -->
                                <tr>
                                    <td>Hart Hagerty</td>
                                    <td>Desktop Support Technician</td>
                                    <td>Purple</td>
                                </tr>
                                <!-- row 3 -->
                                <tr>
                                    <td>Brice Swyre</td>
                                    <td>Tax Accountant</td>
                                    <td>Red</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="card-title font-normal justify-between">
                    Manajemen Agenda
                    <button class="btn">More</button>
                </div>
                <div>
                    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
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
                                <!-- row 1 -->
                                <tr>
                                    <td>Cy Ganderton</td>
                                    <td>Quality Control Specialist</td>
                                    <td>Blue</td>
                                </tr>
                                <!-- row 2 -->
                                <tr>
                                    <td>Hart Hagerty</td>
                                    <td>Desktop Support Technician</td>
                                    <td>Purple</td>
                                </tr>
                                <!-- row 3 -->
                                <tr>
                                    <td>Brice Swyre</td>
                                    <td>Tax Accountant</td>
                                    <td>Red</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="divider"></div>
            </div>
        </div>
    </div>