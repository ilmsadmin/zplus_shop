<?php

namespace Zplus\Warranty\Database\Seeders;

use Illuminate\Database\Seeder;
use Zplus\Warranty\Models\WarrantyPackage;

class WarrantyPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => '1 tháng',
                'slug' => '1-thang',
                'duration_months' => 1,
                'description' => 'Gói bảo hành 1 tháng',
                'is_active' => true,
                'price' => 0,
            ],
            [
                'name' => '3 tháng',
                'slug' => '3-thang',
                'duration_months' => 3,
                'description' => 'Gói bảo hành 3 tháng',
                'is_active' => true,
                'price' => 0,
            ],
            [
                'name' => '6 tháng',
                'slug' => '6-thang',
                'duration_months' => 6,
                'description' => 'Gói bảo hành 6 tháng',
                'is_active' => true,
                'price' => 0,
            ],
            [
                'name' => '12 tháng',
                'slug' => '12-thang',
                'duration_months' => 12,
                'description' => 'Gói bảo hành 12 tháng',
                'is_active' => true,
                'price' => 0,
            ],
        ];

        foreach ($packages as $package) {
            WarrantyPackage::firstOrCreate(
                ['slug' => $package['slug']],
                $package
            );
        }
    }
}