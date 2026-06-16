<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('badge_text')->nullable()->after('category_id'); // e.g., 'BEST SELLER', 'POPULAR', 'NEW'
            $table->string('badge_color')->nullable()->after('badge_text'); // HTML Bootstrap classes like 'bg-success', 'bg-primary'
            $table->json('key_features')->nullable()->after('description'); // Holds our array of bullet points safely as JSON
            $table->integer('emi_starting_price')->nullable()->after('price'); // The calculated base EMI digit
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['badge_text', 'badge_color', 'key_features', 'emi_starting_price']);
        });
    }
};