<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\OrderPerHour;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrdersPerHour implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public $tries = 3;
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void{
        $order = $event->order;
        $now = now();
        $hour = (int)$now->format('H').':00:00';
        $existing = OrderPerHour::where('date', $now->toDateString())->where('time', $hour)->first();
        if ($existing) {
            $existing->order_count += 1;
            $existing->revenue += $order->total_price;
            $existing->save();
        } else {
            $record = new OrderPerHour;
            $record->date = $now->toDateString();
            $record->time = $hour;
            $record->order_count = 1;
            $record->revenue = $order->total_price;
            $record->save();
        }
    }
}
