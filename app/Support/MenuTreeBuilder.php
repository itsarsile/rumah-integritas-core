<?php

namespace App\Support;

use App\Models\Menu;
use Illuminate\Support\Collection;

class MenuTreeBuilder
{
    /**
     * Build a nested array of active menus keyed by hierarchy.
     */
    public static function activeTree(): array
    {
        $menus = Menu::query()
            ->where('is_active', true)
            ->orderBy('parent_id')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $grouped = $menus->groupBy('parent_id');

        return static::buildBranch($grouped, null);
    }

    protected static function buildBranch(Collection $grouped, ?int $parentId): array
    {
        return $grouped->get($parentId, collect())
            ->map(fn($menu) => [
                'id' => $menu->id,
                'name' => $menu->name,
                'icon' => $menu->icon,
                'route' => $menu->route,
                'children' => static::buildBranch($grouped, $menu->id),
            ])
            ->values()
            ->toArray();
    }
}
