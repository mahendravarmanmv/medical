<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove user pincode
        if (Schema::hasColumn('users', 'pincode')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('pincode');
            });
        }

        // Remove pincode-based dealer coverage
        if (
            Schema::hasTable('dealers') &&
            Schema::hasColumn('dealers', 'pincode_coverage_pattern')
        ) {
            Schema::table('dealers', function (Blueprint $table) {
                $table->dropColumn('pincode_coverage_pattern');
            });
        }

        // Remove pincode-based delivery rules
        Schema::dropIfExists('delivery_regions');
    }

    public function down(): void
    {
        // Restore users.pincode
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'pincode')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('pincode', 10)
                    ->nullable()
                    ->index()
                    ->after('password');
            });
        }

        // Restore dealer pincode coverage
        if (
            Schema::hasTable('dealers') &&
            !Schema::hasColumn('dealers', 'pincode_coverage_pattern')
        ) {
            Schema::table('dealers', function (Blueprint $table) {
                $table->string('pincode_coverage_pattern', 20)
                    ->default('%')
                    ->index()
                    ->after('city');
            });
        }

        // Restore delivery regions table
        if (!Schema::hasTable('delivery_regions')) {
            Schema::create('delivery_regions', function (Blueprint $table) {
                $table->id();
                $table->string('pincode_pattern', 20)->unique()->index();
                $table->integer('delivery_hours')->default(24);
                $table->boolean('has_store_pickup')->default(false);
                $table->decimal('base_delivery_fee', 8, 2)->default(0.00);
                $table->integer('priority')->default(1);
                $table->timestamps();
            });
        }
    }
};