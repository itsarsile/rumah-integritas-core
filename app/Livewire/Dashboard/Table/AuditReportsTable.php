<?php

namespace App\Livewire\Dashboard\Table;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AuditReportsTable extends Component
{
    public $data;


    public function getData()
    {
        $this->data = DB::table('audit_reports')
            ->join('users', 'audit_reports.created_by', '=', 'users.id')
            ->select('audit_reports.*', 'users.name as user_name', 'users.avatar as user_avatar')
            ->limit(5)
            ->get();
    } 

    public function mount()
    {
        $this->getData();
    }

    public function render()
    {
        return view('livewire.dashboard.table.audit-reports-table', [
            'data' => $this->data,
        ]);
    }
}
