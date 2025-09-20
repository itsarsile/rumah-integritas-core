<?php

namespace App\Livewire\Agenda;

use App\Livewire\Components\DataTable;
use App\Models\AgendaReports;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Manajemen Agenda')]
class Master extends DataTable
{
    public function mount()
    {
        $this->model = AgendaReports::class;
        $this->relationships = ['personInCharge', 'creator'];

        $this->columns = [
            ['key' => 'created_at', 'label' => 'Waktu', 'format' => 'datetime'],
            ['key' => 'title', 'label' => 'Judul'],
            [
                'key' => 'personInCharge.name',
                'label' => 'Pena',
                'view' => 'components.user-avatar',
                'viewData' => ['user' => 'personInCharge'],
            ],
            [
                'key' => 'creator.name',
                'label' => 'Pembuat',
                'view' => 'components.user-avatar',
                'viewData' => ['user' => 'creator'],
            ],
            ['key' => 'status', 'label' => 'Status', 'format' => 'status'],
            [
                'key' => 'action',
                'label' => 'Aksi',
                'view' => 'components.agenda-actions',
                'viewData' => ['agenda' => '']
            ],
        ];

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
                'key' => 'date',
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
