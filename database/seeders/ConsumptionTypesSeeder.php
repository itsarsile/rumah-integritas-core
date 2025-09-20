<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsumptionTypesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $types = [
            ['code' => 'SNACK', 'name' => 'Snack'],
            ['code' => 'COFFEE', 'name' => 'Coffee Break'],
            ['code' => 'LUNCH', 'name' => 'Makan Siang'],
            ['code' => 'DINNER', 'name' => 'Makan Malam'],
            ['code' => 'DRINK', 'name' => 'Minuman'],
        ];

        foreach ($types as $t) {
            DB::table('consumption_types')->updateOrInsert(
                ['code' => $t['code']],
                [
                    'name' => $t['name'],
                    'updated_at' => now(),
                    'created_at' => DB::raw("COALESCE((SELECT created_at FROM consumption_types WHERE code = '{$t['code']}' LIMIT 1), now())"),
                    'deleted_at' => null,
                ]
            );
        }
    }
}

