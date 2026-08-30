<?php

namespace App\Services;

use App\Mail\AdminNewOrderMail;
use App\Mail\CustomerOrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderNotificationLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderNotificationService
{
    public function __construct(
        protected WhatsAppService $whatsappService
    ) {
    }

    /**
     * Send all order notifications.
     *
     * Notification failures are intentionally isolated
     * from the already-created order.
     */
    public function sendOrderNotifications(
        Order $order
    ): void {

        $this->sendCustomerEmail($order);

        $this->sendAdminEmail($order);

        $this->sendCustomerWhatsApp($order);

        $this->sendAdminWhatsApp($order);
    }


    protected function sendCustomerEmail(
        Order $order
    ): void {

        $recipient =
            $order->user?->email;

        if (empty($recipient)) {

            $this->log(
                $order,
                'email',
                'unknown',
                'order_confirmation',
                'skipped',
                null,
                'Customer email address is not available.'
            );

            return;
        }

        try {

            Mail::to($recipient)
                ->send(
                    new CustomerOrderConfirmationMail(
                        $order
                    )
                );

            $this->log(
                $order,
                'email',
                $recipient,
                'order_confirmation',
                'sent'
            );

        } catch (\Throwable $exception) {

            $this->log(
                $order,
                'email',
                $recipient,
                'order_confirmation',
                'failed',
                null,
                $exception->getMessage()
            );

            Log::error(
                'Customer order email failed.',
                [
                    'order_id' =>
                        $order->id,

                    'order_number' =>
                        $order->order_number,

                    'recipient' =>
                        $recipient,

                    'error' =>
                        $exception->getMessage(),
                ]
            );
        }
    }


    protected function sendAdminEmail(
        Order $order
    ): void {

        $recipient =
            config(
                'services.sleepwell.admin_email'
            );

        if (empty($recipient)) {

            $this->log(
                $order,
                'email',
                'unknown',
                'admin_new_order',
                'skipped',
                null,
                'Admin email is not configured.'
            );

            return;
        }

        try {

            Mail::to($recipient)
                ->send(
                    new AdminNewOrderMail(
                        $order
                    )
                );

            $this->log(
                $order,
                'email',
                $recipient,
                'admin_new_order',
                'sent'
            );

        } catch (\Throwable $exception) {

            $this->log(
                $order,
                'email',
                $recipient,
                'admin_new_order',
                'failed',
                null,
                $exception->getMessage()
            );

            Log::error(
                'Admin order email failed.',
                [
                    'order_id' =>
                        $order->id,

                    'order_number' =>
                        $order->order_number,

                    'recipient' =>
                        $recipient,

                    'error' =>
                        $exception->getMessage(),
                ]
            );
        }
    }


    protected function sendCustomerWhatsApp(
        Order $order
    ): void {

        $recipient =
            $order->address?->phone
            ?? $order->user?->phone;

        if (empty($recipient)) {

            $this->log(
                $order,
                'whatsapp',
                'unknown',
                'order_confirmation',
                'skipped',
                null,
                'Customer WhatsApp number is not available.'
            );

            return;
        }

        $result =
            $this->whatsappService
                ->sendOrderConfirmation(
                    $order,
                    $recipient
                );

        $this->log(
            $order,
            'whatsapp',
            $recipient,
            'order_confirmation',
            $result ? 'sent' : 'skipped'
        );
    }


    protected function sendAdminWhatsApp(
        Order $order
    ): void {

        $recipient =
            config(
                'services.sleepwell.admin_whatsapp'
            );

        if (empty($recipient)) {

            $this->log(
                $order,
                'whatsapp',
                'unknown',
                'admin_new_order',
                'skipped',
                null,
                'Admin WhatsApp number is not configured.'
            );

            return;
        }

        $result =
            $this->whatsappService
                ->sendOrderConfirmation(
                    $order,
                    $recipient
                );

        $this->log(
            $order,
            'whatsapp',
            $recipient,
            'admin_new_order',
            $result ? 'sent' : 'skipped'
        );
    }


    protected function log(
        Order $order,
        string $channel,
        string $recipient,
        string $notificationType,
        string $status,
        ?string $providerMessageId = null,
        ?string $errorMessage = null
    ): OrderNotificationLog {

        return OrderNotificationLog::create([
            'order_id' =>
                $order->id,

            'channel' =>
                $channel,

            'recipient' =>
                $recipient,

            'notification_type' =>
                $notificationType,

            'status' =>
                $status,

            'provider_message_id' =>
                $providerMessageId,

            'error_message' =>
                $errorMessage,

            'sent_at' =>
                $status === 'sent'
                    ? now()
                    : null,
        ]);
    }
}