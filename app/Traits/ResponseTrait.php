<?php

namespace App\Traits;

trait ResponseTrait
{

    static function responseJSON($payload, $status, $status_code = 200){

        return response()->json([
            "payload" => $payload,
            "status" => $status
        ], $status_code);
    }
}
