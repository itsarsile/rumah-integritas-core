<?php

namespace App\Livewire\Admin;

use App\Models\AgendaReports;
use App\Models\AuditReports;
use App\Models\ConsumptionReport;
use App\Models\MaintenanceReports;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Approvals extends Component
{
    public $tab = 'all';

    public function mount()
    {
        if (!auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function approve(string $type, int $id)
    {
        [$model, $statusApproved] = $this->resolveModel($type);
        if (!$model) {
            session()->flash('error', 'Tipe tidak dikenal.');
            return;
        }

        $record = $model::find($id);
        if (!$record) {
            session()->flash('error', 'Data tidak ditemukan.');
            return;
        }

        $record->status = $statusApproved; // accepted/approved depending on table
        // reviewed_at / reviewed_by when available
        if (property_exists($record, 'reviewed_at') || isset($record->reviewed_at)) {
            $record->reviewed_at = now();
        }
        if (property_exists($record, 'reviewed_by') || isset($record->reviewed_by)) {
            $record->reviewed_by = Auth::id();
        }
        $record->save();

        // activity log
        ActivityLog::create([
            'user_id' => Auth::id(),
            'module' => $type,
            'action' => 'approved',
            'entity_type' => $model,
            'entity_id' => $record->id,
            'description' => 'Menyetujui ' . $type . ' #' . ($record->request_code ?? $record->report_code ?? $record->id),
            'metadata' => [ 'status' => $record->status ],
        ]);

        session()->flash('success', 'Berhasil menyetujui laporan.');
    }

    public function reject(string $type, int $id)
    {
        [$model, ] = $this->resolveModel($type);
        if (!$model) {
            session()->flash('error', 'Tipe tidak dikenal.');
            return;
        }

        $record = $model::find($id);
        if (!$record) {
            session()->flash('error', 'Data tidak ditemukan.');
            return;
        }

        $record->status = 'rejected';
        if (property_exists($record, 'reviewed_at') || isset($record->reviewed_at)) {
            $record->reviewed_at = now();
        }
        if (property_exists($record, 'reviewed_by') || isset($record->reviewed_by)) {
            $record->reviewed_by = Auth::id();
        }
        $record->save();

        // activity log
        ActivityLog::create([
            'user_id' => Auth::id(),
            'module' => $type,
            'action' => 'rejected',
            'entity_type' => $model,
            'entity_id' => $record->id,
            'description' => 'Menolak ' . $type . ' #' . ($record->request_code ?? $record->report_code ?? $record->id),
            'metadata' => [ 'status' => $record->status ],
        ]);

        session()->flash('success', 'Laporan ditolak.');
    }

    private function resolveModel(string $type): array
    {
        // Return [modelClass, approvedStatus]
        return match ($type) {
            'audit' => [AuditReports::class, 'accepted'],
            'maintenance' => [MaintenanceReports::class, 'approved'],
            'agenda' => [AgendaReports::class, 'approved'],
            'consumption' => [ConsumptionReport::class, 'approved'],
            default => [null, null],
        };
    }

    public function render()
    {
        $pendingAudit = AuditReports::where('status', 'pending')->latest()->limit(20)->get();
        $pendingMaintenance = MaintenanceReports::where('status', 'pending')->latest()->limit(20)->get();
        $pendingAgenda = AgendaReports::where('status', 'pending')->latest()->limit(20)->get();
        $pendingConsumption = ConsumptionReport::where('status', 'pending')->latest()->limit(20)->get();

        return view('livewire.admin.approvals', [
            'pendingAudit' => $pendingAudit,
            'pendingMaintenance' => $pendingMaintenance,
            'pendingAgenda' => $pendingAgenda,
            'pendingConsumption' => $pendingConsumption,
        ]);
    }
}
