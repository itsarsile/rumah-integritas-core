<?php

namespace App\Livewire\Audit;

use App\Livewire\Components\DataTable;
use App\Models\AuditReports;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Sistem Audit & Pengawasan')]
class Master extends DataTable
{
    public function mount()
    {
        $this->model = AuditReports::class;
        $this->relationships = ['creator'];

        // Define columns with proper structure
        $this->columns = [
            ['key' => 'created_at', 'label' => 'Waktu', 'format' => 'datetime'],
            ['key' => 'report_code', 'label' => 'Kode Laporan'],
            ['key' => 'report_title', 'label' => 'Judul Laporan', 'class' => 'truncate max-w-sm'],
            ['key' => 'report_description', 'label' => 'Ringkasan', 'class' => 'truncate max-w-lg'],
            [
                'key' => 'creator.name',
                'label' => 'Pengaju',
                'view' => 'components.user-avatar',
                'viewData' => ['user' => 'creator'],
            ],
            ['key' => 'status', 'label' => 'Status', 'format' => 'status'],
            [
                'key' => 'actions',
                'label' => 'Aksi',
                'view' => 'components.audit-actions',
                'viewData' => ['audit' => ''],
            ],
        ];

        // Define searchable columns
        $this->searchableColumns = ['report_code', 'report_title', 'report_description'];

        // Define sortable columns
        $this->sortableColumns = [
            ['key' => 'created_at'],
            ['key' => 'report_title'],
            ['key' => 'status'],
        ];

        // Define filterable columns
        $this->filterableColumns = [
            [
                'key' => 'report_title',
                'label' => 'Judul Laporan',
                'type' => 'text',
            ],
            [
                'key' => 'report_description',
                'label' => 'Deskripsi',
                'type' => 'text',
            ],
            [
                'key' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'options' => [
                    'draft' => 'Draft',
                    'in_progress' => 'Sedang Berjalan',
                    'completed' => 'Selesai',
                    'published' => 'Dipublikasikan',
                ],
            ],
        ];

        // Set default sorting
        $this->defaultSort = 'created_at';
        $this->defaultSortDirection = 'desc';

        // Set pagination options
        $this->perPageOptions = [10, 25, 50, 100];


        parent::mount();
    }

    public function render()
    {
        return parent::render();
    }
}
