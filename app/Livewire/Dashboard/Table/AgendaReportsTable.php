<?php

namespace App\Livewire\Dashboard\Table;

use Livewire\Component;
use DB;

class AgendaReportsTable extends Component
{
    public $data;
    public function mount()
    {
        $this->data = DB::table("agenda_reports")
            ->join('users', 'agenda_reports.created_by', '=', 'users.id')
            ->select('agenda_reports.*', 'users.name as user_name', 'users.avatar as user_avatar')
            ->limit(5)
            ->get();
    }
    public function render()
    {
        return view('livewire.dashboard.table.agenda-reports-table');
    }
}
