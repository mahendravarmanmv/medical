<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('order_number', 50)->unique();

            $table->string('status', 30)
                ->default('pending')
                ->index();

            $table->decimal('subtotal', 12, 2)->default(0);

            $table->decimal('gst_amount', 12, 2)->default(0);

            $table->decimal('delivery_charge', 12, 2)->default(0);

            $table->decimal('discount_amount', 12, 2)->default(0);

            $table->decimal('total_amount', 12, 2)->default(0);

            $table->string('payment_method', 30)
                ->default('cod');

            $table->string('payment_status', 30)
                ->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};