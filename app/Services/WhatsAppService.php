<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp order confirmation.
     *
     * This service is intentionally disabled until the
     * WhatsApp provider credentials are configured.
     */
    public function sendOrderConfirmation(
        Order $order,
        string $recipient
    ): bool {

        if (!config('services.whatsapp.enabled')) {

            Log::info(
                'WhatsApp notification skipped because WhatsApp is disabled.',
                [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'recipient' => $recipient,
                ]
            );

            return false;
        }

        $phoneNumberId =
            config(
                'services.whatsapp.phone_number_id'
            );

        $accessToken =
            config(
                'services.whatsapp.access_token'
            );

        if (
            empty($phoneNumberId) ||
            empty($accessToken)
        ) {

            Log::warning(
                'WhatsApp notification skipped because credentials are missing.',
                [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]
            );

            return false;
        }

        /*
         * Normalize recipient.
         */
        $recipient = $this->normalizePhoneNumber(
            $recipient
        );

        if (!$recipient) {

            Log::warning(
                'WhatsApp notification skipped because recipient phone number is invalid.',
                [
                    'order_id' => $order->id,
                    'recipient' => $recipient,
                ]
            );

            return false;
        }

        /*
         * Build the message.
         *
         * For the initial implementation we keep the
         * message structure provider-independent.
         *
         * Once Meta credentials/templates are configured,
         * this will be mapped to an approved WhatsApp
         * template.
         */
        $message = $this->buildOrderMessage(
            $order
        );

        try {

            $url =
                rtrim(
                    config(
                        'services.whatsapp.base_url'
                    ),
                    '/'
                )
                . '/'
                . config(
                    'services.whatsapp.api_version'
                )
                . '/'
                . $phoneNumberId
                . '/messages';

            $response = Http::withToken(
                $accessToken
            )
                ->acceptJson()
                ->post(
                    $url,
                    [
                        'messaging_product' =>
                            'whatsapp',

                        'to' =>
                            $recipient,

                        'type' =>
                            'text',

                        'text' => [
                            'preview_url' =>
                                false,

                            'body' =>
                                $message,
                        ],
                    ]
                );

            if ($response->successful()) {

                Log::info(
                    'WhatsApp order notification sent successfully.',
                    [
                        'order_id' => $order->id,
                        'order_number' =>
                            $order->order_number,
                        'recipient' =>
                            $recipient,
                    ]
                );

                return true;
            }

            Log::error(
                'WhatsApp API returned an error.',
                [
                    'order_id' =>
                        $order->id,

                    'order_number' =>
                        $order->order_number,

                    'recipient' =>
                        $recipient,

                    'status' =>
                        $response->status(),

                    'response' =>
                        $response->json(),
                ]
            );

            return false;

        } catch (\Throwable $exception) {

            Log::error(
                'WhatsApp API request failed.',
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

            return false;
        }
    }


    /**
     * Build a readable order confirmation.
     */
    protected function buildOrderMessage(
        Order $order
    ): string {

        $lines = [];

        $lines[] =
            "SleepWell Order Confirmation";

        $lines[] = "";

        $lines[] =
            "Order: {$order->order_number}";

        $lines[] =
            "Payment: Cash on Delivery";

        $lines[] = "";

        $lines[] =
            "Products:";

        foreach ($order->items as $item) {

            $warranty = '';

            if ($item->warranty_years) {

                $warranty =
                    " | Warranty: "
                    . $item->warranty_years
                    . " Year"
                    . (
                        $item->warranty_years > 1
                            ? 's'
                            : ''
                    );
            }

            $lines[] =
                "- "
                . $item->product_name
                . " | Qty: "
                . $item->quantity
                . $warranty;
        }

        $lines[] = "";

        $lines[] =
            "Subtotal: ₹"
            . number_format(
                $order->subtotal,
                2
            );

        $lines[] =
            "GST: ₹"
            . number_format(
                $order->gst_amount,
                2
            );

        $lines[] =
            "Delivery: "
            . (
                (float) $order->delivery_charge > 0
                    ? '₹' . number_format(
                        $order->delivery_charge,
                        2
                    )
                    : 'FREE'
            );

        if (
            isset($order->installation_charges) &&
            (float) $order->installation_charges > 0
        ) {

            $lines[] =
                "Installation: ₹"
                . number_format(
                    $order->installation_charges,
                    2
                );
        }

        $lines[] =
            "Total: ₹"
            . number_format(
                $order->total_amount,
                2
            );

        $lines[] = "";

        $lines[] =
            "Thank you for choosing SleepWell.";

        return implode(
            "\n",
            $lines
        );
    }


    /**
     * Convert phone number into international format
     * without +, spaces, brackets or hyphens.
     *
     * Example:
     * +91 8088141246
     * becomes
     * 918088141246
     */
    protected function normalizePhoneNumber(
        string $phone
    ): ?string {

        $phone =
            preg_replace(
                '/[^0-9]/',
                '',
                $phone
            );

        if (!$phone) {
            return null;
        }

        /*
         * Indian 10-digit number.
         */
        if (
            strlen($phone) === 10 &&
            str_starts_with($phone, '6') ||
            str_starts_with($phone, '7') ||
            str_starts_with($phone, '8') ||
            str_starts_with($phone, '9')
        ) {

            return '91' . $phone;
        }

        /*
         * Already contains country code.
         */
        if (
            strlen($phone) >= 11 &&
            strlen($phone) <= 15
        ) {
            return $phone;
        }

        return null;
    }
}