<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetTypesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $assets = [
            ['code' => 'PC', 'name' => 'Komputer/PC', 'description' => 'Unit komputer, desktop, workstation'],
            ['code' => 'LPT', 'name' => 'Laptop', 'description' => 'Perangkat laptop/notebook'],
            ['code' => 'PRN', 'name' => 'Printer', 'description' => 'Printer, MFP, scanner'],
            ['code' => 'NET', 'name' => 'Jaringan', 'description' => 'Switch, router, access point'],
            ['code' => 'AC', 'name' => 'AC', 'description' => 'Pendingin ruangan'],
            ['code' => 'PROJ', 'name' => 'Proyektor', 'description' => 'Proyektor & layar'],
            ['code' => 'VEH', 'name' => 'Kendaraan', 'description' => 'Mobil/motor operasional'],
            ['code' => 'FURN', 'name' => 'Perabot', 'description' => 'Meja, kursi, lemari, dsb.'],
        ];

        foreach ($assets as $a) {
            DB::table('asset_types')->updateOrInsert(
                ['code' => $a['code']],
                [
                    'name' => $a['name'],
                    'description' => $a['description'],
                    'updated_at' => now(),
                    'created_at' => DB::raw("COALESCE((SELECT created_at FROM asset_types WHERE code = '{$a['code']}' LIMIT 1), now())"),
                    'deleted_at' => null,
                ]
            );
        }
    }
}

