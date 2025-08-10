<?php

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Listeners\OrdersPerHour;
use App\Events\OrderStatusShipped;
use App\Listeners\SendNotification;
use App\Listeners\UpdateStockQuantities;
use App\Listeners\SendOrderPlacedNotification;
use App\Listeners\SendShippedSMS;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        OrderPlaced::class => [SendOrderPlacedNotification::class,
        OrdersPerHour::class,
        UpdateStockQuantities::class,
    SendNotification::class],

        OrderStatusShipped::class => [SendShippedSMS::class,
        SendNotification::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(){
        parent::boot();
    }
}
