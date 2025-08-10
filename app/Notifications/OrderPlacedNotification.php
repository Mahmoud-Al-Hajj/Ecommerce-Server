<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
{
    use Queueable;
    public $order;

    public function __construct($order){
        $this->order = $order;

    }

    public function via(object $notifiable): array{
        return ['database'];
    }


    public function toArray(object $notifiable): array{
        return [
            'order_id' => $this->order->id,
            'message' => 'Your order has been placed successfully!'
            //
        ];
    }
    public function toDatabase(object $notifiable): array{
        return [
            'order_id' => $this->order->id,
            'message' => '  Your order has been placed successfully!'
        ];
    }
}
