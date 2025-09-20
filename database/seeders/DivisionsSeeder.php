<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionsSeeder extends Seeder
{
    public function run(): void
    {
        // Define divisions with optional parent_code to build hierarchy
        $divisions = [
            ['code' => 'UMUM',   'name' => 'Direktorat Umum'],
            ['code' => 'KEU',    'name' => 'Keuangan'],
            ['code' => 'TI',     'name' => 'Teknologi Informasi'],
            ['code' => 'OPS',    'name' => 'Operasional'],
            ['code' => 'SDM',    'name' => 'Sumber Daya Manusia'],
            ['code' => 'HUM',    'name' => 'Humas'],
            // Children under OPS
            ['code' => 'OPS-SP', 'name' => 'Sarana & Prasarana', 'parent_code' => 'OPS'],
            ['code' => 'OPS-LOG','name' => 'Logistik',            'parent_code' => 'OPS'],
        ];

        // First upsert parents (those without parent_code)
        foreach ($divisions as $d) {
            if (!isset($d['parent_code'])) {
                DB::table('divisions')->updateOrInsert(
                    ['code' => $d['code']],
                    [
                        'name' => $d['name'],
                        'parent_div_id' => null,
                        'deleted_at' => null,
                        'updated_at' => now(),
                        'created_at' => DB::raw("COALESCE((SELECT created_at FROM divisions WHERE code = '{$d['code']}' LIMIT 1), now())"),
                    ]
                );
            }
        }

        // Then upsert children with resolved parent ids
        foreach ($divisions as $d) {
            if (isset($d['parent_code'])) {
                $parentId = DB::table('divisions')->where('code', $d['parent_code'])->value('id');
                DB::table('divisions')->updateOrInsert(
                    ['code' => $d['code']],
                    [
                        'name' => $d['name'],
                        'parent_div_id' => $parentId,
                        'deleted_at' => null,
                        'updated_at' => now(),
                        'created_at' => DB::raw("COALESCE((SELECT created_at FROM divisions WHERE code = '{$d['code']}' LIMIT 1), now())"),
                    ]
                );
            }
        }
    }
}

