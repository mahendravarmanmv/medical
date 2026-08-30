<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNewOrderMail extends Mailable
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
                'New SleepWell COD Order - ' .
                $this->order->order_number
            )
            ->view('email.orders.admin-new-order');
    }
}