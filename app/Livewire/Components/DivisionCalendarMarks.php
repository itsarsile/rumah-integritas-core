<?php

namespace App\Livewire\Components;

use App\Models\CalendarMark;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;

class DivisionCalendarMarks extends Component
{
    public string $module = '';
    public string $month; // format: Y-m
    public ?int $divisionId = null;

    public array $marks = [];
    public ?string $note = null;
    public ?string $color = null;

    public function mount(string $module): void
    {
        $this->module = $module;
        $this->month = now()->format('Y-m');
        $this->divisionId = auth()->user()?->division_id;
        $this->loadMarks();
    }

    public function loadMarks(): void
    {
        $this->marks = [];
        if (!$this->divisionId) return;

        $start = Carbon::createFromFormat('Y-m', $this->month)->startOfMonth()->toDateString();
        $end = Carbon::createFromFormat('Y-m', $this->month)->endOfMonth()->toDateString();

        $this->marks = CalendarMark::query()
            ->where('module', $this->module)
            ->where('division_id', $this->divisionId)
            ->whereBetween('date', [$start, $end])
            ->get()
            ->keyBy(fn($m) => $m->date->format('Y-m-d'))
            ->toArray();
    }

    public function previousMonth(): void
    {
        $this->month = Carbon::createFromFormat('Y-m', $this->month)->subMonth()->format('Y-m');
        $this->loadMarks();
    }

    public function nextMonth(): void
    {
        $this->month = Carbon::createFromFormat('Y-m', $this->month)->addMonth()->format('Y-m');
        $this->loadMarks();
    }

    public function toggleMark(string $date): void
    {
        if (!$this->divisionId) return;

        $existing = CalendarMark::where([
            'module' => $this->module,
            'division_id' => $this->divisionId,
            'date' => $date,
        ])->first();

        if ($existing) {
            $existing->delete();
        } else {
            CalendarMark::create([
                'module' => $this->module,
                'division_id' => $this->divisionId,
                'date' => $date,
                'label' => $this->note,
                'color' => $this->color,
                'created_by' => auth()->id(),
            ]);
        }

        $this->loadMarks();
    }

    public function daysInGrid(): array
    {
        // Build a simple calendar grid (Mon-Sun)
        $first = Carbon::createFromFormat('Y-m', $this->month)->startOfMonth();
        $start = $first->copy()->startOfWeek(Carbon::MONDAY);
        $end = $first->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $days = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $days[] = [
                'date' => $cursor->toDateString(),
                'inMonth' => $cursor->isSameMonth($first),
                'isToday' => $cursor->isToday(),
                'mark' => $this->marks[$cursor->toDateString()] ?? null,
            ];
            $cursor->addDay();
        }
        return $days;
    }

    public function render()
    {
        return view('livewire.components.division-calendar-marks');
    }
}

