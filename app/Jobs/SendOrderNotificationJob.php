<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use App\Mail\AdminNewOrderMail;
use App\Mail\CustomerOrderConfirmationMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Number of attempts before the job is marked failed.
     */
    public int $tries = 3;

    /**
     * Backoff between attempts.
     */
    public array $backoff = [
        60,
        300,
    ];

    public function __construct(
        public int $orderId
    ) {
    }

    public function handle(): void
    {
        $order = Order::query()
            ->with([
                'user',
                'items',
                'address',
                'payment',
            ])
            ->find($this->orderId);

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
         * CUSTOMER EMAIL
         * -------------------------------------------------------------
         */
        if (
            $order->user &&
            !empty($order->user->email)
        ) {

            try {

                Mail::to($order->user->email)
                    ->send(
                        new CustomerOrderConfirmationMail($order)
                    );

            } catch (\Throwable $exception) {

                Log::error(
                    'Customer order email failed.',
                    [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'email' => $order->user->email,
                        'error' => $exception->getMessage(),
                    ]
                );
            }
        }


        /*
         * -------------------------------------------------------------
         * ADMIN EMAIL
         * -------------------------------------------------------------
         */
        $adminEmail = env(
            'SLEEPWELL_ADMIN_EMAIL'
        );

        if (!empty($adminEmail)) {

            try {

                Mail::to($adminEmail)
                    ->send(
                        new AdminNewOrderMail($order)
                    );

            } catch (\Throwable $exception) {

                Log::error(
                    'Admin order email failed.',
                    [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'email' => $adminEmail,
                        'error' => $exception->getMessage(),
                    ]
                );
            }
        }
		
		/*
 * -------------------------------------------------------------
 * CUSTOMER WHATSAPP
 * -------------------------------------------------------------
 */
$customerPhone =
    $order->address->phone
    ?? $order->user?->phone;

if (!empty($customerPhone)) {

    $whatsappService->sendOrderConfirmation(
        $order,
        $customerPhone
    );
}


/*
 * -------------------------------------------------------------
 * ADMIN WHATSAPP
 * -------------------------------------------------------------
 */
$adminWhatsApp =
    env('SLEEPWELL_ADMIN_WHATSAPP');

if (!empty($adminWhatsApp)) {

    $whatsappService->sendOrderConfirmation(
        $order,
        $adminWhatsApp
    );
}
    }
}