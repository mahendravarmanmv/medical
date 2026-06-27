<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		Schema::create('dealer_product', function (Blueprint $table) {
		$table->id();
		// Links to your existing products.id
		$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
		// Links to your fresh dealers.id
		$table->foreignId('dealer_id')->constrained('dealers')->onDelete('cascade');

		// Crucial: The custom price this specific dealer charges for this item
		$table->decimal('price', 10, 2); 
		$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_product');
    }
};
