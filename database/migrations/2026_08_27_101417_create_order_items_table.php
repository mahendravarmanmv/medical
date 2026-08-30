<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->nullOnDelete();

            $table->foreignId('dealer_id')
                ->nullable()
                ->constrained('dealers')
                ->nullOnDelete();

            $table->string('product_name');

            $table->string('package_name')
                ->nullable();

            $table->unsignedInteger('warranty_years')
                ->nullable();

            $table->decimal('warranty_price', 12, 2)
                ->nullable();

            $table->decimal('unit_price', 12, 2);

            $table->unsignedInteger('quantity');

            $table->decimal('line_total', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};