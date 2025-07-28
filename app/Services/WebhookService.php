<?php

namespace App\Services;

use App\Models\WebhookLog;
use Illuminate\Support\Facades\Request;

class WebhookService
{
    /**
     * Create a new class instance.
     */
       public static function process(Request $request): void
    {
        $payload = $request->all();

        $eventType = $payload['event']
                    ?? $payload['type']
                    ?? 'unknown';

        WebhookLog::create([
            'event_type' => $eventType,
            'payload'    => $payload,
        ]);
    }
}
