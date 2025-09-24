<?php

namespace App\Livewire;

use App\Models\AgendaReports;
use App\Models\AuditReports;
use App\Models\ConsumptionReport;
use App\Models\MaintenanceReports;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    #[Title('Dashboard')]
    public function render()
    {
        // Aggregate counts across modules that require approvals
        $total = AuditReports::count()
               + MaintenanceReports::count()
               + AgendaReports::count()
               + ConsumptionReport::count();

        $pending = AuditReports::where('status', 'pending')->count()
                 + MaintenanceReports::where('status', 'pending')->count()
                 + AgendaReports::where('status', 'pending')->count()
                 + ConsumptionReport::where('status', 'pending')->count();

        // Note: Audit uses 'accepted' as approved; others use 'approved'
        $approved = AuditReports::where('status', 'accepted')->count()
                  + MaintenanceReports::where('status', 'approved')->count()
                  + AgendaReports::where('status', 'approved')->count()
                  + ConsumptionReport::where('status', 'approved')->count();

        $rejected = AuditReports::where('status', 'rejected')->count()
                  + MaintenanceReports::where('status', 'rejected')->count()
                  + AgendaReports::where('status', 'rejected')->count()
                  + ConsumptionReport::where('status', 'rejected')->count();

        return view('livewire.dashboard', [
            'title' => 'Dashboard',
            'stats' => [
                'total' => $total,
                'pending' => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
            ],
        ]);
    }
}
