{{-- resources/views/livewire/audit/master.blade.php --}}
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Page Header --}}
        <div class="mb-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Konsumsi Audit Master Data</h1>
                    <p class="mt-2 text-sm text-gray-700">
                        Kelola dan pantau semua permintaan konsumsi dalam sistem
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    {{-- Optional: Add export or other action buttons here --}}
                    <button type="button"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Export Data
                    </button>
                </div>
            </div>
        </div>

        {{-- DataTable Component --}}
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                @livewire('agenda.master')
            </div>
        </div>
    </div>
</div>