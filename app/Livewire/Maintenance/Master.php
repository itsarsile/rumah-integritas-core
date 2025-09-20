<?php

namespace App\Livewire\Maintenance;

use App\Livewire\Components\DataTable;
use App\Models\MaintenanceReports;

class Master extends DataTable
{
    public function mount()
    {
        $this->model = MaintenanceReports::class;
        $this->relationships = ['images'];

        $this->columns = [
            ['key' => 'created_at', 'label' => 'Waktu', 'format' => 'datetime'],
            ['key' => 'divisions.name', 'label' => 'Departemen'],
            [
                'key' => 'creator.name',
                'label' => 'Pengaju',
                'view' => 'components.user-avatar',
                'viewData' => ['user' => 'creator']
            ],
            ['key' => 'description', 'label' => 'Deskripsi'],
            ['key' => 'status', 'label' => 'Status', 'format' => 'status', 'class' => ''],
            ['key' => 'actions', 'label' => 'Aksi', 'view' => 'components.maintenance-actions', 'viewData' => ['maintenance' => '']],
        ];

        parent::mount();
    }
    public function render()
    {
        return parent::render();
    }
}
