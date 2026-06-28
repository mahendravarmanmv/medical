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
	Schema::table('dealers', function (Blueprint $table) {
		// e.g., '500090', '500%', 'TG%', or '%' for a catch-all fallback dealer
		$table->string('pincode_coverage_pattern', 20)->default('%')->index()->after('city');
	});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealers', function (Blueprint $table) {
            //
        });
    }
};
