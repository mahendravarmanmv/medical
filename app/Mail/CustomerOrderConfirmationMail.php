<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerOrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject(
                'SleepWell Order Confirmation - ' .
                $this->order->order_number
            )
            ->view('email.orders.customer-confirmation');
    }
}