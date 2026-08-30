<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderNotificationLog;

class OrderNotificationLogger
{
    public function createPending(
        Order $order,
        string $channel,
        string $recipient,
        string $notificationType
    ): OrderNotificationLog {
        return OrderNotificationLog::create([
            'order_id' => $order->id,

            'channel' => $channel,

            'recipient' => $recipient,

            'notification_type' => $notificationType,

            'status' => 'pending',
        ]);
    }

    public function markSent(
        OrderNotificationLog $log,
        ?string $providerMessageId = null
    ): void {
        $log->update([
            'status' => 'sent',

            'provider_message_id' =>
                $providerMessageId,

            'sent_at' => now(),

            'error_message' => null,
        ]);
    }

    public function markFailed(
        OrderNotificationLog $log,
        string $error
    ): void {
        $log->update([
            'status' => 'failed',

            'error_message' => $error,
        ]);
    }

    public function markSkipped(
        OrderNotificationLog $log,
        string $reason
    ): void {
        $log->update([
            'status' => 'skipped',

            'error_message' => $reason,
        ]);
    }
}