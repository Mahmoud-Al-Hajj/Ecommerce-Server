<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\OrderPlacedMail;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderPlacedNotification implements ShouldQueue{
    use InteractsWithQueue;

    public $tries = 3;

    public function handle(OrderPlaced $event){

        Mail::to($event->order->user->email)->send(new OrderPlacedMail($event->order));

    }
}
