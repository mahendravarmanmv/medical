<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogisticsRegionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('delivery_regions')->truncate();

        DB::table('delivery_regions')->insert([
            [
                'pincode_pattern' => '500090',
                'delivery_hours' => 2,
                'has_store_pickup' => true,
                'base_delivery_fee' => 0.00,
                'priority' => 10, // Highest priority for exact match
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'pincode_pattern' => '500%', // All other Hyderabad / Secunderabad codes starting with 500
                'delivery_hours' => 24,
                'has_store_pickup' => true,
                'base_delivery_fee' => 50.00,
                'priority' => 5,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'pincode_pattern' => '50%', // Broad catch-all for remaining Telangana regions
                'delivery_hours' => 48,
                'has_store_pickup' => false,
                'base_delivery_fee' => 100.00,
                'priority' => 2,
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);
    }
}