<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $newStatus;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, string $newStatus)
    {
        $this->order = $order;
        $this->newStatus = $newStatus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        $customerName = $this->order->user->name;
        $orderId = $this->order->id;

        $emailBody = "
            <h1>Order Status Updated</h1>
            <p>Hi {$customerName},</p>
            <p>The status of your order with ID <strong>{$orderId}</strong> has been updated to: <strong>{$this->newStatus}</strong>.</p>
        ";

        if ($this->newStatus === 'Shipped') {
            $emailBody .= "<p>Your order is on its way!</p>";
        }

        $emailBody .= "<p>Thanks,<br> Laravel </p>";

        return $this->subject('Your Order Status Has Been Updated')
            ->html($emailBody);
    }
}
