<?php

namespace App\Livewire\Consumption;

use App\Livewire\Components\DataTable;
use App\Models\ConsumptionReport;
use Livewire\Component;
use Debugbar;


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

        parent::mount();
    }


    public function render()
    {
        return parent::render();
    }
}
