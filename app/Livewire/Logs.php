<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Logs extends Component
{
    use WithPagination;

    public $month = '';
    public $year = '';
    public $search = '';

    public function updating($name, $value)
    {
        if (in_array($name, ['month', 'year', 'search'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = ActivityLog::query()
            ->with('user')
            ->latest('created_at');

        if ($this->month !== '') {
            $query->whereMonth('created_at', $this->month);
        }
        if ($this->year !== '') {
            $query->whereYear('created_at', $this->year);
        }
        if ($this->search !== '') {
            $s = '%' . $this->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', $s)
                  ->orWhere('module', 'like', $s)
                  ->orWhere('action', 'like', $s);
            });
        }

        $logs = $query->paginate(20);

        // Group by date (Y-m-d)
        $grouped = $logs->getCollection()->groupBy(fn($l) => $l->created_at->toDateString());

        return view('livewire.logs', [
            'logs' => $logs,
            'grouped' => $grouped,
            'years' => $this->yearsOptions(),
        ]);
    }

    private function yearsOptions(): array
    {
        $minYear = (int) (ActivityLog::min(DB::raw('EXTRACT(YEAR from created_at)')) ?: now()->year);
        $years = [];
        for ($y = now()->year; $y >= $minYear; $y--) {
            $years[] = $y;
        }
        return $years;
    }
}

