<?php

namespace App\Livewire\Consumption;

use App\Livewire\Components\DataTable;
use App\Models\ConsumptionReport;
use App\Models\Division;
use App\Models\ConsumptionType;
use Livewire\Component;
use Debugbar;
use Livewire\Attributes\Title;


#[Title('Manajemen Konsumsi')]
class Master extends DataTable
{
    public function mount()
    {
        $this->model = ConsumptionReport::class;
        $this->relationships = ['creator', 'divisions', 'consumptionType'];

        $this->columns = [
            ['key' => 'created_at', 'label' => 'Waktu', 'format' => 'datetime'],
            ['key' => 'divisions.name', 'label' => 'Departemen'],
            [
                'key' => 'creator.name',
                'label' => 'Pengaju',
                'view' => 'components.user-avatar',
                'viewData' => ['user' => 'creator']
            ],
            ['key' => 'consumptionType.name', 'label' => 'Jenis Konsumsi'],
            ['key' => 'audience_count', 'label' => 'Jumlah'],
            ['key' => 'description', 'label' => 'Deskripsi'],
            ['key' => 'status', 'label' => 'Status', 'format' => 'status', 'class' => ''],
            ['key' => 'actions', 'label' => 'Aksi', 'view' => 'components.consumption-actions', 'viewData' => ['consumption' => '']],
        ];

        // Filters: status, division, type, date range
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
                'key' => 'consumption_type_id',
                'label' => 'Jenis',
                'type' => 'select',
                'options' => ConsumptionType::orderBy('name')->pluck('name', 'id')->toArray(),
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
