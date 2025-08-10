<?php

namespace App\Services;

use App\Models\WebhookLog;
use Illuminate\Support\Facades\Http;

class WebhookService{

    public static function MockPost($order): void{
        $payload = [
            'order' => $order->toArray(),
            'user' => $order->user->toArray(),
            'items' => $order->items->toArray(),
        ];

        Http::post('https://meet.google.com/', $payload);
        $log = new WebhookLog;
        $log->event_type = 'order_placed';
        $log->payload = json_encode($payload);
        $log->save();
    }
}
