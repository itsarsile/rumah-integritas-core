<?php

namespace App\Livewire\Maintenance;

use App\Livewire\Components\DataTable;
use App\Models\MaintenanceReports;
use App\Models\Division;
use Livewire\Attributes\Title;

#[Title('Manajemen Pemeliharaan')]
class Master extends DataTable
{
    public function mount()
    {
        $this->model = MaintenanceReports::class;
        $this->relationships = ['images', 'creator', 'divisions'];

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
        // Filters: status, division, priority, date
        $this->filterableColumns = [
            [
                'key' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'options' => [
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                ],
            ],
            [
                'key' => 'division_id',
                'label' => 'Departemen',
                'type' => 'select',
                'options' => Division::orderBy('name')->pluck('name', 'id')->toArray(),
            ],
            [
                'key' => 'priority',
                'label' => 'Prioritas',
                'type' => 'select',
                'options' => [
                    'low' => 'Rendah',
                    'medium' => 'Sedang',
                    'high' => 'Penting',
                ],
            ],
            [
                'key' => 'created_at',
                'label' => 'Tanggal',
                'type' => 'date_range',
            ],
        ];

        parent::mount();
    }
    public function render()
    {
        return parent::render();
    }
}
