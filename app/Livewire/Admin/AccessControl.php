<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('RBAC - Access Control')]
class AccessControl extends Component
{
    public array $modules = [
        'audit' => ['view', 'create', 'update', 'approve', 'reject'],
        'maintenance' => ['view', 'create', 'update', 'approve', 'reject'],
        'consumption' => ['view', 'create', 'update', 'approve', 'reject'],
        'agenda' => ['view', 'create', 'update', 'approve', 'reject'],
    ];

    public $roles; // collection
    public ?int $selectedRoleId = null;

    // bound checkboxes: ["action module" => true]
    public array $grants = [];

    public function mount()
    {
        if (!auth()->user()?->hasRole('admin')) {
            abort(403);
        }

        $this->roles = Role::where('guard_name', 'web')->get();
        $this->selectedRoleId = $this->roles->first()?->id;
        $this->loadRoleGrants();
    }

    public function updatedSelectedRoleId()
    {
        $this->loadRoleGrants();
    }

    private function allManagedPermissionNames(): array
    {
        $names = [];
        foreach ($this->modules as $module => $actions) {
            foreach ($actions as $action) {
                $names[] = "$action $module";
            }
        }
        return $names;
    }

    private function loadRoleGrants(): void
    {
        $this->grants = [];
        if (!$this->selectedRoleId) return;
        $role = Role::find($this->selectedRoleId);
        if (!$role) return;

        $current = $role->permissions->pluck('name')->all();
        foreach ($this->modules as $module => $actions) {
            foreach ($actions as $action) {
                $perm = "$action $module";
                $this->grants[$perm] = in_array($perm, $current);
            }
        }
    }

    public function save()
    {
        $role = Role::findOrFail($this->selectedRoleId);

        // Ensure permissions exist
        foreach ($this->modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::findOrCreate("$action $module", 'web');
            }
        }

        $selected = collect($this->grants)
            ->filter(fn($v, $k) => $v === true)
            ->keys()
            ->values()
            ->all();

        // Keep existing permissions not managed here
        $managed = $this->allManagedPermissionNames();
        $keep = $role->permissions
            ->pluck('name')
            ->reject(fn($name) => in_array($name, $managed))
            ->values()
            ->all();

        $final = array_values(array_unique(array_merge($keep, $selected)));
        $role->syncPermissions($final);

        session()->flash('success', 'Permissions updated for role: ' . $role->name);
    }

    public function render()
    {
        return view('livewire.admin.access-control');
    }
}

