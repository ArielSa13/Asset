<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Loan;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Default admin user
        User::firstOrCreate(
            ['email' => 'admin@assetms.com'],
            ['name' => 'Administrator', 'password' => Hash::make('password')]
        );

        // 2. Seed kategori IT
        $this->call(AssetCategorySeeder::class);

        // 3. Seed sample assets
        $catLaptop  = AssetCategory::where('prefix', 'IT')->first();
        $catMonitor = AssetCategory::where('prefix', 'MON')->first();
        $catPrinter = AssetCategory::where('prefix', 'PRN')->first();
        $catAP      = AssetCategory::where('prefix', 'AP')->first();

        $assets = [
            ['name'=>'MacBook Pro 14"',    'code'=>'IT-001',  'category_id'=>$catLaptop->id,  'brand'=>'Apple',   'model'=>'M2 Pro',       'serial_number'=>'SN-MBP-001', 'purchase_date'=>'2023-01-15', 'purchase_price'=>25000000, 'condition'=>'good', 'status'=>'available'],
            ['name'=>'Dell Monitor 27"',   'code'=>'MON-001', 'category_id'=>$catMonitor->id, 'brand'=>'Dell',    'model'=>'U2723DE',      'serial_number'=>'SN-DL-002',  'purchase_date'=>'2023-02-01', 'purchase_price'=>6500000,  'condition'=>'good', 'status'=>'in_use'],
            ['name'=>'HP LaserJet Printer','code'=>'PRN-001', 'category_id'=>$catPrinter->id, 'brand'=>'HP',      'model'=>'LaserJet M404','serial_number'=>'SN-HP-003',  'purchase_date'=>'2023-03-12', 'purchase_price'=>4200000,  'condition'=>'fair', 'status'=>'maintenance'],
            ['name'=>'TP-Link Access Point','code'=>'AP-001', 'category_id'=>$catAP->id,      'brand'=>'TP-Link', 'model'=>'EAP670',       'serial_number'=>'SN-TP-004',  'purchase_date'=>'2023-04-05', 'purchase_price'=>1800000,  'condition'=>'good', 'status'=>'available'],
        ];

        foreach ($assets as $data) {
            Asset::firstOrCreate(['code' => $data['code']], $data);
        }

        // 4. Sample loan
        $assetInUse = Asset::where('status', 'in_use')->first();
        if ($assetInUse) {
            Loan::firstOrCreate(
                ['asset_id' => $assetInUse->id, 'returned_at' => null],
                [
                    'borrower_name'       => 'Budi Santoso',
                    'borrower_department' => 'Marketing',
                    'borrower_phone'      => '081234567890',
                    'borrowed_at'         => now()->subDays(5),
                    'expected_return_at'  => now()->addDays(5),
                    'condition_before'    => 'good',
                    'purpose'             => 'Presentasi ke klien',
                    'approved_by'         => 'Manager IT',
                ]
            );
        }

        // 5. Sample maintenance
        $assetMaintenance = Asset::where('status', 'maintenance')->first();
        if ($assetMaintenance) {
            Maintenance::firstOrCreate(
                ['asset_id' => $assetMaintenance->id, 'status' => 'in_progress'],
                [
                    'type'         => 'corrective',
                    'description'  => 'Printer jam dan masalah paper feed',
                    'vendor_name'  => 'HP Service Center',
                    'vendor_phone' => '02198765432',
                    'cost'         => 350000,
                    'started_at'   => now()->subDays(3),
                    'performed_by' => 'Teknisi HP',
                ]
            );
        }
    }
}
