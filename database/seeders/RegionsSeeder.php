<?php

namespace Database\Seeders;

use App\Models\Regions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Regions::firstOrCreate([
            'name' => 'Jakarta',
            'code' => 'JKT',
        ]);

        Regions::firstOrCreate([
            'name' => 'Bandung',
            'code' => 'BND',
        ]);
    }
}
