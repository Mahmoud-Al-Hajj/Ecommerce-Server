<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateStockQuantities implements ShouldQueue
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
    public function handle(OrderPlaced $event): void{
        $order= $event->order;
        foreach ($order->items as $item) {
            Product::where('id', $item->product_id)->decrement('quantity', $item->quantity);
            //decrement wont work if the quantity is already 0
        }
    }
}
