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
	Schema::create('delivery_regions', function (Blueprint $table) {
		$table->id();
		$table->string('pincode_pattern', 20)->unique()->index(); // Explicit code or wildcard like '500%'
		$table->integer('delivery_hours')->default(24);
		$table->boolean('has_store_pickup')->default(false);
		$table->decimal('base_delivery_fee', 8, 2)->default(0.00);
		$table->integer('priority')->default(1); // Higher priority matches first (e.g., exact match vs wildcard)
		$table->timestamps();
	});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_regions');
    }
};
