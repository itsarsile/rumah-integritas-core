<?php

namespace App\Http\Controllers;

use App\Models\CalendarMark;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class CalendarController extends Controller
{
    public function events(Request $request)
    {
        $user = $request->user();
        if (!$user) abort(401);

        $start = Carbon::parse($request->query('start', now()->startOfMonth()))->toDateString();
        $end = Carbon::parse($request->query('end', now()->endOfMonth()))->toDateString();
        $module = $request->query('module', 'all');
        $divisionId = (int) ($request->query('division_id') ?: 0);

        if (!$divisionId) {
            $divisionId = (int) ($user->division_id ?: 0);
        } else {
            // Only admin/manager can view other divisions
            if (!$user->hasRole('admin') && !$user->hasRole('manager') && $divisionId !== (int)$user->division_id) {
                abort(403);
            }
        }

        if (!$divisionId) {
            return response()->json([]);
        }

        $query = CalendarMark::query()
            ->where('division_id', $divisionId)
            ->whereBetween('date', [$start, $end]);
        if ($module !== 'all') {
            $query->where('module', $module);
        }
        $marks = $query->get();

        $colorMap = [
            'primary' => '#3b82f6',
            'secondary' => '#a855f7',
            'accent' => '#22c55e',
            'info' => '#38bdf8',
            'success' => '#16a34a',
            'warning' => '#f59e0b',
            'error' => '#ef4444',
        ];

        $moduleColor = [
            'consumption' => '#3b82f6',
            'maintenance' => '#f59e0b',
            'agenda' => '#16a34a',
        ];

        $events = $marks->map(function ($m) use ($colorMap, $moduleColor) {
            $color = $m->color && isset($colorMap[$m->color]) ? $colorMap[$m->color] : ($moduleColor[$m->module] ?? '#64748b');
            $event = [
                'id' => (string) $m->id,
                'title' => $m->label ?: ucfirst($m->module),
                'start' => $m->date->toDateString(),
                'allDay' => true,
                'color' => $color,
            ];

            // If linked entity exists, add URL to the proper show route
            if ($m->entity_id) {
                try {
                    switch ($m->module) {
                        case 'consumption':
                            $event['url'] = route('dashboard.consumption.show', $m->entity_id);
                            break;
                        case 'maintenance':
                            $event['url'] = route('dashboard.maintenance.show', $m->entity_id);
                            break;
                        case 'agenda':
                            $event['url'] = route('dashboard.agenda.show', $m->entity_id);
                            break;
                    }
                } catch (\Throwable $e) {
                    // silently ignore route generation errors
                }
            }

            return $event;
        })->values();

        return response()->json($events);
    }

    public function toggle(Request $request)
    {
        $user = $request->user();
        if (!$user) abort(401);

        if (!$user->hasRole('admin') && !$user->hasRole('manager')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'module' => 'required|in:consumption,maintenance,agenda',
            'date' => 'required|date',
            'division_id' => 'nullable|exists:divisions,id',
            'label' => 'nullable|string|max:100',
            'color' => 'nullable|in:primary,secondary,accent,info,success,warning,error',
            'entity_id' => 'nullable|integer',
            'entity_type' => 'nullable|string|max:100',
        ]);

        $divisionId = (int) ($validated['division_id'] ?? $user->division_id);
        if (!$divisionId) {
            return response()->json(['message' => 'No division set'], 422);
        }

        // Non-admin/manager cannot toggle other division
        if (!$user->hasRole('admin') && !$user->hasRole('manager') && $divisionId !== (int)$user->division_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $existing = CalendarMark::where([
            'module' => $validated['module'],
            'division_id' => $divisionId,
            'date' => $validated['date'],
        ])->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed']);
        }

        CalendarMark::create([
            'module' => $validated['module'],
            'division_id' => $divisionId,
            'date' => $validated['date'],
            'label' => $validated['label'] ?? null,
            'color' => $validated['color'] ?? null,
            'created_by' => $user->id,
            'entity_id' => $validated['entity_id'] ?? null,
            'entity_type' => $validated['entity_type'] ?? null,
        ]);

        return response()->json(['status' => 'added']);
    }
}
