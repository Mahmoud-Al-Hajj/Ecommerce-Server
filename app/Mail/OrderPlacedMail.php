<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        $order = $this->order;
        $body = '<h1>Order Confirmation</h1>';
        $body .= '<p>Hi ' . $order->user->name . ',</p>';
        $body .= '<p>Thank you for your order! Your order with ID <strong>' . $order->id . '</strong> has been placed successfully.</p>';
        $body .= '<p>Total price: <strong>' . number_format($order->total_price, 2) .'$</strong></p>';
        $body .= '<p>We will notify you again once your order has been shipped.</p>';
        $body .= '<p>Thanks,<br> From Laravel Team</p>';

        return $this->subject('Your Order Has Been Placed!')->html($body);
    }
}
