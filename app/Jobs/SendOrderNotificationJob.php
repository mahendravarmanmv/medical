<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\OrderNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOrderNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Number of attempts before the job is marked as failed.
     */
    public int $tries = 3;

    /**
     * Delay between retry attempts.
     */
    public array $backoff = [
        60,
        300,
    ];

    /**
     * Order ID whose notifications need to be sent.
     */
    public function __construct(
        public int $orderId
    ) {
    }

    /**
     * Process all order notifications.
     *
     * The OrderNotificationService is responsible for:
     *
     * - Customer Email
     * - Admin Email
     * - Customer WhatsApp
     * - Admin WhatsApp
     *
     * Notification failures must not affect the already
     * successfully created order.
     */
    public function handle(
        OrderNotificationService $notificationService
    ): void {

        /*
         * -------------------------------------------------------------
         * LOAD ORDER
         * -------------------------------------------------------------
         *
         * Load all relationships required by the notification
         * templates and notification services.
         */
        $order = Order::query()
            ->with([
                'user',
                'items',
                'address',
                'payment',
            ])
            ->find($this->orderId);

        /*
         * -------------------------------------------------------------
         * ORDER NOT FOUND
         * -------------------------------------------------------------
         */
        if (!$order) {

            Log::warning(
                'Order notification skipped because order was not found.',
                [
                    'order_id' => $this->orderId,
                ]
            );

            return;
        }

        /*
         * -------------------------------------------------------------
         * SEND ALL NOTIFICATIONS
         * -------------------------------------------------------------
         *
         * The service handles each channel independently.
         *
         * Therefore:
         *
         * Customer Email failure
         *      ↓
         * does NOT stop
         *      ↓
         * Admin Email / WhatsApp
         */
        $notificationService
            ->sendOrderNotifications($order);
    }
}