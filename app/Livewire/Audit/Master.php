<?php

namespace App\Livewire\Audit;

use App\Livewire\Components\DataTable;
use App\Models\AuditReports;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Sistem Audit dan Pengawasan')]
class Master extends DataTable
{
    public function mount()
    {
        $this->model = AuditReports::class;
        $this->relationships = ['creator'];

        // Define columns with proper structure
        $this->columns = [
            ['key' => 'created_at', 'label' => 'Waktu', 'format' => 'datetime'],
            ['key' => 'activity', 'label' => 'Aktivitas', 'text' => 'Sa&P'],
            [
                'key' => 'creator.name',
                'label' => 'Pengaju',
                'view' => 'components.user-avatar',
                'viewData' => ['user' => 'creator'],
            ],
            ['key' => 'status', 'label' => 'Status', 'format' => 'status', 'class' => ''],
            ['key' => 'report_description', 'label' => 'Deskripsi', 'class' => 'truncate max-w-xs'],
            [
                'key' => 'actions',
                'label' => 'Aksi',
                'view' => 'components.audit-actions',
                'viewData' => ['audit' => ''],
            ],
        ];

        // Define searchable columns
        $this->searchableColumns = ['user_name', 'description'];

        // Define sortable columns
        $this->sortableColumns = [
            ['key' => 'created_at'],
            ['key' => 'user_name'],
        ];

        // Define filterable columns
        $this->filterableColumns = [
            [
                'key' => 'activity',
                'label' => 'Aktivitas',
                'type' => 'select',
                'options' => [
                    'Sa&P' => 'Sa&P',
                    'Login' => 'Login',
                    'Logout' => 'Logout',
                    'Create' => 'Create',
                    'Update' => 'Update',
                    'Delete' => 'Delete',
                    // Add more activity types as needed
                ]
            ],
            [
                'key' => 'created_at',
                'label' => 'Tanggal',
                'type' => 'date_range'
            ],
            [
                'key' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'options' => [
                    'pending' => 'Menunggu',
                    'accepted' => 'Disetujui',
                    'rejected' => 'Ditolak',
                ]
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
