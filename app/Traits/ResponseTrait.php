<?php

namespace App\Traits;

trait ResponseTrait{

static function ResponseJSON( $payload, $status , $status_code=200){

  return response()->json([
            "payload" => $payload,
            "status" => $status
        ], $status_code);
    }

}


