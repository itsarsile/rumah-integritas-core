<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class RequestTracking extends Component
{
    use WithPagination;

    public $status = '';
    public $type = '';
    public $search = '';

    public function mount()
    {
        if (!auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
    }

    public function updating($name, $value)
    {
        if (in_array($name, ['status', 'type', 'search'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        // Build base queries with aligned columns for union
        $consumption = DB::table('consumption_reports as t')
            ->join('users as u', 't.created_by', '=', 'u.id')
            ->select([
                DB::raw("t.id as source_id"),
                DB::raw("'consumption' as source"),
                DB::raw("'Konsumsi' as type"),
                't.request_code',
                DB::raw('t.request_title as activity'),
                DB::raw('t.event_request_date as date'),
                'u.name as requester',
                't.status',
                't.created_at',
            ]);

        $maintenance = DB::table('maintenance_reports as t')
            ->join('users as u', 't.created_by', '=', 'u.id')
            ->select([
                DB::raw("t.id as source_id"),
                DB::raw("'maintenance' as source"),
                DB::raw("'Pemeliharaan' as type"),
                't.request_code',
                DB::raw('t.description as activity'),
                DB::raw('t.created_at as date'),
                'u.name as requester',
                't.status',
                't.created_at',
            ]);

        $agenda = DB::table('agenda_reports as t')
            ->join('users as u', 't.created_by', '=', 'u.id')
            ->select([
                DB::raw("t.id as source_id"),
                DB::raw("'agenda' as source"),
                DB::raw("'Agenda' as type"),
                't.request_code',
                DB::raw('t.title as activity'),
                DB::raw('t.date as date'),
                'u.name as requester',
                't.status',
                't.created_at',
            ]);

        // Union the queries
        $union = $consumption->unionAll($maintenance)->unionAll($agenda);

        // Wrap union to apply filters and ordering
        $requests = DB::query()->fromSub($union, 'r');

        if ($this->status !== '') {
            $requests->where(function ($q) {
                // Treat 'approved' and 'accepted' as same for filtering when user picks approved/accepted
                if ($this->status === 'approved') {
                    $q->whereIn('status', ['approved', 'accepted']);
                } elseif ($this->status === 'accepted') {
                    $q->whereIn('status', ['approved', 'accepted']);
                } else {
                    $q->where('status', $this->status);
                }
            });
        }

        if ($this->type !== '') {
            $requests->where('source', $this->type);
        }

        if ($this->search !== '') {
            $s = "%" . strtolower($this->search) . "%";
            $requests->where(function ($q) use ($s) {
                $q->whereRaw('LOWER(activity) like ?', [$s])
                  ->orWhereRaw('LOWER(request_code) like ?', [$s])
                  ->orWhereRaw('LOWER(requester) like ?', [$s]);
            });
        }

        $requests = $requests->orderByDesc('created_at')->paginate(10);

        return view('livewire.admin.request-tracking', [
            'requests' => $requests,
        ]);
    }
}

