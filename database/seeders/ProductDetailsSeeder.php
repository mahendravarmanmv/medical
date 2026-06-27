<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Dealer;

class ProductDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $targetProductId = 1; 

        // 1. CLEAR EXISTING DATA FOR ID 1 to prevent child row stacking on re-runs
        DB::table('product_packages')->where('product_id', $targetProductId)->delete();
        DB::table('product_images')->where('product_id', $targetProductId)->delete();

        // 2. SEED DYNAMIC PACKAGE VARIATIONS (For Product ID 1)
        DB::table('product_packages')->insert([
            [
                'product_id' => $targetProductId,
                'package_name' => 'Standard Pack',
                'price' => 72900.00,
                'emi_starting_price' => 3200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $targetProductId,
                'package_name' => 'Pack + Mask Premium',
                'price' => 76500.00,
                'emi_starting_price' => 3500,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. SEED REAL GALLERY IMAGES (For Product ID 1)
        DB::table('product_images')->insert([
            [
                'product_id' => $targetProductId,
                'image_url' => 'images/products/resmed2.webp',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $targetProductId,
                'image_url' => 'images/products/resmed3.webp',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 4. FETCH OR CREATE LIVE DEALER CORPORATE PROFILES
        $dealerOne = Dealer::firstOrCreate(['dealer_name' => 'Hyderabad Medical Systems Inc.']);
        $dealerTwo = Dealer::firstOrCreate(['dealer_name' => 'Care Medical Devices Ltd.']);

        // 5. LOOP THROUGH ALL ACTIVE PRODUCTS AND ATTACH DEALERS DYNAMICALLY
        $allProducts = Product::where('is_active', true)->get();

        foreach ($allProducts as $prod) {
            // Calculate a slightly lower competitive price relative to each product's base price
            $dealerOnePrice = $prod->price * 0.98; // 2% cheaper
            $dealerTwoPrice = $prod->price * 1.02; // 2% more expensive

            // syncWithoutDetaching links the records smoothly without resetting existing entries
            $prod->dealers()->syncWithoutDetaching([
                $dealerOne->id => [
                    'price' => $dealerOnePrice, 
                    'created_at' => now(), 
                    'updated_at' => now()
                ],
                $dealerTwo->id => [
                    'price' => $dealerTwoPrice, 
                    'created_at' => now(), 
                    'updated_at' => now()
                ]
            ]);
        }
    }
}