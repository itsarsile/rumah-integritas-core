<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Division;
use App\Support\MenuTreeBuilder;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use WithPagination;

    // Search and Filters
    public $search = '';
    public $statusFilter = '';
    public $roleFilter = '';
    public $perPage = 10;

    // Sorting
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Selection
    public $selectedUsers = [];
    public $selectAll = false;

    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $userToDelete = null;
    public $showMenuConfigModal = false;
    public $menuConfigUserId = null;

    // Form data
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $roles;
    public $status = 'active';
    public $editingUserId = null;
    public $selectedRoleId = null;
    public $userMenuSelections = [];
    public $menuTree = [];
    public $division_id = null;
    public $divisions;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'openCreateModal' => 'openCreateModal',
        'userDeleted' => '$refresh',
    ];

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->editingUserId),
            ],
            'selectedRoleId' => [
                'required',
                'exists:roles,id'
            ],
            'status' => 'required|in:active,inactive,pending,suspended',
            'division_id' => 'nullable|integer|exists:divisions,id',
        ];

        // Password rules for creating new user
        if (!$this->editingUserId) {
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['password_confirmation'] = 'required';
        } elseif ($this->password) {
            // Password rules for editing (only if password is provided)
            $rules['password'] = 'string|min:8|confirmed';
            $rules['password_confirmation'] = 'required_with:password';
        }

        return $rules;
    }

    public function mount()
    {
        // Initialize component
        $this->roles = Role::where('guard_name', 'web')->get();
        $this->menuTree = MenuTreeBuilder::activeTree();
        $this->divisions = Division::orderBy('name')->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
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
            $this->selectedUsers = $this->getFilteredUsers()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedUsers = [];
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
        $this->statusFilter = '';
        $this->roleFilter = '';
        $this->resetPage();
    }

    public function clearSelection()
    {
        $this->selectedUsers = [];
        $this->selectAll = false;
    }

    private function getFilteredUsers()
    {
        $query = User::query();

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Role filter
        if ($this->roleFilter) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        $query->with('roles');
        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query;
    }

    public function getUsers()
    {
        return $this->getFilteredUsers()->paginate($this->perPage);
    }

    // Statistics methods
    public function getTotalUsersProperty()
    {
        return User::count();
    }

    public function getActiveUsersProperty()
    {
        return User::where('status', 'active')->count();
    }

    public function getPendingUsersProperty()
    {
        return User::where('status', 'pending')->count();
    }

    public function getInactiveUsersProperty()
    {
        return User::whereIn('status', ['inactive', 'suspended'])->count();
    }

    // Modal methods
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal($userId)
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoleId = $user->roles()->pluck('id')->first();
        $this->status = $user->status ?? 'active';
        $this->division_id = $user->division_id;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showEditModal = true;
    }


    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function closeMenuConfigModal()
    {
        $this->showMenuConfigModal = false;
        $this->menuConfigUserId = null;
        $this->userMenuSelections = [];
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'user';
        $this->status = 'active';
        $this->editingUserId = null;
        $this->division_id = null;
        $this->resetValidation();
    }

    public function openMenuConfigModal(int $userId)
    {
        $user = User::with('menus')->findOrFail($userId);
        $this->menuConfigUserId = $user->id;
        $this->userMenuSelections = $user->menus->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->showMenuConfigModal = true;
    }

    public function saveUserMenus()
    {
        if (!$this->menuConfigUserId) {
            return;
        }

        $user = User::findOrFail($this->menuConfigUserId);
        $menuIds = collect($this->userMenuSelections)
            ->filter()
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->toArray();

        $user->menus()->sync($menuIds);

        $this->closeMenuConfigModal();

        session()->flash('message', 'Menu configuration updated successfully.');
        $this->dispatch('user-menu-updated');
    }

    // CRUD Operations
    public function createUser()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'status' => $this->status,
            'division_id' => $this->division_id ? (int) $this->division_id : null,
            'email_verified_at' => $this->status === 'active' ? now() : null,
        ];

        $user = User::create($userData);
        if ($this->selectedRoleId) {
            $role = Role::findById($this->selectedRoleId); 
            $user->assignRole($role);
        }


        $this->closeCreateModal();
        session()->flash('message', 'User created successfully.');
        $this->dispatch('user-created');
    }

    public function editUser($userId)
    {
        $this->openEditModal($userId);
    }

    public function updateUser()
    {
        $this->validate();

        $user = User::findOrFail($this->editingUserId);

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'division_id' => $this->division_id ? (int) $this->division_id : null,
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        $user->update($userData);

        if ($this->selectedRoleId) {
            $role = Role::findById($this->selectedRoleId);
            $user->syncRoles([$role]);
        }

        $this->closeEditModal();
        session()->flash('message', 'User updated successfully.');
        $this->dispatch('user-updated');
    }

    public function viewUser($userId)
    {
        // You can dispatch an event to show user details modal
        // or redirect to user detail page
        $this->dispatch('show-user-details', userId: $userId);
    }

    public function confirmDelete($userId)
    {
        $this->userToDelete = $userId;
        $this->showDeleteModal = true;
    }

    public function deleteUser($userId = null)
    {
        $userId = $userId ?: $this->userToDelete;

        $user = User::findOrFail($userId);

        // Prevent deleting current user
        if (auth()->id() === $user->id) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $user->delete();

        $this->showDeleteModal = false;
        $this->userToDelete = null;

        // Remove from selection if selected
        $this->selectedUsers = array_filter($this->selectedUsers, fn($id) => $id != $userId);

        session()->flash('message', 'User deleted successfully.');
        $this->dispatch('user-deleted');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
    }

    // Status management
    public function activateUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'status' => 'active',
            'email_verified_at' => $user->email_verified_at ?: now(),
        ]);

        session()->flash('message', 'User activated successfully.');
    }

    public function suspendUser($userId)
    {
        $user = User::findOrFail($userId);

        // Prevent suspending current user
        if (auth()->id() === $user->id) {
            session()->flash('error', 'You cannot suspend your own account.');
            return;
        }

        $user->update(['status' => 'suspended']);
        session()->flash('message', 'User suspended successfully.');
    }

    // Bulk operations
    public function bulkActivate()
    {
        if (empty($this->selectedUsers)) {
            return;
        }

        User::whereIn('id', $this->selectedUsers)
            ->update([
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

        $count = count($this->selectedUsers);
        $this->clearSelection();
        session()->flash('message', "{$count} user(s) activated successfully.");
    }

    public function bulkSuspend()
    {
        if (empty($this->selectedUsers)) {
            return;
        }

        // Remove current user from selection to prevent self-suspension
        $this->selectedUsers = array_filter($this->selectedUsers, fn($id) => $id != auth()->id());

        if (empty($this->selectedUsers)) {
            session()->flash('error', 'Cannot suspend your own account.');
            return;
        }

        User::whereIn('id', $this->selectedUsers)->update(['status' => 'suspended']);

        $count = count($this->selectedUsers);
        $this->clearSelection();
        session()->flash('message', "{$count} user(s) suspended successfully.");
    }

    public function bulkDelete()
    {
        if (empty($this->selectedUsers)) {
            return;
        }

        // Remove current user from selection to prevent self-deletion
        $this->selectedUsers = array_filter($this->selectedUsers, fn($id) => $id != auth()->id());

        if (empty($this->selectedUsers)) {
            session()->flash('error', 'Cannot delete your own account.');
            return;
        }

        $count = count($this->selectedUsers);
        User::whereIn('id', $this->selectedUsers)->delete();

        $this->clearSelection();
        session()->flash('message', "{$count} user(s) deleted successfully.");
    }

    #[Title('Manajemen Pengguna')]
    public function render()
    {
        $users = $this->getUsers();

        // Update selectAll state based on current page selection
        $allCurrentPageSelected = $users->count() > 0 &&
            collect($users->items())->every(fn($user) => in_array((string) $user->id, $this->selectedUsers));

        $this->selectAll = $allCurrentPageSelected;

        return view('livewire.user-management', [
            'users' => $users,
            'totalUsers' => $this->totalUsers,
            'activeUsers' => $this->activeUsers,
            'pendingUsers' => $this->pendingUsers,
            'inactiveUsers' => $this->inactiveUsers,
        ]);
    }
}
