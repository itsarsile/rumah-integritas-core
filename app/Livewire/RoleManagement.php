<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleManagement extends Component
{
    use WithPagination;

    // Search and Filters
    public $search = '';
    public $guardFilter = '';
    public $perPage = 10;

    // Sorting
    public $sortField = 'name';
    public $sortDirection = 'asc';

    // Selection
    public $selectedRoles = [];
    public $selectAll = false;

    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $roleToDelete = null;

    // Form data
    public $name = '';
    public $guard_name = 'web';
    public $permissions = [];
    public $editingRoleId = null;


    protected $paginationTheme = 'bootstrap';

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openDeleteModal()
    {
        $this->resetForm();
        $this->showDeleteModal = true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($this->editingRoleId),
            ],
            'guard_name' => 'required|string|in:web,api', // Adjust guards as needed
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    public function mount()
    {
        // Initialize component
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedGuardFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRoles = $this->getFilteredRoles()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedRoles = [];
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->guardFilter = '';
        $this->resetPage();
    }

    public function clearSelection()
    {
        $this->selectedRoles = [];
        $this->selectAll = false;
    }

    private function getFilteredRoles()
    {
        $query = Role::query();

        // Search
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Guard filter
        if ($this->guardFilter) {
            $query->where('guard_name', $this->guardFilter);
        }

        // Eager load permissions
        $query->with('permissions');

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query;
    }

    public function getRoles()
    {
        return $this->getFilteredRoles()->paginate($this->perPage);
    }

    // Statistics methods
    #[Computed]
    public function totalRoles()
    {
        $query = Role::query();

        return $query->count();
    }

    #[Computed]
    public function totalPermissions()
    {
        return Permission::count();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal($roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->editingRoleId = $role->id;
        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->permissions = $role->permissions->pluck('id')->toArray();
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->guard_name = 'web';
        $this->permissions = [];
        $this->editingRoleId = null;
        $this->resetValidation();
    }

    // CRUD Operations
    public function createRole()
    {
        $this->validate();

        try {
            $role = Role::create([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);

            if (!empty($this->permissions)) {
                $permissions = Permission::whereIn('id', $this->permissions)->get();
                $role->syncPermissions($permissions);
            }

            $this->closeCreateModal();
            session()->flash('message', 'Role created successfully.');
            $this->dispatch('role-created');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while creating the role.');
        }
    }

    public function editRole($roleId)
    {
        $this->openEditModal($roleId);
    }

    public function updateRole()
    {
        $this->validate();

        $role = Role::findOrFail($this->editingRoleId);

        $role->update([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        $permissions = Permission::whereIn('id', $this->permissions)->get();
        $role->syncPermissions($permissions);

        $this->closeEditModal();
        session()->flash('message', 'Role updated successfully.');
        $this->dispatch('role-updated');
    }

    public function viewRole($roleId)
    {
        // Dispatch an event to show role details modal or redirect
        $this->dispatch('show-role-details', roleId: $roleId);
    }

    public function confirmDelete($roleId)
    {
        $this->roleToDelete = $roleId;
        $this->showDeleteModal = true;
    }

    public function deleteRole($roleId = null)
    {
        $roleId = $roleId ?: $this->roleToDelete;

        $role = Role::findOrFail($roleId);
        // Optional: Prevent deleting super admin or built-in roles
        if (in_array($role->name, ['super-admin'])) {
            session()->flash('error', 'Cannot delete built-in role.');
            return;
        }

        Role::where('id', $roleId)->delete();

        $this->showDeleteModal = false;
        $this->roleToDelete = null;

        // Remove from selection if selected
        $this->selectedRoles = array_filter($this->selectedRoles, fn($id) => $id != $roleId);

        session()->flash('message', 'Role deleted successfully.');
        $this->dispatch('role-deleted');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->roleToDelete = null;
    }

    // Bulk operations
    public function bulkDelete()
    {
        if (empty($this->selectedRoles)) {
            return;
        }

        // Optional: Filter out protected roles
        $rolesToDelete = Role::whereIn('id', $this->selectedRoles)
            ->whereNotIn('name', ['super-admin'])
            ->get();

        $count = $rolesToDelete->count();
        $rolesToDelete->each->delete();

        $this->clearSelection();
        session()->flash('message', "{$count} role(s) deleted successfully.");
    }

    #[Title('Manajemen Role')]
    public function render()
    {
        $roles = $this->getRoles();

        // Update selectAll state based on current page selection
        $allCurrentPageSelected = $roles->count() > 0 &&
            collect($roles->items())->every(fn($role) => in_array((string) $role->id, $this->selectedRoles));

        $this->selectAll = $allCurrentPageSelected;

        return view('livewire.role-management', [
            'roles' => $roles,
            'totalRoles' => $this->totalRoles,
            'totalPermissions' => $this->totalPermissions,
        ]);
    }
}