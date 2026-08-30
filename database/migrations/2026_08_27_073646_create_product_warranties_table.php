<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_warranties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');

            $table->unsignedInteger('warranty_years');

            $table->decimal('price', 10, 2);

            $table->timestamps();

            $table->unique([
                'product_id',
                'warranty_years'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_warranties');
    }
};