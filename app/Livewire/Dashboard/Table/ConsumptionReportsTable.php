<?php

namespace App\Livewire\Dashboard\Table;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ConsumptionReportsTable extends Component
{
    public $data;
    public function mount()
    {
        $this->data = DB::table("consumption_reports")
        ->join("users","consumption_reports.created_by","=","users.id")
        ->select("consumption_reports.*","users.name as user_name", "users.avatar as user_avatar")
        ->limit(5)
        ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.table.consumption-reports-table');
    }
}
