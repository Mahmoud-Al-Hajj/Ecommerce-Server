<?php

namespace App\Listeners;

use App\Events\OrderStatusShipped;
use App\Models\smsLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendShippedSMS implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatusShipped $event): void{
        $order = $event->order;
        $name = $order->user->name;
        $phone = $order->user->phone;
        $message = "Thank you for your order {$name}! Your order #{$order->id} has been shipped successfully!";

        Log::info("SMS sent to {$phone}: {$message}");

        $smsLog = new smsLog();
        $smsLog->phone = $phone;
        $smsLog->message = $message;
        $smsLog->save();

    }
}
