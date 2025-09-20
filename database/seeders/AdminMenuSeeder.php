<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminMenuSeeder extends Seeder
{
    public function run(): void
    {
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        if (!$adminRoleId) return;

        $trackingId = $this->upsertMenu('Tracking Permintaan', null, 'dashboard.tracking', 5, 'feathericon-trending-up');
        $approvalId = $this->upsertMenu('Manajemen Persetujuan', null, 'dashboard.approvals', 6, 'feathericon-check-circle');
        $userMgmtId = $this->upsertMenu('Manajemen Pengguna', null, null, 7, 'feathericon-user');
        $this->upsertMenu('User', $userMgmtId, 'dashboard.user-management', 1);
        $this->upsertMenu('Role', $userMgmtId, 'dashboard.role-management', 2);
        $logoutId = $this->upsertMenu('Logout', null, 'logout', 99, 'feathericon-log-out');

        foreach ([$trackingId, $approvalId, $userMgmtId, $logoutId] as $menuId) {
            $this->attachRole($menuId, $adminRoleId);
        }
        // Attach children to admin
        $children = DB::table('menus')->where('parent_id', $userMgmtId)->pluck('id');
        foreach ($children as $cid) {
            $this->attachRole($cid, $adminRoleId);
        }
    }

    private function upsertMenu(string $name, ?int $parentId, ?string $route, int $order, ?string $icon = null): int
    {
        $exists = DB::table('menus')->where('name', $name)->first();
        if ($exists) {
            DB::table('menus')->where('id', $exists->id)->update([
                'icon' => $icon,
                'route' => $route,
                'parent_id' => $parentId,
                'order' => $order,
                'is_active' => true,
                'updated_at' => now(),
            ]);
            return (int) $exists->id;
        }

        return (int) DB::table('menus')->insertGetId([
            'name' => $name,
            'icon' => $icon,
            'route' => $route,
            'parent_id' => $parentId,
            'order' => $order,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function attachRole(int $menuId, int $roleId): void
    {
        $exists = DB::table('menu_roles')
            ->where('menu_id', $menuId)
            ->where('role_id', $roleId)
            ->exists();
        if (!$exists) {
            DB::table('menu_roles')->insert([
                'menu_id' => $menuId,
                'role_id' => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
