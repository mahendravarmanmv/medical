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

        // 1. CLEAR EXISTING DATA FOR ID 1
        // Prevent child row stacking on re-runs.
        DB::table('product_packages')
            ->where('product_id', $targetProductId)
            ->delete();

        DB::table('product_images')
            ->where('product_id', $targetProductId)
            ->delete();

        DB::table('product_warranties')
            ->where('product_id', $targetProductId)
            ->delete();


        // 2. SEED DYNAMIC PACKAGE VARIATIONS
        // For Product ID 1
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


        // 3. SEED OPTIONAL WARRANTY VARIATIONS
        // For Product ID 1
        DB::table('product_warranties')->insert([
            [
                'product_id' => $targetProductId,
                'warranty_years' => 1,
                'price' => 72900.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $targetProductId,
                'warranty_years' => 2,
                'price' => 76900.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $targetProductId,
                'warranty_years' => 3,
                'price' => 80900.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        // 4. SEED REAL GALLERY IMAGES
        // For Product ID 1
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


        // 5. FETCH OR CREATE LIVE DEALER CORPORATE PROFILES
        $dealerOne = Dealer::firstOrCreate([
            'dealer_name' => 'Hyderabad Medical Systems Inc.'
        ]);

        $dealerTwo = Dealer::firstOrCreate([
            'dealer_name' => 'Care Medical Devices Ltd.'
        ]);


        // 6. LOOP THROUGH ALL ACTIVE PRODUCTS AND ATTACH DEALERS DYNAMICALLY
        $allProducts = Product::where('is_active', true)->get();

        foreach ($allProducts as $prod) {

            // Calculate a slightly lower/higher competitive price
            // relative to each product's base price.
            $dealerOnePrice = $prod->price * 0.98;
            $dealerTwoPrice = $prod->price * 1.02;

            // syncWithoutDetaching keeps existing dealer relationships.
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