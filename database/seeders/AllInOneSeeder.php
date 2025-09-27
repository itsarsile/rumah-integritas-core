<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AllInOneSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Roles/permissions and baseline users
        $this->call(UserSeeder::class);

        // 2) Menus and role access bindings
        $this->call([
            MenuSeeder::class,
            AdminMenuSeeder::class,
        ]);

        // 3) Master/reference tables
        $this->call([
            AssetTypesSeeder::class,
            ConsumptionTypesSeeder::class,
            DivisionsSeeder::class,
        ]);

        // 4) Regions and organizations (depends on regions)
        $this->call([
            RegionsSeeder::class,
            RegionalGovernmentOrganizationSeeder::class,
        ]);

        // 5) Login slides/content
        $this->call(LoginSlideSeeder::class);
    }
}

