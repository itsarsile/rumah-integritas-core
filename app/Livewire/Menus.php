<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class Menus extends Component
{
    public $menus;

    public function mount(): void
    {
        $user = auth()->user();

        if (!$user) {
            $this->menus = ['root' => collect(), 'children' => collect()];
            return;
        }

        $roleIds = $user->roles->pluck('id');

        $accessibleMenuIds = $this->collectAccessibleMenuIds($user->id, $roleIds);

        if ($accessibleMenuIds->isEmpty()) {
            $this->menus = ['root' => collect(), 'children' => collect()];
            return;
        }

        $menuIdsWithAncestors = $this->includeAncestorMenus($accessibleMenuIds);

        $menus = DB::table('menus')
            ->whereIn('menus.id', $menuIdsWithAncestors)
            ->where('menus.is_active', true)
            ->orderBy('menus.order')
            ->orderBy('menus.name')
            ->get()
            ->filter(fn($menu) => !$menu->route || Route::has($menu->route));

        $grouped = $menus->groupBy('parent_id');

        $this->menus = [
            'root' => $grouped->get(null, collect()),
            'children' => $grouped->except(null),
        ];
    }

    private function collectAccessibleMenuIds(int $userId, Collection $roleIds): Collection
    {
        $roleMenuIds = $roleIds->isNotEmpty()
            ? DB::table('menu_roles')->whereIn('role_id', $roleIds)->pluck('menu_id')
            : collect();

        $userMenuIds = DB::table('menu_user')
            ->where('user_id', $userId)
            ->pluck('menu_id');

        return $roleMenuIds->merge($userMenuIds)->unique()->values();
    }

    private function includeAncestorMenus(Collection $menuIds): Collection
    {
        $allIds = $menuIds->values();
        $cursor = $menuIds->values();

        while ($cursor->isNotEmpty()) {
            $parentIds = DB::table('menus')
                ->whereIn('id', $cursor)
                ->pluck('parent_id')
                ->filter();

            $newParents = $parentIds
                ->filter(fn($parentId) => !$allIds->contains($parentId))
                ->values();

            if ($newParents->isEmpty()) {
                break;
            }

            $allIds = $allIds->merge($newParents)->unique()->values();
            $cursor = $newParents;
        }

        return $allIds;
    }

    public function render()
    {
        return view('livewire.menus');
    }
}
