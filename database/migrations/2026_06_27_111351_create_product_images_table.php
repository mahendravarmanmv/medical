<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            // Links straight to your unsigned bigint(20) id on products table
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('image_url'); // Stores the local path, e.g., 'images/products/cpap-side.webp'
            $table->integer('sort_order')->default(0); // For structuring image tray sequences
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
