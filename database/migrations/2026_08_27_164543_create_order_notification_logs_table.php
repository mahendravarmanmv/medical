<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_notification_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->string('channel', 30);
            // email / whatsapp

            $table->string('recipient', 255);

            $table->string('notification_type', 100);
            // order_confirmation / admin_new_order

            $table->string('status', 30)->default('pending');
            // pending / sent / failed / skipped

            $table->string('provider_message_id', 255)
                ->nullable();

            $table->text('error_message')
                ->nullable();

            $table->timestamp('sent_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'order_id',
                'channel',
                'notification_type',
            ]);

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_notification_logs');
    }
};