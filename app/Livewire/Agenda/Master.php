<?php

namespace App\Livewire\Agenda;

use App\Livewire\Components\DataTable;
use App\Models\AgendaReports;
use Livewire\Component;

class Master extends DataTable
{
    public function mount()
    {
        $this->model = AgendaReports::class;

        $this->columns = [
            ['key' => 'created_at', 'label' => 'Waktu', 'format' => 'datetime'],
            ['key' => 'title', 'label' => 'Judul'],
            ['key' => 'personInCharge.name', 'label' => 'Pena'],
            ['key' => 'creator.name', 'label' => 'Pembuat'],
            ['key' => 'status', 'label' => 'Status', 'format' => 'status'],
            [
                'key' => 'action',
                'label' => 'Aksi',
                'view' => 'components.agenda-actions',
                'viewData' => ['agenda' => '']
            ],
        ];


        parent::mount();
    }

    public function render()
    {
        return parent::render();
    }
}
