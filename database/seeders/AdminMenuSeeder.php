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
        $appearanceId = $this->upsertMenu('Pengaturan Tampilan', null, null, 8, 'feathericon-image');
        $masterDataId = $this->upsertMenu('Master Data', null, null, 9, 'feathericon-database');
        $this->upsertMenu('User', $userMgmtId, 'dashboard.user-management', 1);
        $this->upsertMenu('Role', $userMgmtId, 'dashboard.role-management', 2);
        $this->upsertMenu('RBAC', $userMgmtId, 'dashboard.access-control', 3);
        $this->upsertMenu('Slider Login', $appearanceId, 'dashboard.login-slider', 1);
        // Master Data children
        $this->upsertMenu('Regions', $masterDataId, 'dashboard.master-data.regions', 1, 'feathericon-map');
        $this->upsertMenu('Divisions', $masterDataId, 'dashboard.master-data.divisions', 2, 'feathericon-grid');
        $this->upsertMenu('Consumption Types', $masterDataId, 'dashboard.master-data.consumption-types', 3, 'feathericon-list');
        $this->upsertMenu('Asset Types', $masterDataId, 'dashboard.master-data.asset-types', 4, 'feathericon-archive');
        $this->upsertMenu('Organizations', $masterDataId, 'dashboard.master-data.organizations', 5, 'feathericon-users');

        $logoutId = $this->upsertMenu('Logout', null, 'logout', 99, 'feathericon-log-out');
        $settingsId = $this->upsertMenu('Setting', null, 'dashboard.settings', 90, 'feathericon-settings');

        foreach ([$trackingId, $approvalId, $userMgmtId, $appearanceId, $masterDataId, $settingsId, $logoutId] as $menuId) {
            $this->attachRole($menuId, $adminRoleId);
        }
        // Attach children to admin
        $children = DB::table('menus')->where('parent_id', $userMgmtId)->pluck('id');
        foreach ($children as $cid) {
            $this->attachRole($cid, $adminRoleId);
        }
        $appearanceChildren = DB::table('menus')->where('parent_id', $appearanceId)->pluck('id');
        foreach ($appearanceChildren as $cid) {
            $this->attachRole($cid, $adminRoleId);
        }

        $masterDataChildren = DB::table('menus')->where('parent_id', $masterDataId)->pluck('id');
        foreach ($masterDataChildren as $cid) {
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
