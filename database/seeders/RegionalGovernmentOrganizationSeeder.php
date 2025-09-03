<?php

namespace Database\Seeders;

use App\Models\RegionalGovernmentOrganization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionalGovernmentOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RegionalGovernmentOrganization::create([
            'name' => 'Dinas Pendidikan dan Kebudayaan',
            'code' => 'DISDIKBUD-001',
            'address' => 'Jl. Pendidikan No. 10',
            'region_id' => 1, 
        ]);

        RegionalGovernmentOrganization::create([
            'name' => 'Dinas Kesehatan',
            'code' => 'DINKES-002',
            'address' => 'Jl. Kesehatan No. 5',
            'region_id' => 1, 
        ]);

        RegionalGovernmentOrganization::create([
            'name' => 'Dinas Perhubungan',
            'code' => 'DISHUB-003',
            'address' => 'Jl. Perhubungan No. 2',
            'region_id' => 2, 
        ]);

        RegionalGovernmentOrganization::create([
            'name' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
            'code' => 'DPU-004',
            'address' => 'Jl. P.U No. 8',
            'region_id' => 2, 
        ]);

        echo "Seeder RegionalGovernmentOrganization berhasil dijalankan." . PHP_EOL;
    }

}
