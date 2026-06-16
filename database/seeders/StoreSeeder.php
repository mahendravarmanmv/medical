<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics', 'icon_class' => 'bi-phone']);
        Category::create(['name' => 'Mobiles', 'slug' => 'mobiles', 'parent_id' => $electronics->id]);
        Category::create(['name' => 'Laptops', 'slug' => 'laptops', 'parent_id' => $electronics->id]);
        Category::create(['name' => 'Smart Watches', 'slug' => 'smart-watches', 'parent_id' => $electronics->id]);

        $fashion = Category::create(['name' => 'Fashion', 'slug' => 'fashion', 'icon_class' => 'bi-bag']);
        Category::create(['name' => 'Men', 'slug' => 'men', 'parent_id' => $fashion->id]);
        Category::create(['name' => 'Women', 'slug' => 'women', 'parent_id' => $fashion->id]);

        Product::create([
            'category_id' => $electronics->id,
            'title' => 'Running Shoes',
            'slug' => Str::slug('Running Shoes'),
            'description' => 'Lightweight and comfortable sports shoes suitable for daily running and gym workouts.',
            'price' => 2499.00,
            'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff',
            'stock_quantity' => 10,
            'is_active' => true
        ]);
    }
}