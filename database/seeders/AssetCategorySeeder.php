<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use Illuminate\Database\Seeder;

class AssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Laptop / PC',     'prefix' => 'IT',  'description' => 'Laptop, desktop, PC all-in-one'],
            ['name' => 'Monitor',          'prefix' => 'MON', 'description' => 'Monitor dan display'],
            ['name' => 'Printer',          'prefix' => 'PRN', 'description' => 'Printer, scanner, mesin fotokopi'],
            ['name' => 'Access Point',     'prefix' => 'AP',  'description' => 'Access point, router, switch'],
            ['name' => 'UPS',              'prefix' => 'UPS', 'description' => 'Uninterruptible Power Supply'],
            ['name' => 'Telepon / VOIP',   'prefix' => 'TEL', 'description' => 'Telepon meja, VOIP, headset'],
            ['name' => 'Kamera / CCTV',    'prefix' => 'CAM', 'description' => 'Kamera keamanan dan CCTV'],
            ['name' => 'Proyektor',        'prefix' => 'PRJ', 'description' => 'Proyektor dan layar presentasi'],
            ['name' => 'Perangkat Lainnya','prefix' => 'OTH', 'description' => 'Perangkat IT lainnya'],
        ];

        foreach ($categories as $cat) {
            AssetCategory::firstOrCreate(
                ['prefix' => $cat['prefix']],
                ['name' => $cat['name'], 'description' => $cat['description'], 'is_active' => true]
            );
        }
    }
}
