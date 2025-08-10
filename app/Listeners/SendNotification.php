<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\User;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotification implements ShouldQueue{

use InteractsWithQueue;

    public $tries = 3;

public function handle(OrderPlaced $event){
    $user = User::find($event->order->user_id);
    if ($user) {
        $user->notify(new OrderPlacedNotification($event->order));
    }

}
}
