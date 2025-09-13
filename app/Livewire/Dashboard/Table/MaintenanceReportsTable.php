<?php

namespace App\Livewire\Dashboard\Table;

use DB;
use Livewire\Component;

class MaintenanceReportsTable extends Component
{
    public $data;
    public function mount()
    {
        $this->data = DB::table("maintenance_reports")
            ->join("users", "maintenance_reports.created_by", "=", "users.id")
            ->select("maintenance_reports.*", "users.name as user_name", "users.avatar as user_avatar")
            ->limit(5)
            ->get();
    }
    public function render()
    {
        return view('livewire.dashboard.table.maintenance-reports-table');
    }
}
