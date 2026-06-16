<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds safely.
     */
    public function run(): void
    {
        // 1. Disable relational constraints to prevent Foreign Key lock errors
        Schema::disableForeignKeyConstraints();

        // 2. Clear existing records so you can re-run this seeder without duplicate issues
        Product::truncate();
        Category::truncate();

        // 3. Re-enable constraint mappings immediately after flushing the tables
        Schema::enableForeignKeyConstraints();

        // ==========================================
        // 4. GENERATE CATEGORIES TREE MAPPINGS
        // ==========================================
        $cpapCategory = Category::create([
            'name' => 'CPAP Machines', 
            'slug' => 'cpap-machines', 
            'icon_class' => 'fa-lungs'
        ]);
        
        $bipapCategory = Category::create([
            'name' => 'BiPAP Machines', 
            'slug' => 'bipap-machines', 
            'icon_class' => 'fa-heartbeat'
        ]);
        
        $masksCategory = Category::create([
            'name' => 'Masks & Accessories', 
            'slug' => 'masks-accessories', 
            'icon_class' => 'fa-mask'
        ]);

        // Add some subcategories for the left vertical sidebar engine
        Category::create(['name' => 'Auto CPAP', 'slug' => 'auto-cpap', 'parent_id' => $cpapCategory->id]);
        Category::create(['name' => 'Travel CPAP', 'slug' => 'travel-cpap', 'parent_id' => $cpapCategory->id]);
        Category::create(['name' => 'Fixed CPAP', 'slug' => 'fixed-cpap', 'parent_id' => $cpapCategory->id]);
        
        Category::create(['name' => 'Standard BiPAP', 'slug' => 'standard-bipap', 'parent_id' => $bipapCategory->id]);
        Category::create(['name' => 'Auto BiPAP', 'slug' => 'auto-bipap', 'parent_id' => $bipapCategory->id]);
        
        Category::create(['name' => 'Full Face Masks', 'slug' => 'full-face-masks', 'parent_id' => $masksCategory->id]);
        Category::create(['name' => 'Nasal Masks', 'slug' => 'nasal-masks', 'parent_id' => $masksCategory->id]);
        Category::create(['name' => 'Nasal Pillows', 'slug' => 'nasal-pillows', 'parent_id' => $masksCategory->id]);

        // ==========================================
        // 5. HYDRATE TARGET MATCHING MACHINE RECORDS
        // ==========================================

        // Item 1: ResMed AirSense 10 AutoSet CPAP
        Product::create([
            'category_id' => $cpapCategory->id,
            'badge_text' => 'BEST SELLER',
            'badge_color' => 'bg-success',
            'title' => 'ResMed AirSense 10 AutoSet CPAP',
            'slug' => Str::slug('ResMed AirSense 10 AutoSet CPAP'),
            'description' => 'Premium automated continuous pressure adjustments handling platform.',
            'key_features' => [
                'Advanced AutoSet Technology',
                'Built-in Humidifier',
                'Easy-Breathe Comfort',
                'Data & Sleep Tracking'
            ],
            'price' => 72900.00,
            'emi_starting_price' => 2431,
            'image_url' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500&q=80',
            'stock_quantity' => 50,
            'is_active' => true
        ]);

        // Item 2: ResMed AirCurve 10 VAuto BiPAP
        Product::create([
            'category_id' => $bipapCategory->id,
            'badge_text' => 'POPULAR',
            'badge_color' => 'bg-primary',
            'title' => 'ResMed AirCurve 10 VAuto BiPAP',
            'slug' => Str::slug('ResMed AirCurve 10 VAuto BiPAP'),
            'description' => 'Bi-level breathing support system engine with automatic pressure tracking adjustments.',
            'key_features' => [
                'VAuto Bi-level Support',
                'Built-in Humidifier',
                'Easy-Breathe Comfort',
                'Data & Sleep Tracking'
            ],
            'price' => 82900.00,
            'emi_starting_price' => 2761,
            'image_url' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500&q=80',
            'stock_quantity' => 35,
            'is_active' => true
        ]);

        // Item 3: ResMed AirMini Travel CPAP
        Product::create([
            'category_id' => $cpapCategory->id,
            'badge_text' => 'NEW',
            'badge_color' => 'bg-info text-dark',
            'title' => 'ResMed AirMini Travel CPAP',
            'slug' => Str::slug('ResMed AirMini Travel CPAP'),
            'description' => 'Ultra portable pocket sized travel continuous breathing optimization model.',
            'key_features' => [
                'Ultra-Compact & Lightweight',
                'Waterless Humidification',
                'App Controlled',
                'Travel Friendly'
            ],
            'price' => 49900.00,
            'emi_starting_price' => 1664,
            'image_url' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500&q=80',
            'stock_quantity' => 22,
            'is_active' => true
        ]);

        // Item 4: CPAP Masks & Accessories
        Product::create([
            'category_id' => $masksCategory->id,
            'badge_text' => null,
            'badge_color' => null,
            'title' => 'CPAP Masks & Accessories',
            'slug' => Str::slug('CPAP Masks & Accessories'),
            'description' => 'Comfortable medical masks range variations options selection map.',
            'key_features' => [
                'Full Face Masks',
                'Nasal Masks',
                'Nasal Pillows',
                'Tubing, Filters & More'
            ],
            'price' => 1299.00,
            'emi_starting_price' => 217,
            'image_url' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?w=500&q=80',
            'stock_quantity' => 100,
            'is_active' => true
        ]);
    }
}